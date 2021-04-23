{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 06-1-16 2:28 PM
    * File Name    : 

--}}
<?php
$userForChat = '';
if ($conv_type == 'couple') {
    $userForChat = array_diff($users_id, [Auth::user()->id]);
    $userForChat = implode(',', $userForChat);
}
?>
@if(isset($messages))
    <?php $current_sender = 0;?>
    @foreach($messages as $row)
        <?php
        if ($current_sender == 0 || $current_sender != $row->getSender()) {
            $current_sender = $row->getSender();
        }
        ?>
        @include('templates.partials.ajax.conversation-messages-all',['row' => $row])

    @endforeach
@else
    There is no message

@endif

@if(empty($repeat))
    <script type="text/javascript">
        $(function(){
            $('.conv-id').val('{{$conv_id}}');
            $('#groupId').val('{{$conv_id}}');
            $('#chat_type').val('{{$conv_type}}');
            $('#userForChat').val('{{$userForChat}}');
        });
    </script>
@endif