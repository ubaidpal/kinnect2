@extends('layouts.default')
@section('content')

<!--Create Poll-->
    <div class="content-gray-title mb10">
        <h4>Create Poll</h4>
    </div>

    <div class="form-container">

    {!! Form::model($poll = new\App\Poll,['url' => 'polls']) !!}
        <div class="form-group field-item">
            {!! Form::label('title' ,'Poll Title') !!}
            {!! Form::text('title',null, ['class' => 'form-control','placeholder'=>'Title']) !!}
             <p id='error_msg_title' style="color:red; display:none;padding-top:10px;margin-left:50px">Battle Title, Please complete this field - it is required.</p>
        </div>

        <div class="form-group field-item">
            {!! Form::label('description' ,'Question / Statement') !!}
            {!! Form::textarea('description',null, ['class' => 'form-control','placeholder'=>'Write questions here']) !!}
        </div>


        <div class="form-group field-item">
            {!! Form::label('poll_option' ,'Poll Between') !!}
            {!! Form::text('poll_option[0]',null, ['class' => 'form-control fltL','placeholder'=>'Write Your Option Here']) !!}
            {!! Form::text('poll_option[1]',null, ['class' => 'form-control fltR','placeholder'=>'Write Your Option Here']) !!}
            <div class="moreForms" id="moreField"></div>
            <p id='error_msg_add' style="color:red; display:none;padding-top:10px;margin-left:200px">Please fill first two fields to add more.</p>
            <p id='error_msg' style="color:red; display:none;padding-top:40px;margin-left:150px">You must provide at least two possible answers.</p>
        </div>

        <div class="upload-photo clrfix mt15">
            <a class="btn btn-add" id="addField"> Add</a>
        </div>

        <div class="field-item">
           <label for="">View Privacy</label>
           <select name="auth_allow_view" id="">
               {{--<option value="PERM_EVERYONE">Who may see this poll?</option>--}}
                <option value="PERM_EVERYONE" selected>Registered Member</option>
                <option value="PERM_FRIENDS_AND_NETWORK">Friends and Network</option>
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
               <option value="PERM_FRIENDS_AND_NETWORK">Friends and Network</option>
               {{--<option value="PERM_FRIENDS_OF_FRIENDS">Friends of Friends</option>--}}
               <option value="PERM_FRIENDS">Friends Only</option>
               <option value="PERM_PRIVATE">Just Me</option>
           </select>
        </div>

         <div class="">
              {!! Form::hidden('search', false, ['id'=>'show-album']) !!}
              {!! Form::checkbox('search', true , ['id'=>'show-album'])!!}
              {!! Form::label('searchlabel' , 'Show this poll in search results') !!}
         </div>


        <div class="form-group save-changes">
            {!! Form::submit('Create Poll', ['class' => 'btn btn-primary form-control btn' ,'id'=>'Create-btn']) !!}
            <a href="{{URL::previous() }}" class="btn btn-grey ml10" id="Cancel-btn">Cancel</a>
        </div>
    {!! Form::close() !!}

    </div>

    <script>
    $(document).ready(function(){
        var max_fields= 10;

         var x = 2; //initial text box
        $("#addField").click(function(e){
              e.preventDefault();
                var val1 = $('input[name="poll_option[0]"]').val();
                var val2 = $('input[name="poll_option[1]"]').val();
            if(val1 == '' || val2 == ''){
                $('#error_msg').hide();
                $('#error_msg_add').show();
                return false;
            }
            else{
                $('#error_msg_add').hide();
            }
              if(x < max_fields){
                  x++;
                  if (x%2 == 0) {
                    $("#moreField").append('<div class="form-group">' +
                       '<input type="text" name="poll_option[]" class="fltR" style="margin-top: 10px" placeholder=" Write Your Option Here"/>' +
                       '</div>');
                  }
                  else{
                    $("#moreField").append('<div class="form-group">' +
                       '<input type="text" name="poll_option[]" class="fltL" style="margin-top: 10px" placeholder=" Write Your Option Here"/>' +
                       '</div>');
                  }

              }
              if (x == max_fields){
                $("#addField").hide();
              }
           });

        });

        $(document).ready(function(){
          $('#Create-btn').click(function(e){
            $('#moreField').find('Input:text').each(function(){
                var x= $(this).val();
                if(x==''){
                    $(this).attr('disabled',true);
                }
            });
            var valTitle = $('input[name="title"]').val();
            if(valTitle == ''){
                $('#error_msg_title').show();
                return false;
            }
            else{
                $('#error_msg_title').hide();
            }
            var val1 = $('input[name="poll_option[0]"]').val();
            var val2 = $('input[name="poll_option[1]"]').val();
             if ((val1 == '') || (val2 == '')){
                $('#error_msg_add').hide();
                $('#error_msg').show();
                return false;
             }
             else{
                 $('#error_msg').hide();
             }
            return true;
          });
        });

    </script>
@stop()
