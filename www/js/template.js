
define(['ionic'], function() {
    'use strict';

    var service = angular.module('main.template', ['ionic']);

    service.factory('loadTemplateCache', ['$templateCache', function($templateCache){
        return function (){};
    }]);
    
})
