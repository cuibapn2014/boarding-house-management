@php
    use App\Constants\SystemDefination;

    $status = SystemDefination::BOARDING_HOUSE_STATUS;
@endphp
<div class="card mb-4 p-3">
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
                        <label class="form-control-label">Giá</label>
                        <input id="price" name="price" class="form-control number-separator" type="text" value="0" placeholder="Nhập giá tiền" autocomplete="off" value="{{ numberFormatVi($boardingHouse->price) }}">
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
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="is_publish" type="checkbox" id="is-publish" {{ $boardingHouse->is_publish ? 'checked' : '' }}>
                        <label class="form-check-label" for="is-publish">Publish</label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>