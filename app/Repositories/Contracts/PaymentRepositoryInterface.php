<?php

namespace App\Repositories\Contracts;

use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PaymentRepositoryInterface
{
    /**
     * Find payment by code
     */
public function findByCode(string $paymentCode): ?Payment;

    /**
     * Find payment by ID
     */
    public function findById(int $id): ?Payment;

    /**
     * Get payments by user ID
     */
    public function getByUserId(int $userId, ?string $status = null): Collection;

    /**
     * Get payments by status
     */
    public function getByStatus(string $status): Collection;

    /**
     * Get paginated payments
     */
    public function paginate(int $perPage = 15, ?string $status = null): LengthAwarePaginator;

    /**
     * Create new payment
     */
    public function create(array $data): Payment;

    /**
     * Update payment
     */
    public function update(Payment $payment, array $data): bool;

    /**
     * Delete payment
     */
    public function delete(Payment $payment): bool;

    /**
     * Check if payment code exists
     */
    public function codeExists(string $code): bool;

    /**
     * Get expired payments
     */
    public function getExpiredPayments(): Collection;

    /**
     * Get payments by boarding house
     */
    public function getByBoardingHouse(int $boardingHouseId, ?string $status = null): Collection;

    /**
     * Get payments by appointment
     */
    public function getByAppointment(int $appointmentId, ?string $status = null): Collection;
}
