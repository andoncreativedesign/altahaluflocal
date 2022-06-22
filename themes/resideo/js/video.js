// Manage page header video and video section
if (jQuery('#pxp-hero-video').length > 0 || jQuery('.pxp-video-section').length > 0) {
    var tv;
    var video;
    var newVideoModal;

    function onYouTubeIframeAPIReady() {
        if (jQuery('#pxp-hero-video').length > 0) {
            tv = new YT.Player('pxp-hero-video', {
                events: {
                    'onReady': onPlayerReady, 
                    'onStateChange': onPlayerStateChange
                }, 
                videoId: jQuery('.pxp-hero-video').attr('data-id'),
                playerVars: {
                    autoplay: 1, 
                    modestbranding: 1, 
                    rel: 0, 
                    showinfo: 0, 
                    controls: 0, 
                    disablekb: 0, 
                    enablejsapi: 1, 
                    iv_load_policy: 3
                }
            });
        }

        if (jQuery('.pxp-video-section').length > 0) {
            var videoModal = jQuery('.pxp-video-section-modal');
            newVideoModal = videoModal.clone().appendTo('body');
            videoModal.remove();

            video = new YT.Player('pxp-video-section-modal-container', {
                videoId: jQuery('.pxp-video-section-modal').attr('data-id'),
                playerVars: {
                    autoplay: 0, 
                    modestbranding: 1, 
                    rel: 0, 
                    showinfo: 0, 
                    controls: 1, 
                    disablekb: 1, 
                    enablejsapi: 1, 
                    iv_load_policy: 3
                }
            });

            newVideoModal.on('shown.bs.modal', function (event) {
                var w = jQuery(this).find('.modal-body').width();

                video.setSize(w, w/16*9);
                video.playVideo();
            });
            newVideoModal.on('hidden.bs.modal', function (event) {
                video.stopVideo();
            });
        }
    }

    function onPlayerReady() {
        if (jQuery('.pxp-hero-video').attr('data-sound') == 'off') {
            tv.mute();
        }
    }

    function onPlayerStateChange(e) {
        if (e.data === 1) {
            jQuery('#pxp-hero-video').addClass('pxp-active');
        }
        if (e.data === YT.PlayerState.ENDED) {
            tv.playVideo(); 
        }
    }

    function vidRescale() {
        var w = jQuery(window).width();
        var h = jQuery(window).height();

        if (jQuery('#pxp-hero-video').length > 0) {
            if (w/h > 16/9) {
                tv.setSize(w, w/16*9);
                jQuery('.pxp-hero-video .pxp-screen').css({'left': '0px'});
            } else {
                tv.setSize(h/9*16, h);
                jQuery('.pxp-hero-video .pxp-screen').css({'left': -(jQuery('.pxp-hero-video .pxp-screen').outerWidth()-w)/2});
            }
        }
    }

    jQuery(window).on('load', function() {
        vidRescale();
    });

    jQuery(window).on('resize', function() {
        vidRescale();

        if (newVideoModal.hasClass('show')) {
            var w = newVideoModal.find('.modal-body').width();

            video.setSize(w, w/16*9);
        }
    });
}