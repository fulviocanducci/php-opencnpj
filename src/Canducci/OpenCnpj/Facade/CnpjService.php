<?php

namespace Canducci\OpenCnpj\Facade;

use Illuminate\Support\Facades\Facade;
use Canducci\OpenCnpj\ICnpjService;

class CnpjService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ICnpjService::class;
    }
}
