<?php

class Semantics3_ApiRequestor
{
  public $apiKey;
  public $apiSecret;
  private $_products_query = array();

  public function __construct($apiKey=null,$apiSecret=null)
  {
    $this->apiKey = $apiKey;
    $this->apiSecret = $apiSecret;

  }

  public function products_field() 
  {
    if (isset($this->_products_query))
        $this->_products_query = array_merge_recursive((array)$this->_products_query, $this->nest_arguments(func_get_args()) );
    echo "FINAL:";
    echo json_encode($this->_products_query);
    echo "\n";
    return;
  }

  private function nest_arguments(){
    $args = func_get_args();
    $args = $args[0];
    if ( is_array($args[0]) )
        $args = $args[0]; 

    $temp_key = $args[0];
    if (count($args) == 2)
        $temp[$args[0]] = $args[1];
    else if (count($args) > 2) {
        unset($args[0]);
        $args = array_values($args);
        $temp[$temp_key] = $this->nest_arguments($args);
    }

    return $temp;
  }

  public function sitedetails($key, $value1, $value2=null) 
  {
    if (is_null($value2))
        $this->_products_query[$key] = $value1;
    else
        $this->_products_query[$key][$value1] = $value2;
    echo json_encode($this->_products_query);
    echo "\n";
  }


  private function _get_products_field(){
    # Throw exception if no product field
    return json_encode($this->_products_query);
  }

  public function connection()
  {
    if (!$this->apiKey)
      throw new Semantics3_AuthenticationError('No API key provided.');

    if (!$this->apiSecret)
      throw new Semantics3_AuthenticationError('No API secret provided.');


    $options = array( 'consumer_key' => $this->apiKey, 'consumer_secret' => $this->apiSecret );
    OAuthStore::instance("2Leg", $options );

    $url = 'https://api.semantics3.com/v1/products?q='.$this->_get_products_field();
    $method = "GET"; 
    $params = null;

    try
    {        
            $request = new OAuthRequester($url, $method, $params);
            $result = $request->doRequest();
            
            $response = $result['body'];
            var_dump($response);
    }
    catch(OAuthException2 $e)
    {
        print "\n";
        $error = $e->getMessage();
        print $error."\n";
    }

  }
}



