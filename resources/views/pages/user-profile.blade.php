@extends('master')
@section('title', 'Tài Khoản - Nhà Trọ Tốt Sài Gòn')

@push('css')
<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #667eea;
        border: 4px solid rgba(255, 255, 255, 0.3);
    }

    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .profile-stats {
        display: flex;
        gap: 2rem;
        padding: 1.5rem 0;
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: #667eea;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 1rem 1.5rem;
    }

    .nav-tabs .nav-link.active {
        color: #667eea;
        border-bottom-color: #667eea;
        background: transparent;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-update {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        font-weight: 600;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
</style>
@endpush

@section('content')
{{-- Profile Header --}}
<div class="profile-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="profile-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>
            <div class="col">
                <h1 class="h3 mb-1">{{ $user->full_name }}</h1>
                <p class="mb-0 opacity-75">{{ $user->email }}</p>
            </div>
        </div>

        {{-- Stats --}}
        <div class="profile-stats mt-4">
            <div class="stat-item">
                <div class="stat-value text-white">{{ $savedListingsCount }}</div>
                <div class="stat-label text-white">Tin đã lưu</div>
            </div>
            <div class="stat-item">
                <div class="stat-value text-white">{{ $user->created_at->diffInDays(now()) }}</div>
                <div class="stat-label text-white">Ngày tham gia</div>
            </div>
        </div>
    </div>
</div>

{{-- Breadcrumbs --}}
<nav aria-label="breadcrumb" class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('home.index') }}">Trang chủ</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Tài khoản</li>
    </ol>
</nav>

<div class="container my-4">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-lg-3 mb-4">
            <div class="profile-card">
                <h5 class="mb-3">Menu</h5>
                <div class="list-group list-group-flush">
                    <a href="#profile-info" class="list-group-item list-group-item-action active" data-bs-toggle="tab">
                        <i class="fa-solid fa-user me-2"></i>
                        Thông tin cá nhân
                    </a>
                    <a href="#change-password" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                        <i class="fa-solid fa-lock me-2"></i>
                        Đổi mật khẩu
                    </a>
                    <a href="{{ route('savedListings.index') }}" class="list-group-item list-group-item-action">
                        <i class="fa-solid fa-heart me-2"></i>
                        Tin đã lưu
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action text-danger border-0 w-100 text-start">
                            <i class="fa-solid fa-sign-out-alt me-2"></i>
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="col-lg-9">
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
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="tab-content">
                {{-- Profile Information Tab --}}
                <div class="tab-pane fade show active" id="profile-info">
                    <div class="profile-card">
                        <h4 class="mb-4">Thông Tin Cá Nhân</h4>
                        
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstname" class="form-label">Tên <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('firstname') is-invalid @enderror" 
                                           id="firstname" 
                                           name="firstname" 
                                           value="{{ old('firstname', $user->firstname) }}" 
                                           required>
                                    @error('firstname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="lastname" class="form-label">Họ <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('lastname') is-invalid @enderror" 
                                           id="lastname" 
                                           name="lastname" 
                                           value="{{ old('lastname', $user->lastname) }}" 
                                           required>
                                    @error('lastname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}" 
                                       required>
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" 
                                       class="form-control @error('address') is-invalid @enderror" 
                                       id="address" 
                                       name="address" 
                                       value="{{ old('address', $user->address) }}">
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="city" class="form-label">Thành phố</label>
                                <input type="text" 
                                       class="form-control @error('city') is-invalid @enderror" 
                                       id="city" 
                                       name="city" 
                                       value="{{ old('city', $user->city) }}">
                                @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="about" class="form-label">Giới thiệu</label>
                                <textarea class="form-control @error('about') is-invalid @enderror" 
                                          id="about" 
                                          name="about" 
                                          rows="4">{{ old('about', $user->about) }}</textarea>
                                @error('about')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-update">
                                <i class="fa-solid fa-save me-2"></i>
                                Cập Nhật Thông Tin
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Change Password Tab --}}
                <div class="tab-pane fade" id="change-password">
                    <div class="profile-card">
                        <h4 class="mb-4">Đổi Mật Khẩu</h4>
                        
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password">
                                @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" 
                                       class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" 
                                       name="new_password">
                                @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Tối thiểu 6 ký tự</small>
                            </div>

                            <div class="mb-4">
                                <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" 
                                       class="form-control" 
                                       id="new_password_confirmation" 
                                       name="new_password_confirmation">
                            </div>

                            <button type="submit" class="btn btn-update">
                                <i class="fa-solid fa-key me-2"></i>
                                Đổi Mật Khẩu
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('seo')
<meta name="robots" content="noindex, follow">
@endpush

