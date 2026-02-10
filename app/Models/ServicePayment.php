<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_type',
        'service_name',
        'points_cost',
        'cash_amount',
        'payment_method',
        'boarding_house_id',
        'payment_id',
        'status',
        'description',
        'metadata',
        'completed_at',
    ];

    protected $casts = [
        'points_cost' => 'integer',
        'cash_amount' => 'decimal:2',
        'completed_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Service type constants
     */
    const SERVICE_PUSH_LISTING = 'push_listing';
    const SERVICE_PRIORITY_LISTING = 'priority_listing';
    const SERVICE_EXTEND_LISTING = 'extend_listing';
    const SERVICE_PUBLISH_LISTING = 'publish_listing';

    /**
     * Payment method constants
     */
    const METHOD_POINTS = 'points';
    const METHOD_CASH = 'cash';
    const METHOD_MIXED = 'mixed';

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the user that made the payment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the boarding house related to this service payment
     */
    public function boardingHouse(): BelongsTo
    {
        return $this->belongsTo(BoardingHouse::class);
    }

    /**
     * Get the payment related to this service payment (if cash payment)
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
