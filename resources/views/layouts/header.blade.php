<header>
    <div class="container">
        <nav>
            <div class="logo d-flex align-items-center pointer">
                <span class="text-md fw-bold">
                    Nhatrototsaigon
                </span>
                <small class="fw-bold" style="font-size:1.2rem;margin-top:0.5rem;">.com</small>
            </div>
            <ul class="mb-0 d-md-flex d-none fw-bold">
                <li><a href="/" class="text-white">Trang Chủ</a></li>
                <li><a href="{{ route('rentalHome.index') }}" class="text-white">Danh Sách</a></li>
                {{-- <li><a href="#" class="text-white">Blog</a></li> --}}
                <li><a href="{{ route('contact.index') }}" class="text-white">Liên Hệ</a></li>
            </ul>
            <a id="btn-open-sidebar" class="text-light btn" data-bs-toggle="offcanvas" href="#menu-sidebar" role="button"
                aria-controls="menu-sidebar" aria-label="Open the menu sidebar">
                <i class="fa-solid fa-bars-staggered"></i>
            </a>
        </nav>
    </div>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="menu-sidebar" aria-labelledby="menuSidebarLabel"
        style="background-color:#4CAF50;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="menuSidebarLabel">
                <div class="logo">
                    <span class="fw-bold">
                        Nhatrototsaigon
                    </span>
                    <small class="fw-bold" style="font-size: small;margin:0 0 0 -5px;font-size:1.2rem">.com</small>
                </div>
            </h5>
            <button type="button" class="btn-close text-white bg-light" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between">
            <div class="row overflow-x-hidden" style="gap: 10px">
                <a href="/" class="item-menu col-md-12 text-white py-2 fw-bold">
                    <i class="fa-solid fa-house fa-fw fa-lg"></i>
                    <span class="mx-2">Trang chủ</span>
                </a>
                <a href="{{ route('rentalHome.index') }}" class="item-menu col-md-12 text-white py-2 fw-bold">
                    <i class="fa-solid fa-bars fa-fw fa-lg"></i>
                    <span class="mx-2">Danh sách</span>
                </a>
                <a href="{{ route('contact.index') }}" class="item-menu col-md-12 text-white py-2 fw-bold">
                    <i class="fa-solid fa-headset fa-fw fa-lg"></i>
                    <span class="mx-2">Liên hệ</span>
                </a>
            </div>
            <div class="d-flex flex-wrap flex-row" style="gap: 10px;font-size:.79rem">
                <a href="{{ route('privacy.index') }}" class="item-menu text-white py-2">
                    <span class="mx-2">Chính sách bảo mật</span>
                </a>
                <a href="/sitemap.xml" class="item-menu text-white py-2">
                    <span class="mx-2">Sơ đồ trang web</span>
                </a>
            </div>
        </div>
    </div>
</header>