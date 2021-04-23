@extends('layouts.default')
@section('content')
        <!-- Brands-->
<div class="clrfix"></div>

<div class="content-gray-title mb10 title-bar">
    <h3>My Brands</h3>

</div>
<?php $brands = Kinnect2::myAllBrands();
?>
<div class="all-brands paginate-data" data-url="{{url('all-my-brand')}}">
    @foreach($brands as $brand)
        <div class="comment-item">
            <div class="comment-img">
                <a class="comnt-imgc" href="{{url(Kinnect2::profileAddress($brand))}}">
                    <img src="{{Kinnect2::getPhotoUrl($brand->photo_id, $brand->id, 'brand', 'thumb_profile')}}" alt="img">
                </a>
            </div>
            <div class="comment-txt">
                <div class="cmnt-title">
                    <a href="{{Kinnect2::profileAddress($brand)}}">{{ ucwords(isset($brand->brand_detail) ? $brand->brand_detail->brand_name : $brand->name) }}</a>
                </div>
            </div>
        </div>
    @endforeach
    <?php echo $brands->render(); ?>
</div>

<div class="clrfix"></div>
@endsection
@section('footer-scripts')
    {!! HTML::script('local/public/assets/js/searchAndPagination.js') !!}
    <script>
        function un_follow( brand_id ) {
            if( $( '#btn_' + brand_id ).html() == 'Please wait..' ) return false;
            $( '#btn_' + brand_id ).html( 'Please wait..' );

            var dataString = "brand_id=" + brand_id;
            $.ajax( {
                type: 'GET', url: '{{url('unfollow')}}', data: dataString, success: function( response ) {
                    $( "#brand_" + brand_id ).remove();
                    //window.location.reload();
                }
            } );
        }//un_follow(brand_id)
    </script>
@endsection

