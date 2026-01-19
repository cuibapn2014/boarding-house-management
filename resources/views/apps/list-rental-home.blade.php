@extends('master')
@section('title', 
    (request('category') ? 'Cho thuê ' . implode(', ', (array)request('category')) . ' - ' : '') . 
    (request('district') ? 'Khu vực ' . implode(', ', (array)request('district')) . ' - ' : '') .
    'Danh Sách Cho Thuê - Nhà Trọ Tốt Sài Gòn' .
    ($boardingHouses->currentPage() > 1 ? ' - Trang ' . $boardingHouses->currentPage() : '')
)

@push('css')
{{-- Preload critical resources --}}
<link rel="preload" as="image" href="{{ asset('assets/images/hero-bg.webp') }}" fetchpriority="high" />
<link rel="preload" href="{{ asset('assets/images/hero-background.webp') }}" as="image" fetchpriority="high"/>
<link rel="preload" href="{{ asset('assets/css/apps/rental-home/style.css') }}" as="style" />

{{-- Preload first few listing images --}}
@foreach($boardingHouses->take(6) as $index => $boardingHouse)
<link rel="preload" as="image" href="{{ resizeImageCloudinary($boardingHouse->thumbnail, 400, 350, 'webp') }}" fetchpriority="{{ $index < 2 ? 'high' : 'low' }}" />
@endforeach

<link rel="stylesheet" href="{{ asset('assets/css/apps/rental-home/style.css') }}"/>

