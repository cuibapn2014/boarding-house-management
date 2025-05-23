<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="vi_VN">
    @stack('seo')
    <meta name="apple-mobile-web-app-title" content="NhaTroTotSaiGon" />
    <meta name="theme-color" content="#4CAF50"/>
    <link rel="icon" type="image/png" href="{{ asset('/assets/images/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/favicon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('assets/images/favicon/site.webmanifest') }}" />
    <link rel="preload" href="{{ asset('assets/images/icon/logo.png') }}" as="image"/>
    
    <!-- Jquery, Bootstrap -->
    <script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    
    {{-- Font Awnsome --}}
    <link rel="preload" as="style" href="{{ asset('vendor/fontawesome-free-6.7.2-web/css/fontawesome.min.css') }}"/>
    <link rel="preload" as="style" href="{{ asset('vendor/fontawesome-free-6.7.2-web/css/solid.min.css') }}"/>
    <link rel="preload" as="style" href="{{ asset('vendor/fontawesome-free-6.7.2-web/css/brands.min.css') }}"/>

    {{-- CSS File --}}
    <link href="{{ asset('/assets/css/bootstrap.min.css') }}" rel="stylesheet" lazyload/>
    <link href="{{ asset('/assets/css/style.css') }}" rel="stylesheet" lazyload/>

    <style>
      @font-face{font-family:'Roboto';font-style:normal;font-weight:300;font-display:swap;src:url(../fonts/Roboto-Regular.ttf) format('ttf');unicode-range:U+0100-02BA,U+02BD-02C5,U+02C7-02CC,U+02CE-02D7,U+02DD-02FF,U+0304,U+0308,U+0329,U+1D00-1DBF,U+1E00-1E9F,U+1EF2-1EFF,U+2020,U+20A0-20AB,U+20AD-20C0,U+2113,U+2C60-2C7F,U+A720-A7FF}@font-face{font-family:'Roboto';font-style:normal;font-weight:300;font-display:swap;src:url(../fonts/Roboto-Regular.ttf) format('ttf');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD}@font-face{font-family:'Roboto';font-style:normal;font-weight:400;font-display:swap;src:url(../fonts/Roboto-SemiBold.ttf) format('ttf');unicode-range:U+0100-02BA,U+02BD-02C5,U+02C7-02CC,U+02CE-02D7,U+02DD-02FF,U+0304,U+0308,U+0329,U+1D00-1DBF,U+1E00-1E9F,U+1EF2-1EFF,U+2020,U+20A0-20AB,U+20AD-20C0,U+2113,U+2C60-2C7F,U+A720-A7FF}@font-face{font-family:'Roboto';font-style:normal;font-weight:400;font-display:swap;src:url(../fonts/Roboto-SemiBold.ttf) format('ttf');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD}@font-face{font-family:'Roboto';font-style:normal;font-weight:500;font-display:swap;src:url(../fonts/Roboto-Bold.ttf) format('ttf');unicode-range:U+0100-02BA,U+02BD-02C5,U+02C7-02CC,U+02CE-02D7,U+02DD-02FF,U+0304,U+0308,U+0329,U+1D00-1DBF,U+1E00-1E9F,U+1EF2-1EFF,U+2020,U+20A0-20AB,U+20AD-20C0,U+2113,U+2C60-2C7F,U+A720-A7FF}@font-face{font-family:'Roboto';font-style:normal;font-weight:500;font-display:swap;src:url(../fonts/Roboto-Bold.ttf) format('ttf');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD}@font-face{font-family:'Roboto';font-style:normal;font-weight:700;font-display:swap;src:url(../fonts/Roboto-ExtraBold.ttf) format('ttf');unicode-range:U+0100-02BA,U+02BD-02C5,U+02C7-02CC,U+02CE-02D7,U+02DD-02FF,U+0304,U+0308,U+0329,U+1D00-1DBF,U+1E00-1E9F,U+1EF2-1EFF,U+2020,U+20A0-20AB,U+20AD-20C0,U+2113,U+2C60-2C7F,U+A720-A7FF}@font-face{font-family:'Roboto';font-style:normal;font-weight:700;font-display:swap;src:url(../fonts/Roboto-ExtraBold.ttf) format('ttf');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD}
    </style>
    @stack('css')

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "name": "Nhà Trọ Tốt Sài Gòn",
            "alternateName": "Nhà Trọ Tốt Sài Gòn",
            "url": "{{ url('/') }}",
            "description": "Tìm kiếm và thuê phòng trọ, nhà nguyên căn, căn hộ hiện đại dễ dàng với Nhatrototsaigon. Khám phá hàng ngàn chỗ ở hoàn hảo cho bạn.",
            "publisher": {
              "@type": "Organization",
              "name": "Nhà Trọ Tốt Sài Gòn",
              "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('assets/images/icon/logo.png') }}",
                "width": 200,
                "height": 60
              }
            },
            "potentialAction": {
              "@type": "SearchAction",
              "target": "{{ url(route('rentalHome.index')) }}?category={search_term_string}",
              "query-input": "required name=search_term_string"
            }
          }           
    </script>
    @stack('jsonLD-sm')

    @if(env('G_TAG_ID'))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('G_TAG_ID') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ env("G_TAG_ID") }}');
    </script>
    @endif

</head>
<body>
    @include('layouts.header')

    @yield('content')

    @include('layouts.footer')

    <div id="scroll-to-top" class="btn btn-outline-success" style="display: none" aria-label="Quay về đầu trang">
        <i class="fa-solid fa-chevron-up" aria-hidden="true"></i>
    </div>

    @include('components.loading')

    @stack('jsonLD-lg')

    <script src="{{ asset('assets/js/script.js') }}" async></script>
    
    @stack('js')
</body>
</html>