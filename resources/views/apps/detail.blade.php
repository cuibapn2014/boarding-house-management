@extends('master')
@section('title', $boardingHouse->title . ' - Cho thuê phòng trọ tại ' . $boardingHouse->district)
@push('css')
{{-- Preload critical resources --}}
<link rel="preload" href="{{ asset('assets/css/splide.min.css') }}" as="style">
<link rel="preload" href="{{ asset('/assets/js/core/splide.min.js') }}" as="script">
@if($boardingHouse?->boarding_house_files?->first())
<link rel="preload" as="image" href="{{ resizeImageCloudinary($boardingHouse->boarding_house_files->first()->url, 800, 450) }}" fetchpriority="high" />
@endif

{{-- Preload other images with lower priority --}}
@foreach($boardingHouse->boarding_house_files->take(3) as $index => $file)
@if($index > 0)
<link rel="preload" as="image" href="{{ resizeImageCloudinary($file->url, 300, 200) }}" fetchpriority="low" />
@endif
@endforeach

<link rel="stylesheet" href="{{ asset('vendor/toastify/css/toastify.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('vendor/flatpickr/css/flatpickr.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/css/splide.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/css/apps/rental-home/detail_style.css') }}"/>

{{-- Critical CSS inline --}}
<style>
    #overlay-preview:not(:has(img)) {
        display: none;
    }

    .preview-item {
        height: 100%;
        object-fit: contain;
    }

    /* Optimize layout shift */
    .hero-container {
        aspect-ratio: 16/9;
        background-color: #f8f9fa;
    }
    
    .hero-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .splide__slide img {
        aspect-ratio: 3/2;
        object-fit: cover;
    }
    
    /* Skeleton optimization */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>
@endpush

@section('content')
@php
$statues = \App\Constants\SystemDefination::BOARDING_HOUSE_STATUS;
$furnitureStatuses = \App\Constants\SystemDefination::FURNITURE_STATUS;
$fullAddress = "{$boardingHouse->address}, {$boardingHouse->ward}, {$boardingHouse->district}";
@endphp

