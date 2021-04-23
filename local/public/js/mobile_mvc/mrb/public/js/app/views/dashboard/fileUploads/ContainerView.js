define( ['App', 'backbone', 'marionette', 'jquery', 'underscore' , 'models/Model', 'text!templates/dashboard/fileUploads/container.html', 'views/dashboard/fileUploads/FileView'],
    function(App, Backbone, Marionette, $, _, Model, template, ChildView) {
        //ItemView provides some default rendering logic

        return Backbone.Marionette.CompositeView.extend( {
            template: _.template(template),
            itemView: ChildView,
            itemViewContainer: ".uploads-container",
            tagName: "div",


            initialize: function(params){

                this.k2App = params.k2App;
                this.collection = this.k2App.collections.fileUploads;
                //this.collections.on('change', 'render', this);
            },

            itemViewOptions: function(){
                var options = {k2App: this.k2App};
                return options;
            },

            events: {

            }
        });
    });