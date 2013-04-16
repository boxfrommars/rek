"use strict";
define([
    'underscore',
    'backbone',
    'jquery',
    'router',
    'bootstrap',
    'datepicker'
], function(_, Backbone, jQuery, Router, $) {
    var initialize = function() {
        jQuery('.datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });
        Router.initialize();
    }
    return {
        initialize: initialize
    }
});