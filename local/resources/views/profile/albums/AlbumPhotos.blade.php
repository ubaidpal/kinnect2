@extends('layouts.default')
@section('content')
        <!-- Post Div-->



        <div class="content-gray-title mb10" data-user="{{$user->username}}">
            <h4>Album Photos</h4>
            @can('update', $album)
            <a href="{{ URL::to('albums/'.$album->album_id.'/edit') }}" title="View sent Requests" class="btn fltR ">Edit
                Album Details</a>
            @endcan
            <?php $Owner = Kinnect2::groupOwner($album->owner_id); ?>
            <a  href="{{url(Kinnect2::profileAddress($Owner))}}/albums" class="btn fltR" style="margin-right:5px">Back to Albums</a>
        </div>
        <div class="wall-photos">
            @if($album->owner_id === Auth::user()->id)
                <a href="javascript:void(0);" class="" id="add-photo">
                    <img src="{!! asset('local/public/assets/images/add-image.jpg') !!}" width="150" height="160"
                         alt=""/>
                </a>
            @endif

            <?php
            $path = \Config::get( 'constants_activity.PHOTO_URL' );
                   //echo '<tt><pre>'; print_r($album->AlbumPhotos); die;
            ?>
            @foreach($album->AlbumPhotos as $photo)
                <?php
                    $photo_path = '';
                    $photo_mime = '';

                    if ( isset( $photo->storage_file ) ) {
                        $photo_path = $photo->storage_file->storage_path;
                    }
                if (strpos($photo_path, 'public/') !== false) {
                    $photo_path = url('local/'.$photo_path);

                }else{
                    if ( isset( $photo->storage_file ) ) {
                        $photo_mime = $photo->storage_file->mime_type;
                    }
                    $photo_path = $path.$photo_path.'?type='.urlencode($photo_mime);
                    }

                ?>

                <a href="javascript:void(0);">
                    <img width="150"
                         height="160" data-id="{{$photo->photo_id}}" src="{{\Kinnect2::getAlbumPhotoUrl($photo->storage_file->file_id,'thumb_normal')}}"


                         alt=""/>
                    @can('update', $album)
                    <span data-url="{{url('albums/photo/delete/'.$photo->photo_id)}}" data-id="{{$photo->file_id}}"
                          class="js-open-modal" data-modal-id="popup1" data-title="{{$photo->title}}"
                          data-description="{{$photo->description}}">Edit Photo</span>
                    @endcan
                </a>
            @endforeach
        </div>
        @can('update', $album)
        <div class="modal-box" id="popup1" data-photo="{{$album->photo_id}}">
            <a href="#" class="js-modal-close close"></a>

            <div class="modal-body">
                <div class="edit-photo-poup">
                    <h3>Edit Photo</h3>

                    <div class="wall-photos">
                        <a href="#">
                            <img src="{!! asset('local/public/assets/images/user-img.jpg') !!}" width="150" height="160"
                                 alt="" id="photo-in-popup"/>
                            <span id="delete-url" onclick="confirmation()" class="delete">Delete Photo</span>
                        </a>

                        <div class="photoDetail">
                            <div class="form-container">
                                {!! Form::open(array('method'=> 'post','url'=> 'albums/save-description')) !!}
                                {!! Form::hidden('photo_id', '', array('id' => 'photo_id')) !!}
                                <div class="field-item">
                                    <label for="">Title</label>
                                    <input id="title" type="text" placeholder="In the bar with Jay" name="title"/>
                                </div>
                                <div class="field-item">
                                    <label for="">Description</label>
                                    <textarea id="description" name="description" placeholder="Write Description here..."></textarea>
                                </div>

                                <div class="saveArea">
                                    <div class="field-item-checkbox">
                                        <input id="cover-photo" value="1" type="checkbox" name="is_cover">
                                        <label for="cover-photo">Set as a cover photo</label>
                                    </div>
                                    <input class="orngBtn fltR" type="submit" value="Save Changes"/>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {!! Form::open(array('method'=>'post','url' => 'album/add-photo', 'id'=>'submit-photo','class'=>'hide', 'enctype'=>"multipart/form-data")) !!}
        {!! Form::file('file', array('id'=>'file-btn', 'accept'=>'image/*')) !!}
        {!! Form::hidden('album_id', $album->album_id) !!}
        {!! Form::close() !!}
        @endcan






@endsection
@section('footer-scripts')
    <script src="{!! asset('local/public/assets/js/inner-pages.js') !!}"></script>

    <style>
        .profile-content.target.hide {
            display: none;
        }

        .hide {
            display: none;
        }
    </style>
@endsection

