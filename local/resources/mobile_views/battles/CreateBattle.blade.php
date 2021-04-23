@extends('layouts.default')
@section('content')
<style>
    #battle_brand_name {
        position: relative;
        top: -10px;
    }

    .brand_battle_image_icon {
        border-radius: 50%;
        margin-right: 5px;
        margin-top: 2px;
    }
    .select2-results{
        display: none;
    }
</style>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.full.js"></script>

<!--Create Battle-->
    <div class="content-gray-title mb10">
        <h4>Create Battle</h4>
    </div>

    <div class="form-container">
{{--,'onkeyup'=>'getBrand()'--}}
    {!! Form::model($battle = new\App\Battle,['url' => 'battles']) !!}
        <div class="field-item">
            {!! Form::label('title' ,'Battle Title') !!}
            {!! Form::text('title',null, ['class' => 'form-control','placeholder'=>'Title']) !!}
            <p id='error_msg_title' style="color:red; display:none;padding-top:10px;margin-left:50px">Battle Title, Please complete this field - it is required.</p>
        </div>

        <div class="field-item">
            {!! Form::label('description' ,'Question / Statement') !!}
            {!! Form::textarea('description',null, ['class' => 'form-control','placeholder'=>'Write questions here']) !!}
        </div>

        <div class="field-item overflowV" id="select2Form">
           {!! Form::label('brandtextfield[]' ,'Battle Between') !!}

           <div class="bBattle">
               @if(count($brands)>0)
                   <select name="select1" id="select1" class="form-control fltL" data-allow-clear="true" data-placeholder="Select an option">
                       @foreach($brands as $brand)
                       <option  value="{{$brand['image_src']."+_+".$brand['brand_id']}}">{{$brand['brand_name']}}</option>
                       @endforeach
                   </select>
               @endif
           </div>
           <b>VS</b>
           <div class="bBattle">
               @if(count($brands)>0)
                   <select name="select2" id="select2" class="form-control fltL" data-allow-clear="true" data-placeholder="Select an option">
                       @foreach($brands as $brand)
                           <option value="{{$brand['image_src']."+_+".$brand['brand_id']}}">{{$brand['brand_name']}}</option>
                       @endforeach
                   </select>
               @endif
           </div>
           <div class="clrfix">
            <p id='error_msg' style="color:red; display:none;padding-top:10px;margin-left:200px">You must provide at least two possible answers.</p>
            <p id='error_msg_dpl' style="color:red; display:none;padding-top:10px;margin-left:100px">Battle cannot be created between same brands.Please Select different Brands</p>
           </div>

        </div>

        <div class="field-item">
           <label for="">View Privacy</label>
           <select name="auth_allow_view" id="">
               {{--<option value="PERM_EVERYONE">Who may see this battle?</option>--}}
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
              {{--<option value="PERM_EVERYONE">Who may post comments on this battle?</option>--}}
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
             {!! Form::label('searchlabel' , 'Show this battle in search results',['id'=>'abc']) !!}
        </div>

        <div class="form-group save-changes">
            {!! Form::submit('Create Battle', ['class' => 'btn btn-primary form-control btn','id'=>'Create-btn']) !!}
            <a href="{{URL::previous() }}" class="btn btn-grey ml10" id="Cancel-btn">Cancel</a>
        </div>

      {!! Form::close() !!}
    </div>
<script>

</script>
<script>
function formatState (brandtextfield) {
  if (!brandtextfield.id) { return brandtextfield.text; }

    var a = brandtextfield.id;
    var imageSrc = a.split("+_+");

    var $brandtextfield = $(
     '<span class="search_brand_item">' +
        '<span id="battle_brand_image">' +
            '<img class="brand_battle_image_icon" width="30" height="30" src="'+imageSrc[0]+'" />' +
        '</span>' +

         '<span id="battle_brand_name">'
         + brandtextfield.text +
         '</span>' +
     '</span>'
    );
    return $brandtextfield;
};

$("#select1").select2({
  placeholder: "Select an option",
  templateResult: formatState,
  allowClear: true,
  templateSelection:formatState,
});
$("#select2").select2({
  placeholder: "Select an option",
  templateResult: formatState,
  templateSelection:formatState,
  allowClear: true
});

//(document)('.select2-search select2-search--dropdown').on('onkeypress', function()
 $(document).on("keypress",".select2-search__field",function() {

    if($('.select2-search__field').val() == ""){
        $('.select2-results').hide();
    }
    else{
        $('.select2-results').show();
    }
});
</script>

 <script type="text/javascript">
  $(document).ready(function(){
    $(document).ready(function(){
        $('#Create-btn').click(function(e){
            //e.preventDefault();
            var valTitle = $('input[name="title"]').val();
            if(valTitle == ''){
                $('#error_msg_title').show();
                return false;
            }
            else{
                $('#error_msg_title').hide();
            }

            var val1 = $('#select1').val();
            val1 = val1.split("+_+");

            var val2 = $('#select2').val();
            val2 = val2.split("+_+");

            if ((val1[1] == '') || (val2[1] == '')){
                $('#error_msg_dpl').hide();
                $('#error_msg').show();
                return false;
             }
             else{
                $('#error_msg').hide();
             }
            if(val1[1] == val2[1]){
                $('#error_msg').hide();
                $('#error_msg_dpl').show();
                return false;
            }
            else{
                $('#error_msg_dpl').hide();
            }
            return true;
        });
    });
  });
 </script>

 <script type="text/javascript">
    $(document).ready(function(){
        $(document).ready(function(){
            $("#abc").click(function(e){
              var isChecked=jQuery('input[name=search]').is(':checked');
              if(isChecked) {
                      checkbox.prop('checked',false);
                      status.html('unchecked');
                  } else {
                      checkbox.prop('checked', true);
                      status.html('checked');
                  }
            })
        });
    });
 </script>


@stop()
