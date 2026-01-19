@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Nạp điểm')

@push('css')
<style>
    .package-card {
        border: 2px solid #e9ecef;
        border-radius: 15px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .package-card:hover {
        border-color: #667eea;
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.2);
    }
    .package-card.selected {
        border-color: #667eea;
        background: #f8f9ff;
    }
    .package-price {
        font-size: 32px;
        font-weight: 700;
        color: #667eea;
    }
    .package-points {
        font-size: 24px;
        font-weight: 600;
        color: #28a745;
    }
    .bonus-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Nạp điểm vào ví</h6>
                        <div>
                            <span class="text-muted">Số dư hiện tại: </span>
                            <span class="fw-bold text-primary">{{ number_format($balance, 0) }} điểm</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('point.process-top-up') }}" method="POST" id="topUpForm">
                        @csrf
                        <div class="row">
                            @foreach($packages as $package)
                                <div class="col-md-4 mb-4">
                                    <div class="package-card card h-100" onclick="selectPackage({{ $package->id }})">
                                        <div class="card-body text-center">
                                            @if($package->bonus_points > 0)
                                                <div class="mb-2">
                                                    <span class="bonus-badge">
                                                        <i class="fas fa-gift me-1"></i>Tặng {{ number_format($package->bonus_points, 0) }} điểm
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="package-price mb-2">
                                                {{ number_format($package->price, 0) }} đ
                                            </div>
                                            <div class="package-points mb-3">
                                                {{ number_format($package->points, 0) }} điểm
                                            </div>
                                            @if($package->bonus_points > 0)
                                                <div class="text-success mb-3">
                                                    <small>Tổng nhận: <strong>{{ number_format($package->total_points, 0) }} điểm</strong></small>
                                                </div>
                                            @endif
                                            @if($package->description)
                                                <p class="text-muted small mb-3">{{ $package->description }}</p>
                                            @endif
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="package_id" 
                                                       id="package_{{ $package->id }}" value="{{ $package->id }}" required>
                                                <label class="form-check-label" for="package_{{ $package->id }}">
                                                    Chọn gói này
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($packages->count() == 0)
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Hiện tại chưa có gói nạp điểm nào khả dụng
                            </div>
                        @else
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-credit-card me-2"></i>Tiếp tục thanh toán
                                    </button>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="fas fa-info-circle text-primary me-2"></i>Thông tin nạp điểm
                    </h6>
                    <ul class="mb-0">
                        <li>Sau khi thanh toán thành công, điểm sẽ được tự động cộng vào ví của bạn</li>
                        <li>Bạn sẽ nhận được thông báo khi điểm được cộng vào tài khoản</li>
                        <li>Điểm có thể sử dụng ngay sau khi nạp thành công</li>
                        <li>Nếu có vấn đề, vui lòng liên hệ bộ phận hỗ trợ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function selectPackage(packageId) {
        // Uncheck all radios
        document.querySelectorAll('input[name="package_id"]').forEach(radio => {
            radio.checked = false;
        });
        
        // Check selected radio
        document.getElementById('package_' + packageId).checked = true;
        
        // Update visual selection
        document.querySelectorAll('.package-card').forEach(card => {
            card.classList.remove('selected');
        });
        event.currentTarget.classList.add('selected');
    }

    // Handle form submission
    document.getElementById('topUpForm').addEventListener('submit', function(e) {
        const selectedPackage = document.querySelector('input[name="package_id"]:checked');
        if (!selectedPackage) {
            e.preventDefault();
            alert('Vui lòng chọn một gói nạp điểm');
            return false;
        }
    });
</script>
@endpush
