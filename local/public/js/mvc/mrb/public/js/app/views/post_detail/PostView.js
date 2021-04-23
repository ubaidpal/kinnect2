define( ['App', 'backbone', 'marionette', 'jquery', 'underscore' , 'models/Model', 'collections/Collection', 'text!templates/post_detail/post.html', 'views/post_detail/CommentView'],
    function(App, Backbone, Marionette, $, _, Model, Collection, template, ChildView) {
        //ItemView provides some default rendering logic
        var k2App;
        return Backbone.Marionette.CompositeView.extend( {
            template: _.template(template),
            itemView: ChildView,
            itemViewContainer: "#comments_container",
            itemViewOptions: function(){
                return {k2App: k2App}
            },
            className: function(){
               return "post-details-cols";
            },
            serializeData: function(params){

                var fav_cls, like_cls, dislike_cls = "";
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

                if(this.model.get("post_created_at")){
                    var date = this.model.get("post_created_at");
                    date = date.date;
                    console.log(date);
                    this.model.set("formatted_created_date", k2App.util.getFormattedTime(date));
                }

                if(this.model.get("link_type") == "youtube") {
                    this.model.set("link_vid", k2App.util.youtube_parser(this.model.get("object_uri")));
                }else if(this.model.get("link_type") == "vimeo"){
                    this.model.set("link_vid", k2App.util.vimeo_parser(this.model.get("object_uri")));
                }
                var data = this.model.toJSON();
                data.fav_cls = fav_cls;
                data.like_cls = like_cls;
                data.dislike_cls = dislike_cls;

                data.body = k2App.util.nl2br(this.model.get('body'));

                if(this.model.get('post_owner_body')){
                    data.post_owner_body = k2App.util.nl2br(this.model.get('post_owner_body'));
                }

                data.userPhoto = userPhoto;

                if (this.model.get("comment_liked")) {
                    comment_like_cls = "active";
                }
                /// Related to comments Nodes
                data.comment_like_cls = comment_like_cls;
                return data;
            },

            initialize: function(params){
                this.model = new Model(params.modelData);
                this.model.on('change', this.render, this);

                //this.modelData = params.modelData;
                this.collection = new Collection(params.modelData.comments);
                //this.collection.on('change', this.render, this);

                k2App = params.k2App;
                var context = this;
                setTimeout(function(){
                    context.render();
                }, 0);
                this.ajaxPathPrefix = params.ajaxPathPrefix;


            },
            events: {
                'click .share-activity-btn' :  "sharePost",
                "click .share-social-media" : "shareOnSocialMedia",
                "click .report-activity" : "flagActivity",
                "click .send-comment": "sendComment",
                "click .like-post": "handlePostLikeEvent",
                "click .dislike-post": "handlePostDislikeEvent",
                "click .favourite-post": "handlePostFavouriteEvent",
                "keydown .write-comment-box" : "handleCommentEvent",
                "click .hidden-comment-submit": "sendComment",
                "click .social-share-post": "socialSharePost",
                "click .select-attachment" : "triggerFileSelect",
                //"click .delete-post": "deletePost",
                //"click .battle-voting" : "VoteBattle",
                //"click .poll-voting" : "votePoll",
            },
            triggerFileSelect : function (e) {
                e.preventDefault();
                e.stopPropagation();
                jQuery(this.el).find('input[type="file"]').trigger('click');
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
            handleCommentEvent : function(e){
                if (e.keyCode == 13 && e.shiftKey) {
                    var s = $(this).val();
                    $(this).val(s+"\n");
                }else if(e.keyCode == 13 && !e.shiftKey){
                    this.sendComment(e);
                }
            },
            sendComment: function (e) {
                e.preventDefault();
                e.stopPropagation();
                var commentBody = $(this.el).find(".box-comment").val();
                var form = jQuery(this.el).find('form');
                
                var context = this;

                if (commentBody || form.find('input[type="file"]').val()) {
                    $(this.el).find(".box-comment").val('');
                    k2App.collections.posts.postComment(
                        {
                            comment_body: commentBody,
                            form : form,
                            postID: context.model.get("post_id"),
                            view: context,
                            successCallback: function (comment, view) {
                                view.model.set({"post_comment_count": parseInt(view.model.get("post_comment_count")) + 1}, {"silent": true});
                                $(view.el).find(".comments-count").text(view.model.get("post_comment_count") + " Comments");

                                view.collection.add(comment);

                            }
                        }
                    );
                }
            },

            handlePostLikeEvent: function (e) {
                if ($(e.target).hasClass("active")) {
                    this.unlikePost(e);
                } else {
                    this.likePost(e);
                }
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
                k2App.collections.posts.dislikePost(
                    {
                        postID: this.model.get("post_id"),
                        successCallback: function (collection, postID,response) {
                            collection.where({post_id:postID})[0].set('likes',response.likes);
                            collection.where({post_id: postID})[0].set("post_dislike_count", parseInt(response.likes.dislike_count));
                            collection.where({post_id: postID})[0].set("post_like_count", parseInt(response.likes.like_count));
                            collection.where({post_id: postID})[0].set("post_disliked", 1);
                            collection.where({post_id: postID})[0].set("post_liked", 0);

                            k2App.views.postDetail.model.set('likes',response.likes);
                            $('.post-detail-options .like-post').removeClass('active');
                            $('.post-detail-options .dislike-post').addClass('active');
                            $('.post-detail-counts .likes-count').text(response.likes.like_count + ' likes');
                            $('.post-detail-counts .dislikes-count').text(response.likes.dislike_count+ ' dislikes');
                        }
                    }
                );

            },

            undoDislikePost: function () {

                k2App.collections.posts.undoDislikePost(
                    {
                        postID: this.model.get("post_id"),
                        successCallback: function (collection, postID,response) {
                            collection.where({post_id:postID})[0].set('likes',response.likes);
                            collection.where({post_id: postID})[0].set("post_dislike_count", response.likes.dislike_count);
                            collection.where({post_id: postID})[0].set("post_disliked", 0);

                            k2App.views.postDetail.model.set('likes',response.likes);
                            $('.post-detail-options .like-post').removeClass('active');
                            $('.post-detail-options .dislike-post').removeClass('active');
                            $('.post-detail-counts .likes-count').text(response.likes.like_count + ' likes');
                            $('.post-detail-counts .dislikes-count').text(response.likes.dislike_count+ ' dislikes');
                        }
                    }
                );

            },
            likePost: function () {
                if($(this.el).find(".dislike-post").hasClass("active")){
                    var newCount = this.model.get("post_dislike_count") - 1;
                    this.model.set("post_dislike_count", newCount, {silent: true});
                    $(this.el).find(".dislikes-count").text(newCount);
                }
                k2App.collections.posts.likePost(
                    {
                        postID: this.model.get("post_id"),
                        successCallback: function (collection, postID,response) {

                            collection.where({post_id: postID})[0].set('likes',response.likes);
                            collection.where({post_id: postID})[0].set("post_like_count", parseInt(response.likes.like_count));
                            collection.where({post_id: postID})[0].set("post_dislike_count", parseInt(response.likes.dislike_count));
                            collection.where({post_id: postID})[0].set("post_liked", 1);
                            collection.where({post_id: postID})[0].set("post_disliked", 0);

                            k2App.views.postDetail.model.set('likes',response.likes);
                            k2App.views.postDetail.model.set("post_like_count", response.likes.like_count);
                            k2App.views.postDetail.model.set("post_dislike_count", response.likes.dislike_count);
                            k2App.views.postDetail.model.set("post_liked", 1);
                            k2App.views.postDetail.model.set("post_disliked", 0);
                        }
                    }
                );

            },

            unlikePost: function () {

                k2App.collections.posts.unlikePost(
                    {
                        postID: this.model.get("post_id"),
                        successCallback: function (collection, postID,response) {
                            collection.where({post_id: postID})[0].set('likes',response.likes);
                            collection.where({post_id: postID})[0].set("post_like_count", response.likes.like_count);
                            collection.where({post_id: postID})[0].set("post_dilike_count", response.likes.post_dislike_count);
                            collection.where({post_id: postID})[0].set("post_liked", 0);

                            k2App.views.postDetail.model.set('likes',response.likes);
                            k2App.views.postDetail.model.set("post_like_count", response.likes.like_count);
                            k2App.views.postDetail.model.set("post_dislike_count", response.likes.dislike_count);
                            k2App.views.postDetail.model.set("post_liked", 0);
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
                k2App.collections.posts.favouritePost(
                    {
                        postID: this.model.get("post_id"),
                        successCallback: function (collection, postID) {
                            collection.where({post_id: postID})[0].set("post_fav", 1);
                            k2App.views.postDetail.model.set("post_fav", 1);
                        }
                    }
                );
            },

            unFavouritePost: function (e) {
                k2App.collections.posts.unFavouritePost(
                    {
                        postID: this.model.get("post_id"),
                        successCallback: function (collection, postID) {
                            collection.where({post_id: postID})[0].set("post_fav", 0);
                            k2App.views.postDetail.model.set("post_fav", 0);
                        }
                    }
                );
            },

            flagActivity : function (e) {
                e.preventDefault();
                post_id = jQuery('#postIDentifier').val();
                category = jQuery('#category').val();
                report_text = jQuery('#report_text').val();

                k2App.collections.posts.flagPost(
                {
                    postID: post_id,
                    text: report_text,
                    category : category,
                    view: k2App.views.dashboard,
                    util: k2App.util,
                    successCallback: function (collection, response) {
                        $(".modal-box, .modal-overlay").fadeOut(500, function () {
                            jQuery('#report_text').val('');
                            $(".modal-overlay").remove();
                        });
                    }
                });
            },
            shareOnSocialMedia : function (e) {
                e.preventDefault();
                
                target = jQuery(e.target);

                platform = target.attr('data-target');

                var my_url = 'http://'+window.location.host + k2App.ajaxPathPrefix + 'postDetail/' + jQuery('#socialShareIdent').val();

                if(platform == 'twitter')
                {
                    sharer_uri = 'https://twitter.com/intent/tweet?url=';
                }else if(platform == 'facebook')
                {
                    sharer_uri = 'https://www.facebook.com/sharer/sharer.php?u=';
                }else if(platform == 'google-plus')
                {
                    sharer_uri = 'https://plus.google.com/share?url=';
                }else if(platform == 'linkedin')
                {
                    sharer_uri = 'http://www.linkedin.com/shareArticle?mini=true&url=';
                }

                var sharer_uri =  sharer_uri + this.urlencode(my_url);
                
                var trigger = jQuery('#socialShareTrigger');
                
                trigger.attr('href',sharer_uri);
                trigger.bind('click',this.PopupHandler)
                trigger.click();
            },
            PopupHandler : function(e) {

                e = (e ? e : window.event);
                var t = (e.target ? e.target : e.srcElement);
                var width = 500;
                var height = 500;
                // popup position
                var
                    px = Math.floor(((screen.availWidth || 1024) - width) / 2),
                    py = Math.floor(((screen.availHeight || 700) - height) / 2);

                // open popup
                var popup = window.open(t.href, "social", 
                    "width="+width+",height="+height+
                    ",left="+px+",top="+py+
                    ",location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1");
                if (popup) {
                    popup.focus();
                    if (e.preventDefault) e.preventDefault();
                    e.returnValue = false;
                }

                return !! popup;

            },
            urlencode : function(str) {
                str = (str + '')
                    .toString();
                return encodeURIComponent(str)
                    .replace(/!/g, '%21')
                    .replace(/'/g, '%27')
                    .replace(/\(/g, '%28')
                    .replace(/\)/g, '%29')
                    .replace(/\*/g, '%2A')
                    .replace(/%20/g, '+');
            },
            closeModal : function (e) {
                e.preventDefault();
                $('body').css({'overflow-y':'auto', 'position':'static','width':'auto'});
                $(".modal-box, .modal-overlay").fadeOut(0, function () {
                    $(".modal-overlay").remove();
                });
            },
            sharePost: function (e) {

                e.stopPropagation();
                var text = jQuery('#share_text').val();

                k2App.collections.posts.reSharePost(
                {
                    object_type : jQuery('#ObjectType').val(),
                    object_id  : jQuery('#objectIdentifier').val(),
                    text: text,
                    view: k2App.views.dashboard,
                    util: k2App.util,
                    successCallback: function (collection, post, view) {

                        if($("#mvc-main").data('screen') != "postDetail"){

                            collection.unshift(post);
                            //view.model.set("post_share_count", parseInt(view.model.get("post_share_count"))+1);
                            view.render();
                        }
                    }
                });
            },

            onShow: function(){

            },
            onDomRefresh: function(){
                var index = $('#selectedIndex').val();
                console.log(index);
                $(".modal-box .album-photo").bxSlider({
                    adaptiveHeight : true,
                    startSlide : index
                });
            }
        });
    });
