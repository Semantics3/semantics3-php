<?php

namespace Semantics3\Api;

use Semantics3\AuthenticationError;
use OAuthException2;
use OAuthRequester;
use OAuthStore;

abstract class Connector
{
  private $apiKey;
  private $apiSecret;

  public function __construct($apiKey=null,$apiSecret=null,$apiBase=null){
    $this->apiKey = $apiKey;
    $this->apiSecret = $apiSecret;
    $this->apiBase = is_null($apiBase) ? "https://api.semantics3.com/v1/" : $apiBase;
  }

  public function runQuery($endpoint, $params, $method="GET", array $requestOptions = [])
  {
    if (!$this->apiKey)
      throw new AuthenticationError('No API key provided.');

    if (!$this->apiSecret)
      throw new AuthenticationError('No API secret provided.');

    $options = array( 'consumer_key' => $this->apiKey, 'consumer_secret' => $this->apiSecret );
    OAuthStore::instance("2Leg", $options );
    $url = $this->apiBase.$endpoint;
    if ($method == "GET") {
      $url = $url."?q=".urlencode(json_encode($params));
    }
    else {
      $params = json_encode($params);
    }

    try
    {
      switch ($method) {
        case "GET":
          $request = new OAuthRequester($url, $method);
          break;
        case "POST":
          $request = new OAuthRequester($url, $method, '', $params);
          break;
        case "DELETE":
          $request = new OAuthRequester($url, $method);
          break;
        default:
          $request = new OAuthRequester($url, $method);
      }

      $usrId = array_key_exists('usrId', $requestOptions) ? $requestOptions['usrId'] : 0;
      $curlOptions = array_key_exists('curlOptions', $requestOptions) ? $requestOptions['curlOptions'] : [];
      $options = array_key_exists('options', $requestOptions) ? $requestOptions['options'] : [];

      $result = $request->doRequest($usrId, $curlOptions, $options);
      return $result['body'];
    }
    catch(OAuthException2 $e)
    {
      print "\n";
      $error = $e->getMessage();
      print $error."\n";
    }

  }
}
