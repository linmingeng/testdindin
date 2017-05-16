requirejs.config({
    baseUrl: '',
    paths: {
        'jQuery': 'js/libs/jquery.min',
        'ionic': 'js/libs/ionic.bundle.min',
        'storage': 'js/libs/angular-local-storage.min',
        'citypicker': 'js/libs/citypicker.min',
        'ionicLazyLoad': 'js/libs/ionic-image-lazy-load',
        'config': 'js/config',
        'main.directives': 'js/directives',
        'main.controllers': 'js/controller',
        'main.services': 'js/services',
        'main.template': 'js/template',
        'main.devices': 'js/devices',
        'app': 'js/app'
    },
    waitSeconds: 0,
    shim: {
        'jQuery': {
            exports: 'jQuery'
        },
        'ionic': {
            deps: ['jQuery'],
            exports: 'ionic'
        },
        'citypicker': {
            deps: ['ionic'],
            exports: 'citypicker'
        },
        'storage': {
            deps: ['ionic'],
            exports: 'storage'
        },
        'ionicLazyLoad': {
            deps: ['ionic'],
            exports: 'ionicLazyLoad'
        }
    }
});

require([
    'ionic',
    'config',
    'app'
], function() {
    'use strict';
    angular.bootstrap(document.getElementById("main-view"), ['main']);
});
