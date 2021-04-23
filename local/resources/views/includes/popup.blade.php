@if(isset($type) && $type == 'popup6')
    <div class="modal-box" id={{$id}}>
     <a href="#" class="js-modal-close close" style="margin-top: 10px">×</a>
     <div class="modal-body">
         <div class="edit-photo-poup">
             <h3 class="mb10">Friends List</h3>
             <div class="other_member_list">
             @foreach($friendsFollowing as $friend)
                <?php $name = Kinnect2::groupOwner($friend->user_id);
                 if(!isset($name->id)){continue;}
                 ?>
                   <a href="{{url(Kinnect2::profileAddress($name))}}">
                        <img src="{{Kinnect2::getPhotoUrl($name->photo_id, $name->id, 'user', 'thumb_profile')}}" alt="image" />
                        <span>{{$name['displayname']}}</span>
                   </a>
             @endforeach
             </div>
         </div>
     </div>
    </div>
@elseif(isset($type) && $type == 'invite-friends')
    <link href="{{ asset('/local/public/css/jquery.multiselect.css') }}" rel="stylesheet">
    <script src="{{ asset('/local/public/js/jquery.multiselect.js') }}"></script>
    <div class="modal-box" id="popup2">
        <a href="#" class="js-modal-close close">×</a>

        <div class="modal-body">
            <div class="edit-photo-poup">
                <h3>Invite Members</h3>

                <p>Choose the people you want to invite to this event.</p>

                <div class="wall-photos" style="overflow: auto">
                    <div class="photoDetail">
                        <div class="form-container">
                            {!! Form::open(array('method'=> 'post','url'=> "invitation/invite-friends",'id'=> "invitation-invite-friends")) !!}
                            {!! Form::hidden('object_id',$object_id) !!}
                            {!! Form::hidden('object_type',$object_type) !!}
                            <div>
                                <label for=""></label>
                                @if($friends == [])
                                    <p>No Friend Left To Send Request</p>
                                @else

                                    {!! Form::select('friends[]',$friends,null,[' multiple'] ) !!}
                                @endif
                                <p id='error_msg' style="color:red; display:none;padding-top:5px">Please select some friends to send invite</p>
                            </div>
                            <div class="clrfix"></div>
                            <div class="saveArea mt10">
                                @if($friends != [])
                                    <input class="orngBtn fltL" type="submit" value="Send Invites" id="Send_invites"/>
                                    <input class="orngBtn js-modal-close close" style="margin-left: 110px;" type="button"value="Cancel"/>
                                @else
                                    <input class="orngBtn js-modal-close close" style="margin-left:130px;" type="button" value="Cancel"/>
                                @endif

                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .ms-options {
            position: relative !important;
        }
    </style>
    <script>
        $('select[multiple]').multiselect({
            columns: 1,
            search: true,
            placeholder: 'Select options'
        });

        $( "form input:checkbox" ).change(function(){
            var selectedFriendsCount = $('select[multiple]').val();
            if( selectedFriendsCount > 0 ) {
                $("#error_msg").hide();
            }
        });

        $('#invitation-invite-friends').submit(function(evt){
            var selectedFriendsCount = $('select[multiple]').val();
            if( selectedFriendsCount < 1 || selectedFriendsCount == null || selectedFriendsCount == undefined){
                $("#error_msg").show();
                return false;
            }
        });

    </script>
@else

    <div class="modal-box" id="{{$id}}">
     <a href="#" id="pop_up_close" class="js-modal-close close">×</a>

     <div class="modal-body">
         <div class="edit-photo-poup">
             <h3>{{@$title}}</h3>
             <p>{{@$text}}</p>
             <div class="wall-photos">
                 <div class="photoDetail">
                     <div class="form-container">
                         <div class="saveArea">
                            {!! Form::submit(@$submitButtonText, ['class' => 'orngBtn fltL mr10']) !!}
                            {!! Form::button(@$cancelButtonText, ['class' => 'orngBtn js-modal-close fltL close']) !!}

                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
    </div>
 @endif
