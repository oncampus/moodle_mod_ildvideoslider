/** @namespace */
var IVS = IVS || {};

IVS.videoSlide = '';
IVS.videoTitles = '';

IVS.transformVideos = function () {
    var r = $.Deferred();

    videos.forEach(function (v, i, ar) {
        var content = $('#module-' + v.id + ' .no-overflow').html();

        if (i == 0) {
            IVS.videoSlide += '<div class="video-slide" data-id="' + v.id + '" style="display: block">' + content + '</div>';
            IVS.videoTitles += '<li class="video-title-area clicked" data-id="' + v.id + '"><p class="video-title">' + v.title_1 + '</br><strong>' + v.title_2 + '</strong></p></li>';
        } else {
            IVS.videoSlide += '<div class="video-slide" data-id="' + v.id + '" style="display: block">' + content + '</div>';
            IVS.videoTitles += '<li class="video-title-area" data-id="' + v.id + '"><p class="video-title">' + v.title_1 + '</br><strong>' + v.title_2 + '</strong></p></li>';
        }

        $('#module-' + v.id).remove();
    });

    $('.video-slides').html(IVS.videoSlide);
    $('.video-titles-inner').html(IVS.videoTitles);

    return r;
};

IVS.initSly = function () {
    $frame = $('.video-titles-outter');
    $slidee = $frame.children('ul').eq(0);
    $wrap = $frame.parent();
    slideeWidth = 0;

    // Call Sly on frame
    $frame.sly({
        slidee: $slidee,
        horizontal: 1,
        itemNav: 'forceCentered',
        smart: 1,
        mouseDragging: 1,
        touchDragging: 1,
        releaseSwing: 1,
        startAt: 0,
        scrollBy: 1,
        speed: 300,
        elasticBounds: 1,
        easing: 'easeOutExpo',

        // Buttons
        prev: $wrap.find('.video-prev'),
        next: $wrap.find('.video-next')
    });

    $slidee.find('li').each(function () {
        slideeWidth += $(this).width();
    });

    $slidee.css('width', slideeWidth + 5);
};

IVS.reloadSly = function () {
    $frame.sly('reload');
    $slidee.css('width', slideeWidth + 5);
};

$(window).resize(function (e) {
    IVS.reloadSly();
});

IVS.hideVideos = function () {
    var counter = 1;

    $('.video-slide').each(function () {
        if (counter === 1) {
            $(this).css('display', 'block');
        } else {
            $(this).css('display', 'none');
        }

        counter++;
    });
};

$(document).ready(function () {
    IVS.transformVideos().done(IVS.initSly());

    setTimeout(function () {
        IVS.hideVideos();
    }, 700);

    $('.video-title-area').click(function () {
        var clickedTitleId = $(this).data('id');

        $('.video-title-area').each(function () {
            var titleId = $(this).data('id');

            if (titleId == clickedTitleId) {
                $(this).addClass('clicked');
            } else {
                $(this).removeClass('clicked');
            }
        });

        $('.video-slide').each(function () {
            var slideId = $(this).data('id');

            if (slideId == clickedTitleId) {
                $(this).css('display', 'block');
            } else {
                $(this).css('display', 'none');
            }
        });
    });

    $('.video-prev, .video-next').click(function () {
        IVS.reloadSly();
    });

});