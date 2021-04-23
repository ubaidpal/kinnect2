define( ['App', 'backbone', 'marionette', 'jquery', 'underscore', 'models/Model','text!templates/dashboard/linksPreview/linksPreview.html'],
    function(App, Backbone, Marionette, $, _, Model, template) {
        //ItemView provides some default rendering logic
        return Backbone.Marionette.CompositeView.extend( {
           // template: _.template(template),
            getTemplate: function(){
                return _.template(template);
            },
            serializeData: function () {
                var data = {link: "", title: "", description: "", image: "" };
                if(!this.model.get('image') && !this.model.get('title')){
                    this.model.set(data);
                }
                return this.model.toJSON();
            },
            onShow: function(){
            },

            onDomRefresh: function(){
                if(this.model.get("title") || this.model.get("image")){
                    $("#write-your-post #links-preview").css({"margin": "10px"});
                }else{
                    $("#write-your-post #links-preview").css({"margin": "0px"});
                    $("#status-file-upload").show();
                }
            },

            initialize: function (options) {
                this.k2App = options.k2App;
                this.model = new Model();
                this.model.on('change', this.render, this);
            },

            // View Event Handlers
            events: {
                "click .remove-link" : "removeLink"

            },

            removeLink: function(){
                this.model.clear();
                $("#write-your-post #links-preview").css({"margin": "0px"});
                $("#status-file-upload").show();
            }
        });
    });