<?php
//error_reporting(E_ALL & ~E_DEPRECATED);
//ini_set('always_populate_raw_post_data', '-1');

require __DIR__ . '/../../vendor/autoload.php';

// todo: get the wsdl path from a config file not from a constant.
defined('WSDL_PATH') || define('WSDL_PATH', __DIR__ . '/soap.wsdl');
defined('WSDL_FILE') || define('WSDL_FILE', __DIR__ . '/soap.wsdl'); // @todo fix this

require __DIR__ . '/App.php';