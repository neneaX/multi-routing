<?php
ini_set('soap.wsdl_cache_enabled', '0');
ini_set('soap.wsdl_cache_ttl', '0');

$client = new SoapClient(
    'http://localhost/multi-routing/examples/basic/soap.wsdl',
    array(
        'location' => 'http://localhost/multi-routing/examples/basic/',
        'cache_wsdl' => WSDL_CACHE_NONE
    )
);

print_r($client->__getFunctions());

echo '===================';
print_r($client->__call('ping', ['Input' => 'ping']));