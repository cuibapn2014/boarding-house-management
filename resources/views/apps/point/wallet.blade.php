@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Ví điểm')

@push('css')
<style>
    .wallet-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .balance-amount {
        font-size: 48px;
        font-weight: 700;
        margin: 20px 0;
    }
    .point-icon {
        font-size: 32px;
        margin-right: 10px;
    }
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
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Wallet Card -->
            <div class="card wallet-card mb-4">
                <div class="card-body text-center">
                    <h5 class="text-white-50 mb-3">
                        <i class="fas fa-wallet point-icon"></i>Số dư điểm
                    </h5>
                    <div class="balance-amount">{{ number_format($balance, 0) }}</div>
                    <p class="text-white-50 mb-0">Điểm</p>
                    <div class="mt-4">
                        <a href="{{ route('point.top-up') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-plus-circle me-2"></i>Nạp điểm ngay
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-history fa-2x text-primary mb-3"></i>
                            <h6>Lịch sử giao dịch</h6>
                            <a href="{{ route('point.transactions') }}" class="btn btn-sm btn-outline-primary">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-gift fa-2x text-success mb-3"></i>
                            <h6>Nạp điểm</h6>
                            <a href="{{ route('point.top-up') }}" class="btn btn-sm btn-outline-success">
                                Chọn gói
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-info-circle fa-2x text-info mb-3"></i>
                            <h6>Quy định</h6>
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#rulesModal">
                                Xem quy định
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Giao dịch gần đây</h6>
                        <a href="{{ route('point.transactions') }}" class="btn btn-sm btn-outline-primary">
                            Xem tất cả
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                        @foreach($transactions->items() as $transaction)
                            <div class="transaction-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $transaction->description }}</h6>
                                        <small class="text-muted">
                                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        @if($transaction->isPositive())
                                            <span class="text-success fw-bold">+{{ number_format($transaction->amount, 0) }}</span>
                                        @else
                                            <span class="text-danger fw-bold">{{ number_format($transaction->amount, 0) }}</span>
                                        @endif
                                        <br>
                                        <small class="text-muted">Số dư: {{ number_format($transaction->balance_after, 0) }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có giao dịch nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rules Modal -->
<div class="modal fade" id="rulesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quy định sử dụng điểm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul>
                    <li>1 điểm = 1,000 VNĐ</li>
                    <li>Điểm chỉ dùng trong ứng dụng, không quy đổi lại tiền mặt</li>
                    <li>Điểm có thời hạn sử dụng: 365 ngày kể từ ngày nạp</li>
                    <li>Khi thanh toán dịch vụ, hệ thống sẽ ưu tiên trừ điểm trước</li>
                    <li>Nếu không đủ điểm, bạn có thể thanh toán bằng tiền mặt qua QR code</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endsection
