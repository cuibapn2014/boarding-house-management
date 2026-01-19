@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Thanh toán dịch vụ')

@push('css')
<style>
    .service-card {
        border: 2px solid #e9ecef;
        border-radius: 15px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .service-card:hover {
        border-color: #667eea;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
    }
    .service-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }
    .points-cost {
        font-size: 28px;
        font-weight: 700;
        color: #667eea;
    }
    .balance-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Balance Info -->
            <div class="balance-info mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Số dư điểm hiện tại</h6>
                        <h2 class="mb-0">{{ number_format($balance, 0) }} điểm</h2>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('point.top-up') }}" class="btn btn-light">
                            <i class="fas fa-plus-circle me-2"></i>Nạp điểm
                        </a>
                    </div>
                </div>
            </div>

            <!-- Boarding House Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="fas fa-home text-primary me-2"></i>Tin đăng
                    </h6>
                    <h5>{{ $boardingHouse->title }}</h5>
                    <p class="text-muted mb-0">{{ $boardingHouse->address }}</p>
                </div>
            </div>

            <!-- Services -->
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Chọn dịch vụ</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($services as $service)
                            <div class="col-md-4 mb-4">
                                <form action="{{ route('service-payment.process', $boardingHouse->id) }}" method="POST" class="service-form">
                                    @csrf
                                    <input type="hidden" name="service_type" value="{{ $service['type'] }}">
                                    
                                    <div class="service-card card h-100" onclick="this.closest('form').submit()">
                                        <div class="card-body text-center">
                                            <div class="service-icon text-primary">
                                                @if($service['type'] === 'push_listing')
                                                    <i class="fas fa-arrow-up"></i>
                                                @elseif($service['type'] === 'priority_listing')
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="fas fa-clock"></i>
                                                @endif
                                            </div>
                                            <h5 class="mb-2">{{ $service['name'] }}</h5>
                                            <p class="text-muted small mb-3">{{ $service['description'] }}</p>
                                            <div class="points-cost mb-3">
                                                {{ number_format($service['points_cost'], 0) }} điểm
                                            </div>
                                            <small class="text-muted">
                                                ≈ {{ number_format($service['points_cost'] * 1000, 0) }} VNĐ
                                            </small>
                                            <div class="mt-3">
                                                @if($balance >= $service['points_cost'])
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <i class="fas fa-check me-2"></i>Sử dụng dịch vụ
                                                    </button>
                                                @else
                                                    <div class="alert alert-warning mb-2">
                                                        <small>Bạn cần thêm {{ number_format($service['points_cost'] - $balance, 0) }} điểm</small>
                                                    </div>
                                                    <button type="submit" class="btn btn-outline-primary w-100">
                                                        <i class="fas fa-qrcode me-2"></i>Thanh toán bằng tiền mặt
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="fas fa-info-circle text-primary me-2"></i>Thông tin thanh toán
                    </h6>
                    <ul class="mb-0">
                        <li>Hệ thống sẽ ưu tiên trừ điểm từ ví của bạn trước</li>
                        <li>Nếu không đủ điểm, bạn sẽ được chuyển đến trang thanh toán bằng QR code</li>
                        <li>Sau khi thanh toán thành công, dịch vụ sẽ được kích hoạt ngay lập tức</li>
                        <li>Bạn có thể nạp điểm tại <a href="{{ route('point.top-up') }}">đây</a> để sử dụng dịch vụ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // Add loading state on form submit
    document.querySelectorAll('.service-form').forEach(form => {
        form.addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
            }
        });
    });
</script>
@endpush
