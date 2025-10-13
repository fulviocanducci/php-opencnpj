<?php

namespace Tests;

use Orchestra\Testbench\TestCase;
use Canducci\OpenCnpj\Models\Company;

final class CompanyTest extends TestCase
{
    private array $sampleData = [
        'cnpj' => '12345678000199',
        'razao_social' => 'ACME INDUSTRIES LTDA',
        'nome_fantasia' => 'ACME',
        'telefones' => [
            ['numero' => '1123456789'],
            ['numero' => '11987654321']
        ],
        'QSA' => [
            ['nome_socio' => 'John Doe'],
            ['nome_socio' => 'Jane Doe']
        ]
    ];

    public function testToArray()
    {
        $company = new Company($this->sampleData);        
        $array = $company->toArray();

        // Checa propriedades principais
        $this->assertSame('12345678000199', $array['cnpj']);
        $this->assertSame('ACME INDUSTRIES LTDA', $array['legalName']);

        // Checa arrays de objetos
        $this->assertCount(2, $array['phones']);
        $this->assertSame('1123456789', $array['phones'][0]['number']);
        $this->assertCount(2, $array['shareholders']);
        $this->assertSame('John Doe', $array['shareholders'][0]['name']);
    }

    public function testJsonSerialize()
    {
        $company = new Company($this->sampleData);
        $json = json_encode($company);

        $expected = json_encode($company->toArray());

        $this->assertJsonStringEqualsJsonString($expected, $json);
    }
}
