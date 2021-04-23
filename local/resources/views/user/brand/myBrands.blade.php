@extends('layouts.default')
@section('content')
        <!-- Brands-->
<div class="clrfix"></div>

<div class="content-gray-title mb10">
    <h4>My Brands</h4>
    <a title="Browse All Recommended Brands" class="btn fltR" href="{{ url('/brands')}}">Browse Brands</a>

</div>
<?php $brands = Kinnect2::myAllBrands();
?>
<div class="all-brands paginate-data" data-url="{{url('all-my-brand')}}">
    @foreach($brands as $brand)
            <!-- Post Div-->
    <div class="myBrands" id="brand_{{$brand->id}}">
        <a class="brandUrl_{{ $brand->username }}" href="{{url(Kinnect2::profileAddress($brand))}}"
           title="{{ ucwords(isset($brand->brand_detail) ? $brand->brand_detail->brand_name : $brand->name) }}">
            <img src="{{Kinnect2::getPhotoUrl($brand->photo_id, $brand->id, 'brand', 'thumb_profile')}}"
                 width="134" height="134" alt="Apple">
            @if(isset($brand->brand_detail) && $brand->brand_detail->store_created == 1 && env('STORE_ENABLED'))
                <span class="store brand_store_link" id="{{$brand->username}}"></span>
            @endif
        </a>
        <a href="{{Kinnect2::profileAddress($brand)}}"
           title="{{ ucwords(isset($brand->brand_detail) ? $brand->brand_detail->brand_name : $brand->name) }}"
           class="bName">{{ ucwords(isset($brand->brand_detail) ? $brand->brand_detail->brand_name : $brand->name) }} </a>
        <span>{{ Kinnect2::brand_kinnectors($brand->id) }} Followers</span>
        <a href="javascript:void(0);" onclick="un_follow({{$brand->id}})"
           title="Click to Un-Follow {{ ucwords(isset($brand->brand_detail) ? $brand->brand_detail->brand_name : $brand->name) }}"
           id="btn_{{$brand->id}}" class="btn">Un-Follow</a>
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
    <script>
        $(".brand_store_link").click(function(event){
            var brandNameStore = event.target.id;
            var hrefBrandStore = "<?php echo url('store')?>/";
            hrefBrandStore = hrefBrandStore + brandNameStore;
            $(".brandUrl_" + brandNameStore).attr('href', hrefBrandStore);
        });
    </script>
@endsection

