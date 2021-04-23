define(
    [
        'App', 'backbone', 'marionette', 'moment',

        "collections/Posts"

    ],
    function
        (
            App, Backbone, Marionette, moment,

            PostsCollection
        )

    {
        /*

        - This should not be coupled via dependency injector, This will not require any views, rather all reviews
        should have this util in their require list.

        - Desktop/Mobile Controller (main controller) will include this in its dependency injector and NOT vice versa.

        - You can pass object of k2app in any view or other file on initialization from DesktopController to access
        that object

        */

    return Backbone.Marionette.Controller.extend({
        initialize:function (options) {

        },

        isValidURL: function(url){
            return url.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/);
        },

        ws:{
            isSuccess: function(data){
                return true;
            }
        },
        nl2br : function(str, is_xhtml) {
            var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display

            return (str + '')
                .replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
        },
        escapeHtml : function(text) {
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        },
        bindScrollToEnd: function (elem, callback, options) {
            $(elem).unbind('scroll');
            $(elem).bind('scroll', function () {
                if ($(this).scrollTop() +
                    $(this).innerHeight()
                    >= $(this)[0].scrollHeight) {
                    console.log('end of ' + elem + ' reached');
                    callback(options);
                }
            })
        },
        bindScrollToWindowEnd: function (callback, options) {
            $(window).scroll(function () {
                if ($(window).scrollTop() >= $(document).height() - $(window).height() - 714) {
                    callback(options);
                }
            });
        },
        getFormattedTime: function(timeStr){

            //var timezoneHoursDifference = 5;
            //var timezoneHoursDifference = Math.abs(new Date().getTimezoneOffset()/60);
            var timezoneHoursDifference = -(new Date().getTimezoneOffset()/60);
            if(moment().diff(moment(timeStr), "hours") > 23){
                return moment.utc(timeStr).add(timezoneHoursDifference, "hours").format("LLL");
            }else{
                return moment(timeStr).add(timezoneHoursDifference, "hours").fromNow()
            }
        },

        getAbsFormattedTime: function(timeStr){

            return moment.utc(timeStr).add(0, "hours").format("LLL");
        },

        getConfirmationPopup: function(options){

            var text = options.text;

            var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
            $("body").append(appendthis);
            $(".modal-overlay").fadeTo(500, 0.7);
            $('#mvc-global-confirmation-popup').fadeIn(500);
            $(window).resize(function () {
                $(".modal-box").css({
                    top: ($(window).height() - $(".modal-box").outerHeight()) / 3,
                    left: ($(window).width() - $(".modal-box").outerWidth()) / 2
                });
            });

            $(window).resize();
            $("#mvc-global-confirmation-popup .popup-text").text(text);
            $("#mvc-global-confirmation-popup .global-confirm-yes").unbind("click");
            $("#mvc-global-confirmation-popup .global-confirm-yes").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                options.confirmCallback(options.options);
                $('#mvc-global-confirmation-popup .global-confirm-cancel').trigger("click");
            });
        },
        getConfirmationPopupOverPopup : function(options){
            var text = options.text;
            $('#mvc-global-confirmation-popup-overpopup').fadeIn(500).css('z-index',200);
            $("#mvc-global-confirmation-popup-overpopup .js-modal-close").click(function (e) {
                e.preventDefault();
                e.stopPropagation();
                 $("#mvc-global-confirmation-popup-overpopup").fadeOut(500);
            });
            $(window).resize(function () {
                $(".modal-box").css({
                    top: ($(window).height() - $(".modal-box").outerHeight()) / 3,
                    left: ($(window).width() - $(".modal-box").outerWidth()) / 2
                });
            });
            $(window).resize();
            $("#mvc-global-confirmation-popup-overpopup .popup-text").text(text);
            $("#mvc-global-confirmation-popup-overpopup .global-confirm-yes").unbind("click");
            $("#mvc-global-confirmation-popup-overpopup .global-confirm-yes").click(function(e){
                options.confirmCallback(options.options);
                $("#mvc-global-confirmation-popup-overpopup").fadeOut(500);
            });
        },
        youtube_parser: function (url){
            var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
            var match = url.match(regExp);
            return (match&&match[7].length==11)? match[7] : false;
        },

        vimeo_parser: function(url){
            var vimeo_Reg = /https?:\/\/(?:www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|ondemand\/|ondemand\/([^\/]*)\/|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/;
            var match = url.match(vimeo_Reg);
            if (match){
                return match[4];
            }else{
                return false;
            }
        }



    });
});
