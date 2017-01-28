<?php

namespace Semantics3;

class Error extends \Exception
{
  private $httpStatus;

  private $httpBody;

  private $jsonBody;

  public function __construct($message=null, $httpStatus=null, $httpBody=null, $jsonBody=null)
  {
    parent::__construct($message);
    $this->httpStatus = $httpStatus;
    $this->httpBody = $httpBody;
    $this->jsonBody = $jsonBody;
  }

  public function getHttpStatus()
  {
    return $this->httpStatus;
  }

  public function getHttpBody()
  {
    return $this->httpBody;
  }

  public function getJsonBody()
  {
    return $this->jsonBody;
  }
}
