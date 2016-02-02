<?php

abstract class Api_Connector
{
    private $apiKey;
    private $apiSecret;
    private $catchExceptions;

    public function __construct($apiKey=null,
                                $apiSecret=null,
                                $apiBase=null,
                                $catchExceptions=true){
        $this->catchExceptions = $catchExceptions;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->apiBase = is_null($apiBase) ? "https://api.semantics3.com/v1/" : $apiBase;
    }

    public function run_query($endpoint, $params, $method="GET")
    {
        if (!$this->apiKey)
            throw new Semantics3_AuthenticationError('No API key provided.');

        if (!$this->apiSecret)
            throw new Semantics3_AuthenticationError('No API secret provided.');

        $options = array( 'consumer_key' => $this->apiKey, 'consumer_secret' => $this->apiSecret );
        OAuthStore::instance("2Leg", $options );
        $url = $this->apiBase.$endpoint;
        if ($method == "GET") {
            $url = $url."?q=".urlencode(json_encode($params));
            $params = null;
        }
        else {
            $params = json_encode($params);
        }

        if(!$this->catchExceptions) {
            return $this->getOAuthRequester($method, $url, $params);
        }

        try
        {
            return $this->getOAuthRequester($method, $url, $params);
        }
        catch(OAuthException2 $e)
        {
            print "\n";
            $error = $e->getMessage();
            print $error."\n";
        }

    }

    protected function getOAuthRequester($method, $url, $params){
        switch ($method) {
        case "GET":
            $request = new OAuthRequester($url, $method, $params);
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

        $result = $request->doRequest();
        return $result['body'];
    }
}
