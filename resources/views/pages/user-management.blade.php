@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Quản lý người dùng')
@push('css')
<style>
    /* Modern User Card Design */
    .user-card {
        border: none;
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        background: #ffffff;
        position: relative;
        cursor: default;
    }
    .user-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .user-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .user-card:hover::before {
        opacity: 1;
    }

    /* Search Card */
    .search-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        backdrop-filter: blur(10px);
    }

    /* Add Button */
    .add-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 14px;
        padding: 14px 28px;
        color: white !important;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        font-size: 15px;
    }
    .add-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        color: white !important;
        text-decoration: none;
    }
    .add-btn:active {
        transform: translateY(-1px);
    }

    /* User Avatar */
    .user-avatar {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        object-fit: cover;
        border: 3px solid #e2e8f0;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .user-card:hover .user-avatar {
        border-color: #667eea;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* User Role Badges */
    .user-role-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .user-role-badge.bg-danger-soft {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%) !important;
        color: #dc2626 !important;
        border: 1px solid rgba(220, 38, 38, 0.2);
    }
    .user-role-badge.bg-primary-soft {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%) !important;
        color: #2563eb !important;
        border: 1px solid rgba(37, 99, 235, 0.2);
    }
    .user-role-badge.bg-warning {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important;
        color: white !important;
        border: none;
    }
    .user-role-badge.bg-secondary {
        background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%) !important;
        color: white !important;
        border: none;
    }
    .user-role-badge.bg-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: white !important;
        border: none;
    }
    .user-role-badge.bg-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: white !important;
        border: none;
    }

    /* Action Buttons */
    .action-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        border: none;
        position: relative;
        overflow: hidden;
    }
    .action-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    .action-btn:hover::before {
        width: 200px;
        height: 200px;
    }
    .action-btn:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    .action-btn i {
        position: relative;
        z-index: 1;
    }

    /* Stats Card */
    .stats-mini {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 24px;
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
    }
    .stats-mini:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
    }
    .stats-mini h2 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .stats-mini p {
        font-size: 14px;
        opacity: 0.9;
        margin: 0;
    }

    /* User Info */
    .user-name {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
        line-height: 1.3;
    }
    .user-info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #64748b;
        margin-bottom: 6px;
    }
    .user-info-item i {
        width: 16px;
        color: #667eea;
        font-size: 12px;
    }

    /* Stats Row */
    .user-stats-row {
        background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%);
        border-radius: 12px;
        padding: 16px;
        margin: 16px 0;
        border: 1px solid rgba(102, 126, 234, 0.1);
    }
    .user-stats-row .col-4 {
        text-align: center;
    }
    .user-stats-row .stat-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .user-stats-row .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #667eea;
    }

    /* Empty State */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 24px;
        background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: #667eea;
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    .empty-state h5 {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }
    .empty-state p {
        font-size: 15px;
        color: #64748b;
        margin-bottom: 24px;
    }

    /* Pagination */
    .pagination {
        justify-content: center;
    }
    .pagination .page-link {
        border-radius: 10px;
        margin: 0 4px;
        border: 1.5px solid #e2e8f0;
        color: #64748b;
        padding: 10px 16px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .pagination .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Form Controls */
    .form-control {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        padding: 12px 16px;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    .input-group-text {
        background: white;
        border: 1.5px solid #e2e8f0;
        border-right: none;
        border-radius: 12px 0 0 12px;
    }
    .input-group .form-control {
        border-left: none;
        border-radius: 0 12px 12px 0;
    }
    .input-group .form-control:focus {
        border-left: 1.5px solid #667eea;
    }

    /* Responsive */
    @media (max-width: 767px) {
        .user-avatar {
            width: 60px;
            height: 60px;
        }
        .user-name {
            font-size: 16px;
        }
        .action-btn {
            width: 36px;
            height: 36px;
        }
        .user-stats-row {
            padding: 12px;
        }
    }

    /* Smooth Scroll */
    html {
        scroll-behavior: smooth;
    }

    /* Badge Container */
    .badge-container {
        display: flex;
        flex-direction: column;
        gap: 6px;
        align-items: flex-end;
    }
</style>
@endpush
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Quản lý người dùng'])

<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12" style="z-index: 9999;">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="mb-3 mb-md-0">
                    <h4 class="text-dark font-weight-bold mb-1" style="font-size: 24px; color: #1e293b;">Quản lý người dùng</h4>
                    <p class="text-sm text-muted mb-0" style="color: #64748b;">Danh sách và quản lý tất cả người dùng trong hệ thống</p>
                </div>
                <div class="d-flex gap-3 align-items-center">
                    <div class="stats-mini text-center d-none d-md-block" style="min-width: 140px;">
                        <h2 class="mb-0 font-weight-bold">{{ $users->total() }}</h2>
                        <p class="mb-0 text-sm">Tổng người dùng</p>
                    </div>
                    <a href="{{ route('user.create') }}" class="btn add-btn">
                        <i class="fas fa-user-plus me-2"></i>Thêm mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="search-card card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8 col-lg-9">
                            <div class="input-group" style="box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); border-radius: 12px; overflow: hidden;">
                                <span class="input-group-text bg-white border-end-0" style="border: 1.5px solid #e2e8f0; border-right: none;">
                                    <i class="fas fa-search" style="color: #667eea;"></i>
                                </span>
                                <input type="search" class="form-control border-start-0 ps-0" id="search-user"
                                    placeholder="Tìm kiếm theo tên, email, số điện thoại..." 
                                    style="border: 1.5px solid #e2e8f0; border-left: none;">
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 text-end mt-3 mt-md-0">
                            <div class="stats-mini text-center d-md-none">
                                <h2 class="mb-0 font-weight-bold">{{ $users->total() }}</h2>
                                <p class="mb-0 text-sm">Tổng người dùng</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Grid -->
    <div class="row" id="user-list">
        @forelse($users as $user)
        <div class="col-lg-4 col-md-6 mb-4 user-item">
            <div class="card user-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-4">
                        <img src="{{ $user?->avatar ?? '/img/user-placeholder.png' }}" class="user-avatar me-3" alt="avatar">
                        <div class="flex-grow-1">
                            <h6 class="user-name">
                                {{ $user->firstname }} {{ $user->lastname }}
                            </h6>
                            <div class="user-info-item">
                                <i class="fas fa-envelope"></i>
                                <span>{{ Str::limit($user->email, 25) }}</span>
                            </div>
                            @if($user->phone)
                            <div class="user-info-item">
                                <i class="fas fa-phone"></i>
                                <span>{{ $user->phone }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="badge-container">
                            @if($user->is_admin)
                            <span class="user-role-badge bg-danger-soft text-danger">
                                <i class="fas fa-crown"></i>Admin
                            </span>
                            @else
                            <span class="user-role-badge bg-primary-soft text-primary">
                                <i class="fas fa-user"></i>User
                            </span>
                            @endif
                            @if($user->plan_current == 'premium')
                            <span class="user-role-badge bg-warning text-white">
                                <i class="fas fa-star"></i>Premium
                            </span>
                            @else
                            <span class="user-role-badge bg-secondary text-white">
                                <i class="fas fa-gift"></i>Free
                            </span>
                            @endif
                            @if($user->status == 'active')
                            <span class="user-role-badge bg-success text-white">
                                <i class="fas fa-check-circle"></i>Active
                            </span>
                            @else
                            <span class="user-role-badge bg-danger text-white">
                                <i class="fas fa-lock"></i>Lock
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="user-stats-row">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-label">Nhà trọ</div>
                                <div class="stat-value">0</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-label">Phòng</div>
                                <div class="stat-value">0</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-label">Khách</div>
                                <div class="stat-value">0</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: #e2e8f0 !important;">
                        <div class="user-info-item mb-0">
                            <i class="far fa-calendar"></i>
                            <span style="font-size: 12px;">{{ date('d/m/Y', strtotime($user->created_at)) }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('user.edit', $user->id) }}" 
                                class="action-btn bg-gradient-primary text-white" 
                                title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(!$user->is_admin)
                            <button type="button" 
                                onclick="deleteUser({{ $user->id }})" 
                                class="action-btn bg-gradient-danger text-white" 
                                title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card" style="border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border-radius: 20px;">
                <div class="card-body empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5>Chưa có người dùng nào</h5>
                    <p>Hãy bắt đầu bằng cách thêm người dùng đầu tiên vào hệ thống</p>
                    <a href="{{ route('user.create') }}" class="btn add-btn">
                        <i class="fas fa-user-plus me-2"></i>Thêm người dùng ngay
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($users->count() > 0 && $users->hasPages(2))
    <div class="row mt-5">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
@include('components.modal', [
    'id' => 'confirmDeleteUser',
    'title' => 'Xác nhận xoá',
    'size' => 'md',
    'okText' => 'Xác nhận',
    'btnId' => 'btn-confirm-delete-user'
])

@endsection

@push('js')
<script>
    // Search functionality
    document.getElementById('search-user')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const userItems = document.querySelectorAll('.user-item');
        
        userItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Delete user function
    function deleteUser(userId) {
        if (confirm('Bạn có chắc chắn muốn xóa người dùng này?')) {
            fetch(`/user/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra khi xóa người dùng');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi xóa người dùng');
            });
        }
    }
</script>
@endpush
