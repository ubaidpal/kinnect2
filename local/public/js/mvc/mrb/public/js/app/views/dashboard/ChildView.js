define(['App', 'backbone', 'marionette', 'jquery', 'underscore', 'models/Model', 'collections/Collection', 'views/dashboard/fileUploads/ContainerView', 'text!templates/dashboard/postContainer.html', 'text!templates/dashboard/comment.html', 'text!templates/dashboard/writeComment.html', "flowplayer", "jplayer", "linkify",'mediaElement'],
    function (App, Backbone, Marionette, $, _, Model, Collection, UploadsContainerView, postContainer, comment, writeComment, flowplayer, jplayer, linkify) {
        //ItemView provides some default rendering logic

        return Backbone.Marionette.CompositeView.extend({
            // template: _.template(template),
            getTemplate: function () {
                if (this.model.get("postLevel")) {
                    return _.template(postContainer);
                } else if (this.model.get("writeCommentNode")) {
                    return _.template(writeComment);
                } else {
                    return _.template(comment);
                }
            },

            className: function () {
                var cls = "comment-item";
                if (this.model.get("comments")) {
                    cls = "post-box";

                    var subjectType = this.model.get("subject_type");
                    subjectType = subjectType.substring(4);
                    if(subjectType == "Consumer"){
                        cls += " consumer";
                    }else{
                        cls += " brand";
                    }
                }else if(this.model.get("isReply")){
                    cls += " reply-comment"
                }
                return cls;
            },

            itemViewOptions: function () {
                var options = {k2App: this.k2App};
                options.parentModel = this.model;
                options.parentView = this;
                return options;
            },


            serializeData: function () {

                var fav_cls, like_cls, dislike_cls = flag_cls = "";
                var comment_like_cls = "not-active";

                /// Related to Post Nodes

                if (this.model.get("post_fav")) {
                    fav_cls = "active";
                }
                if (this.model.get("post_liked")) {
                    like_cls = "active";
                }
                if (this.model.get("post_disliked")) {
                    dislike_cls = "active";
                }
                if(this.model.get('post_reported')){
                    flag_cls = 'active';
                }
                var userPhoto = "local/public/images/login-page/upload-img.png";
                //this.model.get("base_url")+"/local/storage/app/photos/0/default_male_user_profile_photo.svg"
                //this.model.get("base_url")+"/local/storage/app/photos/0/default_female_user_profile_photo.svg"
                //this.model.get("base_url")+"/local/storage/app/photos/0/default_brand_profile_photo.svg"
                var subjectType = this.model.get("subject_type") || this.model.get("poster_type");
                var baseURL = "";
                if(this.model.get("base_url") && typeof this.model.get("base_url") != 'undefined'){
                    baseURL = this.model.get("base_url");
                }
                if(subjectType){
                    subjectType = subjectType.substring(4);
                    if(subjectType == "Consumer"){
                        userPhoto = baseURL+"/local/storage/app/photos/0/default_male_user_profile_photo.svg";
                        if(this.model.get("subject_gender") == "2"){
                            userPhoto = baseURL+"/local/storage/app/photos/0/default_female_user_profile_photo.svg";
                        }
                    }else {
                        userPhoto = baseURL+"/local/storage/app/photos/0/default_brand_profile_photo.svg";
                    }
                }


                if (this.model.get("subject_photo_path")) {
                    userPhoto = this.model.get("subject_photo_path");
                }
                if (!this.model.get("postLevel") && this.model.get("poster_photo_path")) {
                    userPhoto = this.model.get("poster_photo_path");
                }
                if(this.model.get("postLevel") && this.model.get("post_type") == 'friends'){
                   var result = this.model.get("post_body").replace("{item:$object}", "");
                    result = result.replace("{item:$subject}.", "");
                   this.model.set("modified_post_body", result);
                }

                if(this.model.get("created_at")){
                    this.model.set("formatted_created_date", this.k2App.util.getFormattedTime(this.model.get("created_at")));
                }
                if(this.model.get("link_type") == "youtube") {
                    this.model.set("link_vid", this.k2App.util.youtube_parser(this.model.get("object_uri")));
                }else if(this.model.get("link_type") == "vimeo"){
                    this.model.set("link_vid", this.k2App.util.vimeo_parser(this.model.get("object_uri")));
                }
                var data = this.model.toJSON();
                data.fav_cls = fav_cls;
                data.like_cls = like_cls;
                data.dislike_cls = dislike_cls;

                data.body = this.k2App.util.nl2br(this.model.get('body'));
                if(typeof data.post_body != 'undefined') {
                    // data.post_body = this.k2App.util.escapeHtml(data.post_body);
                }
                if(this.model.get('post_owner_body')){
                    data.post_owner_body = this.k2App.util.nl2br(this.model.get('post_owner_body'));
                    //data.post_owner_body = this.k2App.util.escapeHtml(data.post_owner_body);
                }

                data.userPhoto = userPhoto;

                if (this.model.get("comment_liked")) {
                    comment_like_cls = "active";
                }
                /// Related to comments Nodes
                data.comment_like_cls = comment_like_cls;
                if(this.model.get("postLevel")){
                    data.isAuthenticatedUser = !this.model.get("anonymousUser");
                    this.model.set('isAuthenticatedUser',!this.model.get("anonymousUser"));
                }else{
                    data.isAuthenticatedUser = !this.options.parentModel.get("anonymousUser");
                    this.model.set('isAuthenticatedUser',!this.options.parentModel.get("anonymousUser"));
                }

                return data;

            },
            onShow: function () {

                this.k2App.views.editUploadsContainerView = new UploadsContainerView({k2App:this.k2App});
                var itemViewContext = this;
                if ($(this.el).find(".audio-post") && this.model.get("post_type") == 'audio_new') {

                    $("#jquery_jplayer_"+this.model.get("post_id")).jPlayer({
                        ready: function (event) {
                            $(this).jPlayer("setMedia", {
                                //title: "Bubble",
                                mp3: itemViewContext.model.get("object_path")

                            });
                        },
                        play : function(){
                            $(this).jPlayer("pauseOthers"); // pause all players except this one.
                        },
                        solution:"flash,html",
                        swfPath: "https://www.kinnect2.com/local/public/js/mvc/mrb/public/js/libs/jplayer/",
                        supplied: "mp3",
                        wmode: "window",
                        useStateClassSkin: true,
                        autoBlur: false,
                        smoothPlayBar: true,
                        keyEnabled: true,
                        remainingDuration: true,
                        toggleDuration: true,
                        cssSelectorAncestor: "#jp_container_"+this.model.get("post_id")
                    });
                }

                if(this.model.get("post_type") == "product_create"){

                    $(this.el).find(".pap-img").bxSlider({
                        pagerCustom: "."+this.model.get("post_id")+"-"+this.model.get("object_id")+"-thumbs",
                        controls: false,
                        onSliderLoad: function(){
                            $(".pap-img").css("transform", "translate3d("+($(".pap-img img:eq(0)").width()*-1)+"px, 0px, 0px)");
                        }
                    });


                }
            },

            onDomRefresh: function () {
                var itemViewContext = this;
                var vidEl = $(this.el).find(".flowplayer-"+ this.model.get("post_id"));
                if (vidEl) {
                    if(!$(vidEl).hasClass("flowplayer") && this.model.get("post_id") != 'undefined' && typeof this.model.get("post_id") != 'undefined' ){
                        var $video = $(".flowplayer-" + this.model.get("post_id")).find('video');
                        $video.mediaelementplayer();
                        //flowplayer({autoBuffering: false, key: "$103665719304012" });
                    }

                }else {

                }



                
                $(this.el).find('.posted-text').linkify({
                    target: "_blank"
                });
            },
            initialize: function (options) {
                this.k2App = options.k2App;
                var that = this;

                if (this.model.get("postLevel")) {
                    // this.itemViewContainer = ".comment-container";
                    this.collection = new Collection(this.model.get("comments"));
                    this.collection.forEach(function(model, index) {
                        model.set("isAuthenticatedUser" , that.model.get("isAuthenticatedUser"));
                    });
                   if(this.model.get("object_comment_permission") && ($("#group-details-wrapper").length < 1 || $("#group-details-wrapper").data("comment-permission") )){
                       this.collection.add({"writeCommentNode": 1});
                   }
                }else if (this.model.get("isReply")) {
                    this.itemViewContainer =  ".comment-replies";
                    this.collection = new Collection([]);
                    if(this.model.get("object_comment_permission") && ($("#group-details-wrapper").length < 1 || $("#group-details-wrapper").data("comment-permission") )){
                        // Code to show reply button
                    }
                }

                this.model.on('change', this.render, this);
                //this.model.on('change', function(){ if(this.model.get("postLevel")){this.render()}}, this);
            },

            // View Event Handlers
            events: {
                "click .like-post": "handlePostLikeEvent",
                "click .dislike-post": "handlePostDislikeEvent",
                "click .favourite-post": "handlePostFavouriteEvent",
                "click .share-post-kinnct": "openModal",
                "click .social-share-post": "socialSharePost",
                "click .flag-post": "openModal",
                "click .send-comment": "sendComment",
                "keydown .write-comment-box" : "handleCommentEvent",
                "click .like-comment.not-active": "likeComment",
                "click .like-comment.active": "unlikeComment",
                "click .hidden-comment-submit": "sendComment",
                "click .delete-post": "deletePost",
                "click .battle-voting" : "VoteBattle",
                "click .poll-voting" : "votePoll",
                "click .light-box-item" : "showPostDetail",
                "click .delete-comment" : "deleteComment",
                "click .post-likes" : "getPostLikes",
                "click .product-title" : "goToProduct",
                "click .edit-post" : "editPost",
                "click .edit-delete-photo" : "editDeleteObject",
                "click .post-dislikes" : "getPostDislikes",
                "click .comment-threaded" : "showCommentBox",
                "keydown .wrtite-comment-threaded" : "saveCommentThreaded",
                "click .select-attachment" : "triggerFileSelect",
                "change .comment-attachment" : "readURL",
                "click .remove-comment-attachment" : "removeAttachment"

            },
            removeAttachment : function (e) {
                e.preventDefault();
                $(this.el).find('.attachment-preview-box').css('display','none');
                var control = $(this.el).find('.comment-attachment');
                control.replaceWith( control = control.clone( true ) );
            },
            readURL : function(e) {
                var elem = $(this.el);
                if ($(e.target)[0].files && $(e.target)[0].files[0]) {
                    var reader = new FileReader();
    
                    reader.onload = function (e) {

                        elem.find('.preview').attr('src', e.target.result);

                    }
    
                    reader.readAsDataURL($(e.target)[0].files[0]);
                    elem.find('.attachment-preview-box').css('display','');
                }
            },
            triggerFileSelect : function (e) {
                e.preventDefault();
                e.stopPropagation();
                jQuery(this.el).find('input[type="file"]').trigger('click');
            },
            saveCommentThreaded : function (e) {
                e.stopPropagation();
                var comment_id = this.model.get("comment_id");
                var post_id = this.model.get('resource_id');
                var elem = jQuery(e.target);
                context = this;
                if(e.keyCode == 13 && !e.shiftKey){
                    var comment = elem.val();
                    $(this.el).find(".disable-required").attr("disabled", true).css("color", "lightgrey");
                    this.k2App.collections.posts.saveCommentThreaded({
                        postID : post_id,
                        commentID : comment_id,
                        comment : comment,
                        context: this,
                        successCallback: function(result, context){
                            result.comment.isReply = true;
                            context.collection.add(result.comment);
                            context.render();
                            context.model.set('reply_count',context.model.get('reply_count') + 1);
                            jQuery(context.el).find('.wrtite-comment-threaded').css('display','');
                            var reply_count = context.model.get('reply_count');
                            var html = '';
                            if(reply_count > 1){
                                html = reply_count+' Replies';
                            }else {
                                html = reply_count + ' Reply';
                            }
                            jQuery('.comment-threaded-' + context.model.get('comment_id')).text(html);
                            $(this.el).find(".disable-required").attr("disabled", true).css("color", "lightgrey");
                        }
                    });
                }
            },
            showCommentBox : function (e) {
                e.preventDefault();
                e.stopPropagation();
                jQuery(this.el).find('.wrtite-comment-threaded').css('display','');
                comment_id = this.model.get('comment_id');
                if(jQuery(this.el).find('.comment-replies').html() == '') {
                    this.k2App.collections.posts.getCommentsThreaded({
                        commentID: comment_id,
                        context: this,
                        successCallback: function (response, context) {
                            context.itemViewContainer = ".comment-replies";
                            $.each(response, function () {
                                this.isReply = true;
                            });
                            ttzz = context;
                            context.collection = new Collection([]);
                            context.collection.add(response);
                            context.render();
                            jQuery(context.el).find('.wrtite-comment-threaded').css('display', '');
                        }
                    });
                }
            },
            editPost : function (e) {
                jQuery('#post_edit_text').val('');
                e.preventDefault();
                var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
                jQuery('body').append(appendthis).css({'overflow-y':'hidden'});
                jQuery(".modal-overlay").fadeTo(500, 0.7);
                jQuery('#edit_post_container').fadeIn(500);
                //this.k2App.collections.fileUploads.reset([]);
                // this.k2App.App.addRegions({
                //     edit_files_container:"#edit_files_container"
                // });
                $('.edit-post-save').data('executing',false);
                //this.k2App.views.editUploadsContainerView = new FileContainerView({k2App:this.k2App});
                //this.k2App.views.editUploadsContainerView = new UploadsContainerView({k2App:this.k2App});
                //this.k2App.App.edit_files_container.show(this.k2App.views.editUploadsContainerView); //k2App

                this.k2App.collections.posts.getPost({
                    postID : this.model.get('post_id'),
                    k2App : this.k2App,
                    successCallback : function (response,k2App) {
                        jQuery('#post_edit_text').val(response.post.body);
                        jQuery('#editPostIdentifier').val(response.post.action_id);

                        // if(response.post.type == 'album_photo_new'){
                        //     //this.k2App.collections.fileUploads.add(response.photos)
                        // }
                        // this.k2App.collections.fileUploads.add({"newUpload": true});
                    }
                })
            },
            editDeleteObject : function (e) {
                var $target = jQuery(e.target);
            },
            getPostLikes : function (e) {
                e.preventDefault();
                jQuery('#likes_container').fadeOut(500);
                var post_id = this.model.get('post_id');
                this.k2App.collections.posts.getPostLikes({
                    postID : post_id,
                    successCallback : function(response){
                        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
                        $('body').append(appendthis).css({'overflow-y':'hidden'});
                        $(".modal-overlay").fadeTo(500, 0.7);
                        jQuery('#likes_container').fadeIn(500);
                        myHtml = '<div class="like-box">';
                        myHtml += '<div class="like-box-heading">';
                        myHtml += '<span>Likes</span>';
                        
                        myHtml += '<a data-id="'+post_id+'" href="#" class="post-dislikes-tab">Dislikes</a>';
                        myHtml += '</div>';

                        if(response.likes.length > 0) {
                            jQuery.each(response.likes, function (elem, obj) {
                                myHtml += '<div class="like-dislike-users">';
                                myHtml += '<a class="user-photo" href="' + response.base_url + '/profile/' + obj.liker.username + '">';
                                myHtml += '<img src="' + obj.photo_url + '" width="50"/>';
                                myHtml += '</a>';
                                myHtml += '<a class="user-name" href="' + response.base_url + '/profile/' + obj.liker.username + '">';
                                if (obj.liker.user_type == 2) {
                                    myHtml += obj.liker.displayname;
                                } else {
                                    myHtml += obj.liker.first_name + ' ' + obj.liker.last_name;
                                }
                                myHtml += '</a>';
                                myHtml += '</div>';
                            });
                        }else{
                            myHtml += '<div class="like-dislike-users">';
                            myHtml += '<p>No post like</p>';
                            myHtml += '</div>';
                        }
                        myHtml += '</div>';

                        jQuery('#post_likes_users').html(myHtml);
                    }
                });
            },
            getPostDislikes : function (e) {
                e.preventDefault();
                jQuery('#likes_container').fadeOut();
                var post_id = this.model.get('post_id');
                this.k2App.collections.posts.getPostDislikes({
                    postID : post_id,
                    successCallback : function(response){
                        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
                        $('body').append(appendthis).css({'overflow-y':'hidden'});
                        $(".modal-overlay").fadeTo(500, 0.7);
                        jQuery('#likes_container').fadeIn(500);
                        myHtml = '<div class="like-box">';
                        myHtml += '<div class="like-box-heading">';
                        myHtml += '<a data-id="'+post_id+'" href="#" class="post-likes-tab">Likes</a>';
                        myHtml += '<span>Dislikes</span>';
                        myHtml += '</div>';
                        if(response.likes.length > 0) {
                            jQuery.each(response.likes, function (elem, obj) {
                                myHtml += '<div class="like-dislike-users">';
                                myHtml += '<a class="user-photo" href="' + response.base_url + '/profile/' + obj.disliker.username + '">';
                                myHtml += '<img src="' + obj.photo_url + '" width="50"/>';
                                myHtml += '</a>';
                                myHtml += '<a class="user-name" href="' + response.base_url + '/profile/' + obj.disliker.username + '">';
                                if (obj.disliker.user_type == 2) {
                                    myHtml += obj.disliker.displayname;
                                } else {
                                    myHtml += obj.disliker.first_name + ' ' + obj.disliker.last_name;
                                }
                                myHtml += '</a>';
                                myHtml += '</div>';
                            });
                        }else{
                            myHtml += '<div class="like-dislike-users">';
                            myHtml += '<p>No post dislike</p>';
                            myHtml += '</div>';
                        }
                        myHtml += '<div>';

                        jQuery('#post_likes_users').html(myHtml);
                    }
                });
            },
            isAuthenticatedUser: function(){
                return false;
            },
            handleCommentEvent : function(e){
                if (e.keyCode == 13 && e.shiftKey) {
                    var s = $(this).val();
                    $(this).val(s+"\n");
                }else if(e.keyCode == 13 && !e.shiftKey){
                    this.sendComment(e);
                }
            },
            showPostDetail : function(e){
                if(this.model.get("isAuthenticatedUser")){
                    Backbone.history.navigate("/");
                    $('#selectedIndex').val($(e.target).data('index'));
                    Backbone.history.navigate("post/"+this.model.get("post_id"), true);
                    $('body').css({'overflow-y':'hidden'});
                }
                return false;
                //var post_id = this.model.get("post_id");
                //this.k2App.collections.posts.getPostDetails({
                //    post_id : post_id,
                //    successCallback : function(response){
                //        console.log(response);
                //    }
                //});
            },

            goToProduct: function(){
                window.location = this.model.get("base_url")+"/product/"+this.model.get("object_id")
            },

            VoteBattle : function(e)
            {
                var target = jQuery(e.target);

                option = target.val();

                if(option)
                {
                    this.k2App.collections.posts.voteBattle({
                        option : option,
                        postID : this.model.get('post_id'),
                        view: this,
                        successCallback : function(response,model, view){
                            view.model.set(response);
                        }
                    });
                }
            },
            votePoll : function(e){
                var target = jQuery(e.target);

                option = target.val();

                if(option)
                {
                    this.k2App.collections.posts.votePoll({
                        option : option,
                        postID : this.model.get('post_id'),
                        view: this,
                        successCallback : function(response,model, view){
                            view.model.set(response);
                        }
                    });
                }
            },
            openModal : function (e) {
                e.preventDefault();

                var appendthis = ("<div class='modal-overlay js-modal-close'></div>");

                var target = jQuery(e.target);

                identifier = target.attr('data-id');

                $("body").append(appendthis);
                $(".modal-overlay").fadeTo(500, 0.7);
                if(identifier == 'reShare'){
                    $('#ObjectType').val(this.model.get('object_type'));
                    $('#objectIdentifier').val(this.model.get('object_id'));
                }else {
                    $('#postIDentifier').val(this.model.get('post_id'));
                }
                
                $('#' + identifier + '_popup').fadeIn($('#'+ identifier +'_popup').data());

                $('body').css({'overflow-y':'hidden',});

                $(window).resize(function () {
                    $(".modal-box").css({
                        top: ($(window).height() - $(".modal-box").outerHeight()) / 3,
                        left: ($(window).width() - $(".modal-box").outerWidth()) / 2
                    });
                });

                $(window).resize();

            },

            deletePost: function(){

                var postsCollection = this.k2App.collections.posts;
                var context = this;
                this.k2App.util.getConfirmationPopup({
                    text: "Are you sure you want to delete this post?",
                    confirmCallback: function(){
                        postsCollection.deletePost(
                            {
                                postID: context.model.get("post_id"),
                                model: context.model,
                                successCallback: function (collection, model) {
                                    collection.remove(model);
                                }
                            }
                        );
                    }
                });
            },

            handlePostLikeEvent: function (e) {
                if ($(e.target).hasClass("active")) {
                    this.unlikePost(e);
                } else {
                    this.likePost(e);
                }
            },

            likePost: function () {

                if($(this.el).find(".dislike-post").hasClass("active")){
                    var newCount = this.model.get("post_dislike_count") - 1;
                    this.model.set("post_dislike_count", newCount, {silent: true});
                    $(this.el).find(".dislikes-count").text(newCount);
                }
                this.k2App.collections.posts.likePost(
                    {
                        postID: this.model.get("post_id"),
                        successCallback: function (collection, postID,response) {
                            collection.where({post_id: postID})[0].set('likes',response.likes);
                            collection.where({post_id: postID})[0].set("post_like_count", parseInt(collection.where({post_id: postID})[0].get("post_like_count")) + 1);
                            collection.where({post_id: postID})[0].set("post_liked", 1);
                            collection.where({post_id: postID})[0].set("post_disliked", 0);
                        }
                    }
                );

            },

            unlikePost: function () {

                this.k2App.collections.posts.unlikePost(
                    {
                        postID: this.model.get("post_id"),
                        successCallback: function (collection, postID,response) {
                            collection.where({post_id: postID})[0].set('likes',response.likes);
                            collection.where({post_id: postID})[0].set("post_like_count", collection.where({post_id: postID})[0].get("post_like_count") - 1);
                            collection.where({post_id: postID})[0].set("post_liked", 0);
                        }
                    }
                );

            },

            handlePostDislikeEvent: function (e) {
                if ($(e.target).hasClass("active")) {
                    this.undoDislikePost(e);
                } else {
                    this.dislikePost(e);
                }
            },

            dislikePost: function () {
                if($(this.el).find(".like-post").hasClass("active")){
                    var newCount = this.model.get("post_like_count") - 1;
                    this.model.set("post_like_count", newCount, {silent: true});
                    $(this.el).find(".likes-count").text(newCount);
                }
                this.k2App.collections.posts.dislikePost(
                    {
                        postID: this.model.get("post_id"),
                        successCallback: function (collection, postID,response) {
                            collection.where({post_id:postID})[0].set('likes',response.likes);
                            collection.where({post_id: postID})[0].set("post_dislike_count", parseInt(collection.where({post_id: postID})[0].get("post_dislike_count")) + 1);
                            collection.where({post_id: postID})[0].set("post_disliked", 1);
                            collection.where({post_id: postID})[0].set("post_liked", 0);
                        }
                    }
                );

            },

            undoDislikePost: function () {

                this.k2App.collections.posts.undoDislikePost(
                    {
                        postID: this.model.get("post_id"),
                        successCallback: function (collection, postID,response) {
                            collection.where({post_id:postID})[0].set('likes',response.likes);
                            collection.where({post_id: postID})[0].set("post_dislike_count", collection.where({post_id: postID})[0].get("post_dislike_count") - 1);
                            collection.where({post_id: postID})[0].set("post_disliked", 0);
                        }
                    }
                );

            },

            handlePostFavouriteEvent: function (e) {
                if ($(e.target).hasClass("active")) {
                    this.unFavouritePost(e);
                } else {
                    this.favouritePost(e);
                }
            },
            favouritePost: function (e) {
                this.k2App.collections.posts.favouritePost(
                    {
                        postID: this.model.get("post_id"),
                        successCallback: function (collection, postID) {
                            collection.where({post_id: postID})[0].set("post_fav", 1);
                        }
                    }
                );
            },

            unFavouritePost: function (e) {
                this.k2App.collections.posts.unFavouritePost(
                    {
                        postID: this.model.get("post_id"),
                        successCallback: function (collection, postID) {
                            collection.where({post_id: postID})[0].set("post_fav", 0);
                        }
                    }
                );
            },
            socialSharePost: function (e) {
                var div = jQuery('#popover');
                div.css({left:0,"z-index":100});
                var target = jQuery(e.target);
                var left = target.position().left;
                var top = target.position().top;
                div.css( {position:"absolute", top: (top - 43), left: (left - 40)}).show();
                jQuery('#socialShareIdent').val(this.model.get('post_id'));
                
            },
            flagPost: function (e) {
                e.preventDefault();
                post_id = this.model.get('post_id');

                this.k2App.collections.posts.flagPost({
                    postID : this.model.get('post_id'),
                });
            },
            sendComment: function (e) {
                e.preventDefault();
                e.stopPropagation();
                var commentBody = $(this.el).find(".box-comment").val();
                var form = jQuery(this.el).find('form');
                if (commentBody || form.find('input[type="file"]').val()) {
                    $(this.el).find(".disable-required").attr("disabled", true).css("color", "lightgrey");
                    this.k2App.collections.posts.postComment(
                        {
                            comment_body: commentBody,
                            form : form,
                            postID: this.options.parentModel.get("post_id"),
                            view: this.options.parentView,
                            successCallback: function (comment, view) {
                                var writeNewNode = view.collection.at(view.collection.length - 1);
                                view.model.set({"post_comment_count": parseInt(view.model.get("post_comment_count")) + 1}, {"silent": true});
                                $(view.el).find(".comments-count").text(view.model.get("post_comment_count") + " Comments");
                                view.collection.remove(writeNewNode);
                                view.collection.add(comment);
                                view.collection.add(writeNewNode);
                            }
                        }
                    );
                }
            },
            likeComment: function (e) {
                if($(e.target).hasClass("active")){
                    return false;
                }
                e.stopPropagation();
                this.k2App.collections.posts.likeComment(
                    {
                        id: this.model.get("comment_id"),
                        view: this,
                        successCallback: function (view) {
                            view.model.set({"comment_liked": 1, "like_count": parseInt(view.model.get("like_count")) + 1});
                            //collection.where({post_id: postID})[0].set("post_fav", 0);
                        }
                    }
                );
            },


            unlikeComment: function (e) {
                e.stopPropagation();
                this.k2App.collections.posts.unlikeComment(
                    {
                        id: this.model.get("comment_id"),
                        view: this,
                        successCallback: function (view) {
                            view.model.set({"comment_liked": 0, "like_count": parseInt(view.model.get("like_count")) - 1});
                            //collection.where({post_id: postID})[0].set("post_fav", 0);
                        }
                    }
                );
            },

            deleteComment: function(e){
                e.stopPropagation();
                var postsCollection = this.k2App.collections.posts;

                var context = this;
                this.k2App.util.getConfirmationPopup({
                    text: "Are you sure you want to delete this comment?",
                    confirmCallback: function(){
                        postsCollection.deleteComment(
                            {
                                id: context.model.get("comment_id"),
                                view: context,
                                successCallback: function (view) {
                                    $(view.el).remove();
                                    view.options.parentView.collection.remove(view.model);
                                    //collection.where({post_id: postID})[0].set("post_fav", 0);
                                }
                            }
                        );
                    }
                });

            }
        });
    });
