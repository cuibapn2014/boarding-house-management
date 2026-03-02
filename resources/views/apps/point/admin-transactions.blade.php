@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Lịch sử điểm (tất cả người dùng)')

@push('css')
<style>
    .transaction-item { border-bottom: 1px solid #e9ecef; padding: 12px 0; }
    .transaction-item:last-child { border-bottom: none; }
    .transaction-type-badge { padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 600; }
    .type-top_up { background: #d4edda; color: #155724; }
    .type-deduction { background: #f8d7da; color: #721c24; }
    .type-refund { background: #d1ecf1; color: #0c5460; }
    .type-service_payment { background: #fff3cd; color: #856404; }
    .type-admin_add { background: #cfe2ff; color: #084298; }
    .type-admin_subtract { background: #f8d7da; color: #58151c; }
    @media (max-width: 767px) {
        .transaction-item .row > div { margin-bottom: 8px; }
        .transaction-item .text-md-end { text-align: left !important; }
    }
</style>
@endpush

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Lịch sử điểm (tất cả)'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <h6 class="mb-0">Lịch sử giao dịch điểm — tất cả người dùng</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('point.admin.adjust') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-coins me-1"></i>Điều chỉnh điểm
                            </a>
                            <a href="{{ route('point.wallet') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Ví điểm
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="get" class="mb-4">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small">Lọc theo người dùng</label>
                                <select name="user_id" class="form-select form-select-sm">
                                    <option value="">— Tất cả —</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                            {{ $u->firstname }} {{ $u->lastname }} ({{ $u->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fas fa-filter me-1"></i>Lọc</button>
                            </div>
                        </div>
                    </form>
                    @if($transactions->count() > 0)
                        @foreach($transactions as $transaction)
                            <div class="transaction-item">
                                <div class="row align-items-center">
                                    <div class="col-md-5">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($transaction->isPositive())
                                                    <i class="fas fa-arrow-circle-down fa-2x text-success"></i>
                                                @else
                                                    <i class="fas fa-arrow-circle-up fa-2x text-danger"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $transaction->description }}</h6>
                                                <small class="text-muted d-block">
                                                    <span class="transaction-type-badge type-{{ $transaction->transaction_type }}">
                                                        @switch($transaction->transaction_type)
                                                            @case('top_up') Nạp điểm @break
                                                            @case('deduction') Trừ điểm @break
                                                            @case('refund') Hoàn điểm @break
                                                            @case('admin_add') Admin cộng điểm @break
                                                            @case('admin_subtract') Admin trừ điểm @break
                                                            @default Thanh toán dịch vụ
                                                        @endswitch
                                                    </span>
                                                    <span class="ms-2">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
                                                </small>
                                                @if($transaction->user)
                                                    <small class="text-primary">User: {{ $transaction->user->firstname }} {{ $transaction->user->lastname }} ({{ $transaction->user->email }})</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        @if($transaction->isPositive())
                                            <span class="text-success fw-bold">+{{ number_format($transaction->amount, 0) }}</span>
                                        @else
                                            <span class="text-danger fw-bold">{{ number_format($transaction->amount, 0) }}</span>
                                        @endif
                                        <small class="text-muted d-block">điểm</small>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <small class="text-muted">Số dư sau</small>
                                        <div class="fw-bold">{{ number_format($transaction->balance_after, 0) }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="mt-4">{{ $transactions->links() }}</div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có giao dịch nào</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
