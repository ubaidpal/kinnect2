define(['App', 'backbone', 'marionette', 'jquery', 'underscore', 'models/Model', 'collections/Collection', 'text!templates/post_detail/comment.html', 'text!templates/dashboard/comment.html', 'text!templates/dashboard/writeComment.html', "flowplayer", "jplayer", "linkify"],
    function (App, Backbone, Marionette, $, _, Model, Collection, postContainer, comment, writeComment, flowplayer, jplayer, linkify) {
        //ItemView provides some default rendering logic

        return Backbone.Marionette.ItemView.extend({
            // template: _.template(template),
            getTemplate: function () {
                if (this.model.get("writeCommentNode")) {
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

                if (this.model.get("comment_liked")) {
                    comment_like_cls = "active";
                }
                /// Related to comments Nodes
                data.comment_like_cls = comment_like_cls;
                return data;

            },
            onShow: function () {
                console.log(this.el);
            },

            onDomRefresh: function () {
            },
            initialize: function (options) {
                this.k2App = options.k2App;
                this.model.on('change', this.render, this);
            },

            // View Event Handlers
            events: {
                "click .delete-comment" : "deleteComment",
                "click .like-comment.not-active": "likeComment",
                "click .like-comment.active": "unlikeComment",
                "click .delete-comment" : "deleteComment",
                "click .comment-threaded" : "showCommentBox",
                "keydown .wrtite-comment-threaded" : "saveCommentThreaded",
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

                            context.collection = new Collection([]);
                            context.collection.add(response);
                            context.render();
                            jQuery(context.el).find('.wrtite-comment-threaded').css('display', '');
                        }
                    });
                }
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
                            jQuery(context.el).find('.wrtite-comment-threaded').css('display','');
                            $(this.el).find(".disable-required").attr("disabled", true).css("color", "lightgrey");
                        }
                    });
                }
            },
            handleCommentEvent : function(e){
                if (e.keyCode == 13 && e.shiftKey) {
                    var s = $(this).val();
                    $(this).val(s+"\n");
                }else if(e.keyCode == 13 && !e.shiftKey){
                    this.sendComment(e);
                }
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
            sendComment: function (e) {
                e.preventDefault();
                e.stopPropagation();
                var commentBody = $(this.el).find(".box-comment").val();

                if (commentBody) {
                    $(this.el).find(".disable-required").attr("disabled", true).css("color", "lightgrey");
                    this.k2App.collections.posts.postComment(
                        {
                            comment_body: commentBody,
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
                this.k2App.util.getConfirmationPopupOverPopup({
                    text: "Are you sure you want to delete this comment?",
                    confirmCallback : function () {
                        postsCollection.deleteComment(
                            {
                                id: context.model.get("comment_id"),
                                view: context,
                                successCallback: function (view) {
                                    $(view.el).remove();
                                    view.options.k2App.views.postDetail.model.set({"post_comment_count": parseInt(view.options.k2App.views.postDetail.model.get("post_comment_count")) - 1});
                                    //collection.where({post_id: postID})[0].set("post_fav", 0);
                                }
                            }
                        );
                    }
                });
            }
        });
    });
