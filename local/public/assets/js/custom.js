// Create Ad -> Help & More Menu-btn

/* Ad Left Nav script*/
$(document).ready(function () {

    $('#help-more ul > li > a').click(function () {
        $('#help-more li').removeClass('active');
        $(this).closest('li').addClass('active');
        $(this).addClass('border-submenu');
        /* added for createAd->help&more->SUB-MENU */

        var checkElement = $(this).next();
        if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
            $(this).closest('li').removeClass('active');
            $(this).removeClass('border-submenu');
            /* removed for createAd->help&more->SUB-MENU */

            checkElement.slideUp('normal');
        }

        if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
            $('#help-more ul ul:visible').slideUp('normal');
            checkElement.slideDown('normal');
        }

        if ($(this).closest('li').find('ul').children().length == 0) {
            return true;
        } else {
            return false;
        }
    });
    $('.gen-faq-item .content-head p').click(function (event) {
        $(this).next('.content-text').slideToggle(200);
    });
});

/*Left and Rigt panel Stick = End*/
/*Profile Tooltip*/
$(document).ready(function () {
    var h = false;
    $("#profileLink").click(function () {
        if (h == false) {
            $("#popUp").fadeIn('fast');
            $("#popUpText").fadeIn(function () {
                h = true;
            });
        }
        if (h == true) {
            $("#popUp").fadeOut('fast');
            $("#popUpText").fadeOut(function () {
                h = false
            });
        }
    });
    var mouse_is_inside = false;


    $('#popUpText').hover(function () {
        mouse_is_inside = true;
    }, function () {
        mouse_is_inside = false;
    });

    $("body").mouseup(function () {
        if (!mouse_is_inside) {
            $("#popUp").fadeOut('fast');
            $("#popUpText").fadeOut(function () {
                h = false
            });
        }
    });
});

$(document).ready(function (e) {
    $('#profileLink').click(function () {
        if (($("#leaderboard").css("marginRight")) == '0px') {
            $("#leaderboard").animate({marginRight: '-220px'});
            $('#leaderboard-img').removeClass('active');
            $("#leaderboard-img").animate({marginRight: '0px'});
        }
        if (($("#feedback-left").css("marginLeft")) == '0px') {
            $("#feedback-left").animate({marginLeft: '-300px'});
            $("#feedback-img").removeClass('active');
            $("#feedback-img").animate({marginLeft: '0px'});
        }
    });

});

$(function () {
    window.URL = $('.header-main').data('url');
    var form_id = $('#feedback-form');
    var button = $('#feedback');
    form_id.submit(function (e) {
        var msg = $('#feedback-text');
        if (msg.val() == '') {
            msg.css({
                'border': '1px solid #a82828', 'box-shadow': '0 0 4px 1px red'
            });
            return false;
        }
        msg.css({
            'border': 'none', 'box-shadow': 'none'
        });
        e.preventDefault();
        button.text('Sending...');
        button.prop('disabled', true);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST", url: form_id.attr('action'), data: form_id.serialize(), success: function (data) {
                button.text('Comment');
                button.prop('disabled', false);
                msg.val('');
                msg.css({
                    'border': 'none', 'box-shadow': 'none'
                });
                $('.feedback-img').click();
            }
        })
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    setInterval(function () {
        get_notification();
    }, 10000);

    function get_notification() {
        var is_message_page = 0;
        var conv_id = 0;
        var pathname = window.location.href;
        var pagename = pathname.substr(pathname.lastIndexOf('/') + 1);
        if (pagename == 'messages' || pagename.toLowerCase().indexOf("messages?timezone") >= 0) {
            is_message_page = 1;
            conv_id = $('input[name="conv_id"]').val();
        }
        
        $.ajax({
            type: "POST",
            url: URL + '/notification/update',
            data: {
                is_get_message: is_message_page,
                conv_id: conv_id,
                last_update_time:jQuery('#notification_last_update_time').val(),
                repeat : 1,
            },
            success: function (data) {
                if (data.count > 0) {
                    $('#notifications-area').html('<span id="notification-header">' + data.count + '</span>');
                } else {
                    $('#notifications-area').empty();
                }

                if (data.unread_message > 0) {
                    $('#inbox').html('<span >' + data.unread_message + '</span>');
                } else {
                    $('#inbox').empty();
                }

                if (data.friend_request > 0) {
                    $('#kinnector-noti').html('<span >' + data.friend_request + '</span>');
                } else {
                    $('#kinnector-noti').empty();
                }
                if(typeof data.last_update_time != 'undefined' && data.last_update_time != '') {
                    jQuery('#notification_last_update_time').val(data.last_update_time);
                }
                if (pagename == 'messages' || pagename.toLowerCase().indexOf("messages?timezone") >= 0) {
                    if (data.messages_content != 0) {
                        jQuery('video').mediaelementplayer({});
                        if(data.last_update_time != '') {
                            $('.conversation-messages-update').append(data.messages_content);
                        }else {
                            $('.conversation-messages-update').empty();
                            $('.conversation-messages-update').html(data.messages_content);
                        }
                        //$('.conversation-messages-update').append(data.messages_content);
                        // scroll();
                    }

                }
            },
            error: function (status) {

            }

        });
    };

    var clickBtn = $('.click-button');
    var is_click = false;

    clickBtn.click(function (e) {
        e.preventDefault();
        var target = $(this).data('target');

        if (is_click == false) {
            $('#' + target).addClass('uiToggleDialog');

            clickBtn.find('span').remove();

            if ($(this).data('ajax') == true) {
                notification();
            }
        } else {
            is_click = false;
            $('#' + target).removeClass('uiToggleDialog');
        }

    });

    function notification() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST", url: URL + '/notification/mark-read', data: {notification: 1}, success: function (data) {
                $('#notifications_menu').html(data);
                is_click = true;
            }, error: function (status) {
            }
        });
    }

    $(document).mouseup(function (e) {
        var container = $("#kNotificationDialog");
        var clickBtn = $("#notifications-area");

        if (container.hasClass('uiToggleDialog')) {
            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0 && !clickBtn.is(e.target)) // ... nor a descendant of the
            // container
            {
                //container.hide();
                container.removeClass('uiToggleDialog');
                is_click = false;
                is_clicked = false;
            }
        }
    });
});

