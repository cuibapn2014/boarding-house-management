<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\BoardingHouse;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    //
    public function __construct(
        private TelegramService $telegramService
    )
    {
        
    }
    public function store(StoreAppointmentRequest $request, $id)
    {
        $boardingHouse = BoardingHouse::find($id);
        
        if(! $boardingHouse) {
            return $this->responseError('Phòng/nhà trọ không tồn tại hoặc đã bị xoá!');
        }

        $appointment = new Appointment();

        try{
            DB::transaction(function () use($id, $boardingHouse, &$appointment, $request) {
                $appointment->customer_name = trim($request->input('customer_name'));
                $appointment->phone = trim($request->input('phone'));
                $appointment->total_person = $request->input('total_person');
                $appointment->total_bike = $request->input('total_bike');
                $appointment->boarding_house_id = $id;
                $appointment->move_in_date = convertDateWithFormat($request->input('move_in_date'), 'd/m/Y');
                $appointment->status = 'WAITING_CONFIRM';
                $appointment->note = $request->input('note');
                $appointment->appointment_at = convertDateWithFormat($request->input('appointment_at'), 'd/m/Y H:i', 'Y-m-d H:i');
                $appointment->save();

                $messageNotify = "CUỘC HẸN XEM PHÒNG VỪA ĐƯỢC TẠO [Tạo bởi {$appointment->customer_name}]".PHP_EOL.PHP_EOL
                    ."- Ngày giờ hẹn xem phòng: ".date('d/m/Y H:i', strtotime($appointment->appointment_at)).PHP_EOL
                    ."- Họ tên khách: {$appointment->customer_name}".PHP_EOL
                    ."- SĐT/Zalo: {$appointment->phone}".PHP_EOL
                    ."- Tổng người ở: {$appointment->total_person}".PHP_EOL
                    ."- Tổng xe: {$appointment->total_bike}".PHP_EOL
                    ."- Ngày chuyển vào dự kiến: ".($appointment->move_in_date ? date('d/m/Y', strtotime($appointment->move_in_date)) : 'Không rõ').PHP_EOL
                    ."- Địa chỉ: {$boardingHouse->address}, {$boardingHouse->ward}, {$boardingHouse->district}".PHP_EOL
                    ."- Post: {$boardingHouse->title}".PHP_EOL
                    ."- ID Post: {$boardingHouse->id}".PHP_EOL
                    ."- Ghi chú: {$appointment->note}".PHP_EOL;

                $this->telegramService->sendMessage($messageNotify);
            });
        } catch(\Exception $ex) {
            return $this->responseError();
        }
        
        return $this->responseSuccess('Đã tạo cuộc hẹn xem phòng. Hãy kiểm tra lại thông tin!', $appointment);
    }
}
