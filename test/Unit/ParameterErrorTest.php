<?php

namespace Semantics3\Test\Unit;

use Semantics3\Error;
use Semantics3\ParameterError;

class ParameterErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceOfError()
    {
        $error = new ParameterError();

        $this->assertInstanceOf(Error::class, $error);
    }
}
