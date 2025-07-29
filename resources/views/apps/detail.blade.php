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
$fullAddress = "{$boardingHouse->address}, {$boardingHouse->ward}, {$boardingHouse->district}";
@endphp

<div class="container" style="margin-top: 100px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="/" itemprop="item"><span itemprop="name">Home</span></a>
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
            <div>
                <div class="hero-container mb-2">
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
                             class="hero-image mb-4" 
                             loading="eager" 
                             decoding="async" 
                             alt="Hình ảnh {{ $boardingHouse->title }}"
                             width="800" 
                             height="450" />
                    </picture>
                    @endif
                </div>
                
                <section id="thumbnail-carousel" class="splide" aria-label="Ảnh phòng trọ được chọn hiển thị">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach($boardingHouse->boarding_house_files as $index => $file)
                            <li class="splide__slide rounded position-relative" aria-hidden="false">
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
                                    <i class="fa-solid fa-video text-white" aria-label="Video phòng trọ"></i>
                                </div>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            </div>
            
            <div>
                <h1 class="fw-bold fs-2 mt-1">{{ $boardingHouse->title }}</h1>
                <p class="text-muted">
                    Đăng bởi <strong>{{ $boardingHouse->user_create->firstname }}</strong> | 
                    <time datetime="{{ $boardingHouse->updated_at }}">
                        Cập nhật {{ dateForHumman($boardingHouse->updated_at) }}
                    </time>
                </p>
            </div>
            
            <div class="fw-bold fs-3 text-success d-flex align-items-center" style="gap:5px;">
                <span itemprop="price" content="{{ $boardingHouse->price }}">{{ numberFormatVi($boardingHouse->price) }}</span>
                <span class="text-dark fs-6">/tháng</span>
                <span class="{{ $boardingHouse->status == 'available' ? 'bg-success text-white' : 'bg-warning text-dark' }} py-1 px-2 rounded-pill fs-6"
                    style="max-width: fit-content;font-size:.5em;">{{ $statues[$boardingHouse->status] }}</span>
            </div>
            
            <section class="mt-3">
                <h2 class="fw-bold h4">Mô Tả</h2>
                <div itemprop="description">
                    {!! $boardingHouse->content !!}
                </div>
            </section>
            
            <section class="mt-3">
                <h2 class="fw-bold h4">Địa Chỉ</h2>
                <address itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                    <span itemprop="streetAddress">{{ $boardingHouse->address }}</span>, 
                    <span itemprop="addressLocality">{{ $boardingHouse->ward }}</span>, 
                    <span itemprop="addressRegion">{{ $boardingHouse->district }}</span>
                </address>
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
            </section>
        </div>

        <!-- Contact Section -->
        <aside class="col-lg-4">
            <div class="contact-card position-sticky" style="top: 100px;" itemscope itemtype="https://schema.org/Person">
                <h2 class="fw-bold h4">Thông Tin Liên Hệ</h2>
                <p>
                    <strong>
                        <i class="fa-solid fa-user fa-fw fa-lg"></i>
                        <span itemprop="name">{{ $boardingHouse->user_create->full_name }}</span>
                    </strong>
                </p>
                <p>Zalo/SMS: 
                    <a href="{{ getZaloLink($boardingHouse->phone ?? $boardingHouse->user_create->phone) }}"
                       aria-label="Liên hệ {{ $boardingHouse->user_create->full_name }}" 
                       target="_blank" 
                       rel="noopener"
                       itemprop="telephone">{{ $boardingHouse->phone ?? $boardingHouse->user_create->phone }}</a>
                </p>
                <p><i class="fa-solid fa-envelope text-warning mr-2"></i> <span>********@*****.com</span></p>
                <div class="contact__divide mx-auto my-2">Hoặc</div>
                <button class="btn btn-success w-100" 
                        data-bs-toggle="modal" 
                        data-bs-target="#createAppointmentModal"
                        aria-label="Đặt lịch xem phòng {{ $boardingHouse->title }}">
                    Đặt lịch xem phòng
                </button>
            </div>
        </aside>
    </div>

    <!-- Related Rooms -->
    @if($boardingHouseRelation->count() > 0)
    <section class="mt-5">
        <h2 class="fw-bold mb-2 h3">Có Thể Bạn Cũng Quan Tâm</h2>
        <div class="row g-3">
            @foreach($boardingHouseRelation as $index => $relation)
            <article class="col-md-3 col-6">
                <a href="{{ route('rentalHome.show', ['id' => $relation->id, 'title' => $relation->slug]) }}"
                   class="text-dark position-relative d-block">
                    <div class="related-room">
                        <picture>
                            <source srcset="{{ resizeImageCloudinary($relation->thumbnail, 400, 270, 'webp') }}" type="image/webp">
                            <img src="{{ resizeImageCloudinary($relation->thumbnail, 400, 270) }}" 
                                 alt="{{ $relation->title }}"
                                 class="img-fluid rounded" 
                                 loading="{{ $index < 4 ? 'eager' : 'lazy' }}" 
                                 decoding="async"
                                 width="400" 
                                 height="270">
                        </picture>
                        <h3 class="mt-2 fw-bold fs-5">{{ $relation->title }}</h3>
                        <span class="fs-6 {{ $relation->status == 'available' ? 'bg-success text-white' : 'bg-warning text-dark' }} p-1 position-absolute top-0 left-0"
                              style="max-width: fit-content;">{{ $statues[$relation->status] }}</span>
                        <p class="fw-bold text-success">
                            {{ getShortPrice($relation->price) }}
                            <span class="text-dark fs-6">/tháng</span>
                        </p>
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
@endpush

@push('seo')
<!-- Enhanced Meta Tags -->
<meta name="description" content="{{ $boardingHouse->description }}">
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
<meta property="og:description" content="{{ $boardingHouse->description }}">
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
<meta name="twitter:description" content="{{ $boardingHouse->description }}">
<meta name="twitter:image" content="{{ resizeImageCloudinary($boardingHouse->thumbnail, 1200, 630) }}">
<meta name="twitter:image:alt" content="Hình ảnh {{ $boardingHouse->title }}">

<!-- Additional Images for Open Graph -->
@foreach($boardingHouse->boarding_house_files->take(3) as $file)
<meta property="og:image" content="{{ resizeImageCloudinary($file->url, 1200, 630) }}">
@endforeach

<link rel="canonical" href="{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}">
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
    "@type": ["Product", "RealEstate"],
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