@php
use App\Constants\SystemDefination;

$categories = SystemDefination::BOARDING_HOUSE_CATEGORY;
$districts  = SystemDefination::LIST_DISTRICT;
$furnitureStatuses = SystemDefination::FURNITURE_STATUS;
@endphp
<form class="form-search">
    <!-- Lọc theo Tiện ích -->
    <div class="mb-4">
        <label class="form-label fw-bold">Loại</label>
        @foreach($categories as $key => $category)
        <div class="form-check">
            <input class="form-check-input" name="category[]" type="checkbox" id="category_{{ \Str::slug($key) }}" value="{{ $key }}" />
            <label class="form-check-label" for="airConditioner">{{ $category }}</label>
        </div>
        @endforeach
    </div>

    <!-- Lọc theo Giá -->
    <div class="mb-4">
        <label class="form-label fw-bold">Khoảng Giá (VNĐ)</label>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="price1" name="price[]" value="1000000-3000000"/>
            <label class="form-check-label" for="price1">1 triệu - 3 triệu</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="price2" name="price[]" value="3000000-5000000"/>
            <label class="form-check-label" for="price2">3 triệu - 5 triệu</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="price3" name="price[]" value="5000000-7000000"/>
            <label class="form-check-label" for="price3">5 triệu - 7 triệu</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="price4" name="price[]" value="7000000-10000000"/>
            <label class="form-check-label" for="price4">7 triệu - 10 triệu</label>
        </div>
    </div>

    <!-- Lọc theo Tình trạng nội thất -->
    <div class="mb-4">
        <label class="form-label fw-bold">Tình trạng nội thất</label>
        @foreach($furnitureStatuses as $key => $status)
        <div class="form-check">
            <input class="form-check-input" name="furniture_status[]" type="checkbox" id="furniture_{{ \Str::slug($key) }}" value="{{ $key }}" />
            <label class="form-check-label" for="furniture_{{ \Str::slug($key) }}">{{ $status }}</label>
        </div>
        @endforeach
    </div>

    <!-- Lọc theo Địa điểm -->
    <div class="mb-4">
        <label for="locationSelect" class="form-label fw-bold">Khu vực</label>
        <div class="d-flex flex-wrap" style="gap:8px; padding: 5px 0;">
            @foreach($districts as $district)
                <label for="filter_d_{{ \Str::slug($district) }}">
                    <input id="filter_d_{{ \Str::slug($district) }}" type="checkbox" class="tag-district d-none" name="district[]" value="{{ $district }}"/>
                    <span class="rounded-pill border border text-secondary px-1 fs-6 pointer">
                        {{ str_replace([''], '', $district) }}
                    </span>
                </label>
            @endforeach
        </div>
    </div>

    <!-- Nút Reset Bộ Lọc -->
    <div class="d-grid">
        <button type="button" class="btn btn-warning reset-filter" style="max-width:fit-content">
            <i class="fa-solid fa-filter-circle-xmark"></i>
            <span>Bỏ bộ lọc</span>
        </button>
    </div>
</form>