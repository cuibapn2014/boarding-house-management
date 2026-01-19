@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Tạo thanh toán')

@push('css')
<style>
    .payment-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        background: #ffffff;
    }
    .payment-type-badge {
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
    }
    .amount-input {
        font-size: 24px;
        font-weight: 700;
        color: #667eea;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card payment-card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <h6 class="mb-0">Tạo thanh toán mới</h6>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('payment.store') }}" method="POST" id="paymentForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Loại thanh toán</label>
                                    <select name="payment_type" id="payment_type" class="form-control" required>
                                        <option value="deposit" {{ $type === 'deposit' ? 'selected' : '' }}>Đặt cọc</option>
                                        <option value="booking_fee" {{ $type === 'booking_fee' ? 'selected' : '' }}>Phí đặt lịch</option>
                                        <option value="rent" {{ $type === 'rent' ? 'selected' : '' }}>Tiền thuê</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Số tiền (VND)</label>
                                    <input type="number" name="amount" id="amount" class="form-control amount-input" 
                                           value="{{ number_format($amount, 0, '', '') }}" 
                                           min="1000" step="1000" required>
                                    <small class="text-muted">Số tiền tối thiểu: 1,000 VND</small>
                                </div>
                            </div>
                        </div>

                        @if($boardingHouse)
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <strong>Phòng trọ:</strong> {{ $boardingHouse->title }}<br>
                                    <small>{{ $boardingHouse->address }}</small>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="boarding_house_id" value="{{ $boardingHouse->id }}">
                        @endif

                        @if($appointment)
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <strong>Cuộc hẹn:</strong> {{ $appointment->customer_name }} - {{ $appointment->phone }}
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                        @endif

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Mô tả (tùy chọn)</label>
                                    <textarea name="description" class="form-control" rows="3" 
                                              placeholder="Nhập mô tả thanh toán..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-credit-card me-2"></i>Tạo thanh toán
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{!! generatePaymentButton(5000, 'Thanh toán phòng trọ') !!}
@endsection

@push('js')
<script>
    // Format amount input
    document.getElementById('amount').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = value;
    });
</script>
@endpush
