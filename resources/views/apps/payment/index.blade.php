@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Lịch sử thanh toán')

@push('css')
<style>
    .payment-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    .payment-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-processing { background: #cfe2ff; color: #084298; }
    .status-completed { background: #d1e7dd; color: #0f5132; }
    .status-failed { background: #f8d7da; color: #842029; }
    .status-cancelled { background: #e2e3e5; color: #41464b; }
    .filter-tabs {
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 30px;
    }
    .filter-tab {
        padding: 12px 24px;
        border: none;
        background: none;
        color: #6c757d;
        font-weight: 600;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
    }
    .filter-tab:hover {
        color: #667eea;
    }
    .filter-tab.active {
        color: #667eea;
        border-bottom-color: #667eea;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Lịch sử thanh toán</h6>
                        @if(auth()->user()->is_admin)
                        <a href="{{ route('payment.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Tạo thanh toán mới
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Tabs -->
                    <div class="filter-tabs">
                        <a href="{{ route('payment.index') }}" 
                           class="filter-tab {{ !$status ? 'active' : '' }}">
                            Tất cả
                        </a>
                        <a href="{{ route('payment.index', ['status' => 'pending']) }}" 
                           class="filter-tab {{ $status === 'pending' ? 'active' : '' }}">
                            Đang chờ
                        </a>
                        <a href="{{ route('payment.index', ['status' => 'completed']) }}" 
                           class="filter-tab {{ $status === 'completed' ? 'active' : '' }}">
                            Đã thanh toán
                        </a>
                        <a href="{{ route('payment.index', ['status' => 'cancelled']) }}" 
                           class="filter-tab {{ $status === 'cancelled' ? 'active' : '' }}">
                            Đã hủy
                        </a>
                    </div>

                    @if($payments->count() > 0)
                    <div class="row">
                        @foreach($payments as $payment)
                        <div class="col-12">
                            <div class="card payment-card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <div class="mb-2">
                                                <strong class="text-primary">{{ $payment->payment_code }}</strong>
                                            </div>
                                            <div class="text-muted small">
                                                {{ $payment->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-2">
                                                <strong>{{ number_format($payment->amount, 0, ',', '.') }} VND</strong>
                                            </div>
                                            <div class="text-muted small">
                                                @if($payment->payment_type === 'deposit')
                                                    Đặt cọc
                                                @elseif($payment->payment_type === 'booking_fee')
                                                    Phí đặt lịch
                                                @else
                                                    Tiền thuê
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="status-badge status-{{ $payment->status }}">
                                                @if($payment->status === 'pending')
                                                    Đang chờ
                                                @elseif($payment->status === 'processing')
                                                    Đang xử lý
                                                @elseif($payment->status === 'completed')
                                                    Đã thanh toán
                                                @elseif($payment->status === 'failed')
                                                    Thất bại
                                                @else
                                                    Đã hủy
                                                @endif
                                            </span>
                                            @if($payment->paid_at)
                                            <div class="text-muted small mt-1">
                                                Thanh toán: {{ $payment->paid_at->format('d/m/Y H:i') }}
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-3 text-end">
                                            <a href="{{ route('payment.show', $payment->payment_code) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                    @if($payment->description)
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <small class="text-muted">{{ $payment->description }}</small>
                                        </div>
                                    </div>
                                    @endif
                                    @if($payment->boardingHouse)
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <small>
                                                <i class="fas fa-home me-1"></i>
                                                <a href="{{ route('boarding-house.show', $payment->boardingHouse->id) }}">
                                                    {{ $payment->boardingHouse->title }}
                                                </a>
                                            </small>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có thanh toán nào</p>
                        <a href="{{ route('payment.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tạo thanh toán mới
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
