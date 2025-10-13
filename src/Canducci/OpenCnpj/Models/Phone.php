<?php

namespace Canducci\OpenCnpj\Models;

use Canducci\OpenCnpj\Models\Bases\BaseModel;

class Phone extends BaseModel
{
    protected string $ddd;
    protected string $number;
    protected bool $isFax;

    public function __construct(array $data)
    {
        $this->ddd = $data['ddd'] ?? '';
        $this->number = $data['numero'] ?? '';
        $this->isFax = $data['is_fax'] ?? false;
    }

    public function getDdd(): string { return $this->ddd; }

    public function getNumber(): string { return $this->number; }

    public function isFax(): bool { return $this->isFax; }

    public function __get($name) 
    {
        if (property_exists($this, $name)) 
        {
            return $this->$name;
        }
        return null;
    }
}