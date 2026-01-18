@extends('components.modal')
@push('css')
<style>
    .appointment-form {
        padding: 0.5rem 0;
    }
    
    .appointment-form .form-group {
        margin-bottom: 1.25rem;
    }
    
    .appointment-form .form-label {
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .appointment-form .form-label i {
        color: #667eea;
        font-size: 0.875rem;
    }
    
    .appointment-form .form-label .required {
        color: #dc3545;
        margin-left: 0.25rem;
    }
    
    .appointment-form .form-control {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    
    .appointment-form .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    
    .appointment-form .form-control::placeholder {
        color: #a0aec0;
    }
    
    .appointment-form .input-group-icon {
        position: relative;
    }
    
    .appointment-form .input-group-icon i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
        z-index: 10;
        pointer-events: none;
    }
    
    .appointment-form .input-group-icon .form-control {
        padding-left: 2.75rem;
    }
    
    .appointment-form .form-text {
        font-size: 0.8rem;
        color: #718096;
        margin-top: 0.25rem;
    }
    
    .appointment-form .row {
        margin: 0;
    }
    
    .appointment-form .row > [class*="col-"] {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    
    .appointment-form .input-error-message {
        color: #dc3545;
        font-size: 0.8rem;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .appointment-form .input-error-message i {
        font-size: 0.75rem;
    }
    
    .appointment-info-box {
        background: #f7fafc;
        border-left: 4px solid #667eea;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .appointment-info-box p {
        margin: 0;
        font-size: 0.875rem;
        color: #4a5568;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .appointment-info-box i {
        color: #667eea;
    }
    
    @media (max-width: 576px) {
        .appointment-form .form-group {
            margin-bottom: 1rem;
        }
        
        .appointment-form .row > [class*="col-"] {
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }
    }
</style>
@endpush

@section('modal-body')
<form id="formCreateAppointment" action="{{ route('appointment.store', ['id' => $b_id, 'title' => $b_title]) }}" method="POST">
    @csrf
    
    <div class="appointment-form">
        <div class="appointment-info-box">
            <p>
                <i class="fa-solid fa-info-circle"></i>
                <span>Vui lòng điền đầy đủ thông tin để chủ nhà có thể liên hệ với bạn sớm nhất</span>
            </p>
        </div>
        
        <div class="row g-3">
            <!-- Họ tên -->
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label" for="customer_name">
                        <i class="fa-solid fa-user"></i>
                        Họ tên
                        <span class="required">*</span>
                    </label>
                    <div class="input-group-icon">
                        <i class="fa-solid fa-user"></i>
                        <input id="customer_name" 
                               maxlength="50" 
                               name="customer_name" 
                               class="form-control" 
                               type="text" 
                               placeholder="Nhập họ tên của bạn"
                               required>
                    </div>
                    <small class="form-text">Tối đa 50 ký tự</small>
                </div>
            </div>
            
            <!-- Số điện thoại -->
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label" for="phone">
                        <i class="fa-solid fa-phone"></i>
                        Số điện thoại/Zalo
                        <span class="required">*</span>
                    </label>
                    <div class="input-group-icon">
                        <i class="fa-solid fa-phone"></i>
                        <input id="phone" 
                               name="phone" 
                               class="form-control" 
                               maxlength="10" 
                               type="text" 
                               placeholder="Nhập số điện thoại (10 số)"
                               inputmode="numeric"
                               pattern="[0-9]{10}"
                               required>
                    </div>
                    <small class="form-text">Nhập số điện thoại 10 chữ số</small>
                </div>
            </div>
            
            <!-- Số người ở và Số xe -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="total_person">
                        <i class="fa-solid fa-users"></i>
                        Số người ở
                        <span class="required">*</span>
                    </label>
                    <div class="input-group-icon">
                        <i class="fa-solid fa-users"></i>
                        <input id="total_person" 
                               name="total_person" 
                               class="form-control" 
                               type="number" 
                               placeholder="0"
                               inputmode="numeric" 
                               value="1" 
                               min="1"
                               required>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="total_bike">
                        <i class="fa-solid fa-motorcycle"></i>
                        Số xe
                        <span class="required">*</span>
                    </label>
                    <div class="input-group-icon">
                        <i class="fa-solid fa-motorcycle"></i>
                        <input id="total_bike" 
                               name="total_bike" 
                               class="form-control" 
                               type="number" 
                               placeholder="0"
                               inputmode="numeric" 
                               value="0" 
                               min="0"
                               required>
                    </div>
                </div>
            </div>
            
            <!-- Ngày chuyển vào -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="move_in_date">
                        <i class="fa-solid fa-calendar-day"></i>
                        Ngày chuyển vào dự kiến
                    </label>
                    <div class="input-group-icon">
                        <i class="fa-solid fa-calendar-day"></i>
                        <input class="form-control flatpickr-date" 
                               type="text" 
                               id="move_in_date" 
                               name="move_in_date" 
                               placeholder="Chọn ngày (dd/mm/yyyy)"
                               data-date-format="d/m/Y">
                    </div>
                    <small class="form-text">Không bắt buộc</small>
                </div>
            </div>
            
            <!-- Ngày hẹn xem -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="appointment_at">
                        <i class="fa-solid fa-calendar-check"></i>
                        Ngày giờ hẹn xem
                        <span class="required">*</span>
                    </label>
                    <div class="input-group-icon">
                        <i class="fa-solid fa-calendar-check"></i>
                        <input class="form-control flatpickr-datetime" 
                               type="text" 
                               id="appointment_at" 
                               name="appointment_at" 
                               placeholder="Chọn ngày giờ (dd/mm/yyyy HH:mm)"
                               data-date-format="d/m/Y H:i"
                               required>
                    </div>
                    <small class="form-text">Chọn thời gian bạn muốn xem phòng</small>
                </div>
            </div>
            
            <!-- Ghi chú -->
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label" for="note">
                        <i class="fa-solid fa-note-sticky"></i>
                        Ghi chú
                    </label>
                    <textarea id="note" 
                              maxlength="255" 
                              name="note" 
                              class="form-control" 
                              rows="4"
                              placeholder="Nhập ghi chú thêm (nếu có)..."></textarea>
                    <small class="form-text">Tối đa 255 ký tự</small>
                </div>
            </div>
        </div>
    </div>
</form>
@stop

@push('js')
<script>
$(document).ready(function() {
    // Phone number formatting
    $('#createAppointmentModal #phone').on('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });
    
    // Character counter for textarea
    const noteTextarea = $('#createAppointmentModal #note');
    if (noteTextarea.length) {
        const maxLength = 255;
        const counter = $('<small class="form-text text-end d-block" id="note-counter">0/' + maxLength + ' ký tự</small>');
        noteTextarea.parent().append(counter);
        
        noteTextarea.on('input', function() {
            const length = $(this).val().length;
            counter.text(length + '/' + maxLength + ' ký tự');
            if (length > maxLength * 0.9) {
                counter.css('color', '#dc3545');
            } else {
                counter.css('color', '#718096');
            }
        });
    }
    
    // Re-initialize flatpickr when modal is shown (in case it was already initialized)
    $('#createAppointmentModal').on('shown.bs.modal', function() {
        // Flatpickr will be initialized by detail_rental_script.js
        // Just ensure inputs are ready
    });
});
</script>
@endpush