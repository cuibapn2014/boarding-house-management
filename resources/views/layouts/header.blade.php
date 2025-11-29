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
            <!-- <div class="search-header d-none d-lg-flex flex-grow-1 mx-4">
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
            </div> -->

            {{-- Navigation Links --}}
            <ul class="nav-links d-none d-md-flex mb-0 align-items-center" style="gap: 0.75rem;">
                {{-- Phone - Only show on large screens --}}
                <li class="d-none d-xl-block">
                    <a href="tel:0388794195" class="text-decoration-none d-flex align-items-center" title="Gọi ngay hotline">
                        <i class="fa-solid fa-phone text-success"></i>
                        <span class="text-dark fw-bold ms-2 small">0388 794 195</span>
                    </a>
                </li>
                
                {{-- Zalo --}}
                <li>
                    <a href="{{ getZaloLink('0388794195') }}" 
                       target="_blank" 
                       rel="noopener"
                       class="btn btn-outline-primary btn-sm px-2 rounded-pill"
                       title="Chat Zalo">
                        <i class="fa-brands fa-whatsapp"></i>
                        <span class="d-none d-lg-inline ms-1">Zalo</span>
                    </a>
                </li>
                
                {{-- Saved Listings - Only for authenticated users --}}
                @auth
                <li>
                    <a href="{{ route('savedListings.index') }}" 
                       class="text-decoration-none text-dark" 
                       title="Tin đã lưu">
                        <i class="fa-solid fa-heart text-danger"></i>
                        <span class="d-none d-lg-inline ms-1 small">Tin đã lưu</span>
                    </a>
                </li>
                @endauth
                
                {{-- Help --}}
                <li class="d-none d-lg-block">
                    <a href="{{ route('contact.index') }}" class="text-decoration-none text-dark small">
                        Trợ giúp
                    </a>
                </li>
                
                {{-- Guest: Login & Register --}}
                @guest
                <li>
                    <a href="{{ route('login') }}" class="text-decoration-none text-dark small">
                        <i class="fa-solid fa-sign-in-alt me-1"></i>
                        Đăng nhập
                    </a>
                </li>
                <li>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3 rounded-pill">
                        Đăng ký
                    </a>
                </li>
                @else
                {{-- Authenticated: User Dropdown --}}
                <li class="nav-item dropdown">
                    <a href="#" 
                       class="nav-link dropdown-toggle text-dark p-0 d-flex align-items-center" 
                       id="userDropdown" 
                       role="button"
                       data-bs-toggle="dropdown" 
                       aria-expanded="false"
                       onclick="toggleUserDropdown(event)"
                       style="padding: 0 !important;">
                        <i class="fa-solid fa-user-circle fs-5"></i>
                        <span class="d-none d-lg-inline ms-1 small">{{ Auth::user()->firstname }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown" id="userDropdownMenu">
                        <li class="px-3 py-2 border-bottom">
                            <div class="fw-bold px-2">{{ Auth::user()->full_name }}</div>
                            <small class="text-muted px-2">{{ Auth::user()->email }}</small>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('profile.show') }}">
                                <i class="fa-solid fa-user me-2"></i>
                                Tài khoản
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('savedListings.index') }}">
                                <i class="fa-solid fa-heart me-2"></i>
                                Tin đã lưu
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                                    <i class="fa-solid fa-sign-out-alt me-2"></i>
                                    Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @endguest
                
                {{-- Post Listing Button --}}
                <li>
                    <a href="{{ adminPortalUrl('boarding-house/create') }}" 
                       class="btn btn-success btn-sm px-3 rounded-pill">
                        <i class="fa-solid fa-plus d-lg-none"></i>
                        <span class="d-none d-lg-inline">Đăng tin</span>
                    </a>
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
        <!-- <div class="search-mobile d-lg-none pb-3">
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
        </div> -->
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
            {{-- Quick Contact Actions --}}
            <div class="mb-3 pb-3 border-bottom">
                <div class="d-flex flex-column gap-2">
                    <a href="tel:0388794195" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-phone"></i>
                        <span>Gọi Ngay: 0388 794 195</span>
                    </a>
                    <a href="{{ getZaloLink('0388794195') }}" 
                       target="_blank"
                       rel="noopener"
                       class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="fa-brands fa-whatsapp"></i>
                        <span>Chat Zalo</span>
                    </a>
                </div>
            </div>
            
            <div class="d-flex flex-column" style="gap: 1rem;">
                @auth
                <div class="bg-light p-3 rounded mb-2">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-user-circle fa-2x text-primary me-2"></i>
                        <div>
                            <div class="fw-bold text-dark">{{ Auth::user()->full_name }}</div>
                            <small class="text-muted">{{ Auth::user()->email }}</small>
                        </div>
                    </div>
                </div>
                @endauth
                
                <a href="{{ route('home.index') }}" class="text-decoration-none text-dark py-2 border-bottom">
                    <i class="fa-solid fa-house fa-fw me-2"></i>
                    <span>Trang chủ</span>
                </a>
                <a href="{{ route('rentalHome.index') }}" class="text-decoration-none text-dark py-2 border-bottom">
                    <i class="fa-solid fa-list fa-fw me-2"></i>
                    <span>Danh sách</span>
                </a>
                
                @auth
                <a href="{{ route('savedListings.index') }}" class="text-decoration-none text-dark py-2 border-bottom">
                    <i class="fa-solid fa-heart fa-fw me-2"></i>
                    <span>Tin đã lưu</span>
                </a>
                <a href="{{ route('profile.show') }}" class="text-decoration-none text-dark py-2 border-bottom">
                    <i class="fa-solid fa-user fa-fw me-2"></i>
                    <span>Tài khoản</span>
                </a>
                @endauth
                
                <a href="{{ route('about.index') }}" class="text-decoration-none text-dark py-2 border-bottom">
                    <i class="fa-solid fa-info-circle fa-fw me-2"></i>
                    <span>Giới thiệu</span>
                </a>
                <a href="{{ route('contact.index') }}" class="text-decoration-none text-dark py-2 border-bottom">
                    <i class="fa-solid fa-headset fa-fw me-2"></i>
                    <span>Trợ giúp</span>
                </a>
                
                @guest
                <a href="{{ route('login') }}" class="btn btn-primary mt-3">
                    <i class="fa-solid fa-sign-in-alt me-2"></i>
                    Đăng nhập
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-user-plus me-2"></i>
                    Đăng ký
                </a>
                @else
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="fa-solid fa-sign-out-alt me-2"></i>
                        Đăng xuất
                    </button>
                </form>
                @endguest
                
                <a href="{{ adminPortalUrl('boarding-house/create') }}" class="btn btn-success mt-3">Đăng tin ngay</a>
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

