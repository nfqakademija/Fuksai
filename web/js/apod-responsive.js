$(window).load(function() {

    $('.image-container').each(function (i , obj){

        var boxheight = $(this).children('.picture-image').innerHeight();
        console.log(boxheight);
        console.log(this);
        $(this).children('.picture-text').outerHeight(boxheight);
    });
});
