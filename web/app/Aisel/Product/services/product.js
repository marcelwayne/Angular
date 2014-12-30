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
    app.service('productService', ['$http', 'Aisel',
        function ($http, Aisel) {
            return {
                getProducts: function ($scope) {
                    var locale = Aisel.getLocale();
                    var url = Aisel.settings.api + '/' + locale + '/product/list.json?limit=' + $scope.pageLimit + '&current=' + $scope.paginationPage + '&category=' + $scope.categoryId;
                    console.log(url);
                    return $http.get(url);
                },
                getProductByURL: function ($url) {
                    var locale = Aisel.getLocale();
                    var url = Aisel.settings.api + '/' + locale + '/product/view/url/' + $url + '.json';
                    console.log(url);
                    return $http.get(url);
                }
            };
        }]);
});