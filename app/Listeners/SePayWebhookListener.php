<?php

namespace App\Listeners;

use SePay\SePay\Events\SePayWebhookEvent;
use App\Services\Contracts\PaymentServiceInterface;
use App\Services\WebhookValidationService;
use App\Models\WebhookLog;
use App\Events\PaymentCompleted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SePayWebhookListener
{
    public function __construct(
        protected PaymentServiceInterface $paymentService,
        protected WebhookValidationService $validationService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(SePayWebhookEvent $event): void
    {
        $webhookLog = null;

        try {
            $data = $event->sePayWebhookData;
            
            // Create webhook log
            $webhookLog = $this->createWebhookLog($data, request());

            // Only process incoming transfers
            if ($data->transferType !== 'in') {
                $webhookLog->markAsFailed(['reason' => 'Non-incoming transfer ignored']);
                Log::info('SePay webhook: Ignoring non-incoming transfer', [
                    'transferType' => $data->transferType,
                    'referenceCode' => $data->referenceCode ?? null,
                ]);
                return;
            }

            // Extract payment code from content
            $pattern = config('sepay.pattern', 'SE');
            $paymentCode = $pattern . $event->info;
            
            if (!$paymentCode) {
                $webhookLog->markAsFailed(['reason' => 'Could not extract payment code']);
                Log::warning('SePay webhook: Could not extract payment code from content', [
                    'content' => $data->content ?? '',
                    'pattern' => $pattern,
                    'info' => $event->info,
                ]);
                return;
            }

            // Update webhook log with payment code
            $webhookLog->update(['payment_code' => $paymentCode]);
            $webhookLog->markAsProcessing();

            // Prepare webhook data
            $sepayData = [
                'gateway' => $data->gateway ?? null,
                'transactionDate' => $data->transactionDate ?? null,
                'accountNumber' => $data->accountNumber ?? null,
                'content' => $data->content ?? '',
                'transferType' => $data->transferType,
                'transferAmount' => $data->transferAmount ?? 0,
                'referenceCode' => $data->referenceCode ?? null,
                'id' => $data->id ?? null,
            ];

            // Validate payment
            $validation = $this->validationService->validatePayment($paymentCode, $sepayData);

            if (!$validation['valid']) {
                $webhookLog->markAsFailed([
                    'validation_errors' => $validation['errors'],
                ]);
                Log::warning('SePay webhook: Payment validation failed', [
                    'payment_code' => $paymentCode,
                    'errors' => $validation['errors'],
                ]);
                return;
            }

            $payment = $validation['payment'];

            // Process payment completion
            $processedPayment = $this->paymentService->processPaymentCompletion($paymentCode, $sepayData);

            if ($processedPayment) {
                $webhookLog->markAsSuccess();
                
                Log::info('SePay webhook: Payment processed successfully', [
                    'payment_code' => $paymentCode,
                    'payment_id' => $processedPayment->id,
                    'amount' => $processedPayment->amount,
                    'webhook_amount' => $sepayData['transferAmount'],
                ]);

                // Trigger payment completed event
                event(new PaymentCompleted($processedPayment));
            } else {
                $webhookLog->markAsFailed(['reason' => 'Payment processing returned null']);
                Log::warning('SePay webhook: Payment processing returned null', [
                    'payment_code' => $paymentCode,
                ]);
            }
        } catch (\Exception $e) {
            if ($webhookLog) {
                $webhookLog->markAsFailed([
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            Log::error('SePay webhook: Error processing payment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'info' => $event->info ?? null,
            ]);

            // Re-throw to let Laravel handle it (or queue for retry)
            throw $e;
        }
    }

    /**
     * Create webhook log entry
     */
    protected function createWebhookLog($data, $request): WebhookLog
    {
        return DB::transaction(function () use ($data, $request) {
            return WebhookLog::create([
                'webhook_id' => $data->id ?? null,
                'gateway' => $data->gateway ?? null,
                'transfer_type' => $data->transferType ?? null,
                'transfer_amount' => $data->transferAmount ?? 0,
                'account_number' => $data->accountNumber ?? null,
                'content' => $data->content ?? null,
                'raw_payload' => json_encode([
                    'id' => $data->id ?? null,
                    'gateway' => $data->gateway ?? null,
                    'transactionDate' => $data->transactionDate ?? null,
                    'accountNumber' => $data->accountNumber ?? null,
                    'subAccount' => $data->subAccount ?? null,
                    'code' => $data->code ?? null,
                    'content' => $data->content ?? null,
                    'transferType' => $data->transferType ?? null,
                    'description' => $data->description ?? null,
                    'transferAmount' => $data->transferAmount ?? null,
                    'referenceCode' => $data->referenceCode ?? null,
                    'accumulated' => $data->accumulated ?? null,
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => WebhookLog::STATUS_PENDING,
            ]);
        });
    }
}
