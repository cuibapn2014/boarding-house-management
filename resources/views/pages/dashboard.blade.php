@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Bảng điều khiển')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Tổng quan hệ thống'])
    
    <style>
        .stat-card {
            border: none;
            border-radius: 16px;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }
        .stat-card.primary::before {
            background: linear-gradient(90deg, #5e72e4 0%, #825ee4 100%);
        }
        .stat-card.success::before {
            background: linear-gradient(90deg, #2dce89 0%, #2dcecc 100%);
        }
        .stat-card.warning::before {
            background: linear-gradient(90deg, #fb6340 0%, #fbb140 100%);
        }
        .stat-card.info::before {
            background: linear-gradient(90deg, #11cdef 0%, #1171ef 100%);
        }
        .icon-box {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 28px;
        }
        .room-status-card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            border-left: 4px solid;
        }
        .room-status-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .room-status-card.available {
            border-left-color: #2dce89;
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
        }
        .room-status-card.occupied {
            border-left-color: #fb6340;
            background: linear-gradient(135deg, #ffffff 0%, #fef2f2 100%);
        }
        .room-status-card.maintenance {
            border-left-color: #f5365c;
            background: linear-gradient(135deg, #ffffff 0%, #fef1f2 100%);
        }
        .timeline-item {
            position: relative;
            padding-left: 30px;
            padding-bottom: 20px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 8px;
            bottom: -12px;
            width: 2px;
            background: #e9ecef;
        }
        .timeline-item:last-child::before {
            display: none;
        }
        .timeline-dot {
            position: absolute;
            left: 0;
            top: 5px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 3px solid;
        }
        .payment-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .payment-badge.urgent {
            background: #fee;
            color: #f5365c;
        }
        .payment-badge.soon {
            background: #fff4e6;
            color: #fb6340;
        }
        .chart-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        .quick-action-btn {
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            border: 2px solid;
            transition: all 0.3s ease;
        }
        .quick-action-btn:hover {
            transform: scale(1.05);
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card stat-card primary shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm text-muted mb-1 text-uppercase font-weight-bold">Tổng số phòng</p>
                                <h3 class="font-weight-bolder mb-0 text-dark">24</h3>
                                <p class="mb-0 mt-2">
                                    <span class="text-success text-sm font-weight-bold">
                                        <i class="fas fa-arrow-up me-1"></i>100%
                                    </span>
                                    <span class="text-muted text-xs">đang hoạt động</span>
                                </p>
                            </div>
                            <div class="icon-box bg-gradient-primary shadow">
                                <i class="fas fa-building text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card stat-card success shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm text-muted mb-1 text-uppercase font-weight-bold">Phòng trống</p>
                                <h3 class="font-weight-bolder mb-0 text-dark">6</h3>
                                <p class="mb-0 mt-2">
                                    <span class="text-success text-sm font-weight-bold">
                                        <i class="fas fa-check-circle me-1"></i>25%
                                    </span>
                                    <span class="text-muted text-xs">sẵn sàng cho thuê</span>
                                </p>
                            </div>
                            <div class="icon-box bg-gradient-success shadow">
                                <i class="fas fa-door-open text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card stat-card warning shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm text-muted mb-1 text-uppercase font-weight-bold">Doanh thu tháng</p>
                                <h3 class="font-weight-bolder mb-0 text-dark">128.5M</h3>
                                <p class="mb-0 mt-2">
                                    <span class="text-success text-sm font-weight-bold">
                                        <i class="fas fa-arrow-up me-1"></i>+12%
                                    </span>
                                    <span class="text-muted text-xs">so với tháng trước</span>
                                </p>
                            </div>
                            <div class="icon-box bg-gradient-warning shadow">
                                <i class="fas fa-chart-line text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card stat-card info shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm text-muted mb-1 text-uppercase font-weight-bold">Khách thuê</p>
                                <h3 class="font-weight-bolder mb-0 text-dark">18</h3>
                                <p class="mb-0 mt-2">
                                    <span class="text-success text-sm font-weight-bold">
                                        <i class="fas fa-user-plus me-1"></i>+3
                                    </span>
                                    <span class="text-muted text-xs">trong tháng này</span>
                                </p>
                            </div>
                            <div class="icon-box bg-gradient-info shadow">
                                <i class="fas fa-users text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Notifications Row -->
        <div class="row mb-4">
            <!-- Revenue Chart -->
            <div class="col-lg-8 mb-4">
                <div class="card chart-card">
                    <div class="card-header bg-transparent pb-0 pt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="text-dark font-weight-bold mb-1">Doanh thu theo tháng</h5>
                                <p class="text-sm text-muted mb-0">
                                    <i class="fas fa-chart-bar text-success me-1"></i>
                                    Tổng doanh thu năm 2024: <span class="font-weight-bold text-dark">1.2 tỷ VNĐ</span>
                                </p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Năm 2024
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">2024</a></li>
                                    <li><a class="dropdown-item" href="#">2023</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="chart">
                            <canvas id="revenue-chart" height="320"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Notifications -->
            <div class="col-lg-4 mb-4">
                <div class="card chart-card h-100">
                    <div class="card-header bg-transparent pb-0 pt-4">
                        <div class="d-flex justify-content-between align-items-center mb-0">
                            <h5 class="text-dark font-weight-bold mb-0">Thanh toán sắp tới</h5>
                            <span class="badge bg-danger">5</span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1 font-weight-bold text-sm">Phòng 101 - Nguyễn Văn A</h6>
                                        <p class="text-xs text-muted mb-0">
                                            <i class="far fa-calendar me-1"></i>Hạn: 25/11/2024
                                        </p>
                                    </div>
                                    <span class="payment-badge urgent">Khẩn cấp</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-dark font-weight-bold">3.500.000 VNĐ</span>
                                    <button class="btn btn-sm btn-outline-primary">Nhắc nhở</button>
                                </div>
                            </div>

                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1 font-weight-bold text-sm">Phòng 205 - Trần Thị B</h6>
                                        <p class="text-xs text-muted mb-0">
                                            <i class="far fa-calendar me-1"></i>Hạn: 28/11/2024
                                        </p>
                                    </div>
                                    <span class="payment-badge soon">Sớm</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-dark font-weight-bold">4.200.000 VNĐ</span>
                                    <button class="btn btn-sm btn-outline-primary">Nhắc nhở</button>
                                </div>
                            </div>

                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1 font-weight-bold text-sm">Phòng 302 - Lê Văn C</h6>
                                        <p class="text-xs text-muted mb-0">
                                            <i class="far fa-calendar me-1"></i>Hạn: 30/11/2024
                                        </p>
                                    </div>
                                    <span class="payment-badge soon">Sớm</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-dark font-weight-bold">3.800.000 VNĐ</span>
                                    <button class="btn btn-sm btn-outline-primary">Nhắc nhở</button>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-sm btn-outline-dark">Xem tất cả</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Room Status and Recent Activities -->
        <div class="row mb-4">
            <!-- Room Status Grid -->
            <div class="col-lg-8 mb-4">
                <div class="card chart-card">
                    <div class="card-header bg-transparent pb-0 pt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="text-dark font-weight-bold mb-0">Trạng thái phòng</h5>
                            <div>
                                <span class="badge bg-success me-2">
                                    <i class="fas fa-circle me-1" style="font-size: 8px;"></i>Trống
                                </span>
                                <span class="badge bg-danger me-2">
                                    <i class="fas fa-circle me-1" style="font-size: 8px;"></i>Đã thuê
                                </span>
                                <span class="badge bg-warning">
                                    <i class="fas fa-circle me-1" style="font-size: 8px;"></i>Bảo trì
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Available Rooms -->
                            <div class="col-md-4 col-sm-6">
                                <div class="room-status-card available p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h4 class="font-weight-bold mb-0 text-success">101</h4>
                                        <span class="badge bg-success-soft text-success">Trống</span>
                                    </div>
                                    <p class="text-xs text-muted mb-1">Tầng 1 • 25m²</p>
                                    <p class="text-sm font-weight-bold text-dark mb-0">3.500.000 VNĐ/tháng</p>
                                </div>
                            </div>

                            <!-- Occupied Rooms -->
                            <div class="col-md-4 col-sm-6">
                                <div class="room-status-card occupied p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h4 class="font-weight-bold mb-0 text-danger">102</h4>
                                        <span class="badge bg-danger-soft text-danger">Đã thuê</span>
                                    </div>
                                    <p class="text-xs text-muted mb-1">Nguyễn Văn A • Tầng 1</p>
                                    <p class="text-sm font-weight-bold text-dark mb-0">Hạn: 25/11/2024</p>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6">
                                <div class="room-status-card available p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h4 class="font-weight-bold mb-0 text-success">103</h4>
                                        <span class="badge bg-success-soft text-success">Trống</span>
                                    </div>
                                    <p class="text-xs text-muted mb-1">Tầng 1 • 30m²</p>
                                    <p class="text-sm font-weight-bold text-dark mb-0">4.000.000 VNĐ/tháng</p>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6">
                                <div class="room-status-card occupied p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h4 class="font-weight-bold mb-0 text-danger">201</h4>
                                        <span class="badge bg-danger-soft text-danger">Đã thuê</span>
                                    </div>
                                    <p class="text-xs text-muted mb-1">Trần Thị B • Tầng 2</p>
                                    <p class="text-sm font-weight-bold text-dark mb-0">Hạn: 28/11/2024</p>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6">
                                <div class="room-status-card maintenance p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h4 class="font-weight-bold mb-0 text-warning">202</h4>
                                        <span class="badge bg-warning-soft text-warning">Bảo trì</span>
                                    </div>
                                    <p class="text-xs text-muted mb-1">Tầng 2 • Sửa chữa</p>
                                    <p class="text-sm font-weight-bold text-dark mb-0">Hoàn thành: 30/11</p>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6">
                                <div class="room-status-card available p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h4 class="font-weight-bold mb-0 text-success">203</h4>
                                        <span class="badge bg-success-soft text-success">Trống</span>
                                    </div>
                                    <p class="text-xs text-muted mb-1">Tầng 2 • 28m²</p>
                                    <p class="text-sm font-weight-bold text-dark mb-0">3.800.000 VNĐ/tháng</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <a href="#" class="btn btn-outline-primary quick-action-btn">
                                <i class="fas fa-th me-2"></i>Xem tất cả phòng
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="col-lg-4 mb-4">
                <div class="card chart-card h-100">
                    <div class="card-header bg-transparent pb-0 pt-4">
                        <h5 class="text-dark font-weight-bold mb-0">Hoạt động gần đây</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-dot border-success bg-success"></div>
                                <div>
                                    <p class="text-sm font-weight-bold text-dark mb-1">Khách mới đăng ký</p>
                                    <p class="text-xs text-muted mb-1">Nguyễn Thị D đã đăng ký thuê phòng 304</p>
                                    <span class="text-xs text-muted">
                                        <i class="far fa-clock me-1"></i>5 phút trước
                                    </span>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot border-primary bg-primary"></div>
                                <div>
                                    <p class="text-sm font-weight-bold text-dark mb-1">Thanh toán thành công</p>
                                    <p class="text-xs text-muted mb-1">Lê Văn C đã thanh toán tiền phòng tháng 11</p>
                                    <span class="text-xs text-muted">
                                        <i class="far fa-clock me-1"></i>2 giờ trước
                                    </span>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot border-warning bg-warning"></div>
                                <div>
                                    <p class="text-sm font-weight-bold text-dark mb-1">Yêu cầu sửa chữa</p>
                                    <p class="text-xs text-muted mb-1">Phòng 202 cần sửa chữa hệ thống điện</p>
                                    <span class="text-xs text-muted">
                                        <i class="far fa-clock me-1"></i>5 giờ trước
                                    </span>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot border-info bg-info"></div>
                                <div>
                                    <p class="text-sm font-weight-bold text-dark mb-1">Hợp đồng gia hạn</p>
                                    <p class="text-xs text-muted mb-1">Phòng 102 gia hạn thêm 6 tháng</p>
                                    <span class="text-xs text-muted">
                                        <i class="far fa-clock me-1"></i>1 ngày trước
                                    </span>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot border-danger bg-danger"></div>
                                <div>
                                    <p class="text-sm font-weight-bold text-dark mb-1">Khách trả phòng</p>
                                    <p class="text-xs text-muted mb-1">Phạm Văn E đã trả phòng 305</p>
                                    <span class="text-xs text-muted">
                                        <i class="far fa-clock me-1"></i>2 ngày trước
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card chart-card">
                    <div class="card-body p-4">
                        <h5 class="text-dark font-weight-bold mb-4">Thao tác nhanh</h5>
                        <div class="row g-3">
                            <div class="col-lg-3 col-md-6">
                                <a href="#" class="btn btn-outline-primary quick-action-btn w-100 text-start">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Thêm khách thuê mới
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <a href="#" class="btn btn-outline-success quick-action-btn w-100 text-start">
                                    <i class="fas fa-file-invoice-dollar me-2"></i>
                                    Tạo hóa đơn
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <a href="#" class="btn btn-outline-warning quick-action-btn w-100 text-start">
                                    <i class="fas fa-tools me-2"></i>
                                    Yêu cầu bảo trì
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <a href="#" class="btn btn-outline-info quick-action-btn w-100 text-start">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Xem báo cáo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="./assets/js/plugins/chartjs.min.js"></script>
    <script>
        // Revenue Chart
        var ctx = document.getElementById("revenue-chart").getContext("2d");
        var gradient = ctx.createLinearGradient(0, 0, 0, 350);
        gradient.addColorStop(0, 'rgba(94, 114, 228, 0.3)');
        gradient.addColorStop(1, 'rgba(94, 114, 228, 0.0)');

        new Chart(ctx, {
            type: "line",
            data: {
                labels: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", 
                         "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"],
                datasets: [{
                    label: "Doanh thu (triệu VNĐ)",
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointBackgroundColor: "#5e72e4",
                    pointBorderColor: "#fff",
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: "#5e72e4",
                    pointHoverBorderColor: "#fff",
                    pointHoverBorderWidth: 2,
                    borderColor: "#5e72e4",
                    backgroundColor: gradient,
                    fill: true,
                    data: [98, 102, 105, 110, 115, 118, 120, 122, 125, 126, 128, 130],
                    maxBarThickness: 6
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#344767',
                        bodyColor: '#67748e',
                        borderColor: '#e9ecef',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Doanh thu: ' + context.parsed.y + ' triệu VNĐ';
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                            color: '#e9ecef'
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#67748e',
                            font: {
                                size: 12,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                            callback: function(value) {
                                return value + 'M';
                            }
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        ticks: {
                            display: true,
                            color: '#67748e',
                            padding: 10,
                            font: {
                                size: 12,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    </script>
@endpush
