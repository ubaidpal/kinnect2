@extends('layouts.default')
@section('content')

    <div class="brand-inst">
        <span>Follow at least 3 brands to explore world of "Consumers and Brands Engagement"</span>

        <h3 id="remainingToFollow">Follow 3 brands to continue.</h3>
    </div>
    <?php $brands = Kinnect2::allBrands()?>
            <!-- Brands Container -->
    <div class="brands-container">

        <!-- Round Img Container -->
        <div class="round-img-container">
            <!-- Round Img Item -->

            @foreach( $brands as $brand)
                <div class="round-img-item myBrands" id="brand_{{$brand->id}}">
                    <div class="round-img-contnr">
                        <a class="round-img" href="{{url(\Kinnect2::profileAddress($brand))}}">
                            <img src="{{Kinnect2::getPhotoUrl($brand->photo_id, $brand->id, 'user', 'thumb_normal')}}"
                                 alt="img">
                        </a>
                    </div>
                    <div class="round-img-title">
                        <a class="round-title-txt"
                           href="{{url(\Kinnect2::profileAddress($brand))}}">{{$brand->displayname}}</a>
                    </div>

                    <div class="round-img-title">
                        {{ Kinnect2::brand_kinnectors($brand->id) }} Followers
                    </div>

                    <div class="">
                        <a id="btn_{{$brand->id}}" title="Click to Follow {{ ucwords($brand->brand_name) }}" class="btn-round-img" href="javascript:void(0);" onclick="follow_b({{$brand->id}})" >Follow</a>
                    </div>
                </div>
            @endforeach


        </div>

        <!-- Button Show More -->
        {{-- <div class="brand-btn">
             <a class="btn" href="javascript:void(0)">Show More</a>
         </div>--}}
    </div>




    <?php //echo $brands->render(); ?>
    <div class="clrfix"></div>
    <script>
        var followedBrands = 0;

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
                }
            });
        }//un_follow(brand_id)

        function follow_b(brand_id){
            followedBrands = followedBrands + 1;
            if($('#btn_' + brand_id).html() == 'Please wait..') return false;
            $('#btn_' + brand_id).html('Please wait..');

            var brand = $("#brand_" + brand_id).clone();

            var followers = brand.children("span").text().replace(/[^0-9]/g, '');
            followers ++;
            brand.children("span").text(followers + " Followers");

            $("#brand_" + brand_id).remove();

            var anchor = brand.find('a#btn_' + brand_id);
            anchor.text('Un-Follow');
            anchor.removeClass('follow');
            anchor.attr('onClick', 'un_follow_b(' + brand_id + ')');
            // console.log(brand.find('a#btn_'+brand_id));

            var dataString = "brand_id=" + brand_id;
            $.ajax({
                type : 'GET', url : '{{url('follow')}}', data : dataString, success : function(response){
                    if(followedBrands < 3){
                        if(followedBrands == 2){
                            $("#remainingToFollow").html(3 - followedBrands + " more brand you have to follow.");
                        }else{
                            $("#remainingToFollow").html(3 - followedBrands + " more brands you have to follow.");
                        }
                    }else{
                        $("#remainingToFollow").html('<a class="btn follow" title="Explore Kinnect2" href="<?php echo url("/") ?>">Click here to explore</a>');
                    }
                }
            });
        }//follow(brand_id)

        $('#search_brands_to_follow').keyup(function(){
            var searchField = $('#search_brands_to_follow').val();
            if(searchField == ''){
                searchField = - 1;
            }
            var regex  = new RegExp(searchField, "i");
            var output = '<h3 id="remainingToFollow">Follow 3 brands to continue.</h3>';
            var count  = 0;
            var url    = '<?php echo url('getBrandsToFollow').'/'; ?>' + searchField;
            $.getJSON(url, function(data){
//            console.log(data);
                $.each(data, function(key, val){
                    count ++;
                    output += '<div class="myBrands" id="brand_' + val.id + '"><a title="' + val.displayname + '" href="{{url('brand')}}/' + val.username + '"> <img src="' + val.image + '" width="134" height="134" alt="' + val.displayname + '">   </a> <a title="' + val.displayname + '" href="{{url('brand')}}/' + val.username + '" class="bName">' + val.displayname + '</a> <span>' + val.followers + ' Followers</span> <a href="javascript:void(0);" onclick="follow_b(' + val.id + ')" title="Click to Follow ' + val.displayname + '" id="btn_' + val.id + '" class="btn follow">Follow</a></div>';
                });

                if(count > 0){
                    $(".all-brands").html(output);
                }else{
                    output += '<div class="myBrands" id="brand">Nothing Found</div>';
                    $(".all-brands").html(output);
                }

            });
        });
    </script>
@endsection
