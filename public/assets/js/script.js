$(document).ready(function() {
    $('#loading').fadeOut();

    $(document).on('click', '.logo', () => window.location.href = '/');

    $(window).on('scroll', function(e) {
        const scrollTop = $(window).scrollTop();

        if(scrollTop > 300) {
            $('#scroll-to-top').fadeIn();
        } else {
            $('#scroll-to-top').fadeOut();
        }
    })

    $(window).trigger('scroll');

    $(document).on('click', '#scroll-to-top', function(e) {
        e.preventDefault();

        $(window).scrollTop(0);
    })
});