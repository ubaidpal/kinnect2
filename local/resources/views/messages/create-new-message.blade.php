{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 07-1-16 12:20 PM
    * File Name    : 

--}}

<div class="new-message-field">
    <select id="friends" multiple="multiple" class="tokenize-sample" name="members[]">
        @foreach($friends as $row)
            <option value="{{$row->user_id}}">{{$row->displayname}}</option>
        @endforeach
    </select>
</div>


