/**
 * Created by artur on 11/29/16.
 */
document.addEventListener("DOMContentLoaded",
    function() {
        $('.youtube-image').on('click', function () {
            var element = $(this);
            var video = document.createElement('iframe');
            var path = element.data('id');
            video.setAttribute("src", path);
            video.setAttribute("frameborder", "0");
            video.setAttribute("allowfullscreen", "1");
            element.replaceWith(video);
        });
    });
