<?php

namespace Canducci\OpenCnpj\Models;

class Company
{
    public string $cnpj;
    public string $legalName;
    public string $tradeName;
    public string $status;
    public string $statusDate;
    public string $headOffice;
    public string $startDate;
    public string $mainCnae;
    public array $secondaryCnaes = [];
    public int $secondaryCnaesCount;
    public string $legalNature;
    public string $address;
    public string $number;
    public string $complement;
    public string $neighborhood;
    public string $zip;
    public string $state;
    public string $city;
    public string $email;
    public array $phones = [];
    public string $capital;
    public string $companySize;
    public ?string $simplesOption;
    public ?string $simplesDate;
    public ?string $meiOption;
    public ?string $meiDate;
    public array $shareholders = [];

    public function __construct(array $data)
    {
        $this->cnpj = $data['cnpj'] ?? '';
        $this->legalName = $data['razao_social'] ?? '';
        $this->tradeName = $data['nome_fantasia'] ?? '';
        $this->status = $data['situacao_cadastral'] ?? '';
        $this->statusDate = $data['data_situacao_cadastral'] ?? '';
        $this->headOffice = $data['matriz_filial'] ?? '';
        $this->startDate = $data['data_inicio_atividade'] ?? '';
        $this->mainCnae = $data['cnae_principal'] ?? '';
        $this->secondaryCnaes = $data['cnaes_secundarios'] ?? [];
        $this->secondaryCnaesCount = $data['cnaes_secundarios_count'] ?? 0;
        $this->legalNature = $data['natureza_juridica'] ?? '';
        $this->address = $data['logradouro'] ?? '';
        $this->number = $data['numero'] ?? '';
        $this->complement = $data['complemento'] ?? '';
        $this->neighborhood = $data['bairro'] ?? '';
        $this->zip = $data['cep'] ?? '';
        $this->state = $data['uf'] ?? '';
        $this->city = $data['municipio'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->phones = array_map(fn($p) => new Phone($p), $data['telefones'] ?? []);
        $this->capital = $data['capital_social'] ?? '';
        $this->companySize = $data['porte_empresa'] ?? '';
        $this->simplesOption = $data['opcao_simples'] ?? null;
        $this->simplesDate = $data['data_opcao_simples'] ?? null;
        $this->meiOption = $data['opcao_mei'] ?? null;
        $this->meiDate = $data['data_opcao_mei'] ?? null;
        $this->shareholders = array_map(fn($s) => new Shareholder($s), $data['QSA'] ?? []);
    }
}