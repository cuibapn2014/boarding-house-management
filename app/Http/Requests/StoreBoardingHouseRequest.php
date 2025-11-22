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
            'files' => ['nullable', 'array', function ($attribute, $value, $fail) {
                $this->validateFilesByPlan($value, $fail);
            }],
            'files.*' => 'nullable|mimes:png,jpg,mp4,jpeg,webp',
            'phone' => 'nullable|digits:10',
            'district' => 'required',
            'ward' => 'required',
            'category' => 'required|in:' . $category
        ];
    }

    /**
     * Validate files based on user's plan
     */
    protected function validateFilesByPlan($files, $fail)
    {
        $user = auth()->user();
        
        // Admin không bị giới hạn
        if ($user->is_admin) {
            return;
        }
        
        $planCurrent = $user->plan_current ?? 'free';

        // Only validate for free plan
        if ($planCurrent === 'free') {
            $imageCount = 0;
            $videoCount = 0;

            // Count new files being uploaded
            foreach ($files as $file) {
                $mimeType = $file->getMimeType();
                
                if (strpos($mimeType, 'image') !== false) {
                    $imageCount++;
                } elseif (strpos($mimeType, 'video') !== false) {
                    $videoCount++;
                }
            }

            // If editing, count existing files
            $boardingHouseId = $this->route('boarding_house');
            if ($boardingHouseId) {
                $boardingHouse = \App\Models\BoardingHouse::with('boarding_house_files')->find($boardingHouseId);
                if ($boardingHouse) {
                    foreach ($boardingHouse->boarding_house_files as $existingFile) {
                        if ($existingFile->type === 'image') {
                            $imageCount++;
                        } elseif ($existingFile->type === 'video') {
                            $videoCount++;
                        }
                    }
                }
            }

            // Check limits for free plan
            if ($imageCount > 5) {
                $fail('Gói Free chỉ được phép tải lên tối đa 5 ảnh (bao gồm cả ảnh cũ). Bạn hiện có ' . $imageCount . ' ảnh.');
            }

            if ($videoCount > 1) {
                $fail('Gói Free chỉ được phép tải lên tối đa 1 video (bao gồm cả video cũ). Bạn hiện có ' . $videoCount . ' video.');
            }
        }
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
