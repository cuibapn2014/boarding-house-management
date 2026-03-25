@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Lịch xem phòng')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h6 class="mb-0">Đặt lịch xem phòng</h6>
                            <p class="text-sm text-muted mb-0">Danh sách lịch hẹn theo tin đăng của bạn{{ auth()->user()->is_admin ? ' (admin: xem tất cả)' : '' }}</p>
                        </div>
                        <a href="{{ route('boarding-house.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i>Về tin đăng
                        </a>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <form method="get" action="{{ route('appointments.index') }}" class="row g-2 align-items-end mb-4">
                        <div class="col-md-4 col-lg-3">
                            <label class="form-label text-xs text-muted mb-1">Tìm khách / SĐT</label>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Họ tên hoặc số điện thoại">
                        </div>
                        <div class="col-md-3 col-lg-2">
                            <label class="form-label text-xs text-muted mb-1">Trạng thái</label>
                            <select name="status" class="form-control form-control-sm">
                                <option value="">Tất cả</option>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5 col-lg-4">
                            <label class="form-label text-xs text-muted mb-1">Tin đăng</label>
                            <select name="boarding_house_id" class="form-control form-control-sm">
                                <option value="">Tất cả</option>
                                @foreach($listingOptions as $id => $title)
                                    <option value="{{ $id }}" @selected((string) request('boarding_house_id') === (string) $id)>{{ \Illuminate\Support\Str::limit($title, 60) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-primary mb-0">Lọc</button>
                            <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-secondary mb-0">Xoá lọc</a>
                        </div>
                    </form>

                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Giờ hẹn</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Khách</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tin / địa chỉ</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Người / xe</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vào ở dự kiến</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Trạng thái</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $row)
                                        @php
                                            $bh = $row->boarding_house;
                                        @endphp
                                        <tr>
                                            <td class="text-sm">
                                                <span class="font-weight-bold">{{ $row->appointment_at->format('d/m/Y H:i') }}</span>
                                            </td>
                                            <td class="text-sm">
                                                <div class="font-weight-bold">{{ $row->customer_name }}</div>
                                                <a href="tel:{{ $row->phone }}" class="text-primary text-xs">{{ $row->phone }}</a>
                                            </td>
                                            <td class="text-sm">
                                                @if($bh)
                                                    <a href="{{ route('boarding-house.edit', $bh->id) }}" class="font-weight-bold text-dark">{{ \Illuminate\Support\Str::limit($bh->title, 48) }}</a>
                                                    <div class="text-xs text-muted">{{ $bh->address }}, {{ $bh->ward }}, {{ $bh->district }}</div>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-sm text-center">{{ $row->total_person }} / {{ $row->total_bike }}</td>
                                            <td class="text-sm">
                                                {{ $row->move_in_date ? $row->move_in_date->format('d/m/Y') : '—' }}
                                            </td>
                                            <td class="text-sm">
                                                <span class="badge badge-sm bg-gradient-{{ $row->status === 'CONFIRMED' ? 'success' : ($row->status === 'CANCELED' ? 'secondary' : 'warning') }}">
                                                    {{ $statuses[$row->status] ?? $row->status }}
                                                </span>
                                            </td>
                                            <td class="text-sm text-muted" style="max-width: 12rem;">
                                                {{ $row->note ? \Illuminate\Support\Str::limit($row->note, 80) : '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="far fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có lịch xem phòng</h5>
                            <p class="text-muted mb-0">Khi có khách đặt lịch qua tin đăng, thông tin sẽ hiển thị tại đây.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
