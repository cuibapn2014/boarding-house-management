@extends('master')
@section('title', 'Liên hệ')
@push('css')
    <link rel="preload" href="{{ asset('assets/images/hero-background.webp') }}" as="image"/>
    <style>
        section.hero .hero-overlay {
            --bs-bg-opacity: 0.35;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
    </style>
@endpush
@section('content')
@include('components.hero')
<div class="container my-5" style="min-height:50vh;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h2 class="card-title mb-4">Liên hệ với chúng tôi</h2>
                    <form action="#" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và Tên</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Nhập họ và tên của bạn" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Nhập email của bạn" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Nội dung</label>
                            <textarea id="message" name="message" class="form-control" placeholder="Nhập nội dung cần liên hệ" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Gửi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection