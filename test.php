<?php 
    
require_once __DIR__ . '/vendor/autoload.php';


$key = '';
$secret = '';

$requestor = new Semantics3\Products($key,$secret);


/**
* Random Complicated Query
* 
*/
$requestor->productsField("cat_id", 4992);
$requestor->productsField("brand", "Toshiba");
$requestor->productsField("name", "Portege");

$requestor->productsField("sitedetails", "name", "amazon.com");
$requestor->sitedetails("latestoffers", "price", "gte", 100);
$requestor->sitedetails("latestoffers", "currency", "USD");

$requestor->productsField("weight", "gte", 1000000);
$requestor->productsField("weight", "lt", 1500000);

$requestor->productsField("sort", "name", "desc");

echo $requestor->getProducts()."\n";
$requestor->clearQuery();

/**
* Sem3_ID Array Query
* 
*/
$requestor->productsField("sem3_id", array("2NnNAztqoGeoQGeSya0y4K", "0xzFQX9Ss8ecMwkMy0C8Ui", "1XgtmTtMgWswmYaGS6Kgyc") );

echo $requestor->getProducts()."\n";
$requestor->clearQuery();

/**
* Category Query
* 
*/
$requestor->categoriesField("name", "hard drives");

echo $requestor->getCategories()."\n";
$requestor->clearQuery();



echo "OK!\n";
