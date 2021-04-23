@extends('layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('includes.user-profile-banner')

<div class="mainCont">
    @include('includes.main-left-side')
    <div class="profile-content target" id="info">
        <div class="content-gray-title mb10" data-user="{{$user->username}}">
            <h4>Albums</h4>

        </div>
        <div class="create-album">
            <ul>
                @if($user->id == Auth::user()->id)
                    <li>
                        <a href="{{ URL::to('albums/create')}}">
                            <img src="{!! asset('local/public/assets/images/add-image.jpg') !!}" width="150"
                                 height="150" alt=""/>
                        </a>
                        <a class="txt" href="{{ URL::to('albums/create')}}" title="Create an Album">Create an Album</a>

                        <div class="clrfix"></div>
                    </li>

                @endif
                <?php
                $path = \Config::get( 'constants_activity.PHOTO_URL' );
                ?>
                @if(count($albums) > 0)
                    @foreach($albums as $album)

                        <?php
                        $privacy = is_allowed( $album->album_id, 'album', 'view', Auth::user()->id, $album->owner_id )
                        ?>
                        @if($user->id === Auth::user()->id || $privacy)
                            <li>
                                <?php
                                $photo_path = '';
                                $photo_mime = '';
                                if ( ! empty( $album->cover_photo ) ) {
                                    $photo_path = $album->cover_photo->storage_path;
                                }
                                if ( ! empty( $album->cover_photo ) ) {
                                    $photo_mime = $album->cover_photo->mime_type;
                                }

                                $imageSrc = $path.$photo_path.'?type='.urlencode($photo_mime);

                                if (strpos($photo_path, 'public/') !== false) {
                                    $imageSrc = url('local/'.$photo_path);
                                }
                                ?>

                                {{--<a href="{{ URL::to('albums/'.$album->album_id.'/edit') }}">--}}
                                <a href="{{url("albums/photos/".$album->album_id)}}">
                                    @if($photo_path)
                                        <img src="{!! $imageSrc !!} "
                                             width="150" height="150" alt=""/>
                                    @else
                                        <img src="{!! asset('local/public/assets/images/album.png') !!} " width="150"
                                             height="150" alt=""/>
                                    @endif
                                </a>
                                <a class="txt" href="{{url("albums/photos/".$album->album_id)}}"
                                   title="Profile Photo">{{$album->title}}</a>

                                <div class="clrfix"></div>
                                <span> {{count($album->AlbumPhotos)}} Photos</span>
                            </li>
                        @endif

                    @endforeach

                @endif
            </ul>
        </div>
    </div>
    @include('profile.profile-view-links')
    @include('includes.ads-right-side')
</div>


@endsection
@section('footer-scripts')
    <script src="{!! asset('local/public/assets/js/inner-pages.js') !!}"></script>

    <style>
        .profile-content.target.hide {
            display: none;
        }
    </style>
@endsection

