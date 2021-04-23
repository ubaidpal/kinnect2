<div class="modal-box" id="{{$id}}">
<a href="#" class="js-modal-close close">×</a>

 <div class="modal-body">
     <div class="edit-photo-poup">
         <h3 style="color: #0080e8">{{$title}}</h3>
         <p class="mt10" style="width: 315px;line-height: normal">{{$text}}</p>
         <div class="wall-photos">
             <div class="photoDetail">
                 <div class="form-container">
                     <div class="saveArea">
                        {!! Form::submit($submitButtonText, ['class' => 'btn fltL blue mr10']) !!}
                        {!! Form::button($cancelButtonText, ['class' => 'btn blue js-modal-close fltL close']) !!}

                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
</div>
