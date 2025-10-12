<?php

use PHPUnit\Framework\TestCase;
use Canducci\OpenCnpj\CnpjService;
use Canducci\OpenCnpj\Utils\Valid;
use Canducci\OpenCnpj\Utils\ApiResponse;

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
        $this->assertFalse(Valid::isCnpj('11111111111111'));
        $this->assertFalse(Valid::isCnpj('123'));
    }

    public function testGetCnpjReturnsApiResponse(): void
    {
        $cnpj = '11222333000181';
        $response = $this->service->getCnpj($cnpj);
        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertTrue($response->isValid() || !$response->isValid());
    }
}
