jQuery(document).ready(function($){
    var headerOffset = $('.header').offset().top;
    $(window).scroll(function(){
        var header = $('.header'),
            scroll = $(window).scrollTop();
        if (scroll > headerOffset){ 
            header.addClass('fixed');
        }else {
            header.removeClass('fixed');
        }
    });
		/* show message */
    function isEmpty( el ){
      return !$.trim(el.html())
    }
    if (!isEmpty($('#messages'))) {
        $("#messages").addClass("show");
    }
    setTimeout(function() {
        $("#messages").removeClass("show");
        setTimeout(function(){
            $("#messages").empty();
        }, 500);
      }, 4000);
    /* end show message */
});