<?php

namespace Semantics3\Test\Unit;

use Semantics3\AuthenticationError;
use Semantics3\Error;

final class AuthenticationErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceOfError()
    {
        $error = new AuthenticationError();

        $this->assertInstanceOf(Error::class, $error);
    }
}
