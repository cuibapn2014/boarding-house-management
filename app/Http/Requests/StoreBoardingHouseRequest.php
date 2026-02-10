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
            'map_link' => 'nullable|url|max:500',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:320',
            'canonical_url' => 'nullable|url|max:500',
            'district' => 'required',
            'ward' => 'required',
            'category' => 'required|in:' . $category,
            'require_deposit' => 'nullable',
            'deposit_amount' => [
                'nullable',
                'min:0',
                function ($attribute, $value, $fail) {
                    if ($this->input('require_deposit') === 'on' && empty($value)) {
                        $fail('Vui lòng nhập số tiền cọc khi yêu cầu cọc.');
                    }
                }
            ],
            'min_contract_months' => 'nullable|integer|min:1',
            'area' => 'nullable|integer|min:1',
            'is_publish' => 'nullable',
            'listing_days' => 'required_if:is_publish,on|nullable|in:10,15,30,60',
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
            
            'map_link.url' => 'Link bản đồ phải là một URL hợp lệ',
            'map_link.max' => 'Link bản đồ không được vượt quá :max ký tự',
            
            'district.required' => 'Vui lòng chọn quận/huyện',
            
            'ward.required' => 'Vui lòng chọn phường/xã',
            
            'category.required' => 'Vui lòng chọn danh mục',
            'category.in' => 'Danh mục được chọn không hợp lệ',
            
            'deposit_amount.integer' => 'Số tiền cọc phải là số nguyên',
            'deposit_amount.min' => 'Số tiền cọc phải lớn hơn hoặc bằng 0',
            
            'min_contract_months.integer' => 'Số tháng hợp đồng tối thiểu phải là số nguyên',
            'min_contract_months.min' => 'Số tháng hợp đồng tối thiểu phải lớn hơn 0',
            
            'area.integer' => 'Diện tích phải là số nguyên',
            'area.min' => 'Diện tích phải lớn hơn 0',
            'listing_days.required_if' => 'Khi đăng tin bạn phải chọn thời gian hiển thị (10, 15, 30 hoặc 60 ngày).',
            'listing_days.in' => 'Thời gian hiển thị phải là 10, 15, 30 hoặc 60 ngày.',
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
            'map_link' => 'Link bản đồ',
            'district' => 'Quận/Huyện',
            'ward' => 'Phường/Xã',
            'category' => 'Danh mục',
            'require_deposit' => 'Yêu cầu cọc',
            'deposit_amount' => 'Số tiền cọc',
            'min_contract_months' => 'Hợp đồng tối thiểu',
            'area' => 'Diện tích',
            'listing_days' => 'Thời gian hiển thị tin',
        ];
    }
}
