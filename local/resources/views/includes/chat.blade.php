<!--<link href="{{ asset('/local/public/css/jquery.tokenize.css') }}" rel="stylesheet">--}}-->
<div class="online-friends-div">
    <div class="online-friends-header" style="cursor: pointer;">
        <em class="online-chat-friend">Friends Online</em>
        <span class="online-friends-count">(0)</span>
    </div>
    <span class="self-online-status" style="cursor: pointer;" title="Change your online status">#</span>
    <div class="friends-list-wrapper" style="display: none;">
    	<div class="online">
        	<span class="active chatFriendsToShow" data-type="kinnector">Kinnector</span>
            <span class="chatFriendsToShow" data-type="brand">Brand</span>
        </div>
        <ul id="friends_online" ></ul>
    </div>
</div>

<div id="chat_windows"></div>
<div><ul id="chat_messages"></ul></div>
