@extends('master')
@section('title', 'Danh sách cho thuê')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/apps/rental-home/style.css') }}"/>
@endpush
@section('content')
<div class="container d-flex justify-content-center my-4 flex-md-row flex-column">
    <a id="btn-open-filter-sidebar" class="btn btn-outline-success mb-2 align-self-end mx-3 d-md-none" data-bs-toggle="offcanvas" href="#filter-sidebar" role="button"
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
                    <div class="card rounded mb-3 d-flex flex-md-nowrap flex-md-row overflow-hidden">
                        <img class="item-img skeleton" src="{{ resizeImageCloudinary($boardingHouse->thumbnail, 400, 350) }}" alt="Phòng Trọ" loading="lazy">
                        <div class="item-info flex-grow-1 p-2">
                            <h3 class="__title text-lg fw-bold fs-5">{{ $boardingHouse->title }}</h3>
                            <h5 class="text-sm fs-6">
                                <i class="fa-solid fa-clock" style="color:#b0b0b0"></i>
                                <span>{{ dateForHumman($boardingHouse->created_at) }}</span>
                            </h5>
                            <h5 class="text-sm mb-0 fs-6">
                                <i class="fa-solid fa-location-dot text-danger"></i>
                                <span>{{ $boardingHouse->district }}</span>
                            </h5>
                            <h3 class="text-success text-md fw-bold fs-4 mt-2">
                                {{ numberFormatVi($boardingHouse->price) }}
                                <sup><u>đ</u></sup>
                            </h3>
                        </div>
                    </div>
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
    <meta name="keywords" content="thuê phòng trọ, thuê nhà nguyên căn, thuê căn hộ, tìm kiếm chỗ ở, phòng trọ giá rẻ, nhà cho thuê, nhatrototsaigon">
    <meta name="author" content="Nhatrototsaigon Team">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Trang Chủ Thuê Phòng Hiện Đại - {{ config('app.name') }}ễ">
    <meta property="og:description" content="Khám phá hàng ngàn phòng trọ và nhà cho thuê dễ dàng. Tìm chỗ ở hoàn hảo gần bạn.">
    <meta property="og:image" content="https://via.placeholder.com/1200x630">
    <meta property="og:url" content="{{ asset('') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Trang Chủ Thuê Phòng Hiện Đại - {{ config('app.name') }}">
    <meta name="twitter:description" content="Khám phá hàng ngàn phòng trọ và nhà cho thuê dễ dàng. Tìm chỗ ở hoàn hảo gần bạn.">
    <meta name="twitter:image" content="https://via.placeholder.com/1200x630">
    <link rel="canonical" href="{{ asset('') }}">
@endpush