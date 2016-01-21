<?php

//$this->router->useAdapters(['JsonRpc']);

$jsonRpc = \MultiRouting\Adapters\JsonRpc\Adapter::name;
$soap = \MultiRouting\Adapters\Soap\Adapter::name;

$this->router->adapter($jsonRpc)->intent('/', 'getDetails', 'Example\Foo\Application\Controllers\TestController@getDetailsSuccess');
$this->router->adapter($jsonRpc)->intent('/', 'getError', 'Example\Foo\Application\Controllers\TestController@getErrorException');

$this->router->adapter($soap)->wsdl(WSDL_PATH)->intent('/soap', 'getItem', 'Example\Foo\Application\Controllers\TestController@getItemSuccess');
$this->router->adapter($soap)->wsdl(WSDL_PATH)->intent('/', 'getError', 'Example\Foo\Application\Controllers\TestController@getErrorSoapFault');

//$this->router->adapter('Soap')->intent('/', 'ping', 'Example\Foo\Application\Controllers\TestController@getSomeFooBar');

// @note moved this here because of greedy [POST /] route
$this->router->get('/wsdl', 'Example\Foo\Application\Controllers\TestController@deliverWSDLFile');
$this->router->get('/', 'Example\Foo\Application\Controllers\TestController@getHomepage');
$this->router->post('/sandwich', 'Example\Foo\Application\Controllers\TestController@getSomeFooBarWithSandwich');
$this->router->get('/foobar/{foo?}/{bar?}', 'Example\Foo\Application\Controllers\TestController@getSomeFooBar');

