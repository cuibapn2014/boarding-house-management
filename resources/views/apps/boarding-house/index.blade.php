@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Quản lý Nhà trọ')
@push('css')
<style>
    .boarding-house-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    .boarding-house-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }
    .search-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }
    .filter-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        border: 2px solid;
        transition: all 0.3s ease;
        cursor: pointer;
        background: white;
    }
    .filter-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    .filter-badge.active {
        background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%) !important;
        color: white !important;
        border-color: #5e72e4 !important;
    }
    .filter-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 12px;
        padding: 6px 12px;
    }
    @media (max-width: 991px) {
        .filter-container {
            margin-top: 0.5rem;
        }
    }
    .clear-filter-btn {
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s ease;
    }
    .clear-filter-btn.show {
        opacity: 1;
        pointer-events: auto;
    }
    .filter-indicator {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 20px;
        height: 20px;
        background: #ff4444;
        border-radius: 50%;
        font-size: 10px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    @media (max-width: 767px) {
        .header-actions {
            width: 100%;
            margin-top: 1rem;
        }
        .add-btn {
            flex: 1;
            justify-content: center;
        }
    }
    #advance-filter {
        transition: all 0.3s ease;
    }
    #advance-filter .card {
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .form-control-sm {
        font-size: 0.875rem;
    }
    .card-body .input-group .form-control {
        font-size: 0.9375rem;
    }
    .card-body .input-group .input-group-text {
        font-size: 0.9375rem;
    }
    .form-control:focus {
        border-color: #5e72e4;
        box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.25);
    }
    .btn-outline-primary {
        border-width: 2px;
        font-weight: 600;
    }
    .btn-outline-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(94, 114, 228, 0.4);
    }
    .btn-outline-danger {
        border-width: 2px;
        font-weight: 600;
    }
    .btn-outline-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 68, 68, 0.4);
    }
    .toggle-icon {
        transition: transform 0.3s ease;
        font-size: 12px;
    }
    .toggle-icon.rotate {
        transform: rotate(180deg);
    }
    .search-card {
        transition: margin-bottom 0.3s ease;
    }
    #advance-filter.show {
        animation: slideDown 0.3s ease;
    }
    .action-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .action-btn:hover {
        transform: scale(1.1);
    }
    .thumbnail-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        height: 200px;
    }
    .thumbnail-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .boarding-house-card:hover .thumbnail-wrapper img {
        transform: scale(1.1);
    }
    .price-tag {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    .status-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        backdrop-filter: blur(10px);
    }
    .header-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }
    .add-btn {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        color: white !important;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.6);
        color: white !important;
        text-decoration: none;
    }
</style>
@endpush
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Quản lý Nhà trọ'])
@php
use App\Constants\SystemDefination;

