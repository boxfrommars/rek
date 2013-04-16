"use strict";
require.config({
    baseUrl: '/js/app',
    urlArgs: 'v=' + (new Date()).getTime(),
    paths: {
        'jquery': '/vendor/jquery/jquery-1.8.2.min',
        'bootstrap': '/vendor/bootstrap/js/bootstrap.min',
        'jquery-ui': '/vendor/jquery-ui/js/jquery-ui-1.9.1.custom.min',
        'underscore': '/vendor/backbone/underscore-min',
        'backbone': '/vendor/backbone/backbone',
        'holder': '/vendor/holderjs/holder',
        'text': '/vendor/requirejs/text',
        'ymaps': 'http://api-maps.yandex.ru/2.0-stable/?load=package.full&lang=ru-RU&mode=debug',
        'datepicker': '/vendor/datepicker/js/bootstrap-datepicker'
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
        'ymaps': {
            'exports': 'ymaps'
        }
    }
});

require([
    'app'
], function(App){

    App.initialize();
});