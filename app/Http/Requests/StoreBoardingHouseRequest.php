<?php

namespace App\Http\Requests;

use App\Constants\SystemDefination;
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
        $category = implode(',',array_keys(SystemDefination::BOARDING_HOUSE_CATEGORY));
        return [
            //
            'title' => 'required|max:255',
            'description' => 'required',
            'status' => 'required',
            'price' => 'required',
            'files.*' => 'nullable|mimes:png,jpg,mp4,jpeg,webp',
            'phone' => 'nullable|digits:10',
            'district' => 'required',
            'ward' => 'required',
            'category' => 'required|in:' . $category
        ];
    }

    public function messages() : array
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề nhà trọ',
            'title.max' => 'Tiêu đề không được vượt quá :max ký tự',
            
            'description.required' => 'Vui lòng nhập mô tả ngắn',
            
            'status.required' => 'Vui lòng chọn trạng thái',
            
            'price.required' => 'Vui lòng nhập giá thuê',
            
            'files.*.mimes' => 'File tải lên phải là ảnh (png, jpg, jpeg, webp) hoặc video (mp4)',
            
            'phone.digits' => 'Số điện thoại phải có đúng :digits chữ số',
            
            'district.required' => 'Vui lòng chọn quận/huyện',
            
            'ward.required' => 'Vui lòng chọn phường/xã',
            
            'category.required' => 'Vui lòng chọn danh mục',
            'category.in' => 'Danh mục được chọn không hợp lệ',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Tiêu đề',
            'description' => 'Mô tả',
            'status' => 'Trạng thái',
            'is_publish' => 'Pushlish',
            'price' => 'Giá',
            'files' => 'Tệp tin',
            'phone' => 'Liên hệ/Zalo',
            'district' => 'Quận/Huyện',
            'ward' => 'Phường/Xã',
            'category' => 'Danh mục'
        ];
    }
}
