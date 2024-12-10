<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoardingHouseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
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
            'title' => 'required|max:255',
            'description' => 'required',
            'status' => 'required',
            'price' => 'required'
        ];
    }

    public function messages() : array
    {
        return [
            'required' => 'Bắt buộc nhập',
            'max' => ':attribute tối đa :max ký tự'
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Tiêu đề',
            'description' => 'Mô tả',
            'status' => 'Trạng thái',
            'is_publish' => 'Pushlish',
            'price' => 'Giá'
        ];
    }
}
