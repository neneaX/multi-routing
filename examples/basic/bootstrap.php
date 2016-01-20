<?php

require '../../vendor/autoload.php';

// todo: get the wsdl path from a config file not from a constant.
defined('WSDL_PATH') || define('WSDL_PATH', '/home/teo/server/multi-routing/examples/soap.wsdl');

require 'App.php';