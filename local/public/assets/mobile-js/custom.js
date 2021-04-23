var URL ;

$(document).ready(function(){
    URL = $('.nav-container').data('url');
    /*
     =Page Scroll Bar
     */
   // $("html").niceScroll();

    /*
     =Accordian Default
     */
    $('.accordionButton').click(function(){
        $('.accordionButton').removeClass('on');
        $('.accordionContent').slideUp('normal');
        if($(this).next().is(':hidden') == true){
            $(this).addClass('on');
            $(this).next().slideDown('normal');
        }
    });
    $('#search').click(function(){
        $('.search').toggle('100')
    });

    //Notifications
    /*var notification = new EventSource('notification/update');
     notification.onmessage = function(event){
     console.log(event.data);
     $('#msg').text(event.data.id);
     }*/
    $.ajaxSetup({
        headers : {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });
    setInterval(function(){
        get_notification();
    }, 10000);

    $('.trigger').on('click', function(event){
        event.stopPropagation();
        $(this).siblings('.drop').toggle();
    });
    $(document).click(function(){

        $('.drop').hide();

    });

});
function get_notification(){
    var is_message_page = 0;
    var conv_id         = 0;
    var pathname        = window.location.href;
    var pagename        = pathname.substr(pathname.lastIndexOf('/') + 1);
    if(pagename == 'messages' || pagename.toLowerCase().indexOf("messages?timezone") >= 0){
        is_message_page = 1;
        conv_id         = $('input[name="conv_id"]').val();
    }
    $.ajax({
        type : "POST",
        url : URL+'/notification/update',
        data : {is_get_message : is_message_page, conv_id : conv_id},
        success : function(data){
            if(data.count > 0){
                $('#notification-box').html('<span class="img-icm-badge" id="notification">' + data.count + '</span>');
            }else{
                $('#notification-box').empty();
            }

            if(data.unread_message > 0){
                $('#inbox').html('<span class="img-icm-badge">' + data.unread_message + '</span>');
            }else{
                $('#inbox').empty();
            }

            if(data.friend_request > 0){
                $('#kinnector-noti').html('<span class="img-icm-badge">' + data.friend_request + '</span>');
            }else{
                $('#kinnector-noti').empty();
            }

            if(pagename == 'messages' || pagename.toLowerCase().indexOf("messages?timezone") >= 0){
                if(data.messages_content != 0){
                    $('.conversation-messages-update').empty();
                    $('.conversation-messages-update').html(data.messages_content);
                    //$('.conversation-messages-update').append(data.messages_content);
                    // scroll();
                }

            }
        },
        error : function(status){

        }

    });
}
