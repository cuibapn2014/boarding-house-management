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
    /* Optimize layout shift */
    .item-img {
        aspect-ratio: 8/7;
        object-fit: cover;
        min-width: 140px;
        max-width: 200px;
        width: 100%;
        height: auto;
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
    
    /* Card optimization */
    .list-home .card {
        transition: all 0.2s ease;
        border: 1px solid #e9ecef;
        will-change: transform;
    }
    
    .list-home .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    /* Filter sidebar optimization */
    .filter__rental-home {
        position: sticky;
        top: 100px;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
        min-width: 280px;
        max-width: 320px;
    }
    
    /* Loading states */
    .loading-state {
        opacity: 0.7;
        pointer-events: none;
    }
    
    @media (max-width: 768px) {
        .item-img {
            min-width: 120px;
            max-width: 250px;
        }
        
        .list-home .col-6 {
            padding: 0 0.5rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .list-home #room-list {
            margin: 0 -0.5rem;
        }
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

<div class="container d-flex justify-content-center my-4 flex-md-row flex-column px-md-1 px-0">
    <button id="btn-open-filter-sidebar" 
            class="btn btn-sm btn-success mb-2 align-self-end mx-3 d-md-none" 
            data-bs-toggle="offcanvas" 
            href="#filter-sidebar" 
            role="button"
            aria-controls="filter-sidebar"
            aria-label="Mở bộ lọc tìm kiếm">
        <i class="fa-solid fa-filter"></i>
        <span>Bộ lọc</span>
        @if(count($currentFilters) > 0)
        <span class="badge bg-light text-dark ms-1">{{ count($currentFilters) }}</span>
        @endif
    </button>

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
            {{-- Results summary --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h4 mb-1">
                        @if(request('category'))
                            Cho thuê {{ implode(', ', (array)request('category')) }}
                        @else
                            Danh sách phòng trọ cho thuê
                        @endif
                        @if(request('district'))
                            tại {{ implode(', ', (array)request('district')) }}
                        @endif
                    </h1>
                    <p class="text-muted mb-0">
                        Tìm thấy <strong>{{ $boardingHouses->total() }}</strong> kết quả
                        @if($boardingHouses->currentPage() > 1)
                        - Trang {{ $boardingHouses->currentPage() }}/{{ $boardingHouses->lastPage() }}
                        @endif
                    </p>
                </div>
            </div>

            {{-- Active filters --}}
            @if(count($currentFilters) > 0)
            <div class="mb-3">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($currentFilters as $key => $value)
                    @if($value)
                    <span class="badge bg-light text-dark border">
                        {{ ucfirst($key) }}: 
                        @if(is_array($value))
                            {{ implode(', ', $value) }}
                        @else
                            {{ $value }}
                        @endif
                        <a href="#" class="ms-1 text-danger" data-filter="{{ $key }}" aria-label="Xóa bộ lọc {{ $key }}">×</a>
                    </span>
                    @endif
                    @endforeach
                    <a href="{{ route('rentalHome.index') }}" class="badge bg-danger text-white">
                        Xóa tất cả bộ lọc
                    </a>
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
                        <div class="card rounded mb-3 d-flex flex-md-nowrap flex-md-row overflow-hidden position-relative text-dark">
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
                                
                                <div class="text-success text-md fw-bold fs-4 mt-2" 
                                     itemprop="offers" 
                                     itemscope 
                                     itemtype="https://schema.org/Offer">
                                    <span itemprop="price" content="{{ $boardingHouse->price }}">
                                        {{ getShortPrice($boardingHouse->price) }}
                                    </span>/tháng
                                    <meta itemprop="priceCurrency" content="VND">
                                    <meta itemprop="availability" content="https://schema.org/InStock">
                                </div>
                                
                                <address class="text-sm fs-6" 
                                         itemprop="address" 
                                         itemscope 
                                         itemtype="https://schema.org/PostalAddress">
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
                            
                            <div class="bg-success position-absolute top-0 left-0 fs-6 text-white px-1" 
                                 itemprop="category">
                                {{ $categories[$boardingHouse->category] }}
                            </div>
                        </div>
                    </a>
                </article>
                @empty
                <div class="text-center py-5 w-100">
                    <div class="mb-3">
                        <i class="fa-solid fa-home fa-3x text-muted"></i>
                    </div>
                    <h3 class="h5">Không tìm thấy phòng trọ phù hợp</h3>
                    <p class="text-muted mb-3">Hãy thử điều chỉnh bộ lọc hoặc tìm kiếm với từ khóa khác</p>
                    <a href="{{ route('rentalHome.index') }}" class="btn btn-success">
                        <i class="fa-solid fa-arrow-left me-2"></i>
                        Xem tất cả phòng trọ
                    </a>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($boardingHouses->hasPages())
                <nav aria-label="Phân trang danh sách phòng trọ" class="mt-4">
                    <div class="row mx-0" id="pagination">
                        {{ $boardingHouses->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </nav>
                
                {{-- Pagination info for SEO --}}
                @if($boardingHouses->currentPage() > 1)
                <div class="text-center mt-3 text-muted small">
                    Trang {{ $boardingHouses->currentPage() }} của {{ $boardingHouses->lastPage() }} 
                    ({{ $boardingHouses->total() }} kết quả)
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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="filter-sidebar" aria-labelledby="filterSidebarLabel">
        <div class="offcanvas-header">
            <h3 class="offcanvas-title h5" id="filterSidebarLabel">
                <i class="fa-solid fa-filter"></i>
                <span class="text-md">Bộ Lọc</span>
                @if(count($currentFilters) > 0)
                <span class="badge bg-success ms-2">{{ count($currentFilters) }}</span>
                @endif
            </h3>
            <button type="button" 
                    class="btn-close" 
                    data-bs-dismiss="offcanvas" 
                    aria-label="Đóng bộ lọc"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between">
            @include('components.filter')
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

{{-- Enhanced filter functionality --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter removal functionality
    document.querySelectorAll('[data-filter]').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            const filterKey = this.dataset.filter;
            const url = new URL(window.location);
            url.searchParams.delete(filterKey);
            window.location.href = url.toString();
        });
    });

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