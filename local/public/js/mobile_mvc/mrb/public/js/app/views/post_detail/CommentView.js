define(['App', 'backbone', 'marionette', 'jquery', 'underscore', 'models/Model', 'collections/Collection', 'text!templates/post_detail/comment.html', "linkify"],
    function (App, Backbone, Marionette, $, _, Model, Collection, comment,linkify) {
        //ItemView provides some default rendering logic

        return Backbone.Marionette.ItemView.extend({
            // template: _.template(template),
            getTemplate: function () {
                return _.template(comment);
            },

            className: function () {
                return "comment-item";
            },

            itemViewOptions: function () {
                var options = {k2App: this.k2App};
                options.parentModel = this.model;
                options.parentView = this;
                return options;
            },

            serializeData: function () {
                var comment_like_cls = "not-active";
                var userPhoto = "local/public/images/login-page/upload-img.png";
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
                if (!this.model.get("postLevel") && this.model.get("poster_photo_path")) {
                    userPhoto = this.model.get("poster_photo_path");
                }
                if(this.model.get("created_at")){
                    this.model.set("formatted_created_date", this.k2App.util.getFormattedTime(this.model.get("created_at")));
                }
                var data = this.model.toJSON();
                data.userPhoto = userPhoto;
                if (this.model.get("comment_liked")) {
                    comment_like_cls = "active";
                }
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
                "click .like-comment.active": "unlikeComment"
            },
            handleCommentEvent : function(e){
                if (e.keyCode == 13 && e.shiftKey) {
                    var s = $(this).val();
                    $(this).val(s+"\n");
                }else if(e.keyCode == 13 && !e.shiftKey){
                    this.sendComment(e);
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
                var conf = confirm("Are you sure you want to delete this comment?");
                if(conf){
                    postsCollection.deleteComment(
                        {
                            id: context.model.get("comment_id"),
                            view: context,
                            successCallback: function (view) {
                                console.log($(view.el));
                                $(view.el).remove();
                                view.options.k2App.views.postDetail.model.set({"post_comment_count": parseInt(view.options.k2App.views.postDetail.model.get("post_comment_count")) - 1});
                                //collection.where({post_id: postID})[0].set("post_fav", 0);
                            }
                        }
                    );
                };

            }
        });
    });
