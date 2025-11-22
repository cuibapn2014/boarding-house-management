<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'họ và tên',
            'email' => 'email',
            'phone' => 'số điện thoại',
            'subject' => 'tiêu đề',
            'message' => 'nội dung',
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
            'name.required' => 'Vui lòng nhập :attribute.',
            'email.required' => 'Vui lòng nhập :attribute.',
            'email.email' => 'Email không hợp lệ.',
            'message.required' => 'Vui lòng nhập :attribute.',
            'message.min' => 'Nội dung phải có ít nhất :min ký tự.',
            'message.max' => 'Nội dung không được vượt quá :max ký tự.',
        ];
    }
}