<div class="container" style="margin-top: 20px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="/" itemprop="item"><span itemprop="name">Trang chủ</span></a>
                <meta itemprop="position" content="1" />
            </li>
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ route('rentalHome.index') }}" itemprop="item"><span itemprop="name">Danh sách cho thuê</span></a>
                <meta itemprop="position" content="2" />
            </li>
            <li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name">{{ $boardingHouse->category }}</span>
                <meta itemprop="position" content="3" />
            </li>
        </ol>
    </nav>

    <!-- Room Details -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Hero Image Gallery -->
            <div class="hero-container mb-3">
                @if($boardingHouse?->boarding_house_files?->first())
                <picture>
                    <source 
                        srcset="{{ resizeImageCloudinary($boardingHouse->boarding_house_files->first()->url, 800, 450, 'webp') }}" 
                        type="image/webp"
                        media="(min-width: 768px)">
                    <source 
                        srcset="{{ resizeImageCloudinary($boardingHouse->boarding_house_files->first()->url, 400, 225, 'webp') }}" 
                        type="image/webp"
                        media="(max-width: 767px)">
                    <img src="{{ resizeImageCloudinary($boardingHouse->boarding_house_files->first()->url, 800, 450) }}" 
                         srcset="{{ resizeImageCloudinary($boardingHouse->boarding_house_files->first()->url, 400, 225) }} 400w,
                                 {{ resizeImageCloudinary($boardingHouse->boarding_house_files->first()->url, 800, 450) }} 800w"
                         sizes="(max-width: 767px) 400px, 800px"
                         class="hero-image" 
                         loading="eager" 
                         decoding="async" 
                         alt="Hình ảnh {{ $boardingHouse->title }}"
                         width="800" 
                         height="450" />
                </picture>
                @endif
            </div>
            
            <!-- Thumbnail Carousel -->
            <section id="thumbnail-carousel" class="splide mb-4" aria-label="Ảnh phòng trọ được chọn hiển thị">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach($boardingHouse->boarding_house_files as $index => $file)
                        <li class="splide__slide position-relative" aria-hidden="false">
                            <picture>
                                <source srcset="{{ resizeImageCloudinary($file->url, 300, 200, 'webp') }}" type="image/webp">
                                <img src="{{ resizeImageCloudinary($file->url, 300, 200) }}"
                                    alt="Thumbnail {{ $boardingHouse->title }} - Hình {{ $index + 1 }}" 
                                    data-media-type="{{ $file->type }}"
                                    data-src="{{ $file->url }}"
                                    class="skeleton" 
                                    loading="{{ $index < 6 ? 'eager' : 'lazy' }}" 
                                    decoding="async"
                                    width="300" 
                                    height="200">
                            </picture>
                            @if($file->type === 'video')
                            <div class="position-absolute top-0 left-0 h-100 w-100 bg-dark text-white d-flex justify-content-center align-items-center"
                                style="--bs-bg-opacity:0.6;">
                                <i class="fa-solid fa-video text-white fa-2x" aria-label="Video phòng trọ"></i>
                            </div>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
            </section>
            
            <!-- Main Content Card -->
            <div class="detail-content">
                <!-- Title & Meta -->
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h1 class="detail-title mb-0 flex-grow-1">{{ $boardingHouse->title }}</h1>
                        <button class="btn btn-outline-danger save-listing-btn ms-3" 
                                data-boarding-house-id="{{ $boardingHouse->id }}"
                                data-saved="false"
                                onclick="toggleSaveListing(this);"
                                title="Lưu tin"
                                aria-label="Lưu tin này">
                            <i class="fa-regular fa-heart me-1"></i>
                            <span class="d-none d-sm-inline">Lưu tin</span>
                        </button>
                    </div>
                    <div class="detail-meta">
                        <span>
                            <i class="fa-solid fa-user"></i>
                            Đăng bởi <strong>{{ $boardingHouse->user_create->firstname }}</strong>
                        </span>
                        <span>
                            <i class="fa-solid fa-clock"></i>
                            <time datetime="{{ $boardingHouse->updated_at }}">
                                Cập nhật {{ dateForHumman($boardingHouse->updated_at) }}
                            </time>
                        </span>
                        <span>
                            <i class="fa-solid fa-map-marker-alt"></i>
                            {{ $boardingHouse->district }}
                        </span>
                    </div>
                </div>
                
                <!-- Price & Status -->
                <div class="price-section">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div>
                            <div class="price-amount">
                                <span itemprop="price" content="{{ $boardingHouse->price }}">
                                    {{ numberFormatVi($boardingHouse->price) }}
                                </span>
                                <span class="price-period">VNĐ/tháng</span>
                            </div>
                        </div>
                        <span class="status-badge status-{{ $boardingHouse->status == 'available' ? 'available' : 'rented' }}">
                            <i class="fa-solid fa-circle-check"></i>
                            {{ $statues[$boardingHouse->status] }}
                        </span>
                    </div>
                </div>
                
                <!-- Quick Features -->
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-bed"></i>
                        </div>
                        <div class="feature-text">
                            <p class="feature-label">Loại phòng</p>
                            <p class="feature-value">{{ $boardingHouse->category }}</p>
                        </div>
                    </div>
                    <!-- <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-home"></i>
                        </div>
                        <div class="feature-text">
                            <p class="feature-label">Trạng thái</p>
                            <p class="feature-value">{{ $statues[$boardingHouse->status] }}</p>
                        </div>
                    </div> -->
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="feature-text">
                            <p class="feature-label">Khu vực</p>
                            <p class="feature-value">{{ $boardingHouse->district }}</p>
                        </div>
                    </div>
                    @if($boardingHouse->furniture_status)
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-couch"></i>
                        </div>
                        <div class="feature-text">
                            <p class="feature-label">Nội thất</p>
                            <p class="feature-value">{{ $furnitureStatuses[$boardingHouse->furniture_status] ?? 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Description -->
                <section class="mt-4">
                    <h2 class="section-title">
                        <i class="fa-solid fa-align-left me-2"></i>
                        Mô Tả Chi Tiết
                    </h2>
                    <div class="description-content mt-3" itemprop="description">
                        {!! $boardingHouse->content !!}
                    </div>
                </section>
                
                <!-- Address & Map -->
                <section class="mt-4">
                    <h2 class="section-title">
                        <i class="fa-solid fa-map-location-dot me-2"></i>
                        Địa Chỉ & Bản Đồ
                    </h2>
                    <div class="mt-3">
                        <div class="d-flex align-items-start gap-2 mb-3">
                            <i class="fa-solid fa-location-dot text-danger mt-1" style="font-size: 1.25rem;"></i>
                            <address class="mb-0" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                <span itemprop="streetAddress">{{ $boardingHouse->address }}</span>, 
                                <span itemprop="addressLocality">{{ $boardingHouse->ward }}</span>, 
                                <span itemprop="addressRegion">{{ $boardingHouse->district }}</span>
                            </address>
                        </div>
                        <div class="map-container">
                            @if(!! $boardingHouse->map_link)
                            <iframe
                                src="https://www.google.com/maps?q={{ urlencode($fullAddress) }}&output=embed"
                                width="100%" 
                                height="450" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Bản đồ vị trí {{ $boardingHouse->title }}">
                            </iframe>
                            @else
                            <iframe
                                src="{{ $boardingHouse->map_link }}"
                                width="100%" 
                                height="450" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Bản đồ vị trí {{ $boardingHouse->title }}">
                            </iframe>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Contact Section -->
        <aside class="col-lg-4">
            <div class="contact-card position-sticky" style="top: 100px;" itemscope itemtype="https://schema.org/Person">
                <h2>
                    <i class="fa-solid fa-address-card me-2"></i>
                    Thông Tin Liên Hệ
                </h2>
                
                <div class="contact-info">
                    <i class="fa-solid fa-user"></i>
                    <strong itemprop="name">{{ $boardingHouse->user_create->full_name }}</strong>
                </div>
                
                <div class="contact-info">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span>Zalo/SMS:</span><br>
                    <a href="{{ getZaloLink($boardingHouse->phone ?? $boardingHouse->user_create->phone) }}"
                       aria-label="Liên hệ {{ $boardingHouse->user_create->full_name }}" 
                       target="_blank" 
                       rel="noopener"
                       itemprop="telephone">
                        {{ $boardingHouse->phone ?? $boardingHouse->user_create->phone }}
                    </a>
                </div>
                
                <div class="contact-info">
                    <i class="fa-solid fa-envelope"></i>
                    <span>Email:</span><br>
                    <span>********@*****.com</span>
                </div>
                
                <!-- Contact Action Buttons -->
                <div class="contact-actions mt-3">
                    <a href="tel:{{ $boardingHouse->phone ?? $boardingHouse->user_create->phone }}" 
                       class="btn btn-call w-100 mb-2"
                       aria-label="Gọi điện cho {{ $boardingHouse->user_create->full_name }}">
                        <i class="fa-solid fa-phone"></i>
                        Gọi Ngay
                    </a>
                    
                    <a href="{{ getZaloLink($boardingHouse->phone ?? $boardingHouse->user_create->phone) }}" 
                       class="btn btn-zalo w-100 mb-2"
                       target="_blank"
                       rel="noopener"
                       aria-label="Chat Zalo với {{ $boardingHouse->user_create->full_name }}">
                        <i class="fa-brands fa-whatsapp"></i>
                        Chat Zalo
                    </a>
                </div>
                
                <div class="contact__divide mx-auto my-3">Hoặc</div>
                
                <button class="btn btn-success btn-appointment w-100" 
                        data-bs-toggle="modal" 
                        data-bs-target="#createAppointmentModal"
                        aria-label="Đặt lịch xem phòng {{ $boardingHouse->title }}">
                    <i class="fa-solid fa-calendar-check"></i>
                    Đặt Lịch Xem Phòng
                </button>
            </div>
        </aside>
    </div>

    <!-- Related Rooms -->
    @if($boardingHouseRelation->count() > 0)
    <section class="related-rooms-section">
        <div class="text-center mb-4">
            <h2 class="section-title">
                <i class="fa-solid fa-home me-2"></i>
                Có Thể Bạn Cũng Quan Tâm
            </h2>
            <p class="text-muted mt-2">Khám phá thêm các phòng trọ khác trong khu vực</p>
        </div>
        
        <div class="row g-3 g-md-4">
            @foreach($boardingHouseRelation as $index => $relation)
            <article class="col-xl-3 col-lg-4 col-md-6 col-6">
                <a href="{{ route('rentalHome.show', ['id' => $relation->id, 'title' => $relation->slug]) }}"
                   class="text-decoration-none d-block h-100">
                    <div class="related-room">
                        <div class="related-room-card">
                            <div class="related-room-image">
                                <picture>
                                    <source srcset="{{ resizeImageCloudinary($relation->thumbnail, 400, 270, 'webp') }}" type="image/webp">
                                    <img src="{{ resizeImageCloudinary($relation->thumbnail, 400, 270) }}" 
                                         alt="{{ $relation->title }}"
                                         loading="{{ $index < 4 ? 'eager' : 'lazy' }}" 
                                         decoding="async"
                                         width="400" 
                                         height="270">
                                </picture>
                                <span class="related-room-status {{ $relation->status == 'available' ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                    {{ $statues[$relation->status] }}
                                </span>
                            </div>
                            <div class="related-room-content">
                                <h3 class="related-room-title">{{ $relation->title }}</h3>
                                <div class="d-flex align-items-center gap-2 text-muted mb-2" style="font-size: 0.9rem;">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span>{{ $relation->district }}</span>
                                </div>
                                <p class="related-room-price mb-0">
                                    {{ getShortPrice($relation->price) }}
                                    <span class="text-dark" style="font-size: 0.875rem; font-weight: 500;">/tháng</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </article>
            @endforeach
        </div>
    </section>
    @endif
</div>

<!-- Modal Preview Image  -->
<div id="overlay-preview" class="position-fixed top-0 left-0 w-100 h-100 text-center" style="z-index:9998;background-color: rgba(0,0,0,0.5);overflow: hidden;">
    <div class="preview position-absolute top-0 left-0 w-100 h-100 text-center"></div>
    <button class="btn btn-light position-absolute btn__close" style="right:5px; top:5px; z-index:9999;" aria-label="Đóng preview">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>

@include('components.modals.create-appointment', [
    'id' => 'createAppointment',
    'title' => 'Tạo lịch hẹn xem phòng',
    'size' => 'lg',
    'okText' => 'Đặt lịch ngay',
    'b_title' => $boardingHouse->slug,
    'b_id' => $boardingHouse->id
])
@endsection

@push('js')
{{-- Defer non-critical scripts --}}
<script src="{{ asset('/vendor/flatpickr/js/flatpickr.min.js') }}" defer></script>
<script src="{{ asset('/vendor/flatpickr/js/vn.js') }}" defer></script>
<script src="{{ asset('/vendor/toastify/js/toastify.min.js') }}" defer></script>
<script src="{{ asset('assets/js/helper/global_helper.js') }}" defer></script>
<script src="{{ asset('assets/js/helper/ApiHelper.js') }}" defer></script>
<script src="{{ asset('/assets/js/core/splide.min.js') }}"></script>
<script src="{{ asset('assets/js/apps/rental/detail_rental_script.js') }}?v=1.3" defer></script>
<script src="{{ asset('assets/js/apps/rental/SavedListing.js') }}" defer></script>

{{-- Set authentication status for JavaScript --}}
<script>
    window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
</script>
@endpush

@push('seo')
<!-- Enhanced Meta Tags -->
<meta name="description" content="{{ $boardingHouse->meta_description ?? $boardingHouse->description }}">
<meta name="keywords" content="{{ $boardingHouse->tags }}, cho thuê phòng trọ, {{ $boardingHouse->district }}, {{ $boardingHouse->ward }}">
<meta name="author" content="{{ $boardingHouse->user_create->firstname }}">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="googlebot" content="index, follow">

<!-- Geo Tags -->
<meta name="geo.region" content="VN-SG">
<meta name="geo.placename" content="{{ $boardingHouse->district }}, Hồ Chí Minh">
<meta name="ICBM" content="10.8231,106.6297">

<!-- Open Graph Enhanced -->
<meta property="og:title" content="{{ $boardingHouse->title }} - Cho thuê phòng trọ tại {{ $boardingHouse->district }}">
<meta property="og:description" content="{{ $boardingHouse->meta_description ?? $boardingHouse->description }}">
<meta property="og:image" content="{{ resizeImageCloudinary($boardingHouse->thumbnail, 1200, 630) }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}">
<meta property="og:type" content="article">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:locale" content="vi_VN">
<meta property="article:author" content="{{ $boardingHouse->user_create->full_name }}">
<meta property="article:published_time" content="{{ $boardingHouse->created_at }}">
<meta property="article:modified_time" content="{{ $boardingHouse->updated_at }}">

<!-- Twitter Card Enhanced -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $boardingHouse->title }}">
<meta name="twitter:description" content="{{ $boardingHouse->meta_description ?? $boardingHouse->description }}">
<meta name="twitter:image" content="{{ resizeImageCloudinary($boardingHouse->thumbnail, 1200, 630) }}">
<meta name="twitter:image:alt" content="Hình ảnh {{ $boardingHouse->title }}">

