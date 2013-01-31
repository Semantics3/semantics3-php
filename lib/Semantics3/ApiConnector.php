<?php

abstract class Api_Connector
{
  private $apiKey;
  private $apiSecret;

  public function __construct($apiKey=null,$apiSecret=null){
    $this->apiKey = $apiKey;
    $this->apiSecret = $apiSecret;

  }

  public function run_query($endpoint, $query_arr)
  {
    if (!$this->apiKey)
      throw new Semantics3_AuthenticationError('No API key provided.');

    if (!$this->apiSecret)
      throw new Semantics3_AuthenticationError('No API secret provided.');


    $options = array( 'consumer_key' => $this->apiKey, 'consumer_secret' => $this->apiSecret );
    OAuthStore::instance("2Leg", $options );

    $url = "https://api.semantics3.com/v1/$endpoint?q=".$query_arr;
    $method = "GET"; 
    $params = null;

    try
    {        
      $request = new OAuthRequester($url, $method, $params);
      $result = $request->doRequest();
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



