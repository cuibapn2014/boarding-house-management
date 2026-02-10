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
    $furnitureStatus = SystemDefination::BOARDING_HOUSE_FURNITURE_STATUS;
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
                                    placeholder="Ví dụ: Phòng trọ cao cấp gần ĐH Bách Khoa" value="{{ $boardingHouse->title }} (Bản sao)" maxlength="255">
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

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Diện tích (m²)</label>
                                <div class="input-group">
                                    <input id="area" name="area" class="form-control" type="number" 
                                        placeholder="Nhập diện tích" min="1" value="{{ $boardingHouse->area ?? '' }}" autocomplete="off">
                                    <span class="input-group-text">m²</span>
                                </div>
                                <small class="text-muted">Không bắt buộc</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="require_deposit" type="checkbox" id="require-deposit" {{ $boardingHouse->require_deposit ? 'checked' : '' }}>
                                    <label class="form-check-label" for="require-deposit">
                                        <span class="font-weight-bold">Yêu cầu cọc</span>
                                        <p class="text-xs text-muted mb-0">Có cần cọc trước khi thuê không?</p>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3" id="deposit-amount-wrapper" style="display: {{ $boardingHouse->require_deposit ? 'block' : 'none' }};">
                                <label class="form-label">Số tiền cọc <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input id="deposit_amount" name="deposit_amount" class="form-control number-separator" type="text" 
                                        placeholder="0" value="{{ $boardingHouse->deposit_amount ? numberFormatVi($boardingHouse->deposit_amount) : '' }}" autocomplete="off">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hợp đồng tối thiểu</label>
                                <div class="input-group">
                                    <input id="min_contract_months" name="min_contract_months" class="form-control" type="number" 
                                        placeholder="Nhập số tháng" min="1" value="{{ $boardingHouse->min_contract_months ?? '' }}" autocomplete="off">
                                    <span class="input-group-text">tháng</span>
                                </div>
                                <small class="text-muted">Không bắt buộc</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Mô tả ngắn</label>
                                <input id="description" name="description" class="form-control" type="text" 
                                    placeholder="Mô tả ngắn gọn về nhà trọ" value="{{ $boardingHouse->description }}" maxlength="255">
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
                                <div id="keyword-stats" class="mt-2" style="display: none;">
                                    <small class="text-muted">
                                        <span id="keyword-density">Mật độ từ khóa: 0.00%.</span>
                                        <span id="keyword-occurrences" class="ms-2">Xuất hiện: 0/0</span>
                                    </small>
                                </div>
                                <small class="text-muted">Nhập các từ khóa liên quan để tối ưu tìm kiếm</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="card form-card mt-4">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="fas fa-search"></i>
                            SEO
                            <button type="button" class="btn btn-sm btn-link p-0 ms-2" data-bs-toggle="collapse" data-bs-target="#seoSection" aria-expanded="true" aria-controls="seoSection">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </h5>
                        <div class="collapse show" id="seoSection">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">* Meta title</label>
                                    <input id="meta_title" name="meta_title" class="form-control" type="text" 
                                        value="{{ $boardingHouse->meta_title ?? '' }}" placeholder="Tự động lấy từ tiêu đề" maxlength="70">
                                    <div id="meta_title_feedback" class="mt-1"></div>
                                    <small class="text-muted">Độ dài tối ưu: 50-60 ký tự. Google hiển thị tối đa 60 ký tự.</small>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Meta description</label>
                                    <textarea id="meta_description" name="meta_description" class="form-control" 
                                        rows="3" placeholder="Tự động lấy từ mô tả" maxlength="320">{{ $boardingHouse->meta_description ?? '' }}</textarea>
                                    <div id="meta_description_feedback" class="mt-1"></div>
                                    <small class="text-muted">Độ dài tối ưu: 120-160 ký tự. Google hiển thị tối đa 160 ký tự.</small>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Canonical URL</label>
                                    <input id="canonical_url" name="canonical_url" class="form-control" type="url" 
                                        value="{{ $boardingHouse->canonical_url ?? '' }}" placeholder="Tự động lấy từ URL hiện tại" maxlength="500">
                                    <small class="text-muted">URL chuẩn của trang (tự động lấy từ route nếu để trống)</small>
                                </div>
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
                                <label class="form-label">Link bản đồ (Google Maps)</label>
                                <input id="map_link" name="map_link" class="form-control" type="url" 
                                    placeholder="https://maps.google.com/..." value="{{ $boardingHouse->map_link ?? '' }}">
                                <small class="text-muted">Dán link Google Maps để hiển thị vị trí trên bản đồ (tùy chọn)</small>
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
                        @if(auth()->user()->is_admin)
                        <p class="text-sm text-muted mb-3">
                            <i class="fas fa-crown text-warning me-1"></i>
                            <strong>Admin:</strong> Không giới hạn số lượng file
                        </p>
                        @elseif(auth()->user()->plan_current === 'free')
                        <div class="alert alert-warning mb-3" style="font-size: 13px;">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Gói Free:</strong> Tối đa <strong>5 ảnh</strong> và <strong>1 video</strong>
                        </div>
                        @else
                        <p class="text-sm text-muted mb-3">Tải lên hình ảnh hoặc video về nhà trọ (tối đa 10 file)</p>
                        @endif
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
                            <label class="form-label">Tình trạng nội thất</label>
                            <select id="furniture_status" name="furniture_status" class="form-control">
                                <option value="">Chọn tình trạng nội thất</option>
                                @foreach($furnitureStatus as $k => $fs)
                                <option value="{{ $k }}" {{ $boardingHouse->furniture_status == $k ? 'selected' : '' }}>{{ $fs }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="is_publish" type="checkbox" id="is-publish" value="on">
                                <label class="form-check-label" for="is-publish">
                                    <span class="font-weight-bold">Đăng tin ngay (cần thanh toán)</span>
                                    <p class="text-xs text-muted mb-0">Bật để đăng tin — phí theo thời gian hiển thị</p>
                                </label>
                            </div>
                        </div>

                        <div class="mb-4" id="listing-duration-wrapper" style="display: none;">
                            <label class="form-label">Thời gian hiển thị tin <span class="text-danger">*</span></label>
                            <select id="listing_days" name="listing_days" class="form-control">
                                <option value="">Chọn thời gian</option>
                                @foreach($listingDurationPoints ?? [] as $days => $points)
                                <option value="{{ $days }}">{{ $days }} ngày — {{ $points }} point</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4 p-3 rounded bg-light border">
                            <span class="form-label mb-0 d-block">Số dư hiện tại</span>
                            <span class="fw-bold text-primary">{{ number_format($userPoints ?? 0) }} point</span>
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

                        @if(($draftCount ?? 0) >= 1)
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-info-circle me-2"></i>Bạn đã có 1 tin nháp. Chỉ được tối đa 1 tin nháp.
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 mt-4">
                            @if(($draftCount ?? 0) < 1)
                            <button type="button" id="btn-save-draft" class="btn btn-outline-secondary">
                                <i class="fas fa-file-alt me-2"></i>Lưu nháp
                            </button>
                            @endif
                            <button type="submit" id="btn-submit-publish" class="btn submit-btn">
                                <i class="fas fa-check-circle me-2"></i>Thanh toán & Đăng tin
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
<script>
    // Set user plan for Dropzone validation
    Dropzone.userPlan = '{{ auth()->user()->plan_current ?? "free" }}';
    Dropzone.isAdmin = {{ auth()->user()->is_admin ? 'true' : 'false' }};
</script>
<script src="{{ asset('assets/js/apps/boarding_house/form-page.js') }}?v=1.0.0"></script>
<script>
    // Toggle deposit amount field based on require_deposit checkbox
    document.addEventListener('DOMContentLoaded', function() {
        const requireDepositCheckbox = document.getElementById('require-deposit');
        const depositAmountWrapper = document.getElementById('deposit-amount-wrapper');
        const depositAmountInput = document.getElementById('deposit_amount');

        if (requireDepositCheckbox && depositAmountWrapper) {
            requireDepositCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    depositAmountWrapper.style.display = 'block';
                    depositAmountInput.setAttribute('required', 'required');
                } else {
                    depositAmountWrapper.style.display = 'none';
                    depositAmountInput.removeAttribute('required');
                    depositAmountInput.value = '';
                }
            });
        }
        const isPublish = document.getElementById('is-publish');
        const listingDurationWrapper = document.getElementById('listing-duration-wrapper');
        const listingDays = document.getElementById('listing_days');
        if (isPublish && listingDurationWrapper) {
            isPublish.addEventListener('change', function() {
                listingDurationWrapper.style.display = this.checked ? 'block' : 'none';
                if (!this.checked) listingDays.removeAttribute('required');
                else listingDays.setAttribute('required', 'required');
            });
        }
        const btnSaveDraft = document.getElementById('btn-save-draft');
        const formCreate = document.getElementById('formCreateBoardingHouse');
        if (btnSaveDraft && formCreate) {
            btnSaveDraft.addEventListener('click', function() {
                if (document.getElementById('is-publish')) document.getElementById('is-publish').checked = false;
                if (listingDays) listingDays.removeAttribute('required');
                formCreate.submit();
            });
        }
        if (formCreate && listingDays) {
            formCreate.addEventListener('submit', function(e) {
                const isPublishEl = document.getElementById('is-publish');
                if (isPublishEl && isPublishEl.checked && !listingDays.value) {
                    e.preventDefault();
                    alert('Vui lòng chọn thời gian hiển thị tin đăng.');
                    listingDays.focus();
                    return false;
                }
            });
        }
    });
</script>
@endpush
