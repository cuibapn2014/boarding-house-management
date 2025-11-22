@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Hồ sơ cá nhân')
@push('css')
<style>
    .profile-header {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        border-radius: 16px;
        padding: 40px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 8px 24px rgba(76, 175, 80, 0.3);
    }
    .avatar-wrapper {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 5px solid white;
        overflow: hidden;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .avatar-wrapper:hover {
        transform: scale(1.05);
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
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .avatar-wrapper:hover .avatar-upload-overlay {
        opacity: 1;
    }
    .form-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #344767;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
        display: flex;
        align-items: center;
    }
    .section-title i {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        font-size: 14px;
        margin-right: 12px;
    }
    .form-label {
        font-weight: 600;
        color: #344767;
        margin-bottom: 8px;
        font-size: 13px;
    }
    .form-control {
        border-radius: 8px;
        border: 1px solid #d2d6da;
        padding: 10px 15px;
    }
    .form-control:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
    }
    .submit-btn {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        border: none;
        border-radius: 12px;
        padding: 12px 32px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
    }
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.6);
    }
    .stats-card {
        text-align: center;
        padding: 20px;
        border-radius: 12px;
        background: white;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .stats-card:hover {
        border-color: #4CAF50;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.15);
    }
    .stats-number {
        font-size: 28px;
        font-weight: 700;
        color: #4CAF50;
    }
    .stats-label {
        font-size: 13px;
        color: #67748e;
        font-weight: 600;
        margin-top: 5px;
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
                        <i class="fas fa-camera fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <h3 class="mb-1 text-white font-weight-bold">
                    {{ auth()->user()->firstname ?? 'Firstname' }} {{ auth()->user()->lastname ?? 'Lastname' }}
                </h3>
                <p class="mb-0 text-white opacity-8">
                    <i class="fas fa-user-shield me-2"></i>{{ auth()->user()->is_admin ? 'Administrator' : 'User' }}
                </p>
                <p class="mb-0 text-white opacity-8 mt-2">
                    <i class="fas fa-envelope me-2"></i>{{ auth()->user()->email }}
                </p>
            </div>
            <div class="col-auto">
                <span class="badge bg-white text-primary px-3 py-2" style="font-size: 12px;">
                    <i class="fas fa-check-circle me-1"></i>Active
                </span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
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
    </div>

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

                        <div class="mb-3">
                            <label class="form-label">Ảnh đại diện</label>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ auth()->user()->avatar ?? '/img/team-1.jpg' }}" 
                                     alt="avatar" 
                                     id="avatar-preview-form"
                                     style="width: 80px; height: 80px; object-fit: cover; border-radius: 12px; border: 2px solid #e9ecef;">
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mb-2" onclick="document.getElementById('avatar-input').click()">
                                        <i class="fas fa-upload me-1"></i>Tải ảnh lên
                                    </button>
                                    <p class="text-xs text-muted mb-0">JPG, PNG hoặc WEBP (tối đa 2MB)</p>
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

            <!-- Change Password Section -->
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
                                    <label class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                    <div class="input-group">
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
                                    <div class="input-group">
                                        <input class="form-control" type="password" name="new_password" 
                                            id="new_password" placeholder="Nhập mật khẩu mới" required>
                                        <span class="input-group-text" onclick="togglePassword('new_password')" style="cursor: pointer;">
                                            <i class="fas fa-eye" id="toggleNewPassword"></i>
                                        </span>
                                    </div>
                                    <small class="text-muted">Tối thiểu 8 ký tự</small>
                                    @error('new_password')
                                    <br><small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                    <div class="input-group">
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

                        <hr>

                        <h6 class="font-weight-bold text-sm mb-3">Bảo mật</h6>
                        <a href="#change-password-section" class="btn btn-outline-warning w-100 mb-2" onclick="scrollToChangePassword()">
                            <i class="fas fa-key me-2"></i>Đổi mật khẩu
                        </a>

                        <hr>

                        <div class="alert alert-light border">
                            <h6 class="font-weight-bold text-sm mb-2">
                                <i class="fas fa-shield-alt text-success me-2"></i>Thông tin tài khoản
                            </h6>
                            <p class="text-xs text-muted mb-1">
                                <strong>Tham gia:</strong> {{ date('d/m/Y', strtotime(auth()->user()->created_at)) }}
                            </p>
                            <p class="text-xs text-muted mb-0">
                                <strong>Cập nhật:</strong> {{ date('d/m/Y', strtotime(auth()->user()->updated_at)) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatar-input');
    const avatarPreview = document.getElementById('avatar-preview');
    const avatarPreviewForm = document.getElementById('avatar-preview-form');
    
    if(avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if(file) {
                // Validate file size (2MB)
                if(file.size > 2 * 1024 * 1024) {
                    alert('Kích thước file quá lớn. Vui lòng chọn file nhỏ hơn 2MB.');
                    return;
                }
                
                // Validate file type
                if(!file.type.match('image.*')) {
                    alert('Vui lòng chọn file ảnh (JPG, PNG, WEBP).');
                    return;
                }
                
                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    if(avatarPreview) avatarPreview.src = e.target.result;
                    if(avatarPreviewForm) avatarPreviewForm.src = e.target.result;
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
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}
</script>
@endpush
