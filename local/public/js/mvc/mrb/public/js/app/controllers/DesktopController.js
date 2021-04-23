define(
    ['App', 'backbone', 'marionette', "jqueryui","croppic","bxslider", "cropit", "io", "chat","controllers/UtilController",

        'views/dashboard/DashboardView',
        'views/dashboard/PostFilters',
        'views/post_detail/PostView',


        "collections/Posts",
        "collections/FileUploads"

    ],
    function
        (
            App, Backbone, Marionette, jqueryui,croppic,bxslider,cropit, io, chat, UtilController,
            DashboardView,
            PostFilters,
            PostDetailView,

            PostsCollection,
            FileUploadsCollection
        )

    {
        var k2App = {App: App,Backbone: Backbone,views:{}, collections: {}, log: function(data){} };
        k2App.util = new UtilController();
        mydev = k2App;


    return Backbone.Marionette.Controller.extend({
        initialize:function (options) {
            //App.headerRegion.show(new DesktopHeaderView());
            k2App.ajaxPathPrefix = this.getAjaxPathPrefix();
        },

        index:function () {
            this.applyCustomScripts();
            if($("#mvc-main").length && $("#mvc-main").data("screen")){
                eval("this."+$("#mvc-main").data("screen")+"()");
            }else{
                console.log("No MVC App found");
            }

        },

        dashboard: function(){
            k2App.collections.posts = new PostsCollection();
            k2App.collections.fileUploads = new FileUploadsCollection();
            k2App.collections.posts.ajaxPathPrefix = this.getAjaxPathPrefix();
            k2App.collections.posts.util = k2App.util;
            k2App.views.dashboard = new DashboardView({k2App: k2App, collection: k2App.collections.posts, ajaxPathPrefix: this.getAjaxPathPrefix()});
            App.mainRegion.show(k2App.views.dashboard);

            k2App.views.postFilters = new PostFilters({k2App: k2App, collection: k2App.collections.posts, ajaxPathPrefix: this.getAjaxPathPrefix()});

            App.filterRegion.show(k2App.views.postFilters);

            if(typeof k2App.dashboadAutoFetchTimeoutID == 'undefined'){
                k2App.dashboadAutoFetchTimeoutID = setInterval(function(){
                    k2App.collections.posts.fetch({
                        lastPostId : k2App.collections.posts.at(0).get("post_id"),
                        view: k2App.views.dashboard
                    })
                }, 60000);
            }
        },

        postDetail: function(){
            var data = $("#mvc-main").data("options");
            data = atob(data);

            data = JSON.parse(data);

            if(data.object_view_permission){
                data.formatted_created_date =  k2App.util.getFormattedTime(data.post_created_at.date);
                data.postLevel = true;
                data.post_body = k2App.util.nl2br(data.post_body);

                k2App.collections.posts = new PostsCollection();
                k2App.collections.posts.ajaxPathPrefix = this.getAjaxPathPrefix();
                k2App.collections.posts.util = k2App.util;
                k2App.collections.posts.push(data);
                k2App.views.dashboard = new DashboardView({ stopAutoFetch: true, k2App: k2App, collection: k2App.collections.posts, ajaxPathPrefix: this.getAjaxPathPrefix()});
                App.mainRegion.show(k2App.views.dashboard);
                $("#write-your-post").remove();
            }else{
                $(App.mainRegion.el).html("<div class='your-not-authorized'>You are not authorized to view this content.</div>");
            }

        },


        publicPostDetail: function(){
            var data = $("#mvc-main").data("options");
            data = atob(data);
            //alert("addsds");
            data = JSON.parse(data);
            data.formatted_created_date =  k2App.util.getFormattedTime(data.post_created_at.date);
            data.postLevel = true;
            data.post_body = k2App.util.nl2br(data.post_body);

            k2App.collections.posts = new PostsCollection();
            k2App.collections.posts.ajaxPathPrefix = this.getAjaxPathPrefix();
            k2App.collections.posts.util = k2App.util;
            k2App.collections.posts.push(data);
            k2App.views.dashboard = new DashboardView({ stopAutoFetch: true, k2App: k2App, collection: k2App.collections.posts, ajaxPathPrefix: this.getAjaxPathPrefix()});
            App.mainRegion.show(k2App.views.dashboard);
            $("#write-your-post").remove();
        },


        showPostDetail : function(post_id){

            $(window).resize(function () {
                $(".modal-box").css({
                    top: ($(window).height() - $(".modal-box").outerHeight()) / 3,
                    left: ($(window).width() - $(".modal-box").outerWidth()) / 2
                });
            });

            $(window).resize();
            k2App.collections.posts.getPostDetails({
                post_id: post_id,
                popup : 1,
                successCallback: function(data){
                    var appendthis = ("<div class='modal-overlay js-modal-close detail-popup'></div>");

                    $("body").append(appendthis);
                    $(".modal-overlay").fadeTo(500, 0.7);
                    $('#mvc-global-popup').fadeIn(500);

                    k2App.views.postDetail = new PostDetailView({k2App: k2App, modelData: data});
                    k2App.App.addRegions({postDetailRegion: "#mvc-global-popup .modal-body"});
                    k2App.App.postDetailRegion.show(k2App.views.postDetail);
                    $("#mvc-global-popup .js-modal-close").unbind("click").click(function(e){
                        e.preventDefault();
                        e.stopPropagation();
                        //Backbone.history.navigate("/", false);
                        window.history.pushState('/', 'Title', '/');
                        k2App.views.postDetail.close();
                        k2App.App.removeRegion("postDetailRegion");
                        $(".modal-overlay.js-modal-close.detail-popup").remove();
                        $("#mvc-global-popup").hide();
                        $('body').css({'overflow-y':'auto'});

                    });

                    $(".detail-popup").click(function(e){
                        e.preventDefault();
                        e.stopPropagation();
                        //Backbone.history.navigate("/", false);
                        window.history.pushState('/', 'Title', '/');
                        k2App.views.postDetail.close();
                        k2App.App.removeRegion("postDetailRegion");
                        $(".modal-overlay.js-modal-close.detail-popup").remove();
                        $("#mvc-global-popup").hide();
                        $('body').css({'overflow-y':'auto'});

                    });
                }
            });

            return false;

            //// ********************************* ////

            $(".mrb-popup-holder .modal-overlayt").remove();
            var appendthis = ("<div class='modal-overlay post-detail js-modal-close' id='post-detail-pp'></div>");
            $(".mrb-popup-holder .modal-body").append(appendthis);
            $(".mrb-popup-holder .modal-overlay").fadeTo(500, 0.7);
            $('#popup-wrapper').css({ position: "fixed",top: "50%", left: "50%","z-index":"999999",'display':'block', right:"0", bottom:"0", transform:"translate(-50%, -50%)" });

            $("#popup-wrapper .album-photo").bxSlider({
                adaptiveHeight : true
            });
            k2App.views.postDetail = new PostDetailView({k2App: k2App});
            k2App.App.addRegions({postDetailRegion: "#post-detail-pp"});
            k2App.App.postDetailRegion.show(k2App.views.postDetail);


            $(window).resize(function () {
                $(".modal-box").css({
                    top: ($(window).height() - $(".modal-box").outerHeight()) / 3,
                    left: ($(window).width() - $(".modal-box").outerWidth()) / 2
                });
            });

            $(window).resize();
            return false;
            //*****************
            var post_id = this.model.get("post_id");
            this.k2App.collections.posts.getPostDetails({
                post_id : post_id,
                successCallback : function(response){
                }
            });
        },


        postDetailPopup: function(id){

            //id = parseInt(id);
            var data = k2App.collections.posts.where({post_id:id})[0].toJSON();

            data.formatted_created_date =  k2App.util.getFormattedTime(data.post_created_at.date);
            data.postLevel = true;

            k2App.collections.post = new PostsCollection();
            k2App.collections.post.ajaxPathPrefix = this.getAjaxPathPrefix();
            k2App.collections.post.util = k2App.util;
            k2App.collections.post.push(data);
            k2App.views.postPopup = new DashboardView({ stopAutoFetch: true, k2App: k2App, collection: k2App.collections.post, ajaxPathPrefix: this.getAjaxPathPrefix()});
            k2App.App.addRegions({popupRegion: "#popup-wrapper"});
            k2App.App.popupRegion.show(k2App.views.postPopup);
            $("#popup-wrapper .post").remove();
            $("#popup-wrapper .popover").remove();
            $("#popup-wrapper #postIDentifier").remove();
            $("#popup-wrapper #socialShareTrigger").remove();
            $("#popup-wrapper #reShare_popup").remove();
            $("#popup-wrapper #flag_popup").remove();
            $("#popup-wrapper .delete-post").remove();
            var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
            $("body").append(appendthis);
            $(".modal-overlay").fadeTo(500, 0.7);
            $('#popup-wrapper').css({ position: "fixed",top: "50%", left: "50%","z-index":"999999",'display':'block', right:"0", bottom:"0", transform:"translate(-50%, -50%)" });

            $("#popup-wrapper .album-photo").bxSlider({
                adaptiveHeight : true
            });

            $(window).resize(function () {
                $(".modal-box").css({
                    top: ($(window).height() - $(".modal-box").outerHeight()) / 3,
                    left: ($(window).width() - $(".modal-box").outerWidth()) / 2
                });
            });

            $(window).resize();
        },

        groupProfile: function(){

            var rootTokens = window.location.href.split("/");
            var groupID = rootTokens[rootTokens.length-1];
            groupID = parseInt(groupID);
            k2App.collections.posts = new PostsCollection();
            k2App.collections.posts.postTarget = 'group';
            k2App.collections.posts.postTargetId = groupID;
            k2App.collections.posts.ajaxPathPrefix = this.getAjaxPathPrefix();
            k2App.collections.posts.util = k2App.util;
            k2App.collections.fileUploads = new FileUploadsCollection();
            k2App.views.dashboard = new DashboardView({k2App: k2App, collection: k2App.collections.posts, ajaxPathPrefix: this.getAjaxPathPrefix()});
            App.mainRegion.show(k2App.views.dashboard);

            k2App.views.postFilters = new PostFilters({k2App: k2App, collection: k2App.collections.posts, ajaxPathPrefix: this.getAjaxPathPrefix()});

            App.filterRegion.show(k2App.views.postFilters);

            if($("#group-details-wrapper").data("view-permission")){
                var rootTokens = window.location.href.split("/");
                var groupID = rootTokens[rootTokens.length-1];
                groupID = parseInt(groupID);
                k2App.collections.posts = new PostsCollection();
                k2App.collections.posts.postTarget = 'group';
                k2App.collections.posts.postTargetId = groupID;
                k2App.collections.posts.ajaxPathPrefix = this.getAjaxPathPrefix();
                k2App.collections.posts.util = k2App.util;
                k2App.collections.fileUploads = new FileUploadsCollection();
                k2App.views.dashboard = new DashboardView({k2App: k2App, collection: k2App.collections.posts, ajaxPathPrefix: this.getAjaxPathPrefix()});
                App.mainRegion.show(k2App.views.dashboard);
            }else{
                $("#group-details-wrapper").find(".tab").hide();
            }
            
            setTimeout(function(){
                if(!$("#group-details-wrapper").data("upload-permission")){
                    $("#write-your-post").remove();
                }
            }, 0)
        },

        userProfile: function(){
            this.changeProfilePhoto();

            var rootTokens = window.location.href.split("/");
            var usrSlug = rootTokens[rootTokens.length-1];
            k2App.collections.posts = new PostsCollection();
            k2App.collections.fileUploads = new FileUploadsCollection();
            k2App.collections.posts.postTarget = 'user';
            k2App.collections.posts.postTargetId = usrSlug;
            k2App.collections.posts.util = k2App.util;

            k2App.collections.posts.ajaxPathPrefix = this.getAjaxPathPrefix();

            k2App.views.dashboard = new DashboardView({k2App: k2App, collection: k2App.collections.posts, ajaxPathPrefix: this.getAjaxPathPrefix()});
            App.mainRegion.show(k2App.views.dashboard);

            k2App.views.postFilters = new PostFilters({k2App: k2App, collection: k2App.collections.posts, ajaxPathPrefix: this.getAjaxPathPrefix()});

            App.filterRegion.show(k2App.views.postFilters);
            
            setTimeout(function(){
                if(usrSlug != $("#profileLink").data("username")){
                    $("#write-your-post").remove();
                }
            }, 0)
        },

        getAjaxPathPrefix: function(){
            var ajaxPathPrefix = "/";
            if(window.location.host == 'localhost'){
                ajaxPathPrefix = "/kinnect2/";
            }
            return ajaxPathPrefix;
        },

        applyCustomScripts: function(){
            $.fn.initiChat(io, k2App.util);
            this.changeCoverPhoto();
            $(document).click(function(e){
                var container = $("#popup-wrapper");

                if (!container.is(e.target) // if the target of the click isn't the container...
                    && container.has(e.target).length === 0
                    && container.is(':visible')
                    && !$(e.target).hasClass('light-box-item')) // ... nor a descendant of the container
                {
                    $(".modal-overlay").fadeOut(500, function () {
                        $(".modal-overlay").remove();
                    });
                    container.hide();
                    window.history.pushState('/', 'Title', '/');
                    //k2App.Backbone.history.navigate("");
                }
            });



            $('#friends').tokenize({
                placeholder: 'Type to select friend'
            });

        },
        changeProfilePhoto : function  () {
            jQuery('.image-editor').cropit({
                exportZoom: 1.25,
                imageBackground: true,
                originalSize: true,
                smallImage:"allow",
                onFileChange: function(){},
                imageBackgroundBorderWidth: 20,
                imageState: {
                    //src: '<?php echo Kinnect2::getPhotoUrl($user->photo_id, $user->id, 'user', 'thumb_normal'); ?>',
                }
            });
        },
        changeCoverPhoto: function(){

            var cropperOptions = {
                customUploadButtonId:'change_cover_btn',
                cropData:{
                    "group_id": $('#group_id').val(),
                },
                loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
                modal:false,
                doubleZoomControls:false,
                rotateControls:false,
                processInline:true,
                imgEyecandy:false,
                cropUrl: ajaxPathPrefix + 'saveCoverPhoto',
                onAfterImgUpload : function(){
                    jQuery('#cover_photo_div').css('display','none');
                    jQuery('#crop_div').css({'display':'','z-index':'5'});
                    jQuery('#group-details-wrapper').css('display','none');
                    jQuery('.banner-bottom-bg').css('display','none');
                },
                onAfterImgCrop : function(res){
                    res = jQuery.parseJSON(res);
                    jQuery('#cover_photo').attr('src',res.path);
                    jQuery('#cover_photo_div').css('display','');
                    jQuery('#crop_div').css('display','none');
                    jQuery('#group-details-wrapper').css('display','');
                    jQuery('.banner-bottom-bg').css('display','');
                    jQuery('#change_cover_btn').css('display','');
                    window.location.reload();
                },
                onReset : function(e){
                    jQuery('#cover_photo_div').css('display','');
                    jQuery('#crop_div').css('display','none');
                    jQuery('#group-details-wrapper').css('display','');
                    jQuery('.banner-bottom-bg').css('display','');
                    jQuery('#change_cover_btn').css('display','');
                }
            }

            var cropperHeader = new Croppic('crop_div', cropperOptions);

        }

    });
});
