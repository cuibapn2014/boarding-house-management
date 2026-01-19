<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PointTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_type',
        'amount',
        'balance_before',
        'balance_after',
        'reference_type',
        'reference_id',
        'description',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Transaction type constants
     */
    const TYPE_TOP_UP = 'top_up';
    const TYPE_DEDUCTION = 'deduction';
    const TYPE_REFUND = 'refund';
    const TYPE_SERVICE_PAYMENT = 'service_payment';

    /**
     * Get the user that owns the transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent reference model (polymorphic)
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Check if transaction is positive (adds points)
     */
    public function isPositive(): bool
    {
        return $this->amount > 0;
    }

    /**
     * Check if transaction is negative (deducts points)
     */
    public function isNegative(): bool
    {
        return $this->amount < 0;
    }
}
