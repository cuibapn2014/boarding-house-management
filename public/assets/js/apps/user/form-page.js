"use strict";

// For User Create/Edit Page
const UserFormPage = {
    
    init: function() {
        this.bindEvents();
    },

    bindEvents: function() {
        const self = this;
        
        // Form validation and submit
        $('form').on('submit', function(e) {
            const form = $(this);
            const password = form.find('input[name="password"]').val();
            const passwordConfirmation = form.find('input[name="password_confirmation"]').val();
            
            // Validate password match
            if(password && password !== passwordConfirmation) {
                e.preventDefault();
                if(typeof GlobalHelper !== 'undefined') {
                    GlobalHelper.toastError('Mật khẩu xác nhận không khớp!');
                } else {
                    alert('Mật khẩu xác nhận không khớp!');
                }
                return false;
            }

            // Show loading state
            const submitBtn = form.find('button[type="submit"]');
            submitBtn.prop('disabled', true);
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...');
            
            // Let form submit naturally, but ensure button state is restored if there's an error
            setTimeout(() => {
                if(submitBtn.prop('disabled')) {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);
                }
            }, 5000);
        });

        // Phone number validation
        $('input[name="phone"]').on('keypress', function(e) {
            // Only allow numbers
            if(e.which < 48 || e.which > 57) {
                e.preventDefault();
            }
        });

        // Username validation - no spaces
        $('input[name="username"]').on('keypress', function(e) {
            // Don't allow spaces
            if(e.which === 32) {
                e.preventDefault();
            }
        });

        // Email validation
        $('input[name="email"]').on('blur', function() {
            const email = $(this).val();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if(email && !emailRegex.test(email)) {
                $(this).addClass('is-invalid');
                if(!$(this).next('.invalid-feedback').length) {
                    $(this).after('<div class="invalid-feedback">Email không hợp lệ</div>');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            }
        });

        // Password strength indicator
        $('input[name="password"]').on('keyup', function() {
            const password = $(this).val();
            const strength = self.checkPasswordStrength(password);
            
            // Remove existing strength indicator
            $(this).next('.password-strength').remove();
            
            if(password.length > 0) {
                let strengthClass = '';
                let strengthText = '';
                
                switch(strength) {
                    case 1:
                        strengthClass = 'text-danger';
                        strengthText = 'Yếu';
                        break;
                    case 2:
                        strengthClass = 'text-warning';
                        strengthText = 'Trung bình';
                        break;
                    case 3:
                        strengthClass = 'text-success';
                        strengthText = 'Mạnh';
                        break;
                }
                
                $(this).after(`<small class="password-strength ${strengthClass}">Độ mạnh: ${strengthText}</small>`);
            }
        });
    },

    checkPasswordStrength: function(password) {
        let strength = 0;
        
        if(password.length >= 8) strength++;
        if(password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if(password.match(/\d/)) strength++;
        if(password.match(/[^a-zA-Z\d]/)) strength++;
        
        return Math.min(strength, 3);
    }
};

// Initialize when document is ready
$(document).ready(function() {
    // Only init if we're on user create or edit page
    if($('form[action*="user"]').length > 0) {
        UserFormPage.init();
    }
});

