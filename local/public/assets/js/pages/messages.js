/**
 * Created by Admin on 06-1-16.
 */
var loading             = $('#loading');
var loading_2           = $('#loading-2');
var message_box         = $('#conversation-messages');
var reply_form          = $('.reply-discussions');
var msg_body            = $('#msg-body');
var new_message_trigger = $('#trigger');
var chat_title;
var is_new_conv         = 0;
$(document).ready(function(){
    //scroll to bottom
    scroll();

    $.ajaxSetup({
        headers : {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Click on Conversation to show messages

    $(document).on('click', '.conversation', function(e){
        var selector = $('.conversation');
        if($(e.target).hasClass('leave-conv-a')){
            return false;
        }
        selector.removeClass('active');
        var $this = $(this);
        $this.addClass('active');
        var copyConv = $(this).parent().clone();

        chat_title = $(this).find('p.usr-msg-title').text();
        $('.message-thread-title').text(chat_title);
        $('.rename-conv').val($.trim(chat_title));
        var target = $('#conversation-messages');
        target.empty();
        var url = $(this).data('url');
        loading.show();
        $.ajax({
            type : 'POST', url : url, cache : true, async : false, dataType : 'html', success : function(data){
                reply_form.show();
                //console.log(data);
                $this.parent().remove();

                $('.conv-box').prepend(copyConv);
                target.html(data);
                loading.hide();
                $('#all-friends').hide();
                msg_body.focus();
                message_box.show();
                scroll();
                $('.message-thread-title').show();

            }
        });
    });

    //Search within conversations
    var selector_conversation = $('#search-conversation');
    selector_conversation.unbind('keyup').bind('keyup', function(){
        var val       = $(this).val();
        var search_in = $('.conversation');
        search_in.hide();
        var i = 0;
        $('.not-found').remove();
        search_in.each(function(index, element){
            var text = $(element).find('p.usr-msg-title').text();
            if(text.toLowerCase().indexOf(val) >= 0){
                $(this).show();
                i ++;
            }else{
                //console.log('no')
            }
        });
        if(i == 0){
            $('<div class="user-msgs-box not-found" style="text-align: center">' + '<h3 >Not Found</h3>' + '<p>No people or conversations named ' + val + '</p>' + '</div>').insertAfter('#search-form');
        }
    });

    // Send Message
    //var send_selector = $('.cht-send');
    $(document).on('click', '.cht-send', function(){
        var form = $('#msg-form');
        var file = $('#postFiles').val();
        loading_2.show();
        var data = new FormData($('#msg-form')[0]);
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

                    //If conversation is closed
                    if(data.is_closed && data.is_closed == 1){
                        alert(data.msg);
                    }
                    msg_body.prop('disabled', false);
                    $('[data-form="create-new-message"]').remove();

                    reply_form.show();
                    //$('.new-message-field').remove();
                    $('#all-friends').hide();
                    if(! message_box.hasClass('conversation-messages-update')){
                        message_box.empty();
                        message_box.addClass('conversation-messages-update');
                    }

                    message_box.animate({
                        scrollTop : message_box.scrollHeight
                    }, 300);
                    $('#postFiles').val('');
                    $('.message-btn').show();
                    $('#close-new-message').hide();
                    loading_2.hide();
                    msg_body.focus();
                    message_box.show();
                    if(is_new_conv == 1){
                        is_new_conv = 0;
                        message_box.append(data.messages);
                        $('#conv-' + data.conv_id).remove();
                        $('#no-message').remove();
                        $('.conv-box').prepend(data.conv_head);
                        $('#friends').find('option:selected').removeAttr("selected");
                        $('.conv-id').val(data.conv_id);
                        jQuery('#notification_last_update_time').val(data.last_updated);
                    }else{
                        message_box.append(data);
                        jQuery('#notification_last_update_time').val(jQuery(data).find('.last_update_time').val());
                    }
                    scroll();
                },
                error : function(error){
                    msg_body.prop('disabled', false);
                    $('[data-form="create-new-message"]').remove();
                }
            });
        }
    });

    // New Message
    var new_message = $('#new-message');
    new_message.click(function(){
        $('#friends').tokenize().clear();
        loading.show();
        $('#conversation-messages').hide();
        jQuery('#old_conversation_id').val(jQuery('.conv-id').val());
        $('.conv-id').val('');
        loading.hide();
        $('.message-thread-title').hide();
        $('#all-friends').show();
        $('.message-btn').hide();
        $('#close-new-message').show();
        //$('#conversation-messages').html(data);
        message_box.removeClass('conversation-messages-update');
        is_new_conv = 1;
        reply_form.show();

        //Message filter
        $('.messages-conv').show();
        $('.dispute').hide();
        $('.msg-filter').removeClass('active');
        $("[data-type=messages-conv]").addClass('active');

    });

    // Leave Conversation
    var leave_conversation = $('.leave-conversation');
    $(document).on('click', '.leave-conversation', function(e){
        e.preventDefault();

        if(confirm('Are you sure to leave conversation?')){
            var $this = $(this);
           $this.parents('.conv-for').remove();
           // var head = $this.parents('.conv-for').siblings('.conv-for');
            var head = $('.conv-for.messages-conv:first .conversation');


            loading.show();
            $.ajax({
                type : 'GET', url : $(this).data('url'), success : function(data){
                    loading.hide();
                    //$(".conv-box").children().first().trigger('click');
                    head.trigger('click')
                }
            });
        }
    });

    $('#trigger').click(function(event){

        //event.stopPropagation();
        $(this).hide();
        $('.rename-conv').focus(function(){
            $(this).select();
        });
        $('#rename').show();

    });

    $('#cancel-btn').click(function(e){
        e.preventDefault();
        $('#rename').hide();

    });

    $('#chat-attachment').on('click', function(e){
        e.preventDefault();
        $('#postFiles').trigger('click');
    });

    $('#postFiles').change(function(){
        $('.cht-send').click();
        /*msg_body.prop('disabled', true);
         loading_2.show();
         var formData = new FormData($('#msg-form')[0]);
         $.ajax({
         type:'POST',
         url:URL+'/messages/upload-attachment',
         data: formData,
         async: false,
         success: function (data) {
         msg_body.prop('disabled', false);
         loading_2.hide();
         if(data == 'invalid_file'){
         alert('Invalid file');
         }else{
         message_box.append(data);
         }

         },
         cache: false,
         contentType: false,
         processData: false
         });*/
    })

    $('.chat-trigger').click(function(){

        $('.conv-chat-trigger').data('group', $('#groupId').val());
        $('.conv-chat-trigger').data('user', $('#userForChat').val());
        $('.conv-chat-trigger').data('type', $('#chat_type').val());

        $('.conv-chat-trigger').trigger('click');
    });
    var height = $(window).innerHeight();
    message_box.css({
        maxHeight : height - 250, minHeight : height - 250
    });

    $('.new-message-field').css({
        maxHeight : height - 250, minHeight : height - 250
    });
    $('.mainContainer').css({
        maxHeight : height - 50, minHeight : height - 50
    });
    $('.conv-box').css({
        maxHeight : height - 206
    });

    // Hide New message BOX
    $('#close-new-message').click(function(){
        $('.message-btn').show();
        $(this).hide();
        $('#all-friends').hide();
        $('#conversation-messages').show();
        $('.message-thread-title').show();
        jQuery('.conv-id').val(jQuery('#old_conversation_id').val());
        message_box.addClass('conversation-messages-update');
        reply_form.show();
        $('#friends').find('option:selected').removeAttr("selected");
        is_new_conv = 0;
    })


    // Message fiilter
    $('.msg-filter').click(function(e){
        e.preventDefault();
        var type = $(this).data('type');
        $('.msg-filter').removeClass('active');
        $(this).addClass('active');
        $('.conv-for').hide();
        $('.'+type).show();
    })

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

$(document).mouseup(function(e){
    var container = $("#rename");
    var clickBtn  = $("#trigger");
    if(! container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0 || clickBtn.is(e.target)) // ... nor a descendant of the
    // container
    {
        //container.hide();
    }

});

function rename_conversation(){
    loading.show();
    var rename_conversation = $('#rename-conversation');
    if(rename_conversation.find('input[name="name"]').val() != ''){
        $.ajax({
            type : 'POST',
            url : rename_conversation.attr('action'),
            data : rename_conversation.serialize(),
            success : function(data){
                loading.hide();
                $('#rename').hide();
                $('#trigger').show();
                $('#trigger').text(rename_conversation.find('input[name="name"]').val());

            }
        });
    }
}
/*$(document).ajaxSuccess(function(){
 $('#friends').tokenize({
 placeholder: 'Type to select friend'
 });
 })*/
function scroll(){
    message_box.animate({
        scrollTop : message_box.prop("scrollHeight")
    }, 0);
}
