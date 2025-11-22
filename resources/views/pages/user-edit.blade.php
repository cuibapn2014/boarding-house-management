@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Chỉnh sửa người dùng')
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
@include('layouts.navbars.auth.topnav', ['title' => 'Chỉnh sửa người dùng'])

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="text-dark font-weight-bold mb-0">Chỉnh sửa người dùng</h4>
                    <p class="text-sm text-muted mb-0">Cập nhật thông tin: {{ $user->username }}</p>
                </div>
                <a href="{{ route('page.index', 'user-management') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
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
                                <input class="form-control" type="text" name="username" 
                                    value="{{ old('username', $user->username) }}"
                                    placeholder="Nhập tên đăng nhập" required>
                                @error('username')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input class="form-control" type="email" name="email" 
                                    value="{{ old('email', $user->email) }}"
                                    placeholder="example@email.com" required>
                                @error('email')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="alert alert-info border-0 text-white">
                                    <small><i class="fas fa-info-circle me-2"></i>Để trống nếu không muốn thay đổi mật khẩu</small>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mật khẩu mới</label>
                                <input class="form-control" type="password" name="password"
                                    placeholder="Nhập mật khẩu mới">
                                @error('password')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Xác nhận mật khẩu mới</label>
                                <input class="form-control" type="password" name="password_confirmation"
                                    placeholder="Nhập lại mật khẩu">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ</label>
                                <input class="form-control" type="text" name="firstname" 
                                    value="{{ old('firstname', $user->firstname) }}"
                                    placeholder="Nhập họ">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tên</label>
                                <input class="form-control" type="text" name="lastname" 
                                    value="{{ old('lastname', $user->lastname) }}"
                                    placeholder="Nhập tên">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Số điện thoại/Zalo</label>
                                <input class="form-control" type="text" name="phone" 
                                    value="{{ old('phone', $user->phone) }}"
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
                                    value="{{ old('address', $user->address) }}"
                                    placeholder="Số nhà, tên đường...">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Thành phố</label>
                                <input class="form-control" type="text" name="city" 
                                    value="{{ old('city', $user->city) }}"
                                    placeholder="Thành phố">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quốc gia</label>
                                <input class="form-control" type="text" name="country" 
                                    value="{{ old('country', $user->country) }}"
                                    placeholder="Quốc gia">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Mã bưu điện</label>
                                <input class="form-control" type="text" name="postal" 
                                    value="{{ old('postal', $user->postal) }}"
                                    placeholder="Mã bưu điện">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Giới thiệu</label>
                                <textarea class="form-control" name="about" rows="4"
                                    placeholder="Viết vài dòng giới thiệu...">{{ old('about', $user->about) }}</textarea>
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
                            Thông tin
                        </h5>

                        <div class="alert alert-info border-0 text-white">
                            <h6 class="font-weight-bold text-sm mb-2 text-white">
                                <i class="fas fa-info-circle me-2 text-white"></i>Chi tiết tài khoản
                            </h6>
                            <p class="text-xs mb-2">
                                <strong>Vai trò:</strong> {{ $user->is_admin ? 'Administrator' : 'User' }}
                            </p>
                            <p class="text-xs mb-2">
                                <strong>Tạo lúc:</strong> {{ date('d/m/Y H:i', strtotime($user->created_at)) }}
                            </p>
                            <p class="text-xs mb-0">
                                <strong>Cập nhật:</strong> {{ date('d/m/Y H:i', strtotime($user->updated_at)) }}
                            </p>
                        </div>

                        <div class="alert alert-light border">
                            <h6 class="font-weight-bold text-sm mb-2">
                                <i class="fas fa-lightbulb text-warning me-2"></i>Lưu ý
                            </h6>
                            <ul class="text-xs text-muted mb-0 ps-3">
                                <li>Tên đăng nhập phải là duy nhất</li>
                                <li>Mật khẩu mới tối thiểu 8 ký tự</li>
                                <li>Email phải là hợp lệ và duy nhất</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn submit-btn">
                                <i class="fas fa-save me-2"></i>Cập nhật
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

