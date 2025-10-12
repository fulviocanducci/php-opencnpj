<?php

use PHPUnit\Framework\TestCase;
use Canducci\OpenCnpj\CnpjService;
use Canducci\OpenCnpj\Utils\Valid;
use Canducci\OpenCnpj\Utils\ApiResponse;
use Canducci\OpenCnpj\Models\Phone;
use Canducci\OpenCnpj\Models\Shareholder;

final class CnpjServiceTest extends TestCase
{
    private CnpjService $service;

    protected function setUp(): void
    {
        $this->service = CnpjService::create();
    }

    public function testNormalizeCnpj(): void
    {
        $reflection = new ReflectionClass($this->service);
        $method = $reflection->getMethod('normalizeCnpj');
        $method->setAccessible(true);
        $raw = '11.222.333/0001-81';
        $normalized = $method->invoke($this->service, $raw);
        $this->assertEquals('11222333000181', $normalized);
    }

    public function testValidCnpj(): void
    {
        $this->assertTrue(Valid::isCnpj('11222333000181'));
        $this->assertTrue(Valid::isCnpj('60326114000158'));
        $this->assertFalse(Valid::isCnpj('123'));
        $this->assertFalse(Valid::isCnpj(''));
        $this->assertFalse(Valid::isCnpj('00000000000000'));
    }

    public function testGetCnpjReturnsApiResponse(): void
    {
        $mockResponse = [
            'cnpj' => '11222333000181',
            'razao_social' => 'Empresa Teste LTDA',
            'nome_fantasia' => 'Empresa Teste',
            'situacao_cadastral' => 'Ativa',
            'data_situacao_cadastral' => '01/01/2000',
            'matriz_filial' => 'Matriz',
            'data_inicio_atividade' => '01/01/2000',
            'cnae_principal' => '6201-5/01',
            'cnaes_secundarios' => ['6202-3/02'],
            'cnaes_secundarios_count' => 1,
            'natureza_juridica' => '2062',
            'logradouro' => 'Rua Teste',
            'numero' => '123',
            'complemento' => 'Sala 1',
            'bairro' => 'Centro',
            'cep' => '01001000',
            'uf' => 'SP',
            'municipio' => 'São Paulo',
            'email' => 'teste@empresa.com',
            'telefones' => [
                ['ddd' => '11', 'numero' => '900000000', 'is_fax' => false]
            ],
            'capital_social' => '100000',
            'porte_empresa' => 'Médio',
            'opcao_simples' => 'Sim',
            'data_opcao_simples' => '01/01/2000',
            'opcao_mei' => null,
            'data_opcao_mei' => null,
            'QSA' => [
                [
                    'nome_socio' => 'Fulvio C.',
                    'cnpj_cpf_socio' => '12345678901',
                    'qualificacao_socio' => 'Administrador',
                    'data_entrada_sociedade' => '01/01/2000',
                    'identificador_socio' => 'PF',
                    'faixa_etaria' => '31 a 40 anos'
                ]
            ]
        ];

        // Mock do getExec
        $mock = $this->getMockBuilder(CnpjService::class)
            ->onlyMethods(['getExec'])
            ->getMock();
        $mock->method('getExec')->willReturn([
            'value' => json_encode($mockResponse),
            'error' => 0,
            'errorMsg' => ''
        ]);

        $response = $mock->getCnpj('11222333000181');
        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertTrue($response->isValid());

        $company = $response->getCompany();
        $this->assertEquals('11222333000181', $company->getCnpj());
        $this->assertEquals('Empresa Teste LTDA', $company->getLegalName());
        $this->assertEquals('Rua Teste', $company->getAddress());
        $this->assertCount(1, $company->getPhones());
        $this->assertInstanceOf(Phone::class, $company->getPhones()[0]);
        $this->assertEquals('11', $company->getPhones()[0]->getDdd());
        $this->assertCount(1, $company->getShareholders());
        $this->assertInstanceOf(Shareholder::class, $company->getShareholders()[0]);
        $this->assertEquals('Fulvio C.', $company->getShareholders()[0]->getName());
    }

    public function testGetCnpjWithInvalidCnpj(): void
    {
        $cnpj = '123';
        $response = $this->service->getCnpj($cnpj);
        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->isValid());
        $this->assertNotNull($response->getException());
        $this->assertStringContainsString('Invalid CNPJ format', $response->getException()->getMessage());
    }

    public function testGetCnpjEmptyResponse(): void
    {
        $mock = $this->getMockBuilder(CnpjService::class)
            ->onlyMethods(['getExec'])
            ->getMock();
        $mock->method('getExec')->willReturn([
            'value' => '',
            'error' => 0,
            'errorMsg' => ''
        ]);

        $response = $mock->getCnpj('11222333000181');
        $this->assertFalse($response->isValid());
        $this->assertStringContainsString('Empty response', $response->getException()->getMessage());
    }

    public function testGetCnpjInvalidJson(): void
    {
        $mock = $this->getMockBuilder(CnpjService::class)
            ->onlyMethods(['getExec'])
            ->getMock();
        $mock->method('getExec')->willReturn([
            'value' => '{invalid_json:',
            'error' => 0,
            'errorMsg' => ''
        ]);

        $response = $mock->getCnpj('11222333000181');
        $this->assertFalse($response->isValid());
        $this->assertStringContainsString('Invalid JSON response', $response->getException()->getMessage());
    }

    public function testGetCnpjCurlError(): void
    {
        $mock = $this->getMockBuilder(CnpjService::class)
            ->onlyMethods(['getExec'])
            ->getMock();
        $mock->method('getExec')->willReturn([
            'value' => null,
            'error' => 28,
            'errorMsg' => 'Timeout'
        ]);

        $response = $mock->getCnpj('11222333000181');
        $this->assertFalse($response->isValid());
        $this->assertStringContainsString('Timeout', $response->getException()->getMessage());
    }
}
