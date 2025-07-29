@extends('master')
@section('title', 'Nhà Trọ Tốt Sài Gòn: Cho thuê phòng trọ, nhà trọ giá tốt ở khu vực Hồ Chí Minh')

@push('css')
{{-- Preload critical resources --}}
<link rel="preload" as="image" href="{{ asset('assets/images/hero-bg.webp') }}" fetchpriority="high" />
<link rel="preload" href="{{ asset('assets/images/hero-background.webp') }}" as="image" fetchpriority="high"/>
<link rel="preload" href="{{ asset('assets/css/apps/home/style.css') }}" as="style" />

{{-- Preload category images --}}
<link rel="preload" as="image" href="{{ asset('assets/images/room.webp') }}" fetchpriority="high" />
<link rel="preload" as="image" href="{{ asset('assets/images/sleepbox.webp') }}" fetchpriority="low" />
<link rel="preload" as="image" href="{{ asset('assets/images/house.webp') }}" fetchpriority="low" />

{{-- Preload first few listing images --}}
@foreach($latestPosts->take(4) as $index => $boardingHouse)
<link rel="preload" as="image" href="{{ resizeImageCloudinary($boardingHouse->thumbnail, 400, 350, 'webp') }}" fetchpriority="{{ $index < 2 ? 'high' : 'low' }}" />
@endforeach

<link rel="stylesheet" href="{{ asset('assets/css/apps/home/style.css') }}"/>

{{-- Critical CSS inline --}}
<style>
    /* Optimize layout shift */
    .card img {
        aspect-ratio: 4/3;
        object-fit: cover;
        width: 100%;
        height: auto;
    }
    
    .item-img {
        aspect-ratio: 8/7;
        object-fit: cover;
        min-width: 140px;
        max-width: 200px;
    }
    
    /* Enhanced skeleton loading */
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
    
    /* Optimize card hover effects */
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        will-change: transform;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Category cards optimization */
    .categories .card {
        min-width: 20rem;
        border: none;
        overflow: hidden;
    }
    
    /* List item optimization */
    .list-home .card {
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
    }
</style>
@endpush

@php
$categories = \App\Constants\SystemDefination::BOARDING_HOUSE_CATEGORY;
@endphp

@section('content')
@include('components.hero')

