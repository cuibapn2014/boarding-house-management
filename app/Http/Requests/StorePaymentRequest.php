<?php

namespace App\Http\Requests;

use App\Models\Payment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
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
            'payment_type' => [
                'required',
                'string',
                Rule::in([
                    Payment::TYPE_DEPOSIT,
                    Payment::TYPE_RENT,
                    Payment::TYPE_BOOKING_FEE,
                ])
            ],
            'amount' => [
                'required',
                'numeric',
                'min:1000',
                'max:999999999999'
            ],
            'boarding_house_id' => [
                'nullable',
                'integer',
                'exists:boarding_houses,id'
            ],
            'appointment_id' => [
                'nullable',
                'integer',
                'exists:appointments,id'
            ],
            'description' => [
                'nullable',
                'string',
                'max:500'
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'payment_type.required' => 'Vui lòng chọn loại thanh toán',
            'payment_type.in' => 'Loại thanh toán không hợp lệ',
            'amount.required' => 'Vui lòng nhập số tiền',
            'amount.numeric' => 'Số tiền phải là số',
            'amount.min' => 'Số tiền tối thiểu là 1,000 VND',
            'amount.max' => 'Số tiền tối đa là 999,999,999,999 VND',
            'boarding_house_id.exists' => 'Phòng trọ không tồn tại',
            'appointment_id.exists' => 'Cuộc hẹn không tồn tại',
            'description.max' => 'Mô tả không được vượt quá 500 ký tự',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure amount is numeric
        if ($this->has('amount')) {
            $this->merge([
                'amount' => (float) str_replace(',', '', $this->input('amount'))
            ]);
        }
    }
}