{{-- Critical CSS inline --}}
<style>
    /* ========== Layout & Container ========== */
    .list-home {
        min-height: 60vh;
    }
    
    .list-home .container {
        max-width: 100%;
    }
    
    /* ========== Card Improvements ========== */
    .item-img {
        aspect-ratio: 8/7;
        object-fit: cover;
        min-width: 160px;
        max-width: 220px;
        width: 100%;
        height: auto;
        transition: transform 0.3s ease;
    }
    
    .list-home .card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        will-change: transform;
        background: #fff;
        position: relative;
    }
    
    .list-home .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        border-color: #28a745;
    }
    
    .list-home .card:hover .item-img {
        transform: scale(1.05);
    }
    
    .list-home .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #28a745, #20c997);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .list-home .card:hover::before {
        opacity: 1;
    }
    
    /* Card Image Container */
    .list-home .card .position-relative {
        overflow: hidden;
        background: #f8f9fa;
    }
    
    /* Card Info Section */
    .item-info {
        padding: 1rem 1.25rem !important;
        min-height: 180px;
    }
    
    .__title {
        font-size: 1.1rem;
        font-weight: 600;
        line-height: 1.4;
        color: #212529;
        margin-bottom: 0.75rem;
        transition: color 0.2s ease;
    }
    
    .list-home .card:hover .__title {
        color: #28a745;
    }
    
    /* Price Styling */
    .text-success.fs-4 {
        font-size: 1.5rem !important;
        font-weight: 700;
        letter-spacing: -0.5px;
    }
    
    /* Address Styling */
    address {
        font-style: normal;
        color: #6c757d;
        font-size: 0.95rem;
    }
    
    /* Category Badge */
    .list-home .card .bg-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.4rem 0.75rem;
        border-radius: 0 0 8px 0;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        z-index: 3;
    }
    
    /* Partner Badge - Compact Design */
    .partner-badge-compact {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 50%, #FF8C00 100%);
        color: #fff !important;
        font-weight: 700 !important;
        font-size: 0.65rem !important;
        padding: 0.25rem 0.5rem !important;
        border-radius: 12px !important;
        box-shadow: 0 2px 8px rgba(255, 215, 0, 0.4), 
                    0 1px 4px rgba(255, 140, 0, 0.3),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        white-space: nowrap;
        line-height: 1;
        vertical-align: middle;
    }
    
    .partner-badge-compact i {
        font-size: 0.7rem;
        filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2));
        animation: partnerBadgeShine 2s ease-in-out infinite;
    }
    
    @keyframes partnerBadgeShine {
        0%, 100% {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }
        50% {
            opacity: 1;
            transform: rotate(8deg) scale(1.1);
            filter: drop-shadow(0 1px 3px rgba(255, 215, 0, 0.6));
        }
    }
    
    .list-home .card:hover .partner-badge-compact {
        transform: scale(1.05);
        box-shadow: 0 3px 10px rgba(255, 215, 0, 0.5), 
                    0 2px 6px rgba(255, 140, 0, 0.4),
                    inset 0 1px 0 rgba(255, 255, 255, 0.4);
    }
    
    /* Save Button Enhancement */
    .save-listing-btn {
        transition: all 0.3s ease;
        backdrop-filter: blur(8px);
        background: rgba(255, 255, 255, 0.9) !important;
        border: 1px solid rgba(0,0,0,0.1);
    }
    
    .save-listing-btn:hover {
        background: rgba(255, 255, 255, 1) !important;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .save-listing-btn[data-saved="true"] i {
        color: #dc3545 !important;
    }
    
    /* ========== Filter Sidebar ========== */
    .filter__rental-home {
        position: sticky;
        top: 100px;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
        min-width: 300px;
        max-width: 340px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        padding: 1.5rem !important;
    }
    
    .filter__rental-home h2 {
        color: #212529;
        font-weight: 700;
        font-size: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 1.5rem !important;
    }
    
    .filter__rental-home h2 i {
        color: #28a745;
        margin-right: 0.5rem;
    }
    
    .filter__rental-home .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
    }
    
    /* Filter Form */
    .form-search {
        display: flex;
        flex-direction: column;
        gap: 0;
    }
    
    .form-search .mb-4 {
        margin-bottom: 2rem !important;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .form-search .mb-4:last-of-type {
        border-bottom: none;
        margin-bottom: 1.5rem !important;
    }
    
    .form-search .form-label {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #212529;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-search .form-label::before {
        content: '';
        width: 3px;
        height: 18px;
        background: linear-gradient(135deg, #28a745, #20c997);
        border-radius: 2px;
    }
    
    .form-search .form-check {
        margin-bottom: 0.75rem;
        padding-left: 2rem;
        transition: all 0.2s ease;
    }
    
    .form-search .form-check:hover {
        background: rgba(40, 167, 69, 0.05);
        border-radius: 6px;
        padding-left: 2rem;
        margin-left: -0.5rem;
        margin-right: -0.5rem;
        padding-left: calc(2rem - 0.5rem);
        padding-right: 0.5rem;
    }
    
    .form-search .form-check-input {
        width: 1.1rem;
        height: 1.1rem;
        margin-top: 0.2rem;
        cursor: pointer;
        border: 2px solid #ced4da;
        transition: all 0.2s ease;
    }
    
    .form-search .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.15);
    }
    
    .form-search .form-check-input:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.15);
    }
    
    .form-search .form-check-label {
        font-size: 0.95rem;
        line-height: 1.5;
        word-wrap: break-word;
        overflow-wrap: break-word;
        cursor: pointer;
        color: #495057;
        transition: color 0.2s ease;
    }
    
    .form-search .form-check:hover .form-check-label {
        color: #28a745;
    }
    
    /* District Pills */
    .tag-district + span {
        transition: all 0.2s ease;
        cursor: pointer;
        padding: 0.5rem 1rem !important;
        font-size: 0.875rem;
        border: 1.5px solid #dee2e6 !important;
        background: #fff;
    }
    
    .tag-district:checked + span {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #fff !important;
        border-color: #28a745 !important;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        transform: translateY(-1px);
    }
    
    /* Reset Button */
    .reset-filter {
        background: linear-gradient(135deg, #ffc107, #ff9800);
        border: none;
        color: #fff;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    }
    
    .reset-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
        color: #fff;
    }
    
    /* ========== Results Header ========== */
    .list-home .container > div:first-child {
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        border: 1px solid #e9ecef;
    }
    
    .list-home h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 0.5rem;
    }
    
    /* ========== Active Filters ========== */
    .list-home .badge {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .list-home .badge.bg-light {
        background: #f8f9fa !important;
        border: 1.5px solid #dee2e6;
        color: #495057;
    }
    
    .list-home .badge.bg-light:hover {
        background: #e9ecef !important;
        transform: translateY(-1px);
    }
    
    .list-home .badge.bg-danger {
        background: linear-gradient(135deg, #dc3545, #c82333) !important;
        border: none;
    }
    
    .list-home .badge a {
        text-decoration: none;
        margin-left: 0.5rem;
        font-weight: 700;
        font-size: 1.1rem;
        line-height: 1;
    }
    
    /* ========== Empty State ========== */
    .list-home .text-center {
        padding: 4rem 2rem;
    }
    
    .list-home .text-center i {
        color: #dee2e6;
        margin-bottom: 1.5rem;
    }
    
    .list-home .text-center h3 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .list-home .text-center .btn {
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: 8px;
    }
    
    /* ========== Pagination ========== */
    .pagination {
        justify-content: center;
        margin-top: 2rem;
    }
    
    .pagination .page-link {
        color: #28a745;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .pagination .page-link:hover {
        background: #28a745;
        color: #fff;
        border-color: #28a745;
        transform: translateY(-1px);
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #28a745, #20c997);
        border-color: #28a745;
        color: #fff;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }
    
    /* ========== Mobile Filter Button ========== */
    #btn-open-filter-sidebar {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        transition: all 0.3s ease;
    }
    
    #btn-open-filter-sidebar:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(40, 167, 69, 0.4);
    }
    
    /* ========== Skeleton Loading ========== */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 8px;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    /* ========== Loading States ========== */
    .loading-state {
        opacity: 0.7;
        pointer-events: none;
    }
    
    /* ========== Responsive Design - Tablet ========== */
    @media (max-width: 991px) {
        .filter__rental-home {
            min-width: 260px;
            max-width: 280px;
        }
    }
    
    /* ========== Responsive Design - Mobile ========== */
    @media (max-width: 768px) {
        /* Container & Layout */
        .list-home .container {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        
        /* Header Section */
        .list-home .container > div:first-child {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 10px;
        }
        
        .list-home h1 {
            font-size: 1.15rem;
            line-height: 1.3;
        }
        
        .list-home h1 + p {
            font-size: 0.875rem;
        }
        
        /* Card Grid - 2 columns on mobile */
        .list-home .col-6 {
            padding: 0 0.375rem !important;
        }
        
        .list-home #room-list {
            gap: 0;
        }
        
        /* Card Styling for Mobile - Vertical Layout */
        .list-home .card {
            border-radius: 10px;
            margin-bottom: 0.75rem !important;
            flex-direction: column !important;
            display: flex !important;
        }
        
        .list-home .card:hover {
            transform: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .list-home .card::before {
            display: none;
        }
        
        /* Image Container for Mobile */
        .list-home .card > .position-relative:first-child {
            width: 100% !important;
            flex-shrink: 0 !important;
        }
        
        /* Image for Mobile */
        .item-img {
            min-width: 100% !important;
            max-width: 100% !important;
            width: 100% !important;
            height: 130px !important;
            aspect-ratio: auto !important;
            object-fit: cover;
        }
        
        /* Card Info for Mobile */
        .item-info {
            padding: 0.625rem 0.75rem !important;
            min-height: auto !important;
            width: 100%;
        }
        
        .__title {
            font-size: 0.85rem;
            margin-bottom: 0.375rem;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            line-height: 1.3;
        }
        
        .text-success.fs-4 {
            font-size: 1rem !important;
            margin-bottom: 0.25rem !important;
        }
        
        .text-success.fs-4 + span,
        .text-success.fs-4 .text-muted {
            font-size: 0.75rem !important;
        }
        
        address {
            font-size: 0.75rem;
            margin-bottom: 0.25rem !important;
        }
        
        address i {
            font-size: 0.7rem;
        }
        
        /* Meta info (area, time) - Hide on mobile to save space */
        .item-info > div:last-child {
            display: none !important;
        }
        
        /* Category Badge */
        .list-home .card .bg-success {
            font-size: 0.6rem;
            padding: 0.2rem 0.4rem;
            border-radius: 0 0 6px 0;
        }
        
        .list-home .card .bg-success i {
            display: none;
        }
        
        /* Save Button */
        .save-listing-btn {
            width: 30px !important;
            height: 30px !important;
            margin: 0.25rem !important;
            top: 0 !important;
            right: 0 !important;
        }
        
        .save-listing-btn i {
            font-size: 0.85rem !important;
        }
        
        /* Image Overlay - Hide on mobile */
        .list-home .card .position-relative > div[style*="gradient"] {
            display: none;
        }
        
        /* Filter Sidebar */
        .filter__rental-home {
            min-width: auto;
            max-width: 100%;
            padding: 1.25rem !important;
        }
        
        .offcanvas-body {
            padding: 1rem;
        }
        
        .form-search .form-check {
            margin-bottom: 0.6rem;
            padding-left: 1.75rem;
        }
        
        .form-search .form-check-label {
            font-size: 0.9rem;
        }
        
        .form-search .form-label {
            font-size: 0.95rem;
            margin-bottom: 0.75rem;
        }
        
        .form-search .mb-4 {
            margin-bottom: 1.5rem !important;
            padding-bottom: 1rem;
        }
        
        /* District Pills */
        .tag-district + span {
            padding: 0.375rem 0.75rem !important;
            font-size: 0.8rem;
        }
        
        /* Active Filters */
        .list-home .badge {
            padding: 0.375rem 0.5rem;
            font-size: 0.75rem;
        }
        
        /* Pagination */
        .pagination {
            margin-top: 1.5rem;
        }
        
        .pagination .page-link {
            padding: 0.375rem 0.625rem;
            font-size: 0.875rem;
        }
        
        /* Empty State */
        .list-home .text-center {
            padding: 2.5rem 1.5rem;
        }
        
        .list-home .text-center .d-inline-block {
            padding: 1.5rem !important;
        }
        
        .list-home .text-center i.fa-4x {
            font-size: 2.5rem !important;
        }
        
        .list-home .text-center h3 {
            font-size: 1.1rem;
        }
        
        .list-home .text-center p {
            font-size: 0.9rem;
        }
        
        .list-home .text-center .btn {
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
        }
    }
    
    /* ========== Responsive Design - Small Mobile ========== */
    @media (max-width: 576px) {
        .list-home #room-list {
            margin: 0 -0.25rem;
        }
        
        .list-home .col-6 {
            padding: 0 0.25rem !important;
        }
        
        /* Even smaller cards */
        .item-img {
            height: 110px !important;
        }
        
        .item-info {
            padding: 0.5rem 0.625rem !important;
        }
        
        .__title {
            font-size: 0.8rem;
            line-height: 1.25;
            margin-bottom: 0.25rem;
        }
        
        .text-success.fs-4 {
            font-size: 0.9rem !important;
        }
        
        address {
            font-size: 0.7rem;
        }
        
        /* Filter Form */
        .form-search .form-check {
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
        }
        
        .form-search .form-check-label {
            font-size: 0.875rem;
        }
        
        .form-search .form-label {
            font-size: 0.9rem;
        }
        
        .form-search .mb-4 {
            margin-bottom: 1.25rem !important;
        }
        
        .offcanvas-body {
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .list-home h1 {
            font-size: 1.1rem;
        }
        
        /* Breadcrumb */
        .breadcrumb {
            font-size: 0.8rem;
        }
        
        /* Filter Button */
        #btn-open-filter-sidebar {
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
        }
        
        /* Active Filters */
        .list-home .badge {
            padding: 0.3rem 0.4rem;
            font-size: 0.7rem;
        }
        
        /* Pagination */
        .pagination .page-link {
            padding: 0.3rem 0.5rem;
            font-size: 0.8rem;
            margin: 0 0.125rem;
        }
    }
    
    /* ========== Extra Small Mobile (iPhone SE, etc) ========== */
    @media (max-width: 375px) {
        .list-home h1 {
            font-size: 1rem;
        }
        
        .item-img {
            height: 95px !important;
        }
        
        .__title {
            font-size: 0.75rem;
        }
        
        .text-success.fs-4 {
            font-size: 0.85rem !important;
        }
        
        address {
            font-size: 0.65rem;
        }
        
        .save-listing-btn {
            width: 26px !important;
            height: 26px !important;
        }
        
        .save-listing-btn i {
            font-size: 0.75rem !important;
        }
        
        .list-home .card .bg-success {
            font-size: 0.55rem;
            padding: 0.15rem 0.35rem;
        }
    }
    
    /* ========== Scrollbar Styling ========== */
    .filter__rental-home::-webkit-scrollbar {
        width: 6px;
    }
    
    .filter__rental-home::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .filter__rental-home::-webkit-scrollbar-thumb {
        background: #28a745;
        border-radius: 10px;
    }
    
    .filter__rental-home::-webkit-scrollbar-thumb:hover {
        background: #20c997;
    }
    
    /* ========== Mobile Offcanvas ========== */
    @media (max-width: 768px) {
        #filter-sidebar {
            width: 85vw !important;
            max-width: 320px;
        }
        
        #filter-sidebar .offcanvas-body {
            padding: 1rem;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        #filter-sidebar .offcanvas-body::-webkit-scrollbar {
            width: 4px;
        }
        
        #filter-sidebar .offcanvas-body::-webkit-scrollbar-thumb {
            background: #28a745;
            border-radius: 4px;
        }
        
        /* Hide horizontal scrollbar for active filters */
        .overflow-auto::-webkit-scrollbar {
            display: none;
        }
    }
    
    /* ========== Touch Friendly ========== */
    @media (hover: none) and (pointer: coarse) {
        /* Larger touch targets */
        .form-search .form-check {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        
        .form-search .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
        }
        
        .tag-district + span {
            padding: 0.5rem 0.875rem !important;
        }
        
        /* Disable hover effects on touch */
        .list-home .card:hover {
            transform: none;
        }
        
        .list-home .card:active {
            transform: scale(0.98);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .save-listing-btn:hover {
            transform: none;
        }
        
        .save-listing-btn:active {
            transform: scale(0.95);
        }
    }
    
    /* ========== Safe Area for Notched Devices ========== */
    @supports (padding: max(0px)) {
        .offcanvas-body {
            padding-bottom: max(100px, env(safe-area-inset-bottom) + 100px) !important;
        }
        
        #filter-sidebar .position-fixed {
            padding-bottom: max(1rem, env(safe-area-inset-bottom));
        }
    }
    
    /* ========== Smooth Animations ========== */
    @media (prefers-reduced-motion: no-preference) {
        .list-home .card {
            animation: fadeInUp 0.4s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    }
    
    /* ========== Dark Mode Support (Optional) ========== */
    @media (prefers-color-scheme: dark) {
        /* Can be enabled later if needed */
    }
</style>
@endpush

@section('content')
@include('components.hero')

@php
$categories = \App\Constants\SystemDefination::BOARDING_HOUSE_CATEGORY;
$currentFilters = array_filter([
    'category' => request('category'),
    'district' => request('district'),
    'price_min' => request('price_min'),
    'price_max' => request('price_max'),
    'furniture_status' => request('furniture_status'),
]);
@endphp

{{-- Breadcrumbs --}}
<nav aria-label="breadcrumb" class="container mt-3">
    <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="/" itemprop="item"><span itemprop="name">Trang chủ</span></a>
            <meta itemprop="position" content="1" />
        </li>
        <li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span itemprop="name">
                @if(request('category'))
                    Cho thuê {{ implode(', ', (array)request('category')) }}
                @else
                    Danh sách cho thuê
                @endif
            </span>
            <meta itemprop="position" content="2" />
        </li>
    </ol>
</nav>

<div class="container d-flex justify-content-center my-4 flex-md-row flex-column px-md-1 px-2">
    {{-- Mobile Filter Bar --}}
    <div class="d-md-none w-100 mb-3 px-2">
        <div class="d-flex justify-content-between align-items-center gap-2">
            <button id="btn-open-filter-sidebar" 
                    class="btn btn-success flex-grow-1 shadow-sm d-flex align-items-center justify-content-center gap-2" 
                    data-bs-toggle="offcanvas" 
                    href="#filter-sidebar" 
                    role="button"
                    aria-controls="filter-sidebar"
                    aria-label="Mở bộ lọc tìm kiếm"
                    style="border-radius: 10px; font-weight: 600; padding: 0.75rem 1rem;">
                <i class="fa-solid fa-sliders"></i>
                <span>Bộ lọc</span>
                @if(count($currentFilters) > 0)
                <span class="badge bg-light text-success" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">{{ count($currentFilters) }}</span>
                @endif
            </button>
            
            {{-- Quick Stats for Mobile --}}
            <div class="bg-light rounded-3 px-3 py-2 text-center" style="min-width: 100px;">
                <div class="fw-bold text-success" style="font-size: 1.1rem; line-height: 1;">{{ number_format($boardingHouses->total(), 0, ',', '.') }}</div>
                <div class="text-muted" style="font-size: 0.7rem;">kết quả</div>
            </div>
        </div>
    </div>

    <aside class="filter__rental-home rounded shadow-sm border bg-white p-2 d-md-block d-none" 
           aria-label="Bộ lọc tìm kiếm phòng trọ">
        <h2 class="mb-3 h5">
            <i class="fa-solid fa-filter"></i>
            <span>Bộ Lọc</span>
            @if(count($currentFilters) > 0)
            <span class="badge bg-success ms-2">{{ count($currentFilters) }}</span>
            @endif
        </h2>
        @include('components.filter')
    </aside>

    <main class="list-home flex-grow-1">
        <div class="container px-md-2 px-0">
            {{-- Results summary - Desktop --}}
            <div class="d-none d-md-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div class="flex-grow-1">
                    <h1 class="h4 mb-2">
                        @if(request('category'))
                            Cho thuê {{ implode(', ', (array)request('category')) }}
                        @else
                            Danh sách phòng trọ cho thuê
                        @endif
                        @if(request('district'))
                            tại {{ implode(', ', (array)request('district')) }}
                        @endif
                    </h1>
                    <p class="text-muted mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-list-check text-success"></i>
                        <span>Tìm thấy <strong class="text-dark">{{ number_format($boardingHouses->total(), 0, ',', '.') }}</strong> kết quả</span>
                        @if($boardingHouses->currentPage() > 1)
                        <span class="text-muted">•</span>
                        <span>Trang {{ $boardingHouses->currentPage() }}/{{ $boardingHouses->lastPage() }}</span>
                        @endif
                    </p>
                </div>
            </div>
            
            {{-- Results summary - Mobile --}}
            <div class="d-md-none mb-3 px-1">
                <h1 class="h5 mb-1 fw-bold">
                    @if(request('category'))
                        Cho thuê {{ implode(', ', (array)request('category')) }}
                    @else
                        Danh sách phòng trọ
                    @endif
                    @if(request('district'))
                        <span class="d-block text-muted fw-normal" style="font-size: 0.85rem;">tại {{ implode(', ', (array)request('district')) }}</span>
                    @endif
                </h1>
                @if($boardingHouses->currentPage() > 1)
                <p class="text-muted mb-0" style="font-size: 0.8rem;">
                    Trang {{ $boardingHouses->currentPage() }}/{{ $boardingHouses->lastPage() }}
                </p>
                @endif
            </div>

            {{-- Active filters --}}
            @if(count($currentFilters) > 0)
            <div class="mb-3 mb-md-4 px-1 px-md-0">
                {{-- Desktop View --}}
                <div class="d-none d-md-flex flex-wrap align-items-center gap-2">
                    <span class="text-muted small d-flex align-items-center">
                        <i class="fa-solid fa-filter-circle-check text-success me-1"></i>
                        Bộ lọc đang áp dụng:
                    </span>
                    @foreach($currentFilters as $key => $value)
                    @if($value)
                    <span class="badge bg-light text-dark border d-flex align-items-center gap-1">
                        @php
                            $filterLabels = [
                                'category' => 'Loại',
                                'district' => 'Khu vực',
                                'price' => 'Giá',
                                'furniture_status' => 'Nội thất'
                            ];
                            $label = $filterLabels[$key] ?? ucfirst($key);
                        @endphp
                        <i class="fa-solid fa-tag text-success" style="font-size: 0.7rem;"></i>
                        <span>{{ $label }}: 
                        @if(is_array($value))
                            {{ implode(', ', $value) }}
                        @else
                            {{ $value }}
                        @endif
                        </span>
                        <a href="#" class="text-danger text-decoration-none fw-bold" data-filter="{{ $key }}" aria-label="Xóa bộ lọc {{ $key }}" style="font-size: 1.1rem; line-height: 1;">×</a>
                    </span>
                    @endif
                    @endforeach
                    <a href="{{ route('rentalHome.index') }}" class="badge bg-danger text-white d-flex align-items-center gap-1">
                        <i class="fa-solid fa-xmark"></i>
                        <span>Xóa tất cả</span>
                    </a>
                </div>
                
                {{-- Mobile View - Horizontal Scroll --}}
                <div class="d-md-none">
                    <div class="d-flex align-items-center gap-2 overflow-auto pb-2" style="scrollbar-width: none; -ms-overflow-style: none;">
                        @foreach($currentFilters as $key => $value)
                        @if($value)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success d-flex align-items-center gap-1 flex-shrink-0" style="font-size: 0.75rem; padding: 0.4rem 0.6rem;">
                            @php
                                $filterLabels = [
                                    'category' => 'Loại',
                                    'district' => 'Khu vực',
                                    'price' => 'Giá',
                                    'furniture_status' => 'Nội thất'
                                ];
                                $label = $filterLabels[$key] ?? ucfirst($key);
                            @endphp
                            <span>{{ $label }}: 
                            @if(is_array($value))
                                {{ Str::limit(implode(', ', $value), 15) }}
                            @else
                                {{ Str::limit($value, 15) }}
                            @endif
                            </span>
                            <a href="#" class="text-success text-decoration-none fw-bold ms-1" data-filter="{{ $key }}" aria-label="Xóa bộ lọc {{ $key }}" style="font-size: 1rem; line-height: 1;">×</a>
                        </span>
                        @endif
                        @endforeach
                        <a href="{{ route('rentalHome.index') }}" class="badge bg-danger text-white d-flex align-items-center gap-1 flex-shrink-0" style="font-size: 0.75rem; padding: 0.4rem 0.6rem;">
                            <i class="fa-solid fa-xmark"></i>
                            <span>Xóa</span>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <div class="d-flex flex-wrap justify-content-md-center" id="room-list" role="list">
                @forelse($boardingHouses as $index => $boardingHouse)
                <article class="col-md-12 col-6" 
                         role="listitem" 
                         itemscope 
                         itemtype="https://schema.org/RealEstate">
                    <a href="{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}" 
                       class="text-decoration-none"
                       aria-label="Xem chi tiết {{ $boardingHouse->title }}">
                        <div class="card rounded mb-3 d-flex flex-md-nowrap flex-md-row overflow-hidden position-relative text-dark shadow-sm">
                            <div class="position-relative" style="flex-shrink: 0;">
                                <picture>
                                    <source srcset="{{ resizeImageCloudinary($boardingHouse->thumbnail, 400, 350, 'webp') }}" type="image/webp">
                                    <img class="item-img skeleton" 
                                         src="{{ resizeImageCloudinary($boardingHouse->thumbnail, 400, 350) }}" 
                                         alt="{{ $boardingHouse->title }}" 
                                         loading="{{ $index < 6 ? 'eager' : 'lazy' }}" 
                                         decoding="async"
                                         width="400"
                                         height="350"
                                         itemprop="image"/>
                                </picture>
                                
                                {{-- Image Overlay Gradient --}}
                                <div class="position-absolute bottom-0 start-0 end-0" 
                                     style="height: 40%; background: linear-gradient(to top, rgba(0,0,0,0.3), transparent); pointer-events: none;"></div>
                            </div>
                            
                            <div class="item-info flex-grow-1 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h3 class="__title fw-bold mb-0 flex-grow-1" 
                                        itemprop="name"
                                        style="display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">
                                        {{ $boardingHouse->title }}
                                    </h3>
                                </div>
                                
                                <div class="text-success fw-bold fs-4 mb-2 d-flex align-items-baseline gap-2 flex-wrap" 
                                     itemprop="offers" 
                                     itemscope 
                                     itemtype="https://schema.org/Offer">
                                    <span itemprop="price" content="{{ $boardingHouse->price }}">
                                        {{ getShortPrice($boardingHouse->price) }}
                                    </span>
                                    <span class="text-muted" style="font-size: 0.9rem; font-weight: 500;">/tháng</span>
                                    {{-- Partner Badge (Admin created) - Compact Design --}}
                                    @if($boardingHouse->created_by === 1)
                                    <span class="partner-badge-compact">
                                        <i class="fa-solid fa-star"></i>
                                        <span>Đối tác</span>
                                    </span>
                                    @endif
                                    <meta itemprop="priceCurrency" content="VND">
                                    <meta itemprop="availability" content="https://schema.org/InStock">
                                </div>
                                
                                <address class="text-sm mb-2 d-flex align-items-center" 
                                         itemprop="address" 
                                         itemscope 
                                         itemtype="https://schema.org/PostalAddress">
                                    <i class="fa-solid fa-location-dot me-1 text-danger" style="font-size: 0.9rem;"></i>
                                    <span class="text-truncate fw-medium" itemprop="addressLocality">{{ $boardingHouse->district }}</span>
                                </address>
                                
                                <div class="text-sm mb-0 d-flex align-items-center flex-wrap gap-3 mt-auto pt-2" style="border-top: 1px solid #f0f0f0;">
                                    @if(isset($boardingHouse->area) && $boardingHouse->area)
                                    <span class="d-flex align-items-center text-muted">
                                        <i class="fa-solid fa-expand-arrows-alt me-2 text-primary" style="font-size: 0.85rem;"></i>
                                        <span class="fw-medium">{{ $boardingHouse->area }}m²</span>
                                    </span>
                                    @endif
                                    <span class="d-flex align-items-center text-muted">
                                        <i class="fa-solid fa-clock me-2" style="color:#6c757d; font-size: 0.85rem;"></i>
                                        <time datetime="{{ $boardingHouse->created_at }}" itemprop="datePublished" class="fw-medium">
                                            {{ dateForHumman($boardingHouse->created_at) }}
                                        </time>
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Category Badge --}}
                            <div class="bg-success position-absolute top-0 start-0 fs-6 text-white px-2 py-1 shadow-sm" 
                                 itemprop="category"
                                 style="z-index: 3; border-radius: 0 0 8px 0;">
                                <i class="fa-solid fa-tag me-1" style="font-size: 0.7rem;"></i>
                                {{ $categories[$boardingHouse->category] }}
                            </div>

                            {{-- Save Button --}}
                            <button class="btn btn-link position-absolute top-0 end-0 m-2 p-1 text-white bg-dark bg-opacity-25 rounded-circle" 
                                    data-boarding-house-id="{{ $boardingHouse->id }}"
                                    data-saved="false"
                                    onclick="event.preventDefault(); event.stopPropagation(); toggleSaveListing(this);"
                                    title="Lưu tin"
                                    aria-label="Lưu tin này"
                                    style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                                <i class="fa-regular fa-heart fs-6"></i>
                            </button>
                        </div>
                    </a>
                </article>
                @empty
                <div class="text-center py-5 w-100">
                    <div class="mb-4">
                        <div class="d-inline-block p-4 rounded-circle bg-light mb-3">
                            <i class="fa-solid fa-home fa-4x text-muted"></i>
                        </div>
                    </div>
                    <h3 class="h4 fw-bold mb-2">Không tìm thấy phòng trọ phù hợp</h3>
                    <p class="text-muted mb-4" style="max-width: 500px; margin-left: auto; margin-right: auto;">
                        Hãy thử điều chỉnh bộ lọc hoặc tìm kiếm với từ khóa khác để tìm được phòng trọ ưng ý nhất
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <a href="{{ route('rentalHome.index') }}" class="btn btn-success px-4 py-2">
                            <i class="fa-solid fa-arrow-left me-2"></i>
                            Xem tất cả phòng trọ
                        </a>
                        <button class="btn btn-outline-success px-4 py-2" onclick="document.getElementById('btn-open-filter-sidebar').click();">
                            <i class="fa-solid fa-filter me-2"></i>
                            Điều chỉnh bộ lọc
                        </button>
                    </div>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($boardingHouses->hasPages())
                <nav aria-label="Phân trang danh sách phòng trọ" class="mt-5">
                    <div class="row mx-0" id="pagination">
                        {{ $boardingHouses->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </nav>
                
                {{-- Pagination info for SEO --}}
                @if($boardingHouses->currentPage() > 1)
                <div class="text-center mt-3 text-muted small d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-info-circle"></i>
                    <span>Trang {{ $boardingHouses->currentPage() }} của {{ $boardingHouses->lastPage() }} 
                    ({{ number_format($boardingHouses->total(), 0, ',', '.') }} kết quả)</span>
                </div>
                @endif
            @endif

            {{-- Load more for mobile --}}
            <!-- @if($boardingHouses->hasMorePages())
            <div class="text-center mt-4 d-md-none">
                <button class="btn btn-outline-success" id="load-more-btn" data-page="{{ $boardingHouses->currentPage() + 1 }}">
                    <i class="fa-solid fa-plus me-2"></i>
                    Tải thêm phòng trọ
                </button>
            </div>
            @endif -->
        </div>
    </main>

    {{-- Mobile Filter Sidebar --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="filter-sidebar" aria-labelledby="filterSidebarLabel" style="border-radius: 20px 0 0 20px; max-width: 85vw;">
        <div class="offcanvas-header border-bottom py-3" style="background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);">
            <h3 class="offcanvas-title h5 fw-bold d-flex align-items-center gap-2 mb-0" id="filterSidebarLabel">
                <div class="bg-success bg-opacity-10 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="fa-solid fa-sliders text-success"></i>
                </div>
                <span>Bộ Lọc</span>
                @if(count($currentFilters) > 0)
                <span class="badge bg-success" style="font-size: 0.7rem;">{{ count($currentFilters) }}</span>
                @endif
            </h3>
            <button type="button" 
                    class="btn-close" 
                    data-bs-dismiss="offcanvas" 
                    aria-label="Đóng bộ lọc"></button>
        </div>
        <div class="offcanvas-body" style="padding-bottom: 100px;">
            @include('components.filter')
        </div>
        
        {{-- Fixed Bottom Actions --}}
        <div class="position-fixed bottom-0 start-0 end-0 bg-white border-top p-3 d-flex gap-2" style="z-index: 1060; box-shadow: 0 -4px 12px rgba(0,0,0,0.1);">
            <button type="button" class="btn btn-outline-secondary flex-grow-1" data-bs-dismiss="offcanvas">
                <i class="fa-solid fa-xmark me-1"></i>
                Đóng
            </button>
            <a href="{{ route('rentalHome.index') }}" class="btn btn-warning flex-grow-1">
                <i class="fa-solid fa-rotate-left me-1"></i>
                Đặt lại
            </a>
        </div>
    </div>
</div>
@endsection

@push('seo')
<!-- Enhanced Meta Tags -->
<meta name="title" content="
    @if(request('category'))Cho thuê {{ implode(', ', (array)request('category')) }} - @endif
    @if(request('district')){{ implode(', ', (array)request('district')) }} - @endif
    Danh Sách Cho Thuê - Nhà Trọ Tốt Sài Gòn
"/>
<meta name="description" content="
    @if(request('category') || request('district'))
        Tìm {{ $boardingHouses->total() }} phòng trọ {{ request('category') ? implode(', ', (array)request('category')) : '' }} 
        {{ request('district') ? 'tại ' . implode(', ', (array)request('district')) : '' }} với giá tốt nhất. 
    @endif
    Khám phá danh sách phòng trọ, nhà nguyên căn cho thuê chất lượng tại TP.HCM. Tìm chỗ ở hoàn hảo cho bạn.
">
<meta name="keywords" content="
    thuê phòng trọ TP.HCM, danh sách phòng trọ, 
    @if(request('category')){{ implode(', ', array_map(fn($cat) => 'thuê ' . strtolower($cat), (array)request('category'))) }}, @endif
    @if(request('district')){{ implode(', ', array_map(fn($dist) => 'phòng trọ ' . $dist, (array)request('district'))) }}, @endif
    phòng trọ giá rẻ Sài Gòn, nhà cho thuê, nhatrototsaigon
">
<meta name="author" content="Nhatrototsaigon Team">
<meta name="robots" content="
    @if($boardingHouses->currentPage() > 1)noindex, follow@else index, follow, max-snippet:-1, max-image-preview:large @endif
">
<meta name="googlebot" content="index, follow">

<!-- Geo Tags -->
<meta name="geo.region" content="VN-SG">
<meta name="geo.placename" content="
    @if(request('district')){{ implode(', ', (array)request('district')) }}, @endif
    Hồ Chí Minh, Việt Nam
">
<meta name="ICBM" content="10.8231,106.6297">

<!-- Enhanced Open Graph -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:locale" content="vi_VN">
<meta property="og:title" content="
    @if(request('category'))Cho thuê {{ implode(', ', (array)request('category')) }} - @endif
    Danh Sách Cho Thuê - Nhà Trọ Tốt Sài Gòn
">
<meta property="og:description" content="
    Khám phá {{ $boardingHouses->total() }} phòng trọ chất lượng 
    @if(request('district'))tại {{ implode(', ', (array)request('district')) }}@endif. 
    Tìm chỗ ở hoàn hảo với giá tốt nhất tại TP.HCM.
">
<meta property="og:image" content="{{ 
    $boardingHouses->isNotEmpty() 
        ? resizeImageCloudinary($boardingHouses->first()->thumbnail, 1200, 630) 
        : asset('assets/images/hero-background.webp') 
}}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="{{ request()->fullUrl() }}">

<!-- Enhanced Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@nhatrototsaigon">
<meta name="twitter:title" content="Danh Sách Cho Thuê - Nhà Trọ Tốt Sài Gòn">
<meta name="twitter:description" content="
    Khám phá {{ $boardingHouses->total() }} phòng trọ chất lượng. 
    Tìm chỗ ở hoàn hảo với giá tốt nhất.
">
<meta name="twitter:image" content="{{
    $boardingHouses->isNotEmpty() 
        ? resizeImageCloudinary($boardingHouses->first()->thumbnail, 1200, 630) 
        : asset('assets/images/hero-background.webp') 
}}">