<main>
    <section class="categories" aria-labelledby="categories-heading">
        <div class="container">
            <h2 id="categories-heading" class="fw-bold">Danh Mục Nổi Bật</h2>
            <div class="d-flex flex-nowrap justify-content-md-center" id="room-list" style="overflow-x: auto;gap: 0px 5px;" role="list">
                <article class="card pointer" style="min-width: 20rem" role="listitem">
                    <a href="{{ route('rentalHome.index', ['category' => ['Phòng']]) }}" class="text-dark text-decoration-none" aria-label="Xem phòng trọ giá rẻ">
                        <picture>
                            <source srcset="{{ asset('assets/images/room.webp') }}" type="image/webp">
                            <img class="skeleton" 
                                 src="{{ asset('assets/images/room.webp') }}" 
                                 alt="Phòng Trọ Giá Rẻ" 
                                 loading="eager" 
                                 decoding="async"
                                 width="320"
                                 height="240">
                        </picture>
                        <h3 class="fw-bold p-3 mb-0">Phòng Trọ Giá Rẻ</h3>
                    </a>
                </article>
                
                <article class="card pointer" style="min-width: 20rem" role="listitem">
                    <a href="{{ route('rentalHome.index', ['category' => ['KTX', 'SLEEPBOX']]) }}" class="text-dark text-decoration-none" aria-label="Xem KTX/Sleepbox sang trọng">
                        <picture>
                            <source srcset="{{ asset('assets/images/sleepbox.webp') }}" type="image/webp">
                            <img class="skeleton" 
                                 src="{{ asset('assets/images/sleepbox.webp') }}" 
                                 alt="KTX/Sleepbox Sang Trọng" 
                                 loading="lazy" 
                                 decoding="async"
                                 width="320"
                                 height="240">
                        </picture>
                        <h3 class="fw-bold p-3 mb-0">KTX/Sleepbox Sang Trọng</h3>
                    </a>
                </article>
                
                <article class="card pointer" style="min-width: 20rem" role="listitem">
                    <a href="{{ route('rentalHome.index', ['category' => ['Nhà nguyên căn']]) }}" class="text-dark text-decoration-none" aria-label="Xem căn hộ hiện đại">
                        <picture>
                            <source srcset="{{ asset('assets/images/house.webp') }}" type="image/webp">
                            <img class="skeleton" 
                                 src="{{ asset('assets/images/house.webp') }}" 
                                 alt="Căn Hộ Hiện Đại" 
                                 loading="lazy" 
                                 decoding="async"
                                 width="320"
                                 height="240">
                        </picture>
                        <h3 class="fw-bold p-3 mb-0">Căn Hộ Hiện Đại</h3>
                    </a>
                </article>
            </div>
        </div>
    </section>

    <section class="list-home" aria-labelledby="recent-posts-heading">
        <div class="container px-md-2 px-0">
            <h2 id="recent-posts-heading" class="text-center fw-bold">Gần đây nhất</h2>
            <div class="d-flex flex-wrap my-3" id="room-list" role="list">
                @foreach($latestPosts as $index => $boardingHouse)
                <article class="flex-grow-1 col-md-12 col-6 px-1" role="listitem" itemscope itemtype="https://schema.org/RealEstate">
                    <a href="{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}" 
                       class="text-decoration-none"
                       aria-label="Xem chi tiết {{ $boardingHouse->title }}">
                        <div class="card rounded my-2 d-flex flex-md-nowrap flex-md-row overflow-hidden pointer text-dark">
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
                            
                            <div class="item-info flex-grow-1 p-2">
                                <h3 class="__title text-lg fw-bold fs-5" itemprop="name">{{ $boardingHouse->title }}</h3>
                                
                                <div class="text-success text-md fw-bold fs-4 mt-2" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                    <span itemprop="price" content="{{ $boardingHouse->price }}">{{ getShortPrice($boardingHouse->price) }}</span><span>/tháng</span>
                                    <meta itemprop="priceCurrency" content="VND">
                                    <meta itemprop="availability" content="https://schema.org/InStock">
                                </div>
                                
                                <address class="text-sm fs-6" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                    <i class="fa-solid fa-location-dot text-danger"></i>
                                    <span itemprop="addressLocality">{{ $boardingHouse->district }}</span>
                                </address>
                                
                                <div class="text-sm fs-6 mb-0">
                                    <i class="fa-solid fa-clock" style="color:#b0b0b0"></i>
                                    <time datetime="{{ $boardingHouse->created_at }}" itemprop="datePublished">
                                        {{ dateForHumman($boardingHouse->created_at) }}
                                    </time>
                                </div>
                            </div>
                            
                            <div class="bg-success position-absolute top-0 left-0 fs-6 text-white px-1" itemprop="category">
                                {{ $categories[$boardingHouse->category] }}
                            </div>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>
            
            @if($latestPosts->count() > 0)
            <div class="text-center mt-4">
                <a href="{{ route('rentalHome.index') }}" 
                   class="btn btn-success btn-sm px-4"
                   aria-label="Xem tất cả phòng trọ cho thuê">
                    Xem Thêm Phòng Trọ
                    <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
            @endif
        </div>
    </section>
</main>
@endsection

@push('seo')
<!-- Enhanced Meta Tags -->
<meta name="title" content="Nhà Trọ Tốt Sài Gòn: Cho thuê phòng trọ, nhà trọ giá tốt ở khu vực Hồ Chí Minh"/>
<meta name="description" content="Tìm kiếm và thuê phòng trọ, nhà nguyên căn, căn hộ hiện đại dễ dàng với Nhatrototsaigon. Khám phá hàng ngàn chỗ ở hoàn hảo cho bạn với giá tốt nhất.">
<meta name="keywords" content="thuê phòng trọ TP.HCM, thuê nhà nguyên căn Sài Gòn, thuê căn hộ giá rẻ, tìm kiếm chỗ ở, phòng trọ giá rẻ, nhà cho thuê, nhatrototsaigon, nhà trọ tốt sài gòn, phòng trọ quận 1, phòng trọ quận 7, phòng trọ Bình Thạnh">
<meta name="author" content="Nhatrototsaigon Team">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="googlebot" content="index, follow">

