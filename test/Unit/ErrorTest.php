<?php

namespace Semantics3\Test\Unit;

use Semantics3\Error;

final class ErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaults()
    {
        $error = new Error();

        $this->assertSame('', $error->getMessage());
        $this->assertNull($error->getHttpStatus());
        $this->assertNull($error->getHttpBody());
        $this->assertNull($error->getJsonBody());
    }

    public function testGetters()
    {
        $message = 'message';
        $httpStatus = 500;
        $httpBody = 'http body';
        $jsonBody = '{"json":"body"}';

        $error = new Error($message, $httpStatus, $httpBody, $jsonBody);

        $this->assertSame($message, $error->getMessage());
        $this->assertSame($httpStatus, $error->getHttpStatus());
        $this->assertSame($httpBody, $error->getHttpBody());
        $this->assertSame($jsonBody, $error->getJsonBody());
    }

    public function testInstanceOfException()
    {
        $error = new Error();

        $this->assertInstanceOf(\Exception::class, $error);
    }
}
