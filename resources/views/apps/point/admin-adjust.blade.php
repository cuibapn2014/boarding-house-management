@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Điều chỉnh điểm (Admin)')

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Điều chỉnh điểm'])
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Cộng / Trừ điểm thủ công</h6>
                        <a href="{{ route('point.admin.transactions') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-history me-1"></i>Lịch sử
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-4">Mọi thao tác đều được ghi lại đầy đủ trong lịch sử điểm (admin_id, lý do).</p>
                    <form action="{{ route('point.admin.adjust.store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Người dùng <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select" required>
                                <option value="">— Chọn người dùng —</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" data-points="{{ $u->points ?? 0 }}">
                                        {{ $u->firstname }} {{ $u->lastname }} — {{ $u->email }} ({{ number_format($u->points ?? 0, 0) }} điểm)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Thao tác <span class="text-danger">*</span></label>
                            <select name="action" class="form-select" required>
                                <option value="add">Cộng điểm</option>
                                <option value="subtract">Trừ điểm</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điểm <span class="text-danger">*</span></label>
                            <input type="number" name="points" class="form-control" min="1" required placeholder="Ví dụ: 100">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Lý do <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="3" maxlength="500" required placeholder="Ghi rõ lý do điều chỉnh điểm..."></textarea>
                            <small class="text-muted">Tối đa 500 ký tự. Sẽ hiển thị trong lịch sử giao dịch.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-2"></i>Thực hiện
                        </button>
                        <a href="{{ route('point.admin.transactions') }}" class="btn btn-outline-secondary">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
