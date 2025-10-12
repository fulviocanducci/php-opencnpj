<?php

namespace Canducci\OpenCnpj\Models;

class Shareholder
{
    private string $name;
    private string $cpfOrCnpj;
    private string $qualification;
    private string $entryDate;
    private string $type;
    private string $ageRange;

    public function __construct(array $data)
    {
        $this->name = $data['nome_socio'] ?? '';
        $this->cpfOrCnpj = $data['cnpj_cpf_socio'] ?? '';
        $this->qualification = $data['qualificacao_socio'] ?? '';
        $this->entryDate = $data['data_entrada_sociedade'] ?? '';
        $this->type = $data['identificador_socio'] ?? '';
        $this->ageRange = $data['faixa_etaria'] ?? '';
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCpfOrCnpj(): string
    {
        return $this->cpfOrCnpj;
    }

    public function getQualification(): string
    {
        return $this->qualification;
    }

    public function getEntryDate(): string
    {
        return $this->entryDate;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAgeRange(): string
    {
        return $this->ageRange;
    }
}