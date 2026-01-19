<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'webhook_id',
        'payment_code',
        'status',
        'gateway',
        'transfer_type',
        'transfer_amount',
        'account_number',
        'content',
        'raw_payload',
        'validation_errors',
        'processing_errors',
        'ip_address',
        'user_agent',
        'processed_at',
    ];

    protected $casts = [
        'transfer_amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'validation_errors' => 'array',
        'processing_errors' => 'array',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    /**
     * Get the payment related to this webhook
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_code', 'payment_code');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function scopeByPaymentCode($query, string $paymentCode)
    {
        return $query->where('payment_code', $paymentCode);
    }

    /**
     * Mark as processing
     */
    public function markAsProcessing(): void
    {
        $this->update(['status' => self::STATUS_PROCESSING]);
    }

    /**
     * Mark as success
     */
    public function markAsSuccess(): void
    {
        $this->update([
            'status' => self::STATUS_SUCCESS,
            'processed_at' => now(),
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed(array $errors = []): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'processing_errors' => $errors,
            'processed_at' => now(),
        ]);
    }
}
