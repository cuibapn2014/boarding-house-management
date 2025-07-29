<!DOCTYPE html>
<html lang="vi" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title')</title>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    {{-- Security Headers --}}
    @if(app()->environment('production'))
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
        <meta name="referrer" content="strict-origin-when-cross-origin">
    @endif
    
    {{-- Basic SEO Meta Tags --}}
    <meta name="generator" content="Laravel {{ app()->version() }}">
    <meta name="format-detection" content="telephone=no">
    
    {{-- Default Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    
    {{-- Page-specific SEO tags --}}
    @stack('seo')
    
    {{-- PWA Meta Tags --}}
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="NhaTroTotSaiGon">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#4CAF50">
    <meta name="msapplication-TileColor" content="#4CAF50">
    
    {{-- Favicons --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon/favicon-96x96.png') }}" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/favicon/favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('assets/images/favicon/site.webmanifest') }}">
    
    {{-- DNS Prefetch & Preconnect --}}
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//www.googletagmanager.com">
    <link rel="dns-prefetch" href="//res.cloudinary.com">
    @if(env('G_TAG_ID'))
    <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
    @endif
    
    {{-- Critical Resource Preloads --}}
    <link rel="preload" href="{{ asset('assets/images/icon/logo.webp') }}" as="image" fetchpriority="high">
    <link rel="preload" href="{{ asset('assets/css/bootstrap.min.css') }}" as="style">
    <link rel="preload" href="{{ asset('assets/css/style.css') }}" as="style">
    <link rel="preload" href="{{ asset('assets/js/core/jquery.min.js') }}" as="script">
    
    {{-- Font Preloads --}}
    <link rel="preload" href="{{ asset('assets/fonts/Roboto-Regular.ttf') }}" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="{{ asset('assets/fonts/Roboto-Bold.ttf') }}" as="font" type="font/ttf" crossorigin>
    
    {{-- FontAwesome CSS --}}
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free-6.7.2-web/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free-6.7.2-web/css/solid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free-6.7.2-web/css/brands.min.css') }}">

    {{-- Main CSS Files --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('css')

    {{-- Critical CSS Inline --}}
    <style>
        /* Critical CSS for performance */
        * {
            scroll-behavior: smooth;
            scrollbar-width: thin;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            line-height: 1.6;
            font-display: swap;
        }

        /* Font Loading Optimization */
        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url('../assets/fonts/Roboto-Regular.ttf') format('truetype');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        
        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 500;
            font-display: swap;
            src: url('../assets/fonts/Roboto-SemiBold.ttf') format('truetype');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        
        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 700;
            font-display: swap;
            src: url('../assets/fonts/Roboto-Bold.ttf') format('truetype');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        /* Skeleton Loading Animation */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Critical UI Elements */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        /* Scroll to top button */
        #scroll-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Performance optimizations */
        img {
            max-width: 100%;
            height: auto;
        }

        .lazyload {
            opacity: 0;
            transition: opacity 0.3s;
        }

        .lazyload.loaded {
            opacity: 1;
        }
    </style>

    {{-- Enhanced Global Schema --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "{{ config('app.name') }}",
        "alternateName": "Nhà Trọ Tốt Sài Gòn",
        "url": "{{ url('/') }}",
        "description": "Nền tảng cho thuê phòng trọ, nhà nguyên căn và căn hộ hàng đầu tại Hồ Chí Minh. Tìm kiếm và thuê chỗ ở hoàn hảo với giá tốt nhất.",
        "publisher": {
            "@type": "Organization",
            "name": "{{ config('app.name') }}",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('assets/images/icon/logo.webp') }}",
                "width": 200,
                "height": 60
            },
            "address": {
                "@type": "PostalAddress",
                "addressLocality": "Hồ Chí Minh",
                "addressRegion": "Hồ Chí Minh",
                "addressCountry": "VN"
            },
            "contactPoint": {
                "@type": "ContactPoint",
                "contactType": "customer service",
                "availableLanguage": "Vietnamese"
            },
            "sameAs": [
                "https://facebook.com/nhatrototsaigon",
                "https://twitter.com/nhatrototsaigon"
            ]
        },
        "potentialAction": {
            "@type": "SearchAction",
            "target": {
                "@type": "EntryPoint",
                "urlTemplate": "{{ route('rentalHome.index') }}?search={search_term_string}"
            },
            "query-input": "required name=search_term_string"
        },
        "mainEntity": {
            "@type": "ItemList",
            "name": "Danh sách phòng trọ cho thuê",
            "description": "Danh sách các phòng trọ, nhà nguyên căn và căn hộ cho thuê tại TP.HCM"
        }
    }
    </script>

    @stack('jsonLD-sm')

    {{-- Google Analytics --}}
    @if(env('G_TAG_ID'))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('G_TAG_ID') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ env("G_TAG_ID") }}', {
            page_title: document.title,
            page_location: window.location.href,
            custom_map: {'custom_parameter': 'dimension1'}
        });
        
        // Enhanced ecommerce tracking
        gtag('config', '{{ env("G_TAG_ID") }}', {
            custom_map: {'page_type': 'dimension2'}
        });
    </script>
    @endif

    {{-- Structured Data for Breadcrumbs (if needed) --}}
    @stack('breadcrumbs-schema')
