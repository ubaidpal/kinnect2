;(function($, window, document, undefined){
    function initiateChat(io, util){
        if(typeof io == 'undefined'){
            console.log("io, nishta!!!");
            return false;
        }
        console.log("Chat, Initiating ...");
        var logged_in_user_name = $.trim($('#profileLink').text());
        var logged_in_image     = $('#profileLink img').attr('src');
        //var socket = io();
        var socket                 = io('http://localhost:3000');
        var loggedInSocket         = $("#profileLink").data("socket");
        var new_notification_class = 'new_notification';
        chatUsers                  = [];

        var myhost         = window.location.host;
        var ajaxPathPrefix = "/";
        if(myhost == 'localhost'){
            myhost         = myhost + "/kinnect2";
            ajaxPathPrefix = "/kinnect2/";
        }
        //getAllFriends();
        /// -------- Data Getting Functions ---------/////
        function getFriendName(socket){
            if(chatUsers[socket]){
                return chatUsers[socket].displayname;
            }else{
                return false;
            }

        }

        function getFriend(socket){
            if(chatUsers[socket]){
                return chatUsers[socket];
            }else{
                getAllFriends();
                return chatUsers[socket] || false;
            }
        }

        function getAllChatUsers(){
            return chatUsers;
        }

        function getOnlineFriendsCount(){
            return $("#friends_online > li").length;
        }

        function updateUIOnlineCount(){
            setTimeout(function(){
                $(".online-friends-count").text("(" + getOnlineFriendsCount() + ")")
            }, 1000)
        }

        function getAllFriends(params){
            $.ajax({
                type : "POST", url : ajaxPathPrefix + 'messages/friends-detail', //data: {"user_id": 1},
                success : function(data){
                    $.each(data, function(index, value){
                        chatUsers[value.user_id] = value;
                    }); //console.log('*************************************+++++++===========================');

                    //console.log(chatUsers);
                    displayActiveUserHeads();
                    //addFriendOnline(chatUsers);
                    if(params.callback){
                        params.callback(params.friends);
                    }
                }, error : function(status){
                }
            });
        }

        /// ---------- Initial Markup Generators -------------///

        function geChatUsersHtml(skipID){
            var html = '<select multiple="multiple" class="friend-selection-item" style="display: none;">';
            $.each(getAllChatUsers(), function(index, value){
                if(value && (skipID != index)){
                    html += '<option value=' + index + '>' + value.displayname + '</option>';
                }
            });
            html += '</select><input type="button" value="Done" class="friend-selection-done-btn friend-selection-item" style="display: none"/>';

            return html;
        }

        function getChatOptionsHtml(isGroup){
            var html = '<div class="chat-options">';
            if(isGroup){
                html += '<span class="chat-settings">*</span>';
            }
            html += '<span class="add-to-chat">+</span><span class="close-chat">X</span></div>';
            return html;
        }

        function getEditConversationHtml(isGroup){
            var html = "";
            if(isGroup){
                html = '<div class="settings-panel" style="display: none">' + '<div class="settings-options">' + '<div class="leave-it">Leave Conversation</div>' + '<div class="edit-name">Edit Conversation Name</div>' + '</div>' + '<div style="display: none;" class="name-update"> ' + '<input class="group-name-field" placeholder="New Conversation Name"/>' + '<input type="submit" value="Done" class="update-name-btn"/>' + '</div>' + '</div>'
            }

            return html;
        }

        function handleChatView(uiID, displayName, isGroup){

            /// uiID is user interface id of related element;
            console.log('=============+!!!!!!!!');
            var hideChatView = sessionStorage.getItem(loggedInSocket+'-'+"socket-" + uiID) || '';
            var hideCls = '';
            if(hideChatView != ''){
                hideCls = 'hide';
            }
            console.log(hideCls);
            var cls = "one-to-one";
            if(! displayName && ! isGroup){
                displayName = getFriendName(uiID);
            }
            if(isGroup){
                cls = "to-group";
            }

            displayName = displayName || "";

            var chatFriendOptionsHtml = geChatUsersHtml(uiID);
            var socketUIID            = "#socket-" + uiID;
            //$(socketUIID + ' .all-messages-in-box').removeClass('hide');
            if(! $("#socket-" + uiID).length){
                $("#chat_windows").append('<div id="socket-' + uiID + '" class="chat-window ' + cls + '">' + '<div class="chat-window-user"><div class="chat-window-display-name">' + displayName + '</div> ' + getChatOptionsHtml(isGroup) + '</div>' + chatFriendOptionsHtml + '<div class="all-messages-in-box '+hideCls+'"><div class="load-earlier-messages" data-page="1"> Load earlier messages</div>' + ' <div class="messages"></div>' + '<input class="message-attachment" name="message-attachment" type="file" style="position: fixed; top:-35px">' + '<div class="chat-fields-wrapper"> <span class="select-attachment attachment-chat">Attachment</span>' + '<input name="write-message" placeholder="Write message" type="text" data-socket="' + uiID + '" class="msg-socket-' + uiID + '"/> </div></div>' + getEditConversationHtml(isGroup) + '</div>');
                var multiSelect = "#socket-" + uiID + ' > select[multiple]';
                $('.msg-socket-'+uiID).focus();
                if(isGroup){
                    var groupID = uiID.split("-")[1]; // if it is a group then get group id;
                }

                $(socketUIID + " .friend-selection-done-btn").click(function(e){
                    var participants = $(socketUIID + ' > select[multiple]').val();
                    if(participants && participants.length > 0){
                        if(! isGroup){
                            participants.push(uiID);
                        }

                        participants.push(loggedInSocket);
                        if($(socketUIID).hasClass("one-to-one")){

                            $.ajax({
                                type : "POST",
                                url : ajaxPathPrefix + 'messages/create-group',
                                data : {"users" : participants},
                                success : function(data){
                                    var groupID = data.convId;
                                    var title   = data.title;
                                    handleChatView("group-" + groupID, title, true); /// This will shift inside success of ajax that creates a group
                                    socket.emit('join_group', groupID, participants);

                                },
                                error : function(status){
                                }
                            });
                        }else{
                            var groupID = uiID.split("-")[1];
                            socket.emit('join_group', groupID, participants);
                            //$(e.target).hide();
                            /* $.ajax({
                             type: "POST",
                             url: ajaxPathPrefix + 'messages/join-group',
                             data: {"users": [1,2]},
                             success: function (groupID) {
                             handleChatView("group-"+groupID, "Group Chat", true); /// This will shift inside success of ajax that creates a group
                             socket.emit('join_group', groupID, participants);
                             //$(e.target).show();
                             },
                             error: function (status) {
                             }
                             }); */
                        }
                    }

                    $(socketUIID + " .friend-selection-item").hide();
                });
                $(".msg-socket-" + uiID).keyup(function(e){
                    if(e.keyCode == 13 && $.trim(e.target.value)){
                        console.log("going to emit");
                        if(isGroup){
                            console.log(uiID);
                            var groupID = uiID.split("-")[1];
                            socket.emit('group_chat_message', e.target.value, groupID, loggedInSocket);
                            saveMessage({conv_id : groupID, sender_id : loggedInSocket, body : e.target.value});
                        }else{
                            socket.emit('chat_message', e.target.value, uiID, loggedInSocket);
                            saveMessage({receiver_id : uiID, sender_id : loggedInSocket, body : e.target.value});
                        }
                        var message        = {};
                        message['content'] = e.target.value;
                        appendUserMessage(uiID, message, true)
                        e.target.value = "";
                    }
                });
                $("#socket-" + uiID + " .close-chat").click(function(e){
                    $("#socket-" + uiID).remove();
                    var parent = $(this).parents('.chat-window');
                    console.log(parent);
                    sessionStorage.removeItem(loggedInSocket+'-'+parent.attr('id'));
                    if(isGroup){
                        var groupID = uiID.split("-")[1];
                        removeActiveGroupHeadLocally(groupID, loggedInSocket)
                    }else{
                        removeActiveUserHeadLocally(uiID, loggedInSocket)
                    }
                });
                $("#socket-" + uiID + " .add-to-chat").click(function(e){
                    if($(socketUIID + " .friend-selection-item.Tokenize").length){
                        $(socketUIID + " .friend-selection-item.Tokenize").show();
                        $(socketUIID + " .friend-selection-item.friend-selection-done-btn").show();
                    }else{
                        $(socketUIID + " .friend-selection-item").show();
                        $(multiSelect).tokenize();
                    }

                });
                $("#socket-" + uiID + " .chat-settings").click(function(e){
                    $(socketUIID + " .settings-panel").toggle();
                });
                $("#socket-" + uiID + " .edit-name").click(function(e){
                    $(socketUIID + " .name-update").toggle();
                });
                $("#socket-" + uiID + " .update-name-btn").click(function(e){

                    var newName = $.trim($(socketUIID + " .group-name-field").val());
                    if(newName){
                        updateGroupName(groupID, newName)
                        $(socketUIID + " .chat-window-display-name").text(newName);
                    }
                    $(socketUIID + " .name-update").hide();
                });

                $("#socket-" + uiID + " .select-attachment").click(function(e){
                    $("#socket-" + uiID + " .message-attachment").trigger("click")
                });

                $("#socket-" + uiID + " .message-attachment").change(function(e){
                    var files   = e.target.files;
                    var options = {
                        files : files, uiID : uiID, isGroup : isGroup, receiver : uiID
                    };
                    if(isGroup){
                        options.receiver = groupID
                    }
                    sendAttachment(options);
                });

                $("#socket-" + uiID + " .leave-it").click(function(e){
                    leaveGroupConversation(groupID, loggedInSocket);
                    $(socketUIID + " .close-chat").trigger("click");
                });
                $("#socket-" + uiID + " .load-earlier-messages").click(function(e){
                    var page = $(e.target).data("page");
                    if(isGroup){
                        getGroupMessages(groupID, page)
                    }else{
                        getUserMessages(uiID, page);
                    }

                });

                if(isGroup){
                    var groupID = uiID.split("-")[1];
                    saveActiveGroupHeadLocally(groupID, loggedInSocket);
                    getGroupMessages(groupID);
                    getGroupName(groupID);
                }else{
                    saveActiveUserHeadLocally(uiID, loggedInSocket);
                    getUserMessages(uiID, 1);
                }
            }
            $( "#socket-" + uiID+ " .messages" ).on( 'mousewheel DOMMouseScroll', function ( e ) {
                var e0 = e.originalEvent,
                    delta = e0.wheelDelta || -e0.detail;

                this.scrollTop += ( delta < 0 ? 1 : -1 ) * 30;
                e.preventDefault();
            });
        }

        /// ---------- Others --------------------------------//
        function removeActiveGroupHeadLocally(head, loggedInSocket){
            var userKey = "groupheads-" + loggedInSocket;
            removeHeadsLocallyByKey(userKey, head);
        }

        function removeActiveUserHeadLocally(head, loggedInSocket){
            var userKey = "userheads-" + loggedInSocket;
            removeHeadsLocallyByKey(userKey, head);
        }

        function removeHeadsLocallyByKey(userKey, head){
            var existingHeads = sessionStorage.getItem(userKey) || "[]";
            existingHeads     = JSON.parse(existingHeads);
            var index         = existingHeads.indexOf(btoa(head));
            if(index > - 1){
                existingHeads.splice(index, 1);
            }
            existingHeads = JSON.stringify(existingHeads);
            sessionStorage.setItem(userKey, existingHeads);
        }

        function saveActiveGroupHeadLocally(head, loggedInSocket){
            var userKey = "groupheads-" + loggedInSocket;
            saveHeadsLocallyByKey(userKey, head);
        }

        function saveActiveUserHeadLocally(head, loggedInSocket){
            var userKey = "userheads-" + loggedInSocket;
            saveHeadsLocallyByKey(userKey, head);
        }

        function saveHeadsLocallyByKey(userKey, head){  // type: group or user and it's id
            var existingHeads = sessionStorage.getItem(userKey) || "[]";
            existingHeads     = JSON.parse(existingHeads);
            var index         = existingHeads.indexOf(btoa(head));
            if(index > - 1){
                return;
            }
            existingHeads.push(btoa(head));
            existingHeads = JSON.stringify(existingHeads);
            sessionStorage.setItem(userKey, existingHeads);
        }

        function getAllActiveUserHeads(loggedInSocket){
            var userKey = "userheads-" + loggedInSocket;
            return getLocallySavedHeads(userKey);
        }

        function getAllActiveGroupsHeads(loggedInSocket){
            var userKey = "groupheads-" + loggedInSocket;
            return getLocallySavedHeads(userKey);
        }

        function getLocallySavedHeads(userKey){
            var existingHeads = sessionStorage.getItem(userKey) || "[]";
            existingHeads     = JSON.parse(existingHeads);
            var headTokens    = [];
            $.each(existingHeads, function(){
                headTokens.push(atob(this));
            });
            return headTokens;
        }

        function displayActiveUserHeads(){
            setTimeout(function(){
                $.each(getAllActiveUserHeads(loggedInSocket), function(){
                    console.log("showing acitve head view");
                    console.log(this);
                    handleChatView(this);
                });

                $.each(getAllActiveGroupsHeads(loggedInSocket), function(){
                    console.log("showing acitve  group head view");
                    console.log(this);
                    handleChatView("group-" + this, "", true);
                });
            }, 2000);
        }

        function getUserMessages(uid, page){
            if(! page){
                page = 1;
            }
            $.ajax({
                type : "POST", url : ajaxPathPrefix + 'messages/get-thread', data : {
                    "user_1" : loggedInSocket, "user_2" : uid, "page" : page
                }, success : function(data){
                    console.log(data)
                    if(data.constructor === Array){
                        //data.reverse();
                        $("#socket-" + uid + " .load-earlier-messages").data("page", page + 1);
                        $.each(data, function(){
                            if(this.sender_id == loggedInSocket){
                                if(this.file_id){
                                    var attachmentOptions = {
                                        filePath : this.url,
                                        fileName : this.file_name,
                                        from : loggedInSocket,
                                        to : uid,
                                        self : true,
                                        oldMessage : true,
                                        isGroup : false,
                                        uiID : uid
                                    };
                                    attachmentHtml(attachmentOptions);

                                }else{
                                    appendUserMessage(uid, this, true, true);
                                }
                            }else{
                                if(this.file_id){
                                    var attachmentOptions = {
                                        filePath : this.url,
                                        fileName : this.file_name,
                                        from : uid,
                                        to : loggedInSocket,
                                        self : false,
                                        oldMessage : true,
                                        isGroup : false,
                                        uiID : uid
                                    };
                                    attachmentHtml(attachmentOptions);
                                }else{
                                    appendUserMessage(uid, this, false, true);
                                }
                            }
                        });
                    }else{
                        $("#socket-" + uid + " .load-earlier-messages").hide();
                    }

                }, error : function(status){
                }
            });
        }

        function getGroupMessages(groupID, page){
            if(! page){
                page = 1;
            }

            $.ajax({
                type : "POST", url : ajaxPathPrefix + 'messages/get-thread', data : {
                    "group_id" : groupID, "conv_id" : groupID, "page" : page
                }, success : function(data){
                    console.log(data)
                    if(data.constructor === Array){
                        //data.reverse();
                        $("#socket-group-" + groupID + " .load-earlier-messages").data("page", page + 1);
                        $.each(data, function(){
                            if(this.sender_id == loggedInSocket){
                                if(this.file_id){
                                    var attachmentOptions = {
                                        filePath : this.url,
                                        fileName : this.file_name,
                                        from : loggedInSocket,
                                        to : groupID,
                                        self : true,
                                        oldMessage : true,
                                        isGroup : true,
                                        uiID : "group-" + groupID
                                    };
                                    attachmentHtml(attachmentOptions);

                                }else{
                                    appendGroupMessage("group-" + groupID, this, true, true);
                                }
                            }else{
                                if(this.file_id){
                                    var attachmentOptions = {
                                        filePath : this.url,
                                        fileName : this.file_name,
                                        from : this.sender_id,
                                        to : groupID,
                                        self : false,
                                        oldMessage : true,
                                        isGroup : true,
                                        uiID : "group-" + groupID
                                    };
                                    attachmentHtml(attachmentOptions);
                                }else{
                                    appendGroupMessage("group-" + groupID, this, false, true, this.sender_id);
                                }
                            }
                        });
                    }else{
                        $("#socket-group-" + groupID + " .load-earlier-messages").hide();
                    }

                }, error : function(status){
                }
            });
        }

        function getGroupName(groupID){
            $.ajax({
                type : "POST", url : ajaxPathPrefix + 'messages/get-group-name', data : {
                    "conv_id" : groupID
                }, success : function(data){
                    console.log(data.group_name)
                    $("#socket-group-" + groupID + " .chat-window-display-name").text(data.group_name);
                }, error : function(status){
                }
            });
        }

        function updateGroupName(groupID, name){
            $.ajax({
                type : "POST", url : ajaxPathPrefix + 'messages/rename-conversation', data : {
                    "conv_id" : groupID, "name" : name
                }, success : function(data){
                    console.log("Updated-group-name");
                }, error : function(status){
                    console.log("Failed:Updated-group-name");
                }
            });
        }

        function leaveGroupConversation(groupID, userID){
            $.ajax({
                type : "GET",
                url : ajaxPathPrefix + 'messages/leave-group-api/' + groupID + '/' + userID,
                data : {},
                success : function(data){
                    console.log(data.group_name)
                    $("#socket-group-" + groupID + " .chat-window-display-name").text(data.group_name);
                },
                error : function(status){
                }
            });
        }

        function getUserDetailFromServer(users){
            $.ajax({
                type : "POST",
                url : ajaxPathPrefix + 'messages/members-detail',
                data : {users : users},
                success : function(data){
                    console.log("this is user detail")
                    console.log(data);
                    $.each(data, function(){
                        console.log(this.name);
                        console.log(".friend-message.soc-" + this.id);
                        this.displayname   = this.name;
                        chatUsers[this.id] = this;
                        //$(".friend-message.soc-"+this.id).find(".name").text(this.name);
                        $(".friend-message.soc-" + this.id).find(".pic").html("<img width='30px' title='" + this.name + "' src='" + chatUsers[this.id].profile_pic + "'/>");
                    });
                },
                error : function(status){
                }
            });
        }

        function appendUserMessage(uiID, message, self, olderMessage){
            var time           = message.created_at || get_current_time();
            time               = util.getFormattedTime(time);
            message.created_at = time;

            var logged_user_photo = "<span class='pic'><img width='30px' title='" + logged_in_user_name + "' src='" + logged_in_image + "'/></span>";
            if(self){
                if(olderMessage){
                    $('#socket-' + uiID + " > .all-messages-in-box > .messages").prepend("<div class='my-message'><div>" + logged_user_photo + "<span class='msg-content' title='" + message.created_at + "'>" + message.content + "</span></div></div>");
                }else{
                    $('#socket-' + uiID + " > .all-messages-in-box > .messages").append("<div class='my-message'><div>" + logged_user_photo + "<span class='msg-content' title='" + message.created_at + "'>" + message.content + "</span></div></div>");
                }
            }else{
                var userCls = "";
                //var userName = "";
                var userPhoto = "";
                if(! chatUsers[uiID] || ! chatUsers[uiID].profile_pic){
                    console.log("This is my test");
                    console.log(chatUsers[uiID]);
                    if(uiID){
                        userCls = "soc-" + uiID
                        console.log("Before API call for: " + ".friend-message ." + userCls);
                        if($(".friend-message." + userCls).length < 1){
                            console.log("Should Call");
                            getUserDetailFromServer([uiID]);
                        }
                    }
                    userPhoto = "<span class='pic'></span>";
                    //userName  = "<span class='name'></span>";

                }else{
                    userPhoto = "<span class='pic'><img width='30px' title='" + chatUsers[uiID].displayname + "' src='" + chatUsers[uiID].profile_pic + "'/></span>";
                    //userName  = "<span class='name'>"+chatUsers[uiID].displayname+"</span>";
                }
                if(olderMessage){
                    $('#socket-' + uiID + " > .all-messages-in-box > .messages").prepend("<div class='friend-message " + userCls + "'><div>" + userPhoto + "<span class='msg-content' title='" + message.created_at + "'>" + message.content + "</span></div></div>");
                }else{
                    $('#socket-' + uiID + " > .all-messages-in-box > .messages").append("<div class='friend-message " + userCls + "'><div>" + userPhoto + "<span class='msg-content' title='" + message.created_at + "'>" + message.content + "</span></div></div>");
                }

            }
            scroll($('#socket-' + uiID + " > .all-messages-in-box > .messages"));
        }

        function appendGroupMessage(uiID, message, self, oldMessage, from){

            var time = message.created_at || get_current_time();
            // console.log(time);
            time = util.getFormattedTime(time);
            //console.log(time);
            message.created_at    = time;
            var logged_user_photo = "<span class='pic'><img width='30px' title='" + logged_in_user_name + "' src='" + logged_in_image + "'/></span>";
            if(self){
                if(oldMessage){
                    $('#socket-' + uiID + " > .all-messages-in-box > .messages").prepend("<div class='my-message'><div>" + logged_user_photo + "<span class='msg-content' title='" + message.created_at + "'>" + message.content + "</span></div></div>");
                }else{
                    $('#socket-' + uiID + " > .all-messages-in-box > .messages").append("<div class='my-message'><div>" + logged_user_photo + "<span class='msg-content' title='" + message.created_at + "'>" + message.content + "</span></div></div>");
                }

            }else{

                var userCls = "";
                //var userName = "";
                var userPhoto = "";
                console.log("Group Sender:" + from)
                if(! chatUsers[from] || ! chatUsers[from].profile_pic){
                    console.log("This is my test grp");
                    console.log(chatUsers[from]);
                    if(from){
                        userCls = "soc-" + from
                        console.log("Before API call for: " + ".friend-message ." + userCls);
                        if($(".friend-message." + userCls).length < 1){
                            console.log("Should Call");
                            getUserDetailFromServer([from]);
                        }
                    }
                    userPhoto = "<span class='pic'></span>";
                    //userName  = "<span class='name'></span>";

                }else{
                    userPhoto = "<span class='pic'><img width='30px' title='" + chatUsers[from].displayname + "' src='" + chatUsers[from].profile_pic + "'/></span>";
                    //userName  = "<span class='name'>"+chatUsers[uiID].displayname+"</span>";
                }

                if(oldMessage){
                    $('#socket-' + uiID + " > .all-messages-in-box > .messages").prepend("<div class='friend-message " + userCls + "'><div>" + userPhoto + "<span class='msg-content' title='" + message.created_at + "'>" + message.content + "</span></div></div>");
                }else{
                    $('#socket-' + uiID + " > .all-messages-in-box > .messages").append("<div class='friend-message " + userCls + "'><div>" + userPhoto + "<span class='msg-content' title='" + message.created_at + "'>" + message.content + "</span></div></div>");
                }
            }
            scroll($('#socket-' + uiID + " > .all-messages-in-box > .messages"));
        }

        // Scroll to bottom
        function scroll(id){
            id.animate({
                scrollTop : id.prop("scrollHeight")
            }, 0);
        }

        function sendAttachment(options){
            var uiID     = options.uiID;
            var files    = options.files;
            var formData = new FormData();
            formData.append("attachment", files[0]);
            formData.append("sender_id", loggedInSocket);
            formData.append("body", "");

            if(options.isGroup){
                formData.append("conv_id", options.receiver);
            }else{
                formData.append("receiver_id", options.receiver);
            }

            if(formData){
                $("#socket-" + uiID + " .attachment-chat").addClass('attachment-loader');
                $("#socket-" + uiID + " .attachment-chat").removeClass('select-attachment');
                $.ajax({
                    url : this.ajaxPathPrefix + "messages/store", // uploadAttachment
                    type : "POST", data : formData, processData : false, contentType : false, success : function(data){
                        if(data.url){
                            var filePath          = data.url;
                            var fileName          = data.file_name;
                            var attachmentOptions = {
                                filePath : filePath,
                                fileName : fileName,
                                from : loggedInSocket,
                                to : options.receiver,
                                self : true,
                                oldMessage : false,
                                isGroup : options.isGroup,
                                uiID : options.uiID
                            };
                            attachmentHtml(attachmentOptions);
                            attachmentOptions.self = false;
                            socket.emit('send_attachment', attachmentOptions);
                            $("#socket-" + uiID + " .attachment-chat").removeClass('attachment-loader');
                            $("#socket-" + uiID + " .attachment-chat").addClass('select-attachment');
                        }

                    }, error : function(){
                        $("#socket-" + uiID + " .attachment-chat").removeClass('attachment-loader');
                        $("#socket-" + uiID + " .attachment-chat").addClass('select-attachment');
                        alert("its an error");
                    }
                });
            }else{
                alert("Your browser does not support this feature");
            }
        }

        function saveMessage(data){
            if(data){
                $.ajax({
                    url : this.ajaxPathPrefix + "messages/store", // uploadAttachment
                    type : "POST", data : data,

                    success : function(data){
                        console.log(data);
                    }, error : function(){
                        console.log("Message not saved!")
                    }
                });
            }
        }

        function attachmentHtml(options){
            var filePath       = options.filePath;
            var fileName       = options.fileName;
            var uiID           = options.uiID;
            options['content'] = "<a class='attachment-file' download='' href='" + filePath + "'>" + fileName + "</a>";
            if(options.isGroup){

                appendGroupMessage(uiID, options, options.self, options.oldMessage, options.from);
            }else{

                appendUserMessage(uiID, options, options.self, options.oldMessage);
            }
        }

        function get_current_time(){
            var d     = new Date();
            var h     = d.getUTCHours();
            var month = d.getUTCMonth();
            var day   = d.getUTCDay();
            var y     = d.getUTCFullYear();
            var m     = d.getUTCMinutes();
            var s     = d.getUTCSeconds();
            var datec = y + '-' + (month + 1) + '-' + day + ' ' + h + ':' + m + ':' + s;
            datec     = datec.toString();
            //return datec;
            return d.toUTCString();
        }

        /// -------------- IO Events Firing -------------- ///

        socket.emit('come_online', loggedInSocket);
        console.log("done it man.....");

        socket.on('friends_online', function(friends){
            all_online_friends = friends;
            console.log("client-side-event");
            console.log(friends);
            getAllFriends({
                friends : friends, callback : function(friends){
                    $.each(friends, function(){
                        console.log("Here are TYPES");
                        console.log(this);
                        addFriendOnline(this);
                    });
                }
            })

        });

        function addFriendOnline(data){
            setTimeout(function(){
                if(getFriend(data.user_id)){
                    getFriend(data.user_id);
                }
                if($('li[data-socket="' + data.user_id + '"]')[0]){
                    return;
                }
                var userClass;
                if(data.user_type == '1'){
                    userClass = 'kinnector';
                }else{
                    userClass = 'brand';
                }
                var user = getFriend(data.user_id);
                $('#friends_online').append('<li class="' + userClass + ' allOnlineFriends" data-socket="' + data.user_id + '"><img src="' + user.profile_pic + '" width="34px" title="' + data.displayname + '">' + data.displayname + '</li>');
                $('#friends_online > li').unbind("click").bind("click", function(){
                    var friendSocket = $(this).data("socket");
                    var displayName  = $(this).text();
                    handleChatView(friendSocket, displayName);
                });
                updateUIOnlineCount();
            }, 2000)
        }

        socket.on('all_friends', function(friends){
            console.log(friends)
            /*chatUsers = friends;
             $.each(friends, function()){
             if(!getFriend(this.user_id)){
             getFriend(this.user_id, this)
             }
             }*/
            displayActiveUserHeads();
        });

        var playNotification = function($id){

            var hidden, state;

            if(typeof document.hidden !== "undefined"){
                state = "visibilityState";
            }else if(typeof document.mozHidden !== "undefined"){
                state = "mozVisibilityState";
            }else if(typeof document.msHidden !== "undefined"){
                state = "msVisibilityState";
            }else if(typeof document.webkitHidden !== "undefined"){
                state = "webkitVisibilityState";
            }

            if($($id).children('.all-messages-in-box').hasClass('hide')){
                $($id + ' .chat-window-user').addClass(new_notification_class)
            }
            if(document[state] == "hidden"){
                $('.play-notification').trigger("play");
            }

            /*else
             document.title = "Active";*/
        };
        socket.on('chat_message', function(msg, from){
            console.log(msg + "  >>> FROM:" + from);
            playNotification("#socket-" + from);
            var message        = {};
            message['content'] = msg;
            if($("#socket-" + from).length){
                appendUserMessage(from, message, false);
            }else{
                setTimeout(function(){
                   handleChatView(from);
               },100);

            }

        });

        socket.on('group_chat_message', function(msg, from, group){
            console.log("chat group: " + group + " >>>>> , message:" + msg + ",,, Frome : " + from);
            playNotification("#socket-group-" + group);
            if(from != loggedInSocket){
                if(! $("#socket-group-" + group).length){
                    handleChatView("group-" + group, "GROUP CHAT", true);
                }else{
                    var data     = {};
                    data.content = msg;
                    appendGroupMessage("group-" + group, data, false, false, from)
                }

            }
        });

        socket.on('friend-come-online', function(friend){
            console.log(friend);
            var friendInfo = getFriend(friend);
            if(friendInfo){
                addFriendOnline(friendInfo);
            }
            updateUIOnlineCount();
            //handleChatView(from);
            //$('#socket-'+from+" > .messages").append("<div class='friend-message'>"+friend+"</div>");
        });

        socket.on('friend-go-offline', function(friend){
            $('li[data-socket="' + friend + '"]').remove();
            updateUIOnlineCount();
            //  handleChatView(from);
            //  $('#socket-'+from+" > .messages").append("<div class='friend-message'>"+msg+"</div>");
        });

        socket.on('receive_attachment', function(attachmentOptions){
            //playNotification();

            if(attachmentOptions.isGroup){
                console.log(attachmentOptions.from + " ----- " + loggedInSocket)
                if(attachmentOptions.from != loggedInSocket){
                    attachmentOptions.uiID = "group-" + attachmentOptions.to;
                    playNotification("#socket-" + attachmentOptions.uiID);
                }
            }else{

                attachmentOptions.uiID = attachmentOptions.from
                playNotification("#socket-" + attachmentOptions.uiID);
            }
            attachmentHtml(attachmentOptions);
        });

        socket.on('alive_again', function(status){

            socket.emit('come_online', loggedInSocket);
        });

        socket.on('status', function(options){
            console.log("this is status offline");
            console.log(options);
            var dEl = $(".chat-wrapper");
            dEl.removeClass("not-ready");
            if(!options.status){
                dEl.addClass("offline").removeClass("online");
            }else{
                dEl.addClass("online").removeClass("offline");
            }
        });

        /// --------------- Initial Runners -----------------///

        updateUIOnlineCount();
        $(document).ready(function(){
            $(".online-friends-header").click(function(){
                $(".friends-list-wrapper").toggle();
            });

            $(".self-online-status").click(function(e){
               // $(".friends-list-wrapper").toggle();
                e.stopPropagation();
                var dEl = $(".chat-wrapper");
                if(dEl.hasClass("not-ready")){
                    //return false;
                }
                var currentStatus = dEl.hasClass("offline");
                if(!currentStatus){
                    dEl.addClass("offline");
                    dEl.removeClass("online");
                    socket.emit('setStatus', {socketId: loggedInSocket, online: false });
                    $("#chat_windows .chat-window").each(function(){ console.log("This is test"); $(this).remove()})
                }else{
                    dEl.addClass("online");
                    dEl.removeClass("offline");
                    socket.emit('setStatus', {socketId: loggedInSocket, online: true });
                }
                console.log("online:"+ (!dEl.hasClass("online")));


            });

            $(".conv-chat-trigger").click(function(){
                if($(this).data("type") == "group"){
                    handleChatView("group-" + $(this).data("group"), "", true);
                }else{
                    handleChatView($(this).data("user"));
                }
            });

            $(document).on('click', '.chat-window-user', function(event){
                var classTarget = $(event.target).attr('class');
                if(classTarget == 'add-to-chat' || classTarget == 'chat-settings'){
                    return;
                }
                $(this).removeClass(new_notification_class);
                var box = $(this).siblings('.all-messages-in-box');
                var child = box.find('.messages');
                var parent = box.parent();

                if(box.hasClass('hide')){
                    box.removeClass('hide');
                    sessionStorage.removeItem(loggedInSocket+'-'+parent.attr('id'));
                    scroll(child);
                }else{
                    box.addClass('hide');
                    if(classTarget != 'close-chat'){
                        sessionStorage.setItem(loggedInSocket+'-'+parent.attr('id'),loggedInSocket+'-'+parent.attr('id'));
                    }

                }
            });
            $(document).on('click', function(e){
                var clsNotClopsAble = ['chat-window-user', 'chat-settings', 'add-to-chat', 'chat-window-display-name', 'online-friends-header', 'chatFriendsToShow active', 'kinnector allOnlineFriends', 'brand allOnlineFriends', 'conv-chat-trigger', 'online-chat-friend', 'online-friends-count', 'btn fltR mr5 chat-trigger message-btn', 'close-chat'];

                var $tgt         = $(e.target);
                var classTrigger = $(e.target).attr('class');
                //alert(classT);
                var container = $('.chat-window-user');
                if(! $tgt.closest(".all-messages-in-box").length && $.inArray(classTrigger, clsNotClopsAble) == - 1){
                    //alert($( e.target ).attr( 'class' ));
                    $.each(container, function(){
                        var parent = $(this).parent();
                        sessionStorage.setItem(loggedInSocket+'-'+parent.attr('id'),loggedInSocket+'-'+parent.attr('id'));
                        $(this).siblings('.all-messages-in-box').addClass('hide');
                    })
                }

            });

            $('.chatFriendsToShow').click(function(){
                var type = $(this).data('type');
                $('.chatFriendsToShow').removeClass('active');
                $(this).addClass('active');
                $('.allOnlineFriends').hide();
                $('.' + type).show();
            });


        });
        window.chatInitiated = true;
    }

    $.fn.initiChat = initiateChat;
})(jQuery, window, document);
