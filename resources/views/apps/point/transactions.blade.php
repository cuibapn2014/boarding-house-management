@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Lịch sử giao dịch điểm')

@push('css')
<style>
    .transaction-item {
        border-bottom: 1px solid #e9ecef;
        padding: 15px 0;
    }
    .transaction-item:last-child {
        border-bottom: none;
    }
    .transaction-type-badge {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }
    .type-top_up {
        background: #d4edda;
        color: #155724;
    }
    .type-deduction {
        background: #f8d7da;
        color: #721c24;
    }
    .type-refund {
        background: #d1ecf1;
        color: #0c5460;
    }
    .type-service_payment {
        background: #fff3cd;
        color: #856404;
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
                        <h6 class="mb-0">Lịch sử giao dịch điểm</h6>
                        <a href="{{ route('point.wallet') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i>Về ví điểm
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                        @foreach($transactions as $transaction)
                            <div class="transaction-item">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
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
                                                <small class="text-muted">
                                                    <span class="transaction-type-badge type-{{ $transaction->transaction_type }}">
                                                        @if($transaction->transaction_type === 'top_up')
                                                            Nạp điểm
                                                        @elseif($transaction->transaction_type === 'deduction')
                                                            Trừ điểm
                                                        @elseif($transaction->transaction_type === 'refund')
                                                            Hoàn điểm
                                                        @else
                                                            Thanh toán dịch vụ
                                                        @endif
                                                    </span>
                                                    <span class="ms-2">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="mb-1">
                                            @if($transaction->isPositive())
                                                <span class="text-success fw-bold fs-5">+{{ number_format($transaction->amount, 0) }}</span>
                                            @else
                                                <span class="text-danger fw-bold fs-5">{{ number_format($transaction->amount, 0) }}</span>
                                            @endif
                                            <small class="text-muted d-block">điểm</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <div>
                                            <small class="text-muted">Số dư sau:</small>
                                            <div class="fw-bold">{{ number_format($transaction->balance_after, 0) }} điểm</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có giao dịch nào</h5>
                            <p class="text-muted">Lịch sử giao dịch điểm của bạn sẽ hiển thị tại đây</p>
                            <a href="{{ route('point.top-up') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus-circle me-2"></i>Nạp điểm ngay
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
