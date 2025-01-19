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
            //
            'required'          => ':attribute bắt buộc nhập',
            'regex'             => 'Định dạng không hợp lệ',
            'date'              => 'Định dạng không hợp lệ',
            'date_format'       => 'Định dạng không hợp lệ',
            'numeric'           => 'Định dạng không hợp lệ',
            'string'            => 'Định dạng không hợp lệ',
            'gte'               => 'Giá trị phải >= :value',
            'note.max'          => ':attribute tối đa :max ký tự',
            'appointment.after' => 'Ngày hẹn phải sau hiện tại'
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
