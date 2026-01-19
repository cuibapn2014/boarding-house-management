<?php

namespace App\Strategies\Contracts;

use App\Models\BoardingHouse;
use App\Models\Appointment;
use App\Models\User;

interface PaymentStrategyInterface
{
    /**
     * Calculate payment amount
     */
    public function calculateAmount(?BoardingHouse $boardingHouse = null, ?Appointment $appointment = null): float;

    /**
     * Generate payment description
     */
    public function generateDescription(?BoardingHouse $boardingHouse = null, ?Appointment $appointment = null): string;

    /**
     * Generate payment metadata
     */
    public function generateMetadata(?BoardingHouse $boardingHouse = null, ?Appointment $appointment = null): array;

    /**
     * Get payment type
     */
    public function getPaymentType(): string;
}
