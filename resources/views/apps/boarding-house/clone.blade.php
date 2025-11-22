@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Sao chép Nhà trọ')
@push('css')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
<style>
    .form-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #344767;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }
    .section-title i {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        font-size: 14px;
        margin-right: 10px;
    }
    .form-label {
        font-weight: 600;
        color: #344767;
        margin-bottom: 8px;
        font-size: 13px;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #d2d6da;
        padding: 10px 15px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
    }
    .submit-btn {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        border: none;
        border-radius: 12px;
        padding: 12px 32px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
    }
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.6);
    }
    .cancel-btn {
        border: 2px solid #d2d6da;
        border-radius: 12px;
        padding: 12px 32px;
        color: #67748e;
        font-weight: 600;
        background: white;
        transition: all 0.3s ease;
    }
    .cancel-btn:hover {
        border-color: #4CAF50;
        color: #4CAF50;
        background: #f8f9fa;
    }
</style>
@endpush
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Sao chép Nhà trọ'])
@php
    use App\Constants\SystemDefination;

    $status = SystemDefination::BOARDING_HOUSE_STATUS;
    $categories = SystemDefination::BOARDING_HOUSE_CATEGORY;
@endphp

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="text-dark font-weight-bold mb-0">Sao chép Nhà trọ</h4>
                    <p class="text-sm text-muted mb-0">Tạo bản sao từ: {{ $boardingHouse->title }}</p>
                </div>
                <a href="{{ route('boarding-house.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Info -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info border-0 text-white">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Lưu ý:</strong> Đây là bản sao từ nhà trọ "<strong>{{ $boardingHouse->title }}</strong>". Bạn có thể chỉnh sửa thông tin trước khi lưu.
            </div>
        </div>
    </div>

    <!-- Form -->
    <form id="formCreateBoardingHouse" action="{{ route('boarding-house.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Main Information -->
            <div class="col-lg-8 mb-4">
                <div class="card form-card">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Thông tin cơ bản
                        </h5>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                                <input id="title" name="title" class="form-control" type="text" 
                                    placeholder="Ví dụ: Phòng trọ cao cấp gần ĐH Bách Khoa" value="{{ $boardingHouse->title }} (Bản sao)">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                                <select id="category" name="category" class="form-control">
                                    <option value="">Chọn danh mục</option>
                                    @foreach($categories as $k => $category)
                                    <option value="{{ $k }}" {{ $boardingHouse->category === $k ? 'selected' : '' }}>{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giá thuê/tháng <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input id="price" name="price" class="form-control number-separator" type="text" 
                                        value="{{ numberFormatVi($boardingHouse->price) }}" placeholder="0" autocomplete="off">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Mô tả ngắn</label>
                                <input id="description" name="description" class="form-control" type="text" 
                                    placeholder="Mô tả ngắn gọn về nhà trọ" value="{{ $boardingHouse->description }}">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-file-alt me-1"></i>Nội dung chi tiết
                                </label>
                                <textarea id="content" name="content" class="form-control tiny-editor" 
                                    placeholder="Mô tả chi tiết về phòng trọ, tiện ích, quy định...">{!! $boardingHouse->content !!}</textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Từ khoá (SEO)</label>
                                <input class="form-control" id="tags" data-color="dark" type="text" name="tags" 
                                    value="{{ $boardingHouse->tags }}" placeholder="Nhập từ khóa và nhấn Enter" />
                                <small class="text-muted">Nhập các từ khóa liên quan để tối ưu tìm kiếm</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="card form-card mt-4">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="fas fa-map-marker-alt"></i>
                            Địa chỉ
                        </h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                <select id="district" name="district" class="form-control">
                                    <option value="">Chọn quận/huyện</option>
                                    <option value="{{ $boardingHouse->district }}" selected>{{ $boardingHouse->district }}</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                <select id="ward" name="ward" class="form-control">
                                    <option value="">Chọn phường/xã</option>
                                    <option value="{{ $boardingHouse->ward }}" selected>{{ $boardingHouse->ward }}</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                                <input id="address" name="address" class="form-control" type="text" 
                                    placeholder="Số nhà, tên đường..." value="{{ $boardingHouse->address }}">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Số điện thoại/Zalo <span class="text-danger">*</span></label>
                                <input id="phone" name="phone" class="form-control" type="text" 
                                    placeholder="Nhập số điện thoại liên hệ" value="{{ $boardingHouse->phone ?? auth()->user()?->phone }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Files -->
                <div class="card form-card mt-4">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="fas fa-images"></i>
                            Hình ảnh & Video
                        </h5>
                        <p class="text-sm text-muted mb-3">Tải lên hình ảnh hoặc video về nhà trọ (tối đa 10 file)</p>
                        <p class="text-sm text-warning mb-3">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Lưu ý: Ảnh từ bản gốc sẽ không được sao chép. Vui lòng tải lên ảnh mới.
                        </p>
                        @include('components.dropzone')
                    </div>
                </div>
            </div>

            <!-- Sidebar Settings -->
            <div class="col-lg-4 mb-4">
                <div class="card form-card">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="fas fa-cog"></i>
                            Cài đặt
                        </h5>

                        <div class="mb-4">
                            <label class="form-label">Trạng thái</label>
                            <select id="status" name="status" class="form-control">
                                @foreach($status as $k => $st)
                                <option value="{{ $k }}" {{ $boardingHouse->status == $k ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="is_publish" type="checkbox" id="is-publish" {{ $boardingHouse->is_publish ? 'checked' : '' }}>
                                <label class="form-check-label" for="is-publish">
                                    <span class="font-weight-bold">Publish</span>
                                    <p class="text-xs text-muted mb-0">Hiển thị công khai trên website</p>
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="alert alert-light border">
                            <h6 class="font-weight-bold text-sm mb-2">
                                <i class="fas fa-lightbulb text-warning me-2"></i>Lưu ý
                            </h6>
                            <ul class="text-xs text-muted mb-0 ps-3">
                                <li>Đây là bản sao từ nhà trọ gốc</li>
                                <li>Kiểm tra và cập nhật thông tin nếu cần</li>
                                <li>Tải lên ảnh mới cho nhà trọ này</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn submit-btn">
                                <i class="fas fa-save me-2"></i>Lưu Nhà trọ
                            </button>
                            <a href="{{ route('boarding-house.index') }}" class="btn cancel-btn">
                                <i class="fas fa-times me-2"></i>Hủy bỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('js')
{{-- Tagify --}}
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>

<script src="{{ asset('assets/js/helper/Dropzone.js') }}"></script>
<script src="{{ asset('assets/js/apps/boarding_house/form-page.js') }}"></script>
@endpush
