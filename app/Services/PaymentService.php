<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\BoardingHouse;
use App\Models\Appointment;
use App\Models\User;
use App\Models\PointPackage;
use App\DTOs\PaymentData;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Services\Contracts\PaymentServiceInterface;
use App\Services\Contracts\PointServiceInterface;
use App\Strategies\PaymentStrategyFactory;
use App\Events\PaymentCreated;
use App\Events\PaymentCompleted;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SePay\SePayClient;

class PaymentService implements PaymentServiceInterface
{
    public function __construct(
        protected PaymentRepositoryInterface $repository,
        protected PaymentStrategyFactory $strategyFactory,
        protected PointServiceInterface $pointService
    ) {}

    /**
     * Create a new payment
     */
    public function createPayment(PaymentData $data): Payment
    {
        return DB::transaction(function () use ($data) {
            $paymentData = $data->toArray();
            $paymentData['payment_code'] = $this->generateUniquePaymentCode();
            $paymentData['status'] = Payment::STATUS_PENDING;
            $paymentData['expires_at'] = $data->expiresAt ?? Carbon::now()->addDays(1);

            $payment = $this->repository->create($paymentData);

            event(new PaymentCreated($payment));

            return $payment;
        });
    }

    /**
     * Create deposit payment for boarding house
     */
    public function createDepositPayment(
        BoardingHouse $boardingHouse,
        User $user,
        float $amount,
        ?string $description = null
    ): Payment {
        $strategy = $this->strategyFactory->create(Payment::TYPE_DEPOSIT);
        
        $paymentData = new PaymentData(
            paymentType: Payment::TYPE_DEPOSIT,
            amount: $amount,
            userId: $user->id,
            boardingHouseId: $boardingHouse->id,
            currency: 'VND',
            description: $description ?? $strategy->generateDescription($boardingHouse),
            metadata: $strategy->generateMetadata($boardingHouse)
        );

        return $this->createPayment($paymentData);
    }

    /**
     * Create booking fee payment for appointment
     */
    public function createBookingFeePayment(
        Appointment $appointment,
        float $amount,
        ?string $description = null
    ): Payment {
        $strategy = $this->strategyFactory->create(Payment::TYPE_BOOKING_FEE);
        $boardingHouse = $appointment->boardingHouse;
        
        $paymentData = new PaymentData(
            paymentType: Payment::TYPE_BOOKING_FEE,
            amount: $amount,
            userId: auth()->id(),
            boardingHouseId: $appointment->boarding_house_id,
            appointmentId: $appointment->id,
            currency: 'VND',
            description: $description ?? $strategy->generateDescription($boardingHouse, $appointment),
            metadata: $strategy->generateMetadata($boardingHouse, $appointment)
        );

        return $this->createPayment($paymentData);
    }

    /**
     * Create rent payment
     */
    public function createRentPayment(
        BoardingHouse $boardingHouse,
        User $user,
        float $amount,
        ?string $description = null
    ): Payment {
        $strategy = $this->strategyFactory->create(Payment::TYPE_RENT);
        
        $paymentData = new PaymentData(
            paymentType: Payment::TYPE_RENT,
            amount: $amount,
            userId: $user->id,
            boardingHouseId: $boardingHouse->id,
            currency: 'VND',
            description: $description ?? $strategy->generateDescription($boardingHouse),
            metadata: $strategy->generateMetadata($boardingHouse)
        );

        return $this->createPayment($paymentData);
    }

