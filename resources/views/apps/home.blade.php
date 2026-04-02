@extends('master')
@section('title', 'Cho Thuê Phòng Trọ TPHCM Giá Rẻ | Nhà Trọ Tốt Sài Gòn - Tìm Phòng Uy Tín 2025')
@section('meta_description', 'Tìm phòng trọ, căn hộ, nhà nguyên căn tại TP.HCM với giá tốt, thông tin rõ ràng và cập nhật liên tục. Khám phá tin cho thuê mới mỗi ngày tại Nhà Trọ Tốt Sài Gòn.')

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
    
    .property-card:hover .partner-badge-compact {
        transform: scale(1.05);
        box-shadow: 0 3px 10px rgba(255, 215, 0, 0.5), 
                    0 2px 6px rgba(255, 140, 0, 0.4),
                    inset 0 1px 0 rgba(255, 255, 255, 0.4);
    }

    .pushed-top-badge {
        min-width: 34px;
        height: 24px;
        padding: 0 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: linear-gradient(135deg, #ff9800 0%, #ff5722 100%);
        color: #fff;
        box-shadow: 0 2px 10px rgba(255, 87, 34, 0.35);
        border: 1px solid rgba(255, 255, 255, 0.55);
        z-index: 4;
    }

    .pushed-top-badge i {
        font-size: 0.7rem;
    }
</style>
@endpush

@php
$categories = \App\Constants\SystemDefination::BOARDING_HOUSE_CATEGORY;
@endphp

@section('content')
@include('components.hero')

<main class="main-content">
    {{-- Popular Locations Section --}}
    <section class="popular-locations py-5" aria-labelledby="popular-locations-heading">
        <div class="container">
            <h2 id="popular-locations-heading" class="fw-bold mb-4">Địa Điểm Được Quan Tâm Nhiều Nhất</h2>
            
            <div class="row g-3 g-md-4" role="list">
                @foreach($latestPosts as $index => $boardingHouse)
                <article class="col-lg-3 col-md-4 col-sm-6 col-6" role="listitem" itemscope itemtype="https://schema.org/RealEstate">
                    <a href="{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}" 
                       class="text-decoration-none"
                       aria-label="Xem chi tiết {{ $boardingHouse->title }}">
                        @php
                            $isPushedTop = !empty($boardingHouse->pushed_at) && (empty($boardingHouse->expires_at) || strtotime($boardingHouse->expires_at) > time());
                        @endphp
                        <div class="card property-card h-100 border-0 shadow-sm overflow-hidden position-relative">
                            {{-- Property Image --}}
                            <div class="property-image position-relative">
                                <picture>
                                    <source media="(max-width: 768px)" srcset="{{ resizeImageCloudinary($boardingHouse->thumbnail, 300, 225, 'webp') }}" type="image/webp">
                                    <source srcset="{{ resizeImageCloudinary($boardingHouse->thumbnail, 400, 300, 'webp') }}" type="image/webp">
                                    <img class="card-img-top skeleton" 
                                         src="{{ resizeImageCloudinary($boardingHouse->thumbnail, 400, 300) }}"
                                         srcset="{{ resizeImageCloudinary($boardingHouse->thumbnail, 300, 225) }} 300w, {{ resizeImageCloudinary($boardingHouse->thumbnail, 400, 300) }} 400w"
                                         sizes="(max-width: 768px) 50vw, 25vw"
                                         alt="{{ $boardingHouse->title }}" 
                                         loading="{{ $index < 8 ? 'eager' : 'lazy' }}" 
                                         decoding="async"
                                         style="height: 200px; object-fit: cover;"
                                         itemprop="image"/>
                                </picture>
                                
                                {{-- Status Badge --}}
                                @if(!$isPushedTop)
                                <span class="pushed-top-badge position-absolute top-0 end-0 m-2" title="Tin được đẩy top" aria-label="Tin được đẩy top">
                                    <i class="fa-solid fa-bolt"></i>
                                </span>
                                @elseif($index < 3)
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2 px-2 py-1 shadow-sm">HOT</span>
                                @elseif($index < 6)
                                <span class="badge bg-success position-absolute top-0 end-0 m-2 px-2 py-1 shadow-sm">MỚI</span>
                                @endif

                                {{-- Favorite Icon --}}
                                <button class="btn btn-link position-absolute top-0 start-0 m-2 p-1 text-white bg-dark bg-opacity-25 rounded-circle" 
                                        type="button" 
                                        aria-label="Thêm vào yêu thích"
                                        style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                                    <i class="fa-regular fa-heart fs-6"></i>
                                </button>

                            </div>

                            {{-- Property Info --}}
                            <div class="card-body p-3 d-flex flex-column">
                                <h3 class="card-title fs-6 fw-semibold text-dark mb-2" 
                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; white-space: normal; min-height: 40px; line-height: 1.4;"
                                    itemprop="name">
                                    {{ $boardingHouse->title }}
                                </h3>
                                
                                <div class="price mb-2 d-flex align-items-center gap-2 flex-wrap" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                    <span class="text-success fw-bold fs-5" itemprop="price" content="{{ $boardingHouse->price }}">
                                        {{ getShortPrice($boardingHouse->price) }}/tháng
                                    </span>
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
                                
                                <address class="text-muted small mb-2 d-flex align-items-center" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                    <i class="fa-solid fa-location-dot me-1 text-danger"></i>
                                    <span class="text-truncate d-inline-block" style="max-width: 90%;" itemprop="addressLocality">
                                        {{ $boardingHouse->district }}
                                    </span>
                                </address>
                                
                                <div class="small text-muted d-flex align-items-center flex-wrap gap-2">
                                    @if(isset($boardingHouse->area) && $boardingHouse->area)
                                    <span class="d-flex align-items-center">
                                        <i class="fa-solid fa-expand-arrows-alt me-1 text-primary"></i>
                                        <span>{{ $boardingHouse->area }}m²</span>
                                    </span>
                                    @endif
                                    <span class="d-flex align-items-center">
                                        <i class="fa-solid fa-clock me-1"></i>
                                        <span>{{ dateForHumman($boardingHouse->created_at) }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>

            {{-- View More Button --}}
            @if($latestPosts->count() > 0)
            <div class="text-center mt-4">
                <a href="{{ route('rentalHome.index') }}" 
                   class="btn btn-outline-success px-5 py-2"
                   aria-label="Xem tất cả phòng trọ cho thuê">
                    Xem Thêm
                    <i class="fa-solid fa-arrow-right ms-2"></i>
                </a>
            </div>
            @endif
        </div>
    </section>

    {{-- Explore Areas Section --}}
    <section class="explore-areas py-5 bg-light" aria-labelledby="explore-areas-heading">
        <div class="container">
            <h2 id="explore-areas-heading" class="fw-bold mb-4">Khám Phá Các Khu Vực Trọng Điểm</h2>
            <p class="text-muted text-center mb-4">Tìm phòng trọ theo từng quận, huyện tại TP. Hồ Chí Minh</p>
            
            <div class="row g-3">
                @php
                $areas = [
                    ['name' => 'Quận 1', 'url' => route('rentalHome.index', ['district' => ['Quận 1']])],
                    ['name' => 'Quận 3', 'url' => route('rentalHome.index', ['district' => ['Quận 3']])],
                    ['name' => 'Quận 5', 'url' => route('rentalHome.index', ['district' => ['Quận 5']])],
                    ['name' => 'Quận 7', 'url' => route('rentalHome.index', ['district' => ['Quận 7']])],
                    ['name' => 'Quận 10', 'url' => route('rentalHome.index', ['district' => ['Quận 10']])],
                    ['name' => 'Bình Thạnh', 'url' => route('rentalHome.index', ['district' => ['Quận Bình Thạnh']])],
                ];
                @endphp

                @foreach($areas as $area)
                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                    <a href="{{ $area['url'] }}" 
                       class="btn btn-outline-secondary w-100 py-2 text-decoration-none"
                       title="Tìm phòng trọ cho thuê tại {{ $area['name'] }} giá rẻ"
                       aria-label="Xem danh sách phòng trọ tại {{ $area['name'] }}">
                        <i class="fa-solid fa-map-marker-alt me-1"></i>
                        {{ $area['name'] }}
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why Choose Us Section --}}
    <section class="why-choose-us py-5" aria-labelledby="why-choose-us-heading">
        <div class="container">
            <h2 id="why-choose-us-heading" class="fw-bold text-center mb-5">Tại Sao Nên Chọn Nền Tảng Của Chúng Tôi?</h2>
            
            <div class="row g-4 justify-content-center">
                {{-- Feature 1: Management --}}
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="feature-icon mb-3">
                            <i class="fa-solid fa-shield-halved text-success fs-1"></i>
                        </div>
                        <h3 class="feature-title h5 fw-bold mb-3">Quản Lý Chuyên Nghiệp</h3>
                        <p class="feature-desc text-muted mb-0">
                            Hơn 1,200 phòng trọ được quản lý<br>
                            Đảm bảo chất lượng và uy tín
                        </p>
                    </div>
                </div>

                {{-- Feature 2: Fast & Trust --}}
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="feature-icon mb-3">
                            <i class="fa-solid fa-bolt text-warning fs-1"></i>
                        </div>
                        <h3 class="feature-title h5 fw-bold mb-3">Nhanh Chóng & Tin Cậy</h3>
                        <p class="feature-desc text-muted mb-0">
                            Tìm kiếm nhanh chóng, chính xác<br>
                            Thông tin được xác thực kỹ lưỡng
                        </p>
                    </div>
                </div>

                {{-- Feature 3: Support 24/7 --}}
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="feature-icon mb-3">
                            <i class="fa-solid fa-headset text-info fs-1"></i>
                        </div>
                        <h3 class="feature-title h5 fw-bold mb-3">Hỗ Trợ 24/7</h3>
                        <p class="feature-desc text-muted mb-0">
                            Đội ngũ tư vấn nhiệt tình<br>
                            Sẵn sàng hỗ trợ mọi lúc mọi nơi
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SEO Content Section --}}
    <section class="seo-content py-5 bg-light">
        <div class="container">
            <article class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="h3 fw-bold mb-4">Cho Thuê Phòng Trọ TPHCM Giá Rẻ - Tìm Nhà Trọ Uy Tín {{ date('Y') }}</h2>
                    
                    <div class="content-text text-muted">
                        <p class="mb-3">
                            <strong>Nhatrototsaigon.com</strong> là nền tảng <strong>cho thuê phòng trọ TPHCM</strong> uy tín hàng đầu, chuyên cung cấp 
                            các giải pháp tìm kiếm <strong>phòng trọ giá rẻ</strong> cho sinh viên, người đi làm tại khu vực Hồ Chí Minh. 
                            Với hơn <strong>1,200 phòng trọ</strong> được cập nhật liên tục, chúng tôi cam kết mang đến cho bạn những lựa chọn 
                            tốt nhất với mức giá từ <strong>1-3 triệu đồng/tháng</strong>.
                        </p>
                        
                        <h3 class="h5 fw-bold mt-4 mb-3">Tìm Phòng Trọ Gần Trường Đại Học</h3>
                        <p class="mb-3">
                            Nếu bạn là sinh viên đang tìm kiếm <strong>phòng trọ gần trường đại học</strong>, Nhatrototsaigon.com cung cấp bộ lọc thông minh 
                            giúp bạn dễ dàng tìm được phòng trọ gần các trường như: Đại học Bách Khoa, Đại học Khoa học Xã hội và Nhân văn, 
                            Đại học Kinh tế, Đại học Sư phạm TPHCM... với khoảng cách chỉ từ 500m - 2km.
                        </p>
                        
                        <h3 class="h5 fw-bold mt-4 mb-3">Phòng Trọ Giá Rẻ Theo Quận</h3>
                        <p class="mb-3">
                            Nhatrototsaigon.com có <strong>phòng trọ cho thuê</strong> tại tất cả các quận huyện TPHCM: Quận 1, Quận 3, Quận 5, Quận 7, 
                            Quận 10, Bình Thạnh, Phú Nhuận, Tân Bình, Thủ Đức... Mỗi khu vực đều có những ưu điểm riêng về vị trí, 
                            tiện ích và mức giá phù hợp với từng nhu cầu.
                        </p>
                        
                        <h3 class="h5 fw-bold mt-4 mb-3">Cam Kết Của Nhatrototsaigon</h3>
                        <ul class="mb-3">
                            <li>✓ Thông tin phòng trọ được xác thực 100%</li>
                            <li>✓ Hỗ trợ xem phòng trực tiếp miễn phí</li>
                            <li>✓ Tư vấn nhiệt tình, chuyên nghiệp 24/7</li>
                            <li>✓ Không thu phí trung gian</li>
                            <li>✓ Cập nhật phòng mới hàng ngày</li>
                        </ul>
                        
                        <p class="mb-0">
                            Hãy để <strong>Nhatrototsaigon.com</strong> đồng hành cùng bạn tìm được <strong>nơi ở hoàn hảo</strong> tại Sài Gòn. 
                            Liên hệ ngay <strong><a href="tel:0388794195" class="text-success">0388 794 195</a></strong> để được hỗ trợ tư vấn miễn phí!
                        </p>
                    </div>
                </div>
            </article>
        </div>
    </section>
