@extends('layouts.app')
@section('title', 'Đăng nhập')

@section('content')
<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">
            {{-- @include('layouts.navbars.guest.navbar') --}}
        </div>
    </div>
</div>
<main class="main-content  mt-0">
    <section>
        <div class="page-header min-vh-100">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                        <div class="card card-plain">
                            <div class="card-header pb-0 text-start">
                                <h4 class="font-weight-bolder">Đăng nhập</h4>
                                <p class="mb-0">Nhập email và mật khẩu của bạn</p>
                            </div>
                            <div class="card-body">
                                {{-- Success Message --}}
                                @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
                                    <span class="alert-text"><strong>{{ session('success') }}</strong></span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif

                                {{-- Error Message --}}
                                @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
                                <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
                                    <span class="alert-text">
                                        <strong>
                                            @foreach ($errors->all() as $error)
                                            {{ $error }}
                                            @endforeach
                                        </strong>
                                    </span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif

                                <form role="form" method="POST" action="{{ route('login.perform') }}" id="loginForm" class="text-start">
                                    @csrf
                                    @method('post')
                                    <div class="mb-3">
                                        <label for="email" class="form-label text-dark">Email</label>
                                        <input type="email"
                                            name="email"
                                            id="email"
                                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}"
                                            placeholder="email@example.com"
                                            required
                                            autofocus>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label text-dark">Mật khẩu</label>
                                        <input type="password"
                                            name="password"
                                            id="password"
                                            class="form-control form-control-lg @error('password') is-invalid @enderror"
                                            placeholder="Nhập mật khẩu"
                                            value="{{ old('password') }}"
                                            required>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-check form-switch ps-0 mb-3 d-flex">
                                        <input class="form-check-input"
                                            name="remember"
                                            type="checkbox"
                                            id="rememberMe"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark ms-2" for="rememberMe">Ghi nhớ đăng nhập</label>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-lg bg-gradient-primary w-100 mt-2 mb-0" id="loginBtn">
                                            <span class="btn-text">Đăng nhập</span>
                                            <span class="spinner-border spinner-border-sm d-none ms-2" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <p class="mb-2 text-sm mx-auto">
                                    <a href="{{ route('reset-password') }}" class="text-primary font-weight-bold">Quên mật khẩu?</a>
                                </p>
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <p class="mb-4 text-sm mx-auto">
                                    Chưa có tài khoản?
                                    <a href="{{ route('register') }}" class="text-primary font-weight-bold">Đăng ký ngay</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                        <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                            style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signin-ill.jpg');
              background-size: cover;">
                            <span class="mask bg-gradient-primary opacity-6"></span>
                            <h4 class="mt-5 text-white font-weight-bolder position-relative">"Quản lý tin đăng dễ dàng"</h4>
                            <p class="text-white position-relative">Hệ thống quản lý tin đăng hiện đại, tiện lợi và chuyên nghiệp.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@push('css')
<style>
    .form-control:focus {
        border-color: #e91e63;
        box-shadow: 0 0 0 2px rgba(233, 30, 99, 0.25);
    }

    .is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.25);
    }

    .card-plain {
        box-shadow: none;
    }
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');

        if (loginForm) {
            loginForm.addEventListener('submit', function() {
                loginBtn.disabled = true;
                loginBtn.querySelector('.btn-text').textContent = 'Đang xử lý...';
                loginBtn.querySelector('.spinner-border').classList.remove('d-none');
            });
        }

        // Auto dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                } else {
                    alert.style.display = 'none';
                }
            });
        }, 5000);
    });
</script>
@endpush
@endsection