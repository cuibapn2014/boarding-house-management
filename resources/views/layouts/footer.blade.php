<footer class="footer-new bg-light py-5 mt-5">
    <div class="container">
        <div class="row">
            {{-- Logo and Description --}}
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footer-logo mb-3">
                    <a href="{{ route('home.index') }}" class="d-flex align-items-center text-decoration-none" title="Nhatrototsaigon - Tìm phòng trọ giá rẻ">
                        <i class="fa-solid fa-house text-success fs-4 me-2"></i>
                        <div class="d-flex align-items-baseline">
                            <span class="fw-bold fs-4 text-dark">Nhatrototsaigon</span>
                            <small class="fw-bold text-success ms-1" style="font-size: 1rem;">.com</small>
                        </div>
                    </a>
                </div>
                <p class="text-muted small mb-3">
                    Nền tảng cho thuê phòng trọ, nhà nguyên căn uy tín hàng đầu tại TP. Hồ Chí Minh. 
                    Giúp bạn tìm được nơi ở hoàn hảo một cách nhanh chóng và tiện lợi.
                </p>
                <div class="contact-info small text-muted mb-3">
                    <div class="mb-2">
                        <i class="fa-solid fa-envelope me-2"></i>
                        <a href="mailto:nmtworks.7250@gmail.com" class="text-muted text-decoration-none">nmtworks.7250@gmail.com</a>
                    </div>
                    <div>
                        <i class="fa-solid fa-phone me-2"></i>
                        <a href="tel:0388794195" class="text-muted text-decoration-none">0388 794 195</a>
                    </div>
                </div>
                {{-- Quick Contact Buttons --}}
                <div class="footer-contact-actions d-flex flex-column gap-2">
                    <a href="tel:0388794195" 
                       class="btn btn-footer-call btn-sm d-flex align-items-center justify-content-center gap-2"
                       title="Gọi ngay hotline">
                        <i class="fa-solid fa-phone"></i>
                        <span>Gọi Ngay</span>
                    </a>
                    <a href="{{ getZaloLink('0388794195') }}" 
                       target="_blank"
                       rel="noopener"
                       class="btn btn-footer-zalo btn-sm d-flex align-items-center justify-content-center gap-2"
                       title="Chat Zalo với chúng tôi">
                        <i class="fa-brands fa-whatsapp"></i>
                        <span>Chat Zalo</span>
                    </a>
                </div>
            </div>

            {{-- Danh mục cho thuê --}}
            <div class="col-lg-3 col-md-6 mb-4 text-dark">
                <h5 class="fw-bold mb-3 fs-6">Danh Mục Cho Thuê</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('rentalHome.index', ['category' => ['Phòng']]) }}" 
                           class="text-muted text-decoration-none small"
                           title="Cho thuê phòng trọ giá rẻ">
                            <i class="fa-solid fa-chevron-right me-2 small"></i>
                            Phòng Trọ Giá Rẻ
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('rentalHome.index', ['category' => ['KTX']]) }}" 
                           class="text-muted text-decoration-none small"
                           title="Cho thuê KTX và Sleepbox">
                            <i class="fa-solid fa-chevron-right me-2 small"></i>
                            KTX/Sleepbox
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('rentalHome.index', ['category' => ['Nhà nguyên căn']]) }}" 
                           class="text-muted text-decoration-none small"
                           title="Cho thuê nhà nguyên căn">
                            <i class="fa-solid fa-chevron-right me-2 small"></i>
                            Nhà Nguyên Căn
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('rentalHome.index') }}" 
                           class="text-muted text-decoration-none small"
                           title="Xem tất cả phòng trọ cho thuê">
                            <i class="fa-solid fa-chevron-right me-2 small"></i>
                            Tất Cả Phòng Trọ
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Chính sách --}}
            <div class="col-lg-3 col-md-6 mb-4 text-dark">
                <h5 class="fw-bold mb-3 fs-6">Chính Sách</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('privacy.index') }}" 
                           class="text-muted text-decoration-none small"
                           title="Chính sách bảo mật thông tin">
                            <i class="fa-solid fa-chevron-right me-2 small"></i>
                            Chính Sách Bảo Mật
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('privacy.index') }}" 
                           class="text-muted text-decoration-none small"
                           title="Điều khoản sử dụng dịch vụ">
                            <i class="fa-solid fa-chevron-right me-2 small"></i>
                            Điều Khoản Dịch Vụ
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('contact.index') }}" 
                           class="text-muted text-decoration-none small"
                           title="Quy định đăng tin">
                            <i class="fa-solid fa-chevron-right me-2 small"></i>
                            Quy Định Đăng Tin
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Hỗ trợ --}}
            <div class="col-lg-3 col-md-6 mb-4 text-dark ">
                <h5 class="fw-bold mb-3 fs-6">Hỗ Trợ</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('contact.index') }}" 
                           class="text-muted text-decoration-none small"
                           title="Liên hệ với chúng tôi">
                            <i class="fa-solid fa-chevron-right me-2 small"></i>
                            Liên Hệ
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('contact.index') }}" 
                           class="text-muted text-decoration-none small"
                           title="Hướng dẫn đăng tin cho thuê">
                            <i class="fa-solid fa-chevron-right me-2 small"></i>
                            Hướng Dẫn Đăng Tin
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('contact.index') }}" 
                           class="text-muted text-decoration-none small"
                           title="Câu hỏi thường gặp">
                            <i class="fa-solid fa-chevron-right me-2 small"></i>
                            Câu Hỏi Thường Gặp
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="/sitemap.xml" 
                           class="text-muted text-decoration-none small"
                           title="Sơ đồ trang web">
                            <i class="fa-solid fa-chevron-right me-2 small"></i>
                            Sơ Đồ Trang Web
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Footer Bottom --}}
        <div class="row mt-4 pt-4 border-top">
            <div class="col-md-6 mb-3 mb-md-0">
                <p class="text-muted small mb-0">
                    © {{ date('Y') }} Nhatrototsaigon.com - Bản quyền thuộc về Nền tảng cho thuê phòng trọ uy tín.
                </p>
                <p class="text-muted small mb-0 mt-1">
                    Địa chỉ: TP. Hồ Chí Minh, Việt Nam
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="social-links d-flex justify-content-md-end justify-content-start gap-3 mb-2">
                    <a href="https://facebook.com/nhatrototsaigon" 
                       class="text-muted" 
                       aria-label="Theo dõi Nhatrototsaigon trên Facebook"
                       title="Facebook Nhatrototsaigon"
                       target="_blank"
                       rel="noopener noreferrer">
                        <i class="fa-brands fa-facebook fs-5"></i>
                    </a>
                    <!-- <a href="https://twitter.com/nhatrototsaigon" 
                       class="text-muted" 
                       aria-label="Theo dõi Nhatrototsaigon trên Twitter"
                       title="Twitter Nhatrototsaigon"
                       target="_blank"
                       rel="noopener noreferrer">
                        <i class="fa-brands fa-twitter fs-5"></i>
                    </a> -->
                    <a href="https://instagram.com/manhtuan.n7250" 
                       class="text-muted" 
                       aria-label="Theo dõi Nhatrototsaigon trên Instagram"
                       title="Instagram Nhatrototsaigon"
                       target="_blank"
                       rel="noopener noreferrer">
                        <i class="fa-brands fa-instagram fs-5"></i>
                    </a>
                    <!-- <a href="https://www.youtube.com/@nhatrototsaigon" 
                       class="text-muted" 
                       aria-label="Theo dõi Nhatrototsaigon trên YouTube"
                       title="YouTube Nhatrototsaigon"
                       target="_blank"
                       rel="noopener noreferrer">
                        <i class="fa-brands fa-youtube fs-5"></i>
                    </a> -->
                </div>
                <p class="text-muted small mb-0">
                    Kết nối với chúng tôi trên mạng xã hội
                </p>
            </div>
        </div>
    </div>
</footer>