</main>
@endsection

@push('seo')
<!-- Enhanced Meta Tags -->
<meta name="title" content="Cho Thuê Phòng Trọ TPHCM Giá Rẻ | Nhà Trọ Tốt Sài Gòn - Tìm Phòng Uy Tín 2025"/>
<meta name="description" content="🏠 Tìm phòng trọ TPHCM giá rẻ từ 1-3 triệu ⭐ Hơn 1,200+ phòng trọ uy tín ✓ Cập nhật mới nhất 2025 ✓ Gần trường ĐH, công ty ✓ Xem phòng miễn phí ✓ Hỗ trợ 24/7 | Nhatrototsaigon.com">
<meta name="keywords" content="cho thuê phòng trọ TPHCM, phòng trọ giá rẻ Sài Gòn, thuê phòng trọ quận 1, phòng trọ sinh viên, nhà trọ gần trường đại học, phòng trọ có gác, KTX giá rẻ, nhà nguyên căn cho thuê, phòng trọ Bình Thạnh, phòng trọ quận 7, phòng trọ có ban công, phòng trọ dưới 3 triệu, cho thuê phòng có điều hòa, phòng trọ gần công ty"
<meta name="author" content="Nhatrototsaigon Team">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="googlebot" content="index, follow">

<!-- Geo Tags -->
<meta name="geo.region" content="VN-SG">
<meta name="geo.placename" content="Hồ Chí Minh, Việt Nam">
<meta name="ICBM" content="10.8231,106.6297">

