<?php 

namespace Canducci\OpenCnpj\Models;

class Shareholder
{
    public string $name;
    public string $cpfOrCnpj;
    public string $qualification;
    public string $entryDate;
    public string $type;
    public string $ageRange;

    public function __construct(array $data)
    {
        $this->name = $data['nome_socio'] ?? '';
        $this->cpfOrCnpj = $data['cnpj_cpf_socio'] ?? '';
        $this->qualification = $data['qualificacao_socio'] ?? '';
        $this->entryDate = $data['data_entrada_sociedade'] ?? '';
        $this->type = $data['identificador_socio'] ?? '';
        $this->ageRange = $data['faixa_etaria'] ?? '';
    }
}