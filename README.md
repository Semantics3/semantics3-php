# semantics3
semantics3 is the php bindings for accessing the Semantics3 Products API, which provides structured information, including pricing histories, for a large number of products.
See https://www.semantics3.com for more information.

Quickstart guide: https://www.semantics3.com/quickstart
API documentation can be found at https://www.semantics3.com/docs/

## Installation

semantics3 can be installed through composer:

```bash
php composer.phar install
```

To install the latest source from the repository

```bash
git clone https://github.com/Semantics3/semantics3-php.git
```

## Getting Started

In order to use the client, you must have both an API key and an API secret. To obtain your key and secret, you need to first create an account at
https://www.semantics3.com/
You can access your API access credentials from the user dashboard at https://www.semantics3.com/dashboard/applications

### Setup Work

Let's lay the groundwork.

```php
require('lib/Semantics3.php');

# Set up a client to talk to the Semantics3 API using your Semantics3 API Credentials
$key = 'SEM3xxxxxxxxxxxxxxxxxxxxxx';
$secret = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

$requestor = new Semantics3_Products($key,$secret);
```

### First Query aka 'Hello World':

Let's make our first query! For this query, we are going to search for all Toshiba products that fall under the category of "Computers and Accessories", whose cat_id is 4992. 

```php
# Build the query
$requestor->products_field("cat_id", 4992);
$requestor->products_field("brand", "Toshiba");

# Make the query
$results = $requestor->get_products();

# View the results of the query
echo $results;
```

## Examples

The following examples show you how to interface with some of the core functionality of the Semantics3 Products API. For more detailed examples check out the Quickstart guide: https://www.semantics3.com/quickstart

### Explore the Category Tree

In this example we are going to be accessing the categories endpoint. We are going to be specifically exploiring the "Computers and Accessories" category, which has a cat_id of 4992. For more details regarding our category tree and associated cat_ids check out our API docs at https://www.semantics3.com/docs

```python
# Build the query
$requestor->categories_field("cat_id", "4992");

# Execute the query
$results = $requestor->get_products();

# View the results of the query
echo $results;
```

### Nested Search Query

You can intuitively construct all your complex queries but just repeatedly using the products_field() or add() methods.
Here is how we translate the following JSON query:

```javascript
{
	"cat_id" : 4992, 
	"brand"  : "Toshiba",
	"weight" : { "gte":1000000, "lt":1500000 },
	"sitedetails" : {
		"name" : "newegg.com",
		"latestoffers" : {
			"currency": "USD",
			"price"   : { "gte" : 100 } 
		}
	}
}
```


This query returns all Toshiba products within a certain weight range narrowed down to just those that retailed recently on newegg.com for >= USD 100.

```python
# Build the query
$requestor->products_field("cat_id", 4992);
$requestor->products_field("brand", "Toshiba");
$requestor->products_field("weight", "gte", 1000000);
$requestor->products_field("weight", "lt", 1500000);
$requestor->products_field("sitedetails", "name", "newegg.com");
$requestor->products_field("sitedetails", "latestoffers", "currency", "USD");
$requestor->products_field("sitedetails", "latestoffers", "price", "gte", 100);
# Let's make a modification - say we no longer want the weight attribute
$requestor->remove("products", "weight");

# Make the query
$results = $requestor->get_products();
echo $results
```

### Pagination

The Semantics3 API allows for pagination, so you can request for, say, 5 results,
and then continue to obtain the next 5 from where you stopped previously. For the
python semantics3 module, we have implemented this using iterators.
All you have to do is specify a cache size, and use it the same way you would
any iterator:

```python
# Specify a cache size
$requestor->limit(5);

# Execute the query
$results = $requestor->iterate_products();

# View the results of the query
echo $results;
```
Our library will automatically request for results 10 products at a time.


### Explore Price Histories
For this example, we are going to look at a particular product that is sold by select merchants and has a price of >= USD 30 and seen after a specific date (specified as a UNIX timestamp).

```python
# Build the query
$requestor->offers_field("sem3_id", "4znupRCkN6w2Q4Ke4s6sUC");
$requestor->offers_field("seller", ["LFleurs","Frys","Walmart"] );
$requestor->offers_field("currency", "USD");
$requestor->offers_field("price", "gte", 30);
$requestor->offers_field("lastrecorded_at", "gte", 1348654600);



# Make the query
$results = $requestor->get_offers());

# View the results of the query
echo $results
```

## Troubleshooting

If you are getting "Exception CURL error: SSL certificate problem, verify that the CA cert is OK" or similar, that indicates problem with the remote SSL certificate.

To fix this, you should just pass array(CURLOPT_SSL_VERIFYPEER => false) as the $curl_options parameter in OAuthRequester::requestRequestToken.

Or alternatively, if you are using ubuntu, you may do:

```bash
sudo apt-get install --reinstall ca-certificates
```

## Contributing
Use GitHub's standard fork/commit/pull-request cycle.  If you have any questions, email <support@semantics3.com>.

## Author

* Vinoth Gopinathan <vinoth@semantics3.com>

## Copyright

Copyright (c) 2013 Semantics3 Inc.

## License

    The "MIT" License
    
    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:
    
    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.
    
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
    OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
    THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
    DEALINGS IN THE SOFTWARE.