<script>
    // User Dropdown Toggle Function
    function toggleUserDropdown(event) {
        event.preventDefault();
        event.stopPropagation();
        
        const dropdownMenu = document.getElementById('userDropdownMenu');
        const dropdownToggle = document.getElementById('userDropdown');
        
        if (dropdownMenu) {
            // Toggle show class
            const isShown = dropdownMenu.classList.contains('show');
            
            // Close all other dropdowns first
            document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                menu.classList.remove('show');
            });
            
            // Toggle current dropdown
            if (!isShown) {
                dropdownMenu.classList.add('show');
                dropdownToggle.setAttribute('aria-expanded', 'true');
            } else {
                dropdownMenu.classList.remove('show');
                dropdownToggle.setAttribute('aria-expanded', 'false');
            }
        }
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.querySelector('.nav-item.dropdown');
        const dropdownMenu = document.getElementById('userDropdownMenu');
        
        if (dropdown && dropdownMenu && !dropdown.contains(event.target)) {
            dropdownMenu.classList.remove('show');
            const dropdownToggle = document.getElementById('userDropdown');
            if (dropdownToggle) {
                dropdownToggle.setAttribute('aria-expanded', 'false');
            }
        }
    });
    
    // Close dropdown when clicking on dropdown items (except logout button)
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownItems = document.querySelectorAll('#userDropdownMenu .dropdown-item:not(button)');
        dropdownItems.forEach(function(item) {
            item.addEventListener('click', function() {
                const dropdownMenu = document.getElementById('userDropdownMenu');
                if (dropdownMenu) {
                    dropdownMenu.classList.remove('show');
                }
            });
        });
    });
    
    // Close dropdown on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const dropdownMenu = document.getElementById('userDropdownMenu');
            const dropdownToggle = document.getElementById('userDropdown');
            
            if (dropdownMenu && dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
                if (dropdownToggle) {
                    dropdownToggle.setAttribute('aria-expanded', 'false');
                    dropdownToggle.focus();
                }
            }
        }
    });
</script>