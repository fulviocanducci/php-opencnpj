<?php

namespace Tests;

use Canducci\OpenCnpj\Facade\Cnpj;
use Canducci\OpenCnpj\ICnpjService;
use Orchestra\Testbench\TestCase;

class CnpjFacadeTest extends TestCase
{
    public function test_facade_resolves_service(): void
    {
        $service = $this->createMock(ICnpjService::class);

        $this->app->instance(ICnpjService::class, $service);

        $this->assertSame($service, Cnpj::getFacadeRoot());
    }
}