<?php

namespace Semantics3;

use Semantics3\Api\Connector;

class Products extends Connector
{

  private $queryResult = array();
  private $dataQuery = array();


  /**
   * set the products search fields
   * 
   * This function sets the products search fields
   */
  public function productsField()
  {
    $args = func_get_args();
    array_unshift($args, "products");

    call_user_func_array("self::add", $args);

    return;
  }

  /**
   * set the categories search fields
   * 
   * This function sets the categories search fields
   */
  public function categoriesField($fieldName, $fieldValue)
  {
    $args = func_get_args();
    array_unshift($args, "categories");

    call_user_func_array("self::add", $args);

    return;
  }

  public function offersField()
  {
    $args = func_get_args();
    array_unshift($args, "offers");

    call_user_func_array("self::add", $args);

    return;
  }

  public function add()
  {
    $args = func_get_args();
    $endpoint = array_shift($args);

    if (!array_key_exists($endpoint, $this->dataQuery)) {
      $this->dataQuery[$endpoint] = array();
    }

    $this->dataQuery[$endpoint] = array_merge_recursive(
        (array)$this->dataQuery[$endpoint],
        call_user_func_array("self::_nest_arguments", $args)
    );
  }

  public function remove()
  {
    $args = func_get_args();
    $endpoint = array_shift($args);

    $query = &$this->dataQuery[$endpoint];

    foreach ($args as $value){
      if (array_key_exists($value, (array)$query)){

        if (end($args) == $value){
          unset($query[$value]);
        } else {
          $query = &$query[$value];
        }
      } else {
        throw new ParameterError("Attempted to detele something which didn't exist.");
      }
    }
  }

  /**
   * get the categories from the API
   * 
   * This function calls the API and returns the categories based on the query
   */
  public function getCategories()
  {
    $this->queryResult = parent::run_query("categories", $this->dataQuery["categories"]);

    return $this->queryResult;
  }

  public function get_offers()
  {
    $this->queryResult = parent::run_query("offers", $this->dataQuery["offers"]);

    return $this->queryResult;
  }

  private function nestArguments()
  {
    $args = func_get_args();

    $queryKey = $args[0];
    if (count($args) == 2) {
      $query[$queryKey] = $args[1];
    } else if (count($args) > 2) {
      unset($args[0]);
      $args = array_values($args);
      $query[$queryKey] = call_user_func_array("self::_nest_arguments", $args);
    }

    return $query;
  }

  /**
   * set the sitedetails fields
   * 
   * This function sets the sitedetails fields
   */
  public function sitedetails()
  {
    $args = func_get_args();
    array_unshift($args, "sitedetails");

    call_user_func_array("self::products_field", $args);
  }

  /**
   * set the latestoffers fields
   * 
   * This function sets the latestoffers fields
   */
  public function latestoffers($fieldName, $fieldValue1, $fieldValue2)
  {
    $args = array("sitedetails", $fieldName, $fieldValue1, $fieldValue2);

    call_user_func_array("self::products_field", $args);
  }

  public function limit($limit)
  {
    $args = array("limit", $limit);

    call_user_func_array("self::products_field", $args);
  }

  public function offset($offset)
  {
    $args = array("offset", $offset);

    call_user_func_array("self::products_field", $args);
  }

  public function sort_list($sortField, $sortValue)
  {
    $args = array("sort", $sortField, $sortValue);

    call_user_func_array("self::products_field", $args);
  }

  private function _get_products_field()
  {
    # Throw exception if no product field
    return json_encode($this->dataQuery["products"]);
  }

  public function clearQuery()
  {
    $this->dataQuery = array();
    $this->queryResult = array();
  }

  public function iterateProducts()
  {
    if (gettype($this->queryResult) == "string"){
      $queryResult = json_decode($this->queryResult, true);
      if (!array_key_exists('total_results_count', $queryResult) || (array_key_exists('offset', $queryResult) && $queryResult['offset'] >= $queryResult['total_results_count']))
        return 0;

      $limit = 10;

      if (array_key_exists('limit', $this->dataQuery["products"]))
        $limit = $this->dataQuery["products"]['limit'];

      if (!array_key_exists('offset', $queryResult))
        $this->dataQuery["products"]['offset'] = 0;
      else
        $this->dataQuery["products"]['offset'] = $queryResult['offset'];

      $this->dataQuery["products"]['offset'] += $limit;
    }

    return $this->getProducts();
  }

  public function allProducts()
  {
    $queryResult = json_decode($this->queryResult, true);

    return array_key_exists('results', $queryResult) ? $queryResult['results'] : 0;
  }

  public function queryJson($endpoint, $queryJson)
  {
    $this->queryResult = parent::run_query($endpoint, json_decode($queryJson));

    return $this->queryResult;
  }

  public function getQueryJson($endpoint = null)
  {
    if ($endpoint == null) {
      throw new ParameterError("Query Endpoint was not defined. You need to provide one. Eg: products");
    }

    return json_encode($this->dataQuery[$endpoint]);
  }

  public function getQuery($endpoint = null)
  {
    if ($endpoint == null){
      throw new ParameterError("Query Endpoint was not defined. You need to provide one. Eg: products");
    }

    return $this->dataQuery[$endpoint];
  }

  public function getProducts()
  {
    $this->queryResult = parent::run_query("products", $this->dataQuery["products"]);

    return $this->queryResult;
  }

  public function query($endpoint, $queryArr = array())
  {
    $this->queryResult = parent::run_query($endpoint, $queryArr);

    return $this->queryResult;
  }

}
