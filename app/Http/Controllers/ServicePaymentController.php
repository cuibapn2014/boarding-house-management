<?php

namespace App\Http\Controllers;

use App\Models\BoardingHouse;
use App\Models\ServicePayment;
use App\Services\Contracts\ServicePaymentServiceInterface;
use App\Services\Contracts\PointServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServicePaymentController extends Controller
{
    public function __construct(
        protected ServicePaymentServiceInterface $servicePaymentService,
        protected PointServiceInterface $pointService
    ) {}

    /**
     * Show service payment options for a boarding house
     */
    public function show(BoardingHouse $boardingHouse)
    {
        $user = auth()->user();
        $balance = $this->pointService->getBalance($user);

        $services = [
            [
                'type' => ServicePayment::SERVICE_PUSH_LISTING,
                'name' => 'Đẩy tin',
                'description' => 'Đưa tin đăng lên đầu danh sách',
                'points_cost' => $this->servicePaymentService->getServiceCost(ServicePayment::SERVICE_PUSH_LISTING),
            ],
            [
                'type' => ServicePayment::SERVICE_PRIORITY_LISTING,
                'name' => 'Tin ưu tiên',
                'description' => 'Đánh dấu tin đăng là ưu tiên',
                'points_cost' => $this->servicePaymentService->getServiceCost(ServicePayment::SERVICE_PRIORITY_LISTING),
            ],
            [
                'type' => ServicePayment::SERVICE_EXTEND_LISTING,
                'name' => 'Gia hạn tin đăng',
                'description' => 'Gia hạn thời gian hiển thị tin đăng',
                'points_cost' => $this->servicePaymentService->getServiceCost(ServicePayment::SERVICE_EXTEND_LISTING),
            ],
        ];

        return view('apps.service-payment.show', compact('boardingHouse', 'services', 'balance'));
    }

    /**
     * Process service payment
     */
    public function process(Request $request, BoardingHouse $boardingHouse)
    {
        $request->validate([
            'service_type' => 'required|in:' . implode(',', [
                ServicePayment::SERVICE_PUSH_LISTING,
                ServicePayment::SERVICE_PRIORITY_LISTING,
                ServicePayment::SERVICE_EXTEND_LISTING,
            ]),
        ]);

        try {
            $user = auth()->user();
            $serviceType = $request->service_type;
            $pointsCost = $this->servicePaymentService->getServiceCost($serviceType);

            $serviceNames = [
                ServicePayment::SERVICE_PUSH_LISTING => 'Đẩy tin',
                ServicePayment::SERVICE_PRIORITY_LISTING => 'Tin ưu tiên',
                ServicePayment::SERVICE_EXTEND_LISTING => 'Gia hạn tin đăng',
            ];

            $serviceName = $serviceNames[$serviceType] ?? 'Dịch vụ';

            // Process service payment
            $servicePayment = $this->servicePaymentService->processServicePayment(
                $user,
                $serviceType,
                $serviceName,
                $pointsCost,
                $boardingHouse,
                "Thanh toán dịch vụ {$serviceName} cho tin đăng: {$boardingHouse->title}"
            );

            // If payment completed with points
            if ($servicePayment->isCompleted() && $servicePayment->payment_method === ServicePayment::METHOD_POINTS) {
                return redirect()
                    ->route('boarding-house.show', $boardingHouse->id)
                    ->with('success', "Đã thanh toán dịch vụ {$serviceName} thành công bằng điểm!");
            }

            // If requires cash payment, redirect to payment page
            if ($servicePayment->payment_id) {
                $payment = \App\Models\Payment::find($servicePayment->payment_id);
                if ($payment) {
                    return redirect()
                        ->route('payment.show', $payment->payment_code)
                        ->with('info', "Bạn không đủ điểm. Vui lòng thanh toán bằng tiền mặt để sử dụng dịch vụ.");
                }
            }

            return back()->with('error', 'Có lỗi xảy ra khi xử lý thanh toán dịch vụ.');
        } catch (\Exception $e) {
            Log::error('Error processing service payment', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'boarding_house_id' => $boardingHouse->id,
                'service_type' => $request->service_type,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán dịch vụ. Vui lòng thử lại.');
        }
    }
}
