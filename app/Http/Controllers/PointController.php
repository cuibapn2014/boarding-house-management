<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    private function ensureAdmin(): void
    {
        if (! auth()->user()->is_admin) {
            abort(403, 'Chỉ admin mới có quyền truy cập.');
        }
    }

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

    /**
     * Admin: xem lịch sử sử dụng điểm của tất cả user
     */
    public function adminTransactions(Request $request)
    {
        $this->ensureAdmin();
        $userId = $request->filled('user_id') ? (int) $request->user_id : null;
        $transactions = $this->pointService->getTransactionHistoryForAllUsers(20, $userId);
        $users = User::orderBy('firstname')->get(['id', 'firstname', 'lastname', 'email']);

        return view('apps.point.admin-transactions', compact('transactions', 'users'));
    }

    /**
     * Admin: form cộng/trừ điểm thủ công
     */
    public function showAdjustPoints(Request $request)
    {
        $this->ensureAdmin();
        $users = User::orderBy('firstname')->get(['id', 'firstname', 'lastname', 'email', 'points']);

        return view('apps.point.admin-adjust', compact('users'));
    }

    /**
     * Admin: xử lý cộng/trừ điểm (ghi đầy đủ lịch sử)
     */
    public function storeAdjustPoints(Request $request)
    {
        $this->ensureAdmin();
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'action' => 'required|in:add,subtract',
            'points' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
        ]);

        $targetUser = User::findOrFail($request->user_id);
        $adminUser = auth()->user();
        $points = (int) $request->points;
        $reason = trim($request->reason);

        try {
            if ($request->action === 'add') {
                $this->pointService->addPointsByAdmin($targetUser, $points, $reason, $adminUser);
                $message = "Đã cộng {$points} điểm cho " . $targetUser->firstname . ' ' . $targetUser->lastname;
            } else {
                $this->pointService->subtractPointsByAdmin($targetUser, $points, $reason, $adminUser);
                $message = "Đã trừ {$points} điểm của " . $targetUser->firstname . ' ' . $targetUser->lastname;
            }
            return redirect()->route('point.admin.transactions')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Admin adjust points failed: ' . $e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