<!-- Enhanced Open Graph -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="Nhà Trọ Tốt Sài Gòn - Nhatrototsaigon.com">
<meta property="og:locale" content="vi_VN">
<meta property="og:title" content="Cho Thuê Phòng Trọ TPHCM Giá Rẻ | Nhà Trọ Tốt Sài Gòn - Hơn 1,200+ Phòng">
<meta property="og:description" content="🏠 Tìm phòng trọ TPHCM giá rẻ từ 1-3 triệu. Hơn 1,200+ phòng trọ uy tín, cập nhật mới nhất 2025. Gần trường ĐH, công ty. Xem phòng miễn phí. Hỗ trợ 24/7.">
<meta property="og:image" content="{{ asset('assets/images/hero-background.webp') }}">
<meta property="og:image:alt" content="Cho thuê phòng trọ TPHCM giá rẻ - Nhà Trọ Tốt Sài Gòn">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="{{ route('home.index') }}">

<!-- Enhanced Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@nhatrototsaigon">
<meta name="twitter:creator" content="@nhatrototsaigon">
<meta name="twitter:title" content="Cho Thuê Phòng Trọ TPHCM Giá Rẻ | Nhà Trọ Tốt Sài Gòn">
<meta name="twitter:description" content="🏠 Tìm phòng trọ TPHCM giá rẻ từ 1-3 triệu. Hơn 1,200+ phòng uy tín. Cập nhật 2025. Hỗ trợ 24/7.">
<meta name="twitter:image" content="{{ asset('assets/images/hero-background.webp') }}">
<meta name="twitter:image:alt" content="Nhà Trọ Tốt Sài Gòn - Nền tảng cho thuê phòng trọ TPHCM uy tín">

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
    "name": "Nhà Trọ Tốt Sài Gòn - Nhatrototsaigon.com",
    "alternateName": ["Nhatrototsaigon", "Nhà Trọ Tốt SG", "Tìm Phòng Trọ TPHCM"],
    "url": "{{ route('home.index') }}",
    "description": "Nền tảng cho thuê phòng trọ, nhà nguyên căn uy tín tại TP. Hồ Chí Minh với hơn 1,200 phòng trọ giá rẻ từ 1-3 triệu đồng/tháng",
    "publisher": {
        "@type": "Organization",
        "name": "Nhatrototsaigon",
        "url": "{{ route('home.index') }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('assets/images/icon/logo.webp') }}"
        }
    },
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
            "@type": "EntryPoint",
            "urlTemplate": "{{ route('rentalHome.index') }}?search={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    },
    "audience": {
        "@type": "Audience",
        "audienceType": "Sinh viên, người đi làm tại TP. Hồ Chí Minh"
    }
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "RealEstateAgent",
    "name": "Nhà Trọ Tốt Sài Gòn - Nhatrototsaigon.com",
    "alternateName": "Nhatrototsaigon",
    "url": "{{ route('home.index') }}",
    "logo": "{{ asset('assets/images/icon/logo.webp') }}",
    "description": "Nền tảng cho thuê phòng trọ, nhà nguyên căn uy tín hàng đầu tại TP. Hồ Chí Minh. Chuyên cung cấp phòng trọ giá rẻ cho sinh viên và người đi làm.",
    "image": "{{ asset('assets/images/hero-background.webp') }}",
    "priceRange": "1.000.000đ - 10.000.000đ",
    "telephone": "0388794195",
    "email": "nmtworks.7250@gmail.com",
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "TP. Hồ Chí Minh",
        "addressRegion": "Hồ Chí Minh",
        "addressCountry": "VN"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "10.8231",
        "longitude": "106.6297"
    },
    "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
        "opens": "00:00",
        "closes": "23:59"
    },
    "sameAs": [
        "https://facebook.com/FakerHT",
        "https://instagram.com/manhtuan.n7250"
    ],
    "areaServed": [
        {
            "@type": "City",
            "name": "Hồ Chí Minh",
            "containedInPlace": {
                "@type": "Country",
                "name": "Việt Nam"
            }
        }
    ],
    "knowsAbout": ["Cho thuê phòng trọ", "Cho thuê nhà nguyên căn", "Cho thuê KTX", "Phòng trọ sinh viên"],
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "reviewCount": "1250",
        "bestRating": "5",
        "worstRating": "1"
    }
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Danh sách phòng trọ cho thuê mới nhất tại TPHCM",
    "description": "Danh sách {{ $latestPosts->count() }} phòng trọ và nhà cho thuê mới nhất, cập nhật {{ date('Y') }} tại TP. Hồ Chí Minh với giá từ 1-10 triệu/tháng",
    "numberOfItems": {{ $latestPosts->count() }},
    "itemListElement": [
        @foreach($latestPosts as $index => $boardingHouse)
        {
            "@type": "ListItem",
            "position": {{ $index + 1 }},
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

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {
            "@type": "Question",
            "name": "Nhatrototsaigon là gì?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Nhatrototsaigon.com là nền tảng cho thuê phòng trọ uy tín hàng đầu tại TP. Hồ Chí Minh, cung cấp hơn 1,200 phòng trọ giá rẻ từ 1-3 triệu đồng/tháng. Chúng tôi kết nối chủ nhà và người thuê một cách nhanh chóng, tiện lợi với dịch vụ hỗ trợ 24/7."
            }
        },
        {
            "@type": "Question",
            "name": "Giá thuê phòng trọ tại TPHCM là bao nhiêu?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Giá thuê phòng trọ tại TPHCM dao động từ 1-10 triệu đồng/tháng tùy theo khu vực và tiện ích. Phòng trọ sinh viên giá rẻ thường từ 1-3 triệu, phòng có đầy đủ tiện nghi từ 3-5 triệu, và nhà nguyên căn từ 5-10 triệu trở lên."
            }
        },
        {
            "@type": "Question",
            "name": "Làm sao để tìm phòng trọ gần trường đại học?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Bạn có thể sử dụng thanh tìm kiếm trên Nhatrototsaigon.com, nhập tên trường đại học hoặc khu vực gần trường. Chúng tôi có bộ lọc thông minh giúp bạn tìm được phòng trọ phù hợp với vị trí, giá cả và tiện ích mong muốn."
            }
        },
        {
            "@type": "Question",
            "name": "Có cần đặt cọc khi thuê phòng trọ không?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Thông thường, khi thuê phòng trọ bạn cần đặt cọc từ 1-2 tháng tiền thuê. Tuy nhiên, trên Nhatrototsaigon.com có nhiều phòng trọ không yêu cầu cọc hoặc cọc thấp. Bạn có thể lọc theo tiêu chí này khi tìm kiếm."
            }
        },
        {
            "@type": "Question",
            "name": "Nhatrototsaigon có hỗ trợ xem phòng trực tiếp không?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Có, Nhatrototsaigon.com hỗ trợ đặt lịch xem phòng trực tiếp hoàn toàn miễn phí. Chúng tôi có đội ngũ tư vấn sẵn sàng đồng hành cùng bạn trong quá trình tìm kiếm và xem phòng 24/7."
            }
        }
    ]
}
</script>
@endpush