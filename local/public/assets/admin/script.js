/**
 * Created by Admin on 07-Mar-2016.
 */
var URL;
$(document).ready(function(){
    URL = $('.header-main').data('url')+'/admin/';

    $.ajaxSetup({
        headers : {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    })

    $('.assign-claim').change(function(){
        var id = $(this).data('id');
        var $this = $(this);
        var arbitrator = $(this).val();
        $.ajax({
            type:'POST',
            url:URL+'claim/assign',
            data:{id:id,arbitrator:arbitrator},
            success:function(data){
                if(data == 1){
                    $('.task_inner_wrapper').prepend('<div id="display-success"> <img id="img"  alt="Success" />Claim assigned successfully</div>');
                }else if(data == 2){

                    $('.task_inner_wrapper').prepend('<div id="display-error"> <img id="img"  alt="Success" />You are not allowed to perform this action</div>');
                }
            },error:function(data){

                $('.task_inner_wrapper').append('<div id="display-error"> <img id="img"  alt="Success" />Oops! Something goes wrong. Please try again</div>');
            }
        })
    });

    $('#buyer').click(function(){
        if($(this).is(":checked")){
           $('#buyer-amount').show();
            $('#buyer-amount input').prop('required',true);
        }else{
            $('#buyer-amount').hide();
            $('#buyer-amount input').val('');
            $('#buyer-amount input').prop('required',false);
        }
    })
});
