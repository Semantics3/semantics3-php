# semantics3-php
semantics3-php is the PHP bindings for accessing the Semantics3 Products API, which provides structured information, including pricing histories, for a large number of products.
See https://www.semantics3.com for more information.

Quickstart guide: https://www.semantics3.com/quickstart
API documentation can be found at https://www.semantics3.com/docs/

## Installation

semantics3-php can be installed through composer:

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

### First Request aka 'Hello World':

Let's run our first request! We are going to run a simple search fo the word "iPhone" as follows:

```php
# Build the request
$requestor->products_field( "search", "iphone" );

# Run the request
$results = $requestor->get_products();

# View the results of the request
echo $results;
```

## Sample Requests

The following requests show you how to interface with some of the core functionality of the Semantics3 Products API. For more detailed examples check out the Quickstart guide: https://www.semantics3.com/quickstart

### Pagination

The example in our "Hello World" script returns the first 10 results. In this example, we'll scroll to subsequent pages, beyond our initial request:

```php
# Build the request
$requestor->products_field( "search", "iphone" );

# Run the request
$results = $requestor->get_products();

# View the results of the request
echo $results;

$page = 0
while ($results = $requestor->iterate_products()) {
    $page++;
    echo "We are at page = $page\n";
    echo "The results for this page are:\n";
    echo $results;
}
```

### UPC Query

Running a UPC/EAN/GTIN query is as simple as running a search query:

```php
# Build the request
$requestor->products_field( "upc", "883974958450" );
$requestor->products_field( "field", ["name","gtins"] );

# Run the request
$results = $requestor->get_products();

# View the results of the request
echo $results;
```

### URL Query

Get the picture? You can run URL queries as follows:

```php
$requestor->products_field( "url", "http://www.walmart.com/ip/15833173" );
$results = $requestor->get_products();
echo $results;
```

### Price Filter

Filter by price using the "lt" (less than) tag:

```php
$requestor->products_field( "search", "iphone" );
$requestor->products_field( "price", "lt", 300 );
$results = $requestor->get_products();
echo $results;
```

### Category ID Query

To lookup details about a cat_id, run your request against the categories resource:

```php
# Build the request
$requestor->categories_field("cat_id", "4992");

# Run the request
$results = $requestor->get_products();

# View the results of the request
echo $results;
```

## Webhooks
You can use webhooks to get near-real-time price updates from Semantics3.

### Creating a webhook

You can register a webhook with Semantics3 by sending a POST request to `"webhooks"` endpoint.
To verify that your URL is active, a GET request will be sent to your server with a `verification_code` parameter. Your server should respond with `verification_code` in the response body to complete the verification process.

```php
$params["webhook_uri"] = "http://mydomain.com/webhooks-callback-url";


$results = $requestor->run_query("webhooks", $params, "POST");
echo $results;
```

To fetch existing webhooks
```php
$results = $requestor->run_query("webhooks", null);
echo $results;
```

To remove a webhook
```php
$webhook_id = "7JcGN81u";

$endpoint = "webhooks/" . $webhook_id;

$results = $requestor->run_query($endpoint, null, "DELETE");
echo $results;
```

### Registering events
Once you register a webhook, you can start adding events to it. Semantics3 server will send you notifications when these events occur.
To register events for a specific webhook send a POST request to the `"webhooks/{webhook_id}/events"` endpoint

```php
$params = array(
    "type" => "price.change",
    "product" => array(
        "sem3_id" => "1QZC8wchX62eCYS2CACmka"
    ),
    "constraints" => array(
        "gte" => 10,
        "lte" => 100
    )
);

$webhook_id = "7JcGN81u";
$endpoint = "webhooks/" . $webhook_id . "/events";

$results = $requestor->run_query($endpoint, $params, "POST");
echo $results;
```

To fetch all registered events for a give webhook
```php
$webhook_id = "7JcGN81u";
$endpoint = "webhooks/" . $webhook_id . "/events";

$results = $requestor->run_query($endpoint, $params, "GET");
echo $results;
```

### Webhook Notifications
Once you have created a webhook and registered events on it, notifications will be sent to your registered webhook URI via a POST request when the corresponding events occur. Make sure that your server can accept POST requests. Here is how a sample notification object looks like
```javascript
{
    "type": "price.change",
    "event_id": "XyZgOZ5q",
    "notification_id": "X4jsdDsW",
    "changes": [{
        "site": "abc.com",
        "url": "http://www.abc.com/def",
        "previous_price": 45.50,
        "current_price": 41.00
    }, {
        "site": "walmart.com",
        "url": "http://www.walmart.com/ip/20671263",
        "previous_price": 34.00,
        "current_price": 42.00
    }]
}
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

Copyright (c) 2015 Semantics3 Inc.

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

