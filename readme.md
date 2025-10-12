# PHP OpenCNPJ

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.0-blue.svg)](https://php.net)

Uma biblioteca PHP simples e eficiente para consultar informações de empresas brasileiras através do CNPJ utilizando a API do OpenCNPJ.

## Características

- ✅ Consulta dados completos de empresas pelo CNPJ
- ✅ Informações detalhadas incluindo sócios, telefones e endereço
- ✅ Tratamento robusto de erros
- ✅ Modelos estruturados para fácil manipulação dos dados
- ✅ Compatível com PHP 8.0+
- ✅ PSR-4 autoloading

## Instalação

Instale via Composer:

```bash
composer require fulviocanducci/opencnpj
```

## Uso Básico

```php
<?php

require_once 'vendor/autoload.php';

use Canducci\OpenCnpj\WebRequest;

// Criar uma instância do WebRequest
$webRequest = WebRequest::create();

// Consultar CNPJ (com ou sem formatação)
$response = $webRequest->getCnpj('11.222.333/0001-81');
// ou
$response = $webRequest->getCnpj('11222333000181');

// Verificar se a consulta foi bem-sucedida
if ($response->isValid()) {
    $company = $response->getCompany();
    
    echo "Razão Social: " . $company->legalName . "\n";
    echo "Nome Fantasia: " . $company->tradeName . "\n";
    echo "CNPJ: " . $company->cnpj . "\n";
    echo "Status: " . $company->status . "\n";
    echo "Endereço: " . $company->address . ", " . $company->number . "\n";
    echo "Cidade: " . $company->city . " - " . $company->state . "\n";
    echo "CEP: " . $company->zip . "\n";
    
    // Listar telefones
    foreach ($company->phones as $phone) {
        echo "Telefone: (" . $phone->ddd . ") " . $phone->number;
        echo $phone->isFax ? " (Fax)" : "";
        echo "\n";
    }
    
    // Listar sócios
    foreach ($company->shareholders as $shareholder) {
        echo "Sócio: " . $shareholder->name . "\n";
        echo "CPF/CNPJ: " . $shareholder->cpfOrCnpj . "\n";
        echo "Qualificação: " . $shareholder->qualification . "\n";
    }
    
} else {
    $exception = $response->getException();
    echo "Erro: " . $exception->getMessage() . "\n";
}
```

## Estrutura dos Dados

### Company (Empresa)

A classe `Company` contém todas as informações da empresa:

```php
$company->cnpj                    // CNPJ da empresa
$company->legalName              // Razão social
$company->tradeName              // Nome fantasia
$company->status                 // Situação cadastral
$company->statusDate             // Data da situação cadastral
$company->headOffice             // Matriz/Filial
$company->startDate              // Data de início da atividade
$company->mainCnae               // CNAE principal
$company->secondaryCnaes         // Array de CNAEs secundários
$company->secondaryCnaesCount    // Quantidade de CNAEs secundários
$company->legalNature            // Natureza jurídica
$company->address                // Logradouro
$company->number                 // Número
$company->complement             // Complemento
$company->neighborhood           // Bairro
$company->zip                    // CEP
$company->state                  // UF
$company->city                   // Município
$company->email                  // E-mail
$company->phones                 // Array de telefones (Phone[])
$company->capital                // Capital social
$company->companySize            // Porte da empresa
$company->simplesOption          // Opção pelo Simples
$company->simplesDate            // Data da opção pelo Simples
$company->meiOption              // Opção pelo MEI
$company->meiDate                // Data da opção pelo MEI
$company->shareholders           // Array de sócios (Shareholder[])
```

### Phone (Telefone)

```php
$phone->ddd        // Código DDD
$phone->number     // Número do telefone
$phone->isFax      // Se é fax (boolean)
```

### Shareholder (Sócio)

```php
$shareholder->name           // Nome do sócio
$shareholder->cpfOrCnpj      // CPF ou CNPJ do sócio
$shareholder->qualification  // Qualificação do sócio
$shareholder->entryDate      // Data de entrada na sociedade
$shareholder->type           // Identificador do sócio
$shareholder->ageRange       // Faixa etária
```

## Tratamento de Erros

A biblioteca utiliza a classe `ApiResponse` para encapsular tanto sucessos quanto erros:

```php
$response = $webRequest->getCnpj('11222333000181');

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

## Exemplos de Uso

### Exemplo Completo

```php
<?php

require_once 'vendor/autoload.php';

use Canducci\OpenCnpj\WebRequest;

function consultarCNPJ(string $cnpj): void
{
    $webRequest = WebRequest::create();
    $response = $webRequest->getCnpj($cnpj);
    
    if (!$response->isValid()) {
        echo "❌ Erro ao consultar CNPJ: " . $response->getException()->getMessage() . "\n";
        return;
    }
    
    $company = $response->getCompany();
    
    echo "🏢 INFORMAÇÕES DA EMPRESA\n";
    echo "========================\n";
    echo "CNPJ: {$company->cnpj}\n";
    echo "Razão Social: {$company->legalName}\n";
    echo "Nome Fantasia: {$company->tradeName}\n";
    echo "Status: {$company->status}\n";
    echo "Porte: {$company->companySize}\n";
    echo "Capital Social: {$company->capital}\n";
    
    echo "\n📍 ENDEREÇO\n";
    echo "===========\n";
    echo "{$company->address}, {$company->number}\n";
    if (!empty($company->complement)) {
        echo "Complemento: {$company->complement}\n";
    }
    echo "{$company->neighborhood}\n";
    echo "{$company->city} - {$company->state}\n";
    echo "CEP: {$company->zip}\n";
    
    if (!empty($company->phones)) {
        echo "\n📞 TELEFONES\n";
        echo "============\n";
        foreach ($company->phones as $phone) {
            $type = $phone->isFax ? "Fax" : "Telefone";
            echo "{$type}: ({$phone->ddd}) {$phone->number}\n";
        }
    }
    
    if (!empty($company->shareholders)) {
        echo "\n👥 SÓCIOS\n";
        echo "=========\n";
        foreach ($company->shareholders as $shareholder) {
            echo "Nome: {$shareholder->name}\n";
            echo "CPF/CNPJ: {$shareholder->cpfOrCnpj}\n";
            echo "Qualificação: {$shareholder->qualification}\n";
            echo "---\n";
        }
    }
}

// Exemplo de uso
consultarCNPJ('11.222.333/0001-81');
```

## Requisitos

- PHP 8.0 ou superior
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

**Fulvio Canducci**
- Email: fulviocanducci@hotmail.com
- GitHub: [@fulviocanducci](https://github.com/fulviocanducci)

## Changelog

### v1.0.0
- Versão inicial
- Consulta de CNPJ via API OpenCNPJ
- Modelos estruturados para Company, Phone e Shareholder
- Tratamento robusto de erros
- Suporte a PHP 8.0+

---

⭐ Se este projeto foi útil para você, considere dar uma estrela no GitHub!