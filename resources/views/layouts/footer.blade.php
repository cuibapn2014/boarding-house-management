{{-- <footer>
    <div class="d-flex flex-md-row mx-0 justify-content-md-between px-md-4 flex-column-reverse align-items-center" style="font-size: .9em;">
        <span class="m-0 copyright">&copy; {{ date('Y') }} Nhatrototsaigon. Mọi Quyền Đều Được Bảo Lưu.</span>
        <ul class="mb-0 d-md-flex justify-content-md-end mx-0 px-md-1 px-4 w-100 d-flex flex-row" style="gap: 5px 15px; list-style-type: none;">
            <li>
                <a class="text-white" href="{{ route('privacy.index') }}">
                    Chính sách bảo mật
                </a>
            </li>
            <li>
                <a class="text-white" href="/sitemap.xml">
                    Sơ đồ trang web
                </a>
            </li>
        </ul>
    </div>
</footer> --}}
<footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row text-start">
            <!-- About Section -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="fw-bold">Về chúng tôi</h5>
                <p>Chúng tôi cung cấp nền tảng tìm kiếm phòng trọ nhanh chóng, tiện lợi, phù hợp cho mọi nhu cầu của bạn.</p>
            </div>
            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4 col-6">
                <h5 class="fw-bold">Liên kết nhanh</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home.index') }}" class="text-light text-decoration-none">Trang chủ</a></li>
                    <li><a href="{{ route('contact.index') }}" class="text-light text-decoration-none">Liên hệ</a></li>
                    <li><a href="{{ route('privacy.index') }}" class="text-light text-decoration-none">Chính sách bảo mật</a></li>
                    <li><a href="/sitemap.xml" class="text-light text-decoration-none">Sơ đồ trang web</a></li>
                </ul>
            </div>
            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6 mb-4 col-6">
                <h5 class="fw-bold">Thông tin liên hệ</h5>
                <p class="mb-0" style="word-break: break-all;"><i class="fa-regular fa-envelope"></i> <a href="mailto:nmtworks.7250@gmail.com" class="text-light">nmtworks.7250@gmail.com</a></p>
                <p class="mb-0"><i class="fa-solid fa-mobile-screen-button"></i> <a href="tel:0388794195" class="text-light">0388 794 195</a></p>
            </div>
            <!-- Social Media -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold">Kết nối với chúng tôi</h5>
                <div class="d-flex gap-3">
                    <a href="https://fb.com/FakerHT" class="text-light fs-4"><i class="fa-brands fa-square-facebook"></i></a>
                    <a href="https://instagram.com/manhtuan.n7250" class="text-light fs-4"><i class="fa-brands fa-instagram"></i></a>
                    {{-- <a href="#" class="text-light fs-4"><i class="fa-brands fa-x-twitter"></i></a> --}}
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <p class="mb-0">&copy; 2025 Nền tảng tìm kiếm phòng trọ. All rights reserved.</p>
        </div>
    </div>
</footer>
