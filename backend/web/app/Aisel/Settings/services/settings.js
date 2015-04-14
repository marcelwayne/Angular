'use strict';

/**
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @name            AiselSettings
 * @description     settingsService
 */

define(['app'], function (app) {
    app.service('settingsService', ['$http', 'Environment',
        function ($http, Environment) {
            return {
                get: function () {
                    var url = Environment.settings.api + '/config/';
                    console.log(url);

                    return $http.get(url);
                },
                save: function (settingsData) {
                    var url = Environment.settings.api + '/config/';

                    return $http({
                        method: 'POST',
                        url: url,
                        data: settingsData,
                        withCredentials: true,
                        headers: {
                            'Content-Type': 'application/json; charset=utf-8'
                        }
                        //headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    });
                }

            };
        }]);
});