$(function () {
    if ($("#start_date").length > 0) {

        $("#start_date").datepicker({
            dateFormat: "yy-mm-dd", minDate: 0, onClose: function (selectedDate) {
                $("#end_date").datepicker("option", "minDate", selectedDate);
            }
        });

        $("#start_date_icon").click(function (evt) {
            evt.preventDefault();
            $("#start_date").click();
        });

        $("#end_date").datepicker({
            dateFormat: "yy-mm-dd",

            /* showOn: 'both',
             buttonImage: '{{asset('local/public/assets/images/img-Start-Time.png')}}',*/
            minDate: 0, onClose: function (selectedDate) {
                $("#start_date").datepicker("option", "maxDate", selectedDate);
            }
        });

        $("#end_date_icon").click(function (evt) {
            evt.preventDefault();
            $("#end_date").click();
        });
    }
});

$(document).ready(function () {

    window.URL = $('.header-main').data('url');

    /*Left and Rigt panel Stick = Start*/
    function fixMe(id) {

        var e = $(id);
        if (e.length == 0) {
            return
        }
        var lastScrollTop = 0;
        var firstOffset = e.offset().top;
        var lastA = e.offset().top;

        var isFixed = false;
        $(window).scroll(function (event) {
            if (isFixed) {
                return;
            }
            var a = e.offset().top;
            var b = e.height();

            var c = $(window).height() - 30;

            var d = $(window).scrollTop();
            if (b <= c - a) {
                e.css({position: "fixed"});
                isFixed = true;
                return;
            }
            if (d > lastScrollTop) { // scroll down
                if (e.css("position") !== "fixed" && c + d >= a + b) {
                    e.css({position: "fixed", bottom: 0, top: "auto"});
                }
                if (a - d >= firstOffset) {
                    e.css({position: "absolute", bottom: "auto", top: lastA});
                }
            } else { // scroll up
                if (a - d >= 50 && d >= 290 && typeof e.data('page') !== 'undefined') {
                    if (e.css("position") !== "fixed") {
                        e.css({position: "fixed", bottom: "auto", top: 50});
                    }
                } else if (a - d >= firstOffset) {
                    // if (e.css("position") !== "fixed") {
                    e.css({position: "fixed", bottom: "auto", top: firstOffset});
                    // }
                } else {
                    if (e.css("position") !== "absolute") {
                        e.css({position: "absolute", bottom: "auto", top: lastA});
                    }
                }
            }
            lastScrollTop = d;
            lastA = a;
        });
    }

    function adFix(id) {

        var e = $(id);
        if (e.length == 0) {
            return
        }
        var lastScrollTop = 0;
        var firstOffset = e.offset().top;
        var lastA = e.offset().top;

        var isFixed = false;
        $(window).scroll(function (event) {
            /*if( isFixed ) {
             return;
             }*/
            var a = e.offset().top;
            var b = e.height();
            var c = $(window).height() - 30;

            var d = $(window).scrollTop();
            /* if( b <= c - a ) {
             e.css( {position: "fixed"} );
             isFixed = true;
             return;
             }*/
            if (typeof e.data('page') !== 'undefined') {

                if (d > lastScrollTop) { // scroll down

                    if (d >= 380) {

                        e.css({position: "fixed", bottom: "auto", top: 50});
                    }
                } else { // scroll up
                    if (d <= 380) {

                        e.css({position: "absolute", bottom: "auto", top: 'auto'});
                    }
                }
                lastScrollTop = d;
                lastA = a;
            }
        });
    }

    function storeFix(id) {

        var e = $(id);
        if (e.length == 0) {
            return
        }
        var lastScrollTop = 0;
        var firstOffset = e.offset().top;
        var lastA = e.offset().top;

        var isFixed = false;
        $(window).scroll(function (event) {
            /*if( isFixed ) {
             return;
             }*/
            var a = e.offset().top;
            var b = e.height();
            var c = $(window).height() - 30;

            var d = $(window).scrollTop();
            /* if( b <= c - a ) {
             e.css( {position: "fixed"} );
             isFixed = true;
             return;
             }*/
            if (typeof e.data('page') !== 'undefined') {

                if (d > lastScrollTop) { // scroll down

                    //if (d >= 300) {

                        e.css({position: "fixed", bottom: "auto", top: 50});
                    //}
                } else { // scroll up
                    if (a - d >= 50) {

                        //e.css({position: "absolute", bottom: "auto", top: ''});
                    }
                }
                lastScrollTop = d;
                lastA = a;
            }
        });
    }

    fixMe("#stick");
    adFix("#stickAdd");
    storeFix("#store-sidebar");
});
function scroll() {
    message_box.animate({
        scrollTop: message_box.prop("scrollHeight")
    }, 0);
}
