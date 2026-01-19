<?php

namespace App\Services;

use App\Models\User;
use App\Models\PointPackage;
use App\Models\PointTransaction;
use App\Services\Contracts\PointServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PointService implements PointServiceInterface
{
    /**
     * Top up points for user from package
     */
    public function topUpPoints(User $user, PointPackage $package, ?int $paymentId = null): PointTransaction
    {
        return DB::transaction(function () use ($user, $package, $paymentId) {
            $totalPoints = $package->total_points;
            $balanceBefore = $user->points ?? 0;
            $balanceAfter = $balanceBefore + $totalPoints;

            // Update user points
            $user->increment('points', $totalPoints);
            $user->refresh();

            // Create transaction record
            $transaction = PointTransaction::create([
                'user_id' => $user->id,
                'transaction_type' => PointTransaction::TYPE_TOP_UP,
                'amount' => $totalPoints,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => PointPackage::class,
                'reference_id' => $package->id,
                'description' => "Nạp điểm từ gói: {$package->name}",
                'metadata' => [
                    'package_id' => $package->id,
                    'package_name' => $package->name,
                    'points' => $package->points,
                    'bonus_points' => $package->bonus_points,
                    'payment_id' => $paymentId,
                ],
            ]);

            Log::info("Points topped up", [
                'user_id' => $user->id,
                'package_id' => $package->id,
                'points' => $totalPoints,
                'transaction_id' => $transaction->id,
            ]);

            return $transaction;
        });
    }

    /**
     * Deduct points from user
     */
    public function deductPoints(User $user, int $points, string $description, $reference = null): PointTransaction
    {
        return DB::transaction(function () use ($user, $points, $description, $reference) {
            if (!$this->hasEnoughPoints($user, $points)) {
                throw new \Exception("Không đủ điểm để thực hiện giao dịch");
            }

            $balanceBefore = $user->points ?? 0;
            $balanceAfter = $balanceBefore - $points;

            // Update user points
            $user->decrement('points', $points);
            $user->refresh();

            // Create transaction record
            $transaction = PointTransaction::create([
                'user_id' => $user->id,
                'transaction_type' => PointTransaction::TYPE_DEDUCTION,
                'amount' => -$points,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference?->id,
                'description' => $description,
                'metadata' => $reference ? [
                    'reference_type' => get_class($reference),
                    'reference_id' => $reference->id,
                ] : null,
            ]);

            Log::info("Points deducted", [
                'user_id' => $user->id,
                'points' => $points,
                'transaction_id' => $transaction->id,
            ]);

            return $transaction;
        });
    }

    /**
     * Refund points to user
     */
    public function refundPoints(User $user, int $points, string $description, $reference = null): PointTransaction
    {
        return DB::transaction(function () use ($user, $points, $description, $reference) {
            $balanceBefore = $user->points ?? 0;
            $balanceAfter = $balanceBefore + $points;

            // Update user points
            $user->increment('points', $points);
            $user->refresh();

            // Create transaction record
            $transaction = PointTransaction::create([
                'user_id' => $user->id,
                'transaction_type' => PointTransaction::TYPE_REFUND,
                'amount' => $points,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference?->id,
                'description' => $description,
                'metadata' => $reference ? [
                    'reference_type' => get_class($reference),
                    'reference_id' => $reference->id,
                ] : null,
            ]);

            Log::info("Points refunded", [
                'user_id' => $user->id,
                'points' => $points,
                'transaction_id' => $transaction->id,
            ]);

            return $transaction;
        });
    }

    /**
     * Get user point balance
     */
    public function getBalance(User $user): float
    {
        return (float) ($user->points ?? 0);
    }

    /**
     * Check if user has enough points
     */
    public function hasEnoughPoints(User $user, int $points): bool
    {
        return $this->getBalance($user) >= $points;
    }

    /**
     * Get transaction history for user
     */
    public function getTransactionHistory(User $user, ?int $perPage = 15): LengthAwarePaginator
    {
        return PointTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all active point packages
     */
    public function getActivePackages(): Collection
    {
        return PointPackage::active()->ordered()->get();
    }
}
