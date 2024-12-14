@php
    use App\Constants\SystemDefination;

    $status = SystemDefination::BOARDING_HOUSE_STATUS;
@endphp
<div class="card p-3">
    <div class="card-body px-0 pt-0 pb-2">
        <form id="formEditBoardingHouse" action="{{ route('boarding-house.update', [$boardingHouse->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-control-label">Tiêu đề</label>
                        <input id="title" name="title" class="form-control" type="text" placeholder="Nhập tiêu để" value="{{ $boardingHouse->title }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-control-label">Mô tả</label>
                        <input id="description" name="description" class="form-control" type="text" placeholder="Nhập mô tả" value="{{ $boardingHouse->description }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-control-label">Nội dung</label>
                        <textarea id="content" name="content" class="form-control" placeholder="Nhập nội dung">
                            {!! $boardingHouse->content !!}
                        </textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-control-label">Quận/Huyện</label>
                        <select id="district" name="district" class="form-control">
                            <option value="">Chọn quận/huyện</option>
                            <option value="{{ $boardingHouse->district }}" selected>{{ $boardingHouse->district }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-control-label">Phường/Xã</label>
                        <select id="ward" name="ward" class="form-control">
                            <option value="">Chọn phường/xã</option>
                            <option value="{{ $boardingHouse->ward }}" selected>{{ $boardingHouse->ward }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-control-label">Địa chỉ</label>
                        <input id="address" name="address" class="form-control" type="text" placeholder="Số nhà, tên đường" value="{{ $boardingHouse->address }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-control-label">Liên hệ/Zalo</label>
                        <input id="phone" name="phone" class="form-control" type="text" placeholder="Nhập số điện thoại/Zalo" value="{{ $boardingHouse?->phone }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-control-label">Giá</label>
                        <input id="price" name="price" class="form-control number-separator" type="text" placeholder="Nhập giá tiền" autocomplete="off" value="{{ numberFormatVi($boardingHouse->price) }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-control-label">Trạng thái</label>
                        <select id="status" name="status" class="form-control">
                            @foreach($status as $k => $st)
                            <option value="{{ $k }}" {{ $boardingHouse->status == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-control-label">Từ khoá</label>
                        <input class="form-control" id="tags" data-color="dark" type="text" name="tags" value="{{ $boardingHouse->tags }}" placeholder="Nhập từ khoá" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="is_publish" type="checkbox" id="is-publish" {{ $boardingHouse->is_publish ? 'checked' : '' }}>
                        <label class="form-check-label" for="is-publish">Publish</label>
                    </div>
                </div>
                @if($boardingHouse?->boarding_house_files->count() > 0)
                <div class="col-md-12">
                    <div class="d-flex flex-wrap my-2" style="gap: 5px 5px;max-width:fit-content">
                        @foreach($boardingHouse?->boarding_house_files as $file)
                        <div class="file-uploaded">
                            @if($file->type === 'image')
                            <img class="img-uploaded" src="{{ $file->url }}" alt="image"/>
                            <span class="remove-file" data-url="{{ route('boardingHouseFile.destroy', $file->id) }}">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </span>
                            @else
                            <img class="img-uploaded" src="{{ \Storage::url('images/video.png') }}" alt="image"/>
                            <span class="remove-file" data-url="{{ route('boardingHouseFile.destroy', $file->id) }}">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="col-md-12">
                    <label class="form-input-label">Upload file</label>
                    @include('components.dropzone')
                </div>
            </div>
        </form>
    </div>
</div>