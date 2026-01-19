<?php

namespace App\Listeners;

use App\Events\PaymentCompleted;
use App\Models\Payment;
use App\Models\PointPackage;
use App\Services\Contracts\PointServiceInterface;
use App\Services\Contracts\ServicePaymentServiceInterface;
use Illuminate\Support\Facades\Log;

class ProcessPointTopUpOnPaymentCompleted
{
    public function __construct(
        protected PointServiceInterface $pointService,
        protected ServicePaymentServiceInterface $servicePaymentService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(PaymentCompleted $event): void
    {
        $payment = $event->payment;

        // Handle point top-up payment
        if ($payment->payment_type === Payment::TYPE_POINT_TOP_UP) {
            $this->processPointTopUp($payment);
        }

        // Handle service payment completion
        if ($payment->payment_type === Payment::TYPE_SERVICE_PAYMENT) {
            $this->processServicePaymentCompletion($payment);
        }
    }

    /**
     * Process point top-up when payment is completed
     * Note: This is a backup handler. Primary point top-up is handled in PaymentService::processPaymentCompletion
     */
    protected function processPointTopUp(Payment $payment): void
    {
        try {
            // Check if points were already added (check for transaction record with this payment_id)
            $metadata = $payment->metadata ?? [];
            $packageId = $metadata['package_id'] ?? null;
            
            if ($packageId) {
                $existingTransaction = \App\Models\PointTransaction::where('user_id', $payment->user_id)
                    ->where('reference_type', PointPackage::class)
                    ->where('reference_id', $packageId)
                    ->where('transaction_type', \App\Models\PointTransaction::TYPE_TOP_UP)
                    ->whereJsonContains('metadata->payment_id', $payment->id)
                    ->first();

                if ($existingTransaction) {
                    Log::info('Points already topped up for this payment, skipping listener', [
                        'payment_id' => $payment->id,
                        'transaction_id' => $existingTransaction->id,
                    ]);
                    return;
                }
            }

            $user = $payment->user;
            if (!$user) {
                Log::warning('Payment completed but user not found', [
                    'payment_id' => $payment->id,
                    'payment_code' => $payment->payment_code,
                ]);
                return;
            }

            $metadata = $payment->metadata ?? [];
            $packageId = $metadata['package_id'] ?? null;

            if (!$packageId) {
                Log::warning('Point top-up payment completed but package_id not found in metadata', [
                    'payment_id' => $payment->id,
                    'payment_code' => $payment->payment_code,
                ]);
                return;
            }

            $package = PointPackage::find($packageId);
            if (!$package) {
                Log::warning('Point package not found', [
                    'payment_id' => $payment->id,
                    'package_id' => $packageId,
                ]);
                return;
            }

            // Top up points (backup handler)
            $this->pointService->topUpPoints($user, $package, $payment->id);

            Log::info('Points topped up after payment completion (backup handler)', [
                'payment_id' => $payment->id,
                'payment_code' => $payment->payment_code,
                'user_id' => $user->id,
                'package_id' => $package->id,
                'points' => $package->total_points,
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing point top-up after payment completion', [
                'payment_id' => $payment->id,
                'payment_code' => $payment->payment_code,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Process service payment completion
     */
    protected function processServicePaymentCompletion(Payment $payment): void
    {
        try {
            $servicePayment = \App\Models\ServicePayment::where('payment_id', $payment->id)->first();

            if (!$servicePayment) {
                Log::warning('Service payment not found for completed payment', [
                    'payment_id' => $payment->id,
                    'payment_code' => $payment->payment_code,
                ]);
                return;
            }

            // Complete service payment
            $this->servicePaymentService->completeServicePayment($servicePayment, $payment);

            Log::info('Service payment completed after cash payment', [
                'payment_id' => $payment->id,
                'service_payment_id' => $servicePayment->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing service payment completion', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
