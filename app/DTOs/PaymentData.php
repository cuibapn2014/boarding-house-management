<?php

namespace App\DTOs;

use Carbon\Carbon;

class PaymentData
{
    public function __construct(
        public readonly string $paymentType,
        public readonly float $amount,
        public readonly ?int $userId = null,
        public readonly ?int $boardingHouseId = null,
        public readonly ?int $appointmentId = null,
        public readonly string $currency = 'VND',
        public readonly ?string $description = null,
        public readonly ?Carbon $expiresAt = null,
        public readonly array $metadata = []
    ) {}

    /**
     * Create from array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            paymentType: $data['payment_type'],
            amount: (float) $data['amount'],
            userId: $data['user_id'] ?? null,
            boardingHouseId: $data['boarding_house_id'] ?? null,
            appointmentId: $data['appointment_id'] ?? null,
            currency: $data['currency'] ?? 'VND',
            description: $data['description'] ?? null,
            expiresAt: isset($data['expires_at']) ? Carbon::parse($data['expires_at']) : null,
            metadata: $data['metadata'] ?? []
        );
    }

    /**
     * Convert to array for database
     */
    public function toArray(): array
    {
        return [
            'payment_type' => $this->paymentType,
            'amount' => $this->amount,
            'user_id' => $this->userId,
            'boarding_house_id' => $this->boardingHouseId,
            'appointment_id' => $this->appointmentId,
            'currency' => $this->currency,
            'description' => $this->description,
            'expires_at' => $this->expiresAt?->toDateTimeString(),
            'metadata' => $this->metadata,
        ];
    }
}
