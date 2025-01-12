@php
use App\Constants\SystemDefination;

$categories = SystemDefination::BOARDING_HOUSE_CATEGORY;
@endphp
<form class="form-search">
    <!-- Lọc theo Tiện ích -->
    <div class="mb-4">
        <label class="form-label fw-bold">Loại</label>
        @foreach($categories as $key => $category)
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="airConditioner" value="{{ $key }}">
            <label class="form-check-label" for="airConditioner">{{ $category }}</label>
        </div>
        @endforeach
    </div>


    <!-- Lọc theo Giá -->
    <div class="mb-4">
        <label class="form-label fw-bold">Khoảng Giá (VNĐ)</label>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="price1" value="1000000-3000000">
            <label class="form-check-label" for="price1">1 triệu - 3 triệu</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="price2" value="3000000-5000000">
            <label class="form-check-label" for="price2">3 triệu - 5 triệu</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="price3" value="5000000-7000000">
            <label class="form-check-label" for="price3">5 triệu - 7 triệu</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="price4" value="7000000-10000000">
            <label class="form-check-label" for="price4">7 triệu - 10 triệu</label>
        </div>
    </div>

    <!-- Lọc theo Diện tích -->
    {{-- <div class="mb-4">
        <label for="areaRange" class="form-label fw-bold">Diện Tích (m²)</label>
        <input type="range" class="form-range" id="areaRange" min="10" max="100" step="5" onchange="updateAreaLabel()">
        <div class="d-flex justify-content-between">
            <span id="areaMin">10m²</span>
            <span id="areaMax">100m²</span>
        </div>
    </div> --}}

    <!-- Lọc theo Địa điểm -->
    <div class="mb-4">
        <label for="locationSelect" class="form-label fw-bold">Khu vực</label>
        <select class="form-select" id="locationSelect">
            <option selected>Chọn khu vực</option>
            <option value="1">Quận 1</option>
            <option value="2">Quận 3</option>
            <option value="3">Quận 7</option>
            <option value="4">Thủ Đức</option>
        </select>
    </div>

    <!-- Nút Áp Dụng -->
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Áp dụng</button>
    </div>
</form>