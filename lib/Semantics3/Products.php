<?php

class Semantics3_Products extends Api_Connector {
    
  private $_products_query = array();
  private $_categories_query = array();
  private $_query_result = array();


  /**
   * set the products search fields
   * 
   * This function sets the products search fields
   */
  public function products_field(){
    if (isset($this->_products_query))
        $this->_products_query = array_merge_recursive((array)$this->_products_query, $this->_nest_arguments(func_get_args()) );
    echo "FINAL:";
    echo json_encode($this->_products_query);
    echo "\n";
    return;
  }

  /**
   * set the categories search fields
   * 
   * This function sets the categories search fields
   */
  public function categories_field($field_name, $field_value){
    $this->_categories_query[$field_name] = $field_value;
    echo "FINAL:";
    echo json_encode($this->_categories_query);
    echo "\n";
    return;
  }

  /**
   * get the categories from the API
   * 
   * This function calls the API and returns the categories based on the query
   */
  public function get_categories(){
    return $this->_run_query("categories",json_encode($this->_categories_query));
  }

  private function _nest_arguments(){
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
        $temp[$temp_key] = $this->_nest_arguments($args);
    }

    return $temp;
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
    return json_encode($this->_products_query);
  }

  public function clear_query(){
    $this->_products_query = array();
    $this->_categories_query = array();
    $this->_query_result = array();
  }

  public function iter(){
    if (!array_key_exists('total_results_count', $this->_query_result) || $this->_query_result['offset'] >= $this->_query_result['total_results_count'])
      return 0;

    $limit = 10;

    if (array_key_exists('limit', $this->_products_query))
      $limit = $this->_products_query['limit'];

    $this->_products_query['offset'] += $limit;

    $this->get_products();

  }

  public function all_products(){
    return array_key_exists('results', $this->_query_result) ? $this->_query_result['results'] : 0;
  }

  public function query_json($endpoint, $query_json){
    $this->_query_result = parent::run_query($endpoint,$query_json);
    return $this->_query_result;
  }

  public function get_products(){
    $this->_query_result = parent::run_query("products",json_encode($this->_products_query));
    return $this->_query_result;
  }

  public function query($endpoint, $query_arr = array()){
    $this->_query_result = parent::run_query($endpoint,json_encode($query_arr));
    return $this->_query_result;
  }

}
