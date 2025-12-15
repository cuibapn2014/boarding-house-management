@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Quản lý người dùng')
@push('css')
<style>
    .user-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        cursor: pointer;
    }
    .user-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }
    .search-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }
    .add-btn {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    .add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        color: white;
    }
    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #e9ecef;
    }
    .user-role-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .action-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.3s ease;
        border: none;
    }
    .action-btn:hover {
        transform: scale(1.1);
    }
    .stats-mini {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        border-radius: 12px;
        padding: 20px;
        color: white;
    }
</style>
@endpush
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Quản lý người dùng'])

<div class="container-fluid py-4">
    <!-- Header & Stats -->
    <div class="row mb-4">
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h4 class="text-dark font-weight-bold mb-0">Quản lý người dùng</h4>
                    <p class="text-sm text-muted mb-0">Danh sách tất cả người dùng trong hệ thống</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-mini text-center">
                <h2 class="mb-0 font-weight-bold">{{ $users->total() }}</h2>
                <p class="mb-0 text-sm">Tổng người dùng</p>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="search-card card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="search" class="form-control border-start-0 ps-0" id="search-user"
                                    placeholder="Tìm kiếm theo tên, email...">
                            </div>
                        </div>
                        <div class="col-md-6 text-end mt-3 mt-md-0">
                            <a href="{{ route('user.create') }}" class="btn add-btn">
                                <i class="fas fa-user-plus me-2"></i>Thêm
                            </a>
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
                    <div class="d-flex align-items-start mb-3">
                        <img src="{{ $user?->avatar ?? '/img/user-placeholder.png' }}" class="user-avatar me-3" alt="avatar">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 font-weight-bold text-dark">
                                {{ $user->firstname }} {{ $user->lastname }}
                            </h6>
                            <p class="text-xs text-muted mb-1">
                                <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                            </p>
                            @if($user->phone)
                            <p class="text-xs text-muted mb-0">
                                <i class="fas fa-phone me-1"></i>{{ $user->phone }}
                            </p>
                            @endif
                        </div>
                        <div class="text-end">
                            @if($user->is_admin)
                            <span class="user-role-badge bg-danger-soft text-danger d-block mb-2">
                                <i class="fas fa-crown me-1"></i>Admin
                            </span>
                            @else
                            <span class="user-role-badge bg-primary-soft text-primary d-block mb-2">
                                <i class="fas fa-user me-1"></i>User
                            </span>
                            @endif
                            @if($user->plan_current == 'premium')
                            <span class="user-role-badge bg-warning text-white d-block mb-2">
                                <i class="fas fa-star me-1"></i>Premium
                            </span>
                            @else
                            <span class="user-role-badge bg-secondary text-white d-block mb-2">
                                <i class="fas fa-gift me-1"></i>Free
                            </span>
                            @endif
                            @if($user->status == 'active')
                            <span class="user-role-badge bg-success text-white d-block">
                                <i class="fas fa-check-circle me-1"></i>Active
                            </span>
                            @else
                            <span class="user-role-badge bg-danger text-white d-block">
                                <i class="fas fa-lock me-1"></i>Lock
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="border-top pt-3 mt-3">
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="text-xs text-muted mb-1">Nhà trọ</div>
                                <div class="font-weight-bold text-sm">0</div>
                            </div>
                            <div class="col-4">
                                <div class="text-xs text-muted mb-1">Phòng</div>
                                <div class="font-weight-bold text-sm">0</div>
                            </div>
                            <div class="col-4">
                                <div class="text-xs text-muted mb-1">Khách</div>
                                <div class="font-weight-bold text-sm">0</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="far fa-calendar me-1"></i>{{ date('d/m/Y', strtotime($user->created_at)) }}
                            </small>
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
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có người dùng nào</h5>
                    <p class="text-sm text-muted">Hãy thêm người dùng đầu tiên</p>
                    <a href="{{ route('user.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-user-plus me-2"></i>Thêm ngay
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($users->count() > 0 && $users->hasPages(2))
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
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
