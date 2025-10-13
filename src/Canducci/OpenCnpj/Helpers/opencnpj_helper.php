<?php

use Canducci\OpenCnpj\CnpjService;
use Canducci\OpenCnpj\ICnpjService;

if (function_exists('cnpj') === false) 
{
    function cnpj(): ICnpjService
    {
        if (function_exists('app')) {
            return app(ICnpjService::class);
        }
        return new CnpjService();
    }
}