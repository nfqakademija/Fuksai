/**
 * Created by artur on 11/29/16.
 */
document.addEventListener("DOMContentLoaded",
    function() {
        var div, n,
            v = document.getElementsByClassName("youtube-player");
        for (n = 0; n < v.length; n++) {
            div = document.createElement("div");
            div.setAttribute("data-id", v[n].dataset.id);
            div.innerHTML = labnolThumb(v[n].dataset.id);
            div.onclick = labnolIframe;
            v[n].appendChild(div);
        }
    });

function labnolThumb(id) {
    var thumb = '<logo style="width: 300px; height: auto; margin: 27px 0px" src="https://camo.githubusercontent.com/2d1ecd0715be563b805dc2f210fa534ad899f9f5/68747470733a2f2f7331362e706f7374696d672e6f72672f666e61316d743770782f4c6f676f6d616b725f386b5f486c5f586a2e706e67">',
        play = '<div class="play"></div>';
    return thumb.replace("ID", id) + play;
}

function labnolIframe() {
    var iframe = document.createElement("iframe");
    iframe.setAttribute("src", this.dataset.id);
    iframe.setAttribute("frameborder", "0");
    iframe.setAttribute("allowfullscreen", "1");
    this.parentNode.replaceChild(iframe, this);
}
