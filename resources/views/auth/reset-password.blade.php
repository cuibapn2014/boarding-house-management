@extends('layouts.app')
@section('title', 'Quên mật khẩu')

@section('content')
<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">
            {{-- @include('layouts.navbars.guest.navbar') --}}
        </div>
    </div>
</div>
<main class="main-content mt-0">
    <section>
        <div class="page-header min-vh-100">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                        <div class="card card-plain">
                            <div class="card-header pb-0 text-start">
                                <h4 class="font-weight-bolder">Quên mật khẩu?</h4>
                                <p class="mb-0">Nhập email của bạn để nhận liên kết đặt lại mật khẩu</p>
                            </div>
                            <div class="card-body">
                                {{-- Success Message --}}
                                @if (session('succes'))
                                <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
                                    <span class="alert-icon"><i class="ni ni-check-bold"></i></span>
                                    <span class="alert-text">{{ session('succes') }}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif

                                {{-- Error Message --}}
                                @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
                                    <span class="alert-icon"><i class="ni ni-fat-remove"></i></span>
                                    <span class="alert-text">{{ session('error') }}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif

                                <form role="form" method="POST" action="{{ route('reset.perform') }}" id="resetPasswordForm">
                                    @csrf
                                    @method('post')
                                    <div class="mb-3">
                                        <label for="email" class="form-label text-dark">Email</label>
                                        <input type="email" 
                                            name="email" 
                                            id="email"
                                            class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                            placeholder="email@example.com" 
                                            value="{{ old('email') }}" 
                                            aria-label="Email"
                                            required
                                            autofocus>
                                        @error('email') 
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-lg bg-gradient-primary w-100 mt-4 mb-0" id="resetBtn">
                                            <span class="btn-text">Gửi liên kết đặt lại</span>
                                            <span class="spinner-border spinner-border-sm d-none ms-2" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <p class="mb-2 text-sm mx-auto">
                                    Nhớ mật khẩu? 
                                    <a href="{{ route('login') }}" class="text-primary font-weight-bold">Đăng nhập</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                        <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                            style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signin-ill.jpg');
                            background-size: cover;">
                            <span class="mask bg-gradient-primary opacity-6"></span>
                            <h4 class="mt-5 text-white font-weight-bolder position-relative">"Bảo mật tài khoản"</h4>
                            <p class="text-white position-relative">Chúng tôi sẽ gửi liên kết đặt lại mật khẩu đến email của bạn một cách an toàn.</p>
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

    .alert-icon {
        font-size: 1.2rem;
        margin-right: 0.5rem;
    }
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resetForm = document.getElementById('resetPasswordForm');
        const resetBtn = document.getElementById('resetBtn');

        if (resetForm) {
            resetForm.addEventListener('submit', function() {
                resetBtn.disabled = true;
                resetBtn.querySelector('.btn-text').textContent = 'Đang gửi...';
                resetBtn.querySelector('.spinner-border').classList.remove('d-none');
            });
        }

        // Auto dismiss alerts after 10 seconds
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
        }, 10000);
    });
</script>
@endpush
@endsection
