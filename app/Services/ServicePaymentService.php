<?php

namespace App\Services;

use App\Models\User;
use App\Models\BoardingHouse;
use App\Models\ServicePayment;
use App\Models\Payment;
use App\Services\Contracts\ServicePaymentServiceInterface;
use App\Services\Contracts\PointServiceInterface;
use App\Services\Contracts\PaymentServiceInterface;
use App\DTOs\PaymentData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ServicePaymentService implements ServicePaymentServiceInterface
{
    /**
     * Service costs in points
     */
    protected array $serviceCosts = [
        ServicePayment::SERVICE_PUSH_LISTING => 5,
        ServicePayment::SERVICE_PRIORITY_LISTING => 10,
        ServicePayment::SERVICE_EXTEND_LISTING => 3,
    ];

    public function __construct(
        protected PointServiceInterface $pointService,
        protected PaymentServiceInterface $paymentService
    ) {}

    /**
     * Process service payment (priority: points, fallback: cash)
     */
    public function processServicePayment(
        User $user,
        string $serviceType,
        string $serviceName,
        int $pointsCost,
        ?BoardingHouse $boardingHouse = null,
        ?string $description = null
    ): ServicePayment {
        return DB::transaction(function () use ($user, $serviceType, $serviceName, $pointsCost, $boardingHouse, $description) {
            $hasEnoughPoints = $this->pointService->hasEnoughPoints($user, $pointsCost);
            
            // Determine payment method
            $paymentMethod = $hasEnoughPoints 
                ? ServicePayment::METHOD_POINTS 
                : ServicePayment::METHOD_CASH;

            // Create service payment record
            $servicePayment = ServicePayment::create([
                'user_id' => $user->id,
                'service_type' => $serviceType,
                'service_name' => $serviceName,
                'points_cost' => $pointsCost,
                'cash_amount' => $hasEnoughPoints ? null : $this->pointsToCash($pointsCost),
                'payment_method' => $paymentMethod,
                'boarding_house_id' => $boardingHouse?->id,
                'status' => $hasEnoughPoints ? ServicePayment::STATUS_PENDING : ServicePayment::STATUS_PENDING,
                'description' => $description ?? "Thanh toán dịch vụ: {$serviceName}",
            ]);

            // If user has enough points, deduct immediately
            if ($hasEnoughPoints) {
                $this->pointService->deductPoints(
                    $user,
                    $pointsCost,
                    "Thanh toán dịch vụ: {$serviceName}",
                    $servicePayment
                );

                // Mark as completed
                $servicePayment->update([
                    'status' => ServicePayment::STATUS_COMPLETED,
                    'completed_at' => Carbon::now(),
                ]);

                Log::info("Service payment completed with points", [
                    'service_payment_id' => $servicePayment->id,
                    'user_id' => $user->id,
                    'service_type' => $serviceType,
                    'points_cost' => $pointsCost,
                ]);
            } else {
                // Create cash payment request
                $payment = $this->paymentService->createPayment(
                    PaymentData::fromArray([
                        'payment_type' => Payment::TYPE_SERVICE_PAYMENT,
                        'amount' => $this->pointsToCash($pointsCost),
                        'user_id' => $user->id,
                        'boarding_house_id' => $boardingHouse?->id,
                        'description' => "Thanh toán dịch vụ: {$serviceName}",
                        'expires_at' => Carbon::now()->addDays(1)->toDateTimeString(),
                    ])
                );

                $servicePayment->update([
                    'payment_id' => $payment->id,
                ]);

                Log::info("Service payment requires cash payment", [
                    'service_payment_id' => $servicePayment->id,
                    'user_id' => $user->id,
                    'payment_id' => $payment->id,
                    'cash_amount' => $this->pointsToCash($pointsCost),
                ]);
            }

            return $servicePayment->fresh();
        });
    }

    /**
     * Complete service payment after cash payment is confirmed
     */
    public function completeServicePayment(ServicePayment $servicePayment, Payment $payment): ServicePayment
    {
        return DB::transaction(function () use ($servicePayment, $payment) {
            if ($servicePayment->isCompleted()) {
                return $servicePayment;
            }

            $servicePayment->update([
                'status' => ServicePayment::STATUS_COMPLETED,
                'payment_id' => $payment->id,
                'completed_at' => Carbon::now(),
            ]);

            Log::info("Service payment completed with cash", [
                'service_payment_id' => $servicePayment->id,
                'payment_id' => $payment->id,
            ]);

            return $servicePayment->fresh();
        });
    }

    /**
     * Cancel service payment
     */
    public function cancelServicePayment(ServicePayment $servicePayment): bool
    {
        if ($servicePayment->isCompleted()) {
            return false;
        }

        // If points were deducted, refund them
        if ($servicePayment->payment_method === ServicePayment::METHOD_POINTS) {
            $this->pointService->refundPoints(
                $servicePayment->user,
                $servicePayment->points_cost,
                "Hoàn tiền dịch vụ đã hủy: {$servicePayment->service_name}",
                $servicePayment
            );
        }

        $servicePayment->update([
            'status' => ServicePayment::STATUS_CANCELLED,
        ]);

        return true;
    }

    /**
     * Get service cost in points
     */
    public function getServiceCost(string $serviceType): int
    {
        return $this->serviceCosts[$serviceType] ?? 0;
    }

    /**
     * Convert points to cash (1 point = 1000 VND)
     */
    protected function pointsToCash(int $points): float
    {
        return $points * 1000;
    }
}
