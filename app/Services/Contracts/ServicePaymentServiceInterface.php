<?php

namespace App\Services\Contracts;

use App\Models\User;
use App\Models\BoardingHouse;
use App\Models\ServicePayment;
use App\Models\Payment;

interface ServicePaymentServiceInterface
{
    /**
     * Process service payment (priority: points, fallback: cash)
     * @param array|null $metadata Optional data (e.g. ['listing_days' => 30] for publish_listing)
     */
    public function processServicePayment(
        User $user,
        string $serviceType,
        string $serviceName,
        int $pointsCost,
        ?BoardingHouse $boardingHouse = null,
        ?string $description = null,
        ?array $metadata = null
    ): ServicePayment;

    /**
     * Complete service payment after cash payment is confirmed
     */
    public function completeServicePayment(ServicePayment $servicePayment, Payment $payment): ServicePayment;

    /**
     * Cancel service payment
     */
    public function cancelServicePayment(ServicePayment $servicePayment): bool;

    /**
     * Get service cost in points
     */
    public function getServiceCost(string $serviceType): int;
}
