<?php

namespace App\Strategies;

use App\Models\Payment;
use App\Strategies\Contracts\PaymentStrategyInterface;
use InvalidArgumentException;

class PaymentStrategyFactory
{
    protected array $strategies = [
        Payment::TYPE_DEPOSIT => DepositPaymentStrategy::class,
        Payment::TYPE_BOOKING_FEE => BookingFeePaymentStrategy::class,
        Payment::TYPE_RENT => RentPaymentStrategy::class,
    ];

    /**
     * Create strategy instance by payment type
     */
    public function create(string $paymentType): PaymentStrategyInterface
    {
        if (!isset($this->strategies[$paymentType])) {
            throw new InvalidArgumentException("Payment strategy for type '{$paymentType}' not found");
        }

        $strategyClass = $this->strategies[$paymentType];
        
        return new $strategyClass();
    }

    /**
     * Register custom strategy
     */
    public function register(string $paymentType, string $strategyClass): void
    {
        if (!is_subclass_of($strategyClass, PaymentStrategyInterface::class)) {
            throw new InvalidArgumentException("Strategy must implement PaymentStrategyInterface");
        }

        $this->strategies[$paymentType] = $strategyClass;
    }

    /**
     * Get all registered payment types
     */
    public function getRegisteredTypes(): array
    {
        return array_keys($this->strategies);
    }
}
