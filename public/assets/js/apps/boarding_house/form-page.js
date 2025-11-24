"use strict";

// For Create/Edit Page (not modal)
const BoardingHouseFormPage = {
    
    init: function() {
        this.initLocationData();
        this.initDropzone();
        this.initTagify();
        this.initTinyEditor();
        this.initDistrictSelect();
        this.bindEvents();
    },

    initLocationData: function() {
        if(localStorage.getItem('location_hcm')) {
            window.locationHCM = JSON.parse(localStorage.getItem('location_hcm'));
            this.loadDistricts();
            return;
        }

        const apiProvince = 'https://provinces.open-api.vn/api/?depth=3';
        
        fetch(apiProvince)
            .then(response => response.json())
            .then(data => {
                window.locationHCM = data.find(item => item.codename === 'thanh_pho_ho_chi_minh');
                localStorage.setItem('location_hcm', JSON.stringify(window.locationHCM));
                this.loadDistricts();
            })
            .catch(err => console.error('Error loading location data:', err));
    },

    loadDistricts: function() {
        const district = $('#district');
        const ward = $('#ward');
        const districtSelected = district.val();
        const wardSelected = ward.val();

        if(!window.locationHCM) return;

        district.empty();
        district.append(`<option value="">Chọn quận/huyện</option>`);
        
        window.locationHCM.districts.forEach(item => {
            district.append(`
                <option value="${item.name}" ${item.name === districtSelected ? 'selected' : ''}>${item.name}</option>
            `);
        });

        if(districtSelected) {
            this.loadWards(districtSelected, wardSelected);
        }
    },

    loadWards: function(districtName, wardSelected = '') {
        const ward = $('#ward');
        
        ward.empty();
        ward.append(`<option value="">Chọn phường/xã</option>`);
        
        if(!window.locationHCM) return;
        
        const district = window.locationHCM.districts.find(item => item.name === districtName);
        
        district?.wards?.forEach(item => {
            ward.append(`
                <option value="${item.name}" ${item.name === wardSelected ? 'selected' : ''}>${item.name}</option>
            `);
        });
    },

    initDistrictSelect: function() {
        const self = this;
        $('#district').on('change', function() {
            const selected = $(this).val();
            self.loadWards(selected);
        });
    },

    initDropzone: function() {
        if(typeof Dropzone !== 'undefined' && Dropzone.init) {
            Dropzone.destroy('.dropzone');
            Dropzone.init('.dropzone');
        }
    },

    initTagify: function() {
        const tagsInput = document.querySelector('#tags');
        if(tagsInput && typeof Tagify !== 'undefined') {
            new Tagify(tagsInput, {
                dropdown: {
                    enabled: 0
                }
            });
        }
    },

    initTinyEditor: function() {
        const contentEditor = $('#content');
        
        if(contentEditor.length === 0) {
            console.warn('Content textarea not found, skipping TinyMCE initialization');
            return;
        }
        
        if(typeof GlobalHelper === 'undefined' || !GlobalHelper.initTinyEditor) {
            console.warn('GlobalHelper.initTinyEditor not available');
            return;
        }
        
        try {
            GlobalHelper.initTinyEditor('#content');
            console.log('✅ TinyMCE initialized successfully');
        } catch(error) {
            console.error('❌ Error initializing TinyMCE:', error);
        }
    },

    getEditorContent: function() {
        // Try to get content from TinyMCE
        if(typeof tinymce !== 'undefined') {
            const editor = tinymce.get('content');
            if(editor && typeof editor.getContent === 'function') {
                try {
                    return editor.getContent();
                } catch(error) {
                    console.warn('Error getting TinyMCE content:', error);
                }
            }
        }
        
        // Fallback to textarea value
        const contentTextarea = document.getElementById('content');
        if(contentTextarea) {
            return contentTextarea.value || '';
        }
        
        return '';
    },

    getCsrfToken: function() {
        // Try standard Laravel meta tag (with dash)
        let meta = document.querySelector('meta[name="csrf-token"]');
        if(meta && meta.content) {
            return meta.content;
        }

        // Try alternative meta tag (with underscore) - backward compatibility
        meta = document.querySelector('meta[name="csrf_token"]');
        if(meta && meta.content) {
            return meta.content;
        }

        // Try to get from form input (Laravel sometimes adds this)
        const input = document.querySelector('input[name="_token"]');
        if(input && input.value) {
            return input.value;
        }

        console.error('CSRF token not found in meta tags or form inputs');
        return null;
    },

    bindEvents: function() {
        const self = this;
        
        // Form submit
        $('#formCreateBoardingHouse, #formEditBoardingHouse').on('submit', function(e) {
            e.preventDefault();
            
            // Trigger TinyMCE save before submit
            if(typeof tinymce !== 'undefined') {
                const editor = tinymce.get('content');
                if(editor) {
                    editor.save(); // Save content back to textarea
                }
            }
            
            self.submitForm($(this));
        });

        // Remove file
        $(document).on('click', '.remove-file', function(e) {
            e.preventDefault();
            self.removeFile($(this));
        });

        // Number separator
        // $('.number-separator').on('keyup', function() {
        //     const value = $(this).val().replace(/,/g, '');
        //     if(!isNaN(value) && value !== '') {
        //         $(this).val(parseInt(value).toLocaleString('en-US'));
        //     }
        // });
    },

    submitForm: function(form) {
        const formData = new FormData(form[0]);
        const url = form.attr('action');
        const method = form.attr('method') || 'POST';
        const submitBtn = form.find('button[type="submit"]');
        
        // Get content safely
        const content = this.getEditorContent();
        if(content !== null) {
            formData.set('content', content);
        }
        
        // Add files from Dropzone
        if(typeof Dropzone !== 'undefined' && Dropzone.files) {
            Dropzone.files.forEach(item => {
                formData.append('files[]', item);
            });
        }

        // Show global loading
        if(typeof GlobalHelper !== 'undefined' && GlobalHelper.showLoading) {
            GlobalHelper.showLoading('Đang lưu dữ liệu');
        }

        // Disable submit button
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...');

        // Get CSRF token safely
        const csrfToken = this.getCsrfToken();
        if(!csrfToken) {
            console.error('CSRF token not found!');
            GlobalHelper.toastError('Lỗi bảo mật: CSRF token không tồn tại!');
            submitBtn.prop('disabled', false);
            submitBtn.html('<i class="fas fa-save me-2"></i>Lưu Nhà trọ');
            if(typeof GlobalHelper !== 'undefined' && GlobalHelper.hideLoading) {
                GlobalHelper.hideLoading();
            }
            return;
        }

        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Hide loading
            if(typeof GlobalHelper !== 'undefined' && GlobalHelper.hideLoading) {
                GlobalHelper.hideLoading();
            }

            if(data.status === 'success') {
                // Show success message
                if(typeof GlobalHelper !== 'undefined') {
                    GlobalHelper.toastSuccess(data.message || 'Thành công!');
                }
                
                // Redirect to index page
                setTimeout(() => {
                    window.location.href = '/boarding-house';
                }, 1000);
            } else {
                const errors = data.errors;
                if(typeof GlobalHelper !== 'undefined') {
                    GlobalHelper.toastError(data.message || 'Có lỗi xảy ra!');
                }
                submitBtn.prop('disabled', false);
                submitBtn.html('<i class="fas fa-save me-2"></i>Lưu Nhà trọ');

                for(const key in errors) {
                    $(`#${key}`).after(`<span class="input-error-message text-danger text-sm">${errors[key][0]}<span>`);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Hide loading
            if(typeof GlobalHelper !== 'undefined' && GlobalHelper.hideLoading) {
                GlobalHelper.hideLoading();
            }

            if(typeof GlobalHelper !== 'undefined') {
                GlobalHelper.toastError('Có lỗi xảy ra!');
            }
            submitBtn.prop('disabled', false);
            submitBtn.html('<i class="fas fa-save me-2"></i>Lưu Nhà trọ');
        });
    },

    removeFile: function(element) {
        if(!confirm('Bạn có chắc chắn muốn xóa file này?')) {
            return;
        }

        const url = element.data('url');
        const csrfToken = this.getCsrfToken();
        
        if(!csrfToken) {
            GlobalHelper.toastError('Lỗi bảo mật: CSRF token không tồn tại!');
            return;
        }

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                element.closest('.file-uploaded').remove();
                if(typeof GlobalHelper !== 'undefined') {
                    GlobalHelper.toastSuccess(data.message || 'Xóa thành công!');
                }
            } else {
                if(typeof GlobalHelper !== 'undefined') {
                    GlobalHelper.toastError(data.message || 'Có lỗi xảy ra!');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if(typeof GlobalHelper !== 'undefined') {
                GlobalHelper.toastError('Có lỗi xảy ra!');
            }
        });
    }
};

// Initialize when document is ready
$(document).ready(function() {
    // Only init if we're on create or edit page
    if($('#formCreateBoardingHouse, #formEditBoardingHouse').length > 0) {
        BoardingHouseFormPage.init();
    }
});

