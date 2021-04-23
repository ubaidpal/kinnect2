define(['App', 'backbone', 'marionette', 'jquery', 'underscore', 'models/Model', 'collections/Collection', 'text!templates/dashboard/postContainer.html'],
    function (App, Backbone, Marionette, $, _, Model, Collection, postContainer) {
        //ItemView provides some default rendering logic

        return Backbone.Marionette.ItemView.extend({
            // template: _.template(template),
            getTemplate: function () {
               return _.template(postContainer);
            },

            className: function () {
                var cls = "post-wrapper";
                var subjectType = this.model.get("subject_type");
                subjectType = subjectType.substring(4);
                if(subjectType == "Consumer"){
                    cls += " consumer";
                }else{
                    cls += " brand";
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

                if(this.model.get('post_owner_body')){
                    data.post_owner_body = this.k2App.util.nl2br(this.model.get('post_owner_body'));
                }

                data.userPhoto = userPhoto;

                return data;

            },
            onShow: function () {
            },

            onDomRefresh: function () {

            },
            initialize: function (options) {
                this.k2App = options.k2App;
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
                "click .delete-post": "deletePost",
                "click .battle-voting" : "VoteBattle",
                "click .poll-voting" : "votePoll",
                "click .light-box-item" : "showPostDetail",
                "click .post-comment" : "showPostDetail"

            },
            handleCommentEvent : function(e){
                if (e.keyCode == 13 && e.shiftKey) {
                    var s = $(this).val();
                    $(this).val(s+"\n");
                }else if(e.keyCode == 13 && !e.shiftKey){
                    this.sendComment(e);
                }
            },
            showPostDetail : function(){
                window.location = this.k2App.ajaxPathPrefix+"postDetail/"+this.model.get("post_id");
               // Backbone.history.navigate("/");
               // Backbone.history.navigate("post/"+this.model.get("post_id"), true);
                return false;
                //var post_id = this.model.get("post_id");
                //this.k2App.collections.posts.getPostDetails({
                //    post_id : post_id,
                //    successCallback : function(response){
                //        console.log(response);
                //    }
                //});
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

                var target = jQuery(e.target);

                identifier = target.attr('data-id');

                $(".modal-overlay").fadeTo(500, 0.7);
                if(identifier == 'reShare'){
                    $('#ObjectType').val(this.model.get('object_type'));
                    $('#objectIdentifier').val(this.model.get('object_id'));
                }else {
                    $('#postIDentifier').val(this.model.get('post_id'));
                }
                $('#post_container,#write-your-post').hide();
                $('#' + identifier + '_popup').show();

            },

            deletePost: function(){

                var postsCollection = this.k2App.collections.posts;
                var context = this;

                if(confirm("Are you sure you want to delete this post?")){
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
                //
                //this.k2App.util.getConfirmationPopup({
                //    text: "Are you sure you want to delete this post?",
                //    confirmCallback: function(){
                //
                //    }
                //});
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
                
            }
        });
    });
