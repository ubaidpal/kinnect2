@extends('layouts.default')
@section('content')
        <!-- Post Div-->


<div class="content-gray-title mb10" data-user="{{$user->username}}">
    <h4>Manage Photos</h4>
    <a href="{{url("albums/photos/".$album->album_id)}}" title="Browse" class="btn fltR">Add New Photos</a>
    <a href="{{\Kinnect2::profileAddress($user).'#albums'}}" title="Create Battel" class="btn fltR mr10">My Albums</a>

</div>
<div class="form-container">

    {!! Form::model($album , ['method' => 'PATCH', 'url' => "albums/update/".$album->album_id]) !!}

    <div class="field-item">
        {!! Form::label('title' ,'Album Title') !!}
        {!! Form::text('title',null, ['class' => 'form-control','placeholder'=>'Album Title']) !!}
        <p id='error_msg_title' style="color:red; display:none;padding-top:10px;margin-left:50px">Album Title, Please complete this field - it is required.</p>
    </div>

    <div class="field-item">
        {!! Form::label('category_id' ,'Category') !!}
        {!!  Form::select('category_id',$categories, $album->category_id, ['id' => 'category'])!!}
    </div>

    <div class="field-item">
        {!! Form::label('description' ,'Album Description') !!}
        {!! Form::textarea('description',null, ['class' => 'form-control','placeholder'=>'Album Description']) !!}
    </div>

    <?php
    $settingItems[ Config::get( 'constants.PERM_EVERYONE' ) ] = array( 'PERM_EVERYONE', "Registered Member" );
    $settingItems[ Config::get( 'constants.PERM_FRIENDS_AND_NETWORK' ) ] = array(
            'PERM_FRIENDS_AND_NETWORK',
            "Friends and Network"
    );
    $settingItems[ Config::get( 'constants.PERM_FRIENDS_OF_FRIENDS' ) ] = array(
            'PERM_FRIENDS_OF_FRIENDS',
            "Friends of Friends"
    );
    $settingItems[ Config::get( 'constants.PERM_FRIENDS' ) ] = array( 'PERM_FRIENDS', "Friends Only" );
    $settingItems[ Config::get( 'constants.PERM_PRIVATE' ) ] = array( 'PERM_PRIVATE', "Just Me" );
    ?>

    <div class="create-album">
        <div class="field-item">
            <label for="">Privacy</label>
            <select name="auth_allow_view" id="view_privacy">
                {{$eventViewPrivacySetting = Kinnect2::getAuthAllowSetting('album', $album->album_id, 'view') }}
                @foreach($settingItems as $index => $settingItem)
                    <?php
                    echo "<option value='" . $settingItem[0] . "' ";
                    if ( $index == $eventViewPrivacySetting ) {
                        echo 'selected';
                    }
                    echo ">" . $settingItem[1] . "</option>";
                    ?>
                @endforeach
            </select>
        </div>

        <div class="field-item">
            <label for="">Comment Privacy</label>
            <select name="auth_allow_comment" id="view_privacy">
                {{$eventViewPrivacySetting = Kinnect2::getAuthAllowSetting('album', $album->album_id, 'comment') }}
                @foreach($settingItems as $index => $settingItem)
                    <?php
                    echo "<option value='" . $settingItem[0] . "' ";
                    if ( $index == $eventViewPrivacySetting ) {
                        echo 'selected';
                    }
                    echo ">" . $settingItem[1] . "</option>";
                    ?>
                @endforeach
            </select>
        </div>

        <div class="field-item">
            <label for="">Tagging </label>
            <select name="auth_allow_tagging" id="view_privacy">
                {{$eventViewPrivacySetting = Kinnect2::getAuthAllowSetting('album', $album->album_id, 'tag') }}
                @foreach($settingItems as $index => $settingItem)
                    <?php
                    echo "<option value='" . $settingItem[0] . "' ";
                    if ( $index == $eventViewPrivacySetting ) {
                        echo 'selected';
                    }
                    echo ">" . $settingItem[1] . "</option>";
                    ?>
                @endforeach
            </select>
        </div>

        <div class="form-group save-changes">
            {!! Form::submit('Save Changes', ['class' => 'btn btn-primary form-control btn Create-btn']) !!}
            <a style="" href="{{ URL::previous() }}" class="btn btn-grey ml10">Cancel</a>
        </div>

       {!! Form::close() !!}

    </div>
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