</head>

<body itemscope itemtype="https://schema.org/WebPage">
    {{-- Skip to content for accessibility --}}
    <a class="visually-hidden-focusable" href="#main-content">Bỏ qua đến nội dung chính</a>
    
    @include('layouts.header')

    <main id="main-content" role="main">
        @yield('content')
    </main>

    @include('layouts.footer')

    {{-- Scroll to top button --}}
    <button id="scroll-to-top" 
            class="btn btn-outline-success" 
            style="display: none" 
            aria-label="Quay về đầu trang"
            type="button">
        <i class="fa-solid fa-chevron-up" aria-hidden="true"></i>
    </button>

    @include('components.loading')

    {{-- Large JSON-LD schemas --}}
    @stack('jsonLD-lg')

    {{-- Core JavaScript --}}
    <script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

    {{-- Enhanced main script --}}
    <script>
        // Performance optimization
        document.addEventListener('DOMContentLoaded', function() {
            // Scroll to top functionality
            const scrollButton = document.getElementById('scroll-to-top');
            
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    scrollButton.style.display = 'flex';
                } else {
                    scrollButton.style.display = 'none';
                }
            });

            scrollButton.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Lazy loading images
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazyload');
                            img.classList.add('loaded');
                            observer.unobserve(img);
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }

            // Enhanced form validation
            const forms = document.querySelectorAll('form[novalidate]');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    form.classList.add('was-validated');
                });
            });

                         // Service Worker registration for caching
             if ('serviceWorker' in navigator) {
                 window.addEventListener('load', function() {
                     navigator.serviceWorker.register('/sw.js')
                         .then(function(registration) {
                             console.log('SW registered: ', registration);
                         })
                         .catch(function(registrationError) {
                             console.log('SW registration failed: ', registrationError);
                         });
                 });
             }
         });
         </script>

     @if(env('G_TAG_ID'))
     <script>
         // Google Analytics enhanced tracking
         function trackEvent(action, category, label, value) {
             gtag('event', action, {
                 event_category: category,
                 event_label: label,
                 value: value
             });
         }

         // Track scroll depth
         let scrollDepthTracked = {};
         window.addEventListener('scroll', function() {
             const scrollPercent = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
             
             if (scrollPercent >= 25 && !scrollDepthTracked['25']) {
                 trackEvent('scroll', 'engagement', '25%');
                 scrollDepthTracked['25'] = true;
             }
             if (scrollPercent >= 50 && !scrollDepthTracked['50']) {
                 trackEvent('scroll', 'engagement', '50%');
                 scrollDepthTracked['50'] = true;
             }
             if (scrollPercent >= 75 && !scrollDepthTracked['75']) {
                 trackEvent('scroll', 'engagement', '75%');
                 scrollDepthTracked['75'] = true;
             }
             if (scrollPercent >= 90 && !scrollDepthTracked['90']) {
                 trackEvent('scroll', 'engagement', '90%');
                 scrollDepthTracked['90'] = true;
             }
         });
     </script>
     @endif

     <script src="{{ asset('assets/js/script.js') }}" defer></script>

    @stack('js')

    {{-- Schema markup for webpage --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "@yield('title')",
        "description": "@yield('meta_description', 'Tìm kiếm và thuê phòng trọ chất lượng tại TP.HCM với giá tốt nhất')",
        "url": "{{ request()->fullUrl() }}",
        "inLanguage": "vi-VN",
        "isPartOf": {
            "@type": "WebSite",
            "name": "{{ config('app.name') }}",
            "url": "{{ url('/') }}"
        },
        "datePublished": "{{ now()->toISOString() }}",
        "dateModified": "{{ now()->toISOString() }}"
    }
    </script>
</body>
</html>