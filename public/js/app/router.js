"use strict";
define([
    'jquery',
    'underscore',
    'backbone'
], function($, _, Backbone) {

    var AppRouter = Backbone.Router.extend({
        'routes': {
            '!/index': 'index'
        },
        'none': function() {
        }
    });

    var initialize = function() {
        var app_router = new AppRouter;
        Backbone.history.start();
    };
    return {
        initialize: initialize
    };
});