define( ['App', 'backbone', 'marionette', 'jquery', 'underscore', 'models/Model', 'collections/Collection', 'text!templates/dashboard/fileUploads/file.html'],
    function(App, Backbone, Marionette, $, _, Model, Collection, fileItemView) {
        //ItemView provides some default rendering logic
        return Backbone.Marionette.CompositeView.extend( {
           // template: _.template(template),
            tagName: "li",
            getTemplate: function(){

                    return _.template(fileItemView);

            },

            className: function(){
                var cls = "";
                if(this.model.get("newUpload")){
                    cls = "upload-more";
                }else if(this.model.get("type") == "video"){
                    cls = "video";
                }else if(this.model.get("type") == "audio"){
                    cls = "audio";
                }
                return cls;
            },


            serializeData: function () {
                return this.model.toJSON();
            },

            initialize: function (params) {
                this.k2App = params.k2App;
                this.model.on('change', this.render, this);

            },

            // View Event Handlers
            events: {
                "click .upload-more": "triggerFileSelector",
                "click .delete-upload" : "deleteUpload"
            },

            triggerFileSelector: function(e){
                var control = $('#postFiles');
                control.replaceWith( control = control.clone( true ) );
                $("#status-file-upload").trigger("click");
            },

            deleteUpload: function(){
                this.k2App.collections.fileUploads.remove(this.model);
                if(this.k2App.collections.fileUploads.length < 1 || (this.k2App.collections.fileUploads.length == 1 && this.k2App.collections.fileUploads.at(0).get("newUpload"))){
                    $("#status-file-upload").show();
                    var control = $('#postFiles');
                    control.replaceWith( control = control.clone( true ) );
                    if(this.k2App.collections.fileUploads.at(0)){
                        this.k2App.collections.fileUploads.remove(this.k2App.collections.fileUploads.at(0));
                        
                        this.k2App.collections.posts.deleteFile({
                            token : this.model.get('token'),
                            successCallback : function (response) {
                                if(response.message == 'invalid_token'){
                                    alert('Invalid token');
                                }
                            }
                        });
                    }
                }
            },
            onShow: function(){

            }
        });
    });