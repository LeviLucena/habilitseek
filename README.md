# Implementação de um Sistema de Busca e Visualização de Documentos com PHP e Bootstrap
<p align="center">
<img width="600" alt="Screenshot_9" src="https://github.com/user-attachments/assets/c444313d-a172-446e-8e4a-25e74a44c160">
<p>

## Visão Geral
Este projeto é uma aplicação web desenvolvida em PHP, utilizando Bootstrap para o design responsivo e algumas bibliotecas JavaScript para melhorar a interatividade. A aplicação simula um sistema de busca e visualização de documentos contratuais, gerando dados fictícios para exibir resultados e permitir a filtragem por vários critérios.

## Funcionalidades
- **Busca Avançada:** Permite a pesquisa de documentos por termos específicos, tipo de serviço, cliente, data do documento, empresa, e vigência contratual.
- **Paginação dos Resultados:** Exibe os resultados de forma paginada, facilitando a navegação em grandes quantidades de dados.
- **Validação de Datas:** Inclui validação das datas de vigência para garantir que a data final seja posterior à data inicial.
- **Exibição de Detalhes dos Documentos:** Cada documento é exibido com detalhes como cliente, código do contrato, ordem, empresa, tipo de serviço, data do atestado, vigência contratual, e conteúdo.

## Tecnologias Utilizadas
- **PHP:** Linguagem de programação usada no backend para gerar dados fictícios e processar as buscas.
- **Bootstrap:** Framework CSS utilizado para estilizar a interface da aplicação e torná-la responsiva.
- **JavaScript/jQuery:** Usado para manipulação do DOM, inicialização de plugins e validação de formulários.
- **Tagify:** Biblioteca JavaScript para criar campos de entrada com tags.
- **Bootstrap Datepicker:** Plugin de calendário para facilitar a seleção de datas.
- **HTML/CSS:** Marcação e estilização da interface do usuário.

## Bibliotecas e Dependências
- **Bootstrap:** Utilizado para a construção do layout e componentes da interface.
- **Tagify:** Utilizado para transformar campos de entrada em campos de tags, facilitando a busca por múltiplos termos.
- **Bootstrap Datepicker:** Um plugin para adicionar seletores de data ao formulário.
- **jQuery:** Utilizado para manipulação do DOM e inicialização de plugins.

# Implementação Passo a Passo
**1. Estrutura HTML**<br>
A estrutura básica do HTML inclui a integração com Bootstrap e a configuração do formulário de busca com campos para filtrar os resultados de acordo com os critérios desejados. Também foram adicionadas bibliotecas para Tagify e Datepicker.
```html
<!-- Elaborado por Levi Lucena - https://www.linkedin.com/in/levilucena/ -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Search</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.10.0/tagify.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.10.0/tagify.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.pt-BR.min.js"></script>
</head>
<body>
    <!-- Conteúdo da página -->
</body>
</html>

```

**2. Gerar Dados Fictícios**<br>
No backend, foi criada uma função para gerar dados fictícios de contratos. Esses dados são gerados dinamicamente e incluem informações como cliente, empresa, tipo de serviço, data do atestado, vigência contratual e número de contrato.
```php
<?php
function generateData($num = 100) {
    $clients = ["Prodam","Caixa Econômica Federal", ...];
    $companies = ["Stefanini Consultoria", "Stefanini Cyber", ...];
    $types = ["Aplicações", "BPO", "Cloud", ...];
    $data = [];
    for ($i = 0; $i < $num; $i++) {
        $client = $clients[array_rand($clients)];
        $company = $companies[array_rand($companies)];
        $type = $types[array_rand($types)];
        $code = rand(100, 999);
        $order = rand(1, 100);
        $contractNumber = strtoupper(substr($company, 0, 3)) . '-' . rand(10000, 99999);
        $date = date("d/m/Y", strtotime("-" . rand(1, 365) . " days"));
        $vigencia_inicio = date("d/m/Y", strtotime("-" . rand(1, 180) . " days"));
        $vigencia_fim = date("d/m/Y", strtotime($vigencia_inicio . " + " . rand(1, 180) . " days"));
        $content = "Conteúdo do documento " . ($i + 1);
        $data[] = [
            "client" => $client,
            "code" => $code,
            "order" => $order,
            "contractNumber" => $contractNumber,
            "company" => $company,
            "type" => $type,
            "date" => $date,
            "vigencia_inicio" => $vigencia_inicio,
            "vigencia_fim" => $vigencia_fim,
            "content" => $content
        ];
    }
    return $data;
}
$documents = generateData(100);
?>
```

**3. Implementar a Lógica de Busca e Filtros**<br>
A lógica de busca filtra os documentos com base nos critérios selecionados pelo usuário e os exibe de forma paginada.
```php
$search = isset($_GET['search']) ? $_GET['search'] : '';
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$cliente = isset($_GET['cliente']) ? $_GET['cliente'] : '';
$data = isset($_GET['data']) ? $_GET['data'] : '';
$empresa = isset($_GET['empresa']) ? $_GET['empresa'] : '';
$vigencia_inicio = isset($_GET['vigenciaInicio']) ? $_GET['vigenciaInicio'] : '';
$vigencia_fim = isset($_GET['vigenciaFim']) ? $_GET['vigenciaFim'] : '';

$filteredDocuments = array_filter($documents, function($doc) use ($searchTags, $tipo, $cliente, $data, $empresa, $vigencia_inicio, $vigencia_fim) {
    ...
});
```

**4. Exibir Resultados com Paginação**
Os resultados filtrados são exibidos em uma lista com paginação, permitindo ao usuário navegar por diferentes páginas de resultados.
```php
if (count($pagedDocuments) > 0) {
    echo '<ul class="list-group">';
    foreach ($pagedDocuments as $doc) {
        echo '<li class="list-group-item">';
        echo '<strong>Cliente:</strong> ' . $doc["client"] . '<br>';
        echo '<strong>Código:</strong> ' . $doc["code"] . '<br>';
        echo '<strong>Número do Contrato:</strong> ' . $doc["contractNumber"] . '<br>';
        echo '<strong>Empresa:</strong> ' . $doc["company"] . '<br>';
        ...
    }
    echo '</ul>';
    echo '<nav aria-label="Page navigation">';
    ...
    echo '</nav>';
} else {
    echo "0 results";
}
?>
```

**Conclusão**<br>
Este projeto demonstra como combinar PHP com Bootstrap e algumas bibliotecas JavaScript para criar uma aplicação web funcional e interativa. A aplicação pode ser facilmente adaptada para lidar com dados reais e ser integrada a um sistema mais complexo de gestão de documentos. As funcionalidades de busca avançada e paginação tornam a aplicação escalável e eficiente, proporcionando uma ótima experiência de usuário. 
> [!NOTE]
> Código completo se encontra nos arquivos.

## Contribuições
Contribuições são bem-vindas! Se você tiver ideias de melhorias, correções de bugs ou novas funcionalidades, sinta-se à vontade para abrir uma nova issue ou enviar um pull request.
Licença. Este projeto é licenciado sob a licença MIT. Você é livre para usar, modificar e distribuir o código conforme necessário.

## Licença
Este projeto está licenciado sob a [Licença MIT](LICENSE).

## Autor: [![Linkedin Badge](https://img.shields.io/badge/-LinkedIn-blue?style=flat-square&logo=Linkedin&logoColor=white&link=https://www.linkedin.com/in/levilucena/)](https://www.linkedin.com/in/levilucena/)
