@extends('layouts.default')
@section('content')

<div class="content-gray-title mb10">
    <h4> Edit Privacy </h4>
    <a class="btn fltR mr10" title="Create Battel" href="{{ URL::to('polls/create')}}">Create Poll</a>
</div>

<p>Edit your poll privacy below, then click "Save Privacy" to apply the new privacy settings for the poll.</p>


  <div class="form-container">
  {!! Form::model($poll , ['method' => 'PATCH', 'url' => "polls/update/".$poll->id]) !!}

   <?php
      $settingItems[Config::get('constants.PERM_EVERYONE')] = array('PERM_EVERYONE', "Registered Member");
      $settingItems[Config::get('constants.PERM_FRIENDS_AND_NETWORK')] = array('PERM_FRIENDS_AND_NETWORK', "Friends and Network");
      $settingItems[Config::get('constants.PERM_FRIENDS')] = array('PERM_FRIENDS', "Friends Only");
      $settingItems[Config::get('constants.PERM_PRIVATE')] = array('PERM_PRIVATE', "Just Me");
   ?>

   <div class="field-item">
      <label for="">View Privacy</label>
      <select name="auth_allow_view" id="view_privacy">
          {{$eventViewPrivacySetting = Kinnect2::getAuthAllowSetting('poll', $poll->id, 'view') }}
          @foreach($settingItems as $index => $settingItem)
              <?php
              echo "<option value='".$settingItem[0]."' ";
              if($index == $eventViewPrivacySetting){
                  echo 'selected';
              }
              echo ">".$settingItem[1]."</option>";
              ?>
          @endforeach
      </select>
   </div>

   <div class="field-item">
        <label for="">Comment Privacy</label>
        <select name="auth_allow_comment" id="view_privacy">
            {{$eventViewPrivacySetting = Kinnect2::getAuthAllowSetting('poll', $poll->id, 'comment') }}
            @foreach($settingItems as $index => $settingItem)
                <?php
                echo "<option value='".$settingItem[0]."' ";
                if($index == $eventViewPrivacySetting){
                    echo 'selected';
                }
                echo ">".$settingItem[1]."</option>";
                ?>
            @endforeach
        </select>
   </div>

   <div class="">
       {!! Form::hidden('search', false, ['id'=>'show-album']) !!}
       {!! Form::checkbox('search', true , ['id'=>'show-album'])!!}
       {!! Form::label('searchlabel' , 'Show this poll in search results') !!}
   </div>

   <div class="form-group save-changes">
      {!! Form::submit('Save Privacy', ['class' => 'btn btn-primary form-control btn']) !!}
      <a href="{{URL::previous() }}" class="btn btn-grey ml10" id="Cancel-btn">Cancel</a>
  </div>

 {!! Form::close() !!}
</div>

@stop