@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Chi tiết thanh toán')

@push('css')
<style>
    .payment-status-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        background: #ffffff;
    }
    .payment-code {
        font-size: 32px;
        font-weight: 700;
        color: #667eea;
        letter-spacing: 2px;
        text-align: center;
        padding: 20px;
        background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%);
        border-radius: 12px;
    }
    .status-badge {
        padding: 10px 20px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
    }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-processing { background: #cfe2ff; color: #084298; }
    .status-completed { background: #d1e7dd; color: #0f5132; }
    .status-failed { background: #f8d7da; color: #842029; }
    .status-cancelled { background: #e2e3e5; color: #41464b; }
    .qr-instruction {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        margin-top: 20px;
    }
    .amount-display {
        font-size: 48px;
        font-weight: 700;
        color: #667eea;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card payment-status-card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Chi tiết thanh toán</h6>
                        @if($payment->isPending() && !$payment->isExpired())
                        <form action="{{ route('payment.cancel', $payment->payment_code) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                    onclick="return confirm('Bạn có chắc muốn hủy thanh toán này?')">
                                <i class="fas fa-times me-1"></i>Hủy
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Payment Code -->
                    <div class="text-center mb-4">
                        <div class="payment-code">{{ $payment->payment_code }}</div>
                        <small class="text-muted">Mã thanh toán của bạn</small>
                    </div>

                    <!-- Amount -->
                    <div class="text-center mb-4">
                        <div class="amount-display">{{ number_format($payment->amount, 0, ',', '.') }} VND</div>
                    </div>

                    <!-- Status -->
                    <div class="text-center mb-4">
                        <span class="status-badge status-{{ $payment->status }}">
                            @if($payment->status === 'pending')
                                Đang chờ thanh toán
                            @elseif($payment->status === 'processing')
                                Đang xử lý
                            @elseif($payment->status === 'completed')
                                Đã thanh toán thành công
                            @elseif($payment->status === 'failed')
                                Thanh toán thất bại
                            @else
                                Đã hủy
                            @endif
                        </span>
                    </div>

                    <!-- Payment Info -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Loại thanh toán:</strong><br>
                                @if($payment->payment_type === 'deposit')
                                    <span class="badge bg-info">Đặt cọc</span>
                                @elseif($payment->payment_type === 'booking_fee')
                                    <span class="badge bg-warning">Phí đặt lịch</span>
                                @else
                                    <span class="badge bg-primary">Tiền thuê</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Ngày tạo:</strong><br>
                                {{ $payment->created_at->format('d/m/Y H:i:s') }}
                            </div>
                        </div>
                    </div>

                    @if($payment->description)
                    <div class="mb-3">
                        <strong>Mô tả:</strong><br>
                        {{ $payment->description }}
                    </div>
                    @endif

                    @if($payment->boardingHouse)
                    <div class="mb-3">
                        <strong>Phòng trọ:</strong><br>
                        <a href="{{ route('boarding-house.show', $payment->boardingHouse->id) }}">
                            {{ $payment->boardingHouse->title }}
                        </a>
                    </div>
                    @endif

                    @if($payment->paid_at)
                    <div class="mb-3">
                        <strong>Thời gian thanh toán:</strong><br>
                        {{ $payment->paid_at->format('d/m/Y H:i:s') }}
                    </div>
                    @endif

                    @if($payment->expires_at && $payment->isPending())
                    <div class="mb-3">
                        <strong>Hết hạn vào:</strong><br>
                        <span class="text-danger">{{ $payment->expires_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    @endif

                    <!-- Payment Instructions -->
                    @if($payment->isPending() && !$payment->isExpired())
                    <div class="qr-instruction">
                        <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Hướng dẫn thanh toán</h6>
                        <ol class="mb-0 ps-3">
                            <li>Mở ứng dụng ngân hàng của bạn</li>
                            <li>Quét mã QR hoặc chuyển khoản với nội dung: <strong>{{ $payment->payment_code }}</strong></li>
                            <li>Số tiền: <strong>{{ number_format($payment->amount, 0, ',', '.') }} VND</strong></li>
                            <li>Hệ thống sẽ tự động xác nhận thanh toán trong vài phút</li>
                        </ol>
                        <div class="mt-3 text-center">
                            <small><i class="fas fa-sync-alt me-1"></i>Trang sẽ tự động cập nhật trạng thái</small>
                        </div>
                    </div>
                    @endif

                    @if($payment->isExpired())
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Thanh toán này đã hết hạn. Vui lòng tạo thanh toán mới.
                    </div>
                    @endif

                    @if($payment->isCompleted())
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Thanh toán đã được xác nhận thành công!
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <a href="{{ route('payment.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i>Danh sách thanh toán
                            </a>
                            @if($payment->isPending() && !$payment->isExpired())
                            <button type="button" class="btn btn-primary" onclick="checkPaymentStatus()">
                                <i class="fas fa-sync-alt me-2"></i>Kiểm tra trạng thái
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // Auto refresh payment status if pending
    @if($payment->isPending() && !$payment->isExpired())
    let checkInterval;
    
    function checkPaymentStatus() {
        fetch('{{ route("payment.checkStatus", $payment->payment_code) }}')
            .then(response => response.json())
            .then(data => {
                if (data.is_completed) {
                    clearInterval(checkInterval);
                    location.reload();
                } else if (data.is_expired) {
                    clearInterval(checkInterval);
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error checking payment status:', error);
            });
    }

    // Check status every 30 seconds
    checkInterval = setInterval(checkPaymentStatus, 30000);
    
    // Also check on page visibility change
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            checkPaymentStatus();
        }
    });
    @endif
</script>
@endpush
