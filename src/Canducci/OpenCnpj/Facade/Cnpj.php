<?php

namespace Canducci\OpenCnpj\Facade;

use Illuminate\Support\Facades\Facade;
use Canducci\OpenCnpj\ICnpjService;

class Cnpj extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ICnpjService::class;
    }
}