<!-- Geo Tags -->
<meta name="geo.region" content="VN-SG">
<meta name="geo.placename" content="Hồ Chí Minh, Việt Nam">
<meta name="ICBM" content="10.8231,106.6297">

<!-- Enhanced Open Graph -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:locale" content="vi_VN">
<meta property="og:title" content="Nhà Trọ Tốt Sài Gòn: Cho thuê phòng trọ, nhà trọ giá tốt ở khu vực Hồ Chí Minh">
<meta property="og:description" content="Khám phá hàng ngàn phòng trọ và nhà cho thuê dễ dàng. Tìm chỗ ở hoàn hảo gần bạn với giá tốt nhất tại TP.HCM.">
<meta property="og:image" content="{{ asset('assets/images/hero-background.webp') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="{{ route('home.index') }}">

<!-- Enhanced Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@nhatrototsaigon">
<meta name="twitter:title" content="Nhà Trọ Tốt Sài Gòn: Cho thuê phòng trọ, nhà trọ giá tốt">
<meta name="twitter:description" content="Khám phá hàng ngàn phòng trọ và nhà cho thuê dễ dàng. Tìm chỗ ở hoàn hảo gần bạn.">
<meta name="twitter:image" content="{{ asset('assets/images/hero-background.webp') }}">
<meta name="twitter:image:alt" content="Nhà Trọ Tốt Sài Gòn - Nền tảng cho thuê phòng trọ">

<link rel="canonical" href="{{ route('home.index') }}">

<!-- Additional Rich Snippets --> 
<meta property="business:contact_data:street_address" content="Hồ Chí Minh">
<meta property="business:contact_data:locality" content="Hồ Chí Minh">
<meta property="business:contact_data:region" content="VN">
<meta property="business:contact_data:country_name" content="Vietnam">
@endpush

@push('jsonLD-lg')
<!-- Enhanced Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "{{ config('app.name') }}",
    "alternateName": "Nhà Trọ Tốt Sài Gòn",
    "url": "{{ route('home.index') }}",
    "description": "Nền tảng cho thuê phòng trọ, nhà nguyên căn và căn hộ tại Hồ Chí Minh",
    "publisher": {
        "@type": "Organization",
        "name": "{{ config('app.name') }}",
        "url": "{{ route('home.index') }}"
    },
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
            "@type": "EntryPoint",
            "urlTemplate": "{{ route('rentalHome.index') }}?search={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "{{ config('app.name') }}",
    "alternateName": "Nhà Trọ Tốt Sài Gòn",
    "url": "{{ route('home.index') }}",
    "logo": "{{ asset('assets/images/icon/logo.webp') }}",
    "description": "Nền tảng cho thuê phòng trọ, nhà nguyên căn và căn hộ hàng đầu tại Hồ Chí Minh",
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "Hồ Chí Minh",
        "addressRegion": "Hồ Chí Minh",
        "addressCountry": "VN"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "10.8231",
        "longitude": "106.6297"
    },
    "sameAs": [
        "https://facebook.com/nhatrototsaigon",
        "https://twitter.com/nhatrototsaigon"
    ],
    "areaServed": {
        "@type": "State",
        "name": "Hồ Chí Minh"
    }
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Danh sách phòng trọ mới nhất",
    "description": "Các phòng trọ và nhà cho thuê mới nhất tại Hồ Chí Minh",
    "numberOfItems": {{ $latestPosts->count() }},
    "itemListElement": [
        @foreach($latestPosts as $index => $boardingHouse)
        {
            "@type": "ListItem",
            "position": {{ $index + 1 }},
            "item": {
                "@type": ["Product", "RealEstate"],
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
                    "priceValidUntil": "{{ now()->addMonths(6) }}"
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
        }
    ]
}
</script>
@endpush