<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="apple-touch-icon" sizes="76x76" href="/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/img/favicon.png">
    <title>
        @yield('title') - {{ config('app.name') }}
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!--     Fonts Awesome 6     -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/brands.min.css" integrity="sha512-7rXIvd/xj1FnI2zKQjjXzDFxC4twqBByp8yxOgEQJs4C/aNzNyoQsOO7VxH0RgYNhbAYLJLwzqslaP50KTTHIQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/fontawesome.min.css" integrity="sha512-lauN4D/0AgFUGvmMR+knQnbOADyD/XuQ8VF18I8Ll0+TLvsujshyxvU+uzogmQbSq6qJd5jnUdYtK8ShxXMlSg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/regular.min.css" integrity="sha512-mWnSnCSy+/uyx3fBqLP9Qyq3fDnIolDxqjN/8kqv5Z6Lw3T02XC+X6MqHHbIwtdwT2K/KFstAT/a1EYrSuOTMQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/solid.min.css" integrity="sha512-bdqdas3Yr82pkTg5i0X1gcAT3tBXz/8H3J1ec7RyEKAvr/YiSCJNV2dnkukmL8CicjKb9rxmd+ILK8Kg2o2wvQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/js/all.min.js" integrity="sha512-1JkMy1LR9bTo3psH+H4SV5bO2dFylgOy+UJhMus1zF4VEFuZVu5lsi4I6iIndE4N9p01z1554ZDcvMSjMaqCBQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/js/brands.min.js" integrity="sha512-DVS2SDsNMrQQMlbdcnmPtMdtOeqas4WRl06II/v/iMBRda50NUHHiI8z5Kv3WFu6OCCZYyObCXa9oSHcR4bz4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    @stack('css')

    {{-- Toastify --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    {{-- Dropzone --}}
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.css') }}" type="text/css"/>
 
    {{-- Global css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" type="text/css"/>

    {{-- Loading Spinner CSS --}}
    <style>
        .global-loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .global-loading-overlay.show {
            display: flex;
            opacity: 1;
        }
        .loading-spinner-container {
            background: white;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            animation: scaleIn 0.3s ease;
        }
        @keyframes scaleIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #5e72e4;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .loading-text {
            color: #344767;
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }
        .loading-dots {
            display: inline-flex;
            gap: 4px;
        }
        .loading-dots span {
            width: 8px;
            height: 8px;
            background: #5e72e4;
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out both;
        }
        .loading-dots span:nth-child(1) {
            animation-delay: -0.32s;
        }
        .loading-dots span:nth-child(2) {
            animation-delay: -0.16s;
        }
        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1);
            }
        }
    </style>

    {{-- Jquery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- TinyMCE --}}
    <script src="https://cdn.tiny.cloud/1/7ixm8fbuehf6vt9wnxwtss6w9joq557zoe273irzpmdi4byu/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tinymce/tinymce-jquery@2/dist/tinymce-jquery.min.js"></script>
</head>

<body class="{{ $class ?? '' }}">
    <!-- Global Loading Overlay -->
    <div class="global-loading-overlay" id="globalLoading">
        <div class="loading-spinner-container">
            <div class="loading-spinner"></div>
            <p class="loading-text">
                Đang tải
                <span class="loading-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </p>
        </div>
    </div>

    @guest
        @yield('content')
    @endguest

    @auth
        @if (in_array(request()->route()->getName(), ['sign-in-static', 'sign-up-static', 'login', 'register', 'recover-password', 'rtl', 'virtual-reality']))
            @yield('content')
        @else
            @if (!in_array(request()->route()->getName(), ['profile', 'profile-static']))
                <div class="min-height-300 bg-primary position-absolute w-100"></div>
            @elseif (in_array(request()->route()->getName(), ['profile-static', 'profile']))
                <div class="position-absolute w-100 min-height-300 top-0" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%; z-index:-1;">
                    <span class="mask bg-primary opacity-6"></span>
                </div>
            @endif
            @include('layouts.navbars.auth.sidenav')
                <main class="main-content border-radius-lg">
                    @yield('content')
                </main>
            {{-- @include('components.fixed-plugin') --}}
        @endif
    @endauth

    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/argon-dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/helper/ApiHelper.js') }}"></script>
    <script src="{{ asset('assets/js/helper/number_separator.js') }}"></script>
    <script src="{{ asset('assets/js/helper/global_helper.js') }}"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/i18n/defaults-vi_VN.min.js"></script>
    @stack('js');
</body>

</html>
