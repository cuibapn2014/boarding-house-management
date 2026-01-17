@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Hồ sơ cá nhân')
@push('css')
<style>
    /* Profile Header */
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 40px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .profile-header>* {
        position: relative;
        z-index: 1;
    }

    /* Avatar Wrapper */
    .avatar-wrapper {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 5px solid white;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        position: relative;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .avatar-wrapper:hover {
        transform: scale(1.08);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
    }

    .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-upload-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        backdrop-filter: blur(4px);
    }

    .avatar-wrapper:hover .avatar-upload-overlay {
        opacity: 1;
    }

    .avatar-upload-overlay i {
        color: white;
        font-size: 32px;
    }

    /* Form Card */
    .form-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        background: #ffffff;
        transition: all 0.3s ease;
    }

    .form-card:hover {
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    /* Section Title */
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
    }

    .section-title i {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 16px;
        margin-right: 12px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Form Label */
    .form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .form-label span.text-danger {
        color: #ef4444;
    }

    /* Form Control */
    .form-control {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    /* Input Group */
    .input-group-text {
        background: white;
        border: 1.5px solid #e2e8f0;
        border-left: none;
        border-radius: 0 12px 12px 0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .input-group-text:hover {
        background: #f8f9ff;
        color: #667eea;
    }

    .input-group .form-control {
        border-right: none;
        border-radius: 12px 0 0 12px;
    }

    .input-group .form-control:focus {
        border-right: 1.5px solid #667eea;
    }

    /* Submit Button */
    .submit-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 14px;
        padding: 14px 32px;
        color: white;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        font-size: 15px;
    }

    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        color: white;
    }

    .submit-btn:active {
        transform: translateY(-1px);
    }

    /* Outline Buttons */
    .btn-outline-secondary,
    .btn-outline-warning {
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
        border-width: 1.5px;
    }

    .btn-outline-secondary:hover {
        background: #f8f9ff;
        border-color: #667eea;
        color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-outline-warning:hover {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
    }

    /* Avatar Upload Button */
    .btn-outline-primary {
        border-radius: 10px;
        padding: 8px 16px;
        font-weight: 600;
        border-width: 1.5px;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Stats Card */
    .stats-card {
        text-align: center;
        padding: 24px;
        border-radius: 16px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        border-color: #667eea;
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
    }

    .stats-number {
        font-size: 32px;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 8px;
    }

    .stats-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Alert Box */
    .alert-light {
        background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%);
        border: 1px solid rgba(102, 126, 234, 0.2);
        border-radius: 12px;
        padding: 16px;
    }

    .alert-light h6 {
        color: #1e293b;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .alert-light p {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 6px;
    }

    /* Profile Info */
    .profile-info-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
        color: rgba(255, 255, 255, 0.9);
    }

    .profile-info-item i {
        width: 20px;
        opacity: 0.8;
    }

    /* Status Badge */
    .status-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 20px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        color: white;
    }

    /* Avatar Preview Form */
    #avatar-preview-form {
        border: 3px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    #avatar-preview-form:hover {
        border-color: #667eea;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    }

    /* Responsive */
    @media (max-width: 991px) {
        .profile-header {
            padding: 30px 20px;
        }

        .avatar-wrapper {
            width: 100px;
            height: 100px;
        }

        .form-card {
            margin-bottom: 20px;
        }
    }

    @media (max-width: 767px) {
        .profile-header {
            padding: 24px 16px;
        }

        .avatar-wrapper {
            width: 80px;
            height: 80px;
            border-width: 3px;
        }

        .section-title {
            font-size: 16px;
        }

        .section-title i {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }
    }

    /* Smooth Scroll */
    html {
        scroll-behavior: smooth;
    }

    /* Error Messages */
    .text-danger {
        color: #ef4444 !important;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }

    /* Text Muted */
    .text-muted {
        color: #94a3b8 !important;
        font-size: 12px;
    }
</style>
@endpush
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Hồ sơ cá nhân'])

<div class="container-fluid py-4">
    @include('components.alert')

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="avatar-wrapper" onclick="document.getElementById('avatar-input').click()">
                    <img src="{{ auth()->user()->avatar ?? '/img/team-1.jpg' }}" alt="profile_image" id="avatar-preview">
                    <div class="avatar-upload-overlay">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <h3 class="mb-2 text-white font-weight-bold" style="font-size: 28px;">
                    {{ auth()->user()->firstname ?? 'Firstname' }} {{ auth()->user()->lastname ?? 'Lastname' }}
                </h3>
                <div class="profile-info-item">
                    <i class="fas fa-user-shield"></i>
                    <span>{{ auth()->user()->is_admin ? 'Administrator' : 'User' }}</span>
                </div>
                <div class="profile-info-item">
                    <i class="fas fa-envelope"></i>
                    <span>{{ auth()->user()->email }}</span>
                </div>
                @if(auth()->user()->phone)
                <div class="profile-info-item">
                    <i class="fas fa-phone"></i>
                    <span>{{ auth()->user()->phone }}</span>
                </div>
                @endif
            </div>
            <div class="col-auto text-end">
                <div class="status-badge">
                    <i class="fas fa-check-circle me-1"></i>Active
                </div>
                @if(auth()->user()->plan_current == 'premium')
                <div class="status-badge mt-2" style="background: rgba(251, 191, 36, 0.3); border-color: rgba(251, 191, 36, 0.5);">
                    <i class="fas fa-star me-1"></i>Premium
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    {{-- <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stats-card">
                <div class="stats-number">12</div>
                <div class="stats-label">Nhà trọ đang quản lý</div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stats-card">
                <div class="stats-number">48</div>
                <div class="stats-label">Khách thuê hiện tại</div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stats-card">
                <div class="stats-number">98%</div>
                <div class="stats-label">Tỷ lệ lấp đầy</div>
            </div>
        </div>
    </div> --}}

    <!-- Profile Form -->
    <form role="form" method="POST" action={{ route('profile.update') }} enctype="multipart/form-data">
        @csrf
        <input type="file" id="avatar-input" name="avatar" accept="image/*" style="display: none;">
        <div class="row">
            <!-- Personal Information -->
            <div class="col-lg-8 mb-4">
                <div class="card form-card">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="fas fa-user"></i>
                            Thông tin cá nhân
                        </h5>

                        <div class="mb-4">
                            <label class="form-label">Ảnh đại diện</label>
                            <div class="d-flex align-items-center gap-4">
                                <img src="{{ auth()->user()->avatar ?? '/img/team-1.jpg' }}"
                                    alt="avatar"
                                    id="avatar-preview-form"
                                    style="width: 100px; height: 100px; object-fit: cover; border-radius: 16px; border: 3px solid #e2e8f0; cursor: pointer;"
                                    onclick="document.getElementById('avatar-input').click()">
                                <div class="flex-grow-1">
                                    <button type="button" class="btn btn-outline-primary mb-2" onclick="document.getElementById('avatar-input').click()">
                                        <i class="fas fa-upload me-2"></i>Tải ảnh lên
                                    </button>
                                    <p class="text-muted mb-0" style="font-size: 13px;">
                                        <i class="fas fa-info-circle me-1"></i>JPG, PNG hoặc WEBP (tối đa 2MB)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="username"
                                    value="{{ old('username', auth()->user()->username) }}"
                                    placeholder="Nhập tên đăng nhập">
                                @error('username')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input class="form-control" type="email" name="email"
                                    value="{{ old('email', auth()->user()->email) }}"
                                    placeholder="example@email.com">
                                @error('email')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ</label>
                                <input class="form-control" type="text" name="firstname"
                                    value="{{ old('firstname', auth()->user()->firstname) }}"
                                    placeholder="Nhập họ">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tên</label>
                                <input class="form-control" type="text" name="lastname"
                                    value="{{ old('lastname', auth()->user()->lastname) }}"
                                    placeholder="Nhập tên">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Số điện thoại/Zalo</label>
                                <input class="form-control" type="text" name="phone"
                                    value="{{ old('phone', auth()->user()->phone) }}"
                                    placeholder="Nhập số điện thoại">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card form-card mt-4">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="fas fa-map-marker-alt"></i>
                            Thông tin liên hệ
                        </h5>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <input class="form-control" type="text" name="address"
                                    value="{{ old('address', auth()->user()->address) }}"
                                    placeholder="Số nhà, tên đường...">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Thành phố</label>
                                <input class="form-control" type="text" name="city"
                                    value="{{ old('city', auth()->user()->city) }}"
                                    placeholder="Thành phố">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quốc gia</label>
                                <input class="form-control" type="text" name="country"
                                    value="{{ old('country', auth()->user()->country) }}"
                                    placeholder="Quốc gia">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Mã bưu điện</label>
                                <input class="form-control" type="text" name="postal"
                                    value="{{ old('postal', auth()->user()->postal) }}"
                                    placeholder="Mã bưu điện">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- About Me -->
                <div class="card form-card mt-4">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Giới thiệu
                        </h5>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Về tôi</label>
                                <textarea class="form-control" name="about" rows="4"
                                    placeholder="Viết vài dòng giới thiệu về bản thân...">{{ old('about', auth()->user()->about) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="col-lg-4 mb-4">
                <div class="card form-card">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="fas fa-cog"></i>
                            Hành động
                        </h5>

                        <button type="submit" class="btn submit-btn w-100 mb-3">
                            <i class="fas fa-save me-2"></i>Lưu thay đổi
                        </button>

                        <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 mb-3">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại Dashboard
                        </a>

                        <hr style="border-color: #e2e8f0; margin: 20px 0;">

                        <h6 class="font-weight-bold mb-3" style="color: #1e293b; font-size: 15px;">
                            <i class="fas fa-shield-alt me-2" style="color: #667eea;"></i>Bảo mật
                        </h6>
                        <a href="#change-password-section" class="btn btn-outline-warning w-100 mb-3" onclick="scrollToChangePassword()">
                            <i class="fas fa-key me-2"></i>Đổi mật khẩu
                        </a>

                        <hr style="border-color: #e2e8f0; margin: 20px 0;">

                        <div class="alert alert-light">
                            <h6 class="font-weight-bold mb-3" style="color: #1e293b; font-size: 14px;">
                                <i class="fas fa-info-circle me-2" style="color: #667eea;"></i>Thông tin tài khoản
                            </h6>
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-calendar-plus" style="color: #667eea; width: 18px;"></i>
                                    <span style="font-size: 13px;">
                                        <strong style="color: #475569;">Tham gia:</strong>
                                        <span style="color: #64748b;">{{ date('d/m/Y', strtotime(auth()->user()->created_at)) }}</span>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-sync-alt" style="color: #667eea; width: 18px;"></i>
                                    <span style="font-size: 13px;">
                                        <strong style="color: #475569;">Cập nhật:</strong>
                                        <span style="color: #64748b;">{{ date('d/m/Y', strtotime(auth()->user()->updated_at)) }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Change Password Section (Separate Form) -->
    <div class="row">
        <div class="col-lg-8 mb-4" id="change-password-section">
            <div class="card form-card">
                <div class="card-body p-4">
                    <h5 class="section-title">
                        <i class="fas fa-key"></i>
                        Đổi mật khẩu
                    </h5>

                    <form role="form" method="POST" action="{{ route('profile.change-password') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    <span class="form-label-text">Mật khẩu hiện tại</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group" style="box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); border-radius: 12px; overflow: hidden;">
                                    <input class="form-control" type="password" name="current_password"
                                        id="current_password" placeholder="Nhập mật khẩu hiện tại" required>
                                    <span class="input-group-text" onclick="togglePassword('current_password')" style="cursor: pointer;">
                                        <i class="fas fa-eye" id="toggleCurrentPassword"></i>
                                    </span>
                                </div>
                                @error('current_password')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                                <div class="input-group" style="box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); border-radius: 12px; overflow: hidden;">
                                    <input class="form-control" type="password" name="new_password"
                                        id="new_password" placeholder="Nhập mật khẩu mới" required>
                                    <span class="input-group-text" onclick="togglePassword('new_password')" style="cursor: pointer;">
                                        <i class="fas fa-eye" id="toggleNewPassword"></i>
                                    </span>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fas fa-info-circle me-1"></i>Tối thiểu 8 ký tự
                                </small>
                                @error('new_password')
                                <br><small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                <div class="input-group" style="box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); border-radius: 12px; overflow: hidden;">
                                    <input class="form-control" type="password" name="new_password_confirmation"
                                        id="new_password_confirmation" placeholder="Nhập lại mật khẩu mới" required>
                                    <span class="input-group-text" onclick="togglePassword('new_password_confirmation')" style="cursor: pointer;">
                                        <i class="fas fa-eye" id="toggleConfirmPassword"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn submit-btn">
                                    <i class="fas fa-lock me-2"></i>Đổi mật khẩu
                                </button>
                            </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const avatarInput = document.getElementById('avatar-input');
        const avatarPreview = document.getElementById('avatar-preview');
        const avatarPreviewForm = document.getElementById('avatar-preview-form');

        if (avatarInput) {
            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Kích thước file quá lớn. Vui lòng chọn file nhỏ hơn 2MB.');
                        return;
                    }

                    // Validate file type
                    if (!file.type.match('image.*')) {
                        alert('Vui lòng chọn file ảnh (JPG, PNG, WEBP).');
                        return;
                    }

                    // Preview image
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (avatarPreview) avatarPreview.src = e.target.result;
                        if (avatarPreviewForm) avatarPreviewForm.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });

    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling.querySelector('i');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Scroll to change password section
    function scrollToChangePassword() {
        const section = document.getElementById('change-password-section');
        if (section) {
            section.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }
</script>
@endpush