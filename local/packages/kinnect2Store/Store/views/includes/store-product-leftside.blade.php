<div class="leftPnl" id="store-sidebar" data-page="store-admin">
    @if(Request::is('store/cart'))
    @else
        <?php $brand = getUserDetail($url_user_id) ?>
        <div class="brand-logo">
            <div class="brand-image">
                <img alt="a" class="img-responsive" src="{{Kinnect2::getPhotoUrl($brand->photo_id, $brand->id, 'brand', 'thumb_normal')}}">
            </div>
            <h1>{{$brand->displayname}}</h1>
        </div>
        <div>
            <a class="orngBtn" style="margin: 0px 10px 10px 10px;" href="{{url('/brand')}}/{{ $brand->username }}">< Go Back to Social</a>
        </div>
        <div id="csssubmenu">
            <h2>Product Categories</h2>
            <ul>
                <?php
                $cats = getCatByID($url_user_id);
                $brand = explode('/store/', $_SERVER[ 'REQUEST_URI' ]);
                $brand = explode('/', $brand[ 1 ]);
                $brand[ 0 ];
                ?>
                @if(!$cats->isEmpty())
                    @foreach($cats as $cat)
                        <?php $isactive = ''; if(isset($parent_category_id)) {
                            if($cat->id == $parent_category_id) {
                                $isactive = "active";
                            }
                        }?>
                        <li id="category_{{$cat->id}}" class="has-sub {{$isactive}}">
                            <a href="javascript:void(0);" title="{{$cat->name}}"><span>{{$cat->name}}</span></a>
                            <ul class="csssub" @if($isactive=="active")style="display: block;"@endif>
                                <?php $subCats = getSubByCatID($cat->id)?>
                                @foreach($subCats as $subCat)
                                    <li <?php if(isset($sub_category_id)) {
                                        if($subCat->id == $sub_category_id) {
                                            echo 'class="active"';
                                        }
                                    }?> id="sub_category_{{$subCat->id}}"><a
                                                href="{{url('store/'.$brand[0].'/products/'.$subCat->name.'/'.$subCat->id)}}"
                                                title="{{$subCat->name}}"><span>{{$subCat->name}}</span></a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                @else
                    <li style="list-style: none; padding:10px;">No product category found containing product(s)</li>
                @endif
            </ul>
        </div>
    @endif
</div>