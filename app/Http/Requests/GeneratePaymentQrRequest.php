<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneratePaymentQrRequest extends FormRequest
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
            'amount' => 'required|numeric|min:1000|max:999999999999',
            'payment_type' => 'required|string|in:deposit,rent,booking_fee',
            'boarding_house_id' => 'nullable|integer|exists:boarding_houses,id',
            'appointment_id' => 'nullable|integer|exists:appointments,id',
            'description' => 'nullable|string|max:500',
            'expires_at' => 'nullable|date_format:d/m/Y H:i:s|after:now',
            'metadata' => 'nullable|array',
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
            'amount.required' => 'Số tiền thanh toán là bắt buộc',
            'amount.numeric' => 'Số tiền thanh toán phải là số',
            'amount.min' => 'Số tiền thanh toán phải lớn hơn 1000',
            'amount.max' => 'Số tiền thanh toán phải nhỏ hơn 999999999999',
            'payment_type.required' => 'Loại thanh toán là bắt buộc',
            'payment_type.string' => 'Loại thanh toán phải là chuỗi',
            'payment_type.in' => 'Loại thanh toán không hợp lệ',
            'boarding_house_id.integer' => 'Phòng trọ là bắt buộc',
            'boarding_house_id.exists' => 'Phòng trọ không tồn tại',
            'appointment_id.integer' => 'Cuộc hẹn là bắt buộc',
            'appointment_id.exists' => 'Cuộc hẹn không tồn tại',
            'description.string' => 'Mô tả thanh toán phải là chuỗi',
            'description.max' => 'Mô tả thanh toán không được vượt quá 500 ký tự',
            'expires_at.date_format' => 'Thời gian hết hạn thanh toán không hợp lệ',
            'expires_at.after' => 'Thời gian hết hạn thanh toán phải lớn hơn thời gian hiện tại',
            'metadata.array' => 'Dữ liệu bổ sung phải là mảng',
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
            'amount' => 'Số tiền thanh toán',
            'payment_type' => 'Loại thanh toán',
            'boarding_house_id' => 'Phòng trọ',
            'appointment_id' => 'Cuộc hẹn',
            'description' => 'Mô tả thanh toán',
            'expires_at' => 'Thời gian hết hạn thanh toán',
            'expires_at.date_format' => 'Thời gian hết hạn thanh toán phải là định dạng dd/mm/yyyy hh:mm:ss',
            'expires_at.after' => 'Thời gian hết hạn thanh toán phải lớn hơn thời gian hiện tại',
            'metadata' => 'Dữ liệu bổ sung',
            'metadata.array' => 'Dữ liệu bổ sung phải là mảng',
            'metadata.key' => 'Dữ liệu bổ sung phải là key-value',
        ];
    }
}
