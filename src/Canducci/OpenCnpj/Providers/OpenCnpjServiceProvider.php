<?php 

namespace Canducci\OpenCnpj\Providers;

use Canducci\OpenCnpj\CnpjService;
use Canducci\OpenCnpj\ICnpjService;
use Illuminate\Support\ServiceProvider;

class OpenCnpjServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (!$this->app->bound(ICnpjService::class))
        {
            $this->app->singleton(ICnpjService::class, function()
            {
                return new CnpjService();
            });
        }
        $this->app->alias(ICnpjService::class, CnpjService::class);
    }
}