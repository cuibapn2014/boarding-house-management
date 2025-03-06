$(document).ready(function (){
    var firstItem = $('#thumbnail-carousel img');
    $('.hero-container').append(generateImgOrVideo(firstItem));

    var splide = new Splide( '#thumbnail-carousel', {
        arrows: false,
            fixedWidth : 100,
            fixedHeight: 60,
            gap        : 10,
            rewind     : true,
            pagination : false,
            isNavigation: true
    } ).mount();

    splide.on('active, click', async function(e) {
        const active = await $(e.slide).find('img');
        const mediaType = await active.data('media-type');
        const ele = generateImgOrVideo(active);

        $('.hero-container img, .hero-container video').remove();
        $('.hero-container').append(ele);

        if(mediaType === 'video') {
            if($('video.hero-video source').first().attr('src') != active.data('src')) {
                $('video.hero-video source').first().attr('src', active.data('src'));
                document.querySelector('video.hero-video').load();
            };
            
            $('video.hero-video').removeAttr('hidden');
            $('img.hero-image').attr('hidden', true);
            return;
        }

        if($('img.hero-image').attr('src') != active.data('src')) {
            $('img.hero-image').attr('src', active.data('src'));
        }

        $('video.hero-video').attr('hidden', true);
        $('img.hero-image').removeAttr('hidden');
    });

    $('img.hero-image.skeleton').removeClass('skeleton');

    flatpickr('#createAppointmentModal #move_in_date', {
        enableTime: false,
        dateFormat: "d/m/Y",
        locale: 'vn',
        disableMobile: true,
        time_24h: true,
        allowInput: true
    });

    flatpickr('#createAppointmentModal #appointment_at', {
        enableTime: true,
        dateFormat: "d/m/Y H:i",
        locale: 'vn',
        disableMobile: true,
        time_24h: true,
        allowInput: true
    });

    $(document).on('click', '#createAppointmentModal #btn-submit', storeAppointment);
});


function generateImgOrVideo(item) {
    const type = item.data('media-type');
    const element = document.createElement(type.replace('image', 'img'));
    
    if(type == 'image') {
        $(element).attr('class', 'hero-image mb-4 w-100 skeleton');
        $(element).attr('loading', 'eager');
        $(element).attr('decoding', 'async');
        $(element).attr('src', item.data('src'));
        $(element).attr('alt', 'Hình ảnh phòng trọ tốt được chọn');
    } else{
        $(element).attr('class', 'hero-video');
        $(element).attr('autoplay', true);
        $(element).attr('loop', true);
        $(element).attr('muted', true);
        $(element).attr('controls', true);
        $(element).attr('aria-label', 'Hiển thị video mô tả phòng trọ tốt');
        
        $(element).append(`
            <source src="${item.data('src')}" type="video/mp4">
            <span>Trình duyệt của bạn không hỗ trợ video.</span>
        `)
    }

    return element;
}

const storeAppointment = async function(e) {
    e.preventDefault();

    const modal = $('#createAppointmentModal');
    const form = modal.find('#formCreateAppointment');
    const formData = new FormData(form[0]);
    const url = form.attr('action');
    const method = form.attr('method');
    const _token = $('meta[name="csrf_token"]').attr('content');

    $('.input-error-message').remove();

    const handleSuccess = function(response) {
        if(response.status === 'error') {
            GlobalHelper.toastError(response.message);
            return;
        }

        GlobalHelper.toastSuccess(response.message);
        form[0].reset();
        modal.modal('hide');
    }
    
    await ApiHelper
    .callApi(url, 
        method, 
        formData, 
        {
            "X-CSRF-TOKEN" : _token
        }, 
        {
            processData: false,
            contentType: false,
        }, 
        () => modal.find('#btn-submit').prop('disabled', true), 
        handleSuccess,
        null,
        () => modal.find('#btn-submit').prop('disabled', false)
    )
    .then(() => {})
    .catch(err => {});
}