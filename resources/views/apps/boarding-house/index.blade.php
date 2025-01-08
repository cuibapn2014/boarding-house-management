@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', 'Nhà trọ')
@push('css')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />   
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Nhà trọ'])
    @php
    use App\Constants\SystemDefination;

    $status = SystemDefination::BOARDING_HOUSE_STATUS;
    $categories = SystemDefination::BOARDING_HOUSE_CATEGORY;
    @endphp
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 text-end">
                <button id="btn-create-boarding-house" data-url="{{ route('boarding-house.create') }}" type="button" class="btn btn-white position-relative">
                    <i class="fa-solid fa-plus"></i>
                    <span>Thêm</span>
                </button>
                <button id="btn-advance-filter" class="btn btn-white position-relative" type="button" data-bs-toggle="collapse" data-bs-target="#advance-filter" aria-expanded="false" aria-controls="advance-filter">
                    <i class="fa-solid fa-filter"></i>
                    <span>Tìm kiếm nâng cao</span>
                </button>
            </div>
            <form id="form-search__boarding-house">
                <div class="col-md-4 form-group row">
                    <div class="input-group input-group-md">
                      <input id="byTitle" type="search" class="form-control" name="byTitle" aria-label="Search" placeholder="Tìm kiếm nhà trọ">
                    </div>
                </div>                 
                <div class="card card-body collapse bg-light position-relative mb-2 rounded p-2" id="advance-filter">
                    <div class="row align-items-center">
                        <div class="form-group col-md-2 my-1">
                            <select class="form-control" id="byCategory" name="byCategory">
                              <option value="">Danh mục</option>
                              @foreach($categories as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 form-group my-1">
                            <div class="input-group input-group-md">
                              <span class="input-group-text" id="inputGroup-sizing-default">Giá từ</span>
                              <input id="byFromPrice" type="search" class="form-control number-separator" name="byFromPrice" aria-label="Search" placeholder="0"/>
                            </div>
                        </div>
                        <div class="col-md-2 form-group my-1">
                            <div class="input-group input-group-md">
                              <span class="input-group-text" id="inputGroup-sizing-default">Giá đến</span>
                              <input id="byToPrice" type="search" class="form-control number-separator" name="byToPrice" aria-label="Search" placeholder="6,000,000"/>
                            </div>
                        </div>
                        <div class="form-group col-md-2 my-1">
                            <select class="form-control" id="byStatus" name="byStatus">
                              <option value="">Trạng thái</option>
                              @foreach($status as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2 my-1">
                            <select class="form-control" id="byPublish" name="byPublish">
                              <option value="">Publish</option>
                              <option value="0">Không</option>
                              <option value="1">Có</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Danh sách nhà trọ</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 list-data">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Tiêu đề</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Danh mục</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Giá</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Trạng thái</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Publish</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Tạo lúc</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($boardingHouses as $boardingHouse)
                                    @php
                                        $thumbnail = $boardingHouse?->boarding_house_files?->where('type', 'image')?->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1" style="max-width: 600px;">
                                                <div>
                                                    @if($thumbnail && $thumbnail->type === 'image')
                                                    <img src="{{ $thumbnail->url }}" class="avatar avatar-sm me-3"
                                                        alt="boarding-house-file" loading="lazy">
                                                    @else
                                                    <img src="{{ \Storage::url('images/image.jpg') }}" class="avatar avatar-sm me-3"
                                                        alt="user1">
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column justify-content-center" style="flex-grow: 1">
                                                    <h6 class="mb-0 text-sm overflow-hidden text-ellipsis" style="max-width:500px;text-overflow:ellipsis;">{{ $boardingHouse->title }}</h6>
                                                    {{-- <p class="text-xs text-secondary mb-0">john@creative-tim.com</p> --}}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $boardingHouse->category }}
                                            </p>
                                            {{-- <p class="text-xs text-secondary mb-0">Organization</p> --}}
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ numberFormatVi($boardingHouse->price) }}
                                                <sup>đ</sup>
                                            </p>
                                            {{-- <p class="text-xs text-secondary mb-0">Organization</p> --}}
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="badge badge-sm {{ $boardingHouse->status == 'available' ? 'bg-gradient-success' : 'bg-gradient-warning' }}">{{ $status[$boardingHouse->status] }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                @if($boardingHouse->is_publish)
                                                <i class="fa-solid fa-eye fa-lg"></i>
                                                @else
                                                <i class="fa-solid fa-eye-slash fa-lg text-danger"></i>
                                                @endif
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ date('d/m/Y H:i', strtotime($boardingHouse->created_at)) }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <nobr class="d-flex flex-wrap" style="gap:10px;">
                                                <a href="javascript:;" data-url="{{ route('boarding-house.create', ['id' => $boardingHouse->id]) }}" class="text-secondary font-weight-bold text-xs clone-boarding-house"
                                                    data-toggle="tooltip" data-original-title="Copy">
                                                    Copy
                                                </a>
                                                <a href="javascript:;" data-url="{{ route('boarding-house.edit', [$boardingHouse->id]) }}" class="text-secondary font-weight-bold text-xs edit-boarding-house"
                                                    data-toggle="tooltip" data-original-title="Edit">
                                                    Edit
                                                </a>
                                                <a href="javascript:;" data-url="{{ route('boarding-house.destroy', [$boardingHouse->id]) }}" class="text-secondary font-weight-bold text-xs remove-boarding-house"
                                                    data-toggle="tooltip" data-original-title="Delete">
                                                    Delete
                                                </a>
                                            </nobr>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-sm py-4">Không có dữ liệu</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            
                            @if($boardingHouses->count() > 0)
                            <div id="pagination" class="row mx-0">
                                {{ $boardingHouses->links('pagination::bootstrap-5') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.modal', [
        'id' => 'createBoardingHouse',
        'title' => 'Thêm mới',
        'size' => 'xl'
    ])

    @include('components.modal', [
        'id' => 'editBoardingHouse',
        'title' => 'Chỉnh sửa',
        'size' => 'xl'
    ])
    @include('components.modal', [
        'id' => 'confirmDeleteBoardingHouse',
        'title' => 'Xác nhận xoá',
        'size' => 'md',
        'okText' => 'Chắc chắn',
        'btnId' => 'btn-confirm-delete'
    ])
@endsection
@push('js')
{{-- Tagify --}}
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>

<script src="{{ asset('assets/js/apps/boarding_house/script.js') }}"></script>
<script src="{{ asset('assets/js/apps/boarding_house/BoardingHouse.js') }}"></script>
<script src="{{ asset('assets/js/helper/Dropzone.js') }}"></script>
@endpush