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
    app.service('productCategoryService', ['$http', '$routeParams', 'API_URL',
        function ($http, $routeParams, API_URL) {
            return {
                getCategories: function ($scope) {
                    var locale = Aisel.getLocale();
                    var url = API_URL + '/' + locale + '/product/category/list.json?limit=' + $scope.pageLimit + '&current=' + $scope.paginationPage;
                    console.log(url);
                    return $http.get(url);
                },
                getCategory: function (categoryId) {
                    var locale = Aisel.getLocale();
                    var url = API_URL + '/' + locale + '/product/category/view/' + categoryId + '.json';
                    console.log(url);
                    return $http.get(url);
                }
            };
        }]);
});