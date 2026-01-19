@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Thanh toán nạp điểm')

@push('css')
<style>
    .payment-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        background: #ffffff;
    }
    .package-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .amount-display {
        font-size: 36px;
        font-weight: 700;
        margin: 10px 0;
    }
    
    .payment-form-wrapper button[type="submit"] {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card payment-card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Thanh toán nạp điểm</h6>
                        <a href="{{ route('point.top-up') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
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

                    <!-- Package Info -->
                    <div class="package-info text-center">
                        <h5 class="mb-2">{{ $package->name }}</h5>
                        <div class="amount-display">{{ number_format($package->price, 0, ',', '.') }} VND</div>
                        <p class="mb-0">
                            Nhận được: <strong>{{ number_format($package->total_points, 0) }} điểm</strong>
                            @if($package->bonus_points > 0)
                                <span class="badge bg-light text-dark ms-2">+{{ number_format($package->bonus_points, 0) }} điểm thưởng</span>
                            @endif
                        </p>
                    </div>

                    <!-- Payment Code -->
                    <div class="text-center mb-4">
                        <small class="text-muted">Mã thanh toán:</small>
                        <div class="fw-bold text-primary">{{ $payment->payment_code }}</div>
                        <small class="text-muted">Vui lòng nhập mã này vào nội dung chuyển khoản</small>
                    </div>

                    <!-- Payment Form -->
                    <div class="payment-form-wrapper">
                        {!! generatePaymentButton($payment->amount, $payment->description, $payment->payment_code) !!}
                        <button class="btn btn-primary w-100" onclick="document.querySelector('.payment-form-wrapper form').submit()">
                            <i class="fas fa-credit-card me-2"></i>
                            Thanh toán ngay bằng QR code
                        </button>
                    </div>

                    <!-- Instructions -->
                    <div class="alert alert-info mt-4 text-light">
                        <h6 class="mb-2 text-light"><i class="fas fa-info-circle me-2"></i>Hướng dẫn thanh toán:</h6>
                        <ol class="mb-0 ps-3">
                            <li>Nhập mã thanh toán: <strong>{{ $payment->payment_code }}</strong> vào nội dung chuyển khoản</li>
                            <li>Số tiền: <strong>{{ number_format($payment->amount, 0, ',', '.') }} VND</strong></li>
                            <li>Hoàn tất thanh toán qua form bên trên</li>
                            <li>Điểm sẽ được tự động cộng vào ví sau khi thanh toán thành công</li>
                        </ol>
                    </div>

                    <!-- Payment Status Check -->
                    <div class="text-center mt-4">
                        <a href="{{ route('payment.show', $payment->payment_code) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>Xem chi tiết thanh toán
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // Auto redirect to payment detail page after successful payment
    // This will be handled by SePay callback or webhook
</script>
@endpush
