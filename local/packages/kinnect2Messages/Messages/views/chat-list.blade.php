<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kinnect2 Chat</title>
    <script src="{!! asset('local/public/js/jquery-2.1.3.js') !!}"></script>
    <link rel="stylesheet" href="{!! asset('local/public/kinnect2Messages/css/style.css') !!}">
</head>
<body>

<div id="online-users" class="online-users">
    @foreach($onlineUsers as $onlineUser)
        <div class="online-friend" id="{{$onlineUser->username}}">
            <div class="online-user-info online-user-image" id="online-user-image-{{$onlineUser->username}}">
                <img class="online-user-info" id="user-profile-{{$onlineUser->username}}"
                     src="{{asset('local/storage/app/photos')}}/{{$onlineUser->image}}" title="{{$onlineUser->name}}"/>
            </div>

            <div class="online-user-info" id="online-username">
                {{$onlineUser->name}}
            </div>

        </div>
        <div id="userchatwindow-{{$onlineUser->username}}">

        </div>
    @endforeach
</div>


<div class="form" id="i_am" style="display: none;">
    <textarea name="message" class="messagebox" id="messagebox" placeholder="Message:"></textarea>
</div>
<div id="user">
    <input type="text" id="username" value="{{Auth::user()->username}}" placeholder="Username:">
</div>
<h3>I am: {{Auth::user()->username}}</h3>

<div class="container" id="chatcontainer" style="display: none;">
    <div class="chat" id="chatwindow" style="display: none;"></div>
</div>

<div class="chat_window_wrapper"></div>
<script>
    var to = '';
    $('.online-friend').click(function (e) {

        var parentId = $(this).closest('div').prop('id');

        var profileImageSrc = $('#user-profile-' + parentId).attr("src");
        $('.online-friend').css('border', "4px solid red");
        $(this).css('border', "4px solid green");
        to = parentId;


        if ($("#user-item-" + parentId).length < 1) {

            var mesgHtml =
                    '<div class="msg_box" id="user-item-' +parentId+ '" style="right:290px">' +
                        '<div class="msg_head" id="user-name" title="'+parentId+'">'+parentId
                           + '<div class="close">x</div>' +
                        '</div>' +
                        '<div class="msg_wrap">' +
                            '<div class="msg_body">' +
                                '<div class="msg_a">This is from A	</div>' +
                                '<div class="msg_b">This is from B, and its amazingly kool nah... i know it even i liked it :)</div>' +
                                '<div class="msg_a">Wow, Thats great to hear from you man </div>' +
                                '<div class="msg_push"></div> ' +
                            '</div>' +
                            '<div class="msg_footer">' +
                                '<textarea class="msg_input messagebox" rows="4" autofocus name="message" id="messagebox-'+parentId+'" placeholder="Message:"></textarea>' +
                            '</div>' +
                        '</div>' +
                    '</div>';

            /*var mesgHtml = '<div class="online-user-item" id="user-item-'+parentId+'"><span data-usernameminimize="'+parentId+'" class="minimize-chat-window" id="minimize-'+parentId+'">_</span><span data-usernamecross="'+parentId+'" class="cross-chat-window" id="cross-'+parentId+'">x</span><div id="user-info"><div id="user-image" class="chat-user-image"><img src="'+profileImageSrc+'" id="user-image-'+parentId+'" title="'+parentId+'"></div><div id="user-name" title="'+parentId+'">'+parentId+'</div></div><div id="message-body-'+parentId+'"></div><textarea autofocus name="message" class="messagebox" id="messagebox-'+parentId+'" placeholder="Message:"></textarea></div>';*/

            $('.chat_window_wrapper').append(mesgHtml);

            var url = '{{url('chat/online/get-messages')}}/' + parentId;
            var sender_username = '';
            var msgUserImageSrc = '';
            var messageTextBody = '';
            $.getJSON(url, function (responseData) {
                $.each(responseData, function (key, val) {

                    sender_username = $("#username").val();

                    var leftClass = '';
                    if (val.profile_address != sender_username) {
                        leftClass = ' floatLeft';
                        msgUserImageSrc = $('#user-image-' + val.profile_address).attr("src");

                    } else {
                        leftClass = ' floatRight';
                        msgUserImageSrc = '{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_icon')}}';

                    }

                    messageTextBody = '<div class="db-msg-item ' + leftClass + '" id="db-msg-item-' + val.id + '">';
                    messageTextBody += '<div id="user_info_' + val.receiver_id + '"><img src="' + msgUserImageSrc + '" title="' + val.user_name + '">' + val.user_name + '</div><div db-msg-item>' + val.body + '</div>';
                    messageTextBody += '</div>';

                    $("#message-body-" + parentId).append(messageTextBody);
                });
            });
        }


    });

</script>
<script src="{!! asset('local/public/kinnect2Messages/js/Connection.js') !!}"></script>
<script src="{!! asset('local/public/kinnect2Messages/js/app.js') !!}"></script>
<script>
    var currentUserImageSrc = '{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_icon')}}';
</script>
</body>
</html>
