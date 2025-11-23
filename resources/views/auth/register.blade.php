@extends('master')
@section('title', 'Đăng Ký - Nhà Trọ Tốt Sài Gòn')

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
        min-height: 600px;
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

    .password-strength {
        height: 4px;
        margin-top: 0.5rem;
        background: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
    }

    .password-strength-bar {
        height: 100%;
        transition: all 0.3s ease;
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
                        <h2 class="mb-4">Bắt đầu hành trình tìm nhà!</h2>
                        <p class="mb-4">
                            Đăng ký tài khoản để:
                        </p>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="fa-solid fa-check-circle me-2"></i>
                                Lưu các tin đăng yêu thích
                            </li>
                            <li class="mb-3">
                                <i class="fa-solid fa-check-circle me-2"></i>
                                Đặt lịch xem phòng trực tuyến
                            </li>
                            <li class="mb-3">
                                <i class="fa-solid fa-check-circle me-2"></i>
                                Nhận thông báo tin mới phù hợp
                            </li>
                            <li class="mb-3">
                                <i class="fa-solid fa-check-circle me-2"></i>
                                Liên hệ chủ nhà nhanh chóng
                            </li>
                            <li class="mb-3">
                                <i class="fa-solid fa-check-circle me-2"></i>
                                Quản lý thông tin cá nhân
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Right Side - Register Form --}}
                <div class="col-lg-7 auth-right">
                    <div class="mb-4">
                        <h3 class="mb-2">Đăng Ký Tài Khoản</h3>
                        <p class="text-muted">Tạo tài khoản miễn phí để bắt đầu</p>
                    </div>

                    {{-- Error Messages --}}
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-exclamation-circle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('register.perform') }}" id="registerForm">
                        @csrf

                        <div class="row">
                            {{-- First Name --}}
                            <div class="col-md-6 mb-3">
                                <label for="firstname" class="form-label">Tên <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('firstname') is-invalid @enderror" 
                                       id="firstname" 
                                       name="firstname" 
                                       value="{{ old('firstname') }}" 
                                       placeholder="Văn A"
                                       required>
                                @error('firstname')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Last Name --}}
                            <div class="col-md-6 mb-3">
                                <label for="lastname" class="form-label">Họ <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('lastname') is-invalid @enderror" 
                                       id="lastname" 
                                       name="lastname" 
                                       value="{{ old('lastname') }}" 
                                       placeholder="Nguyễn"
                                       required>
                                @error('lastname')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="example@email.com"
                                   required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   placeholder="0912345678"
                                   pattern="[0-9]{10,11}"
                                   required>
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Số điện thoại để liên hệ</small>
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="••••••••"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Tối thiểu 6 ký tự</small>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="••••••••"
                                   required>
                        </div>

                        {{-- Terms and Conditions --}}
                        <div class="form-check mb-3">
                            <input class="form-check-input @error('terms') is-invalid @enderror" 
                                   type="checkbox" 
                                   name="terms" 
                                   id="terms"
                                   required>
                            <label class="form-check-label" for="terms">
                                Tôi đồng ý với 
                                <a href="{{ route('privacy.index') }}" target="_blank" class="text-primary">Điều khoản sử dụng</a> 
                                và 
                                <a href="{{ route('privacy.index') }}" target="_blank" class="text-primary">Chính sách bảo mật</a>
                            </label>
                            @error('terms')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="btn btn-primary btn-auth btn-lg w-100 mb-3">
                            <i class="fa-solid fa-user-plus me-2"></i>
                            Đăng Ký
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
                            Đăng ký với Google
                        </a>

                        {{-- Login Link --}}
                        <div class="text-center">
                            <p class="mb-0">
                                Đã có tài khoản? 
                                <a href="{{ route('login') }}" class="text-primary fw-bold">Đăng nhập ngay</a>
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

@push('js')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword')?.addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // Phone number formatting
    document.getElementById('phone')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = value;
    });
</script>
@endpush

@push('seo')
<meta name="robots" content="noindex, follow">
<meta name="description" content="Đăng ký tài khoản miễn phí tại Nhà Trọ Tốt Sài Gòn để lưu tin yêu thích và đặt lịch xem phòng">
@endpush
