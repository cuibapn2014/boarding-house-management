@extends('master')
@section('title', 'Giới Thiệu - Nhatrototsaigon.com | Nền Tảng Cho Thuê Phòng Trọ Uy Tín Tại TP.HCM')

@push('css')
<link rel="preload" href="{{ asset('assets/images/hero-background.webp') }}" as="image"/>
<style>
    .about-hero {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }
    
    .about-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('{{ asset('assets/images/hero-background.webp') }}') center/cover;
        opacity: 0.1;
        z-index: 0;
    }
    
    .about-hero .container {
        position: relative;
        z-index: 1;
    }
    
    .about-section {
        padding: 60px 0;
    }
    
    .about-section:nth-child(even) {
        background-color: #f8f9fa;
    }
    
    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 1rem;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #4CAF50, #45a049);
        border-radius: 2px;
    }
    
    .section-subtitle {
        font-size: 1.25rem;
        color: #6c757d;
        margin-bottom: 2rem;
        line-height: 1.8;
    }
    
    .feature-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        transition: all 0.3s ease;
        height: 100%;
        border: 1px solid #e9ecef;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }
    
    .feature-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #4CAF50, #45a049);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        color: white;
        font-size: 1.8rem;
    }
    
    .feature-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1rem;
    }
    
    .feature-description {
        color: #6c757d;
        line-height: 1.7;
    }
    
    .stats-section {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        padding: 60px 0;
    }
    
    .stat-item {
        text-align: center;
        padding: 2rem 1rem;
    }
    
    .stat-number {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .stat-label {
        font-size: 1.1rem;
        opacity: 0.95;
    }
    
    .value-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .value-icon {
        width: 50px;
        height: 50px;
        background: #4CAF50;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 1.5rem;
        flex-shrink: 0;
    }
    
    .value-content h4 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    
    .value-content p {
        color: #6c757d;
        margin: 0;
        line-height: 1.7;
    }
    
    .team-section {
        background: white;
    }
    
    .mission-vision {
        background: white;
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        height: 100%;
        border-left: 4px solid #4CAF50;
    }
    
    .mission-vision h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #4CAF50;
        margin-bottom: 1.5rem;
    }
    
    .mission-vision p {
        color: #6c757d;
        line-height: 1.8;
        font-size: 1.05rem;
    }
    
    @media (max-width: 768px) {
        .about-hero {
            padding: 60px 0 40px;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
        }
        
        .mission-vision {
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<section class="about-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">Về Chúng Tôi</h1>
                <p class="lead mb-0" style="font-size: 1.3rem; opacity: 0.95;">
                    Nhatrototsaigon.com - Nền tảng cho thuê phòng trọ uy tín hàng đầu tại TP. Hồ Chí Minh
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Breadcrumbs --}}
<nav aria-label="breadcrumb" class="container mt-3">
    <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="{{ route('home.index') }}" itemprop="item">
                <span itemprop="name">Trang chủ</span>
            </a>
            <meta itemprop="position" content="1" />
        </li>
        <li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span itemprop="name">Giới thiệu</span>
            <meta itemprop="position" content="2" />
        </li>
    </ol>
</nav>

{{-- Introduction Section --}}
<section class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h2 class="section-title text-center">Chào Mừng Đến Với Nhatrototsaigon.com</h2>
                <p class="section-subtitle text-center">
                    Nhatrototsaigon.com là nền tảng trực tuyến hàng đầu chuyên về cho thuê phòng trọ, nhà nguyên căn và căn hộ tại Thành phố Hồ Chí Minh. 
                    Với sứ mệnh kết nối người cho thuê và người thuê một cách nhanh chóng, tiện lợi và an toàn, chúng tôi đã và đang trở thành địa chỉ tin cậy 
                    của hàng nghìn khách hàng trong việc tìm kiếm chỗ ở phù hợp.
                </p>
                <p class="text-center" style="color: #6c757d; font-size: 1.05rem; line-height: 1.8;">
                    Chúng tôi hiểu rằng việc tìm một nơi ở phù hợp không chỉ là về giá cả, mà còn về chất lượng cuộc sống, 
                    vị trí thuận tiện và sự an toàn. Vì vậy, chúng tôi cam kết cung cấp những thông tin chính xác, đầy đủ và cập nhật nhất 
                    để giúp bạn đưa ra quyết định tốt nhất.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Stats Section --}}
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <div class="stat-item">
                    <span class="stat-number">1000+</span>
                    <span class="stat-label">Phòng Trọ Được Đăng</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <div class="stat-item">
                    <span class="stat-number">5000+</span>
                    <span class="stat-label">Người Dùng Tin Tưởng</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Hỗ Trợ Khách Hàng</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number">100%</span>
                    <span class="stat-label">Thông Tin Xác Thực</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Mission & Vision Section --}}
