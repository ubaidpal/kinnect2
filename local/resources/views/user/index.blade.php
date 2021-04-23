{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 11-11-15 4:24 PM
    * File Name    :

--}}
@extends('layouts.default-extend')
@if($user->can_view_profile)
@section('mvc-app')
@include('includes.client-side-mvc')
@endsection
@endif
@section('content')
        <!-- Post Div-->
@include('includes.user-profile-banner')
<link rel="stylesheet" href="{!! asset('local/public/assets/css/media_elements/mediaelementplayer.min.css') !!}">
<link rel="stylesheet" href="{!! asset('local/public/assets/css/media_elements/mejs-skins.css') !!}">
<div class="mainCont">
    @include('includes.main-left-side')
    <div style="text-align: center;display: none " id="loading">
        <img alt="Loading..." src="{{asset('local/public/images/loading.gif')}}" id="loading-image">
    </div>
    <div class="profile-content target" id="whats-new">
        @if($user->can_view_profile)
            <div class="content-gray-title mb10" data-user="{{$user->username}}">
                <h4>What's New</h4>
            </div>
            <div id="mvc-main" data-screen="userProfile">


            </div>
        @else
            {{Config::get('constants.NOT_AUTHORIZED')}}
        @endif
    </div>
    @include('profile.profile-view-links')
    @include('includes.ads-right-side')
    @if($user->user_type == Config::get('constants.BRAND_USER'))
        @include('includes.popup', ['id' => NULL, 'type' => 'invite-friends','friends' => $friends_to_invite,'object_id'=>$user->id,'object_type' => 'brand'])
    @endif
</div>
<div id="delete_album_confirm" class="modal-box">
        <a href="#" class="js-modal-close close"></a>
        <div class="modal-body">
            <div class="edit-photo-poup" style="overflow: auto">
                <h3>Confirmation</h3>
                <p class="mt10 mb10">Please note that all the photos in this album will be deleted. <br>This action can't undone.</p>
                <div class="report_save">
                    <button class="orngBtn delete_album_confirm mr10" type="button">Delete</button>
                    <input class="blueBtn js-modal-close close" type="button" value="Cancel">
                </div>
            </div>
        </div>
</div>
<input type="hidden" id="delete_album_href">
@endsection
@section('footer-scripts')

    <script src="{!! asset('local/public/assets/js/inner-pages.js') !!}"></script>

    {!! HTML::script('local/public/assets/js/searchAndPagination.js') !!}
    <script type="text/javascript" src="{!! asset('local/public/assets/js/jquery.validate.min.js') !!}"></script>

    <script type="text/javascript">
        var tab_data = "{{$tab}}";
        var page = 2;
        var is_complete = true;
        $( window ).scroll( function() {
            if( $( window ).scrollTop() >= $( document ).height() - $( window ).height() - 714 ) {
                $( '#loading-image' ).show();

                if( is_complete ) {
                    is_complete = false;

                    $.ajax( {
                        type: "POST",
                        url: "{{url('activity/view-more')}}",
                        data: {page: page, template: 'activity-log', userId: 'Muhammad-Yasir-4'},
                        success: function( result ) {
                            page = page + 1;

                            if( result ) {
                                is_complete = true;
                                $( '#loading-image' ).hide();
                                $( '#is_end' ).remove();
                                $( '#next_page_id' ).remove();
                                $( result ).insertBefore( "#load_more" );

                                if( result.indexOf( 'id="no-more"' ) > 0 ) {
                                    is_complete = false;
                                    $( "#load_more" ).remove();
                                    $( '#loading-image' ).remove();
                                }
                            }
                        }
                    } );
                }
            }
        } );

        jQuery(document).on('click','.delete-album',function (e) {
            e.preventDefault();
            var append = ("<div class='modal-overlay js-modal-close'></div>");
            $("body").append(append);
            $(".modal-overlay").fadeTo(500, 0.7);
            $('#delete_album_confirm').fadeIn($(this).data());
            jQuery('#delete_album_href').val(jQuery(this).attr('href'));
        });
        jQuery(document).on('click','.delete_album_confirm',function (e) {
            e.preventDefault();
            jQuery.ajax({
                url : jQuery('#delete_album_href').val(),
            }).done(function () {
                jQuery('#albums').empty();

                $(".modal-box, .modal-overlay").fadeOut(500, function () {
                    $(".modal-overlay").remove();
                    jQuery('a.active').trigger('click');
                });
            });
        });
        validateUser = function () {
          jQuery('#editUser').validate();
        };
    </script>
    <style>
        .profile-content.target.hide {
            display: none;
        }

        #loading {
            position: relative;
            top: 20px;
        }
    </style>

    @if(Auth::user()->user_type == config('constants.BRAND_USER') && Auth::user()->brand_detail->store_created == 0)
        @include('templates.partials.store-created-popup')
    @endif


@endsection