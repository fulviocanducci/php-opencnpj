# PHP OpenCNPJ

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT) [![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.1-blue.svg)](https://php.net) [![CI & Release](https://github.com/fulviocanducci/php-opencnpj/actions/workflows/ci.yml/badge.svg)](https://github.com/fulviocanducci/php-opencnpj/actions/workflows/ci.yml)

Uma biblioteca PHP simples e eficiente para consultar informações de empresas brasileiras através do CNPJ utilizando a API do OpenCNPJ.

Referente ao Projeto [https://opencnpj.org/](https://opencnpj.org/)

## Características

- ✅ Consulta dados completos de empresas pelo CNPJ
- ✅ Validação automática de CNPJ antes da consulta
- ✅ Informações detalhadas incluindo sócios, telefones e endereço
- ✅ Tratamento robusto de erros
- ✅ Modelos estruturados com encapsulamento adequado
- ✅ Interface bem definida com `ICnpjService`
- ✅ Compatível com PHP 8.1+
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

use Canducci\OpenCnpj\CnpjService;

// Criar uma instância do CnpjService
$cnpjService = CnpjService::create();

// Consultar CNPJ (com ou sem formatação)
$response = $cnpjService->getCnpj('11.222.333/0001-81');
// ou
$response = $cnpjService->getCnpj('11222333000181');

// Verificar se a consulta foi bem-sucedida
if ($response->isValid()) {
    $company = $response->getCompany();
    
    echo "Razão Social: " . $company->getLegalName() . "\n";
    echo "Nome Fantasia: " . $company->getTradeName() . "\n";
    echo "CNPJ: " . $company->getCnpj() . "\n";
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

A classe `Company` contém todas as informações da empresa com métodos getters para acesso aos dados:

```php
$company->getCnpj()                    // CNPJ da empresa
$company->getLegalName()               // Razão social
$company->getTradeName()               // Nome fantasia
$company->getStatus()                  // Situação cadastral
$company->getStatusDate()              // Data da situação cadastral
$company->getHeadOffice()              // Matriz/Filial
$company->getStartDate()               // Data de início da atividade
$company->getMainCnae()                // CNAE principal
$company->getSecondaryCnaes()          // Array de CNAEs secundários
$company->getSecondaryCnaesCount()     // Quantidade de CNAEs secundários
$company->getLegalNature()             // Natureza jurídica
$company->getAddress()                 // Logradouro
$company->getNumber()                  // Número
$company->getComplement()              // Complemento
$company->getNeighborhood()            // Bairro
$company->getZip()                     // CEP
$company->getState()                   // UF
$company->getCity()                    // Município
$company->getEmail()                   // E-mail
$company->getPhones()                  // Array de telefones (Phone[])
$company->getCapital()                 // Capital social
$company->getCompanySize()             // Porte da empresa
$company->getSimplesOption()           // Opção pelo Simples
$company->getSimplesDate()             // Data da opção pelo Simples
$company->getMeiOption()               // Opção pelo MEI
$company->getMeiDate()                 // Data da opção pelo MEI
$company->getShareholders()            // Array de sócios (Shareholder[])
```

### Phone (Telefone)

```php
$phone->getDdd()        // Código DDD
$phone->getNumber()     // Número do telefone
$phone->isFax()         // Se é fax (boolean)
```

### Shareholder (Sócio)

```php
$shareholder->getName()           // Nome do sócio
$shareholder->getCpfOrCnpj()      // CPF ou CNPJ do sócio
$shareholder->getQualification()  // Qualificação do sócio
$shareholder->getEntryDate()      // Data de entrada na sociedade
$shareholder->getType()           // Identificador do sócio
$shareholder->getAgeRange()       // Faixa etária
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
$response = $cnpjService->getCnpj('11222333000181'); // Valida automaticamente
```

## Tratamento de Erros

A biblioteca utiliza a classe `ApiResponse` para encapsular tanto sucessos quanto erros:

```php
$response = $cnpjService->getCnpj('11222333000181');

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
    $response = $cnpjService->getCnpj($cnpj);
    
    if (!$response->isValid()) {
        echo "❌ Erro ao consultar CNPJ: " . $response->getException()->getMessage() . "\n";
        return;
    }
    
    $company = $response->getCompany();
    
    echo "🏢 INFORMAÇÕES DA EMPRESA\n";
    echo "========================\n";
    echo "CNPJ: {$company->getCnpj()}\n";
    echo "Razão Social: {$company->getLegalName()}\n";
    echo "Nome Fantasia: {$company->getTradeName()}\n";
    echo "Status: {$company->getStatus()}\n";
    echo "Porte: {$company->getCompanySize()}\n";
    echo "Capital Social: {$company->getCapital()}\n";
    
    echo "\n📍 ENDEREÇO\n";
    echo "===========\n";
    echo "{$company->getAddress()}, {$company->getNumber()}\n";
    if (!empty($company->getComplement())) {
        echo "Complemento: {$company->getComplement()}\n";
    }
    echo "{$company->getNeighborhood()}\n";
    echo "{$company->getCity()} - {$company->getState()}\n";
    echo "CEP: {$company->getZip()}\n";
    
    if (!empty($company->getPhones())) {
        echo "\n📞 TELEFONES\n";
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

### v2.0.0
- **BREAKING CHANGES**: Encapsulamento de propriedades com métodos getters
- Substituição de `WebRequest` por `CnpjService` com interface `ICnpjService`
- Adicionada validação automática de CNPJ com classe `Valid`
- Melhorias na arquitetura e organização do código
- Atualização para PHP 8.1+

### v1.0.0
- Versão inicial
- Consulta de CNPJ via API OpenCNPJ
- Modelos estruturados para Company, Phone e Shareholder
- Tratamento robusto de erros
- Suporte a PHP 8.0+

---

⭐ Se este projeto foi útil para você, considere dar uma estrela no GitHub!