<section class="about-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="mission-vision">
                    <h3><i class="fa-solid fa-bullseye me-2"></i>Sứ Mệnh</h3>
                    <p>
                        Sứ mệnh của chúng tôi là tạo ra một nền tảng kết nối hiệu quả giữa người cho thuê và người thuê, 
                        giúp mọi người dễ dàng tìm được nơi ở phù hợp với nhu cầu và ngân sách của mình. Chúng tôi cam kết 
                        cung cấp dịch vụ chất lượng cao, thông tin minh bạch và hỗ trợ khách hàng tận tâm.
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mission-vision">
                    <h3><i class="fa-solid fa-eye me-2"></i>Tầm Nhìn</h3>
                    <p>
                        Trở thành nền tảng cho thuê phòng trọ số 1 tại Việt Nam, được tin tưởng và lựa chọn bởi hàng triệu người dùng. 
                        Chúng tôi hướng tới việc xây dựng một cộng đồng nơi mọi người đều có thể tìm thấy ngôi nhà lý tưởng của mình 
                        một cách nhanh chóng, an toàn và tiết kiệm.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Features Section --}}
<section class="about-section">
    <div class="container">
        <h2 class="section-title text-center">Tại Sao Chọn Chúng Tôi?</h2>
        <div class="row g-4 mt-2">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="feature-title">Uy Tín & An Toàn</h3>
                    <p class="feature-description">
                        Tất cả thông tin đăng tải đều được kiểm duyệt kỹ lưỡng. Chúng tôi đảm bảo tính xác thực 
                        và an toàn cho mọi giao dịch trên nền tảng.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <h3 class="feature-title">Tìm Kiếm Thông Minh</h3>
                    <p class="feature-description">
                        Hệ thống tìm kiếm và lọc tiên tiến giúp bạn nhanh chóng tìm được phòng trọ phù hợp 
                        theo khu vực, giá cả, tiện ích và nhiều tiêu chí khác.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <h3 class="feature-title">Cập Nhật 24/7</h3>
                    <p class="feature-description">
                        Thông tin được cập nhật liên tục, đảm bảo bạn luôn có quyền truy cập vào những tin đăng 
                        mới nhất và chính xác nhất.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h3 class="feature-title">Hỗ Trợ Tận Tâm</h3>
                    <p class="feature-description">
                        Đội ngũ hỗ trợ khách hàng chuyên nghiệp, sẵn sàng giải đáp mọi thắc mắc và hỗ trợ bạn 
                        trong suốt quá trình tìm kiếm và thuê phòng.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                    </div>
                    <h3 class="feature-title">Giao Diện Thân Thiện</h3>
                    <p class="feature-description">
                        Website được thiết kế tối ưu cho mọi thiết bị, mang lại trải nghiệm duyệt web mượt mà 
                        và dễ sử dụng trên cả máy tính và điện thoại.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-tag"></i>
                    </div>
                    <h3 class="feature-title">Giá Cả Hợp Lý</h3>
                    <p class="feature-description">
                        Chúng tôi kết nối bạn với những phòng trọ có giá cả phải chăng, phù hợp với nhiều mức ngân sách 
                        khác nhau, từ phòng trọ giá rẻ đến căn hộ cao cấp.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Core Values Section --}}
<section class="about-section">
    <div class="container">
        <h2 class="section-title text-center">Giá Trị Cốt Lõi</h2>
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fa-solid fa-heart"></i>
                    </div>
                    <div class="value-content">
                        <h4>Khách Hàng Là Trung Tâm</h4>
                        <p>
                            Mọi hoạt động của chúng tôi đều hướng tới việc mang lại giá trị tốt nhất cho khách hàng. 
                            Chúng tôi lắng nghe, thấu hiểu và không ngừng cải thiện dịch vụ để đáp ứng nhu cầu của bạn.
                        </p>
                    </div>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                    <div class="value-content">
                        <h4>Minh Bạch & Trung Thực</h4>
                        <p>
                            Chúng tôi cam kết cung cấp thông tin chính xác, minh bạch và đầy đủ. Mọi giao dịch đều được 
                            thực hiện một cách công khai, rõ ràng và đáng tin cậy.
                        </p>
                    </div>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fa-solid fa-rocket"></i>
                    </div>
                    <div class="value-content">
                        <h4>Đổi Mới & Phát Triển</h4>
                        <p>
                            Chúng tôi không ngừng cải tiến công nghệ và nâng cao chất lượng dịch vụ để mang lại trải nghiệm 
                            tốt nhất cho người dùng. Sự đổi mới là động lực phát triển của chúng tôi.
                        </p>
                    </div>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="value-content">
                        <h4>Xây Dựng Cộng Đồng</h4>
                        <p>
                            Chúng tôi tin rằng việc xây dựng một cộng đồng mạnh mẽ, nơi mọi người hỗ trợ lẫn nhau, 
                            sẽ tạo ra giá trị bền vững cho tất cả các thành viên.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="about-section" style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4" style="color: white; font-size: 2.5rem; font-weight: 700;">
                    Sẵn Sàng Tìm Ngôi Nhà Lý Tưởng Của Bạn?
                </h2>
                <p class="lead mb-4" style="font-size: 1.2rem; opacity: 0.95;">
                    Khám phá hàng nghìn phòng trọ chất lượng với giá tốt nhất tại TP.HCM ngay hôm nay!
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('rentalHome.index') }}" class="btn btn-light btn-lg px-5">
                        <i class="fa-solid fa-search me-2"></i>
                        Tìm Phòng Trọ Ngay
                    </a>
                    <a href="{{ route('contact.index') }}" class="btn btn-outline-light btn-lg px-5">
                        <i class="fa-solid fa-envelope me-2"></i>
                        Liên Hệ Với Chúng Tôi
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('seo')
{{-- Enhanced Meta Tags --}}
<meta name="title" content="Giới Thiệu - Nhatrototsaigon.com | Nền Tảng Cho Thuê Phòng Trọ Uy Tín Tại TP.HCM">
<meta name="description" content="Nhatrototsaigon.com - Nền tảng cho thuê phòng trọ, nhà nguyên căn uy tín hàng đầu tại TP. Hồ Chí Minh. Tìm hiểu về sứ mệnh, tầm nhìn và giá trị cốt lõi của chúng tôi. Hơn 1000+ phòng trọ được đăng, 5000+ người dùng tin tưởng.">
<meta name="keywords" content="giới thiệu nhatrototsaigon, về chúng tôi, nền tảng cho thuê phòng trọ, phòng trọ TP.HCM, nhà trọ Sài Gòn, cho thuê phòng trọ uy tín, nhatrototsaigon.com">
<meta name="author" content="Nhatrototsaigon Team">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="googlebot" content="index, follow">