$status = SystemDefination::BOARDING_HOUSE_STATUS;
$categories = SystemDefination::BOARDING_HOUSE_CATEGORY;
$furnitureStatus = SystemDefination::BOARDING_HOUSE_FURNITURE_STATUS;
@endphp
<div class="container-fluid py-4">
    <!-- Header Actions -->
    <div class="row mb-4">
        <div class="col-12" style="z-index: 9999;">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <!-- <div>
                    <h4 class="text-dark font-weight-bold mb-0">Danh sách Nhà trọ</h4>
                    <p class="text-sm text-muted mb-0">Quản lý tất cả nhà trọ của bạn</p>
                </div> -->
                <div class="header-actions">
                    <a href="{{ route('boarding-house.create') }}" class="btn add-btn">
                        <i class="fas fa-plus me-2"></i>Thêm Nhà trọ mới
                    </a>
                    <button id="btn-advance-filter" class="btn btn-light position-relative" type="button"
                        data-bs-toggle="collapse" data-bs-target="#advance-filter" aria-expanded="false">
                        <i class="fa-solid fa-sliders-h me-2"></i>Bộ lọc nâng cao
                        <i class="fas fa-chevron-down ms-2 toggle-icon"></i>
                        <span class="filter-indicator d-none" id="filter-count">0</span>
                    </button>
                    <button id="btn-clear-filter" class="btn btn-danger clear-filter-btn" type="button">
                        <i class="fas fa-times me-2"></i>Xóa bộ lọc
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="search-card card">
                <div class="card-body p-3">
                    <form id="form-search__boarding-house">
                        <!-- Compact Search and Quick Filters -->
                        <div class="row g-3 align-items-center">
                            <div class="col-lg-5 col-md-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-primary"></i>
                                    </span>
                                    <input id="byTitle" type="search" class="form-control border-start-0 ps-0" 
                                        name="byTitle" placeholder="Tìm kiếm nhà trọ...">
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-12">
                                <div class="filter-container">
                                    <div class="d-flex gap-2 flex-wrap align-items-center justify-content-lg-end">
                                        <span class="filter-badge border-primary text-primary active" data-filter="all">
                                            <i class="fas fa-layer-group me-1"></i>Tất cả
                                        </span>
                                        <span class="filter-badge border-success text-success" data-filter="available">
                                            <i class="fas fa-check-circle me-1"></i>Còn trống
                                        </span>
                                        <span class="filter-badge border-warning text-warning" data-filter="rented">
                                            <i class="fas fa-home me-1"></i>Đã thuê
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Advanced Filter -->
                        <div class="collapse mt-3" id="advance-filter">
                            <div class="card border-0 bg-light mb-0">
                                <div class="card-body p-3">
                                    <h6 class="text-dark font-weight-bold mb-3">
                                        <i class="fas fa-sliders-h me-2 text-primary"></i>Bộ lọc chi tiết
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label text-sm font-weight-bold mb-1">
                                                <i class="fas fa-tag me-1 text-muted"></i>Danh mục
                                            </label>
                                            <select class="form-control form-control-sm" id="byCategory" name="byCategory">
                                                <option value="">Tất cả danh mục</option>
                                                @foreach($categories as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label text-sm font-weight-bold mb-1">
                                                <i class="fas fa-money-bill me-1 text-muted"></i>Giá từ
                                            </label>
                                            <input id="byFromPrice" type="text" class="form-control form-control-sm number-separator"
                                                name="byFromPrice" placeholder="0 VNĐ" />
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label text-sm font-weight-bold mb-1">
                                                <i class="fas fa-money-bill-wave me-1 text-muted"></i>Giá đến
                                            </label>
                                            <input id="byToPrice" type="text" class="form-control form-control-sm number-separator"
                                                name="byToPrice" placeholder="6,000,000 VNĐ" />
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label text-sm font-weight-bold mb-1">
                                                <i class="fas fa-info-circle me-1 text-muted"></i>Trạng thái
                                            </label>
                                            <select class="form-control form-control-sm" id="byStatus" name="byStatus">
                                                <option value="">Tất cả trạng thái</option>
                                                @foreach($status as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label text-sm font-weight-bold mb-1">
                                                <i class="fas fa-couch me-1 text-muted"></i>Nội thất
                                            </label>
                                            <select class="form-control form-control-sm" id="byFurnitureStatus" name="byFurnitureStatus">
                                                <option value="">Tất cả</option>
                                                @foreach($furnitureStatus as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label text-sm font-weight-bold mb-1">
                                                <i class="fas fa-eye me-1 text-muted"></i>Hiển thị
                                            </label>
                                            <select class="form-control form-control-sm" id="byPublish" name="byPublish">
                                                <option value="">Tất cả</option>
                                                <option value="0">Chưa publish</option>
                                                <option value="1">Đã publish</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Boarding Houses Grid -->
    <div class="row list-data">
        @forelse($boardingHouses as $boardingHouse)
            @php
            $thumbnail = $boardingHouse?->boarding_house_files?->where('type', 'image')?->first();
            @endphp
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card boarding-house-card h-100">
                    <div class="thumbnail-wrapper">
                        @if($thumbnail && $thumbnail->type === 'image')
                            <img src="{{ $thumbnail->url }}" alt="{{ $boardingHouse->title }}" loading="lazy">
                        @else
                            <img src="{{ \Storage::url('images/image.jpg') }}" alt="Default">
                        @endif
                        
                        <!-- Status Badge -->
                        <span class="status-badge {{ $boardingHouse->status == 'available' ? 'bg-success' : 'bg-warning' }} text-white">
                            {{ $status[$boardingHouse->status] }}
                        </span>
                        
                        <!-- Price Tag -->
                        <div class="price-tag">
                            {{ numberFormatVi($boardingHouse->price) }} <sup>đ</sup>/tháng
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title text-dark font-weight-bold mb-0" style="font-size: 16px;">
                                {{ Str::limit($boardingHouse->title, 50) }}
                            </h5>
                            @if($boardingHouse->is_publish)
                                <i class="fas fa-eye text-success" title="Đã publish"></i>
                            @else
                                <i class="fas fa-eye-slash text-danger" title="Chưa publish"></i>
                            @endif
                        </div>

                        <p class="text-xs text-muted mb-2">
                            <i class="fas fa-tag me-1"></i>{{ $boardingHouse->category }}
                        </p>

                        @if($boardingHouse->furniture_status)
                        <p class="text-xs text-muted mb-2">
                            <i class="fas fa-couch me-1"></i>{{ $furnitureStatus[$boardingHouse->furniture_status] ?? '' }}
                        </p>
                        @endif

                        <p class="text-xs text-muted mb-3">
                            <i class="far fa-clock me-1"></i>Tạo: {{ date('d/m/Y H:i', strtotime($boardingHouse->created_at)) }}
                        </p>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <div class="d-flex gap-2">
                                <a href="{{ route('boarding-house.edit', [$boardingHouse->id]) }}" 
                                    class="action-btn bg-gradient-primary text-white" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="javascript:;"
                                    data-url="{{ route('boarding-house.createAppointment', ['id' => $boardingHouse->id]) }}"
                                    class="action-btn bg-gradient-info text-white create-appointment" title="Tạo cuộc hẹn">
                                    <i class="far fa-calendar-plus"></i>
                                </a>
                                <a href="javascript:;"
                                    data-url="{{ route('boarding-house.create', ['id' => $boardingHouse->id]) }}"
                                    class="action-btn bg-gradient-secondary text-white clone-boarding-house" title="Sao chép">
                                    <i class="far fa-copy"></i>
                                </a>
                            </div>
                            <a href="javascript:;"
                                data-url="{{ route('boarding-house.destroy', [$boardingHouse->id]) }}"
                                class="action-btn bg-gradient-danger text-white remove-boarding-house" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-home fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có nhà trọ nào</h5>
                        <p class="text-sm text-muted">Hãy thêm nhà trọ đầu tiên của bạn</p>
                        <a href="{{ route('boarding-house.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-2"></i>Thêm ngay
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($boardingHouses->count() > 0 && $boardingHouses->hasPages(2))
    <div class="row mt-4" id="pagination-container">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ $boardingHouses->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@include('components.modal', [
    'id' => 'createAppointment',
    'title' => 'Tạo cuộc hẹn xem phòng',
    'size' => 'lg'
])

@include('components.modal', [
    'id' => 'confirmDeleteBoardingHouse',
    'title' => 'Xác nhận xoá',
    'size' => 'md',
    'okText' => 'Chắc chắn',
    'btnId' => 'btn-confirm-delete'
])
@endsection
@push('js')
{{-- Flatpickr --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr@4.6.13/dist/l10n/vn.js"></script>

<script src="{{ asset('assets/js/apps/boarding_house/script.js') }}"></script>
<script src="{{ asset('assets/js/apps/boarding_house/BoardingHouse.js') }}"></script>
@endpush