'use strict';

/**
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @name            AiselKernel
 * @description     Most important data are loaded here
 */

define(['app'], function (app) {
    console.log('Kernel init service loaded ...');
    angular.module('app')
        .service('initService', ['$http', '$rootScope', 'settingsService',
            'authService', 'userService', 'pageCategoryService',
            'productCategoryService', 'Environment',
            function ($http, $rootScope, settingsService,
                      authService, userService, pageCategoryService,
                      productCategoryService, Environment) {
                return {
                    launch: function () {
                        var meta = false;
                        var disqus = false;
                        var general = false;

                        // Load user status
                        userService.getUserInformation().success(
                            function (data, status) {
                                console.log(data);
                                if (data.username) {
                                    $rootScope.user = data;
                                } else {
                                    $rootScope.user = undefined;
                                }
                            }
                        );

                        // Load settings data
                        settingsService.getApplicationConfig().success(
                            function (data, status) {
                                general = JSON.parse(data.config_general);
                                meta = JSON.parse(data.config_meta);
                                disqus = JSON.parse(data.config_disqus);
                                $rootScope.footer = JSON.parse(data.config_content).footerContent;
                                $rootScope.disqusShortname = disqus.shortname;
                                $rootScope.disqusStatus = false;
                                $rootScope.currency = general.currency;
                                $rootScope.paymentMethods = general.paymentMethods;

                                console.log('----------- Aisel Loaded! -----------');
                                var setLocale = function () {
                                    $rootScope.availableLocales = Environment.settings.locale.available;
                                    $rootScope.locale = Environment.currentLocale();
                                }
                                var setMetaData = function () {
                                    $rootScope.pageTitle = meta.defaultMetaTitle;
                                    $rootScope.metaDescription = meta.defaultMetaDescription;
                                    $rootScope.metaKeywords = meta.defaultMetaKeywords;
                                }

                                // Init
                                setLocale();
                                setMetaData();

                                // Hook for on route change
                                $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
                                    console.log('State Change ...');
                                    setLocale();
                                    setMetaData();
                                });
                            }
                        );

                        // Load navigation menu
                        settingsService.getMenu().success(
                            function (data, status) {
                                $rootScope.topMenu = data;
                            }
                        );
                        // Load page categories
                        pageCategoryService.getPageCategoryTree().success(
                            function (data, status) {
                                $rootScope.pageCategoryTree = data;
                            }
                        );
                        // Load product categories
                        productCategoryService.getProductCategoryTree().success(
                            function (data, status) {
                                $rootScope.productCategoryTree = data;
                            }
                        );
                    }
                }
            }
        ]);
});