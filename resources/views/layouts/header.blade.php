<header class="header-new bg-white shadow-sm sticky-top">
    <div class="container">
        <nav class="d-flex align-items-center justify-content-between py-3">
            {{-- Logo --}}
            <a href="{{ route('home.index') }}" class="logo d-flex align-items-center text-decoration-none" aria-label="Trang chủ Nhatrototsaigon">
                <i class="fa-solid fa-house text-success fs-3 me-2"></i>
                <div class="d-flex align-items-baseline">
                    <span class="fw-bold fs-4 text-dark">Nhatrototsaigon</span>
                    <small class="fw-bold text-success ms-1" style="font-size: 1rem;">.com</small>
                </div>
            </a>

            {{-- Search Bar Desktop --}}
            <div class="search-header d-none d-lg-flex flex-grow-1 mx-4">
                <form action="{{ route('rentalHome.index') }}" method="GET" class="d-flex w-100">
                    <div class="input-group">
                        <input type="search" 
                               name="search" 
                               class="form-control border-end-0" 
                               placeholder="Tìm kiếm theo khu vực, quận..." 
                               aria-label="Tìm kiếm phòng trọ theo địa điểm"
                               value="{{ request()->input('search') }}">
                        <button class="btn btn-outline-secondary border-0 bg-success text-white" type="submit" aria-label="Tìm kiếm phòng trọ">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Navigation Links --}}
            <ul class="nav-links d-none d-md-flex mb-0 align-items-center" style="gap: 1.5rem;">
                <li>
                    <a href="#" class="text-decoration-none text-dark fw-normal">Yêu thích</a>
                </li>
                <li>
                    <a href="{{ route('contact.index') }}" class="text-decoration-none text-dark fw-normal">Trợ giúp</a>
                </li>
                <li>
                    @auth
                        <a href="#" class="btn btn-success btn-sm px-4 rounded-pill">Đăng tin ngay</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-success btn-sm px-4 rounded-pill">Đăng nhập/Đăng ký</a>
                    @endauth
                </li>
            </ul>

            {{-- Mobile Menu Toggle --}}
            <button class="btn btn-link d-md-none text-dark" 
                    type="button" 
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#menu-sidebar" 
                    aria-controls="menu-sidebar"
                    aria-label="Mở menu">
                <i class="fa-solid fa-bars fs-4"></i>
            </button>
        </nav>

        {{-- Search Bar Mobile --}}
        <div class="search-mobile d-lg-none pb-3">
            <form action="{{ route('rentalHome.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" 
                           name="search" 
                           class="form-control border-end-0" 
                           placeholder="Search by location..." 
                           aria-label="Tìm kiếm theo địa điểm">
                    <button class="btn btn-outline-secondary border-start-0 bg-white" type="submit" aria-label="Tìm kiếm">
                        <i class="fa-solid fa-magnifying-glass text-secondary"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Mobile Sidebar --}}
    <div class="offcanvas offcanvas-start" tabindex="-1" id="menu-sidebar" aria-labelledby="menuSidebarLabel">
        <div class="offcanvas-header bg-success text-white">
            <h5 class="offcanvas-title d-flex align-items-center" id="menuSidebarLabel">
                <i class="fa-solid fa-house fs-4 me-2"></i>
                <div class="d-flex align-items-baseline">
                    <span class="fw-bold fs-5">Nhatrototsaigon</span>
                    <small class="fw-bold ms-1" style="font-size: 0.9rem;">.com</small>
                </div>
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
        </div>
        <div class="offcanvas-body">
            <div class="d-flex flex-column" style="gap: 1rem;">
                <a href="{{ route('home.index') }}" class="text-decoration-none text-dark py-2 border-bottom">
                    <i class="fa-solid fa-house fa-fw me-2"></i>
                    <span>Trang chủ</span>
                </a>
                <a href="{{ route('rentalHome.index') }}" class="text-decoration-none text-dark py-2 border-bottom">
                    <i class="fa-solid fa-list fa-fw me-2"></i>
                    <span>Danh sách</span>
                </a>
                <a href="#" class="text-decoration-none text-dark py-2 border-bottom">
                    <i class="fa-solid fa-heart fa-fw me-2"></i>
                    <span>Yêu thích</span>
                </a>
                <a href="{{ route('contact.index') }}" class="text-decoration-none text-dark py-2 border-bottom">
                    <i class="fa-solid fa-headset fa-fw me-2"></i>
                    <span>Trợ giúp</span>
                </a>
                @auth
                    <a href="#" class="btn btn-success mt-3">Đăng tin ngay</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-success mt-3">Đăng nhập/Đăng ký</a>
                @endauth
            </div>
            <div class="mt-4 pt-4 border-top">
                <div class="d-flex flex-column" style="gap: 0.5rem;">
                    <a href="{{ route('privacy.index') }}" class="text-decoration-none text-secondary small">
                        <span>Chính sách bảo mật</span>
                    </a>
                    <a href="/sitemap.xml" class="text-decoration-none text-secondary small">
                        <span>Sơ đồ trang web</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>