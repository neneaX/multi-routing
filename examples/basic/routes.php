<?php
use MultiRouter\Router\Router;

Router::group([
        'serialization' => 'rpc'
    ], function () {
        Router::post('login', 'user\AuthenticationController@login');
});

Router::group([
        'serialization' => 'rpc',
//         'middleware' => [
//             'ExampleMiddleware1',
//             'ExampleMiddleware2'
//         ]
    ], function () {
        Router::post('searchResource', 'ResourceController@search');
});


Router::group([
        'serialization' => 'soap',
    ], function () {
        Router::post('login', 'user\AuthenticationController@login');
});

Router::group([
        'serialization' => 'rest',
//         'middleware' => 'ExampleMiddleware'
    ], function () {
        Router::get('/resource/{resourceCode}/', 'ResourceController@view', array(
        	'resourceCode' => '[0-9]+'
        ));
});
