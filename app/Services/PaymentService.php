<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\BoardingHouse;
use App\Models\Appointment;
use App\Models\User;
use App\DTOs\PaymentData;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Services\Contracts\PaymentServiceInterface;
use App\Strategies\PaymentStrategyFactory;
use App\Events\PaymentCreated;
use App\Events\PaymentCompleted;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService implements PaymentServiceInterface
{
    public function __construct(
        protected PaymentRepositoryInterface $repository,
        protected PaymentStrategyFactory $strategyFactory
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
        return DB::transaction(function () use ($paymentCode, $sepayData) {
            $payment = $this->repository->findByCode($paymentCode);

            if (!$payment) {
                Log::warning("Payment not found for code: {$paymentCode}");
                return null;
            }

            if ($payment->isCompleted()) {
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

            event(new PaymentCompleted($payment));

            return $payment;
        });
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
