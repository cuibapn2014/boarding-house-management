@extends('master')
@section('title', $boardingHouse->title)
@push('css')
@foreach($boardingHouse->boarding_house_files as $file)
<link rel="preload" as="image" href="{{ resizeImageCloudinary($file->url, 300, 200) }}" fetchpriority="high" />
<link rel="preload" as="image" href="{{ $file->type === 'image' ? resizeImageCloudinary($file->url, 800, 450) : $file->url }}" fetchpriority="high" />
@endforeach
<link rel="stylesheet" href="{{ asset('vendor/toastify/css/toastify.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('vendor/flatpickr/css/flatpickr.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/css/splide.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/css/apps/rental-home/detail_style.css') }}"/>
<style>
    #overlay-preview:not(:has(img)) {
        display: none;
    }

    .preview-item {
        height: 100%;
        object-fit: contain;
    }
</style>
@endpush
@section('content')

@php
$statues = \App\Constants\SystemDefination::BOARDING_HOUSE_STATUS;
@endphp
<div class="container" style="margin-top: 100px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('rentalHome.index') }}">Danh sách cho thuê</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $boardingHouse->category }}</li>
        </ol>
    </nav>
    <!-- Room Details -->
    <div class="row">
        <div class="col-lg-8">
            <div>
                <div class="hero-container mb-2 skeleton">
                    @if($boardingHouse?->boarding_house_files?->first())
                    <img src="{{ getUrlImage($boardingHouse?->boarding_house_files?->first()->url) }}" class="hero-image mb-4 w-100 skeleton" loading="eager" decoding="async" alt="Hình ảnh phòng trọ"/>
                    @endif
                </div>
                <section id="thumbnail-carousel" class="splide" aria-label="Ảnh phòng trọ được chọn hiển thị">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach($boardingHouse->boarding_house_files as $file)
                            <li class="splide__slide rounded position-relative" aria-hidden="false">
                                <img src="{{ resizeImageCloudinary($file->url, 300, 200) }}"
                                    alt="Thumbnail {{ $boardingHouse->title }}" data-media-type="{{ $file->type }}"
                                    data-src="{{ $file->url }}"
                                    class="skeleton" loading="eager" decoding="async">
                                @if($file->type === 'video')
                                <div class="position-absolute top-0 left-0 h-100 w-100 bg-dark text-white d-flex justify-content-center align-items-center"
                                    style="--bs-bg-opacity:0.6;">
                                    <i class="fa-solid fa-video text-white" arial-label="Video phòng trọ"></i>
                                </div>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            </div>
            <h1 class="fw-bold fs-2 mt-1">{{ $boardingHouse->title }}</h1>
            <p class="text-muted">Đăng bởi <strong>{{ $boardingHouse->user_create->firstname }}</strong> | Cập nhật {{
                dateForHumman($boardingHouse->updated_at) }}</p>
            <h2 class="fw-bold fs-3 text-success d-flex align-items-center" style="gap:5px;">
                {{ numberFormatVi($boardingHouse->price) }}
                <span class="text-dark fs-6">/tháng</span>
                <span
                    class="{{ $boardingHouse->status == 'available' ? 'bg-success text-white' : 'bg-warning text-dark' }} py-1 px-2 rounded-pill fs-6"
                    style="max-width: fit-content;font-size:.5em;">{{ $statues[$boardingHouse->status] }}</span>
            </h2>
            <div class="mt-3">
                <h4 class="fw-bold">Mô Tả</h4>
                {!! $boardingHouse->content !!}
            </div>
            <div class="mt-3">
                <h4 class="fw-bold">Địa Chỉ</h4>
                <p>{{ "{$boardingHouse->address}, {$boardingHouse->ward}, {$boardingHouse->district}" }}</p>
                <iframe
                    src="https://www.google.com/maps?q={{ urlencode("{$boardingHouse->address}, {$boardingHouse->ward}, {$boardingHouse->district}") }}&output=embed"
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="col-lg-4">
            <div class="contact-card position-sticky" style="top: 100px;">
                <h4 class="fw-bold">Thông Tin Liên Hệ</h4>
                <p>
                    <strong>
                        <i class="fa-solid fa-user fa-fw fa-lg"></i>
                        {{ $boardingHouse->user_create->full_name }}
                    </strong>
                </p>
                <p>Zalo/SMS: <a href="{{ getZaloLink($boardingHouse->phone ?? $boardingHouse->user_create->phone) }}"
                        aria-label="Người liên hệ" target="_blank">{{ $boardingHouse->phone ??
                        $boardingHouse->user_create->phone }}</a></p>
                <p><i class="fa-solid fa-envelope text-warning mr-2"></i> <span>********@*****.com</span></p>
                <div class="contact__divide mx-auto my-2">Hoặc</div>
                <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#createAppointmentModal">Đặt lịch xem phòng</button>
            </div>
        </div>
    </div>

    <!-- Related Rooms -->
    @if($boardingHouseRelation->count() > 0)
    <div class="mt-5">
        <h3 class="fw-bold mb-2">Có Thể Bạn Cũng Quan Tâm</h3>
        <div class="row g-3">
            @foreach($boardingHouseRelation as $relation)
            <a href="{{ route('rentalHome.show', ['id' => $relation->id, 'title' => $relation->slug]) }}"
                class="col-md-3 col-6 text-dark position-relative">
                <div class="related-room">
                    <img src="{{ resizeImageCloudinary($relation->thumbnail, 400, 270) }}" alt="{{ $relation->title }}"
                        class="img-fluid rounded" loading="eager" decoding="async">
                    <h5 class="mt-2 fw-bold fs-5">{{ $relation->title }}</h5>
                    <span
                        class="fs-6 {{ $boardingHouse->status == 'available' ? 'bg-success text-white' : 'bg-warning text-dark' }} p-1 position-absolute top-0 left-0"
                        style="max-width: fit-content;">{{ $statues[$relation->status] }}</span>
                    <p class="fw-bold text-success">
                        {{ getShortPrice($relation->price) }}
                        <span class="text-dark fs-6">/tháng</span>
                    </p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Modal Preview Image  -->
