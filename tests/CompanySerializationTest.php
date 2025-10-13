<?php

namespace Tests;

use Orchestra\Testbench\TestCase;
use Canducci\OpenCnpj\Models\Company;

final class CompanySerializationTest extends TestCase
{
    private array $sampleData = [
        "cnpj" => "09645389000105",
        "razao_social" => "S2 VIAGENS E TURISMO LTDA",
        "nome_fantasia" => "S2 VIAGENS",
        "situacao_cadastral" => "Ativa",
        "data_situacao_cadastral" => "2008-06-16",
        "matriz_filial" => "Matriz",
        "data_inicio_atividade" => "2008-06-16",
        "cnae_principal" => "7911200",
        "cnaes_secundarios" => ["6319400","6499999","7319003","7490104","7912100","8299799"],
        "natureza_juridica" => "Sociedade Empresária Limitada",
        "logradouro" => "AVIADOR NORBERTO VALLIN",
        "numero" => "775",
        "complemento" => "SALA 01",
        "bairro" => "JARDIM BONGIOVANI",
        "cep" => "19050420",
        "uf" => "SP",
        "municipio" => "PRESIDENTE PRUDENTE",
        "email" => "FISCAL.EFICAZCONTABILIDADE@GMAIL.COM",
        "telefones" => [
            ["ddd" => "18", "numero" => "39082500", "is_fax" => false],
            ["ddd" => "18", "numero" => "39161488", "is_fax" => true]
        ],
        "capital_social" => "40000,00",
        "porte_empresa" => "Microempresa (ME)",
        "opcao_simples" => "N",
        "data_opcao_simples" => "2021-01-01",
        "opcao_mei" => "N",
        "data_opcao_mei" => "0000-00-00",
        "QSA" => [
            [
                "nome_socio" => "CARLOS EDUARDO PRIETO",
                "cnpj_cpf_socio" => "***479368**",
                "qualificacao_socio" => "Sócio-Administrador",
                "data_entrada_sociedade" => "2021-06-01",
                "identificador_socio" => "Pessoa Física",
                "faixa_etaria" => "31 a 40 anos"
            ]
        ]
    ];

    public function testToArray()
    {
        $company = new Company($this->sampleData);
        $array = $company->toArray();
        $this->assertSame('09645389000105', $array['cnpj']);
        $this->assertSame('S2 VIAGENS E TURISMO LTDA', $array['legalName']);
        $this->assertCount(2, $array['phones']);
        $this->assertSame('39082500', $array['phones'][0]['number']);
        $this->assertCount(1, $array['shareholders']);
        $this->assertSame('CARLOS EDUARDO PRIETO', $array['shareholders'][0]['name']);
    }

    public function testJsonSerialize()
    {
        $company = new Company($this->sampleData);
        $json = json_encode($company);
        $expected = json_encode($company->toArray());

        $this->assertJsonStringEqualsJsonString($expected, $json);
    }
}
