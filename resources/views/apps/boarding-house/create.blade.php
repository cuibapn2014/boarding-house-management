@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Thêm Nhà trọ mới')
@push('css')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
<style>
    .form-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }
    .helper-card {
        border-radius: 14px;
        background: #f8f9fa;
        padding: 16px;
        height: 100%;
        border: 1px dashed #d2d6da;
    }
    .helper-icon {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        margin-right: 10px;
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
    .sticky-sidebar {
        position: sticky;
        top: 80px;
    }
    .progress-compact {
        height: 8px;
        border-radius: 20px;
    }
    .checklist {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    .checklist li {
        display: flex;
        align-items: center;
        font-size: 13px;
        color: #67748e;
        margin-bottom: 8px;
    }
    .checklist li .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #d2d6da;
        margin-right: 10px;
        transition: all 0.2s ease;
    }
    .checklist li.completed .status-dot {
        background: #4CAF50;
        box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.15);
    }
    .checklist li.completed {
        color: #2e7d32;
        font-weight: 600;
    }
    @media (max-width: 991px) {
        .sticky-sidebar {
            position: relative;
            top: 0;
        }
    }
</style>
@endpush
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Thêm Nhà trọ mới'])
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
                    <h4 class="text-dark font-weight-bold mb-0"></h4>
                    <p class="text-sm text-muted mb-0"></p>
                </div>
                <a href="{{ route('boarding-house.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Quick helper cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="helper-card d-flex align-items-start">
                <div class="helper-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div>
                    <h6 class="mb-1 text-dark">Nhập đủ thông tin bắt buộc</h6>
                    <p class="text-xs text-muted mb-0">Ưu tiên Tiêu đề, Giá, Danh mục, Địa chỉ và Liên hệ.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="helper-card d-flex align-items-start">
                <div class="helper-icon">
                    <i class="fas fa-camera-retro"></i>
                </div>
                <div>
                    <h6 class="mb-1 text-dark">Tối thiểu 3 hình ảnh</h6>
                    <p class="text-xs text-muted mb-0">Ảnh rõ nét, đủ sáng giúp tăng độ tin cậy cho tin đăng.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="helper-card d-flex align-items-start">
                <div class="helper-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div>
                    <h6 class="mb-1 text-dark">Mô tả ngắn gọn, trọng tâm</h6>
                    <p class="text-xs text-muted mb-0">Nêu ưu điểm nổi bật, tiện ích xung quanh và quy định chính.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form id="formCreateBoardingHouse"
        action="{{ route('boarding-house.store') }}"
        method="POST"
        data-user-plan="{{ auth()->user()->plan_current ?? 'free' }}"
        data-is-admin="{{ auth()->user()?->is_admin ? '1' : '0' }}">
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
                                <input id="title" name="title" class="form-control" type="text" data-quick-required="title"
                                    placeholder="Ví dụ: Phòng trọ cao cấp gần ĐH Bách Khoa" maxlength="255">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                                <select id="category" name="category" class="form-control" data-quick-required="category">
                                    <option value="">Chọn danh mục</option>
                                    @foreach($categories as $k => $category)
                                    <option value="{{ $k }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giá thuê/tháng <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input id="price" name="price" class="form-control number-separator" type="text" data-quick-required="price" 
                                        value="0" placeholder="0" autocomplete="off">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Diện tích (m²)</label>
                                <div class="input-group">
                                    <input id="area" name="area" class="form-control number-separator" type="text" 
                                        maxlength="3"
                                        placeholder="Nhập diện tích" min="1" autocomplete="off">
                                    <span class="input-group-text">m²</span>
                                </div>
                                <small class="text-muted">Không bắt buộc</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="require_deposit" type="checkbox" id="require-deposit">
                                    <label class="form-check-label" for="require-deposit">
                                        <span class="font-weight-bold">Yêu cầu cọc</span>
                                        <p class="text-xs text-muted mb-0">Có cần cọc trước khi thuê không?</p>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3" id="deposit-amount-wrapper" style="display: none;">
                                <label class="form-label">Số tiền cọc <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input id="deposit_amount" name="deposit_amount" class="form-control number-separator" type="text" 
                                        placeholder="0" autocomplete="off">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hợp đồng tối thiểu</label>
                                <div class="input-group">
                                    <input id="min_contract_months" name="min_contract_months" class="form-control" type="number" 
                                        placeholder="Nhập số tháng" min="1" autocomplete="off">
                                    <span class="input-group-text">tháng</span>
                                </div>
                                <small class="text-muted">Không bắt buộc</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Mô tả ngắn</label>
                                <input id="description" name="description" class="form-control" type="text" 
                                    placeholder="Mô tả ngắn gọn về nhà trọ" maxlength="255">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-file-alt me-1"></i>Nội dung chi tiết
                                </label>
                                <textarea id="content" name="content" class="form-control tiny-editor" 
                                    placeholder="Mô tả chi tiết về phòng trọ, tiện ích, quy định..."></textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Từ khoá (SEO)</label>
                                <input class="form-control" id="tags" data-color="dark" type="text" name="tags" 
                                    value="" placeholder="Nhập từ khóa và nhấn Enter" />
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
                                        placeholder="Tự động lấy từ tiêu đề" maxlength="70">
                                    <div id="meta_title_feedback" class="mt-1"></div>
                                    <small class="text-muted">Độ dài tối ưu: 50-60 ký tự. Google hiển thị tối đa 60 ký tự.</small>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Meta description</label>
                                    <textarea id="meta_description" name="meta_description" class="form-control" 
                                        rows="3" placeholder="Tự động lấy từ mô tả" maxlength="320"></textarea>
                                    <div id="meta_description_feedback" class="mt-1"></div>
                                    <small class="text-muted">Độ dài tối ưu: 120-160 ký tự. Google hiển thị tối đa 160 ký tự.</small>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Canonical URL</label>
                                    <input id="canonical_url" name="canonical_url" class="form-control" type="url" 
                                        placeholder="Tự động lấy từ URL hiện tại" maxlength="500">
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
                                <select id="district" name="district" class="form-control" data-quick-required="district">
                                    <option value="">Chọn quận/huyện</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                <select id="ward" name="ward" class="form-control" data-quick-required="ward">
                                    <option value="">Chọn phường/xã</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                                <input id="address" name="address" class="form-control" type="text" data-quick-required="address"
                                    placeholder="Số nhà, tên đường...">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Link bản đồ (Google Maps)</label>
                                <input id="map_link" name="map_link" class="form-control" type="url" 
                                    placeholder="https://maps.google.com/...">
                                <small class="text-muted">Dán link Google Maps để hiển thị vị trí trên bản đồ (tùy chọn)</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Số điện thoại/Zalo <span class="text-danger">*</span></label>
                                <input id="phone" name="phone" class="form-control" type="text" data-quick-required="phone" 
                                    placeholder="Nhập số điện thoại liên hệ" value="{{ auth()->user()?->phone }}">
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
                        @include('components.dropzone')
                    </div>
                </div>
            </div>

            <!-- Sidebar Settings -->
            <div class="col-lg-4 mb-4">
                <div class="card form-card sticky-sidebar">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="fas fa-cog"></i>
                            Cài đặt
                        </h5>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="form-label mb-0">Tiến độ hoàn thiện</span>
                                <span class="badge text-success" id="completion-percentage-badge" style="background: #e8f5e9; border: 1px solid #a5d6a7;">0%</span>
                            </div>
                            <div class="progress progress-compact mb-2">
                                <div class="progress-bar bg-success" id="completion-progress" role="progressbar" style="width: 0%;"></div>
                            </div>
                            <ul class="checklist">
                                <li data-checklist-item="title"><span class="status-dot"></span>Tiêu đề</li>
                                <li data-checklist-item="category"><span class="status-dot"></span>Danh mục</li>
                                <li data-checklist-item="price"><span class="status-dot"></span>Giá thuê</li>
                                <li data-checklist-item="district"><span class="status-dot"></span>Quận/Huyện</li>
                                <li data-checklist-item="ward"><span class="status-dot"></span>Phường/Xã</li>
                                <li data-checklist-item="address"><span class="status-dot"></span>Địa chỉ</li>
                                <li data-checklist-item="phone"><span class="status-dot"></span>Liên hệ</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Trạng thái</label>
                            <select id="status" name="status" class="form-control">
                                @foreach($status as $k => $st)
                                <option value="{{ $k }}">{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Tình trạng nội thất</label>
                            <select id="furniture_status" name="furniture_status" class="form-control">
                                <option value="">Chọn tình trạng nội thất</option>
                                @foreach($furnitureStatus as $k => $fs)
                                <option value="{{ $k }}">{{ $fs }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="is_publish" type="checkbox" id="is-publish" checked="">
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
                                <li>Điền đầy đủ thông tin để tăng độ tin cậy</li>
                                <li>Tải lên ít nhất 3 hình ảnh chất lượng</li>
                                <li>Mô tả chi tiết để thu hút khách hàng</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn submit-btn">
                                <i class="fas fa-save me-2"></i>Lưu tin đăng
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
<script src="{{ asset('assets/js/apps/boarding_house/form-page.js') }}?v=1.0.0"></script>
<script>
    // UI helpers for deposit toggle, checklist progress and meta feedback
    document.addEventListener('DOMContentLoaded', function() {
        const formEl = document.getElementById('formCreateBoardingHouse');
        if (formEl && window.Dropzone) {
            Dropzone.userPlan = formEl.dataset.userPlan || 'free';
            Dropzone.isAdmin = formEl.dataset.isAdmin === '1';
        }

        const requireDepositCheckbox = document.getElementById('require-deposit');
        const depositAmountWrapper = document.getElementById('deposit-amount-wrapper');
        const depositAmountInput = document.getElementById('deposit_amount');

        if (requireDepositCheckbox && depositAmountWrapper) {
            requireDepositCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    depositAmountWrapper.style.display = 'block';
                    depositAmountInput?.setAttribute('required', 'required');
                } else {
                    depositAmountWrapper.style.display = 'none';
                    depositAmountInput?.removeAttribute('required');
                    depositAmountInput.value = '';
                }
            });
        }

        const requiredFields = ['title', 'category', 'price', 'district', 'ward', 'address', 'phone'];
        const progressEl = document.getElementById('completion-progress');
        const progressBadge = document.getElementById('completion-percentage-badge');

        const updateChecklist = () => {
            let completed = 0;

            requiredFields.forEach(fieldId => {
                const el = document.getElementById(fieldId);
                const checklistItem = document.querySelector(`[data-checklist-item="${fieldId}"]`);
                const value = el?.type === 'checkbox' ? el.checked : el?.value?.trim();
                const isDone = !!value;

                if (isDone) completed += 1;

                if (checklistItem) {
                    checklistItem.classList.toggle('completed', isDone);
                }
            });

            const percent = Math.round((completed / requiredFields.length) * 100);
            if (progressEl) progressEl.style.width = `${percent}%`;
            if (progressBadge) progressBadge.textContent = `${percent}%`;
        };

        requiredFields.forEach(fieldId => {
            const el = document.getElementById(fieldId);
            if (!el) return;

            const eventName = ['SELECT', 'OPTION'].includes(el.tagName) ? 'change' : 'input';
            el.addEventListener(eventName, updateChecklist);
        });

        updateChecklist();

        const metaTitle = document.getElementById('meta_title');
        const metaTitleFeedback = document.getElementById('meta_title_feedback');
        const metaDesc = document.getElementById('meta_description');
        const metaDescFeedback = document.getElementById('meta_description_feedback');

        const updateMetaFeedback = (inputEl, feedbackEl, optimalMin, optimalMax) => {
            if (!inputEl || !feedbackEl) return;
            const length = inputEl.value.length;
            const inRange = length >= optimalMin && length <= optimalMax;
            feedbackEl.innerHTML = `<small class="${inRange ? 'text-success' : 'text-muted'}">Độ dài: ${length}/${inputEl.maxLength || '∞'} ký tự${inRange ? ' (tối ưu)' : ''}</small>`;
        };

        metaTitle?.addEventListener('input', () => updateMetaFeedback(metaTitle, metaTitleFeedback, 50, 60));
        metaDesc?.addEventListener('input', () => updateMetaFeedback(metaDesc, metaDescFeedback, 120, 160));

        updateMetaFeedback(metaTitle, metaTitleFeedback, 50, 60);
        updateMetaFeedback(metaDesc, metaDescFeedback, 120, 160);
    });
</script>
@endpush