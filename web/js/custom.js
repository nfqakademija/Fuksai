$(document).ready(function(){

        $('#open').click(function(){
                $("html, body").animate({ scrollTop: 0 }, 800);
                return false;
        });
        $('#open').hide();

        $(window).scroll(function() {
                var top = $(this).scrollTop();
                if (top > 300) {
                        $('#open').fadeIn(800);

                } else if (top < 300) {
                        $('#open').fadeOut();

                }
        });

});
