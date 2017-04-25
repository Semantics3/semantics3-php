<?php

namespace Semantics3;

use Semantics3\Api\Connector;

class Products extends Connector
{
    private $queryResult = [];
    private $dataQuery = [];


  /**
   * set the products search fields
   *
   * This function sets the products search fields
   */
  public function productsField()
  {
      $args = func_get_args();
      array_unshift($args, "products");

      $this->add($args);
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

      $this->add($args);
  }

    public function offersField()
    {
        $args = func_get_args();
        array_unshift($args, "offers");

        $this->add($args);
    }

    public function add()
    {
        $args = func_get_args();
        $endpoint = array_shift($args);

        if (!array_key_exists($endpoint, $this->dataQuery)) {
            $this->dataQuery[$endpoint] = [];
        }

        $this->dataQuery[$endpoint] = array_merge_recursive(
            (array)$this->dataQuery[$endpoint],
            $this->nestArguments($args)
        );
    }

    public function remove()
    {
        $args = func_get_args();
        $endpoint = array_shift($args);

        $query = &$this->dataQuery[$endpoint];

        foreach ($args as $value) {
            if (array_key_exists($value, (array)$query)) {
                if (end($args) == $value) {
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
      return $this->runQuery("categories", $this->dataQuery["categories"]);
  }

    public function get_offers()
    {
        return $this->runQuery("offers", $this->dataQuery["offers"]);
    }

    private function nestArguments()
    {
        $args = func_get_args();

        $queryKey = $args[0];
        if (count($args) == 2) {
            $query[$queryKey] = $args[1];
        } elseif (count($args) > 2) {
            unset($args[0]);
            $args = array_values($args);
            $query[$queryKey] = $this->nestArguments($args);
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

      $this->productsField($args);
  }

  /**
   * set the latestoffers fields
   *
   * This function sets the latestoffers fields
   */
  public function latestoffers($fieldName, $fieldValue1, $fieldValue2)
  {
      $args = array("sitedetails", $fieldName, $fieldValue1, $fieldValue2);

      $this->productsField($args);
  }

    public function limit($limit)
    {
        $args = array("limit", $limit);

        $this->productsField($args);
    }

    public function offset($offset)
    {
        $args = array("offset", $offset);

        $this->productsField($args);
    }

    public function sortList($sortField, $sortValue)
    {
        $args = array("sort", $sortField, $sortValue);

        $this->productsField($args);
    }

    private function getProductsField()
    {
        # Throw exception if no product field
        return json_encode($this->dataQuery["products"]);
    }

    public function clearQuery()
    {
        $this->dataQuery = [];
        $this->queryResult = [];
    }

    public function iterateProducts()
    {
        if (gettype($this->queryResult) == "string") {
            $queryResult = json_decode($this->queryResult, true);
            if (!array_key_exists('total_results_count', $queryResult) || (array_key_exists('offset', $queryResult) && $queryResult['offset'] >= $queryResult['total_results_count'])) {
                return 0;
            }

            $limit = 10;

            if (array_key_exists('limit', $this->dataQuery["products"])) {
                $limit = $this->dataQuery["products"]['limit'];
            }

            if (!array_key_exists('offset', $queryResult)) {
                $this->dataQuery["products"]['offset'] = 0;
            } else {
                $this->dataQuery["products"]['offset'] = $queryResult['offset'];
            }

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
        return $this->runQuery($endpoint, json_decode($queryJson));
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
        if ($endpoint == null) {
            throw new ParameterError("Query Endpoint was not defined. You need to provide one. Eg: products");
        }

        return $this->dataQuery[$endpoint];
    }

    public function getProducts()
    {
        return $this->runQuery("products", $this->dataQuery["products"]);
    }

    public function query($endpoint, $queryArr = [])
    {
        return $this->runQuery($endpoint, $queryArr);
    }
}
