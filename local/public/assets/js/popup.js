$(function () {

    var appendthis = ("<div class='modal-overlay js-modal-close'></div>");

    $(document).on('click','a[data-modal-id]',function (e) {
        e.preventDefault();
        $("body").append(appendthis);
        jQuery('body').css('overflow','hidden');
        //$('body').css({'overflow-y':'scroll', 'position':'fixed', 'width':'100%', });
        $(".modal-overlay").fadeTo(500, 0.7);



        //$(".js-modalbox").fadeIn(500);
        var modalBox = $(this).attr('data-modal-id');
        $('#' + modalBox).fadeIn($(this).data());
    });


    $(document).on('click',".js-modal-close, .modal-overlay",function (e) {
        e.preventDefault();
        $('body').css({'overflow-y':'scroll', 'position':'static','width':'auto'});
        $(".modal-box, .modal-overlay").fadeOut(500, function () {
            $(".modal-overlay").remove();
            $(".agree-overlay").remove();
        });

    });

    $(window).resize(function () {
        $(".modal-box").css({
            top: ($(window).height() - $(".modal-box").outerHeight()) / 3,
            left: ($(window).width() - $(".modal-box").outerWidth()) / 2
        });
    });

    $(window).resize();




});
