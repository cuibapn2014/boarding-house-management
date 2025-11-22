<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255|min:2|unique:users,username|alpha_dash',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|max:255|confirmed',
            'terms' => 'sometimes|accepted'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'username.string' => 'Tên đăng nhập không hợp lệ',
            'username.max' => 'Tên đăng nhập không được vượt quá :max ký tự',
            'username.min' => 'Tên đăng nhập phải có ít nhất :min ký tự',
            'username.unique' => 'Tên đăng nhập đã được sử dụng',
            'username.alpha_dash' => 'Tên đăng nhập chỉ được chứa chữ cái, số, dấu gạch ngang (-) và gạch dưới (_)',
            
            'email.required' => 'Vui lòng nhập địa chỉ email',
            'email.email' => 'Địa chỉ email không đúng định dạng',
            'email.max' => 'Địa chỉ email không được vượt quá :max ký tự',
            'email.unique' => 'Địa chỉ email đã được đăng ký',
            
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.string' => 'Mật khẩu không hợp lệ',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự',
            'password.max' => 'Mật khẩu không được vượt quá :max ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            
            'terms.accepted' => 'Bạn phải đồng ý với điều khoản sử dụng'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'username' => 'Tên đăng nhập',
            'email' => 'Email',
            'password' => 'Mật khẩu',
            'password_confirmation' => 'Xác nhận mật khẩu',
            'terms' => 'Điều khoản sử dụng',
        ];
    }
}

