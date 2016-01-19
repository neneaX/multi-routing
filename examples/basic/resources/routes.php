<?php

//$this->router->useAdapters(['JsonRpc']);

$this->router->get('/foobar/{foo?}/{bar?}', 'Example\Foo\Application\Controllers\TestController@getSomeFooBar');

$this->router->adapter('JsonRpc')->intent('/', 'getFooAndBar', 'Example\Foo\Application\Controllers\TestController@getSomeFooBar');

$this->router->adapter('Soap')->intent('/', 'ping', 'Example\Foo\Application\Controllers\TestController@getSomeFooBar');
