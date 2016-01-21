<?php

//$this->router->useAdapters(['JsonRpc']);

$jsonRpc = \MultiRouting\Adapters\JsonRpc\Adapter::name;
$soap = \MultiRouting\Adapters\Soap\Adapter::name;

$this->router->get('/foobar/{foo?}/{bar?}', 'Example\Foo\Application\Controllers\TestController@getSomeFooBar');

$this->router->post('/', 'Example\Foo\Application\Controllers\TestController@getSomeFooBarWithSandwich');

$this->router->adapter($jsonRpc)->intent('/', 'getFooAndBar', 'Example\Foo\Application\Controllers\TestController@getSomeFooBar');
$this->router->adapter($jsonRpc)->intent('/', 'describe', 'Example\Foo\Application\Controllers\TestController@getSomeFooBarWithSandwich');



$this->router->adapter($soap)->wsdl(WSDL_PATH)->intent('/', 'getFooAndBar', 'Example\Foo\Application\Controllers\TestController@getSomeFooBar');

//$this->router->adapter('Soap')->intent('/', 'describe', 'Example\Foo\Application\Controllers\TestController@describe');

//$this->router->adapter('Soap')->intent('/', 'ping', 'Example\Foo\Application\Controllers\TestController@getSomeFooBar');
