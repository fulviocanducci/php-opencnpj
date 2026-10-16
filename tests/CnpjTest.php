<?php

namespace Tests;

use Orchestra\Testbench\TestCase;

final class CnpjTest extends TestCase
{
    public function testCnpjIsTrue()
    {
        $this->assertTrue(\Canducci\OpenCnpj\Utils\Valid::isCnpj('12.345.678/0001-95'));
    }

    public function testCnpjIsFalse()
    {
        $this->assertFalse(\Canducci\OpenCnpj\Utils\Valid::isCnpj('12.345.678/0001-00'));
    }

    public function testCnpjAlphaIsTrue()
    {
        $this->assertTrue(\Canducci\OpenCnpj\Utils\Valid::isCnpj('12.ABC.345/01DE-35'));
    }

    public function testCnpjAlphaIsFalse()
    {
        $this->assertFalse(\Canducci\OpenCnpj\Utils\Valid::isCnpj('AA.345.678/0001-00'));
    }
}
