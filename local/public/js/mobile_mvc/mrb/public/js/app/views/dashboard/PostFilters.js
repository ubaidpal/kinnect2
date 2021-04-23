define( ['App', 'backbone', 'marionette', 'jquery', 'underscore', 'models/Model','text!templates/dashboard/filters.html'],
    function(App, Backbone, Marionette, $, _, Model, template) {
        //ItemView provides some default rendering logic
        var k2App;
        var type = 'all';
        return Backbone.Marionette.ItemView.extend( {
           // template: _.template(template),
            getTemplate: function(){
                return _.template(template);
            },
            serializeData: function () {
                var data = {link: "", title: "", description: "", image: "" };
                //if(!this.model.get('image') && !this.model.get('title')){
                //    this.model.set(data);
                //}
                return {};
            },
            onShow: function(){
            },

            initialize: function (options)
            {
                this.k2App = options.k2App;

            },

            // View Event Handlers
            events: {
                "click .post-filter" : "filterPosts",
                "change .object-selection" : "filterObjects",

            },

            filterObjects : function(e){
                e.preventDefault();

                this.k2App.collections.posts.reset([]);
                var target = jQuery(e.target);

                var object_type = target.val();

                this.k2App.collections.posts.fetch({
                    successCallback: function(params){
                        App.postFetching = false;
                        $('#page_loader').css('display','none');
                    },
                    type : this.type,
                    skip : 0,
                    object_type: object_type,
                    callbackParams : {
                        k2App: k2App
                    }
                });
            },

            filterPosts: function(e){
                e.preventDefault();
                this.type = $(e.target).attr('data-id');
                jQuery('.filter-type').removeClass('sort_active_link');
                jQuery(e.target).parent().addClass('sort_active_link');
                this.k2App.collections.posts.reset([]);
                this.k2App.collections.posts.fetch({
                    successCallback: function(params){
                        App.postFetching = false;
                        $('#page_loader').css('display','none');
                        //k2App.views.dashboard.render();
                    },
                    type : this.type,
                    skip : 0,
                    callbackParams : {
                        k2App: k2App
                    }
                });
            }
        });
    });