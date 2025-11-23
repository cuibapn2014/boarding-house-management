@extends('master')
@section('title', 'Đăng Nhập - Nhà Trọ Tốt Sài Gòn')

@push('css')
<style>
    .auth-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .auth-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 1000px;
        width: 100%;
    }

    .auth-left {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 500px;
    }

    .auth-right {
        padding: 3rem;
    }

    .auth-logo {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-auth {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 1.5rem 0;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #dee2e6;
    }

    .divider span {
        padding: 0 1rem;
        color: #6c757d;
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .auth-left {
            display: none;
        }
        
        .auth-right {
            padding: 2rem 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="auth-card">
            <div class="row g-0">
                {{-- Left Side - Branding --}}
                <div class="col-lg-5 auth-left d-none d-lg-flex">
                    <div>
                        <div class="auth-logo">
                            <i class="fa-solid fa-house me-2"></i>
                            Nhatrototsaigon
                        </div>
                        <h2 class="mb-4">Chào mừng trở lại!</h2>
                        <p class="mb-4">
                            Đăng nhập để trải nghiệm đầy đủ các tính năng:
                        </p>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="fa-solid fa-check-circle me-2"></i>
                                Lưu tin yêu thích
                            </li>
                            <li class="mb-3">
                                <i class="fa-solid fa-check-circle me-2"></i>
                                Đặt lịch xem phòng
                            </li>
                            <li class="mb-3">
                                <i class="fa-solid fa-check-circle me-2"></i>
                                Nhận thông báo tin mới
                            </li>
                            <li class="mb-3">
                                <i class="fa-solid fa-check-circle me-2"></i>
                                Quản lý thông tin cá nhân
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Right Side - Login Form --}}
                <div class="col-lg-7 auth-right">
                    <div class="mb-4">
                        <h3 class="mb-2">Đăng Nhập</h3>
                        <p class="text-muted">Đăng nhập để tiếp tục</p>
                    </div>

                    {{-- Success Message --}}
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    {{-- Error Messages --}}
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-exclamation-circle me-2"></i>
                        @foreach($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login.perform') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="example@email.com"
                                   required 
                                   autofocus>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" 
                                   class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="••••••••"
                                   required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Remember Me --}}
                        <div class="form-check mb-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="remember" 
                                   id="remember">
                            <label class="form-check-label" for="remember">
                                Ghi nhớ đăng nhập
                            </label>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="btn btn-primary btn-auth btn-lg w-100 mb-3">
                            <i class="fa-solid fa-sign-in-alt me-2"></i>
                            Đăng Nhập
                        </button>

                        {{-- Divider --}}
                        <div class="divider">
                            <span>hoặc</span>
                        </div>

                        {{-- Google Login Button --}}
                        <a href="{{ route('auth.google') }}" class="btn btn-outline-secondary btn-lg w-100 mb-3 d-flex align-items-center justify-content-center">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                <path d="M19.8055 10.2292C19.8055 9.55056 19.7508 8.86711 19.6359 8.19873H10.2002V12.0492H15.6014C15.3773 13.2911 14.6571 14.3898 13.6025 15.0879V17.5866H16.825C18.7174 15.8449 19.8055 13.2728 19.8055 10.2292Z" fill="#4285F4"/>
                                <path d="M10.2002 20.0006C12.9515 20.0006 15.2664 19.1151 16.8286 17.5865L13.6061 15.0879C12.7096 15.6979 11.5521 16.0433 10.2038 16.0433C7.54338 16.0433 5.29098 14.2832 4.50488 11.9169H1.17773V14.4927C2.77719 17.8304 6.30975 20.0006 10.2002 20.0006Z" fill="#34A853"/>
                                <path d="M4.50137 11.9169C4.08088 10.6749 4.08088 9.32938 4.50137 8.08734V5.51157H1.17783C-0.196116 8.33785 -0.196116 11.6665 1.17783 14.4928L4.50137 11.9169Z" fill="#FBBC04"/>
                                <path d="M10.2002 3.95805C11.6235 3.936 13.0006 4.47247 14.0361 5.45722L16.8905 2.60218C15.1802 0.990984 12.9306 0.0808353 10.2002 0.104384C6.30975 0.104384 2.77719 2.27455 1.17773 5.51214L4.50127 8.0879C5.28376 5.71795 7.53977 3.95805 10.2002 3.95805Z" fill="#EA4335"/>
                            </svg>
                            Đăng nhập với Google
                        </a>

                        {{-- Register Link --}}
                        <div class="text-center">
                            <p class="mb-0">
                                Chưa có tài khoản? 
                                <a href="{{ route('register') }}" class="text-primary fw-bold">Đăng ký ngay</a>
                            </p>
                        </div>

                        {{-- Back to Home --}}
                        <div class="text-center mt-3">
                            <a href="{{ route('home.index') }}" class="text-muted">
                                <i class="fa-solid fa-arrow-left me-1"></i>
                                Quay lại trang chủ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('seo')
<meta name="robots" content="noindex, follow">
<meta name="description" content="Đăng nhập vào Nhà Trọ Tốt Sài Gòn để lưu tin yêu thích và đặt lịch xem phòng">
@endpush