<!-- Pagination Meta Tags -->
@if($boardingHouses->hasPages())
    @if($boardingHouses->previousPageUrl())
    <link rel="prev" href="{{ $boardingHouses->previousPageUrl() }}">
    @endif
    @if($boardingHouses->nextPageUrl())
    <link rel="next" href="{{ $boardingHouses->nextPageUrl() }}">
    @endif
@endif

<link rel="canonical" href="{{ $boardingHouses->url($boardingHouses->currentPage()) }}">
@endpush

@push('js')
{{-- Defer non-critical scripts --}}
<script src="{{ asset('assets/js/helper/ApiHelper.js') }}" defer></script>
<script src="{{ asset('assets/js/apps/rental/script.js') }}" defer></script>
<script src="{{ asset('assets/js/apps/rental/Rental.js') }}" defer></script>
<script src="{{ asset('assets/js/apps/rental/SavedListing.js') }}" defer></script>

{{-- Set authentication status for JavaScript --}}
<script>
    window.isAuthenticated = @json(auth()->check());
</script>

{{-- Enhanced filter functionality --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter removal functionality
    document.querySelectorAll('[data-filter]').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            const filterKey = this.dataset.filter;
            const url = new URL(window.location);
            
            // Handle array parameters
            if (filterKey.includes('[]')) {
                const baseKey = filterKey.replace('[]', '');
                url.searchParams.delete(baseKey + '[]');
            } else {
                url.searchParams.delete(filterKey);
            }
            
            // Reload with updated filters
            window.location.href = url.toString();
        });
    });

    // Sort functionality
    const sortSelect = document.getElementById('sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            const url = new URL(window.location);
            
            if (sortValue && sortValue !== 'newest') {
                url.searchParams.set('sort', sortValue);
            } else {
                url.searchParams.delete('sort');
            }
            
            // Show loading state
            document.querySelector('.list-home').classList.add('loading-state');
            
            window.location.href = url.toString();
        });
    }

    // Load more functionality for mobile
    const loadMoreBtn = document.getElementById('load-more-btn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const nextPage = this.dataset.page;
            const url = new URL(window.location);
            url.searchParams.set('page', nextPage);
            
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Đang tải...';
            this.disabled = true;
            
            window.location.href = url.toString();
        });
    }
    
    // Smooth scroll to top when filter changes
    const filterForm = document.querySelector('.form-search');
    if (filterForm) {
        const checkboxes = filterForm.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Small delay to allow form submission
                setTimeout(function() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }, 100);
            });
        });
    }
    
    // Animate cards on load
    const cards = document.querySelectorAll('.list-home .card');
    cards.forEach(function(card, index) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(function() {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 50);
    });
});
</script>
@endpush

