<?php

namespace Canducci\OpenCnpj\Models;

class Phone
{
    private string $ddd;
    private string $number;
    private bool $isFax;

    public function __construct(array $data)
    {
        $this->ddd = $data['ddd'] ?? '';
        $this->number = $data['numero'] ?? '';
        $this->isFax = $data['is_fax'] ?? false;
    }

    public function getDdd(): string
    {
        return $this->ddd;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function isFax(): bool
    {
        return $this->isFax;
    }
}