'use strict';

/**
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @name            AiselProduct
 * @description     ...
 */

define(['app'], function (app) {
    app.controller('ProductDetailCtrl', ['$scope', '$location', '$routeParams', 'productService', '$rootScope', 'cartService', 'notify', 'API_URL',
        function ($scope, $location, $routeParams, productService, $rootScope, cartService, notify, API_URL) {
            $scope.isDisabled = true;

            var productURL = $routeParams.productId;
            var handleSuccess = function (data, status) {
                $scope.productDetails = data;
                $rootScope.productTitle = $scope.productDetails.product.title;

                if ($scope.productDetails.product) {
                    $scope.isDisabled = false;
                }
                // Disqus comments
                window.disqus_shortname = $rootScope.disqusShortname;
                $scope.showComments = $rootScope.disqusStatus && $scope.productDetails.product.comment_status;
            };
            productService.getProductByURL(productURL).success(handleSuccess);

            // Add product to cart
            $scope.addToCart = function () {

                // if user is guest - redirect or login page
                if ($rootScope.isAuthenticated == false) {
                    notify('You need to login or register');
                    var url = API_URL + '/user/login/';
                    $location.path(url);
                } else {
                    $scope.isDisabled = true;
                    cartService.addToCart($scope).success(
                        function (data, status) {
                            notify(data.message);
                            $scope.isDisabled = false;
                        }
                    ).error(function (data, status) {
                            notify(data.message);
                            $scope.isDisabled = false;
                        });
                }
            };

        }]);
});