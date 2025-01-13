<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="vi_VN">
    @stack('seo')
    <meta name="apple-mobile-web-app-title" content="Nhatrototsaigon" />
    <meta name="theme-color" content="#4CAF50"/>
    <link rel="icon" type="image/png" href="{{ asset('/assets/images/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/favicon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('assets/images/favicon/site.webmanifest') }}" />

    {{-- Font Awnsome --}}
    <link href="{{ asset('vendor/fontawesome-free-6.7.2-web/css/fontawesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/fontawesome-free-6.7.2-web/css/solid.min.css') }}" rel="stylesheet" />
    {{-- <link href="{{ asset('vendor/fontawesome-free-6.7.2-web/css/brands.css') }}" rel="stylesheet" /> --}}
    {{-- <link href="{{ asset('vendor/fontawesome-free-6.7.2-web/css/sharp-thin.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/fontawesome-free-6.7.2-web/css/duotone-thin.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/fontawesome-free-6.7.2-web/css/sharp-duotone-thin.css') }}" rel="stylesheet" /> --}}

    {{-- CSS File --}}
    <link href="{{ asset('/assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('/assets/css/style.css') }}" rel="stylesheet" />
    @stack('css')

    @stack('jsonLD-sm')

    <title>@yield('title') - {{ config('app.name') }}</title>
</head>
<body>
    @include('layouts.header')

    @yield('content')

    @include('layouts.footer')

    <div id="scroll-to-top" class="btn btn-outline-success" style="display: none">
        <i class="fa-solid fa-chevron-up"></i>
    </div>

    @include('components.loading')

    @stack('jsonLD-lg')

    <script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    
    @stack('js')
</body>
</html>