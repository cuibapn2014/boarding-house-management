<?php

namespace App\Services;

use App\Models\User;
use App\Models\BoardingHouse;
use App\Models\ServicePayment;
use App\Models\Payment;
use App\Services\Contracts\ServicePaymentServiceInterface;
use App\Services\Contracts\PointServiceInterface;
use App\Services\Contracts\PaymentServiceInterface;
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
     * Process service payment (chỉ thanh toán bằng điểm).
     * @param array|null $metadata Optional (e.g. ['listing_days' => 30] for publish_listing)
     */
    public function processServicePayment(
        User $user,
        string $serviceType,
        string $serviceName,
        int $pointsCost,
        ?BoardingHouse $boardingHouse = null,
        ?string $description = null,
        ?array $metadata = null
    ): ServicePayment {
        return DB::transaction(function () use ($user, $serviceType, $serviceName, $pointsCost, $boardingHouse, $description, $metadata) {
            $hasEnoughPoints = $this->pointService->hasEnoughPoints($user, $pointsCost);

            // Tạm thời chỉ hỗ trợ thanh toán bằng điểm
            if (! $hasEnoughPoints) {
                throw new \Exception('Bạn không đủ điểm. Vui lòng nạp thêm điểm để sử dụng dịch vụ.');
            }

            $servicePayment = ServicePayment::create([
                'user_id' => $user->id,
                'service_type' => $serviceType,
                'service_name' => $serviceName,
                'points_cost' => $pointsCost,
                'cash_amount' => null,
                'payment_method' => ServicePayment::METHOD_POINTS,
                'boarding_house_id' => $boardingHouse?->id,
                'status' => ServicePayment::STATUS_PENDING,
                'description' => $description ?? "Thanh toán dịch vụ: {$serviceName}",
                'metadata' => $metadata,
            ]);

            $this->pointService->deductPoints(
                $user,
                $pointsCost,
                "Thanh toán dịch vụ: {$serviceName}",
                $servicePayment
            );

            $servicePayment->update([
                'status' => ServicePayment::STATUS_COMPLETED,
                'completed_at' => Carbon::now(),
            ]);

            if ($serviceType === ServicePayment::SERVICE_PUBLISH_LISTING && $boardingHouse && ($metadata['listing_days'] ?? null)) {
                $this->applyPublishListingToBoardingHouse($boardingHouse, (int) $metadata['listing_days']);
            }

            if ($serviceType === ServicePayment::SERVICE_PUSH_LISTING && $boardingHouse) {
                $boardingHouse->update(['pushed_at' => Carbon::now()]);
            }

            Log::info("Service payment completed with points", [
                'service_payment_id' => $servicePayment->id,
                'user_id' => $user->id,
                'service_type' => $serviceType,
                'points_cost' => $pointsCost,
            ]);

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

            // Apply publish listing to boarding house when paid by cash
            if ($servicePayment->service_type === ServicePayment::SERVICE_PUBLISH_LISTING && $servicePayment->boarding_house_id) {
                $boardingHouse = BoardingHouse::find($servicePayment->boarding_house_id);
                $listingDays = (int) ($servicePayment->metadata['listing_days'] ?? 0);
                if ($boardingHouse && $listingDays > 0) {
                    $this->applyPublishListingToBoardingHouse($boardingHouse, $listingDays);
                }
            }

            Log::info("Service payment completed with cash", [
                'service_payment_id' => $servicePayment->id,
                'payment_id' => $payment->id,
            ]);

            return $servicePayment->fresh();
        });
    }

    /**
     * Set boarding house as published with listing duration and expiry
     */
    protected function applyPublishListingToBoardingHouse(BoardingHouse $boardingHouse, int $listingDays): void
    {
        $now = Carbon::now();
        $boardingHouse->update([
            'is_publish' => true,
            'listing_days' => $listingDays,
            'published_at' => $now,
            'expires_at' => $now->copy()->addDays($listingDays),
        ]);
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
}
