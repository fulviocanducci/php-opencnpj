<?php 

namespace Canducci\OpenCnpj\Models;

class Phone
{
    public string $ddd;
    public string $number;
    public bool $isFax;

    public function __construct(array $data)
    {
        $this->ddd = $data['ddd'] ?? '';
        $this->number = $data['numero'] ?? '';
        $this->isFax = $data['is_fax'] ?? false;
    }
}