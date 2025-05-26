# Documentação BuilderHttpClientService

## Introdução

O `BuilderHttpClientService` é uma classe PHP que fornece uma interface simples e poderosa para fazer requisições HTTP. Ela é especialmente útil para integrações com APIs REST, oferecendo suporte para diferentes métodos HTTP, autenticação, cabeçalhos personalizados e manipulação de respostas JSON.

## Características Principais

- Suporte para métodos HTTP: GET, POST, PUT
- Envio automático de dados em formato JSON
- Suporte para autenticação via cabeçalho Authorization
- Cabeçalhos HTTP personalizáveis
- Configurações CURL personalizáveis
- Tratamento automático de erros
- Parsing automático de respostas JSON

## Configurações Padrão

A classe utiliza as seguintes configurações padrão para as requisições:

- Timeout de conexão: 10 segundos
- Verificação SSL: Desativada
- Retorno como string: Ativado
- Content-Type: application/json (para POST e PUT)

## Métodos Disponíveis

### GET

```php
BuilderHttpClientService::get($url, $params = [], $authorization = null, $customHeaders = [], $customOptions = [])
```

**Exemplo de uso:**
```php
// Requisição GET simples
$response = BuilderHttpClientService::get('https://api.exemplo.com/usuarios');

// GET com parâmetros de query
$response = BuilderHttpClientService::get('https://api.exemplo.com/usuarios', [
    'pagina' => 1,
    'limite' => 10
]);

// GET com autenticação
$response = BuilderHttpClientService::get('https://api.exemplo.com/usuarios', [], 'Bearer token123');

// GET com cabeçalhos personalizados
$response = BuilderHttpClientService::get('https://api.exemplo.com/usuarios', [], null, [
    'X-Custom-Header' => 'valor'
]);
```

### POST

```php
BuilderHttpClientService::post($url, $params = [], $authorization = null, $customHeaders = [], $customOptions = [], $jsonPayload = true)
```

**Exemplo de uso:**
```php
// POST simples com JSON
$response = BuilderHttpClientService::post('https://api.exemplo.com/usuarios', [
    'nome' => 'João Silva',
    'email' => 'joao@exemplo.com'
]);

// POST com autenticação
$response = BuilderHttpClientService::post('https://api.exemplo.com/usuarios', [
    'nome' => 'Maria Santos',
    'email' => 'maria@exemplo.com'
], 'Bearer token123');

// POST com form-data (não JSON)
$response = BuilderHttpClientService::post('https://api.exemplo.com/upload', [
    'arquivo' => '@caminho/do/arquivo.jpg'
], null, [], [], false);
```

### PUT

```php
BuilderHttpClientService::put($url, $params = [], $authorization = null, $customHeaders = [], $customOptions = [], $jsonPayload = true)
```

**Exemplo de uso:**
```php
// PUT simples
$response = BuilderHttpClientService::put('https://api.exemplo.com/usuarios/123', [
    'nome' => 'João Silva Atualizado'
]);

// PUT com autenticação e cabeçalhos
$response = BuilderHttpClientService::put('https://api.exemplo.com/usuarios/123', [
    'status' => 'ativo'
], 'Bearer token123', [
    'X-Custom-Header' => 'valor'
]);
```

## Tratamento de Erros

A classe lança exceções em dois casos principais:

1. Quando ocorre um erro na requisição CURL:
```php
try {
    $response = BuilderHttpClientService::get('https://api.exemplo.com/dados');
} catch (Exception $e) {
    echo "Erro na requisição: " . $e->getMessage();
}
```

2. Quando a API retorna um código de erro HTTP (>= 400):
```php
try {
    $response = BuilderHttpClientService::post('https://api.exemplo.com/usuarios', [
        'email' => 'email_invalido'
    ]);
} catch (Exception $e) {
    echo "Erro HTTP " . $e->getCode() . ": " . $e->getMessage();
}
```

## Exemplos Avançados

### Requisição com Timeout Personalizado

```php
$response = BuilderHttpClientService::get('https://api.exemplo.com/dados', [], null, [], [
    CURLOPT_TIMEOUT => 30 // timeout de 30 segundos
]);
```

### Requisição com Múltiplos Cabeçalhos

```php
$response = BuilderHttpClientService::post('https://api.exemplo.com/dados', [
    'dados' => 'valor'
], 'Bearer token123', [
    'X-Custom-Header' => 'valor',
    'Accept' => 'application/json',
    'Cache-Control' => 'no-cache'
]);
```

### Requisição com Form-Data

```php
$response = BuilderHttpClientService::post('https://api.exemplo.com/upload', [
    'arquivo' => '@caminho/do/arquivo.jpg',
    'tipo' => 'imagem'
], null, [], [], false);
```

## Dicas de Uso

1. **Sempre use try/catch**: A classe lança exceções em caso de erros, então é importante tratar essas exceções.

2. **Verifique o tipo de resposta**: A classe tenta decodificar a resposta como JSON, mas se falhar, retorna a resposta bruta.

3. **Use autenticação quando necessário**: Para APIs que requerem autenticação, sempre forneça o token no parâmetro `$authorization`.

4. **Personalize o timeout**: Para requisições que podem demorar mais, ajuste o timeout usando `$customOptions`.

5. **Cabeçalhos personalizados**: Use o parâmetro `$customHeaders` para adicionar cabeçalhos específicos da sua API.

## Limitações

- Não suporta requisições DELETE (embora o método `request` suporte)
- Timeout padrão de 10 segundos pode ser muito curto para algumas operações
- Verificação SSL desativada por padrão (pode ser ativada via `$customOptions`) 