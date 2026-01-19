<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class PaymentRepository implements PaymentRepositoryInterface
{
    protected const CACHE_TTL = 3600; // 1 hour
    protected const CODE_CACHE_PREFIX = 'payment_code_exists:';

    public function findByCode(string $paymentCode): ?Payment
    {
        return Cache::remember(
            "payment:code:{$paymentCode}",
            self::CACHE_TTL,
            fn() => Payment::with(['user', 'boardingHouse', 'appointment'])
                ->where('payment_code', $paymentCode)
                ->first()
        );
    }

    public function findById(int $id): ?Payment
    {
        return Payment::with(['user', 'boardingHouse', 'appointment'])->find($id);
    }

    public function getByUserId(int $userId, ?string $status = null): Collection
    {
        $cacheKey = "payments:user:{$userId}" . ($status ? ":status:{$status}" : '');
        
        return Cache::remember(
            $cacheKey,
            self::CACHE_TTL / 2, // Shorter cache for user lists
            function () use ($userId, $status) {
                $query = Payment::with(['boardingHouse', 'appointment'])
                    ->where('user_id', $userId)
                    ->orderBy('created_at', 'desc');

                if ($status) {
                    $query->where('status', $status);
                }

                return $query->get();
            }
        );
    }

    public function getByStatus(string $status): Collection
    {
        return Payment::with(['user', 'boardingHouse', 'appointment'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function paginate(int $perPage = 15, ?string $status = null): LengthAwarePaginator
    {
        $query = Payment::with(['user', 'boardingHouse', 'appointment'])
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }

    public function create(array $data): Payment
    {
        $payment = Payment::create($data);
        
        // Clear relevant caches
        $this->clearUserCache($payment->user_id);
        
        return $payment;
    }

    public function update(Payment $payment, array $data): bool
    {
        $result = $payment->update($data);
        
        if ($result) {
            // Clear caches
            Cache::forget("payment:code:{$payment->payment_code}");
            Cache::forget("payment:id:{$payment->id}");
            $this->clearUserCache($payment->user_id);
        }
        
        return $result;
    }

    public function delete(Payment $payment): bool
    {
        $userId = $payment->user_id;
        $code = $payment->payment_code;
        
        $result = $payment->delete();
        
        if ($result) {
            Cache::forget("payment:code:{$code}");
            Cache::forget("payment:id:{$payment->id}");
            $this->clearUserCache($userId);
        }
        
        return $result;
    }

    public function codeExists(string $code): bool
    {
        return Cache::remember(
            self::CODE_CACHE_PREFIX . $code,
            3600,
            fn() => Payment::where('payment_code', $code)->exists()
        );
    }

    public function getExpiredPayments(): Collection
    {
        return Payment::where('status', Payment::STATUS_PENDING)
            ->where('expires_at', '<', now())
            ->get();
    }

    public function getByBoardingHouse(int $boardingHouseId, ?string $status = null): Collection
    {
        $query = Payment::with(['user', 'appointment'])
            ->where('boarding_house_id', $boardingHouseId)
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    public function getByAppointment(int $appointmentId, ?string $status = null): Collection
    {
        $query = Payment::with(['user', 'boardingHouse'])
            ->where('appointment_id', $appointmentId)
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    /**
     * Clear user-related payment caches
     */
    protected function clearUserCache(?int $userId): void
    {
        if (!$userId) {
            return;
        }

        $patterns = [
            "payments:user:{$userId}",
            "payments:user:{$userId}:status:*",
        ];

        foreach ($patterns as $pattern) {
            // Note: This is a simplified version. In production, use Redis tags or similar
            Cache::flush(); // For now, clear all. In production, implement tag-based cache
        }
    }
}
