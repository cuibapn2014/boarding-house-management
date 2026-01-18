@php
use App\Constants\SystemDefination;

$categories = SystemDefination::BOARDING_HOUSE_CATEGORY;
$districts  = SystemDefination::LIST_DISTRICT;
$furnitureStatuses = SystemDefination::FURNITURE_STATUS;
@endphp
<form class="form-search" onchange="Rental.filter(this)">
    <!-- Lọc theo Loại -->
    <div class="mb-4">
        <label class="form-label fw-bold">
            <i class="fa-solid fa-tags text-success"></i>
            Loại phòng
        </label>
        @foreach($categories as $key => $category)
        <div class="form-check">
            <input class="form-check-input" 
                   name="category[]" 
                   type="checkbox" 
                   id="category_{{ \Str::slug($key) }}" 
                   value="{{ $key }}"
                   {{ in_array($key, (array)request('category', [])) ? 'checked' : '' }} />
            <label class="form-check-label" for="category_{{ \Str::slug($key) }}">
                {{ $category }}
            </label>
        </div>
        @endforeach
    </div>

    <!-- Lọc theo Giá -->
    <div class="mb-4">
        <label class="form-label fw-bold">
            <i class="fa-solid fa-money-bill-wave text-success"></i>
            Khoảng Giá
        </label>
        @php
            $priceRanges = [
                ['id' => 'price1', 'value' => '1000000-3000000', 'label' => '1 triệu - 3 triệu'],
                ['id' => 'price2', 'value' => '3000000-5000000', 'label' => '3 triệu - 5 triệu'],
                ['id' => 'price3', 'value' => '5000000-7000000', 'label' => '5 triệu - 7 triệu'],
                ['id' => 'price4', 'value' => '7000000-10000000', 'label' => '7 triệu - 10 triệu'],
            ];
        @endphp
        @foreach($priceRanges as $priceRange)
        <div class="form-check">
            <input class="form-check-input" 
                   type="checkbox" 
                   id="{{ $priceRange['id'] }}" 
                   name="price[]" 
                   value="{{ $priceRange['value'] }}"
                   {{ in_array($priceRange['value'], (array)request('price', [])) ? 'checked' : '' }}/>
            <label class="form-check-label" for="{{ $priceRange['id'] }}">
                {{ $priceRange['label'] }}
            </label>
        </div>
        @endforeach
    </div>

    <!-- Lọc theo Tình trạng nội thất -->
    <div class="mb-4">
        <label class="form-label fw-bold">
            <i class="fa-solid fa-couch text-success"></i>
            Tình trạng nội thất
        </label>
        @foreach($furnitureStatuses as $key => $status)
        <div class="form-check">
            <input class="form-check-input" 
                   name="furniture_status[]" 
                   type="checkbox" 
                   id="furniture_{{ \Str::slug($key) }}" 
                   value="{{ $key }}"
                   {{ in_array($key, (array)request('furniture_status', [])) ? 'checked' : '' }} />
            <label class="form-check-label" for="furniture_{{ \Str::slug($key) }}">
                {{ $status }}
            </label>
        </div>
        @endforeach
    </div>

    <!-- Lọc theo Địa điểm -->
    <div class="mb-4">
        <label class="form-label fw-bold">
            <i class="fa-solid fa-map-marker-alt text-success"></i>
            Khu vực
        </label>
        <div class="d-flex flex-wrap" style="gap: 0.5rem; padding: 0.25rem 0;">
            @foreach($districts as $district)
                <label for="filter_d_{{ \Str::slug($district) }}" style="cursor: pointer; margin: 0;">
                    <input id="filter_d_{{ \Str::slug($district) }}" 
                           type="checkbox" 
                           class="tag-district d-none" 
                           name="district[]" 
                           value="{{ $district }}"
                           {{ in_array($district, (array)request('district', [])) ? 'checked' : '' }} />
                    <span class="rounded-pill border text-secondary px-2 py-1 fs-6 d-inline-block">
                        {{ str_replace([''], '', $district) }}
                    </span>
                </label>
            @endforeach
        </div>
    </div>

    <!-- Nút Reset Bộ Lọc -->
    <div class="d-grid mt-3">
        <button type="button" class="btn reset-filter" onclick="window.location.href='{{ route('rentalHome.index') }}'">
            <i class="fa-solid fa-filter-circle-xmark me-2"></i>
            <span>Bỏ bộ lọc</span>
        </button>
    </div>
</form>