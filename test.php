<?php 
    
require('lib/Semantics3.php');


$key = '';
$secret = '';

$requestor = new Semantics3_Products($key,$secret);


/**
* Random Complicated Query
* 
*/
$requestor->products_field("cat_id", 4992);
$requestor->products_field("brand", "Toshiba");
$requestor->products_field("name", "Portege");

$requestor->products_field("sitedetails", "name", "amazon.com");
$requestor->sitedetails("latestoffers", "price", "gte", 100);
$requestor->sitedetails("latestoffers", "currency", "USD");

$requestor->products_field("weight", "gte", 1000000);
$requestor->products_field("weight", "lt", 1500000);

$requestor->products_field("sort", "name", "desc");

echo $requestor->get_products()."\n";
$requestor->clear_query();

/**
* Sem3_ID Array Query
* 
*/
$requestor->products_field("sem3_id", array("2NnNAztqoGeoQGeSya0y4K", "0xzFQX9Ss8ecMwkMy0C8Ui", "1XgtmTtMgWswmYaGS6Kgyc") );

echo $requestor->get_products()."\n";
$requestor->clear_query();

/**
* Category Query
* 
*/
$requestor->categories_field("name", "hard drives");

echo $requestor->get_categories()."\n";
$requestor->clear_query();



echo "OK!\n";