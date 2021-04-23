if(appPath){
    appPath = "/"+appPath;
}
require.config({
    baseUrl:"."+appPath+"/local/public/js/mobile_mvc/mrb/public/js/app",
    // 3rd party script alias names (Easier to type "jquery" than "libs/jquery, etc")
    // probably a good idea to keep version numbers in the file names for updates checking
    paths:{
        // Core Libraries
        "jquery":"../libs/jquery",
        "jqueryui":"../libs/jqueryui",
        "cropit" : "../libs/cropit/cropit",
        "croppic" : "../libs/croppic",
        "bxslider" : "../libs/jquery.bxslider",
        //"jquerymobile":"../libs/jquery.mobile",*/
        "underscore":"../libs/lodash",
        "backbone":"../libs/backbone",
       // "backbone.babysitter":"../libs/backbone.babysitter",
        //"backbone.wreqr":"../libs/backbone.wreqr",
        "marionette":"../libs/backbone.marionette",
        "handlebars":"../libs/handlebars",
        "hbs":"../libs/hbs",
        "i18nprecompile":"../libs/i18nprecompile",
        "json2":"../libs/json2",
        "jasmine": "../libs/jasmine",
        "jasmine-html": "../libs/jasmine-html",

        // Plugins
        "backbone.validateAll":"../libs/plugins/Backbone.validateAll",
        //"bootstrap":"../libs/plugins/bootstrap",
        "text":"../libs/plugins/text",
        "jasminejquery": "../libs/plugins/jasmine-jquery",
        "flowplayer": "../libs/flowplayer.comerical.min",
       "jplayer": "../libs/jplayer/jquery.jplayer.min",
        "moment" : "../libs/moment.min",
        "tokenize" : "../libs/jquery.tokenize",
        "io" : "../libs/socket-io.min",
        "chat" : "../libs/plugins/chat-plugin",
        "linkify" : "../libs/plugins/linkify.min",
        "mediaElement" : "../libs/mediaelement-and-player.min"
    },
    // Sets the configuration for your third party scripts that are not AMD compatible
    shim:{
        // Twitter Bootstrap jQuery plugins
       // "bootstrap":["jquery"],
        // jQueryUI
        "jqueryui":["jquery"],
        "cropit" : ["jquery"],
        "croppic" : ["jquery"],
        "bxslider" : ["jquery"],
        // jQuery mobile
     //   "jquerymobile":["jqueryui"],

        // Backbone
        "backbone":{
            // Depends on underscore/lodash and jQuery
            "deps":["underscore", "jquery"],
            // Exports the global window.Backbone object
            "exports":"Backbone"
        },
       /* "backbone.babysitter":{
            "deps":["backbone"]
        },
        "backbone.wreqr":{
            "deps":["backbone"]
        },*/
        //Marionette
        "marionette":{
            "deps":["underscore", "backbone", "jquery"/*, "backbone.babysitter", "backbone.wreqr"*/],
            "exports":"Marionette"
        },
        //Handlebars
        "handlebars":{
            "exports":"Handlebars"
        },
        // Backbone.validateAll plugin that depends on Backbone
        "backbone.validateAll":["backbone"],

        "jasmine": {
            // Exports the global 'window.jasmine' object
            "exports": "jasmine"
        },

        "jasmine-html": {
            "deps": ["jasmine"],
            "exports": "jasmine"
        },
        "jplayer" : ["jquery"],
        "chat" : ["jquery", "tokenize","io"],
        "linkify": ["jquery"],
        'mediaElement' : ["jquery"]
    },
    // hbs config - must duplicate in Gruntfile.js Require build
    hbs: {
        templateExtension: "html",
        helperDirectory: "templates/helpers/",
        i18nDirectory: "templates/i18n/",

        compileOptions: {}        // options object which is passed to Handlebars compiler
    }
});
