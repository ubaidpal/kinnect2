/**
 * Created by Admin on 29-Feb-2016.
 */
var loading    = $('#loading-2');
var msg_body   = $('#msg-body');
var form       = $('#msg-form');
var messageBox = $('#messageBox');
var no_message = $('#no-messages');

$(document).ready(function(){
    //scroll to bottom
    //scroll();

    $.ajaxSetup({
        headers : {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.cht-send', function(e){

        e.preventDefault();
        var file = $('#postFiles').val();
        loading.show();
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
                    no_message.remove();
                    msg_body.prop('disabled', false);
                    messageBox.append(data);
                    loading.hide();
                    $('#postFiles').val('');
                },
                error : function(error){
                    msg_body.prop('disabled', false);
                    $('[data-form="create-new-message"]').remove();
                    loading.hide();
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

        //$('.cht-send').click();
    })
});
