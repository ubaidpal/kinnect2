@extends('layouts.default')
@section('content')
<style>
    .header-container > ul, .adPanel, .leftPnl {
        display: none;
    }
    .leaderboard-img{
        display: none;
    }
    .chat-wrapper{
        display: none;
    }
    #remainingToFollow{
        padding-top:10px ;
        padding-bottom:10px ;
        color: #fff;
    }

</style>
<div class="content-gray-title mb10">
    <div id="remainingToFollow" class="follow-brands">Follow at least 3 brands to explore world of "Consumers and Brands Engagement". <input type="text" name="search_brands_to_follow" placeholder="Type to search brand" id="search_brands_to_follow" value=""></div>
</div>

    <?php $brands = Kinnect2::allBrands()?>
    
    <div class="all-brands">
        <h3 id="remainingToFollow">Follow 3 brands to continue.</h3>
        @foreach( $brands as $brand)
                <!-- Post Div-->
        @if($brand->brand_detail)
            <div class="myBrands" id="brand_{{$brand->id}}">
                <a href="{{url(Kinnect2::profileAddress($brand))}}" title="{{ ucwords(isset($brand->brand_detail) ? $brand->brand_detail->brand_name : $brand->name) }}">
                    <img src="{{Kinnect2::getPhotoUrl($brand->photo_id, $brand->id, 'brand', 'thumb_profile')}}" width="134" height="134"
                         alt="Apple">
                </a>
                <a href="{{\Kinnect2::profileAddress($brand)}}" title="{{ ucwords($brand->brand_detail->brand_name) }}"
                   class="bName">{{ ucwords($brand->brand_detail->brand_name) }}</a>


                <span>{{ Kinnect2::brand_kinnectors($brand->id) }} Followers</span>
                <a href="javascript:void(0);" onclick="follow_b({{$brand->id}})"
                   title="Click to Follow {{ ucwords($brand->brand_detail->brand_name) }}" id="btn_{{$brand->id}}"
                   class="btn follow">Follow</a>
            </div>
        @endif
        @endforeach
    </div>
    <?php //echo $brands->render(); ?>
    <div class="clrfix"></div>
<script>
    var followedBrands = 0;

    function un_follow_b(brand_id) {
        if ($('#btn_' + brand_id).html() == 'Please wait..') return false;
        $('#btn_' + brand_id).html('Please wait..');

        var brand = $("#brand_" + brand_id).clone();
        $("#brand_" + brand_id).remove();
        var followers = brand.children("span").text().replace(/[^0-9]/g, '');
        followers--;
        brand.children("span").text(followers+" Followers");
        brand.appendTo('.all-brands');
        var anchor = brand.find('a#btn_'+brand_id);
        anchor.text('Follow');
        anchor.addClass('follow');
        anchor.attr('onClick', 'follow_b('+brand_id+')');
        var dataString = "brand_id=" + brand_id;
        $.ajax({
            type: 'GET',
            url: '{{url('unfollow')}}',
            data: dataString,
            success: function (response) {
            }
        });
    }//un_follow(brand_id)

    function follow_b(brand_id) {
        followedBrands = followedBrands +1;
        if ($('#btn_' + brand_id).html() == 'Please wait..') return false;
        $('#btn_' + brand_id).html('Please wait..');

        var brand = $("#brand_" + brand_id).clone();

        var followers = brand.children("span").text().replace(/[^0-9]/g, '');
        followers++;
        brand.children("span").text(followers+" Followers");

        $("#brand_" + brand_id).remove();

        var anchor = brand.find('a#btn_'+brand_id);
        anchor.text('Un-Follow');
        anchor.removeClass('follow');
        anchor.attr('onClick', 'un_follow_b('+brand_id+')');
        // console.log(brand.find('a#btn_'+brand_id));

        var dataString = "brand_id=" + brand_id;
        $.ajax({
            type: 'GET',
            url: '{{url('follow')}}',
            data: dataString,
            success: function (response) {
                if(followedBrands < 3){
                    if(followedBrands == 2) {
                        $("#remainingToFollow").html(3 - followedBrands + " more brand you have to follow.");
                    }else{
                        $("#remainingToFollow").html(3 - followedBrands + " more brands you have to follow.");
                    }
                }else{
                        $("#remainingToFollow").html('<a style="width: 140px; margin: 5px auto 0px; padding:8px 10px;" class="btn follow" title="Explore Kinnect2" href="<?php echo url("/") ?>">Click here to explore</a>');
                }
            }
        });
    }//follow(brand_id)

    $('#search_brands_to_follow').keyup(function(){
        var searchField = $('#search_brands_to_follow').val();
        if(searchField == ''){
            searchField = -1;
        }
        var regex       = new RegExp(searchField, "i");
        var output      = '<h3 id="remainingToFollow">Follow 3 brands to continue.</h3>';
        var count       = 0;
        var url         = '<?php echo url('getBrandsToFollow').'/'; ?>' + searchField;
        $.getJSON(url, function(data){
//            console.log(data);
            $.each(data, function(key, val){
                count++;
                output += '<div class="myBrands" id="brand_'+val.id+'"><a title="' + val.displayname+ '" href="{{url('brand')}}/' + val.username + '"> <img src="' + val.image + '" width="134" height="134" alt="' + val.displayname+ '">   </a> <a title="' + val.displayname+ '" href="{{url('brand')}}/' + val.username + '" class="bName">' + val.displayname+ '</a> <span>'+val.followers+' Followers</span> <a href="javascript:void(0);" onclick="follow_b('+val.id+')" title="Click to Follow ' + val.displayname+ '" id="btn_'+val.id+'" class="btn follow">Follow</a></div>';
            });

            if(count > 0){$(".all-brands").html(output);}else{
                output += '<div class="myBrands" id="brand">Nothing Found</div>';
                $(".all-brands").html(output);
            }

        });
    });
</script>
@endsection
