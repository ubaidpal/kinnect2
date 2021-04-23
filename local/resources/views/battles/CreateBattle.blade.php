@extends('layouts.default')
@section('content')
<style type="text/css">
    #battle_brand_name {
        position: relative;
        top: -10px;
    }
    .ui-autocomplete-loading { background:url('/local/public/images/ui-anim_basic_16x16.gif') no-repeat right center !important; }
    .brand_battle_image_icon {
        border-radius: 50%;
        margin-right: 5px;
        margin-top: 2px;
    }
    .select2-results{display: none;}
    p.error{color:red;padding-top:10px;margin-left:10px}
</style>

<!--Create Battle-->
    <div class="content-gray-title mb10">
        <h4>Create Battle</h4>
    </div>

    <div class="form-container">
{{--,'onkeyup'=>'getBrand()'--}}
    {!! Form::model($battle = new\App\Battle,['url' => 'battles','id' => 'createBattle']) !!}
        <div class="field-item">
            {!! Form::label('title' ,'Battle Title') !!}
            {!! Form::text('title',null, ['class' => 'form-control','placeholder'=>'Title']) !!}
            <p id='error_msg_title' style="color:red;padding-top:10px;margin-left:50px">{{$errors->first('title')}}</p>
        </div>

        <div class="field-item">
            {!! Form::label('description' ,'Question / Statement') !!}
            {!! Form::textarea('description',null, ['class' => 'form-control','placeholder'=>'Write questions here']) !!}
        </div>

        <div class="field-item overflowV" id="select2Form">
           {!! Form::label('brandtextfield[]' ,'Battle Between') !!}

           <div class="bBattle">
               <input type="text" class="form-control fltL" placeholder="Search brand" value="{{old('brand_1')}}" name="brand_1" id="brand_1" style="width: 317px">
               <input type="hidden" name="select1" id="select1">

           </div>
           <b>VS</b>
           <div class="bBattle">
               <input type="text" class="form-control fltL" placeholder="Search brand" value="{{old('brand_2')}}" name="brand_2" id="brand_2" style="width: 317px">
               <input name="select2" type="hidden" id="select2">
           </div>
           <div class="clrfix">
           <?php
           $display = 'none';
           $display_dpl = 'none';
           if($errors->has('select1') && $errors->has('select2')){
               $display = 'block';
           }elseif($errors->has('select2')){
               $display_dpl = 'block';
           }

           ?>
            <p id='error_msg' style="color:red; display:{{$display}};padding-top:10px;margin-left:200px">Please select the brands to create a battle .</p>
            <p id='error_msg_dpl' style="color:red; display:{{$display_dpl}};padding-top:10px;margin-left:100px">Battle cannot be created between the same brands.Please Select different Brands</p>
           </div>

        </div>

        <div class="field-item">
           <label for="">View Privacy</label>
           <select name="auth_allow_view" id="">
               {{--<option value="PERM_EVERYONE">Who may see this battle?</option>--}}
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
              {{--<option value="PERM_EVERYONE">Who may post comments on this battle?</option>--}}
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
            <?php //Form::label('searchlabel' , 'Show this battle in search results',['id'=>'abc']) ?>
        </div>

        <div class="form-group save-changes">
            {!! Form::submit('Create Battle', ['class' => 'btn btn-primary form-control btn','id'=>'Create-btn']) !!}
            <a href="{{URL::previous() }}" class="btn btn-grey ml10" id="Cancel-btn">Cancel</a>
        </div>

      {!! Form::close() !!}
    </div>
<script type="text/javascript" src="{!! asset('local/public/assets/js/jquery-ui.min.js') !!}"></script>
<script src="{!! asset('local/public/assets/js/jquery.validate.min.js') !!}"></script>

 <script type="text/javascript">
    $(document).ready(function(){

        jQuery.validator.addMethod("notEqual", function(value, element, param) {
            return this.optional(element) || value != $(param).val();
        }, "Please specify a different (non-default) value");

        jQuery('#createBattle').validate({
            errorElement : 'p',
            ignore: [],
            errorPlacement : function(error,element){
                if(jQuery('#select1').val() != '' && jQuery('#select1').val() == jQuery('#select2').val()){
                    jQuery('#error_msg_dpl').show();
                    jQuery('#error_msg').hide();
                }else if(element.attr('name') == 'select1' || element.attr('name') == 'select2'){
                    jQuery('#error_msg').show();
                    jQuery('#error_msg_dpl').hide();
                }
                if(element.attr('name') == 'title'){
                    error.insertAfter(element);
                }
            },
            rules : {
                'title' : {required:true},
                'select1' : {required:function () {
                    jQuery('#error_msg_dpl').hide();
                    return true;
                }},
                'select2' : {required:function () {
                    jQuery('#error_msg_dpl').hide();
                    return true;
                },notEqual:function () {
                    jQuery('#error_msg').hide();
                    return '#select1';
                }}
            },
            messages : {
                'title' : {
                    required : "{{trans('validation.required',['attribute' => 'title'])}}"
                }
            }
        });

        jQuery('#brand_1').autocomplete({
            source: "{{url('/getBrands')}}",
            minLength: 2,
            select: function( event, ui ) {
                jQuery('input[name="select1"]').val(ui.item.id);
            },
            open: function() {
                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            },
            change: function( event, ui ) {
                if($(this).val() == ''){
                    jQuery('#select1').val('');
                }
            },
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li>" )
                    .append( '<img src="'+item.image_src+'" width="30"><span>' + item.label + '</span>')
                    .appendTo( ul );
        };

        jQuery('#brand_2').autocomplete({
            source: "{{url('/getBrands')}}",
            minLength: 2,
            select: function( event, ui ) {
                jQuery('input[name="select2"]').val(ui.item.id);
            },
            open: function() {
                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            },
            change: function( event, ui ) {
                if($(this).val() == ''){
                    jQuery('#select2').val('');
                }
            },
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li>" )
                    .append( '<img src="'+item.image_src+'" width="30"><span>' + item.label + '</span>')
                    .appendTo( ul );
        };
    });
 </script>

@stop()