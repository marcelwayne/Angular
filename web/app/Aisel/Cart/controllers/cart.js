'use strict';

/**
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @name            AiselCart
 * @description     ...
 */

define(['app'], function (app) {
    app.controller('CartCtrl', ['$location', '$scope', 'cartService', 'notify', 'appSettings',
        function ($location, $scope, cartService, notify, appSettings) {

            $scope.total = function () {
                var total = 0;
                angular.forEach($scope.cartItems, function (item) {
                    total += item.qty * item.product.price;
                })
                return total;
            }
            $scope.getCartItems = function () {
                cartService.getCartItems($scope).success(function (data, status) {
                        $scope.cartItems = data;
                    }
                ).error(function (data, status) {
                    });
            }
            $scope.getCartItems();


            // Update product qty
            $scope.updateProductQty = function (item) {
                cartService.updateInCart(item).success(
                    function (data, status) {
                        notify(data.message);
                        $scope.isDisabled = false;
                        $scope.getCartItems();
                    }
                ).error(function (data, status) {
                        notify(data.message);
                        $scope.isDisabled = false;
                    });
            }

            // Submit order button
            $scope.orderSubmit = function () {
                if ($scope.cartItems) {
                    $scope.isDisabled = true;
                    cartService.orderSubmit().success(
                        function (data, status) {
                            notify(data.message);
                            $scope.isDisabled = false;
                            $scope.getCartItems();
                        }
                    ).error(function (data, status) {
                            notify(data.message);
                            $scope.isDisabled = false;
                        });
                }
            };
        }
    ])
    ;
})
;