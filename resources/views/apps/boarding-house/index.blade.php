@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Quản lý tin đăng')
@push('css')
<style>
    /* Modern Card Design */
    .boarding-house-card {
        border: none;
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        background: #ffffff;
        position: relative;
    }
    .boarding-house-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .boarding-house-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .boarding-house-card:hover::before {
        opacity: 1;
    }

    /* Search Card */
    .search-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        backdrop-filter: blur(10px);
    }

    /* Filter Badges */
    .filter-badge {
        padding: 8px 18px;
        border-radius: 25px;
        font-size: 13px;
        font-weight: 600;
        border: 2px solid;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        background: white;
        position: relative;
        overflow: hidden;
    }
    .filter-badge::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    .filter-badge:hover::before {
        width: 300px;
        height: 300px;
    }
    .filter-badge:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .filter-badge.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        border-color: transparent !important;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    /* Filter Container */
    .filter-container {
        background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%);
        border-radius: 16px;
        padding: 10px 16px;
        border: 1px solid rgba(102, 126, 234, 0.1);
    }

    /* Clear Filter Button */
    .clear-filter-btn {
        display: none;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s ease;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
    }
    .clear-filter-btn.show {
        display: inline-flex;
        opacity: 1;
        pointer-events: auto;
        animation: slideInRight 0.3s ease;
    }
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Filter Indicator */
    .filter-indicator {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 22px;
        height: 22px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 50%;
        font-size: 11px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        animation: pulse 2s infinite;
        box-shadow: 0 2px 8px rgba(245, 87, 108, 0.4);
        border: 2px solid white;
    }
    @keyframes pulse {
        0%, 100% { 
            transform: scale(1);
            box-shadow: 0 2px 8px rgba(245, 87, 108, 0.4);
        }
        50% { 
            transform: scale(1.15);
            box-shadow: 0 4px 16px rgba(245, 87, 108, 0.6);
        }
    }

    /* Header Actions */
    .header-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }
    .add-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 14px;
        padding: 14px 28px;
        color: white !important;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        font-size: 15px;
    }
    .add-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        color: white !important;
        text-decoration: none;
    }
    .add-btn:active {
        transform: translateY(-1px);
    }

    /* Advanced Filter */
    #advance-filter {
        transition: all 0.3s ease;
    }
    #advance-filter .card {
        animation: slideDown 0.3s ease;
        border-radius: 16px;
        background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
        border: 1px solid rgba(102, 126, 234, 0.1);
    }
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Form Controls */
    .form-control, .form-select {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        padding: 10px 16px;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    .form-control-sm {
        font-size: 0.875rem;
        padding: 8px 14px;
    }
    .input-group-text {
        background: white;
        border: 1.5px solid #e2e8f0;
        border-right: none;
        border-radius: 12px 0 0 12px;
    }
    .input-group .form-control {
        border-left: none;
        border-radius: 0 12px 12px 0;
    }
    .input-group .form-control:focus {
        border-left: 1.5px solid #667eea;
    }

    /* Toggle Icon */
    .toggle-icon {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 12px;
    }
    .toggle-icon.rotate {
        transform: rotate(180deg);
    }

    /* Thumbnail Wrapper */
    .thumbnail-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 16px 16px 0 0;
        height: 220px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .thumbnail-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .boarding-house-card:hover .thumbnail-wrapper img {
        transform: scale(1.15);
    }

    /* Price Tag */
    .price-tag {
        position: absolute;
        bottom: 16px;
        right: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 18px;
        border-radius: 25px;
        font-weight: 700;
        font-size: 15px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        backdrop-filter: blur(10px);
        z-index: 2;
    }
    .price-tag sup {
        font-size: 11px;
        font-weight: 500;
        opacity: 0.9;
    }

    /* Status Badge */
    .status-badge {
        position: absolute;
        top: 16px;
        left: 16px;
        padding: 8px 14px;
        border-radius: 25px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        backdrop-filter: blur(10px);
        z-index: 2;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        letter-spacing: 0.5px;
    }
    .status-badge.bg-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    }
    .status-badge.bg-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    }

    /* Card Body */
    .card-body {
        padding: 20px;
    }
    .card-title {
        font-size: 17px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.4;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Info Items */
    .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #64748b;
        margin-bottom: 8px;
    }
    .info-item i {
        width: 18px;
        color: #667eea;
        font-size: 12px;
    }

    /* Action Buttons */
    .action-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }
    .action-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    .action-btn:hover::before {
        width: 200px;
        height: 200px;
    }
    .action-btn:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    .action-btn i {
        position: relative;
        z-index: 1;
    }

    /* Empty State */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 24px;
        background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: #667eea;
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    .empty-state h5 {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }
    .empty-state p {
        font-size: 15px;
        color: #64748b;
        margin-bottom: 24px;
    }

    /* Pagination */
    .pagination {
        justify-content: center;
    }
    .pagination .page-link {
        border-radius: 10px;
        margin: 0 4px;
        border: 1.5px solid #e2e8f0;
        color: #64748b;
        padding: 10px 16px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .pagination .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Publish Icon */
    .publish-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .publish-icon.text-success {
        background: rgba(16, 185, 129, 0.1);
    }
    .publish-icon.text-danger {
        background: rgba(239, 68, 68, 0.1);
    }

    /* Responsive */
    @media (max-width: 991px) {
        .filter-container {
            margin-top: 0.75rem;
        }
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
        .thumbnail-wrapper {
            height: 180px;
        }
        .card-body {
            padding: 16px;
        }
        .action-btn {
            width: 36px;
            height: 36px;
        }
    }

    /* Loading State */
    .loading-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Smooth Scroll */
    html {
        scroll-behavior: smooth;
    }

    /* Filter Button Hover */
    #btn-advance-filter:hover {
        background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%) !important;
        border-color: #667eea !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }
    #btn-advance-filter[aria-expanded="true"] {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        border-color: transparent !important;
    }
    #btn-advance-filter[aria-expanded="true"] i {
        color: white !important;
    }

    /* Card Stats */
    .card-stats {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-item {
        flex: 1;
        background: white;
        padding: 16px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: all 0.3s ease;
    }
    .stat-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }
    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }
    .stat-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }

    /* Creator Info */
    .creator-info {
        background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%);
        border-radius: 12px;
        padding: 12px;
        margin-top: 12px;
        transition: all 0.3s ease;
    }

    .creator-info:hover {
        background: linear-gradient(135deg, #e8ebff 0%, #d8dfff 100%);
        /* box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1); */
    }
    .creator-toggle-btn {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .creator-toggle-btn:hover {
        opacity: 0.8;
    }
    .creator-toggle-btn:focus {
        box-shadow: none;
        outline: none;
    }
    .creator-toggle-icon {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .creator-toggle-btn[aria-expanded="true"] .creator-toggle-icon {
        transform: rotate(180deg);
    }
    .creator-avatar-small {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    .creator-avatar-large {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e2e8f0;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .creator-avatar-large:hover {
        transform: scale(1.1);
        border-color: #667eea !important;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    .creator-details {
        padding: 8px 0;
        animation: slideDown 0.3s ease;
    }
    .creator-detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #64748b;
        margin-bottom: 6px;
    }
    .creator-detail-item i {
        width: 16px;
        color: #667eea;
        font-size: 11px;
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
</style>
@endpush
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Quản lý tin đăng'])
@php
use App\Constants\SystemDefination;

$status = SystemDefination::BOARDING_HOUSE_STATUS;
$categories = SystemDefination::BOARDING_HOUSE_CATEGORY;
$furnitureStatus = SystemDefination::BOARDING_HOUSE_FURNITURE_STATUS;
@endphp
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12" style="z-index: 9999;">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="mb-3 mb-md-0">
                    <h4 class="text-dark font-weight-bold mb-1" style="font-size: 24px; color: #1e293b;"></h4>
                    <p class="text-sm text-muted mb-0" style="color: #64748b;"></p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('boarding-house.create') }}" class="btn add-btn">
                        <i class="fas fa-plus me-2"></i>Thêm mới
                    </a>
                    <button id="btn-advance-filter" class="btn btn-light position-relative" type="button"
                        data-bs-toggle="collapse" data-bs-target="#advance-filter" aria-expanded="false"
                        style="border-radius: 12px; padding: 12px 20px; font-weight: 600; border: 1.5px solid #e2e8f0; transition: all 0.3s ease; background: white;">
                        <i class="fa-solid fa-sliders-h me-2"></i>Bộ lọc
                        <i class="fas fa-chevron-down ms-2 toggle-icon"></i>
                        <span class="filter-indicator d-none" id="filter-count">0</span>
                    </button>
                    <button id="btn-clear-filter" class="btn btn-danger clear-filter-btn" type="button"
                        style="border-radius: 12px; font-weight: 600;">
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
                                <div class="input-group" style="box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); border-radius: 12px; overflow: hidden;">
                                    <span class="input-group-text bg-white border-end-0" style="border: 1.5px solid #e2e8f0; border-right: none;">
                                        <i class="fas fa-search" style="color: #667eea;"></i>
                                    </span>
                                    <input id="byTitle" type="search" class="form-control border-start-0 ps-0" 
                                        name="byTitle" placeholder="Tìm kiếm theo tên, địa chỉ, mô tả..." 
                                        style="border: 1.5px solid #e2e8f0; border-left: none;">
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-12">
                                <div class="filter-container">
                                    <div class="d-flex gap-1 flex-wrap align-items-center justify-content-lg-end">
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
                                    <h6 class="text-dark font-weight-bold mb-4" style="font-size: 16px; color: #1e293b;">
                                        <i class="fas fa-sliders-h me-2" style="color: #667eea;"></i>Bộ lọc chi tiết
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label text-sm font-weight-bold mb-2" style="color: #475569; font-size: 13px;">
                                                <i class="fas fa-tag me-1" style="color: #667eea;"></i>Danh mục
                                            </label>
                                            <select class="form-control form-control-sm" id="byCategory" name="byCategory">
                                                <option value="">Tất cả danh mục</option>
                                                @foreach($categories as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label text-sm font-weight-bold mb-2" style="color: #475569; font-size: 13px;">
                                                <i class="fas fa-money-bill me-1" style="color: #667eea;"></i>Giá từ
                                            </label>
                                            <input id="byFromPrice" type="text" class="form-control form-control-sm number-separator"
                                                name="byFromPrice" placeholder="0 VNĐ" />
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label text-sm font-weight-bold mb-2" style="color: #475569; font-size: 13px;">
                                                <i class="fas fa-money-bill-wave me-1" style="color: #667eea;"></i>Giá đến
                                            </label>
                                            <input id="byToPrice" type="text" class="form-control form-control-sm number-separator"
                                                name="byToPrice" placeholder="6,000,000 VNĐ" />
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label text-sm font-weight-bold mb-2" style="color: #475569; font-size: 13px;">
                                                <i class="fas fa-info-circle me-1" style="color: #667eea;"></i>Trạng thái
                                            </label>
                                            <select class="form-control form-control-sm" id="byStatus" name="byStatus">
                                                <option value="">Tất cả trạng thái</option>
                                                @foreach($status as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label text-sm font-weight-bold mb-2" style="color: #475569; font-size: 13px;">
                                                <i class="fas fa-couch me-1" style="color: #667eea;"></i>Nội thất
                                            </label>
                                            <select class="form-control form-control-sm" id="byFurnitureStatus" name="byFurnitureStatus">
                                                <option value="">Tất cả</option>
                                                @foreach($furnitureStatus as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label text-sm font-weight-bold mb-2" style="color: #475569; font-size: 13px;">
                                                <i class="fas fa-eye me-1" style="color: #667eea;"></i>Hiển thị
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
            <div class="col-xl-3 col-md-6 mb-4">
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

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title">
                                {{ Str::limit($boardingHouse->title, 50) }}
                            </h5>
                            <div class="publish-icon">
                                @if($boardingHouse->is_publish)
                                    <i class="fas fa-eye text-success" title="Đã publish"></i>
                                @else
                                    <i class="fas fa-eye-slash text-danger" title="Chưa publish"></i>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="info-item">
                                <i class="fas fa-tag"></i>
                                <span>{{ $categories[$boardingHouse->category] ?? $boardingHouse->category }}</span>
                            </div>

                            @if($boardingHouse->furniture_status)
                            <div class="info-item">
                                <i class="fas fa-couch"></i>
                                <span>{{ $furnitureStatus[$boardingHouse->furniture_status] ?? '' }}</span>
                            </div>
                            @endif

                            <div class="info-item">
                                <i class="far fa-clock"></i>
                                <span>Tạo: {{ date('d/m/Y H:i', strtotime($boardingHouse->created_at)) }}</span>
                            </div>
                        </div>

                        <!-- Creator Info (Admin Only) -->
                        @if(auth()->user()->is_admin && $boardingHouse->user_create)
                        <div class="creator-info mt-3 pt-3 border-top" style="border-color: #e2e8f0 !important;">
                            <button class="btn btn-sm w-100 text-start p-0 creator-toggle-btn" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#creator-{{ $boardingHouse->id }}" 
                                    aria-expanded="false"
                                    style="background: none; border: none !important; color: inherit; box-shadow: none !important;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $boardingHouse->user_create->avatar ?? '/img/user-placeholder.png' }}" 
                                             alt="creator" 
                                             class="creator-avatar-small">
                                        <div>
                                            <div style="font-size: 12px; font-weight: 600; color: #475569;">
                                                <i class="fas fa-user-circle me-1" style="color: #667eea;"></i>Người tạo
                                            </div>
                                            <div style="font-size: 13px; font-weight: 600; color: #1e293b;">
                                                {{ $boardingHouse->user_create->firstname }} {{ $boardingHouse->user_create->lastname }}
                                            </div>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-down creator-toggle-icon" style="color: #667eea; transition: transform 0.3s ease;"></i>
                                </div>
                            </button>
                            <div class="collapse mt-2" id="creator-{{ $boardingHouse->id }}">
                                <div class="creator-details">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $boardingHouse->user_create->avatar ?? '/img/user-placeholder.png' }}" 
                                             alt="creator" 
                                             class="creator-avatar-large">
                                        <div class="flex-grow-1">
                                            <div style="font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                                                {{ $boardingHouse->user_create->firstname }} {{ $boardingHouse->user_create->lastname }}
                                            </div>
                                            <div class="creator-detail-item">
                                                <i class="fas fa-envelope"></i>
                                                <span>{{ $boardingHouse->user_create->email }}</span>
                                            </div>
                                            @if($boardingHouse->user_create->phone)
                                            <div class="creator-detail-item">
                                                <i class="fas fa-phone"></i>
                                                <span>{{ $boardingHouse->user_create->phone }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: #e2e8f0 !important;">
                            <div class="d-flex gap-2">
                                <a href="{{ getLinkPreview($boardingHouse->id, $boardingHouse->title) }}" 
                                    class="action-btn bg-gradient-success text-white" title="Xem chi tiết" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
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
                <div class="card" style="border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border-radius: 20px;">
                    <div class="card-body empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h5>Chưa có nhà trọ nào</h5>
                        <p>Hãy bắt đầu bằng cách thêm nhà trọ đầu tiên của bạn</p>
                        <a href="{{ route('boarding-house.create') }}" class="btn add-btn">
                            <i class="fas fa-plus me-2"></i>Thêm nhà trọ ngay
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($boardingHouses->count() > 0 && $boardingHouses->hasPages(2))
    <div class="row mt-5" id="pagination-container">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $boardingHouses->links('pagination::bootstrap-5') }}
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