"use strict";
require.config({
    baseUrl: '/js/app',
    urlArgs: 'v=' + (new Date()).getTime(),
    paths: {
        'jquery': '/vendor/jquery/jquery-1.8.2.min',
        'bootstrap': '/vendor/bootstrap/js/bootstrap.min',
        'underscore': '/vendor/backbone/underscore-min',
        'backbone': '/vendor/backbone/backbone',
        'holder': '/vendor/holderjs/holder',
        'text': '/vendor/requirejs/text',
        'datepicker': '/vendor/datepicker/js/bootstrap-datepicker',
        'uiwidget': 'jquery.ui.widget',
        'fileupload': '/vendor/jQuery-File-Upload/js/jquery.fileupload'
    },
    shim: {
        'underscore': {
            'exports': '_'
        },
        'backbone': {
            'deps': ["underscore", "jquery"],
            'exports': "Backbone"
        },
        'bootstrap': {
            'deps': ['jquery'],
            'exports': 'jquery'
        },
        'datepicker': {
            'deps': ['jquery'],
            'exports': 'jquery'
        },
        'uiwidget': {
            'deps': ['jquery'],
            'exports': 'jquery'
        },
        'fileupload': {
            'deps': ['jquery', 'uiwidget'],
            'exports': 'jquery'
        }
    }
});

require([
    'app'
], function(App){

    App.initialize();
});