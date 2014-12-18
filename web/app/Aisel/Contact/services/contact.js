'use strict';

/**
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @name            AiselContact
 * @description     ...
 */

define(['app'], function (app) {
    app.service('contactService', ['$http', '$routeParams', 'API_URL',
        function ($http, $routeParams, API_URL) {
            return {
                send: function (form) {
                    var postData = {
                        name: form.name.$modelValue,
                        email: form.email.$modelValue,
                        phone: form.phone.$modelValue,
                        message: form.message.$modelValue
                    };
                    var url = API_URL + '/contact/send.json';//?name=' + name + '&email=' + email + '&phone=' + phone + '&message=' + message;
                    console.log(url);
                    return $http.post(url, postData);
                }
            };
        }]);
});