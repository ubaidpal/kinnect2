@extends('layouts.default')
@section('content')

    <!--Create Poll-->
    <div class="content-gray-title mb10">
        <h4>Create Poll</h4>
    </div>

    <div class="form-container">

    {!! Form::model($poll = new\App\Poll,['url' => 'polls','files' => true,'id' =>'createPoll']) !!}
        <div class="form-group field-item">
            {!! Form::label('title' ,'Poll Title') !!}
            {!! Form::text('title',null, ['class' => 'form-control','placeholder'=>'Title']) !!}
            <p id='error_msg_title' class="error">{{$errors->first('title')}}</p>
        </div>

        <div class="form-group field-item">
            {!! Form::label('description' ,'Question / Statement') !!}
            {!! Form::textarea('description',null, ['class' => 'form-control','placeholder'=>'Write questions here']) !!}
        </div>


        <div class="form-group field-item">
            {!! Form::label('poll_option' ,'Poll Between') !!}
            <div class="first">
                {!! Form::text('poll_option[0]',null, ['class' => 'form-control','placeholder'=>'Write Your Option Here']) !!}
                {!! Form::file('poll_option_file[0]',null) !!}
                <img src="#" alt="Option 1 image" width="150" style="display: none;" />
                <a href="#" class="close-img" style="display: none;">x</a>
            </div>
            <div class="second">
                {!! Form::text('poll_option[1]',null, ['class' => 'form-control','placeholder'=>'Write Your Option Here']) !!}
                {!! Form::file('poll_option_file[1]',null) !!}
                <img src="#" alt="Option 2 image" width="150" style="display: none;" />
                <a href="#" class="close-img" style="display: none;">x</a>
            </div>
            <div class="clrfix"></div>
            <div class="moreForms" id="moreField"></div>
            <div class="clrfix"></div>
            <p id='error_msg_add' style="color:red; display:none;margin-left:200px">Please fill first two fields to add more.</p>
            <?php
                $display = 'none';
                if($errors->has('poll_option[0]') || $errors->has('poll_option[1]')){
                    $display = 'black';
                }
            ?>
            <p id='error_msg' style="color:red; display:{{$display}};margin-left:150px">You must provide at least two possible answers.</p>
        </div>

        <div class="upload-photo clrfix mt15">
            <a class="btn btn-add" id="addField"> Add</a>
        </div>

        <div class="field-item">
           <label for="">View Privacy</label>
           <select name="auth_allow_view" id="">
               {{--<option value="PERM_EVERYONE">Who may see this poll?</option>--}}
                <option value="PERM_EVERYONE" selected>Registered Member</option>
                <!--<option value="PERM_FRIENDS_AND_NETWORK">Friends and Network</option>-->
                {{--<option value="PERM_FRIENDS_OF_FRIENDS">Friends of Friends</option>--}}
                <option value="PERM_FRIENDS">Friends Only</option>
                <option value="PERM_PRIVATE">Just Me</option>
           </select>
        </div>


        <div class="field-item">
           <label for="">Comment Privacy</label>
           <select name="auth_allow_comment" id="">
               {{--<option value="PERM_EVERYONE">Who may post comments on this poll?</option>--}}
               <option value="PERM_EVERYONE" selected>Registered Member</option>
               <!--<option value="PERM_FRIENDS_AND_NETWORK">Friends and Network</option>-->
               {{--<option value="PERM_FRIENDS_OF_FRIENDS">Friends of Friends</option>--}}
               <option value="PERM_FRIENDS">Friends Only</option>
               <option value="PERM_PRIVATE">Just Me</option>
           </select>
        </div>

         <div class="">
              {!! Form::hidden('search', true, ['id'=>'show-album']) !!}
              <?php //Form::checkbox('search', true , ['id'=>'show-album']) ?>
             <?php //Form::label('searchlabel' , 'Show this poll in search results') ?>
         </div>


        <div class="form-group save-changes">
            {!! Form::submit('Create Poll', ['class' => 'btn btn-primary form-control btn' ,'id'=>'Create-btn']) !!}
            <a href="{{URL::previous() }}" class="btn btn-grey ml10" id="Cancel-btn">Cancel</a>
        </div>
    {!! Form::close() !!}

    </div>
<style type="text/css">
    p.error{color:red;padding-top:10px;margin-left:10px}
</style>
<script src="{!! asset('local/public/assets/js/jquery.validate.min.js') !!}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        jQuery('#createPoll').validate({
            errorElement : 'p',
            errorPlacement : function(error,element){
                if(element.attr('name') == 'poll_option[0]' || element.attr('name') == 'poll_option[1]'){
                    jQuery('#error_msg').show();
                }else {
                    if (element.next().is('p')) {
                        element.next().remove();
                    }
                    error.insertAfter(element);
                }
            },
            rules : {
                'title' : {
                    required: true
                },
                'poll_option[0]' : {
                    required : function(){
                        jQuery('#error_msg_add').hide();
                        return true;
                    }
                },
                'poll_option[1]' : {
                    required : function () {
                        jQuery('#error_msg_add').hide();
                        return true;
                    }
                }
            },
            messages : {
                'title' : {
                    required: "{{trans('validation.required',['attribute' => 'title'])}}"
                }
            }
        });

        var max_fields= 10;

         var x = 2; //initial text box
        $("#addField").click(function(e){
            $('#error_msg').hide();
              e.preventDefault();
                var val1 = $('input[name="poll_option[0]"]').val();
                var val2 = $('input[name="poll_option[1]"]').val();
            if(val1 == '' || val2 == ''){
                $('#error_msg_add').show();
                return false;
            }
            else{
                $('#error_msg_add').hide();
            }
            if(x < max_fields){
                x++;
                if (x%2 == 0) {
                    $("#moreField").append('<div class="form-group mt10"><div class="first">' +
                            '<input type="text" name="poll_option[]" class="" placeholder=" Write Your Option Here"/>' +
                            '<input type="file" name="poll_option_file[]"><img src="#" alt="Option image" width="150" style="display: none;" /><a href="#" class="close-img" style="display: none;">x</a>'+
                            '</div></div>');
                }else{
                    $("#moreField").append('<div class="form-group mt10"><div class="first">' +
                       '<input type="text" name="poll_option[]" class="" placeholder=" Write Your Option Here"/>' +
                       '<input type="file" name="poll_option_file[]"><img src="#" alt="Option image" width="150" style="display: none;" /><a href="#" class="close-img" style="display: none;">x</a>'+
                       '</div></div>');
                }
            }
            if (x == max_fields){
                $("#addField").hide();
            }
        });
    });
    $('#Create-btn').click(function(e){
        $('#moreField').find('Input:text').each(function(){
            var x= $(this).val();
            if(x==''){
                $(this).attr('disabled',true);
            }
        });
    });
    jQuery(document).on('change','input[type="file"]',function (e) {
        readURL(this);
    });
    jQuery(document).on('click','.close-img',function (e) {
        e.preventDefault();
        jQuery(e.target).prev().hide();
        control = jQuery(e.target).prev().prev();
        control.replaceWith( control = control.clone( true ) );
    });
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(input).next('img').attr('src', e.target.result).show();
                $(input).next().next().show();
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@stop()