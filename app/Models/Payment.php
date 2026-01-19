<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_code',
        'user_id',
        'boarding_house_id',
        'appointment_id',
        'payment_type',
        'amount',
        'currency',
        'status',
        'description',
        'sepay_reference_code',
        'sepay_transaction_id',
        'paid_at',
        'expires_at',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Payment status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Payment type constants
     */
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_RENT = 'rent';
    const TYPE_BOOKING_FEE = 'booking_fee';

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache on update/delete
        static::updated(function ($payment) {
            Cache::forget("payment:code:{$payment->payment_code}");
            Cache::forget("payment:id:{$payment->id}");
        });

        static::deleted(function ($payment) {
            Cache::forget("payment:code:{$payment->payment_code}");
            Cache::forget("payment:id:{$payment->id}");
        });
    }

    /**
     * Get the user that made the payment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the boarding house related to this payment
     */
    public function boardingHouse(): BelongsTo
    {
        return $this->belongsTo(BoardingHouse::class, 'boarding_house_id');
    }

    /**
     * Get the appointment related to this payment
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if payment is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast() && !$this->isCompleted();
    }

    /**
     * Check if payment can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return !$this->isCompleted() && !$this->isExpired();
    }

    /**
     * Scopes
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING)
            ->where('expires_at', '<', now());
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('payment_type', $type);
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByBoardingHouse(Builder $query, int $boardingHouseId): Builder
    {
        return $query->where('boarding_house_id', $boardingHouseId);
    }

    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Generate unique payment code (deprecated - use service)
     * @deprecated Use PaymentService::generateUniquePaymentCode() instead
     */
    public static function generatePaymentCode(): string
    {
        $prefix = config('sepay.pattern', 'SE');
        do {
            $code = $prefix . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('payment_code', $code)->exists());

        return $code;
    }
}