<!-- Additional Images for Open Graph -->
@foreach($boardingHouse->boarding_house_files->take(3) as $file)
<meta property="og:image" content="{{ resizeImageCloudinary($file->url, 1200, 630) }}">
@endforeach

<link rel="canonical" href="{{ $boardingHouse->canonical_url ?? route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}">
@endpush

@push('jsonLD-sm')
<!-- Enhanced Structured Data -->
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
            "name": "Danh sách phòng cho thuê",
            "item": "{{ route('rentalHome.index') }}"
        },
        {
            "@type": "ListItem",
            "position": 3,
            "name": "{{ $boardingHouse->category }}",
            "item": "{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}"
        }
    ]
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $boardingHouse->title }}",
    "description": "{{ $boardingHouse->description }}",
    "image": [
        @foreach($boardingHouse->boarding_house_files->take(5) as $index => $file)
        "{{ resizeImageCloudinary($file->url, 1200, 800) }}"{{ $loop->last ? '' : ',' }}
        @endforeach
    ],
    "brand": {
        "@type": "Organization",
        "name": "{{ config('app.name') }}"
    },
    "offers": {
        "@type": "Offer",
        "price": "{{ $boardingHouse->price }}",
        "priceCurrency": "VND",
        "availability": "{{ $boardingHouse->status === 'available' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "validFrom": "{{ $boardingHouse->created_at }}",
        "priceValidUntil": "{{ now()->addMonths(3) }}",
        "seller": {
            "@type": "Person",
            "name": "{{ $boardingHouse->user_create->full_name }}",
            "telephone": "{{ $boardingHouse->phone ?? $boardingHouse->user_create->phone }}"
        }
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
    "url": "{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}",
    "datePublished": "{{ $boardingHouse->created_at }}",
    "dateModified": "{{ $boardingHouse->updated_at }}",
    "author": {
        "@type": "Person",
        "name": "{{ $boardingHouse->user_create->full_name }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "{{ config('app.name') }}"
    }
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "{{ $boardingHouse->title }}",
    "description": "{{ $boardingHouse->description }}",
    "image": "{{ resizeImageCloudinary($boardingHouse->thumbnail, 1200, 630) }}",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "{{ $boardingHouse->address }}",
        "addressLocality": "{{ $boardingHouse->ward }}",
        "addressRegion": "{{ $boardingHouse->district }}",
        "addressCountry": "VN"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "10.8231",
        "longitude": "106.6297"
    },
    "telephone": "{{ $boardingHouse->phone ?? $boardingHouse->user_create->phone }}",
    "priceRange": "{{ number_format($boardingHouse->price, 0, ',', '.') }} VND/tháng",
    "openingHours": "Mo-Su 08:00-20:00",
    "url": "{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}"
}
</script>
@endpush