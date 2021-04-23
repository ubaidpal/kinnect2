@extends('layouts.default')
@section('content')

    <div class="clrfix"></div>
    <div id="recommended-brands">
        <div class="content-gray-title mb10">
            <h4>Recommended Brands</h4>
            <span class="fltR">
        <input placeholder="Type for search" title="Type and press enter" type="text" value="" name="search"
               data-type="recommended-brands" data-UserId="{{$user_id}}" class="search-peoples">
    </span>
        </div>
        <div style="text-align: center;display: none " id="loading-content" class="search-loader">
            <img alt="Loading..." src="{{asset('local/public/images/loading.gif')}}" id="loading-image">
        </div>
        <?php $brands = Kinnect2::recomendedAllBrands()?>
        <div class="all-brands paginate-data" data-url="{{url('all-recommended-brand')}}">
            @foreach( $brands as $brand)
                    <!-- Post Div-->
            @if($brand->brand_detail)
                <div class="myBrands" id="brand_{{$brand->id}}">
                    <a href="{{url(Kinnect2::profileAddress($brand))}}"
                       title="{{ ucwords($brand->displayname) }}">
                        <img src="{{Kinnect2::getPhotoUrl($brand->photo_id, $brand->id, 'brand', 'thumb_normal')}}"
                             width="134" height="134"
                             alt="Apple">
                    </a>
                    <a href="{{\Kinnect2::profileAddress($brand)}}"
                       title="{{ ucwords($brand->displayname) }}"
                       class="bName">{{ ucwords($brand->displayname) }}</a>


                    <span>{{ Kinnect2::brand_kinnectors($brand->id) }} Followers</span>
                    <a href="javascript:void(0);" onclick="follow_b({{$brand->id}})"
                       title="Click to Follow {{ ucwords($brand->displayname) }}" id="btn_{{$brand->id}}"
                       class="btn follow">Follow</a>
                </div>
            @endif
            @endforeach
            <?php echo $brands->render(); ?>
        </div>
    </div>
    <div class="clrfix"></div>
@endsection
@section('footer-scripts')
    {!! HTML::script('local/public/assets/js/searchAndPagination.js') !!}

    <script>
        function un_follow_b(brand_id){
            if($('#btn_' + brand_id).html() == 'Please wait..') return false;
            $('#btn_' + brand_id).html('Please wait..');

            var brand = $("#brand_" + brand_id).clone();
            $("#brand_" + brand_id).remove();
            var followers = brand.children("span").text().replace(/[^0-9]/g, '');
            followers --;
            brand.children("span").text(followers + " Followers");
            brand.appendTo('.all-brands');
            var anchor = brand.find('a#btn_' + brand_id);
            anchor.text('Follow');
            anchor.addClass('follow');
            anchor.attr('onClick', 'follow_b(' + brand_id + ')');
            var dataString = "brand_id=" + brand_id;
            $.ajax({
                type : 'GET', url : '{{url('unfollow')}}', data : dataString, success : function(response){
                    window.location.reload();
                }
            });
        }//un_follow(brand_id)

        function follow_b(brand_id){
            if($('#btn_' + brand_id).html() == 'Please wait..') return false;
            $('#btn_' + brand_id).html('Please wait..');
            var brand     = $("#brand_" + brand_id).clone();
            var followers = brand.children("span").text().replace(/[^0-9]/g, '');
            followers ++;
            brand.children("span").text(followers + " Followers");
            $("#brand_" + brand_id).remove();
            brand.appendTo('.my-brands');
            var anchor = brand.find('a#btn_' + brand_id);
            anchor.text('Un-Follow');
            anchor.removeClass('follow');
            anchor.attr('onClick', 'un_follow_b(' + brand_id + ')');
            // console.log(brand.find('a#btn_'+brand_id));
            var dataString = "brand_id=" + brand_id;
            $.ajax({
                type : 'GET', url : '{{url('follow')}}', data : dataString, success : function(response){
                    window.location.reload();
                }
            });
        }//follow(brand_id)
    </script>

@endsection
