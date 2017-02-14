<?php

namespace Semantics3;

use PHPUnit\Framework;

class ErrorTest extends Framework\TestCase
{
    public function testConstructorSetsValues()
    {
        $message = 'Why, hello!';
        $httpStatus = 500;
        $httpBody = 'This is not a good time right now.';
        $jsonBody = \json_encode(array(
            'message' => 'This is not a good time right now.',
        ));

        $error = new \Semantics3_Error(
            $message,
            $httpStatus,
            $httpBody,
            $jsonBody
        );

        $this->assertInstanceOf('Exception', $error);
        $this->assertSame($message, $error->getMessage());
        $this->assertSame($httpStatus, $error->getHttpStatus());
        $this->assertSame($httpBody, $error->getHttpBody());
        $this->assertSame($jsonBody, $error->getJsonBody());
    }
}