{{-- Geo Tags --}}
<meta name="geo.region" content="VN-SG">
<meta name="geo.placename" content="Hồ Chí Minh, Việt Nam">
<meta name="ICBM" content="10.8231,106.6297">

{{-- Enhanced Open Graph --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:locale" content="vi_VN">
<meta property="og:title" content="Giới Thiệu - Nhatrototsaigon.com | Nền Tảng Cho Thuê Phòng Trọ Uy Tín">
<meta property="og:description" content="Nhatrototsaigon.com - Nền tảng cho thuê phòng trọ uy tín hàng đầu tại TP. Hồ Chí Minh. Tìm hiểu về sứ mệnh, tầm nhìn và giá trị cốt lõi của chúng tôi.">
<meta property="og:image" content="{{ asset('assets/images/icon/logo.webp') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="{{ request()->fullUrl() }}">

{{-- Enhanced Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@nhatrototsaigon">
<meta name="twitter:title" content="Giới Thiệu - Nhatrototsaigon.com">
<meta name="twitter:description" content="Nền tảng cho thuê phòng trọ uy tín hàng đầu tại TP. Hồ Chí Minh. Tìm hiểu về chúng tôi.">
<meta name="twitter:image" content="{{ asset('assets/images/icon/logo.webp') }}">

<link rel="canonical" href="{{ request()->fullUrl() }}">
@endpush

@push('jsonLD-lg')
{{-- Organization Schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Nhatrototsaigon.com",
    "alternateName": "Nhà Trọ Tốt Sài Gòn",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('assets/images/icon/logo.webp') }}",
    "description": "Nền tảng cho thuê phòng trọ, nhà nguyên căn uy tín hàng đầu tại TP. Hồ Chí Minh",
    "foundingDate": "2024",
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "Hồ Chí Minh",
        "addressRegion": "Hồ Chí Minh",
        "addressCountry": "VN"
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+84-388-794-195",
        "contactType": "customer service",
        "email": "nmtworks.7250@gmail.com",
        "availableLanguage": ["Vietnamese"]
    },
    "sameAs": [
        "https://facebook.com/FakerHT",
        "https://instagram.com/manhtuan.n7250"
    ],
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "reviewCount": "5000",
        "bestRating": "5",
        "worstRating": "1"
    }
}
</script>

{{-- AboutPage Schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "AboutPage",
    "name": "Giới Thiệu - Nhatrototsaigon.com",
    "description": "Tìm hiểu về Nhatrototsaigon.com - Nền tảng cho thuê phòng trọ uy tín hàng đầu tại TP. Hồ Chí Minh",
    "url": "{{ request()->fullUrl() }}",
    "mainEntity": {
        "@type": "Organization",
        "name": "Nhatrototsaigon.com"
    },
    "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Trang chủ",
                "item": "{{ route('home.index') }}"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "Giới thiệu",
                "item": "{{ request()->fullUrl() }}"
            }
        ]
    }
}
</script>

{{-- WebPage Schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Giới Thiệu - Nhatrototsaigon.com",
    "description": "Nhatrototsaigon.com - Nền tảng cho thuê phòng trọ uy tín hàng đầu tại TP. Hồ Chí Minh",
    "url": "{{ request()->fullUrl() }}",
    "inLanguage": "vi-VN",
    "isPartOf": {
        "@type": "WebSite",
        "name": "{{ config('app.name') }}",
        "url": "{{ url('/') }}"
    },
    "about": {
        "@type": "Organization",
        "name": "Nhatrototsaigon.com"
    },
    "datePublished": "2024-01-01",
    "dateModified": "{{ now()->toDateString() }}"
}
</script>
@endpush

