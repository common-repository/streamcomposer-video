/**
 * Created by jarl on 1/7/17.
 */
jQuery(function(){
    var embeds = jQuery('.streamcomposer-embed');
    var $window = jQuery(window);
    jQuery.each(embeds, function(idx, embed){
        var $embed = jQuery(embed);
        var overlay;
        var overlaydiv;

        var wrapper = $embed.find('.streamcomposer-videoWrapper');
        var videoIframe = $embed.find('iframe');

        var isPopup = videoIframe.attr('popup') === 'true';
        var mode = videoIframe.attr('player-mode');
        var ratioWidth = parseInt(videoIframe.attr('width'));
        var ratioHeight = parseInt(videoIframe.attr('height'));

        if (isPopup) {
            overlay = $embed.find('.streamcomposer-overlay');
            overlaydiv = $embed.find('.streamcomposer-overlay>div');
            $embed.find('.streamcomposer-popup').click(toggleOverlay);
            overlay.click(toggleOverlay);
            videoIframe.attr('src', '');
        }

        function toggleOverlay() {
            if (overlay.is(':visible')) {
                videoIframe.attr('src', '');
                overlay.hide();
            } else {
                videoIframe.attr('src', videoIframe.attr('framesrc'));
                overlay.show();
                adaptHeight();
            }
        }

        function adaptHeight() {
            if (isPopup && overlay && !overlay.is(':visible')) return;
            if (isPopup) {
                var maxHeight = Math.floor(0.9 * ($window.height() - 100));
                overlaydiv.css('max-width', calculateWidth(maxHeight) + 'px');
            }
            var height = calculateHeight(videoIframe.width());
            videoIframe.height(height + 'px');
            wrapper.height(height + 'px');
            wrapper.css('paddingBottom', 0);
        }

        function calculateHeight(width) {
            var height = 100;
            var playlist = mode !== 'video';
            var right = mode === 'playlistRight';
            var ratio = ratioHeight / ratioWidth;
            if (!playlist) {
                height = width * ratio;
            } else {
                if (right) {
                    height = (width - 140) * ratio;
                } else {
                    height = width * ratio + 128;
                }
            }
            return height;
        }

        function calculateWidth(height) {
            var width = 100;
            var playlist = mode !== 'video';
            var right = mode === 'playlistRight';
            var ratio = ratioHeight / ratioWidth;
            if (!playlist) {
                width = height / ratio;
            } else {
                if (right) {
                    width = height/ratio + 140;
                } else {
                    width = (height - 128) / ratio;
                }
            }
            return width;
        }

        jQuery(window).resize(adaptHeight);
        adaptHeight();
    });
    
});