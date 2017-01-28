<?php

namespace Semantics3;

class Error extends \Exception
{
  private $http_status;

  private $http_body;

  private $json_body;

  public function __construct($message=null, $http_status=null, $http_body=null, $json_body=null)
  {
    parent::__construct($message);
    $this->http_status = $http_status;
    $this->http_body = $http_body;
    $this->json_body = $json_body;
  }

  public function getHttpStatus()
  {
    return $this->http_status;
  }

  public function getHttpBody()
  {
    return $this->http_body;
  }

  public function getJsonBody()
  {
    return $this->json_body;
  }
}
