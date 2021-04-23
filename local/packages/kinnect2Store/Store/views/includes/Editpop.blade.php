@if(isset($type) && $type == 'ProductReview')

    <div class="modal-box" id="{{$id}}">
         <a href="#" class="js-modal-close close fltR">×</a>
         <div class="modal-body">
             <div class="edit-photo-poup ">
                 <h3 style="color: #0080e8">{{$title}}</h3>
                 <div class="m0">
                    <div>
                        <input type="text" style="display: none" id="stars_rating" name="stars_rating_updated">
                        <img class="rating_stars" src="{!! asset('local/public/assets/images/star.png') !!}" alt="Rating" />
                        <img class="rating_stars" src="{!! asset('local/public/assets/images/star.png') !!}" alt="Rating" />
                        <img class="rating_stars" src="{!! asset('local/public/assets/images/star.png') !!}" alt="Rating" />
                        <img class="rating_stars" src="{!! asset('local/public/assets/images/star.png') !!}" alt="Rating" />
                        <img class="rating_stars" src="{!! asset('local/public/assets/images/star.png') !!}" alt="Rating" />
                    </div>
                 </div>
                 <input type="text" name="edited_review_description" value="{{$description}}" placeholder="{{$description}}" style="width: 300px;"
class="storeInput">
                 <div class="form-container mt10">
                     <div class="saveArea">
                        {!! Form::submit($submitButtonText, ['class' => 'btn blue fltL']) !!}
                        {!! Form::button($cancelButtonText, ['class' => 'btn blue fltL ml10 js-modal-close close']) !!}

                     </div>
                 </div>
             </div>
         </div>
    </div>

@else
    <div class="modal-box" id="{{$id}}">
         <a href="#" class="js-modal-close close fltR">×</a>
         <div class="modal-body">
             <div class="edit-photo-poup ">
                 <h3 class="edit-cat-title" style="color: #0080e8">Edit Category</h3>
                 <div class="m0">
                    @if($item == 'Sub-Category')
                        <div class="mb10">
                             {!! Form::select('category_parent_id',$allCategories, $selectedParentId,
                                ['id'=>'select1' ,
                                    'class' => 'selectList m0 cate-select',
                                    'type' => 'required']) !!}
                        </div>
                    @endif
                 </div>
                 <h3 class="cata-title mt5 fltL">{{$item}} Name:</h3>
                 <input required="required" type="text" id="edited_name" name="edited_name" value="{{$title}}" placeholder="" class="storeInput cata-input"><div class="clrfix"></div>
                 <div class="form-container mt10">
                     <div class="saveArea fltR">
                        {!! Form::submit($submitButtonText, ['class' => 'btn blue fltL' ]) !!}
                     </div>
                 </div>
             </div>
         </div>
    </div>
    <script>
        $('#edited_name').keypress(function (e) {
            var regex = new RegExp("^[a-zA-Z]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }

            e.preventDefault();
            return false;
        });

    </script>
@endif
