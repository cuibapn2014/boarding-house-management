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

    splide.on('active, click', async function(e) {
        const active = await $(e.slide).find('img');
        const mediaType = await active.data('media-type');

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
});
