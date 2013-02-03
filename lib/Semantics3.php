<?php

include_once "oauth-php/library/OAuthStore.php";
include_once "oauth-php/library/OAuthRequester.php";

if (!function_exists('curl_init')) {
  throw new Exception('Semantics3 needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Semantics3 needs the JSON PHP extension.');
}
if (!class_exists('OAuthRequester')) {
  throw new Exception('Semantics3 needs the OAUTH-PHP extension.');
}


require(dirname(__FILE__) . '/Semantics3/Error.php');
require(dirname(__FILE__) . '/Semantics3/AuthenticationError.php');
require(dirname(__FILE__) . '/Semantics3/ParameterError.php');


require(dirname(__FILE__) . '/Semantics3/ApiConnector.php');
require(dirname(__FILE__) . '/Semantics3/Products.php');