<div id="overlay-preview" class="position-fixed top-0 left-0 w-100 h-100 text-center" style="z-index:9998;background-color: rgba(0,0,0,0.5);overflow: hidden;">
    <div class="preview position-absolute top-0 left-0 w-100 h-100 text-center"></div>
    <button class="btn btn-light position-absolute btn__close" style="right:5px; top:5px; z-index:9999;">
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
{{-- Flatpickr --}}
<script src="{{ asset('/vendor/flatpickr/js/flatpickr.min.js') }}" async></script>
<script src="{{ asset('/vendor/flatpickr/js/vn.js') }}" async></script>

<script src="{{ asset('/vendor/toastify/js/toastify.min.js') }}" async></script>
<script src="{{ asset('assets/js/helper/global_helper.js') }}" async></script>
<script src="{{ asset('assets/js/helper/ApiHelper.js') }}" async></script>
<script src="{{ asset('/assets/js/core/splide.min.js') }}" async></script>
<script src="{{ asset('assets/js/apps/rental/detail_rental_script.js') }}?v=1.2" async></script>
@endpush
@push('seo')
<meta name="description" content="{{ $boardingHouse->description }}">
<meta name="keywords" content="{{ $boardingHouse->tags }}">
<meta name="author" content="{{ $boardingHouse->user_create->firstname }}">
<meta property="og:title" content="{{ $boardingHouse->title }}">
<meta property="og:description" content="{{ $boardingHouse->description }}">
<meta property="og:image" content="{{ resizeImageCloudinary($boardingHouse->thumbnail, 1200, 600) }}">
<meta property="og:url"
    content="{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}">
<meta property="og:type" content="article">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $boardingHouse->title }}">
<meta name="twitter:description" content="{{ $boardingHouse->description }}">
<meta name="twitter:image" content="{{ resizeImageCloudinary($boardingHouse->thumbnail, 1200, 600) }}">
<link rel="canonical"
    href="{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}">
@endpush
@push('jsonLD-sm')

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
        "@type": "Property",
        "name": "{{ $boardingHouse->title }}",
        "description": "{{ $boardingHouse->description }}",
        "image": "{{ resizeImageCloudinary($boardingHouse->thumbnail, 1200, 600) }}",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ $boardingHouse->address }}",
            "addressLocality": "{{ $boardingHouse->district }}",
            "addressRegion": "Hồ Chí Minh",
            "postalCode": "700000",
            "addressCountry": "VN"
        },
        "offers": {
            "@type": "Offer",
            "price": "{{ $boardingHouse->price }}",
            "priceCurrency": "VND",
            "availability": "https://schema.org/InStock",
            "validFrom": "{{ \Carbon\Carbon::parse($boardingHouse->created_at)->format('Y-m-d\TH:i') }}"
        },
        "url": "{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}"
    }
</script>
@endpush