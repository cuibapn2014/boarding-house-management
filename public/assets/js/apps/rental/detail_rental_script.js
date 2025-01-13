$(document).ready(function (){
    var splide = new Splide( '#thumbnail-carousel', {
        arrows: false,
            fixedWidth : 100,
            fixedHeight: 60,
            gap        : 10,
            rewind     : true,
            pagination : false,
            isNavigation: true
    } ).mount();

    splide.on('active click', function(e) {
        const imgActive = $(e.slide).find('img');
        $('img.hero-image').attr('src', imgActive.data('src'));
    });

    $('img.hero-image.skeleton').removeClass('skeleton');
});
