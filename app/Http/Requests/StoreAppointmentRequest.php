<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'customer_name'  => 'required',
            'phone'          => ['required', 'regex:/^(0|\\+84)[0-9]{9}$/'],
            'total_person'   => 'required|numeric|gte:1',
            'total_bike'     => 'required|gte:0',
            'move_in_date'   => 'nullable|date_format:d/m/Y',
            'note'           => 'nullable|string|max:255',
            'appointment_at' => [
                'required',
                'date_format:d/m/Y H:i',
                'after:now'
            ]
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Vui lòng nhập họ tên khách hàng',
            
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.regex' => 'Số điện thoại không đúng định dạng (Ví dụ: 0912345678)',
            
            'total_person.required' => 'Vui lòng nhập số người ở',
            'total_person.numeric' => 'Số người ở phải là số',
            'total_person.gte' => 'Số người ở phải lớn hơn hoặc bằng :value',
            
            'total_bike.required' => 'Vui lòng nhập số xe',
            'total_bike.gte' => 'Số xe phải lớn hơn hoặc bằng :value',
            
            'move_in_date.date_format' => 'Ngày chuyển vào không đúng định dạng (dd/mm/yyyy)',
            
            'note.string' => 'Ghi chú không hợp lệ',
            'note.max' => 'Ghi chú không được vượt quá :max ký tự',
            
            'appointment_at.required' => 'Vui lòng chọn ngày giờ hẹn xem phòng',
            'appointment_at.date_format' => 'Ngày giờ hẹn không đúng định dạng (dd/mm/yyyy HH:mm)',
            'appointment_at.after' => 'Ngày giờ hẹn phải sau thời điểm hiện tại',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function attributes(): array
    {
        return [
            //
            'customer_name'  => 'Họ tên',
            'phone'          => 'Số điện thoại/Zalo',
            'total_person'   => 'Số người',
            'total_bike'     => 'Số xe',
            'move_in_date'   => 'Ngày chuyển vào',
            'note'           => 'Ghi chú',
            'appointment_at' => 'Ngày hẹn xem phòng'
        ];
    }
}
