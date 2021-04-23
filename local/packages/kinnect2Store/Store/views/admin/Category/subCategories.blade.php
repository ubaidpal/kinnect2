@extends('Store::layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

    @include('Store::includes.store-admin-leftside')


    <div class="product-Analytics">  
        <div class="post-box">
            <h1>Sub-Categories</h1>
            @include('Store::includes.alert.alert')
            @if(Auth::user()->id == $url_user_id)
                <div class="selectdiv m0">
                    <div class="mb10">
                    {!! Form::open(['id' => 'form']) !!}
                        @if((is_array($allCategories)))

                        {!! Form::select('category_parent_id', $allCategories, $previousAddedMainCategoryId,
                                ['id'=>'select1' ,
                                    'class' => 'form-control selectList m0',
                                    'type' => 'required']) !!}
                        <input type="hidden" id="error_msg_cat_exists_input" value="0" name="error_msg_cat_exists_input">
                        <span id='error_msg' style="color:red; display:none;margin-left:50px">Please Select a Category</span>
                        <span id='error_msg_cat_exists' style="color:red; display:none;margin-left:50px">This sub category already exists please try another.</span>
                            @else
                            <select class='form-control selectList m0' required><option>Create category first</option></select>

                        @endif
                    </div>
                    <input required="required" id="name" type="text" name="name" placeholder="Add Sub-Category..." class="storeInput fltL mr10 w340">
                    <input type="submit" class="btn blue fltL ADD-Sub" id="add_sub" value="Add" title="Save your New Sub-Category" />
                    <div style="color:red;display: none;width: 190px;padding-top: 45px;" id="alert"></div>
                    {!! Form::close() !!}
                </div>
            @endif

            @if(is_object($allSubCategories))
                <div id="wrap_categories_list">
            @foreach($allSubCategories as $Subcategory)
                <?php
                    if(!isset($Subcategory->id)){continue;}
                ?>
                <div class="categoryList" id="categoryList">
                    <div>{{$Subcategory->name}}</div>
                    @if(Auth::user()->id == $url_user_id)
                        <div class="actW">
                            <a class="js-open-modal" data-modal-id="popup1-{{$Subcategory->id}}" title="Edit {{$Subcategory->name}}" href="#">
                                <span class="editProduct ml20 mr20"></span>
                             </a>
                             <a class="js-open-modal" data-modal-id="popup2-{{$Subcategory->id}}" title="Delete {{$Subcategory->name}}" href="#">
                                  <span class="deleteProduct"></span>
                             </a>
                        </div>
                    @endif
                </div>

                {!! Form::open(array('method'=> 'post','url'=> "store/".Auth::user()->username."/admin/edit/Subcategory/".$Subcategory->id)) !!}
                    @include('Store::includes.Editpop',
                    ['submitButtonText' => 'Update',
                     'title'=>$Subcategory->name,
                     'item' => 'Sub-Category',
                     'allCategories'=>$allCategories,
                     'selectedParentId'=>$Subcategory->category_parent_id,
                     'id' => 'popup1-'.$Subcategory->id])
                {!! Form::close() !!}
                {!! Form::open(array('method'=> 'get','url'=> "store/".Auth::user()->username."/admin/delete/Subcategory/".$Subcategory->id)) !!}
                    @include('Store::includes.deletePop',
                        ['submitButtonText' => 'Delete',
                        'cancelButtonText' => 'Cancel',
                        'title' => 'Delete Sub-Category',
                        'text' => 'Are You Sure You Want To delete This Sub-category? All the Sub-categories and products will also be deleted',
                        'id' => 'popup2-'.$Subcategory->id])
                {!! Form::close() !!}
            @endforeach
                    </div>
            @else
                <div class="categoryList">
                    <h3>You have no sub-categories added, create new one for your store product(s).</h3>
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

        if(isnum === true){
            $('#alert').html('Only numeric is not allowed.').show();
        }else{
            $('#alert').hide();
        }

        if (regex.test(str)) {
            return true;
        }

        e.preventDefault();
        return false;
    });

    $(document).ready(function() {
        var sub = $('#select1').val();
        if(sub > 1){
            getFilteredSubcategory();
        }

        $("form").submit(function() {
            var nameVal = $('#name').val();
            var isnum = /^\d+$/.test(nameVal);
            if(isnum === true){
                $('#alert').html('Only numeric is not allowed.').show();
                return false;
            }
            var subCat = $('.selectList').val();

            if(subCat === undefined){
                $('#alert').html('Select category first.').show();
                return false;
            }
            if(subCat <= 0){
                $('#alert').html('Select category first.').show();
                return false;
            }

                $("#add_sub").prop('disabled',true);
                $("#add_sub").val("Saving..");



        });

    });


$('#add_sub').click(function(){
    var val1 = $('#select1').val();
    var subcatError = $('#error_msg_cat_exists_input').val();

    if (subcatError == 1){
        $('#error_msg_cat_exists').show();
        return false;
    }

    if (val1 == ''){
        $('#error_msg').show();
        return false;
    }
    else{
        $('#error_msg').hide();
        return true;
    }
});
</script>
<script>
    function getFilteredSubcategory(evt){
        var sub = $('#select1').val();
        var Auth_id = '{{Auth::user()->id}}';
        var Auth_user = '{{Auth::user()->username}}';
        jQuery.ajax({
            url: '{{ url("store/".Auth::user()->username."/admin/filteredCategory/") }}',
            type: "Post",
            data: { sub_category: sub},
            success: function (data) {
                $("#wrap_categories_list").html();
                $("#wrap_categories_list").html(data);

            }, error: function (xhr, ajaxOptions, thrownError) {
                alert("ERROR:" + xhr.responseText + " - " + thrownError);
            }
        });
    }

    $("#select1").change(function (evt) {
        getFilteredSubcategory(evt);
    });

    $("#name").keypress(function (evt) {
        var sub = $('#select1').val();
        var subcategory_name = $('#name').val();
        var Auth_id = '{{Auth::user()->id}}';
        var Auth_user = '{{Auth::user()->username}}';

        jQuery.ajax({
            url: '{{ url("store/".Auth::user()->username."/admin/checkIfAlreadySubCatAjax/") }}',
            type: "Post",
            data: { subcategory_name: subcategory_name, owner_id: Auth_id, category_id: sub},
            success: function (data) {
                if(data == 1){
                    $("#error_msg_cat_exists_input").val(1);
                    $("#error_msg_cat_exists").show();
                }else{
                    $("#error_msg_cat_exists_input").val(0);
                    $("#error_msg_cat_exists").hide();
                }

            }, error: function (xhr, ajaxOptions, thrownError) {
                alert("ERROR:" + xhr.responseText + " - " + thrownError);
            }
        });
    });

</script>
@endsection
