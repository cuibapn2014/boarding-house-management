<?php

namespace App\Services\Contracts;

use App\Models\User;
use App\Models\PointPackage;
use App\Models\PointTransaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PointServiceInterface
{
    /**
     * Top up points for user from package
     */
    public function topUpPoints(User $user, PointPackage $package, ?int $paymentId = null): PointTransaction;

    /**
     * Deduct points from user
     */
    public function deductPoints(User $user, int $points, string $description, $reference = null): PointTransaction;

    /**
     * Refund points to user
     */
    public function refundPoints(User $user, int $points, string $description, $reference = null): PointTransaction;

    /**
     * Get user point balance
     */
    public function getBalance(User $user): float;

    /**
     * Check if user has enough points
     */
    public function hasEnoughPoints(User $user, int $points): bool;

    /**
     * Get transaction history for user
     */
    public function getTransactionHistory(User $user, ?int $perPage = 15): LengthAwarePaginator;

    /**
     * Get transaction history for all users (admin only)
     */
    public function getTransactionHistoryForAllUsers(?int $perPage = 20, ?int $userId = null): LengthAwarePaginator;

    /**
     * Admin: add points to user (logs full history)
     */
    public function addPointsByAdmin(User $targetUser, int $points, string $reason, User $adminUser): PointTransaction;

    /**
     * Admin: subtract points from user (logs full history)
     */
    public function subtractPointsByAdmin(User $targetUser, int $points, string $reason, User $adminUser): PointTransaction;

    /**
     * Get all active point packages
     */
    public function getActivePackages(): Collection;
}
