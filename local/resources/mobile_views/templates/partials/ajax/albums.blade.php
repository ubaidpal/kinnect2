{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 12-11-15 11:26 AM
    * File Name    : 

--}}

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
                               // echo $album->cover_photo; die;
                            $imageSrc = '';
                            $photo_mime = '';
                            $photo_path = '';
                            if ( ! empty( $album->cover_photo ) ) {
                                $photo_mime = $album->cover_photo->mime_type;
                                $photo_path = $album->cover_photo->storage_path;

                                if (strpos($photo_path, 'public/') !== false) {
                                    $imageSrc = url('local/'.$photo_path);
                                }else{
                                    $imageSrc = $path.$photo_path.'?type='.urlencode($photo_mime);
                                }
                            }
                            ?>

                            {{--<a href="{{ URL::to('albums/'.$album->album_id.'/edit') }}">--}}
                            <a href="{{url("albums/photos/".$album->album_id)}}">
                                @if($photo_path)
                                    <img src="{{\Kinnect2::getAlbumPhotoUrl($album->cover_photo->file_id,'thumb_normal')}}"
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

