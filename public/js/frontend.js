jQuery(document).ready(function($){
    var headerOffset = $('.header').offset().top;
//    alert(headerOffset);
    $(window).scroll(function(){
        var header = $('.header'),
            scroll = $(window).scrollTop();
            console.log(headerOffset);

        if (scroll > headerOffset){ 
            header.addClass('fixed');
        }else {
            header.removeClass('fixed');
        }
    });
});