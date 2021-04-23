@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

    @include('Store::includes.store-admin-leftside')

    <div class="product-Analytics">
        <div class="post-box">
            <h1>Categories</h1>
            @include('Store::includes.alert.alert')
            @if(Auth::user()->id == $url_user_id)
                <div class="selectdiv m0">
                    {!! Form::open() !!}
                    <input required="required" type="text" id="name" name="name" placeholder="Add Category..."
                           class="storeInput fltL mr10 w340">
                    <input type="submit" class="btn blue fltL" id="add_button" value="Add"
                           title="Save your New Category"/>

                    <div style="color:red;display: none;width: 190px;padding-top: 45px;" id="alert"></div>
                    {!! Form::close() !!}
                </div>
            @endif

            @if(is_object($allCategories))

                @foreach($allCategories as $category)
                    <div class="categoryList">
                        <div>{{$category->name}}</div>
                        <div class="actW">
                            @if(Auth::user()->id == $url_user_id)
                                <a class="js-open-modal" data-modal-id="popup1-{{$category->id}}"
                                   title="Edit {{$category->name}}" href="#">
                                    <span class="editProduct ml20 mr20"></span>
                                </a>
                                <a class="js-open-modal" data-modal-id="popup2-{{$category->id}}"
                                   title="Delete {{$category->name}}" href="#">
                                    <span class="deleteProduct"></span>
                                </a>
                            @endif
                        </div>
                    </div>
                    {!! Form::open(array('method'=> 'post','url'=> "store/".Auth::user()->username."/admin/edit/category/".$category->id)) !!}
                    @include('Store::includes.Editpop',
                    ['submitButtonText' => 'Update',
                     'title'=>$category->name,
                     'item' => 'Category',
                     'id' => 'popup1-'.$category->id])
                    {!! Form::close() !!}

                    {!! Form::open(array('method'=> 'get','url'=> "store/".Auth::user()->username."/admin/delete/category/".$category->id)) !!}
                    @include('Store::includes.deletePop',
                        ['submitButtonText' => 'Delete',
                        'cancelButtonText' => 'Cancel',
                        'title' => 'Delete Category',
                        'text' => 'Are You Sure You Want To delete This category? All the Sub-categories and product will also be deleted',
                        'id' => 'popup2-'.$category->id])
                    {!! Form::close() !!}
                @endforeach
            @else
                <div class="categoryList">
                    <h3 class="notify">You have no categories added, create new one for your store product(s).</h3>
                </div>
            @endif
        </div>
    </div>
</div>
<script>
    $('#name').keypress(function (e) {

        var regex = new RegExp(/^[a-zA-Z0-9-_!\s\b]+$/);
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);

        var nameVal = $('#name').val();
        var isnum = /^\d+$/.test(nameVal);

        if (isnum === true) {
            $('#alert').html('Only numeric is not allowed.').show();
        } else {
            $('#alert').hide();
        }

        if (regex.test(str)) {
            return true;
        }

        e.preventDefault();
        return false;
    });

    $(document).ready(function () {
        $("form").submit(function () {
            var nameVal = $('#name').val();
            var isnum = /^\d+$/.test(nameVal);
            if (isnum === true) {
                $('#alert').html('Only numeric is not allowed.').show();
                return false;
            }
            $("#add_button").prop('disabled', true);
            $("#add_button").val("Saving..");
        });

    });


</script>

@endsection

