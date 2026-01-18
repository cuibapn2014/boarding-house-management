<div class="hero-new position-relative overflow-hidden" style="min-height: 500px; contain: layout style paint; display: flex; justify-content: center; flex-direction: column;">
    {{-- Hero Background Image - Optimized for LCP --}}
    <div class="hero-image position-absolute w-100 h-100 top-0 start-0">
        {{-- Preload hint in head for critical image --}}
        @push('preload')
        <link rel="preload" 
              as="image" 
              href="{{ asset('assets/images/hero-background.webp') }}" 
              imagesrcset="{{ asset('assets/images/hero-bg.webp') }} 575w, {{ asset('assets/images/hero-background.webp') }} 768w"
              imagesizes="100vw"
              fetchpriority="high">
        @endpush
        
        {{-- Main hero image with explicit dimensions to prevent CLS --}}
        <img class="w-100 h-100 object-fit-cover"
             src="{{ asset('assets/images/hero-background.webp') }}" 
             srcset="{{ asset('assets/images/hero-bg.webp') }} 575w, 
                     {{ asset('assets/images/hero-background.webp') }} 768w,
                     {{ asset('assets/images/hero-background.webp') }} 1200w" 
             sizes="100vw"
             width="1920"
             height="800"
             alt="Tìm nơi an cư, bắt đầu cuộc sống mới" 
             loading="eager" 
             fetchpriority="high"
             decoding="sync" />
        <div class="hero-overlay position-absolute w-100 h-100 top-0 start-0" style="background-color: rgba(0,0,0,0.3);"></div>
    </div>

    {{-- Hero Content --}}
    <div class="container position-relative py-5" style="z-index: 10;">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8 col-md-10 mx-auto text-center text-white">
                <h1 class="display-3 fw-bold mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                    Tìm Nơi An Cư,<br>Bắt Đầu Cuộc Sống Mới
                </h1>
                
                {{-- Advanced Search Bar --}}
                <div class="search-bar-hero bg-white rounded-3 shadow-lg p-4 mt-4">
                    <form action="{{ route('rentalHome.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            {{-- Room Type --}}
                            <div class="col-md-3 col-12 text-start">
                                <label class="form-label text-dark fw-semibold small mb-2">
                                    <i class="fa-solid fa-home text-success me-1"></i>
                                    Loại hình cho thuê
                                </label>
                                <select name="category" class="form-select" aria-label="Chọn loại hình cho thuê phòng trọ">
                                    <option value="">Tất cả loại hình</option>
                                    <option value="Phòng">Phòng Trọ</option>
                                    <option value="KTX">KTX/Sleepbox</option>
                                    <option value="Nhà nguyên căn">Nhà Nguyên Căn</option>
                                    <option value="Căn hộ/Chung cư">Căn hộ/Chung cư</option>
                                    <option value="Nhà ở">Nhà ở</option>
                                    <option value="Văn phòng/Mặt bằng">Văn phòng/Mặt bằng</option>
                                    <option value="Phòng trọ">Phòng trọ</option>
                                    <option value="Ký túc xá">Ký túc xá</option>
                                    <option value="Sleepbox">Sleepbox</option>
                                </select>
                            </div>

                            {{-- Location --}}
                            <div class="col-md-3 col-12 text-start">
                                <label class="form-label text-dark fw-semibold small mb-2">
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i>
                                    Khu vực
                                </label>
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Quận 1, Bình Thạnh..."
                                       aria-label="Nhập khu vực tìm kiếm">
                            </div>

                            {{-- Price Range --}}
                            <div class="col-md-3 col-12 text-start">
                                <label class="form-label text-dark fw-semibold small mb-2">
                                    <i class="fa-solid fa-money-bill-wave text-warning me-1"></i>
                                    Mức giá
                                </label>
                                <select name="price" class="form-select" aria-label="Chọn mức giá thuê phòng">
                                    <option value="">Tất cả mức giá</option>
                                    <option value="1000000-3000000">Dưới 3 Triệu</option>
                                    <option value="3000000-5000000">3 - 5 Triệu</option>
                                    <option value="5000000-7000000">5 - 7 Triệu</option>
                                    <option value="7000000-10000000">7 - 10 Triệu</option>
                                    <option value="10000000-999999999">Trên 10 Triệu</option>
                                </select>
                            </div>

                            {{-- Search Button --}}
                            <div class="col-md-3 col-12">
                                <button type="submit" class="btn btn-success w-100 py-2 fw-semibold" aria-label="Tìm kiếm phòng trọ ngay">
                                    <i class="fa-solid fa-magnifying-glass me-2"></i>
                                    Tìm kiếm ngay
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Quick Filter Tags --}}
                <div class="quick-filters mt-4 d-flex flex-wrap justify-content-center gap-2">
                    <a href="{{ route('rentalHome.index', ['price' => ['1000000-3000000']]) }}" 
                       class="badge bg-white text-success border border-success px-3 py-2 text-decoration-none rounded-pill"
                       title="Tìm phòng trọ giá rẻ dưới 3 triệu">
                        <i class="fa-solid fa-home me-1"></i>
                        Phòng Trọ Dưới 3 Triệu
                    </a>
                    <a href="{{ route('rentalHome.index', ['district' => ['Quận 1']]) }}" 
                       class="badge bg-white text-success border border-success px-3 py-2 text-decoration-none rounded-pill"
                       title="Phòng trọ gần trường đại học">
                        <i class="fa-solid fa-graduation-cap me-1"></i>
                        Gần Trường Đại Học
                    </a>
                    <a href="{{ route('rentalHome.index') }}" 
                       class="badge bg-success text-white px-3 py-2 text-decoration-none rounded-pill"
                       title="Phòng trọ có thể ở ngay không cọc">
                        <i class="fa-solid fa-check-circle me-1"></i>
                        Có Thể Ở Ngay
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>