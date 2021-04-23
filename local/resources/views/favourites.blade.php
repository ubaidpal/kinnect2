@extends('layouts.default')
@section('content')
    <div>
        @if(!empty($d))
        @foreach($d as $key => $ds)
        @if(!empty($ds['subject_href']))
        <div class="favContainer">
            <a href="{{ URL::to('profile/'.$ds['subject_href']) }}">
                <img src="{{$ds['subject_photo_path']}}"/>
            </a>
            <div>
                <a href="{{ URL::to('profile/'.$ds['subject_href']) }}">{{$ds['subject_name']}}</a>
                @if($ds['post_type'] == 'share')
                    @if($ds['subject_owner_href'] == 'owner_self')
                    {{$ds['post_header']}}&nbsp;{{$ds['subject_owner_name']}}
                    @else
                    {{$ds['post_header']}}&nbsp;<a href="url('profile/'.$ds['subject_owner_href'])">{{$ds['subject_owner_name']}}</a>&apos;s
                    @endif
                    <a href="{{url('view/'.$ds['object_type'].'/'.$ds['object_id'])}}">{{$ds['object_display_name']}}</a>
                @else
                {{$ds['post_header']}}
                {{$ds['object_display_name']}}
                @endif
                @if($ds['is_group_post'] && !empty($ds['group']['id']))
                    {{$ds['post_header_group_prefix']}} <a href="{{url('group/'.$ds['group']['id'])}}">{{$ds['group']['name']}}</a>
                @endif
                @if($ds['post_type'] == 'status')
                    <div>
                        <p>{{limit_chr($ds['post_body'],195)}}</p>
                        @if(strlen($ds['post_body']) > 195)
                            <span class="mt5">
                            <a href="{{url('view/'.$ds['object_type'].'/'.$ds['object_id'])}}">Read more</a>
                            </span>
                        @endif
                    </div>
                @elseif($ds['object_type'] == 'link')
                <a href="{{$ds['object_uri']}}" target="_blank">{{$ds['object_name']}}</a>
                <div>
                    <img src="{{@$ds['object_photo_path']}}" width="70">
                    <p>{{limit_chr($ds['object_description'],200)}}</p>
                    @if(strlen($ds['object_description']) > 200)
                        <span class="mt5">
                            <a href="{{url('view/'.$ds['object_type'].'/'.$ds['object_id'])}}">Read more</a>
                            </span>
                    @endif
                </div>
                @elseif(($ds['object_type'] == 'album_photo' || $ds['object_type'] == 'cover_photo') && !empty($ds['object_photo_path'][0]))
                <div>
                    <a href="{{url('view/'.$ds['object_type'].'/'.$ds['object_id'])}}"><img src="{{$ds['object_photo_path'][0]}}"></a>
                    <p>{{limit_chr($ds['post_body'],170)}}</p>
                    @if(strlen($ds['post_body']) > 170)
                        <span class="mt5">
                            <a href="{{url('view/'.$ds['object_type'].'/'.$ds['object_id'])}}">Read more</a>
                            </span>
                    @endif
                </div>
                @elseif($ds['object_type'] == 'video')
                <div>
                    <a href="{{url('view/'.$ds['object_type'].'/'.$ds['object_id'])}}"><img src="{{$ds['object_photo_path']}}" width="70"></a>
                    <p>{{limit_chr($ds['post_body'],170)}}</p>
                    @if(strlen($ds['post_body']) > 170)
                        <span class="mt5">
                            <a href="{{url('view/'.$ds['object_type'].'/'.$ds['object_id'])}}">Read more</a>
                            </span>
                    @endif
                </div>
                @elseif($ds['object_type'] == 'audio')
                <div>
                    <audio src="{{$ds['object_path']}}" controls></audio>
                </div>
                @elseif($ds['object_type'] == 'group')
                <div>
                    <img src="{{$ds['object_photo_path']}}" width="70" />
                        <a href="{{url('group/'.$ds['object_id'])}}">{{$ds['object_name']}}</a>
                        <p>{{limit_chr($ds['object_description'],170)}}</p>
                        @if(strlen($ds['object_description']) > 170)
                            <span class="mt5">
                                <a href="{{url('view/'.$ds['object_type'].'/'.$ds['object_id'])}}">Read more</a>
                                </span>
                        @endif
                </div>
                @elseif($ds['object_type'] == 'event')
                <div>
                    <img src="{{$ds['object_photo_path']}}" width="70" />
                    <a href="{{url('event/'.$ds['object_id'])}}">{{$ds['object_name']}}</a>
                        <p>{{limit_chr($ds['object_description'],170)}}
                        @if(strlen($ds['object_description']) > 170)
                        <span class="mt5"><a href="{{url('view/'.$ds['object_type'].'/'.$ds['object_id'])}}">Read more</a></span>
                        @endif
                </div>
                @elseif($ds['object_type'] == 'product')
                    <div>
                        <a href="{{url('view/'.$ds['object_type'].'/'.$ds['object_id'])}}">{{$ds['object_title']}}</a>
                    </div>
                @elseif($ds['object_type'] == 'poll' || $ds['object_type'] == 'battle')
                    <div>
                        <a href="{{url('view/'.$ds['object_type'].'/'.$ds['object_id'])}}">{{$ds['object_name']}}</a>
                    </div>
                @else
                @endif
            </div>
            <span>{{$ds['post_created_at']}}</span>
        </div>
        @endif
        @endforeach
        @else
            <div class="favContainer">You have not made any post favourite yet.</div>
        @endif
    </div>
    {!! $ff->render() !!}
@endsection