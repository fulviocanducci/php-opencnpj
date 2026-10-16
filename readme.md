# PHP OpenCNPJ

[![Downloads](https://img.shields.io/packagist/dt/fulviocanducci/opencnpj.svg?style=flat)](https://packagist.org/packages/fulviocanducci/opencnpj) [![License](https://img.shields.io/packagist/l/fulviocanducci/opencnpj.svg)](https://packagist.org/packages/fulviocanducci/opencnpj) [![Version](https://img.shields.io/packagist/v/fulviocanducci/opencnpj.svg?label=version)](https://packagist.org/packages/fulviocanducci/opencnpj) [![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.1-blue.svg)](https://php.net) [![CI & Release](https://github.com/fulviocanducci/php-opencnpj/actions/workflows/ci.yml/badge.svg)](https://github.com/fulviocanducci/php-opencnpj/actions/workflows/ci.yml)

Uma biblioteca PHP simples e eficiente para consultar informações de empresas brasileiras através do CNPJ utilizando a API do OpenCNPJ.

Referente ao Projeto [https://opencnpj.org/](https://opencnpj.org/)

## Características

- ✅ Consulta dados completos de empresas pelo CNPJ.
- ✅ Validação automática de CNPJ antes da consulta.
- ✅ Informações detalhadas incluindo sócios, telefones e endereço.
- ✅ Tratamento robusto de erros.
- ✅ Modelos estruturados com encapsulamento adequado.
- ✅ Interface bem definida com `ICnpjService`.
- ✅ Compatível com PHP 8.1+.
- ✅ PSR-4 autoloading.
- ✅ Suporte ao Laravel.
- ✅ Facade com `CnpjService` - (Versão v1.0.1 ou superior).

## Instalação

Instale via Composer:

```bash
composer require fulviocanducci/opencnpj
```

## Integração com Laravel

Para integrar com Laravel, registre o service provider no arquivo `config/app.php`

```php
'providers' => [
    // ...
    Canducci\OpenCnpj\Providers\OpenCnpjServiceProvider::class,
],
```

ou na versões mais recentes `bootstrap/providers.php`


```php
use App\Providers\AppServiceProvider;
use Canducci\OpenCnpj\Providers\OpenCnpjServiceProvider;

return [
    AppServiceProvider::class,
    OpenCnpjServiceProvider::class,
];
```

Ou utilize o auto-discovery do Composer (recomendado para Laravel 5.5+).

Após a instalação, você pode injetar o `CnpjService` em seus controllers:

```php
<?php

namespace App\Http\Controllers;

use Canducci\OpenCnpj\CnpjService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function show(Request $request, CnpjService $cnpjService)
    {
        $cnpj = $request->input('cnpj');
        $response = $cnpjService->get($cnpj);

        if ($response->isValid()) {
            return response()->json($response->getCompany());
        }

        return response()->json(['error' => $response->getException()->getMessage()], 400);
    }
}
```

Facade (versão v1.0.1 ou superior) você utilizar de modo simplificado, sem precisar injetar o serviço no controller:

```php
namespace App\Http\Controllers;

use Canducci\OpenCnpj\Facade\CnpjService; //Facade
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function show(Request $request)
    {
        $cnpj = $request->input('cnpj');
        $response = CnpjService::get($cnpj); // Chamada via Facade

        if ($response->isValid()) {
            return response()->json($response->getCompany());
        }

        return response()->json(['error' => $response->getException()->getMessage()], 400);
    }
}
```


## Uso Básico

```php
<?php

require_once 'vendor/autoload.php';

use Canducci\OpenCnpj\CnpjService;

// Criar uma instância do CnpjService
$cnpjService = CnpjService::create();

// Consultar CNPJ (com ou sem formatação)
$response = $cnpjService->get('11.222.333/0001-81');
// ou
$response = $cnpjService->get('11222333000181');

// Verificar se a consulta foi bem-sucedida
if ($response->isValid()) {
    $company = $response->getCompany();

    echo "Razão Social: " . $company->getLegalName() . "\n";
    echo "Nome Fantasia: " . $company->getTradeName() . "\n";
    echo "CNPJ: " . $company->get() . "\n";
    echo "Status: " . $company->getStatus() . "\n";
    echo "Endereço: " . $company->getAddress() . ", " . $company->getNumber() . "\n";
    echo "Cidade: " . $company->getCity() . " - " . $company->getState() . "\n";
    echo "CEP: " . $company->getZip() . "\n";

    // Listar telefones
    foreach ($company->getPhones() as $phone) {
        echo "Telefone: (" . $phone->getDdd() . ") " . $phone->getNumber();
        echo $phone->isFax() ? " (Fax)" : "";
        echo "\n";
    }

    // Listar sócios
    foreach ($company->getShareholders() as $shareholder) {
        echo "Sócio: " . $shareholder->getName() . "\n";
        echo "CPF/CNPJ: " . $shareholder->getCpfOrCnpj() . "\n";
        echo "Qualificação: " . $shareholder->getQualification() . "\n";
    }

} else {
    $exception = $response->getException();
    echo "Erro: " . $exception->getMessage() . "\n";
}
```

## Estrutura dos Dados

### Company (Empresa)

A classe `Company` contém todas as informações da empresa com métodos getters e acesso mágico via `__get` para acesso aos dados:

```php
$company->getCnpj() ou $company->cnpj                    // CNPJ da empresa
$company->getLegalName() ou $company->legalName         // Razão social
$company->getTradeName() ou $company->tradeName         // Nome fantasia
$company->getStatus() ou $company->status               // Situação cadastral
$company->getStatusDate() ou $company->statusDate       // Data da situação cadastral
$company->getHeadOffice() ou $company->headOffice       // Matriz/Filial
$company->getStartDate() ou $company->startDate         // Data de início da atividade
$company->getMainCnae() ou $company->mainCnae           // CNAE principal
$company->getSecondaryCnaes() ou $company->secondaryCnaes // Array de CNAEs secundários
$company->getSecondaryCnaesCount() ou $company->secondaryCnaesCount // Quantidade de CNAEs secundários
$company->getLegalNature() ou $company->legalNature     // Natureza jurídica
$company->getAddress() ou $company->address             // Logradouro
$company->getNumber() ou $company->number               // Número
$company->getComplement() ou $company->complement       // Complemento
$company->getNeighborhood() ou $company->neighborhood   // Bairro
$company->getZip() ou $company->zip                     // CEP
$company->getState() ou $company->state                 // UF
$company->getCity() ou $company->city                   // Município
$company->getEmail() ou $company->email                 // E-mail
$company->getPhones() ou $company->phones               // Array de telefones (Phone[])
$company->getCapital() ou $company->capital             // Capital social
$company->getCompanySize() ou $company->companySize     // Porte da empresa
$company->getSimplesOption() ou $company->simplesOption // Opção pelo Simples
$company->getSimplesDate() ou $company->simplesDate     // Data da opção pelo Simples
$company->getMeiOption() ou $company->meiOption         // Opção pelo MEI
$company->getMeiDate() ou $company->meiDate             // Data da opção pelo MEI
$company->getShareholders() ou $company->shareholders   // Array de sócios (Shareholder[])
```

### Phone (Telefone)

```php
$phone->getDdd() ou $phone->ddd        // Código DDD
$phone->getNumber() ou $phone->number  // Número do telefone
$phone->isFax() ou $phone->fax         // Se é fax (boolean)
```

### Shareholder (Sócio)

```php
$shareholder->getName() ou $shareholder->name                 // Nome do sócio
$shareholder->getCpfOrCnpj() ou $shareholder->cpfOrCnpj       // CPF ou CNPJ do sócio
$shareholder->getQualification() ou $shareholder->qualification // Qualificação do sócio
$shareholder->getEntryDate() ou $shareholder->entryDate       // Data de entrada na sociedade
$shareholder->getType() ou $shareholder->type                 // Identificador do sócio
$shareholder->getAgeRange() ou $shareholder->ageRange         // Faixa etária
```

## Serialização JSON

As classes `Company`, `Phone` e `Shareholder` implementam serialização JSON, permitindo converter os objetos em arrays ou JSON facilmente:

```php
$company = $response->getCompany();

// Converter para array
$companyArray = $company->toArray();

// Converter para JSON
$companyJson = json_encode($company, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

echo $companyJson;
```

## Validação de CNPJ

A biblioteca inclui validação automática de CNPJ antes de fazer a consulta à API:

```php
use Canducci\OpenCnpj\Utils\Valid;

// Validar CNPJ manualmente
if (Valid::isCnpj('11.222.333/0001-81')) {
    echo "CNPJ válido!";
} else {
    echo "CNPJ inválido!";
}

// A validação é feita automaticamente no CnpjService
$cnpjService = CnpjService::create();
$response = $cnpjService->get('11222333000181'); // Valida automaticamente
```

## Tratamento de Erros

A biblioteca utiliza a classe `ApiResponse` para encapsular tanto sucessos quanto erros:

```php
$response = $cnpjService->get('11222333000181');

if ($response->isValid()) {
    // Sucesso - dados disponíveis
    $company = $response->getCompany();
} else {
    // Erro - verificar exceção
    $exception = $response->getException();
    echo "Código do erro: " . $exception->getCode() . "\n";
    echo "Mensagem: " . $exception->getMessage() . "\n";
}
```

### Tipos de Erro

- **CNPJ Inválido**: Quando o formato do CNPJ não passa na validação
- **Erro de Rede**: Problemas de conectividade ou timeout
- **Resposta Inválida**: JSON malformado ou resposta vazia da API
- **CNPJ Não Encontrado**: Quando o CNPJ não existe na base de dados

## Exemplos de Uso

### Exemplo Completo

```php
<?php

require_once 'vendor/autoload.php';

use Canducci\OpenCnpj\CnpjService;

function consultarCNPJ(string $cnpj): void
{
    $cnpjService = CnpjService::create();
    $response = $cnpjService->get($cnpj);

    if (!$response->isValid()) {
        echo "Erro ao consultar CNPJ: " . $response->getException()->getMessage() . "\n";
        return;
    }

    $company = $response->getCompany();

    echo "INFORMAÇÕES DA EMPRESA\n";
    echo "========================\n";
    echo "CNPJ: {$company->getCnpj()}\n";
    echo "Razão Social: {$company->getLegalName()}\n";
    echo "Nome Fantasia: {$company->getTradeName()}\n";
    echo "Status: {$company->getStatus()}\n";
    echo "Porte: {$company->getCompanySize()}\n";
    echo "Capital Social: {$company->getCapital()}\n";

    echo "\n ENDEREÇO\n";
    echo "===========\n";
    echo "{$company->getAddress()}, {$company->getNumber()}\n";
    if (!empty($company->getComplement())) {
        echo "Complemento: {$company->getComplement()}\n";
    }
    echo "{$company->getNeighborhood()}\n";
    echo "{$company->getCity()} - {$company->getState()}\n";
    echo "CEP: {$company->getZip()}\n";

    if (!empty($company->getPhones())) {
        echo "\n TELEFONES\n";
        echo "============\n";
        foreach ($company->getPhones() as $phone) {
            $type = $phone->isFax() ? "Fax" : "Telefone";
            echo "{$type}: ({$phone->getDdd()}) {$phone->getNumber()}\n";
        }
    }

    if (!empty($company->getShareholders())) {
        echo "\n👥 SÓCIOS\n";
        echo "=========\n";
        foreach ($company->getShareholders() as $shareholder) {
            echo "Nome: {$shareholder->getName()}\n";
            echo "CPF/CNPJ: {$shareholder->getCpfOrCnpj()}\n";
            echo "Qualificação: {$shareholder->getQualification()}\n";
            echo "---\n";
        }
    }
}

// Exemplo de uso
consultarCNPJ('11.222.333/0001-81');
```

## Arquitetura

A biblioteca segue boas práticas de desenvolvimento:

- **Interface `ICnpjService`**: Define o contrato para serviços de consulta CNPJ
- **Classe `CnpjService`**: Implementação concreta do serviço
- **Modelos Encapsulados**: Classes `Company`, `Phone` e `Shareholder` com propriedades privadas e métodos getters
- **Validação**: Classe `Valid` para validação de CNPJ
- **Tratamento de Erros**: Classe `ApiResponse` para encapsular respostas

## Requisitos

- PHP 8.1 ou superior
- Extensão cURL habilitada
- Conexão com a internet

## API Utilizada

Esta biblioteca utiliza a API gratuita do [OpenCNPJ](https://api.opencnpj.org/), que fornece dados atualizados da Receita Federal do Brasil.

## Contribuição

Contribuições são bem-vindas! Por favor:

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request

## Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes.

## Autor

**Fulvio Cezar Canducci Dias**

- Email: fulviocanducci@hotmail.com
- GitHub: [@fulviocanducci](https://github.com/fulviocanducci)

## Changelog

### v1.0.1

- Implementação do Facade para Laravel.
- Mudança na classe da Validação para Cnpj Alpha/Numeric.

### v1.0.0

- Versão estável com suporte completo ao Laravel.

### v0.0.4

- Versão Beta para Facade do Laravel

### v0.0.3

- Implementação de serialização JSON nas classes Company, Phone e Shareholder
- Adicionado método mágico `__get` para acesso direto às propriedades privadas
- Suporte a conversão para array com `toArray()`

### v0.0.2

- Adicionado suporte ao Laravel com service provider e auto-discovery

### v0.0.1

- Substituição de `WebRequest` por `CnpjService` com interface `ICnpjService`
- Adicionada validação automática de CNPJ com classe `Valid`
- Melhorias na arquitetura e organização do código
- Atualização para PHP 8.1+
- Versão inicial
- Consulta de CNPJ via API OpenCNPJ
- Modelos estruturados para Company, Phone e Shareholder
- Tratamento robusto de erros
- Suporte a PHP 8.0+

---

⭐ Se este projeto foi útil para você, considere dar uma estrela no GitHub!
