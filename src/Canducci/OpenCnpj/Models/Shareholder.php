<?php

namespace Canducci\OpenCnpj\Models;

use Canducci\OpenCnpj\Models\Bases\BaseModel;

class Shareholder extends BaseModel
{
    protected string $name;
    protected string $cpfOrCnpj;
    protected string $qualification;
    protected string $entryDate;
    protected string $type;
    protected string $ageRange;

    public function __construct(array $data)
    {
        $this->name = $data['nome_socio'] ?? '';
        $this->cpfOrCnpj = $data['cnpj_cpf_socio'] ?? '';
        $this->qualification = $data['qualificacao_socio'] ?? '';
        $this->entryDate = $data['data_entrada_sociedade'] ?? '';
        $this->type = $data['identificador_socio'] ?? '';
        $this->ageRange = $data['faixa_etaria'] ?? '';
    }

    public function getName(): string { return $this->name; }

    public function getCpfOrCnpj(): string { return $this->cpfOrCnpj; }

    public function getQualification(): string { return $this->qualification; }

    public function getEntryDate(): string { return $this->entryDate; }

    public function getType(): string { return $this->type; }

    public function getAgeRange(): string { return $this->ageRange; }

    public function __get($name) 
    {
        if (property_exists($this, $name)) 
        {
            return $this->$name;
        }
        return null;
    }
}