@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Thêm người dùng mới')
@push('css')
<style>
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
    }
    .section-title i {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        font-size: 14px;
        margin-right: 10px;
    }
    .form-label {
        font-weight: 600;
        color: #344767;
        margin-bottom: 8px;
        font-size: 13px;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #d2d6da;
        padding: 10px 15px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .submit-btn {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        border: none;
        border-radius: 12px;
        padding: 12px 32px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }
    .cancel-btn {
        border: 2px solid #d2d6da;
        border-radius: 12px;
        padding: 12px 32px;
        color: #67748e;
        font-weight: 600;
        background: white;
        transition: all 0.3s ease;
    }
    .cancel-btn:hover {
        border-color: #4CAF50;
        color: #4CAF50;
        background: #f8f9fa;
    }
</style>
@endpush
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Thêm người dùng mới'])

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="text-dark font-weight-bold mb-0">Thêm người dùng mới</h4>
                    <p class="text-sm text-muted mb-0">Tạo tài khoản người dùng mới</p>
                </div>
                <a href="{{ route('page.index', 'user-management') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('user.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Main Information -->
            <div class="col-lg-8 mb-4">
                <div class="card form-card">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="fas fa-user"></i>
                            Thông tin tài khoản
                        </h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="username" value="{{ old('username') }}"
                                    placeholder="Nhập tên đăng nhập" required>
                                @error('username')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input class="form-control" type="email" name="email" value="{{ old('email') }}"
                                    placeholder="example@email.com" required>
                                @error('email')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <input class="form-control" type="password" name="password"
                                    placeholder="Nhập mật khẩu" required>
                                @error('password')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                <input class="form-control" type="password" name="password_confirmation"
                                    placeholder="Nhập lại mật khẩu" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ</label>
                                <input class="form-control" type="text" name="firstname" value="{{ old('firstname') }}"
                                    placeholder="Nhập họ">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tên</label>
                                <input class="form-control" type="text" name="lastname" value="{{ old('lastname') }}"
                                    placeholder="Nhập tên">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Số điện thoại/Zalo</label>
                                <input class="form-control" type="text" name="phone" value="{{ old('phone') }}"
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
                                <input class="form-control" type="text" name="address" value="{{ old('address') }}"
                                    placeholder="Số nhà, tên đường...">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Thành phố</label>
                                <input class="form-control" type="text" name="city" value="{{ old('city') }}"
                                    placeholder="Thành phố">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quốc gia</label>
                                <input class="form-control" type="text" name="country" value="{{ old('country') }}"
                                    placeholder="Quốc gia">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Mã bưu điện</label>
                                <input class="form-control" type="text" name="postal" value="{{ old('postal') }}"
                                    placeholder="Mã bưu điện">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Giới thiệu</label>
                                <textarea class="form-control" name="about" rows="4"
                                    placeholder="Viết vài dòng giới thiệu...">{{ old('about') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Settings -->
            <div class="col-lg-4 mb-4">
                <div class="card form-card">
                    <div class="card-body p-4">
                        <h5 class="section-title">
                            <i class="fas fa-cog"></i>
                            Cài đặt
                        </h5>

                        <div class="alert alert-light border">
                            <h6 class="font-weight-bold text-sm mb-2">
                                <i class="fas fa-lightbulb text-warning me-2"></i>Lưu ý
                            </h6>
                            <ul class="text-xs text-muted mb-0 ps-3">
                                <li>Tên đăng nhập phải là duy nhất</li>
                                <li>Mật khẩu tối thiểu 8 ký tự</li>
                                <li>Email phải là hợp lệ và duy nhất</li>
                                <li>Không thể xóa tài khoản admin</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn submit-btn">
                                <i class="fas fa-user-plus me-2"></i>Tạo người dùng
                            </button>
                            <a href="{{ route('page.index', 'user-management') }}" class="btn cancel-btn">
                                <i class="fas fa-times me-2"></i>Hủy bỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/apps/user/form-page.js') }}"></script>
@endpush

