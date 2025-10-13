<?php

use Canducci\OpenCnpj\CnpjService;
use Canducci\OpenCnpj\ICnpjService;
use Canducci\OpenCnpj\Providers\OpenCnpjServiceProvider;
use Orchestra\Testbench\TestCase;

class OpenCnpjServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            OpenCnpjServiceProvider::class,
        ];
    }

    /** @test */
    public function it_registers_the_icnpjservice_binding()
    {
        $this->assertTrue($this->app->bound(ICnpjService::class));
        $this->assertInstanceOf(CnpjService::class, $this->app->make(ICnpjService::class));
    }

    /** @test */
    public function alias_resolves_to_the_same_instance()
    {
        $icnpj = $this->app->make(ICnpjService::class);
        $cnpj = $this->app->make(CnpjService::class);

        $this->assertSame($icnpj, $cnpj);
    }

    /** @test */
    public function helper_returns_a_valid_service_instance()
    {
        $this->assertInstanceOf(ICnpjService::class, cnpj());
    }
}
