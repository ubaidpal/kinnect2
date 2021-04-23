@extends('layouts.default')
@section('content')

<div>

    <?php //echo '<tt><pre>'; print_r($d); die;?>
  @foreach($d as $key => $ds)

      @if((isset($d[$key]['actual_id'])))
        <?php $user = Kinnect2::groupMember($d[$key]['actual_id'])?>

        @if((isset($d[$key]['subject_href'])) AND (isset($d[$key]['post_id'])))
            <div class="favContainer">
                <a href="{{ URL::to('profile/'.$d[$key]['subject_href']) }}">
                    <img src='{{$d[$key]['subject_photo_path']}}'/>
                </a>
                @if($d[$key]['post_type'] == 'friends')
                    <div>
                        <a href="{{ URL::to('profile/'.$d[$key]['subject_href']) }}">{{$d[$key]['subject_name']}}</a>
                        is now <a href="{{ URL::to('postDetail/'.$d[$key]['post_id']) }}">friends</a> with
                        <a href="{{URL::to('profile/'.$d[$key]['object_href'])}}">{{$d[$key]['object_name']}}</a>
                    </div>

                @elseif($d[$key]['post_type'] == 'status')
                     <div>
                         <a href="{{ URL::to('profile/'.$d[$key]['subject_href']) }}">{{$d[$key]['subject_name']}}'s</a>
                         {{$d[$key]['post_type']}}
                         <a href="{{URL::to('postDetail/'.$d[$key]['post_id'])}}">{{$d[$key]['post_body']}}</a>
                     </div>

                @elseif($d[$key]['post_type'] == $d[$key]['object_type'].'_create')
                     <div>
                         <a href="{{ URL::to('profile/'.$d[$key]['subject_href']) }}">{{$d[$key]['subject_name']}}</a>
                         Created new
                         <a href="{{ URL::to('postDetail/'.$d[$key]['post_id']) }}">{{$d[$key]['object_type']}} @if(isset($d[$key]['object_name']))({{$d[$key]['object_name']}})@endif</a>
                     </div>

                @elseif(($d[$key]['post_type'] == $d[$key]['object_type'].'_new') OR ($d[$key]['post_type'] == 'album_photo'))
                     <div>
                         <a href="{{ URL::to('profile/'.$d[$key]['subject_href']) }}">{{$d[$key]['subject_name']}} </a>
                          Added new
                         <a href="{{ URL::to('postDetail/'.$d[$key]['post_id']) }}">
                            @if($d[$key]['post_type'] == 'album_photo')
                                 Photos Album
                            @else
                                {{$d[$key]['object_type']}}
                            @endif
                            @if(isset($d[$key]['object_name']))({{$d[$key]['object_name']}})@endif</a>
                     </div>

                @elseif($d[$key]['post_type'] == 'join')
                     <div>
                         <a href="{{ URL::to('profile/'.$d[$key]['subject_href']) }}">{{$d[$key]['subject_name']}}</a>
                         joined
                         <a href="{{ URL::to('postDetail/'.$d[$key]['post_id']) }}">{{$d[$key]['object_type']}} @if(isset($d[$key]['object_name']))({{$d[$key]['object_name']}})@endif</a>
                     </div>

                @elseif($d[$key]['post_type'] == 'follow')
                     <div>
                         <a href="{{ URL::to('profile/'.$d[$key]['subject_href']) }}">{{$d[$key]['subject_name']}}</a>
                         is now <a href="{{ URL::to('postDetail/'.$d[$key]['post_id']) }}">following</a> brand
                         <a href="{{URL::to('profile/'.$d[$key]['object_href'])}}">{{$d[$key]['object_name']}}</a>
                     </div>

                @elseif(($d[$key]['post_type'] == 'share') OR ($d[$key]['post_type'] == $d[$key]['object_type'].'_share'))
                     <div>
                         <a href="{{ URL::to('profile/'.$d[$key]['subject_href']) }}">{{$d[$key]['subject_name']}}</a>
                         Shared a
                         <a href="{{ URL::to('postDetail/'.$d[$key]['post_id']) }}">{{$d[$key]['object_type']}} @if(isset($d[$key]['object_name']))({{$d[$key]['object_name']}})@endif</a>
                     </div>

                @elseif(($d[$key]['post_type'] == 'cover_photo_update') OR ($d[$key]['post_type'] == 'profile_photo_update'))
                      <div>
                          <a href="{{ URL::to('profile/'.$d[$key]['subject_href']) }}">{{$d[$key]['subject_name']}}</a>
                          Changed Profile
                          <a href="{{ URL::to('postDetail/'.$d[$key]['post_id']) }}">
                            @if($d[$key]['post_type'] == 'cover_photo_update')
                                Cover Photo
                            @elseif($d[$key]['post_type'] == 'profile_photo_update')
                                Photo
                            @endif
                            @if(isset($d[$key]['object_name']))({{$d[$key]['object_name']}})@endif
                          </a>
                      </div>

                @else
                    <div>
                        <a href="{{ URL::to('profile/'.$d[$key]['subject_href']) }}">{{$d[$key]['subject_name']}}'s </a>
                        post {{$d[$key]['post_body']}}
                        <a href="{{ URL::to('postDetail/'.$d[$key]['post_id']) }}">{{$d[$key]['object_type']}} @if(isset($d[$key]['object_name']))({{$d[$key]['object_name']}})@endif</a>
                    </div>
                @endif

                <span>{{$d[$key]['post_created_at']}}</span>
            </div>
        @endif
      @endif


  @endforeach
</div>
{!! $ff->render() !!}
@endsection

