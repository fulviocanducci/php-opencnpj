<?php

namespace Canducci\OpenCnpj\Models;

use Canducci\OpenCnpj\Models\Bases\BaseModel;

class Company extends BaseModel
{
    protected string $cnpj;
    protected string $legalName;
    protected string $tradeName;
    protected string $status;
    protected string $statusDate;
    protected string $headOffice;
    protected string $startDate;
    protected string $mainCnae;
    protected array $secondaryCnaes = [];
    protected int $secondaryCnaesCount;
    protected string $legalNature;
    protected string $address;
    protected string $number;
    protected string $complement;
    protected string $neighborhood;
    protected string $zip;
    protected string $state;
    protected string $city;
    protected string $email;
    protected array $phones = [];
    protected string $capital;
    protected string $companySize;
    protected ?string $simplesOption;
    protected ?string $simplesDate;
    protected ?string $meiOption;
    protected ?string $meiDate;
    protected array $shareholders = [];

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

    public function getCnpj(): string { return $this->cnpj; }
    public function getLegalName(): string { return $this->legalName; }
    public function getTradeName(): string { return $this->tradeName; }
    public function getStatus(): string { return $this->status; }
    public function getStatusDate(): string { return $this->statusDate; }
    public function getHeadOffice(): string { return $this->headOffice; }
    public function getStartDate(): string { return $this->startDate; }
    public function getMainCnae(): string { return $this->mainCnae; }
    public function getSecondaryCnaes(): array { return $this->secondaryCnaes; }
    public function getSecondaryCnaesCount(): int { return $this->secondaryCnaesCount; }
    public function getLegalNature(): string { return $this->legalNature; }
    public function getAddress(): string { return $this->address; }
    public function getNumber(): string { return $this->number; }
    public function getComplement(): string { return $this->complement; }
    public function getNeighborhood(): string { return $this->neighborhood; }
    public function getZip(): string { return $this->zip; }
    public function getState(): string { return $this->state; }
    public function getCity(): string { return $this->city; }
    public function getEmail(): string { return $this->email; }
    public function getPhones(): array { return $this->phones; }
    public function getCapital(): string { return $this->capital; }
    public function getCompanySize(): string { return $this->companySize; }
    public function getSimplesOption(): ?string { return $this->simplesOption; }
    public function getSimplesDate(): ?string { return $this->simplesDate; }
    public function getMeiOption(): ?string { return $this->meiOption; }
    public function getMeiDate(): ?string { return $this->meiDate; }
    public function getShareholders(): array { return $this->shareholders; }

    public function __get($name) 
    {
        if (property_exists($this, $name)) 
        {
            return $this->$name;
        }
        return null;
    }
}