/**
 * Created by Admin on 18-Feb-2016.
 */
var loading     = $('#loading');
var loading2    = $('#loading-2');
var msg_body    = $('#msg-body');
var message_box = $('#conversation-messages');
var is_new_conv = 0;
var URL;
$(document).ready(function(){
    URL = $('.nav-container').data('url');

    $(document).on('click', '.cht-send', function(){
        var form = $('#msg-form');
        var file = $('#postFiles').val();
        loading2.show();
        var data = new FormData(form[0]);
        if(msg_body.val() != '' || file != ''){
            msg_body.prop('disabled', true);
            msg_body.val('');

            $.ajax({
                type : 'POST',
                url : form.attr('action'),
                data : data,
                processData : false,
                contentType : false,
                success : function(data){
                    msg_body.prop('disabled', false);
                    scroll();
                    $('#postFiles').val('');
                    loading2.hide();
                    msg_body.focus();
                    if($(document).find("[data-msg]").length > 0){
                        window.location.href = URL+'/messages/'+data.profile_url+'/'+data.conv_id;
                    }else{
                        message_box.append(data);
                    }

                },
                error : function(error){
                    msg_body.prop('disabled', false);
                }
            });
        }

    });
    $(document).on('keypress', function(e){
        if(e.which == 13){
            e.preventDefault();
            if($('.rename-conv').is(':focus')){
                rename_conversation();
            }else if(msg_body.is(':focus')){
                $('.cht-send').click()
            }
        }
    });

    $('#chat-attachment').on('click', function(e){
        e.preventDefault();
        $('#postFiles').trigger('click');
    });

    $('#postFiles').change(function(){
        $('.cht-send').click();
    });
    var height = $(window).innerHeight();
    message_box.css({
        maxHeight : height - 180, minHeight : height - 180
    });
    $('.mainContainer').css({
        maxHeight : height - 50, minHeight : height - 50
    });
    $('.conv-box').css({
        maxHeight : height - 160
    });

    scroll();
    msg_body.focus();
});

scroll = function(){

    message_box.animate({
        scrollTop : message_box.prop("scrollHeight")
    }, 0);
};
