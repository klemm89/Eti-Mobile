// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.services' is found in services.js
// 'starter.controllers' is found in controllers.js
angular.module('etiMobile', ['ionic', 'etiMobile.controllers', 'etiMobile.services', 'etiMobile.directives'])

    .run(function ($ionicPlatform) {
        $ionicPlatform.ready(function () {
            // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
            // for form inputs)
            if (window.cordova && window.cordova.plugins.Keyboard) {
                cordova.plugins.Keyboard.hideKeyboardAccessoryBar(false);
            }
            if (window.StatusBar) {
                // org.apache.cordova.statusbar required
                StatusBar.styleDefault();
            }
        });
    })

    .config(function ($stateProvider, $urlRouterProvider) {

        // Ionic uses AngularUI Router which uses the concept of states
        // Learn more here: https://github.com/angular-ui/ui-router
        // Set up the various states which the app can be in.
        // Each state's controller can be found in controllers.js
        $stateProvider
            .state('topics', {
                url: '/topics/:tag',
                templateUrl: 'templates/topics.html',
                controller: 'TopicListCtrl'
            })
            .state('newtopic', {
                url: "/newtopic/:tag",
                templateUrl: 'templates/new_topic.html',
                controller: 'NewTopicCtrl'
            })
            .state('messages', {
                url: "/messagelist/:topicId/:page",
                templateUrl: 'templates/message_list.html',
                controller: 'MsgListCtrl'
            })
            .state('newmessage', {
                url: "/newmessage/:topicId",
                templateUrl: 'templates/new_message.html',
                controller: 'NewMsgCtrl'
            });

        // if none of the above states are matched, use this as the fallback
        $urlRouterProvider.otherwise('/topics/LUE');

    });
