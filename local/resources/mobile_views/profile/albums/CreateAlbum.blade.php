@extends('layouts.default')
@section('content')
        <!-- Post Div-->
<div class="content-gray-title mb10" data-user="{{$user->username}}">
    <h4>Create Album</h4>
    @if($user->id === Auth::user()->id)
        <a href="{{url($user->username."/albums")}}" title="Create Battel" class="btn fltR mr10">My Albums</a>
    @endif
</div>
<div class="form-container">


    {!! Form::model($album = new\App\Album,['url' => 'albums']) !!}
    {!! Form::hidden('owner_type','user') !!}
    <div class="field-item">
        {!! Form::label('title' ,'Album Title') !!}
        {!! Form::text('title',null, ['class' => 'form-control','placeholder'=>'Album Title']) !!}
         <p id='error_msg_title' style="color:red; display:none;padding-top:10px;margin-left:50px">Album Title, Please complete this field - it is required.</p>
    </div>

    <div class="field-item">
        {!! Form::label('category_id' ,'Category') !!}
        {!! Form::select('category_id',$categories, ['class' => 'form-control','placeholder'=>'Title']) !!}
    </div>

    <div class="field-item">
        {!! Form::label('description' ,'Album Description') !!}
        {!! Form::textarea('description',null, ['class' => 'form-control','placeholder'=>'Album Description']) !!}
    </div>

    <div class="field-item">
        <label for="">Privacy</label>
        <select name="auth_allow_view" id="">
            <option value="PERM_EVERYONE" selected>Registered Member</option>
            <option value="PERM_FRIENDS_AND_NETWORK">Friends and Network</option>
            <option value="PERM_FRIENDS_OF_FRIENDS">Friends of Friends</option>
            <option value="PERM_FRIENDS">Friends Only</option>
            <option value="PERM_PRIVATE">Just Me</option>
        </select>
    </div>

    <div class="field-item">
        <label for="">Comment Privacy</label>
        <select name="auth_allow_comment" id="">
            <option value="PERM_EVERYONE" selected>Registered Member</option>
            <option value="PERM_FRIENDS_AND_NETWORK">Friends and Network</option>
            <option value="PERM_FRIENDS_OF_FRIENDS">Friends of Friends</option>
            <option value="PERM_FRIENDS">Friends Only</option>
            <option value="PERM_PRIVATE">Just Me</option>
        </select>
    </div>

    <div class="field-item">
        <label for="">Tagging</label>
        <select name="auth_allow_tagging" id="">
            <option value="PERM_EVERYONE" selected>Registered Member</option>
            <option value="PERM_FRIENDS_AND_NETWORK">Friends and Network</option>
            <option value="PERM_FRIENDS_OF_FRIENDS">Friends of Friends</option>
            <option value="PERM_FRIENDS">Friends Only</option>
            <option value="PERM_PRIVATE">Just Me</option>
        </select>
    </div>

    <div class="form-group save-changes">
        {!! Form::submit('Create Album', ['name' => 'action' , 'class' => 'btn btn-primary form-control btn Create-btn']) !!}
        {!! Form::submit('Create Album and Add Photos', ['name' => 'action' , 'class' => 'btn btn-grey ml10 Create-btn']) !!}
    </div>
    {!! Form::close() !!}

</div>


@endsection
@section('footer-scripts')
    <script src="{!! asset('local/public/assets/js/inner-pages.js') !!}"></script>
    <script>
     $('.Create-btn').click(function(e){
        var valTitle = $('input[name="title"]').val();
            if(valTitle == ''){
                $('#error_msg_title').show();
                return false;
            }
            else{
                $('#error_msg_title').hide();
                return true;
            }
     });
    </script>

    <style>
        .profile-content.target.hide {
            display: none;
        }
    </style>
@endsection

