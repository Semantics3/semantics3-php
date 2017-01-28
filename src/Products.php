<?php

namespace Semantics3;

use Semantics3\Api\Connector;

class Products extends Connector {

  private $_query_result = array();
  private $_data_query = array();


  /**
   * set the products search fields
   * 
   * This function sets the products search fields
   */
  public function products_field(){
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
  public function categories_field($field_name, $field_value){
    $args = func_get_args();
    array_unshift($args, "categories");

    call_user_func_array("self::add", $args);
    return;
  }

  public function offers_field(){
    $args = func_get_args();
    array_unshift($args, "offers");

    call_user_func_array("self::add", $args);
    return;
  }

  public function add(){
    $args = func_get_args();
    $endpoint = array_shift($args);

    if (!array_key_exists($endpoint, $this->_data_query)){
      $this->_data_query[$endpoint] = array();
    }
    $this->_data_query[$endpoint] = array_merge_recursive((array)$this->_data_query[$endpoint], call_user_func_array("self::_nest_arguments", $args) );

  }

  public function remove(){
    $args = func_get_args();
    $endpoint = array_shift($args);

    $query = &$this->_data_query[$endpoint];

    foreach ($args as $value){
      if (array_key_exists($value,(array)$query)){
        if (end($args) == $value){
          unset($query[$value]);
        }
        else
          $query = &$query[$value];
      }
      else {
        throw new ParameterError("Attempted to detele something which didn't exist.");
      }
    }
  }

  /**
   * get the categories from the API
   * 
   * This function calls the API and returns the categories based on the query
   */
  public function get_categories(){
    $this->_query_result = parent::run_query("categories",$this->_data_query["categories"]);
    return $this->_query_result;
  }

    public function get_offers(){
    $this->_query_result = parent::run_query("offers",$this->_data_query["offers"]);
    return $this->_query_result;
  }

  private function _nest_arguments(){
    $args = func_get_args();

    $query_key = $args[0];
    if (count($args) == 2){
      $query[$query_key] = $args[1];
    }
    else if (count($args) > 2) {
      unset($args[0]);
      $args = array_values($args);
      $query[$query_key] = call_user_func_array("self::_nest_arguments", $args);
    }

    return $query;
  }

  /**
   * set the sitedetails fields
   * 
   * This function sets the sitedetails fields
   */
  public function sitedetails(){
    $args = func_get_args();
    array_unshift($args, "sitedetails");

    call_user_func_array("self::products_field", $args);
  }

  /**
   * set the latestoffers fields
   * 
   * This function sets the latestoffers fields
   */
  public function latestoffers($field_name, $field_value1, $field_value2){
    $args = array("sitedetails", $field_name, $field_value1, $field_value2);

    call_user_func_array("self::products_field", $args);
  }

  public function limit($limit){
    $args = array("limit", $limit);

    call_user_func_array("self::products_field", $args);
  }

  public function offset($offset){
    $args = array("offset", $offset);

    call_user_func_array("self::products_field", $args);
  }

  public function sort_list($sort_field, $sort_value){
    $args = array("sort", $sort_field, $sort_value);

    call_user_func_array("self::products_field", $args);
  }

  private function _get_products_field(){
    # Throw exception if no product field
    return json_encode($this->_data_query["products"]);
  }

  public function clear_query(){
    $this->_data_query = array();
    $this->_query_result = array();
  }

  public function iterate_products(){
    if (gettype($this->_query_result) == "string"){
      $query_result = json_decode($this->_query_result, true);
      if (!array_key_exists('total_results_count', $query_result) || (array_key_exists('offset', $query_result) && $query_result['offset'] >= $query_result['total_results_count']))
        return 0;

      $limit = 10;

      if (array_key_exists('limit', $this->_data_query["products"]))
        $limit = $this->_data_query["products"]['limit'];

      if (!array_key_exists('offset', $query_result))
        $this->_data_query["products"]['offset'] = 0;
      else
        $this->_data_query["products"]['offset'] = $query_result['offset'];

      $this->_data_query["products"]['offset'] += $limit;
    }
    return $this->get_products();
  }

  public function all_products(){
    $query_result = json_decode($this->_query_result, true);
    return array_key_exists('results', $query_result) ? $query_result['results'] : 0;
  }

  public function query_json($endpoint, $query_json){
    $this->_query_result = parent::run_query($endpoint,json_decode($query_json));
    return $this->_query_result;
  }

  public function get_query_json($endpoint = null){
    if ($endpoint == null){
      throw new ParameterError("Query Endpoint was not defined. You need to provide one. Eg: products");
    }
    return json_encode($this->_data_query[$endpoint]);
  }

  public function get_query($endpoint = null){
    if ($endpoint == null){
      throw new ParameterError("Query Endpoint was not defined. You need to provide one. Eg: products");
    }
    return $this->_data_query[$endpoint];
  }

  public function get_products(){
    $this->_query_result = parent::run_query("products",$this->_data_query["products"]);
    return $this->_query_result;
  }

  public function query($endpoint, $query_arr = array()){
    $this->_query_result = parent::run_query($endpoint,$query_arr);
    return $this->_query_result;
  }

}
