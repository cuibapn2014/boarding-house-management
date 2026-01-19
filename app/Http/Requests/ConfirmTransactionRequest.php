<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmTransactionRequest extends FormRequest
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
            'gateway' => 'required|string',
            'transactionDate' => 'required|date',
            'accountNumber' => 'required|string',
            'subAccount' => 'nullable|string',
            'code' => 'required|string',
            'content' => 'required|string',
            'transferType' => 'required|string',
            'description' => 'required|string',
            'transferAmount' => 'required|numeric',
            'referenceCode' => 'required|string',
            'accumulated' => 'required|numeric',
            'id' => 'required|integer',
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
            'gateway.required' => 'Gateway là bắt buộc',
            'gateway.string' => 'Gateway phải là chuỗi',
            'transactionDate.required' => 'Ngày giao dịch là bắt buộc',
            'transactionDate.date' => 'Ngày giao dịch phải là ngày',
            'accountNumber.required' => 'Số tài khoản là bắt buộc',
            'accountNumber.string' => 'Số tài khoản phải là chuỗi',
            'subAccount.string' => 'Sub account phải là chuỗi',
            'description.required' => 'Mô tả là bắt buộc',
            'code.required' => 'Mã thanh toán là bắt buộc',
            'code.string' => 'Mã thanh toán phải là chuỗi',
            'content.required' => 'Nội dung thanh toán là bắt buộc',
            'content.string' => 'Nội dung thanh toán phải là chuỗi',
            'transferType.required' => 'Loại chuyển khoản là bắt buộc',
            'transferType.string' => 'Loại thanh toán phải là chuỗi',
            'description.required' => 'Mô tả thanh toán là bắt buộc',
            'description.string' => 'Mô tả thanh toán phải là chuỗi',
            'transferAmount.required' => 'Số tiền chuyển là bắt buộc',
            'transferAmount.numeric' => 'Số tiền thanh toán phải là số',
            'referenceCode.required' => 'Mã tham chiếu là bắt buộc',
            'referenceCode.string' => 'Mã tham chiếu phải là chuỗi',
            'accumulated.required' => 'Tổng số tiền là bắt buộc',
            'accumulated.numeric' => 'Tổng số tiền phải là số',
            'id.required' => 'ID là bắt buộc',
            'id.integer' => 'ID phải là số nguyên',
        ];
    }
}


