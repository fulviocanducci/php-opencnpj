<?php

namespace Canducci\OpenCnpj\Utils;

use Canducci\OpenCnpj\Models\Company;
use Exception;

class ApiResponse 
{
    
    public function __construct(protected bool $status, protected ?Company $company, protected ?Exception $ex = null)
    { 

    }

    public function isValid(): bool
    {
        return $this->status === true;
    }

    public function getCompany(): ?Company 
    {
        return $this->company;
    }

    public static function ok(?Company $company): self
    {
        return new ApiResponse(true, $company);
    }

    public static function error(?Exception $ex): self
    {
        return new ApiResponse(false, null, $ex);
    }

    public function getException(): ?Exception
    {
        return $this->ex;
    }
}