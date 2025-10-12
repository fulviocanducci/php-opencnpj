<?php 

namespace Canducci\OpenCnpj;

use Canducci\OpenCnpj\Utils\ApiResponse;

interface ICnpjService 
{
    public function getCnpj(string $cnpj): ApiResponse;
}