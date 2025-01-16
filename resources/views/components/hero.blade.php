<section class="hero overflow-hidden position-relative">
    <div class="container position-relative" style="z-index:10">
        <h1 class="text-white fw-bold">Tìm chỗ ở hoàn hảo cho bạn</h1>
        <p class="text-white mb-4">Khám phá hàng ngàn phòng trọ và nhà cho thuê gần bạn.</p>
        <a href="{{ route('rentalHome.index') }}" title="Danh sách cho thuê">Xem Danh Sách</a>
    </div>
    <div class="hero-overlay bg-black position-absolute"></div>
    <img class="position-absolute w-100 h-100 top-0 z-0" 
        src="{{ asset('assets/images/hero-bg.webp') }}" 
        srcset="{{ asset('assets/images/hero-bg.webp') }} 575w, 
                {{ asset('assets/images/hero-background.webp') }} 768w" 
        sizes="(max-width: 575px) 100vw, 
            (min-width: 576px) 100vw" 
        alt="Background nhà trọ tốt Sài Gòn" 
        loading="lazy" 
        decoding="async" />
</section>