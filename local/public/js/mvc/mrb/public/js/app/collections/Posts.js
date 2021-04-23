define(["jquery","backbone","models/Model", "controllers/UtilController"],
  function($, Backbone, Model, UtilController) {
    // Creates a new Backbone Collection class object

    var Collection = Backbone.Collection.extend({
      // Tells the Backbone Collection that all of it's models will be of type Model (listed up top as a dependency)
        model: Model,
        skip: 0,
        has_next_page : true,
        post_type : 'all',
        object_type : null,
        hashTag : null,

        sync: function(method, collection, options){
            var that = this;
            if(!this.fetchUrl){
                this.fetchUrl = this.ajaxPathPrefix+"pull";
                if(this.postTarget == 'group'){
                    this.fetchUrl =this.ajaxPathPrefix+"groupPosts/"+this.postTargetId;
                }else if(this.postTarget == 'user'){
                    this.fetchUrl =this.ajaxPathPrefix+"getUserPosts/"+this.postTargetId;
                }
            }

            if(typeof options.object_type != "undefined"){
                this.object_type = options.object_type;
            }

            this.hashTag = jQuery('#mvc-main').data('hashtag');
            
            if(typeof options.type != "undefined"){
                this.post_type = options.type;
            }

            if(typeof options.skip != "undefined"){
                this.skip = options.skip;
            }



            var data = {skip: this.skip,type:this.post_type,object:this.object_type,hashTag:this.hashTag};
            if(typeof  options.lastPostId != 'undefined'){
                data.last_id = options.lastPostId;
                delete data.skip;
            }
            if(method == "read"){
                $.ajax({
                    url: this.fetchUrl,
                    type: "GET",
                    data: data,
                    success: function (res) {
                        that.traversal(collection, res.posts, options, res.base_url);

                        if(typeof  options.lastPostId == 'undefined'){
                            that.has_next_page = res.has_next_page;
                        }
                        //that.traversal(collection, that.mydata)
                    }
                });
                if(typeof options.lastPostId == 'undefined') {
                    this.skip = this.skip + 5; //TODO:replace this harded digit with server respone
                }
                //this.traversal(collection, this.mydata)
            }
            //alert(method);
        },

        traversal: function(collection, posts, options, base_url){
            if(base_url && !this.baseUrl){
                this.baseUrl = base_url;
            }

            var context = this;

            $.each(posts, function(index, data){
                if(data.object_view_permission){
                    data.formatted_created_date =  context.util.getFormattedTime(data.post_created_at.date);
                    data.post_body = context.util.nl2br(data.post_body);
                    data.postLevel = true;
                    data.base_url = base_url;
                    if(typeof options.lastPostId != 'undefined'){
                        collection.unshift(data);
                        if(options.view){
                            options.view.render();
                            if($(window).scrollTop() > 300){
                                $("#more-stories").show();
                            }
                        }
                    }else{
                        collection.push(data);
                    }

                }
            });

            if(options.successCallback){
                var params = options.callbackParams || {};
                options.successCallback(params);
            }
        },

        savePost: function(options){
            var data = options.data;
            if(this.postTarget == "group"){
                data.target_type = "group";
                data.target_id = this.postTargetId;
            }

            var context = this;

            $.ajax({
                url: this.ajaxPathPrefix+"shareStatus",
                type: "POST",
                data: data,
                success: function (res) {
                    if(res.message == "status_shared"){
                        if(options.successCallback){
                            res.post.formatted_created_date =  options.util.getFormattedTime(res.post.post_created_at.date);
                            res.post.base_url = context.baseUrl;
                            options.successCallback(res, options.view);
                        }
                    }
                }
            });
        },
        likePost: function(options){
            var requestData = {
                "id": options.postID
            };
            var collection = this;
            var params = {
                url: this.ajaxPathPrefix+"likeStatus/"+options.postID,
                type: "GET",
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                data: requestData,
                success: function (response) {
                    if(response.message == "status_liked"){
                        options.successCallback(collection, requestData.id,response);
                    }
                },
                error: function (x, t, m) {

                }

            };
            $.ajax(params);
        },

        unlikePost: function(options){
            var requestData = {
                "id": options.postID
            };
            var collection = this;
            var params = {
                url: this.ajaxPathPrefix+"unlikeStatus/"+options.postID,
                type: "GET",
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                data: requestData,
                success: function (response) {
                    if(response.message == "status_unliked"){
                        options.successCallback(collection, requestData.id,response);
                    }
                },
                error: function (x, t, m) {
                    alert("Error in Unliking post");
                }

            };
            $.ajax(params);
        },

        dislikePost: function(options){
            var requestData = {
                "id": options.postID
            };
            var collection = this;
            var params = {
                url: this.ajaxPathPrefix+"dislikeStatus/"+options.postID,
                type: "GET",
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                data: requestData,
                success: function (response) {
                    if(response.message == "status_disliked"){
                        options.successCallback(collection, requestData.id,response);
                    }
                },
                error: function (x, t, m) {
                    alert("Error in Unliking post");
                }

            };
            $.ajax(params);
        },

        undoDislikePost: function(options){
            var requestData = {
                "id": options.postID
            };
            var collection = this;
            var params = {
                url: this.ajaxPathPrefix+"undoDislike/"+options.postID,
                type: "GET",
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                data: requestData,
                success: function (response) {
                    if(response.message == "undone_unlike"){
                        options.successCallback(collection, requestData.id,response);
                    }
                },
                error: function (x, t, m) {
                    alert("Error in Unliking post");
                }

            };
            $.ajax(params);
        },

        favouritePost: function(options){
            var requestData = {
                "id": options.postID
            };
            var collection = this;
            var params = {
                url: this.ajaxPathPrefix+"makeActivityFavourite/"+options.postID,
                type: "GET",
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                data: requestData,
                success: function (response) {
                    if(response.message == "status_fav"){
                        options.successCallback(collection, requestData.id);
                    }
                },
                error: function (x, t, m) {
                    alert("Error in Unliking post");
                }

            };
            $.ajax(params);
        },
        unFavouritePost: function(options){
            var requestData = {
                "id": options.postID
            };
            var collection = this;
            var params = {
                url: this.ajaxPathPrefix+"removeActivityFavourite/"+options.postID,
                type: "GET",
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                data: requestData,
                success: function (response) {
                    if(response.message == "status_unfav"){
                        options.successCallback(collection, requestData.id);
                    }
                },
                error: function (x, t, m) {
                    alert("Error in Unliking post");
                }

            };
            $.ajax(params);
        },

        deletePost: function(options){

            var collection = this;
            var params = {
                url: this.ajaxPathPrefix+"deleteStatus/"+options.postID,
                type: "GET",
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                data: {},
                success: function (response) {
                    if(response.message == "status_deleted"){
                        options.successCallback(collection, options.model);
                    }
                },
                error: function (x, t, m) {
                    alert("Error in post deletion");
                }

            };
            $.ajax(params);
        },

        reSharePost: function(options){
            var requestData = {
                "text" : options.text,
                "object_id" : options.object_id,
                "object_type" : options.object_type,
            };
            
            var collection = this;
            var params = {
                url: this.ajaxPathPrefix+"shareActivity",
                type: "POST",
                //contentType: 'application/json; charset=utf-8',
                //dataType: 'json',
                data: requestData,
                beforeSend : function(xhr){
                    $(".modal-box, .modal-overlay").fadeOut(500, function () {
                        jQuery('#share_text').val('');
                        $('body').css({'overflow-y':'auto', 'position':'static','width':'auto'});
                        $(".modal-overlay").remove();
                    });
                },
                success: function (response) {
                    if(response.message == "status_shared"){
                        response.post.formatted_created_date =  options.util.getFormattedTime(response.post.post_created_at.date);
                        response.post.base_url = collection.baseUrl;
                        response.post.postLevel = true;
                        response.post.comments = [];
                        options.successCallback(collection, response.post, options.view);
                    }else {
                        alert((response.message).replace('_',' '));
                    }
                },
                error: function (x, t, m) {
                }

            };
            $.ajax(params);
        },
        flagPost : function (options) {
            requestData = {
                "post_id" : options.postID,
                "text" : options.text,
                "category" : options.category
            };
            var collection = this;
            var params = {
                url: this.ajaxPathPrefix+"flagActivity",
                type: "POST",
                //contentType: 'application/json; charset=utf-8',
                //dataType: 'json',
                data: requestData,
                success: function (response) {
                    if(response.message == "status_flaged"){
                        options.successCallback(collection, response);
                    }
                },
                error: function (x, t, m) {
                }

            };

            $.ajax(params);
        },
        postComment: function(options){
            var form = options.form;

            var requestData = {
                "comment_body": options.comment_body
            };
            var collection = this;
            var formdata = new FormData(form.get(0));

            formdata.append('comment_body',options.comment_body);
            formdata.append('attachment',form.find('input[type="file"]')[0].files[0]);

            var params = {
                url: this.ajaxPathPrefix + "addComment/"+options.postID,
                type: "POST",
                //contentType: 'application/json; charset=utf-8',
                //dataType: 'json',
                data: formdata,
                processData: false,
                contentType: false,
                success: function (response) {
                    if(response.message == "comment_added"){
                        options.successCallback(response.comment, options.view);
                    }
                },
                error: function (x, t, m) {

                }

            };
            $.ajax(params);
        },

        likeComment: function(options){
            var requestData = {
                "id": options.id
            };
            var collection = this;
            var params = {
                url: this.ajaxPathPrefix+"likeComment/"+options.id,
                type: "GET",
                //contentType: 'application/json; charset=utf-8',
                //dataType: 'json',
                data: requestData,
                success: function (response) {
                    if(response.message == "comment_liked"){
                        options.successCallback(options.view);
                    }
                },
                error: function (x, t, m) {
                    alert("Error in Unliking post");
                }

            };
            $.ajax(params);
        },

        unlikeComment: function(options){
            var requestData = {
                "id": options.id
            };
            var collection = this;
            var params = {
                url: this.ajaxPathPrefix+"unlikeComment/"+options.id,
                type: "GET",
                //contentType: 'application/json; charset=utf-8',
                //dataType: 'json',
                data: requestData,
                success: function (response) {
                    if(response.message == "comment_unliked"){
                        options.successCallback(options.view);
                    }
                },
                error: function (x, t, m) {
                    alert("Error in Unliking post");
                }

            };
            $.ajax(params);
        },

        deleteComment: function(options){
            var requestData = {
                "id": options.id
            };
            var collection = this;
            var params = {
                url: this.ajaxPathPrefix+"deleteComment/"+options.id,
                type: "GET",
                //contentType: 'application/json; charset=utf-8',
                //dataType: 'json',
                data: requestData,
                success: function (response) {
                    if(response.message == "comment_deleted"){
                        options.successCallback(options.view);
                    }
                },
                error: function (x, t, m) {
                    alert("Error in Unliking post");
                }

            };
            $.ajax(params);
        },

        voteBattle : function(options) {
            var requestData = {
                  "option" : options.option,
                  "postID" : options.postID
              };

              var params = {
                  url : this.ajaxPathPrefix+'battles/votes/'+option,
                  type : 'GET',
                  data : requestData,
                  success : function(response){
                     if(response.message == 'success')
                     {
                         options.successCallback(response.post,options.model,options.view);
                     }
                 },
                error: function(x,t,m) {
                   alert('Error voting');
                }
              };

            jQuery.ajax(params);
        },
        votePoll : function(options) {
            var requestData = {
                "option" : options.option,
                "postID" : options.postID
            };

            var params = {
                url : this.ajaxPathPrefix+'polls/votes/'+option,
                type : 'GET',
                data : requestData,
                success : function(response){
                    if(response.message == 'success')
                    {
                        options.successCallback(response.post,options.model,options.view);
                    }
                },
                error: function(x,t,m) {
                    alert('Error voting');
                }
            };

            jQuery.ajax(params);
        },
        getUrlMeta: function(options){

            var requestData = {
                "link": options.link
            };

            var params = {
                url: this.ajaxPathPrefix+"extractLinkMeta",
                type: "POST",
                data: requestData,
                success: function (response) {
                    if(response){
                        response.link = options.link;
                        options.successCallback(response, options.k2App);
                    }
                },
                error: function (x, t, m) {
                    alert("Error fetching link meta");
                }

            };
            $.ajax(params);
        },
        getPostDetails : function(options){
            var requestData = {
                post_id : options.post_id,
                popup : options.popup
            };
            var params = {
                url : this.ajaxPathPrefix + 'getPost/'+options.post_id,
                type : "GET",
                data : requestData,
                success : function(response){
                    options.successCallback(response);
                },
                error: function (x, t, m) {
                    alert("Something went wrong");
                }
            };
            jQuery.ajax(params);
        },
        getLatestPosts : function(k2App){
            //alert(this.at(0).get("post_id"));
            console.log(k2App.collections.posts.at(k2App.collections.posts.length - 1));
            console.log(k2App.collections.posts.at(k2App.collections.posts.length - 1).get("post_id"));
            this.fetch({

                lastPostId : k2App.collections.posts.at(k2App.collections.posts.length - 1).get("post_id")
            })
        },
        getPostLikes : function (options) {
            var requestData = {
                post_id : options.postID
            };
            var params = {
                url : this.ajaxPathPrefix + 'getPostLikes/'+options.postID,
                type : "GET",
                data : requestData,
                success : function(response){
                    options.successCallback(response);
                },
                error: function (x, t, m) {
                    alert("Something went wrong");
                }
            };
            jQuery.ajax(params);
        },
        getPostDislikes : function (options) {
            var requestData = {
                post_id : options.postID
            };
            var params = {
                url : this.ajaxPathPrefix + 'getPostDisikes/'+options.postID,
                type : "GET",
                data : requestData,
                success : function(response){
                    options.successCallback(response);
                },
                error: function (x, t, m) {
                    alert("Something went wrong");
                }
            };
            jQuery.ajax(params);
        },
        getPost : function (options) {
            var requestData = {
                post_id : options.postID
            };
            var params = {
                url : this.ajaxPathPrefix + 'getEditPost/'+options.postID,
                type : "GET",
                data : requestData,
                success : function(response){
                    options.successCallback(response,options.k2App);
                },
                error: function (x, t, m) {
                    alert("Something went wrong");
                }
            };
            jQuery.ajax(params);
        },
        editStatus : function (options) {
            var requestData = options.data;
            var params = {
                url : this.ajaxPathPrefix + 'editStatus',
                type : "POST",
                data : requestData,
                success : function(response){
                    options.successCallback(response);
                },
                error: function (x, t, m) {
                    alert("Something went wrong");
                }
            };
            jQuery.ajax(params);
        },
        saveCommentThreaded : function (options) {
            
            var requestData = {
                parent_comment_id : options.commentID,
                action_id : options.postID,
                comment_body : options.comment,
            };
            context = options.context;
            var params = {
                url : this.ajaxPathPrefix + 'addCommentThreaded',
                type : 'POST',
                data : requestData,
                success : function (response) {
                    console.log(response);
                    options.successCallback(response, options.context);
                },
                error : function (x,t,m) {
                    alert("Something went wrong");
                }
            };
            jQuery.ajax(params);
        },
        getCommentsThreaded : function (options) {
            var requestData = {
                comment_id : options.commentID
            };
            context = options.context;
            var params = {
                url : this.ajaxPathPrefix + 'getCommentsThreaded',
                type : 'GET',
                data : requestData,
                success : function (response) {
                    options.successCallback(response, context);
                },
                error : function (x,t,m) {
                    alert("Something went wrong");
                }
            };
            jQuery.ajax(params);
        },
        deleteFile : function (options) {
            var requestData = {token:options.token};
            var params = {
                url : this.ajaxPathPrefix + 'deletToken',
                type : 'POST',
                data : requestData,
                success : function(response){
                    options.successCallback(response);
                },
                error: function (x, t, m) {
                    alert("Something went wrong");
                }
            };
            jQuery.ajax(params);
        }

    });

    return Collection;
  });

