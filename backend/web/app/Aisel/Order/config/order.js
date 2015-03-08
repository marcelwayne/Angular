'use strict';

/**
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @name            AiselOrder
 * @description     Module configuration
 */

define(['app'], function (app) {
    app.config(['$stateProvider', function ($stateProvider) {
        $stateProvider
            .state("orders", {
                url: "/:locale/orders/",
                templateUrl: '/app/Kernel/Resource/views/collection.html',
                controller: 'OrderCtrl'
            })
            .state("orderView", {
                url: "/:locale/order/view/:id/",
                templateUrl: '/app/Aisel/Order/views/detail.html',
                controller: 'OrderDetailsCtrl'
            })
    }]);
});