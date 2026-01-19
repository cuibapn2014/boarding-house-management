<?php

namespace App\Services\Contracts;

use App\Models\Payment;
use App\Models\BoardingHouse;
use App\Models\Appointment;
use App\Models\User;
use App\DTOs\PaymentData;

interface PaymentServiceInterface
{
    /**
     * Create a new payment
     */
    public function createPayment(PaymentData $data): Payment;

    /**
     * Create deposit payment for boarding house
     */
    public function createDepositPayment(
        BoardingHouse $boardingHouse,
        User $user,
        float $amount,
        ?string $description = null
    ): Payment;

    /**
     * Create booking fee payment for appointment
     */
    public function createBookingFeePayment(
        Appointment $appointment,
        float $amount,
        ?string $description = null
    ): Payment;

    /**
     * Create rent payment
     */
    public function createRentPayment(
        BoardingHouse $boardingHouse,
        User $user,
        float $amount,
        ?string $description = null
    ): Payment;

    /**
     * Process payment completion from SePay webhook
     */
    public function processPaymentCompletion(string $paymentCode, array $sepayData): ?Payment;

    /**
     * Get payment by code
     */
    public function getPaymentByCode(string $paymentCode): ?Payment;

    /**
     * Get user payments
     */
    public function getUserPayments(?int $userId = null, ?string $status = null);

    /**
     * Cancel payment
     */
    public function cancelPayment(Payment $payment): bool;
}
