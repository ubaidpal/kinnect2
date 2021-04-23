define( ['App', 'backbone', 'marionette', 'jquery', 'underscore' , 'models/Model', 'text!templates/dashboard/main.html', 'views/dashboard/ChildView', 'views/dashboard/fileUploads/ContainerView', 'views/dashboard/linksPreview/linksPreview'],
    function(App, Backbone, Marionette, $, _, Model, template, ChildView, UploadsContainerView, LinksPreview) {
        //ItemView provides some default rendering logic
        var k2App;
        return Backbone.Marionette.CompositeView.extend( {
            template: _.template(template),
            itemView: ChildView,
            itemViewContainer: ".post-container",

            allowedPhotoFiles: ['png', 'jpeg', 'jpg', 'bmp'],
            allowedVideoFiles: ['wmv', 'mp4', 'flv','mov','mkv','3gp'],
            allowedAudioFiles: ["mp3"],

            itemViewOptions: function(){
                return {k2App: k2App}
            },
            serializeData: function(){
                return this.model.toJSON();
            },

            initialize: function(params){
                this.stopAutoFetch =  params.stopAutoFetch;
                if(!this.stopAutoFetch){
                    this.collection.fetch();
                }
                k2App = params.k2App;
                this.model = new Model();
                this.ajaxPathPrefix = params.ajaxPathPrefix;
            },
            events: {
                "change #postFiles" : "handleFilesChange",
                "click #status-file-upload" : "triggerHiddenFileSelector",
                "click #savePostBtn" : "savePost",
                "click #share-btn" : "savePost",
                "paste #k2post" : "paste",
                "keydown #k2post" : "handleSubmitEvent",
                "click .js-modal-close" : "closeModal",
                'click .share-activity-btn' :  "sharePost",
                "click .share-social-media" : "shareOnSocialMedia",
                "click .report-activity" : "flagActivity",
                "click #more-stories" : "scrollToNewStories"
            },
            handleSubmitEvent : function(e){
                if (e.keyCode == 13 && e.shiftKey) {
                    var s = $(this).val();
                    $(this).val(s+"\n");
                }else if(e.keyCode == 13 && !e.shiftKey){
                    $('#share-btn').trigger('click');
                }else if(e.keyCode == 8 || e.keyCode == 46){
                    //alert("8:bak or backspace");
                    setTimeout(function(){
                        if($(e.target).val() == "" && k2App.views.linksPreview.model.get("link")){
                            mydev.views.linksPreview.model.clear();
                            $("#status-file-upload").show();
                        }
                    },100);
                }

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
                $(".modal-box, .modal-overlay").fadeOut(500, function () {
                    $("#write-your-post,#post_container").show();
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
            paste: function(e){

                var input = e.target;
                setTimeout(
                    function() {
                        var text = $.trim(input.value);
                        var words = text.split(" ");
                        var lastWord = words[words.length -1];
                        if(k2App.util.isValidURL(lastWord)){
                            k2App.views.dashboard.getUrlMeta(lastWord);
                        }
                    },
                    0);
            },

            getUrlMeta: function(link){
                $("#status-loader").show();
                k2App.collections.posts.getUrlMeta(
                    {
                        link:link,
                        k2App: k2App,
                        successCallback: function(data, k2App){
                            var view = k2App.views.linksPreview;

                            data.image = data.images[0];
                            view.model.set(data);
                            k2App.collections.fileUploads.reset([]);
                            $("#status-file-upload").hide();
                            $("#status-loader").hide();
                        }
                    }
                );
            },

            triggerHiddenFileSelector: function(){
                $("#postFiles").trigger("click");
            },

            savePostSuccessCallback: function(res){
                App.postUploadingFiles.close();
                App.postLinksPreview.close();
                $("#k2post").val("");
                res.post.postLevel = true;
                res.post.post_body = k2App.util.nl2br(res.post.post_body);
                res.post.comments = [];
                k2App.collections.posts.unshift(res.post);
                k2App.collections.fileUploads.reset([]);
                k2App.views.dashboard.render();
                $("#status-file-upload").show();
                $('#share-btn').data('executing',false);

            },

            savePost: function(e){
                e.preventDefault();
                var postText = $.trim($("#k2post").val());
                var dataObj = {};
                
                //var dataString = 'text='+postText ;//+ '&email1='+ email + '&password1='+ password + '&contact1='+ contact;
                dataObj.text = postText;
                dataObj.tokens = [];
                var context = this;

                if($('#share-btn').data('executing'))
                {
                    return false;
                }

                $.each(k2App.collections.fileUploads.toArray(), function(){
                    if(this.get("token")){
                        //dataString += "&token[]="+ this.get("token");
                        dataObj.tokens.push(this.get("token"));
                    }else if(this.get("type") == 'video'){
                        $('#share-btn').data('executing',true);
                        context.uploadVideos({type: 'video'});
                        dataObj = false;
                        return false;
                    }else if(this.get("type") == 'audio'){
                        $('#share-btn').data('executing',true);
                        context.uploadVideos({type: 'audio'});
                        dataObj = false;
                        return false;
                    }
                });
                if(!dataObj){
                    return; // Already sent to server as audio/video post
                }

                if(k2App.views.linksPreview.model.get("link") || k2App.views.linksPreview.model.get("title")){
                    dataObj.link = k2App.views.linksPreview.model.toJSON();
                }
                if(dataObj.tokens.length > 0 || postText || typeof dataObj.link == 'object'){
                    $('#share-btn').data('executing',true);
                    $(".disable-required").attr("disabled", true).css("color", "lightgrey");
                    k2App.collections.posts.savePost(
                        {
                            data: dataObj,
                            view: this,
                            util: k2App.util,
                            successCallback: function(res, view){
                                view.savePostSuccessCallback(res, {});
                            }
                        }
                    );
                }
            },


            uploadVideos: function(params){
                var context = this;
                var postBaseUrl = k2App.collections.posts.baseUrl;
                var util = k2App.util;
                $('form#postForm')
                    .submit(function(e){
                        e.preventDefault();
                        if(params.type == 'audio'){
                            $("#postFiles").attr("name", "audio");
                        }else{
                            $("#postFiles").attr("name", "video");
                        }
                        var formdata = new FormData(this);
                        if(k2App.collections.posts.postTarget == "group"){
                            formdata.append("target_type", "group");
                            formdata.append("target_id", k2App.collections.posts.postTargetId);
                        }
                        k2App.collections.fileUploads.at(0).set("status", false);
                        $.ajax({
                                xhr: function() {
                                    jQuery('#progress_bar').val(0).show();
                                    var xhr = new window.XMLHttpRequest();
                                    xhr.upload.addEventListener("progress", function(evt) {
                                        if (evt.lengthComputable) {
                                            var percentComplete = evt.loaded / evt.total;
                                            percentComplete = parseInt(percentComplete * 100);
                                            jQuery('#progress_bar').val(percentComplete);
                                            if (percentComplete === 100) {
                                                jQuery('#progress_bar').hide();
                                                jQuery('#status-loader').show();
                                            }
                                        }
                                    }, false);

                                    return xhr;
                                },
                                type: "POST",
                                url: context.ajaxPathPrefix+"shareStatus",
                                data: formdata,
                                processData: false,
                                contentType: false
                            })
                            .done(function(res) {
                                if(res.error && res.error == 1){
                                    alert(res.message);
                                    location.reload();
                                }else{
                                    res.post.base_url = postBaseUrl;
                                    res.post.formatted_created_date =  util.getFormattedTime( res.post.post_created_at.date);
                                    res.post.postLevel = true;
                                    context.savePostSuccessCallback(res);
                                }

                                //$ml('form#add_form').each(function(){ this.reset();});
                                //$ml('td#form_add_status').html('<span style="color:green;">Update complete</span>');
                            })
                            .fail(function(jqXHR, msg) {
                                //$ml('td#form_add_status').html('<span style="color:red;">Error Updating Entry: ' + msg + '</span>');
                            })
                    });

                $('form#postForm').trigger("submit");
            },

            handleFilesChange: function(e){
                e.preventDefault();
                var i = 0, len = e.target.files.length, file;
                for ( ; i < len; i++ ) {
                    file = e.target.files[i];
                    var extension = e.target.files[i].name.split('.').pop().toLowerCase();
                    if (this.allowedPhotoFiles.indexOf(extension) > -1) {
                        this.uploadFile(e.target.files[i], "photo");

                    } else if (this.allowedAudioFiles.indexOf(extension) > -1) {
                        k2App.collections.fileUploads.reset([]);
                        this.uploadFile(e.target.files[i], "audio");
                        break;

                    } else if (this.allowedVideoFiles.indexOf(extension) > -1) {
                        this.uploadFile(e.target.files[i], "video");
                        break;
                        //this.uploadVideos(e, 1);
                        //$("#status-file-upload").hide();
                        //if(e.target.files[i+1]){
                        //    alert("Only one video can be shared at a time");
                        //    return;
                        //}
                    } else {
                        alert("Invalid File Type(s)");
                        return;
                    }
                }
            },


            uploadFile: function(file, type){
                k2App.collections.fileUploads.remove(k2App.collections.fileUploads.where({"newUpload": true})[0]);
                $("#status-file-upload").hide();

                if(type == "photo"){
                    var progress_id = (Math.random().toString(36)+'00000000000000000').slice(2, 10+2);
                    k2App.collections.fileUploads.add({"uploadName" : file.name, status: false,progress_id:progress_id});
                    this.uploadPhoto(file,progress_id);
                }else if(type == "audio"){
                    k2App.collections.fileUploads.add({"uploadName" : file.name, status: true, type: 'audio'});
                    return;
                }else if(type == "video"){
                    k2App.collections.fileUploads.add({"uploadName" : file.name, status: true, type: 'video'});
                    return;
                }
                k2App.collections.fileUploads.add({"newUpload": true});
            },

            uploadPhoto: function(file,progress_id){
                var reader;

                if(this.formdata){
                    this.formdata = new FormData();
                }

                if (!!file.type.match(/image.*/)) {
                    if ( window.FileReader ) {
                        reader = new FileReader();
                        reader.readAsDataURL(file);
                    }
                    if (this.formdata) {
                        this.formdata.append("photos", file);
                        this.formdata.append("type", 'photo');
                    }

                }
                if (this.formdata) {
                    $.ajax({
                        xhr: function() {
                            jQuery('#'+progress_id).val(0).show();

                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    percentComplete = parseInt(percentComplete * 100);
                                    jQuery('#'+progress_id).val(percentComplete);
                                    if (percentComplete === 100) {
                                        jQuery('#'+progress_id).hide();
                                    }
                                }
                            }, false);

                            return xhr;
                        },
                        url: this.ajaxPathPrefix+"uploadImage",
                        type: "POST",
                        data: this.formdata,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            if(res.message == 'file_size_exceeded'){
                                alert('file size exceeded limit '+res.limit);
                                k2App.collections.fileUploads.remove(k2App.collections.fileUploads.at(0));
                                return;
                            }
                            JSON.stringify(res.token);
                            k2App.collections.fileUploads.remove(k2App.collections.fileUploads.where({"newUpload": true})[0]);
                            $.each(res.token, function(){

                                var data = this;
                                data.status = true;

                                k2App.collections.fileUploads.where({uploadName: file.name})[0].set(data);
                                //k2App.collections.fileUploads.add(this);
                            });
                            k2App.collections.fileUploads.add({"newUpload": true});
                        }
                    });
                }
            },

            uploadAudio: function(file){
                var reader;

                if(this.formdata){
                    this.formdata = new FormData();
                }

                if ( window.FileReader ) {
                    reader = new FileReader();
                    reader.readAsDataURL(file);
                }
                if (this.formdata) {
                    this.formdata.append("photos[]", file);
                    this.formdata.append("type", 'audio');
                }

                if (this.formdata) {
                    console.log('here');
                    $.ajax({
                        xhr: function() {
                            jQuery('#progress_bar').val(0).show();
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    percentComplete = parseInt(percentComplete * 100);
                                    jQuery('#progress_bar').val(percentComplete);
                                    if (percentComplete === 100) {
                                        jQuery('#progress_bar').hide();
                                    }
                                }
                            }, false);

                            return xhr;
                        },
                        url: this.ajaxPathPrefix+"uploadImage",
                        type: "POST",
                        data: this.formdata,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            JSON.stringify(res.token);
                            k2App.collections.fileUploads.remove(k2App.collections.fileUploads.where({"newUpload": true})[0]);
                            $.each(res.token, function(){
                                k2App.collections.fileUploads.add(this);
                            });
                            k2App.collections.fileUploads.add({"newUpload": true});
                        }
                    });
                }
            },

            //uploadPhotos: function(e, count){
            //    k2App.log("Inside upload photo");
            //    k2App.log(this.formdata);
            //    k2App.log(e);
            //    var i = 0, len = e.target.files.length, img, reader, file;
            //    if(this.formdata){
            //        this.formdata = new FormData();
            //    }
            //
            //    for ( ; i < len; i++ ) {
            //        file = e.target.files[i];
            //        if (!!file.type.match(/image.*/)) {
            //            if ( window.FileReader ) {
            //                reader = new FileReader();
            //                //reader.onloadend = function (e) {
            //                //    viewContext.showUploadedItem(e.target.result);
            //                //};
            //                reader.readAsDataURL(file);
            //            }
            //            if (this.formdata) {
            //                this.formdata.append("photos[]", file);
            //            }
            //
            //        }
            //    }
            //
            //    if (this.formdata) {
            //        $.ajax({
            //            url: "uploadImage",
            //            type: "POST",
            //            data: this.formdata,
            //            processData: false,
            //            contentType: false,
            //            success: function (res) {
            //
            //                JSON.stringify(res.token);
            //                k2App.collections.fileUploads.remove(k2App.collections.fileUploads.where({"newUpload": true})[0]);
            //                $.each(res.token, function(){
            //                    k2App.collections.fileUploads.add(this);
            //                });
            //                k2App.collections.fileUploads.add({"newUpload": true});
            //                //$("#postForm").prepend('<input class="_myTokens" type="hidden" name="fileTokens[] value="'+JSON.stringify(res.token)+'"');
            //                //document.getElementById("response").innerHTML = res;
            //            }
            //        });
            //    }
            //},

            onShow: function(){
                vvv = App;
                App.addRegions({
                    postUploadingFiles:"#postUploadingFiles",
                    postLinksPreview: "#links-preview"
                });

                k2App.views.uploadsContainerView = new UploadsContainerView({k2App:k2App});
                App.postUploadingFiles.show(k2App.views.uploadsContainerView); //k2App

                k2App.views.linksPreview = new LinksPreview({k2App:k2App});
                App.postLinksPreview.show(k2App.views.linksPreview);
                // Related to file uploading//
                this.formdata = false;
                if (window.FormData) {
                    this.formdata = new FormData();
                }else{
                    $("#btn").show();
                }

            },
            onDomRefresh: function(){
                if(App.postUploadingFiles){
                    App.postUploadingFiles.reset();
                    App.postUploadingFiles.show(new UploadsContainerView({k2App:k2App}));
                }
                if(App.postLinksPreview){
                    App.postLinksPreview.reset();
                    k2App.views.linksPreview = new LinksPreview({k2App:k2App});
                    App.postLinksPreview.show(k2App.views.linksPreview);

                }

                if(!this.stopAutoFetch) {
                    k2App.util.bindScrollToWindowEnd(this.getNextPage, {});
                }

            },

            scrollToNewStories: function(e){
                $(e.target).hide();
                $('html, body').animate({scrollTop : 0},800);
            },

            getNextPage: function(){
                ///return;
                App.postFetching = App.postFetching || false;
                if(!App.postFetching){
                    App.postFetching = true;
                        if(!k2App.collections.posts.has_next_page && k2App.collections.posts.length > 0)
                        {
                            return false;
                        }else if(!k2App.collections.posts.has_next_page && k2App.collections.posts.length < 1){
                            return false;
                        }
                        $('#page_loader').css('display','');
                        k2App.collections.posts.fetch(
                        {
                            successCallback: function(params){
                                App.postFetching = false;
                                $('#page_loader').css('display','none');
                                //k2App.views.dashboard.render();
                            },
                            callbackParams : {
                                k2App: k2App
                            }
                        }
                    );
                }
            }

        });
    });