@push('jsonLD-lg')
<!-- Enhanced Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "
        @if(request('category'))Cho thuê {{ implode(', ', (array)request('category')) }}@else Danh sách phòng trọ cho thuê@endif
        @if(request('district')) tại {{ implode(', ', (array)request('district')) }}@endif
    ",
    "description": "Danh sách {{ $boardingHouses->total() }} phòng trọ và nhà cho thuê chất lượng tại Hồ Chí Minh",
    "numberOfItems": {{ $boardingHouses->count() }},
    "itemListOrder": "https://schema.org/ItemListOrderDescending",
    "url": "{{ request()->fullUrl() }}",
    "itemListElement": [
        @foreach($boardingHouses as $index => $boardingHouse)
        {
            "@type": "ListItem",
            "position": {{ ($boardingHouses->currentPage() - 1) * $boardingHouses->perPage() + $loop->iteration }},
            "item": {
                "@type": "Product",
                "name": "{{ $boardingHouse->title }}",
                "description": "{{ $boardingHouse->description }}",
                "url": "{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}",
                "image": "{{ resizeImageCloudinary($boardingHouse->thumbnail, 800, 600) }}",
                "brand": {
                    "@type": "Organization",
                    "name": "{{ config('app.name') }}"
                },
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "{{ $boardingHouse->address }}",
                    "addressLocality": "{{ $boardingHouse->ward }}",
                    "addressRegion": "{{ $boardingHouse->district }}",
                    "postalCode": "700000",
                    "addressCountry": "VN"
                },
                "geo": {
                    "@type": "GeoCoordinates",
                    "latitude": "10.8231",
                    "longitude": "106.6297"
                },
                "offers": {
                    "@type": "Offer",
                    "price": "{{ $boardingHouse->price }}",
                    "priceCurrency": "VND",
                    "availability": "https://schema.org/InStock",
                    "validFrom": "{{ $boardingHouse->created_at }}",
                    "priceValidUntil": "{{ now()->addMonths(3) }}"
                },
                "category": "{{ $boardingHouse->category }}",
                "datePublished": "{{ $boardingHouse->created_at }}",
                "dateModified": "{{ $boardingHouse->updated_at }}"
            }
        }@if (!$loop->last),@endif
        @endforeach
    ]
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Trang chủ",
            "item": "{{ route('home.index') }}"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "
                @if(request('category'))
                    Cho thuê {{ implode(', ', (array)request('category')) }}
                @else
                    Danh sách cho thuê
                @endif
            ",
            "item": "{{ request()->fullUrl() }}"
        }
    ]
}
</script>

@if($boardingHouses->hasPages())
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "Danh sách phòng trọ cho thuê - Trang {{ $boardingHouses->currentPage() }}",
    "description": "Trang {{ $boardingHouses->currentPage() }} của {{ $boardingHouses->lastPage() }} - {{ $boardingHouses->total() }} kết quả",
    "url": "{{ $boardingHouses->url($boardingHouses->currentPage()) }}",
    "isPartOf": {
        "@type": "WebSite",
        "url": "{{ route('home.index') }}"
    },
    "mainEntity": {
        "@type": "ItemList",
        "numberOfItems": {{ $boardingHouses->total() }}
    }
}
</script>
@endif
@endpush