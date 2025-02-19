@extends('master')
@section('title', 'Nhà Trọ Tốt Sài Gòn: Cho thuê phòng trọ, nhà trọ giá tốt ở khu vực Hồ Chí Minh')
@push('css')
    <link rel="preload" href="{{ asset('assets/images/hero-background.webp') }}" as="image"/>
    <link rel="stylesheet" href="{{ asset('assets/css/apps/home/style.css') }}"/>
@endpush
@php
    $categories = \App\Constants\SystemDefination::BOARDING_HOUSE_CATEGORY;
@endphp
@section('content')
@include('components.hero')
<section class="categories">
    <div class="container">
        <h2 class="fw-bold">Danh Mục Nổi Bật</h2>
        <div class="d-flex flex-nowrap justify-content-md-center" id="room-list" style="overflow-x: auto;gap: 0px 5px;">
            <a href="{{ route('rentalHome.index', ['category' => ['Phòng']]) }}" class="card pointer text-dark" style="min-width: 20rem">
                <img class="skeleton" src="{{ asset('assets/images/room.webp') }}" alt="Phòng Trọ" loading="lazy" decoding="async">
                <h3 class="fw-bold">Phòng Trọ Giá Rẻ</h3>
            </a>
            <a href="{{ route('rentalHome.index', ['category' => ['KTX', 'SLEEPBOX']]) }}" class="card pointer text-dark" style="min-width: 20rem">
                <img class="skeleton" src="{{ asset('assets/images/sleepbox.webp') }}" alt="KTX/Sleepbox" loading="lazy" decoding="async">
                <h3 class="fw-bold">KTX/Sleepbox Sang Trọng</h3>
            </a>
            <a href="{{ route('rentalHome.index', ['category' => ['Nhà nguyên căn']]) }}" class="card pointer text-dark" style="min-width: 20rem">
                <img class="skeleton" src="{{ asset('assets/images/house.webp') }}" alt="Căn Hộ" loading="lazy" decoding="async">
                <h3 class="fw-bold">Căn Hộ Hiện Đại</h3>
            </a>
        </div>
    </div>
</section>
<section class="list-home">
    <div class="container px-md-2 px-0">
        <h2 class="text-center fw-bold">Gần đây nhất</h2>
        <div class="d-flex flex-wrap my-3" id="room-list">
            @foreach($latestPosts as $boardingHouse)
            <a href="{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}" class="flex-grow-1 col-md-12 col-6 px-1">
                <div class="card rounded my-2 d-flex flex-md-nowrap flex-md-row overflow-hidden pointer text-dark">
                    <img class="item-img skeleton" src="{{ resizeImageCloudinary($boardingHouse->thumbnail, 400, 350) }}" alt="{{ $boardingHouse->category }}" loading="lazy" decoding="async"/>
                    <div class="item-info flex-grow-1 p-2">
                        <h3 class="__title text-lg fw-bold fs-5">{{ $boardingHouse->title }}</h3>
                        <h4 class="text-success text-md fw-bold fs-4 mt-2">
                            {{ getShortPrice($boardingHouse->price) }}/tháng
                            
                        </h4>
                        <h5 class="text-sm fs-6">
                            <i class="fa-solid fa-location-dot text-danger"></i>
                            <span>{{ $boardingHouse->district }}</span>
                        </h5>
                        <h5 class="text-sm fs-6 mb-0">
                            <i class="fa-solid fa-clock" style="color:#b0b0b0"></i>
                            <span>{{ dateForHumman($boardingHouse->created_at) }}</span>
                        </h5>
                    </div>
                    <div class="bg-success position-absolute top-0 left-0 fs-6 text-white px-1">{{ $categories[$boardingHouse->category] }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
@push('seo')
    <meta name="title" content="Tìm kiếm và thuê phòng trọ, nhà nguyên căn, căn hộ hiện đại dễ dàng với Nhatrototsaigon"/>
    <meta name="description" content="Tìm kiếm và thuê phòng trọ, nhà nguyên căn, căn hộ hiện đại dễ dàng với Nhatrototsaigon. Khám phá hàng ngàn chỗ ở hoàn hảo cho bạn.">
    <meta name="keywords" content="thuê phòng trọ, thuê nhà nguyên căn, thuê căn hộ, tìm kiếm chỗ ở, phòng trọ giá rẻ, nhà cho thuê, nhatrototsaigon, nhà trọ tốt sài gòn">
    <meta name="author" content="Nhatrototsaigon Team">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Nhà Trọ Tốt Sài Gòn: Cho thuê phòng trọ, nhà trọ giá tốt ở khu vực Hồ Chí Minh - {{ config('app.name') }}">
    <meta property="og:description" content="Khám phá hàng ngàn phòng trọ và nhà cho thuê dễ dàng. Tìm chỗ ở hoàn hảo gần bạn.">
    <meta property="og:image" content="{{ asset('assets/images/hero-background.webp') }}">
    <meta property="og:url" content="{{ route('home.index') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Nhà Trọ Tốt Sài Gòn: Cho thuê phòng trọ, nhà trọ giá tốt ở khu vực Hồ Chí Minh - {{ config('app.name') }}">
    <meta name="twitter:description" content="Khám phá hàng ngàn phòng trọ và nhà cho thuê dễ dàng. Tìm chỗ ở hoàn hảo gần bạn.">
    <meta name="twitter:image" content="{{ asset('assets/images/hero-background.webp') }}">
    <link rel="canonical" href="{{ route('home.index') }}">
@endpush
@push('jsonLD-lg')
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ItemList",
        "itemListElement": [
            @foreach($latestPosts as $index => $boardingHouse)
            {
                "@type": "ListItem",
                "position": {{ $index + 1 }},
                "item": {
                    "@type": "Property",
                    "name": "{{ $boardingHouse->title }}",
                    "description": "{{ $boardingHouse->description }}",
                    "url": "{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}",
                    "image": "{{ $boardingHouse->thumbnail }}",
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
                        "availability": "https://schema.org/InStock"
                    }
                }
            }@if (!$loop->last),@endif
            @endforeach
        ]
    }
</script>
@endpush