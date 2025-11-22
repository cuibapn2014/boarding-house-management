@extends('master')
@section('title', 'Liên Hệ - Nhatrototsaigon')
@push('css')
    <link rel="preload" href="{{ asset('assets/images/hero-background.webp') }}" as="image"/>
    <link rel="stylesheet" href="{{ asset('assets/css/apps/contact/style.css') }}">
@endpush
@section('content')
@include('components.hero')

<div class="contact-section py-5">
    <div class="container">
        <!-- Section Header -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="contact-title fw-bold mb-3">Liên Hệ Với Chúng Tôi</h1>
                <p class="text-muted lead">Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Contact Information -->
            <div class="col-lg-4 col-md-12">
                <div class="contact-info-wrapper h-100">
                    <div class="contact-info-card shadow-sm border-0 h-100 p-4">
                        <h3 class="h4 fw-bold mb-4">Thông Tin Liên Hệ</h3>
                        
                        <!-- Address -->
                        <div class="contact-info-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="contact-icon me-3">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-semibold">Địa chỉ</h5>
                                    <p class="text-muted mb-0">TP. Hồ Chí Minh, Việt Nam</p>
                                </div>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="contact-info-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="contact-icon me-3">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-semibold">Điện thoại</h5>
                                    <a href="tel:0388794195" class="text-decoration-none text-success">0388 794 195</a>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="contact-info-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="contact-icon me-3">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-semibold">Email</h5>
                                    <a href="mailto:nmtworks.7250@gmail.com" class="text-decoration-none text-success">nmtworks.7250@gmail.com</a>
                                </div>
                            </div>
                        </div>

                        <!-- Working Hours -->
                        <div class="contact-info-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="contact-icon me-3">
                                    <i class="fa-solid fa-clock"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-semibold">Giờ làm việc</h5>
                                    <p class="text-muted mb-1">Thứ 2 - Thứ 7: 8:00 - 20:00</p>
                                    <p class="text-muted mb-0">Chủ nhật: 9:00 - 18:00</p>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="social-links mt-4 pt-3 border-top">
                            <h5 class="mb-3 fw-semibold">Kết nối với chúng tôi</h5>
                            <div class="d-flex gap-3">
                                <a href="#" class="social-link" aria-label="Facebook">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                                <a href="#" class="social-link" aria-label="Zalo">
                                    <i class="fa-solid fa-phone"></i>
                                </a>
                                <a href="#" class="social-link" aria-label="Telegram">
                                    <i class="fa-brands fa-telegram"></i>
                                </a>
                                <a href="mailto:nmtworks.7250@gmail.com" class="social-link" aria-label="Email">
                                    <i class="fa-solid fa-envelope"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8 col-md-12">
                <div class="contact-form-wrapper">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-4 p-md-5">
                            <h3 class="h4 fw-bold mb-4">Gửi Tin Nhắn</h3>
                            
                            <!-- Success Message -->
                            <div id="successMessage" class="alert alert-success alert-dismissible fade" role="alert">
                                <i class="fa-solid fa-circle-check me-2"></i>
                                <span id="successText"></span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                            <!-- Error Message -->
                            <div id="errorMessage" class="alert alert-danger alert-dismissible fade" role="alert">
                                <i class="fa-solid fa-circle-exclamation me-2"></i>
                                <span id="errorText"></span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                            <form id="contactForm" action="{{ route('contact.store') }}" method="POST">
                                @csrf
                                
                                <div class="row g-3">
                                    <!-- Name Field -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label fw-semibold">
                                                <i class="fa-solid fa-user me-1 text-success"></i>
                                                Họ và Tên <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   id="name" 
                                                   name="name" 
                                                   class="form-control form-control-lg" 
                                                   placeholder="Nguyễn Văn A" 
                                                   required 
                                                   autofocus>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <!-- Email Field -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label fw-semibold">
                                                <i class="fa-solid fa-envelope me-1 text-success"></i>
                                                Email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" 
                                                   id="email" 
                                                   name="email" 
                                                   class="form-control form-control-lg" 
                                                   placeholder="email@example.com" 
                                                   required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <!-- Phone Field -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="form-label fw-semibold">
                                                <i class="fa-solid fa-phone me-1 text-success"></i>
                                                Số Điện Thoại
                                            </label>
                                            <input type="tel" 
                                                   id="phone" 
                                                   name="phone" 
                                                   class="form-control form-control-lg" 
                                                   placeholder="0388794195">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <!-- Subject Field -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="subject" class="form-label fw-semibold">
                                                <i class="fa-solid fa-tag me-1 text-success"></i>
                                                Tiêu Đề
                                            </label>
                                            <input type="text" 
                                                   id="subject" 
                                                   name="subject" 
                                                   class="form-control form-control-lg" 
                                                   placeholder="Vấn đề cần hỗ trợ">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <!-- Message Field -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="message" class="form-label fw-semibold">
                                                <i class="fa-solid fa-message me-1 text-success"></i>
                                                Nội Dung <span class="text-danger">*</span>
                                            </label>
                                            <textarea id="message" 
                                                      name="message" 
                                                      class="form-control form-control-lg" 
                                                      placeholder="Nhập nội dung tin nhắn của bạn..." 
                                                      rows="6" 
                                                      required></textarea>
                                            <div class="form-text">
                                                <small class="text-muted">
                                                    <span id="charCount">0</span>/2000 ký tự
                                                </small>
                                            </div>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn">
                                            <i class="fa-solid fa-paper-plane me-2"></i>
                                            <span class="btn-text">Gửi Tin Nhắn</span>
                                            <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    const messageField = document.getElementById('message');
    const charCount = document.getElementById('charCount');

    // Character counter
    messageField.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;
        
        if (count > 2000) {
            this.value = this.value.substring(0, 2000);
            charCount.textContent = 2000;
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        successMessage.classList.remove('show');
        errorMessage.classList.remove('show');

        // Show loading state
        submitBtn.disabled = true;
        btnText.textContent = 'Đang gửi...';
        spinner.classList.remove('d-none');

        // Submit form
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Show success message
                document.getElementById('successText').textContent = data.message;
                successMessage.classList.add('show');
                
                // Reset form
                form.reset();
                charCount.textContent = '0';
                
                // Scroll to success message
                successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                throw new Error(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            // Handle validation errors
            if (error.errors) {
                Object.keys(error.errors).forEach(key => {
                    const input = document.getElementById(key);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = input.nextElementSibling;
                        if (feedback && feedback.classList.contains('invalid-feedback')) {
                            feedback.textContent = error.errors[key][0];
                        }
                    }
                });
            } else {
                // Show error message
                document.getElementById('errorText').textContent = error.message || 'Đã xảy ra lỗi. Vui lòng thử lại sau.';
                errorMessage.classList.add('show');
                errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        })
        .finally(() => {
            // Reset button state
            submitBtn.disabled = false;
            btnText.textContent = 'Gửi Tin Nhắn';
            spinner.classList.add('d-none');
        });
    });
});
</script>
@endpush
@endsection