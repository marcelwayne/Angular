'use strict';

/**
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @name            Aisel
 * @description     App Kernel
 */

define([
        'angular', 'jQuery', 'underscore', 'angular-resource',
        'angular-cookies', 'angular-sanitize', 'textAngular',
        'ui-bootstrap-tpls', 'ui-utils', 'angular-gravatar',
        'md5', 'angular-disqus', 'angular-notify', 'twitter-bootstrap',
        'angular-ui-router', 'angular-route'],
    function (angular) {
        'use strict';

        var app = angular.module('app', [
            'ngCookies', 'ngResource', 'ngSanitize', 'ngRoute', 'ui.bootstrap', 'ui.router',
            'ui.utils', 'ui.validate', 'ui.gravatar', 'textAngular', 'ngDisqus', 'cgNotify'])

        app.constant('API_URL', Aisel.settings.api)
            .constant("PRIMARY_LOCALE", Aisel.settings.locale.primary)
            .value('appSettings', [])
            .run(['$http', '$rootScope', 'rootService', 'initService',
                function ($http, $rootScope, rootService, initService) {
                    initService.launch();
                }])
            .config(function ($provide, $routeProvider, $locationProvider, $httpProvider) {
                $locationProvider.html5Mode(true);
                $provide.factory('requestInterceptor', function ($q) {
                    return {
                        request: function (config) {
                            document.getElementById("page-is-loading").style.visibility = "visible";
                            return config || $q.when(config);
                        },
                        requestError: function (rejection) {
                            document.getElementById("page-is-loading").style.visibility = "hidden";
                            return $q.reject(rejection);
                        },
                        response: function (response) {
                            document.getElementById("page-is-loading").style.visibility = "hidden";
                            return response || $q.when(response);
                        },
                        responseError: function (rejection) {
                            document.getElementById("page-is-loading").style.visibility = "hidden";
                            return $q.reject(rejection);
                        }
                    };
                });
                $httpProvider.interceptors.push('requestInterceptor');
            });

        return app;
    })
;