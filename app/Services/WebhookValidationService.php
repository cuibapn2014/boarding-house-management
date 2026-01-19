<?php

namespace App\Services;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Support\Facades\Log;

class WebhookValidationService
{
    public function __construct(
        protected PaymentRepositoryInterface $paymentRepository
    ) {}

    /**
     * Validate webhook data against payment
     */
    public function validatePayment(string $paymentCode, array $webhookData): array
    {
        $errors = [];
        
        $payment = $this->paymentRepository->findByCode($paymentCode);
        
        if (!$payment) {
            $errors[] = "Payment not found for code: {$paymentCode}";
            return ['valid' => false, 'errors' => $errors, 'payment' => null];
        }

        // Check if payment is already completed
        if ($payment->isCompleted()) {
            $errors[] = "Payment already completed";
            return ['valid' => false, 'errors' => $errors, 'payment' => $payment];
        }

        // Validate amount (allow small difference for rounding)
        $webhookAmount = (float) ($webhookData['transferAmount'] ?? 0);
        $paymentAmount = (float) $payment->amount;
        $allowedDifference = 1000; // Allow 1000 VND difference

        if (abs($webhookAmount - $paymentAmount) > $allowedDifference) {
            $errors[] = sprintf(
                "Amount mismatch: Payment amount is %s, but webhook amount is %s",
                number_format($paymentAmount, 0, ',', '.'),
                number_format($webhookAmount, 0, ',', '.')
            );
        }

        // Validate payment is not expired
        if ($payment->isExpired()) {
            $errors[] = "Payment has expired";
        }

        // Validate transfer type
        if (($webhookData['transferType'] ?? '') !== 'in') {
            $errors[] = "Invalid transfer type: expected 'in', got '{$webhookData['transferType']}'";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'payment' => $payment,
        ];
    }

    /**
     * Validate webhook signature/token
     */
    public function validateToken(?string $token): bool
    {
        $expectedToken = config('sepay.webhook_token');
        
        if (empty($expectedToken)) {
            Log::warning('SePay webhook token not configured');
            return false;
        }

        return $token === $expectedToken;
    }

    /**
     * Extract payment code from webhook content
     */
    public function extractPaymentCode(string $content, string $pattern): ?string
    {
        // Try to match pattern like "SE123456" in content
        $regex = '/' . preg_quote($pattern, '/') . '(\d{6})/i';
        
        if (preg_match($regex, $content, $matches)) {
            return $pattern . $matches[1];
        }

        return null;
    }
}
