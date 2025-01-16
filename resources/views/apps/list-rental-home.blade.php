@extends('master')
@section('title', 'Danh sách cho thuê - Nhà trọ tốt sài gòn')
@push('css')
    <link rel="preload" href="{{ asset('assets/images/hero-background.webp') }}" as="image"/>
    <link rel="stylesheet" href="{{ asset('assets/css/apps/rental-home/style.css') }}"/>
@endpush
@section('content')
@include('components.hero')
@php
    $categories = \App\Constants\SystemDefination::BOARDING_HOUSE_CATEGORY;
@endphp
<div class="container d-flex justify-content-center my-4 flex-md-row flex-column">
    <a id="btn-open-filter-sidebar" class="btn btn-sm btn-success mb-2 align-self-end mx-3 d-md-none" data-bs-toggle="offcanvas" href="#filter-sidebar" role="button"
        aria-controls="filter-sidebar">
        <i class="fa-solid fa-filter"></i>
        <span>Bộ lọc</span>
    </a>
    <aside class="filter__rental-home rounded shadow-sm border bg-white p-2 d-md-block d-none">
        <h5 class="mb-3">
            <i class="fa-solid fa-filter"></i>
            <span>Bộ Lọc</span>
        </h5>
        @include('components.filter')
    </aside>
    <section class="list-home flex-grow-1">
        <div class="container">
            <div class="grid" id="room-list">
                @forelse($boardingHouses as $boardingHouse)
                    <a href="{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}" class="card rounded mb-3 d-flex flex-md-nowrap flex-md-row overflow-hidden position-relative text-dark">
                        <img class="item-img skeleton" src="{{ resizeImageCloudinary($boardingHouse->thumbnail, 400, 350) }}" alt="{{ $boardingHouse->category }}" loading="lazy" decoding="async"/>
                        <div class="item-info flex-grow-1 p-2">
                            <h3 class="__title text-lg fw-bold fs-5">{{ $boardingHouse->title }}</h3>
                            <h4 class="text-success text-md fw-bold fs-4 mt-2">
                                {{ numberFormatVi($boardingHouse->price) }}
                                <sup><u>đ</u></sup>
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
                    </a>
                @empty
                    <p class="text-center">Không có dữ liệu</p>
                @endforelse
            </div>
            @if($boardingHouses->count() > 0)
                <div class="row" id="pagination">
                    {{ $boardingHouses->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </section>

    {{-- Filter --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="filter-sidebar" aria-labelledby="filterSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="filterSidebarLabel">
                <div class="logo">
                    <i class="fa-solid fa-filter"></i>
                    <span class="text-md">Bộ Lọc</span>
                </div>
            </h5>
            <button type="button" class="btn-close text-white bg-light" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between">
            @include('components.filter')
        </div>
    </div>
</div>
@endsection
@push('seo')
    <meta name="title" content="Tìm kiếm và thuê phòng trọ, nhà nguyên căn, căn hộ hiện đại dễ dàng với Nhatrototsaigon"/>
    <meta name="description" content="Tìm kiếm và thuê phòng trọ, nhà nguyên căn, căn hộ hiện đại dễ dàng với Nhatrototsaigon. Khám phá hàng ngàn chỗ ở hoàn hảo cho bạn.">
    <meta name="keywords" content="thuê phòng trọ, thuê nhà nguyên căn, thuê căn hộ, tìm kiếm chỗ ở, phòng trọ giá rẻ, nhà cho thuê, nhatrototsaigon, nhà trọ giá tốt sài gòn">
    <meta name="author" content="Nhatrototsaigon Team">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Nhà trọ tốt sài gòn: Cho thuê phòng trọ, nhà trọ giá tốt ở khu vực Hồ Chí Minh - {{ config('app.name') }}">
    <meta property="og:description" content="Khám phá hàng ngàn phòng trọ và nhà cho thuê dễ dàng. Tìm chỗ ở hoàn hảo gần bạn.">
    <meta property="og:image" content="{{ asset('assets/images/hero-background.webp') }}">
    <meta property="og:url" content="{{ route('rentalHome.index') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Nhà trọ tốt sài gòn: Cho thuê phòng trọ, nhà trọ giá tốt ở khu vực Hồ Chí Minh - {{ config('app.name') }}">
    <meta name="twitter:description" content="Khám phá hàng ngàn phòng trọ và nhà cho thuê dễ dàng. Tìm chỗ ở hoàn hảo gần bạn.">
    <meta name="twitter:image" content="{{ asset('assets/images/hero-background.webp') }}">
    <link rel="canonical" href="{{ route('rentalHome.index') }}">
@endpush
@push('js')
    <script src="{{ asset('assets/js/helper/ApiHelper.js') }}"></script>
    <script src="{{ asset('assets/js/apps/rental/script.js') }}"></script>
    <script src="{{ asset('assets/js/apps/rental/Rental.js') }}"></script>
@endpush
@push('jsonLD-lg')
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ItemList",
        "itemListElement": [
            @foreach($boardingHouses as $index => $boardingHouse)
            {
                "@type": "ListItem",
                "position": {{ $boardingHouses->firstItem() + $index }},
                "url": "{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}",
                "item": {
                    "@type": "RentalProperty",
                    "name": "{{ $boardingHouse->title }}",
                    "description": "{{ $boardingHouse->description }}",
                    "url": "{{ route('rentalHome.show', ['id' => $boardingHouse->id, 'title' => $boardingHouse->slug]) }}",
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
                    },
                    "image": "{{ $boardingHouse->thumbnail }}"
                }
            }@if (!$loop->last),@endif
            @endforeach
        ]
    }
</script>
@endpush