@extends('layouts.app')
@section('title', 'Đăng ký')

@section('content')
    {{-- @include('layouts.navbars.guest.navbar') --}}
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
            style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signup-cover.jpg'); background-position: top;">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 text-center mx-auto">
                        <h1 class="text-white mb-2 mt-5">Chào mừng!</h1>
                        <p class="text-lead text-white">Tạo tài khoản miễn phí để sử dụng hệ thống quản lý nhà trọ hiện đại và chuyên nghiệp.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-5 col-lg-6 col-md-8 mx-auto">
                    <div class="card z-index-0">
                        <div class="card-header text-center pt-4 pb-1">
                            <h4 class="font-weight-bolder">Đăng ký tài khoản</h4>
                            <p class="mb-0 text-sm">Tạo tài khoản mới để bắt đầu</p>
                        </div>
                        <div class="card-body px-lg-5 pt-0">
                            {{-- Error Summary --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
                                    <span class="alert-text">
                                        @if ($errors->count() == 1)
                                            <strong>{{ $errors->first() }}</strong>
                                        @else
                                            <strong>Vui lòng kiểm tra lại:</strong>
                                            <ul class="mb-0 mt-1 ps-3">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register.perform') }}" id="registerForm" class="text-start">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="username" class="form-label text-dark">Tên đăng nhập <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="username" 
                                           id="username"
                                           class="form-control form-control-lg @error('username') is-invalid @enderror" 
                                           placeholder="Nhập tên đăng nhập" 
                                           value="{{ old('username') }}"
                                           required
                                           autofocus
                                           minlength="2">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <small class="form-text text-muted">Tên đăng nhập không chứa khoảng trắng</small>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label text-dark">Email <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           name="email" 
                                           id="email"
                                           class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                           placeholder="email@example.com" 
                                           value="{{ old('email') }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label text-dark">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="phone" 
                                           id="phone"
                                           class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                           placeholder="0246256256" 
                                           value="{{ old('phone') }}"
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $phone }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label text-dark">Mật khẩu <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <input type="password" 
                                               name="password" 
                                               id="password"
                                               class="form-control @error('password') is-invalid @enderror" 
                                               placeholder="Nhập mật khẩu" 
                                               required
                                               minlength="6">
                                        <!-- <button class="btn btn-outline-secondary border" type="button" id="togglePassword" tabindex="-1">
                                            <i class="fa fa-eye" id="eyeIcon"></i>
                                        </button> -->
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Mật khẩu phải có ít nhất 6 ký tự</small>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label text-dark">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <input type="password" 
                                               name="password_confirmation" 
                                               id="password_confirmation"
                                               class="form-control" 
                                               placeholder="Nhập lại mật khẩu" 
                                               required
                                               minlength="6">
                                        <!-- <button class="btn btn-outline-secondary border" type="button" id="togglePasswordConfirm" tabindex="-1">
                                            <i class="fa fa-eye" id="eyeIconConfirm"></i>
                                        </button> -->
                                    </div>
                                    <div id="passwordMatchFeedback" class="d-none">
                                        <small class="text-danger">Mật khẩu không khớp</small>
                                    </div>
                                </div>

                                <div class="form-check form-check-info text-start ps-0 mb-4">
                                    <input class="form-check-input @error('terms') is-invalid @enderror" 
                                           type="checkbox" 
                                           name="terms" 
                                           id="flexCheckDefault">
                                    <label class="form-check-label text-dark font-weight-normal" for="flexCheckDefault">
                                        Tôi đồng ý với <a href="javascript:;" class="text-dark font-weight-bolder">Điều khoản sử dụng</a>
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-lg bg-gradient-primary w-100 mb-3" id="registerBtn">
                                        <span class="btn-text">Đăng ký</span>
                                        <span class="spinner-border spinner-border-sm d-none ms-2" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                                
                                <p class="text-sm text-center mt-3 mb-0">
                                    Đã có tài khoản? 
                                    <a href="{{ route('login') }}" class="text-primary font-weight-bold">Đăng nhập ngay</a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('layouts.footers.guest.footer')

    @push('css')
    <style>
        .input-group .btn-outline-secondary {
            border-color: #d2d6da;
        }
        .input-group .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            border-color: #d2d6da;
            color: #344767;
        }
        .input-group .form-control:focus + .btn-outline-secondary {
            border-color: #e91e63;
        }
        .form-control:focus {
            border-color: #e91e63;
            box-shadow: 0 0 0 2px rgba(233, 30, 99, 0.25);
        }
        .is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.25);
        }
        .card {
            box-shadow: 0 20px 27px 0 rgba(0, 0, 0, 0.05);
        }
    </style>
    @endpush

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const registerForm = document.getElementById('registerForm');
            const registerBtn = document.getElementById('registerBtn');
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
            const passwordConfirm = document.getElementById('password_confirmation');
            const eyeIconConfirm = document.getElementById('eyeIconConfirm');
            const username = document.getElementById('username');
            const passwordMatchFeedback = document.getElementById('passwordMatchFeedback');
            
            // Form submission handling
            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    // Check password match before submit
                    if (password.value !== passwordConfirm.value) {
                        e.preventDefault();
                        passwordConfirm.focus();
                        passwordMatchFeedback.classList.remove('d-none');
                        return false;
                    }
                    
                    registerBtn.disabled = true;
                    registerBtn.querySelector('.btn-text').textContent = 'Đang xử lý...';
                    registerBtn.querySelector('.spinner-border').classList.remove('d-none');
                });
            }

            // Toggle password visibility
            if (togglePassword && password && eyeIcon) {
                togglePassword.addEventListener('click', function(e) {
                    e.preventDefault();
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    eyeIcon.classList.toggle('fa-eye');
                    eyeIcon.classList.toggle('fa-eye-slash');
                });
            }

            // Toggle password confirmation visibility
            if (togglePasswordConfirm && passwordConfirm && eyeIconConfirm) {
                togglePasswordConfirm.addEventListener('click', function(e) {
                    e.preventDefault();
                    const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordConfirm.setAttribute('type', type);
                    eyeIconConfirm.classList.toggle('fa-eye');
                    eyeIconConfirm.classList.toggle('fa-eye-slash');
                });
            }

            // Password match validation with visual feedback
            if (password && passwordConfirm && passwordMatchFeedback) {
                function checkPasswordMatch() {
                    if (passwordConfirm.value.length > 0) {
                        if (password.value !== passwordConfirm.value) {
                            passwordConfirm.classList.add('is-invalid');
                            passwordMatchFeedback.classList.remove('d-none');
                            passwordConfirm.setCustomValidity('Mật khẩu không khớp');
                        } else {
                            passwordConfirm.classList.remove('is-invalid');
                            passwordMatchFeedback.classList.add('d-none');
                            passwordConfirm.setCustomValidity('');
                        }
                    }
                }
                
                passwordConfirm.addEventListener('input', checkPasswordMatch);
                password.addEventListener('input', function() {
                    if (passwordConfirm.value.length > 0) {
                        checkPasswordMatch();
                    }
                });
            }

            // Username validation (no spaces, lowercase)
            if (username) {
                username.addEventListener('input', function() {
                    // Remove spaces and convert to lowercase
                    this.value = this.value.replace(/\s/g, '').toLowerCase();
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
