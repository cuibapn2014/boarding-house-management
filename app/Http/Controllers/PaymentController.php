<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\BoardingHouse;
use App\Models\Appointment;
use App\Services\Contracts\PaymentServiceInterface;
use App\Strategies\PaymentStrategyFactory;
use App\Http\Requests\StorePaymentRequest;
use App\DTOs\PaymentData;
use App\Http\Requests\ConfirmTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class PaymentController extends Controller
{
    public function __construct(
        protected PaymentServiceInterface $paymentService,
        protected PaymentStrategyFactory $strategyFactory
    ) {}

    /**
     * Display payment form
     */
    public function create(Request $request)
    {
        $type = $request->get('type', Payment::TYPE_DEPOSIT);
        $boardingHouseId = $request->get('boarding_house_id');
        $appointmentId = $request->get('appointment_id');

        $boardingHouse = null;
        $appointment = null;

        if ($boardingHouseId) {
            $boardingHouse = BoardingHouse::findOrFail($boardingHouseId);
        }

        if ($appointmentId) {
            $appointment = Appointment::with('boardingHouse')->findOrFail($appointmentId);
            $boardingHouse = $appointment->boardingHouse;
        }

        // Use strategy to calculate amount
        try {
            $strategy = $this->strategyFactory->create($type);
            $amount = $strategy->calculateAmount($boardingHouse, $appointment);
        } catch (\InvalidArgumentException $e) {
            $amount = 0;
        }

        return view('apps.payment.create', compact('type', 'boardingHouse', 'appointment', 'amount'));
    }

    /**
     * Store new payment
     */
    public function store(StorePaymentRequest $request)
    {
        try {
            $paymentData = PaymentData::fromArray([
                'payment_type' => $request->payment_type,
                'amount' => $request->amount,
                'user_id' => auth()->id(),
                'boarding_house_id' => $request->boarding_house_id,
                'appointment_id' => $request->appointment_id,
                'description' => $request->description,
                'expires_at' => Carbon::now()->addDays(1)->toDateTimeString(),
            ]);

            $payment = $this->paymentService->createPayment($paymentData);

            return redirect()
                ->route('payment.show', $payment->payment_code)
                ->with('success', 'Đã tạo yêu cầu thanh toán thành công!');
        } catch (\Exception $e) {
            Log::error('Error creating payment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo thanh toán. Vui lòng thử lại.');
        }
    }

    /**
     * Show payment details and QR code
     */
    public function show(string $paymentCode)
    {
        $payment = $this->paymentService->getPaymentByCode($paymentCode);

        if (!$payment) {
            abort(404, 'Không tìm thấy thông tin thanh toán');
        }

        // Check authorization
        $this->authorize('view', $payment);

        return view('apps.payment.show', compact('payment'));
    }

    /**
     * Check payment status (AJAX)
     */
    public function checkStatus(string $paymentCode)
    {
        $payment = $this->paymentService->getPaymentByCode($paymentCode);

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        return response()->json([
            'status' => $payment->status,
            'is_completed' => $payment->isCompleted(),
            'is_expired' => $payment->isExpired(),
            'paid_at' => $payment->paid_at?->format('d/m/Y H:i:s'),
        ]);
    }

    /**
     * List user payments
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $payments = $this->paymentService->getUserPayments(auth()->id(), $status);

        return view('apps.payment.index', compact('payments', 'status'));
    }

    /**
     * Cancel payment
     */
    public function cancel(string $paymentCode)
    {
        $payment = $this->paymentService->getPaymentByCode($paymentCode);

        if (!$payment) {
            return back()->with('error', 'Không tìm thấy thanh toán');
        }

        // Check authorization
        if ($payment->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403, 'Bạn không có quyền hủy thanh toán này');
        }

        if (!$payment->canBeCancelled()) {
            return back()->with('error', 'Không thể hủy thanh toán này');
        }

        $this->paymentService->cancelPayment($payment);

        return back()->with('success', 'Đã hủy thanh toán thành công');
    }

    /**
     * Confirm payment
     */
    public function confirm(ConfirmTransactionRequest $request)
    {
          
        $payment = $this->paymentService->processPaymentCompletion($request->code, $request->toArray());

        if (!$payment) {
            return back()->with('error', 'Không tìm thấy thanh toán');
        }
    }
}
