@extends('master')
@section('title', 'Tin Đã Lưu - Nhà Trọ Tốt Sài Gòn')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/apps/rental-home/style.css') }}"/>

<style>
    .saved-listings-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
    }

    .saved-listing-card {
        transition: all 0.3s ease;
    }

    .saved-listing-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .remove-saved-btn {
        transition: all 0.2s ease;
    }

    .remove-saved-btn:hover {
        transform: scale(1.1);
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 5rem;
        color: #e0e0e0;
        margin-bottom: 1.5rem;
    }

    .item-img {
        aspect-ratio: 8/7;
        object-fit: cover;
        min-width: 140px;
        max-width: 200px;
        width: 100%;
        height: auto;
    }

    @media (max-width: 768px) {
        .item-img {
            min-width: 120px;
            max-width: 250px;
        }
    }
</style>
@endpush

@section('content')
{{-- Header Section --}}
<div class="saved-listings-header">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 mb-2">
                    <i class="fa-solid fa-heart me-2"></i>
                    Tin Đã Lưu
                </h1>
                <p class="mb-0 opacity-75">
                    Quản lý các tin đăng bạn đã lưu
                </p>
            </div>
            <div class="text-end">
                <div class="badge bg-white text-dark fs-6 px-3 py-2">
                    <strong>{{ $savedListings->total() }}</strong> tin đã lưu
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Breadcrumbs --}}
<nav aria-label="breadcrumb" class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('home.index') }}">Trang chủ</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Tin đã lưu</li>
    </ol>
</nav>

<div class="container my-4">
    @if($savedListings->count() > 0)
        <div class="row">
            <div class="col-12">
                {{-- Saved Listings Grid --}}
                <div class="d-flex flex-wrap justify-content-md-center" id="saved-listings-container">
                    @foreach($savedListings as $boardingHouse)
                    <article class="col-md-12 col-6 saved-listing-card" 
                             data-listing-id="{{ $boardingHouse->id }}">
                        <div class="card rounded mb-3 d-flex flex-md-nowrap flex-md-row overflow-hidden position-relative">
                            <a href="{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}" 
                               class="text-decoration-none text-dark d-flex flex-md-row flex-column w-100">
                                <picture>
                                    <source srcset="{{ resizeImageCloudinary($boardingHouse->thumbnail, 400, 350, 'webp') }}" type="image/webp">
                                    <img class="item-img" 
                                         src="{{ resizeImageCloudinary($boardingHouse->thumbnail, 400, 350) }}" 
                                         alt="{{ $boardingHouse->title }}" 
                                         loading="lazy" 
                                         decoding="async"
                                         width="400"
                                         height="350"/>
                                </picture>
                                
                                <div class="item-info flex-grow-1 p-2">
                                    <h3 class="__title text-lg fw-bold fs-5">{{ $boardingHouse->title }}</h3>
                                    
                                    <div class="text-success text-md fw-bold fs-4 mt-2">
                                        {{ getShortPrice($boardingHouse->price) }}/tháng
                                    </div>
                                    
                                    <address class="text-sm fs-6">
                                        <i class="fa-solid fa-location-dot text-danger"></i>
                                        {{ $boardingHouse->district }}
                                    </address>
                                    
                                    <div class="text-sm fs-6 mb-0">
                                        <i class="fa-solid fa-clock" style="color:#b0b0b0"></i>
                                        Đăng {{ dateForHumman($boardingHouse->created_at) }}
                                    </div>

                                    <div class="text-sm fs-6 mt-2 text-muted">
                                        <i class="fa-solid fa-bookmark" style="color:#667eea"></i>
                                        Đã lưu {{ dateForHumman($boardingHouse->saved_at) }}
                                    </div>
                                </div>
                            </a>
                            
                            {{-- Category Badge --}}
                            <div class="bg-success position-absolute top-0 left-0 fs-6 text-white px-1">
                                {{ \App\Constants\SystemDefination::BOARDING_HOUSE_CATEGORY[$boardingHouse->category] }}
                            </div>

                            {{-- Remove Button --}}
                            <button class="btn btn-link position-absolute top-0 end-0 p-2 remove-saved-btn" 
                                    onclick="event.preventDefault(); removeSavedListing({{ $boardingHouse->id }}, this.closest('.saved-listing-card'));"
                                    title="Bỏ lưu tin"
                                    aria-label="Bỏ lưu tin này">
                                <i class="fa-solid fa-heart fs-4 text-danger"></i>
                            </button>
                        </div>
                    </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($savedListings->hasPages())
                    <nav aria-label="Phân trang tin đã lưu" class="mt-4">
                        <div class="row mx-0">
                            {{ $savedListings->links('pagination::bootstrap-4') }}
                        </div>
                    </nav>
                @endif
            </div>
        </div>
    @else
        {{-- Empty State --}}
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fa-regular fa-heart"></i>
            </div>
            <h2 class="h4 mb-3">Chưa có tin đã lưu</h2>
            <p class="text-muted mb-4">
                Bạn chưa lưu tin nào. Hãy khám phá và lưu các tin đăng yêu thích để xem lại sau.
            </p>
            <a href="{{ route('rentalHome.index') }}" class="btn btn-success btn-lg">
                <i class="fa-solid fa-search me-2"></i>
                Khám phá phòng trọ
            </a>
        </div>
    @endif
</div>
@endsection

@push('seo')
<meta name="title" content="Tin Đã Lưu - Nhà Trọ Tốt Sài Gòn"/>
<meta name="description" content="Quản lý các tin đăng phòng trọ, nhà nguyên căn đã lưu của bạn tại Nhà Trọ Tốt Sài Gòn.">
<meta name="robots" content="noindex, follow">

<meta property="og:type" content="website">
<meta property="og:title" content="Tin Đã Lưu - Nhà Trọ Tốt Sài Gòn">
<meta property="og:description" content="Quản lý các tin đăng phòng trọ đã lưu của bạn">
<meta property="og:url" content="{{ route('savedListings.index') }}">

<link rel="canonical" href="{{ route('savedListings.index') }}">
@endpush

@push('js')
<script src="{{ asset('assets/js/apps/rental/SavedListing.js') }}" defer></script>

<script>
    window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
</script>
@endpush

