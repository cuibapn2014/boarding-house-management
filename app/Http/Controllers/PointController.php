<?php

namespace App\Http\Controllers;

use App\Models\PointPackage;
use App\Services\Contracts\PointServiceInterface;
use App\Services\Contracts\PaymentServiceInterface;
use App\DTOs\PaymentData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PointController extends Controller
{
    public function __construct(
        protected PointServiceInterface $pointService,
        protected PaymentServiceInterface $paymentService
    ) {}

    /**
     * Display wallet page with balance and transaction history
     */
    public function wallet(Request $request)
    {
        $user = auth()->user();
        $balance = $this->pointService->getBalance($user);
        $transactions = $this->pointService->getTransactionHistory($user, 15);

        return view('apps.point.wallet', compact('balance', 'transactions'));
    }

    /**
     * Display top-up packages page
     */
    public function topUp()
    {
        $packages = $this->pointService->getActivePackages();
        $balance = $this->pointService->getBalance(auth()->user());

        return view('apps.point.top-up', compact('packages', 'balance'));
    }

    /**
     * Process point top-up - show payment form
     */
    public function processTopUp(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:point_packages,id',
        ]);

        try {
            $user = auth()->user();
            $package = PointPackage::findOrFail($request->package_id);

            if (!$package->is_active) {
                return back()->with('error', 'Gói nạp điểm không khả dụng');
            }

            // Create payment for top-up
            $payment = $this->paymentService->createPayment(
                PaymentData::fromArray([
                    'payment_type' => \App\Models\Payment::TYPE_POINT_TOP_UP,
                    'amount' => $package->price,
                    'user_id' => $user->id,
                    'description' => "Nạp điểm từ gói: {$package->name}",
                    'expires_at' => Carbon::now()->addDays(1)->toDateTimeString(),
                    'metadata' => [
                        'package_id' => $package->id,
                        'package_name' => $package->name,
                        'points' => $package->points,
                        'bonus_points' => $package->bonus_points,
                    ],
                ])
            );

            // Return view with payment form using generatePaymentButton
            return view('apps.point.payment', compact('payment', 'package'));
        } catch (\Exception $e) {
            Log::error('Error processing top-up', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'package_id' => $request->package_id,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi xử lý nạp điểm. Vui lòng thử lại.');
        }
    }

    /**
     * Transaction history page
     */
    public function transactions(Request $request)
    {
        $user = auth()->user();
        $transactions = $this->pointService->getTransactionHistory($user, 20);

        return view('apps.point.transactions', compact('transactions'));
    }
}
