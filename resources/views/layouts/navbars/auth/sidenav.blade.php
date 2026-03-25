<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main" style="z-index: 10000;">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="https://nhatrototsaigon.com/"
            target="_blank">
            <img src="{{ asset('img/logo-ct-dark.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">{{ config('app.name') }}</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item mt-2">
                <h6 class="ps-4 text-uppercase text-xs font-weight-bolder opacity-6 mb-1">Tin đăng</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('boarding-house.*') ? 'active' : '' }}" href="{{ route('boarding-house.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-building text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Quản lý tin đăng</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}" href="{{ route('appointments.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-regular fa-calendar-check text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Lịch xem phòng</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 text-uppercase text-xs font-weight-bolder opacity-6 mb-1">Tài khoản &amp; điểm</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}" href="{{ route('profile') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Hồ sơ cá nhân</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('point.wallet') ? 'active' : '' }}" href="{{ route('point.wallet') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-wallet text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Ví điểm</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('point.transactions') ? 'active' : '' }}" href="{{ route('point.transactions') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-history text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Lịch sử điểm</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 text-uppercase text-xs font-weight-bolder opacity-6 mb-1">Thanh toán</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'payment') == true ? 'active' : '' }}" href="{{ route('page.index', ['page' => 'payment']) }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Quản lý thanh toán</span>
                </a>
            </li>

            @if(auth()->user()->is_admin)
            <li class="nav-item mt-3">
                <h6 class="ps-4 text-uppercase text-xs font-weight-bolder opacity-6 mb-1">Quản trị</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('point.admin.transactions') ? 'active' : '' }}" href="{{ route('point.admin.transactions') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-users-cog text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Lịch sử điểm (toàn hệ thống)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('point.admin.adjust') ? 'active' : '' }}" href="{{ route('point.admin.adjust') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-coins text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Điều chỉnh điểm</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'user-management') == true ? 'active' : '' }}" href="{{ route('page.index', ['page' => 'user-management']) }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Quản lý người dùng</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
    <div class="sidenav-footer relative">
        <div class="text-center text-muted position-absolute bottom-0 w-100 py-2 left-0">
            <p class="text-xs font-weight-bold mb-0 px-3">
                Bản quyền thuộc về <strong class="text-primary">Neatlab</strong>
            </p>
        </div>
    </div>
</aside>