    /**
     * Process payment completion from SePay webhook
     */
    public function processPaymentCompletion(string $paymentCode, array $sepayData): ?Payment
    {
        $merchantId = env('SEPAY_MERCHANT_ID');
        $secretKey = env('SEPAY_SECRET');
        $environment = env('SEPAY_ENV');
        $sepay = new SePayClient(
            $merchantId,
            $secretKey,
            $environment
        );

        $orders = $sepay->orders()->retrieve($paymentCode);
        $order = null;
        if ($orders) {
            $order = collect($orders['data']);
        }

        if($order->empty() || $order['order_status'] !== 'CAPTURED') {
            Log::warning("Payment not found for code: {$paymentCode}");
            return null;
        }

        return DB::transaction(function () use ($paymentCode, $sepayData, $order) {
            $payment = $this->repository->findByCode($order['order_invoice_number']);

            if (!$payment) {
                Log::warning("Payment not found for code: {$paymentCode}");
                return null;
            }

            if ($payment->isCompleted() && $order['order_status'] === 'CAPTURED') {
                Log::info("Payment already completed: {$paymentCode}");
                return $payment;
            }

            $updateData = [
                'status' => Payment::STATUS_COMPLETED,
                'sepay_reference_code' => $sepayData['referenceCode'] ?? null,
                'sepay_transaction_id' => $sepayData['id'] ?? null,
                'paid_at' => isset($sepayData['transactionDate']) 
                    ? Carbon::parse($sepayData['transactionDate']) 
                    : Carbon::now(),
            ];

            // Merge metadata
            $metadata = $payment->metadata ?? [];
            $metadata['sepay_data'] = $sepayData;
            $updateData['metadata'] = $metadata;

            $this->repository->update($payment, $updateData);
            $payment->refresh();

            // Process point top-up if payment type is point top-up
            if ($payment->payment_type === Payment::TYPE_POINT_TOP_UP && $order['order_status'] === 'CAPTURED') {
                $this->processPointTopUpInTransaction($payment);
            }

            event(new PaymentCompleted($payment));

            return $payment;
        });
    }

    /**
     * Process point top-up within the same transaction
     */
    protected function processPointTopUpInTransaction(Payment $payment): void
    {
        try {
            $user = $payment->user;
            if (!$user) {
                Log::warning('Payment completed but user not found for point top-up', [
                    'payment_id' => $payment->id,
                    'payment_code' => $payment->payment_code,
                ]);
                return;
            }

            $metadata = $payment->metadata ?? [];
            $packageId = $metadata['package_id'] ?? null;

            if (!$packageId) {
                Log::warning('Point top-up payment completed but package_id not found in metadata', [
                    'payment_id' => $payment->id,
                    'payment_code' => $payment->payment_code,
                ]);
                return;
            }

            $package = PointPackage::find($packageId);
            if (!$package) {
                Log::warning('Point package not found for top-up', [
                    'payment_id' => $payment->id,
                    'package_id' => $packageId,
                ]);
                return;
            }

            // Top up points within the same transaction
            $this->pointService->topUpPoints($user, $package, $payment->id);

            Log::info('Points topped up after payment completion (in transaction)', [
                'payment_id' => $payment->id,
                'payment_code' => $payment->payment_code,
                'user_id' => $user->id,
                'package_id' => $package->id,
                'points' => $package->total_points,
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing point top-up in transaction', [
                'payment_id' => $payment->id,
                'payment_code' => $payment->payment_code,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Re-throw to rollback transaction if needed
            throw $e;
        }
    }

    /**
     * Get payment by code
     */
    public function getPaymentByCode(string $paymentCode): ?Payment
    {
        return $this->repository->findByCode($paymentCode);
    }

    /**
     * Get user payments
     */
    public function getUserPayments(?int $userId = null, ?string $status = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->repository->getByUserId($userId, $status);
    }

    /**
     * Cancel payment
     */
    public function cancelPayment(Payment $payment): bool
    {
        if ($payment->isCompleted()) {
            return false;
        }

        return $this->repository->update($payment, [
            'status' => Payment::STATUS_CANCELLED
        ]);
    }

    /**
     * Generate unique payment code with retry mechanism
     */
    protected function generateUniquePaymentCode(int $maxRetries = 10): string
    {
        $prefix = config('sepay.pattern', 'SE');
        $attempts = 0;

        do {
            $code = $prefix . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
            $attempts++;
            
            if ($attempts >= $maxRetries) {
                throw new \RuntimeException('Failed to generate unique payment code after ' . $maxRetries . ' attempts');
            }
        } while ($this->repository->codeExists($code));

        return $code;
    }
}
