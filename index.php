<!-- Elaborado por Levi Lucena - https://www.linkedin.com/in/levilucena/ -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Search</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <!-- Biblioteca Tagify -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.10.0/tagify.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.10.0/tagify.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- Bootstrap Datepicker Português JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.pt-BR.min.js"></script>

    <style>
        body {
            padding-top: 20px;
        }

        .panel-heading {
            font-weight: bold;
        }

        .pagination {
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div style="text-align: center;">
                    <img style="height: 120px; width: 220px;" src="logo2.png">
                    <p><br>
                </div>
                <!-- PHP e Formulários -->
                <?php
                // Função para gerar dados fictícios
                function generateData($num = 100)
                {
                    $clients = ["Prodam", "Caixa Econômica Federal", "Secretaria Municipal da Educação", "Cencosud Brasil Comercial LTDA", "Prodesp", "Companhia de Saneamento Básico", "Banco do Brasil", "Petrobras"];
                    $companies = ["Stefanini Consultoria", "Stefanini Cyber", "Orbitall Payments", "Necxt Stefanini"];
                    $types = ["Aplicações", "BPO", "Cloud", "DPO", "Gestão", "HaaS", "IaaS", "ITO", "Outros", "PaaS", "PMO", "Produtos", "SaaS", "SAP", "Transformação Digital", "RPA"];
                    $data = [];
                    for ($i = 0; $i < $num; $i++) {
                        $client = $clients[array_rand($clients)];
                        $company = $companies[array_rand($companies)];
                        $type = $types[array_rand($types)];
                        $code = rand(100, 999);
                        $order = rand(1, 100);
                        $date = date("d/m/Y", strtotime("-" . rand(1, 365) . " days"));
                        $vigencia_inicio = date("d/m/Y", strtotime("-" . rand(1, 180) . " days"));
                        $vigencia_fim = date("d/m/Y", strtotime($vigencia_inicio . " + " . rand(1, 180) . " days"));
                        $contract_number = rand(1000, 9999) . '-' . rand(10000, 99999);  // Geração aleatória do número de contrato
                        $content = "Conteúdo do documento " . ($i + 1);
                        $data[] = [
                            "client" => $client,
                            "code" => $code,
                            "order" => $order,
                            "company" => $company,
                            "type" => $type,
                            "date" => $date,
                            "vigencia_inicio" => $vigencia_inicio,
                            "vigencia_fim" => $vigencia_fim,
                            "contract_number" => $contract_number,  // Adicionando o número de contrato ao array
                            "content" => $content
                        ];
                    }
                    return $data;
                }

                // Gerar dados fictícios
                $documents = generateData(100);
                ?>
                <form method="GET" action="">
                    <div class="form-group">
                        <label for="search">Refine sua Busca:</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Buscar">
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo de Serviços:</label>
                        <select class="form-control" id="tipo" name="tipo">
                            <option value="">Todos</option>
                            <option value="Aplicações">Aplicações</option>
                            <option value="BPO">BPO</option>
                            <option value="Cloud">Cloud</option>
                            <option value="DPO">DPO</option>
                            <option value="Gestão">Gestão</option>
                            <option value="HaaS">HaaS</option>
                            <option value="IaaS">IaaS</option>
                            <option value="ITO">ITO</option>
                            <option value="Outros">Outros</option>
                            <option value="PaaS">PaaS</option>
                            <option value="PMO">PMO</option>
                            <option value="Produtos">Produtos</option>
                            <option value="SaaS">SaaS</option>
                            <option value="SAP">SAP</option>
                            <option value="Transformação Digital">Transformação Digital</option>
                            <option value="RPA">RPA</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cliente">Cliente:</label>
                        <select class="form-control" id="cliente" name="cliente">
                            <option value="">Todos</option>
                            <?php
                            // Gerar a lista de clientes dinamicamente
                            $clientsList = array_unique(array_column($documents, 'client'));
                            foreach ($clientsList as $client) {
                                echo '<option value="' . $client . '">' . $client . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="vigenciaInicio">Vigência Contratual:</label>
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" class="form-control" id="vigenciaInicio" name="vigenciaInicio" placeholder="Data Início">
                            <span class="input-group-addon">a</span>
                            <input type="text" class="form-control" id="vigenciaFim" name="vigenciaFim" placeholder="Data Fim">
                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {

                            var input = document.querySelector('#search');
                            var tagify = new Tagify(input);

                            // Opcional: configuração do Tagify, como o delimitador de tags
                            tagify.settings.delimiters = " ,";
                            tagify.settings.keepInvalidTags = true;

                            // Inicializa o Datepicker
                            $('#datepicker').datepicker({
                                format: 'dd/mm/yyyy',
                                startDate: '-100y',
                                endDate: '+100y',
                                autoclose: true,
                                todayHighlight: true,
                                orientation: 'bottom',
                                language: 'pt-BR' // Configura o idioma para português
                            });
                            $('form').on('submit', function(event) {
                                var vigenciaInicio = $('#vigenciaInicio').val();
                                var vigenciaFim = $('#vigenciaFim').val();

                                if (vigenciaInicio && vigenciaFim) {
                                    var dateInicio = new Date(vigenciaInicio.split('/').reverse().join('-'));
                                    var dateFim = new Date(vigenciaFim.split('/').reverse().join('-'));

                                    if (dateFim < dateInicio) {
                                        alert('A data de fim da vigência não pode ser menor que a data de início.');
                                        event.preventDefault();
                                    }
                                }
                            });
                        });

                        function clearForm() {
                            $('#search').val('');
                            $('#tipo').val('');
                            $('#cliente').val('');
                            $('#vigenciaInicio').val('');
                            $('#vigenciaFim').val('');
                            $('#data').val('');
                            $('#empresa').val('');
                        }
                    </script>

                    <div class="form-group">
                        <label for="data">Data do Atestado:</label>
                        <input type="date" class="form-control" id="data" name="data">
                    </div>
                    <div class="form-group">
                        <label for="empresa">Empresa:</label>
                        <select class="form-control" id="empresa" name="empresa">
                            <option value="">Todas</option>
                            <option value="Stefanini Consultoria">Stefanini Consultoria</option>
                            <option value="Stefanini Cyber">Stefanini Cyber</option>
                            <option value="Necxt Stefanini">Necxt Stefanini</option>
                            <option value="Orbitall Payments">Orbitall Payments</option>
                            <option value="Cyber Smart Defense">Cyber Smart Defense</option>
                            <option value="Safe Way">Safe Way</option>
                            <option value="Stefanini Scala">Stefanini Scala</option>
                            <option value="IHM Engenharia">IHM Engenharia</option>
                            <option value="Stefanini Inspiring">Stefanini Inspiring</option>
                            <option value="Topaz">Topaz</option>
                            <option value="NewM">NewM</option>
                            <option value="Capital Market">Capital Market</option>
                            <option value="Intelligenti">Intelligenti</option>
                            <option value="TecCloud">TecCloud</option>
                            <option value="Gauge">Gauge</option>
                            <option value="Woopi">Woopi</option>
                            <option value="MozaiKo">MozaiKo</option>
                            <option value="Infinit">Infinit</option>
                            <option value="Saque e Pague">Saque e Pague</option>
                            <option value="Dn.ia">Dn.ia</option>
                            <option value="Haus">Haus</option>
                            <option value="LogBank">LogBank</option>
                            <option value="N1IT">N1IT</option>
                            <option value="Huia">Huia</option>
                            <option value="W3haus">W3haus</option>
                            <option value="Caps in-company">Caps in-company</option>
                            <option value="Brooke">Brooke</option>
                            <option value="Singulahr">Singulahr</option>
                            <option value="Now3">Now3</option>
                            <option value="Ecglobal">Ecglobal</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-default">Buscar</button>
                    <button type="button" class="btn btn-warning" onclick="clearForm()">Limpar Campos</button>
                </form>
                <hr>
            </div>
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Resultados</div>
                    <div class="panel-body">
                        <?php
                        $start_time = microtime(true);

                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
                        $cliente = isset($_GET['cliente']) ? $_GET['cliente'] : '';
                        $data = isset($_GET['data']) ? $_GET['data'] : '';
                        $empresa = isset($_GET['empresa']) ? $_GET['empresa'] : '';
                        $vigencia_inicio = isset($_GET['vigenciaInicio']) ? $_GET['vigenciaInicio'] : '';
                        $vigencia_fim = isset($_GET['vigenciaFim']) ? $_GET['vigenciaFim'] : '';

                        // Dividir o texto de busca em palavras separadas por vírgulas
                        $searchTags = array_filter(array_map('trim', explode(',', $search)));

                        // Filtrar resultados com base nas tags, tipo, cliente, data, empresa e vigência
                        $filteredDocuments = array_filter($documents, function ($doc) use ($searchTags, $tipo, $cliente, $data, $empresa, $vigencia_inicio, $vigencia_fim) {
                            // Verificar se algum dos termos de busca aparece no cliente ou no conteúdo
                            $matchesSearch = empty($searchTags) || array_reduce($searchTags, function ($carry, $tag) use ($doc) {
                                return $carry || stripos($doc["client"], $tag) !== false || stripos($doc["content"], $tag) !== false;
                            }, false);

                            $matchesTipo = empty($tipo) || $doc["type"] === $tipo;
                            $matchesCliente = empty($cliente) || $doc["client"] === $cliente;
                            $matchesData = empty($data) || $doc["date"] === $data;
                            $matchesEmpresa = empty($empresa) || $doc["company"] === $empresa;

                            // Adicionar filtro de vigência
                            $matchesVigencia = true;

                            $vigenciaInicioTimestamp = $vigencia_inicio ? strtotime(str_replace('/', '-', $vigencia_inicio)) : null;
                            $vigenciaFimTimestamp = $vigencia_fim ? strtotime(str_replace('/', '-', $vigencia_fim)) : null;
                            $docVigenciaInicioTimestamp = strtotime($doc["vigencia_inicio"]);
                            $docVigenciaFimTimestamp = strtotime($doc["vigencia_fim"]);

                            if ($vigenciaInicioTimestamp !== null) {
                                $matchesVigencia = $docVigenciaInicioTimestamp >= $vigenciaInicioTimestamp;
                            }
                            if ($vigenciaFimTimestamp !== null) {
                                $matchesVigencia = $matchesVigencia && $docVigenciaFimTimestamp <= $vigenciaFimTimestamp;
                            }

                            return $matchesSearch && $matchesTipo && $matchesCliente && $matchesData && $matchesEmpresa && $matchesVigencia;
                        });

                        // Configurar paginação
                        $perPage = 10; // Número de itens por página
                        $totalDocuments = count($filteredDocuments);
                        $totalPages = ceil($totalDocuments / $perPage);
                        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $currentPage = max(1, min($totalPages, $currentPage));
                        $start = ($currentPage - 1) * $perPage;
                        $pagedDocuments = array_slice($filteredDocuments, $start, $perPage);

                        $end_time = microtime(true);
                        $execution_time = round(($end_time - $start_time) * 1000);

                        echo '<p>Exibindo todos os resultados indexados</p>';
                        echo '<p>Resultado(s) 1 - ' . count($pagedDocuments) . ' de ' . count($documents) . ' (em ' . $execution_time . ' milisegundos)</p>';

                        if (count($pagedDocuments) > 0) {
                            echo '<ul class="list-group">';
                            foreach ($pagedDocuments as $doc) {
                                echo '<li class="list-group-item">';
                                echo '<strong>Cliente:</strong> ' . $doc["client"] . '<br>';
                                echo '<strong>Código:</strong> ' . $doc["code"] . '<br>';
                                echo '<strong>Ordem:</strong> ' . $doc["order"] . '<br>';
                                echo '<strong>Empresa:</strong> ' . $doc["company"] . '<br>';
                                echo '<strong>Contrato:</strong> ' . $doc["contract_number"] . '<br>';  // Exibindo o número de contrato
                                echo '<strong>Tipo:</strong> ' . $doc["type"] . '<br>';
                                echo '<strong>Vigência Contratual:</strong> ' . $doc["vigencia_inicio"] . ' a ' . $doc["vigencia_fim"] . '<br>';
                                echo '<strong>Data do Atestado:</strong> ' . $doc["date"] . '<br>';
                                echo '<strong>Conteúdo:</strong> ' . $doc["content"] . '<br>';
                                echo '<button class="btn btn-primary">Visualizar Documento</button> ';
                                echo '<button class="btn btn-success">Texto</button> ';
                                echo '<button class="btn btn-info">Download</button>';
                                echo '</li>';
                            }
                            echo '</ul>';

                            // Controles de paginação
                            echo '<nav aria-label="Page navigation">';
                            echo '<ul class="pagination">';
                            if ($currentPage > 1) {
                                echo '<li><a href="?search=' . $search . '&tipo=' . $tipo . '&cliente=' . $cliente . '&data=' . $data . '&empresa=' . $empresa . '&vigenciaInicio=' . $vigencia_inicio . '&vigenciaFim=' . $vigencia_fim . '&page=1">&laquo;&laquo;</a></li>';
                                echo '<li><a href="?search=' . $search . '&tipo=' . $tipo . '&cliente=' . $cliente . '&data=' . $data . '&empresa=' . $empresa . '&vigenciaInicio=' . $vigencia_inicio . '&vigenciaFim=' . $vigencia_fim . '&page=' . ($currentPage - 1) . '">&laquo;</a></li>';
                            } else {
                                echo '<li class="disabled"><span>&laquo;&laquo;</span></li>';
                                echo '<li class="disabled"><span>&laquo;</span></li>';
                            }
                            for ($i = 1; $i <= $totalPages; $i++) {
                                if ($i == $currentPage) {
                                    echo '<li class="active"><span>' . $i . '</span></li>';
                                } else {
                                    echo '<li><a href="?search=' . $search . '&tipo=' . $tipo . '&cliente=' . $cliente . '&data=' . $data . '&empresa=' . $empresa . '&vigenciaInicio=' . $vigencia_inicio . '&vigenciaFim=' . $vigencia_fim . '&page=' . $i . '">' . $i . '</a></li>';
                                }
                            }
                            if ($currentPage < $totalPages) {
                                echo '<li><a href="?search=' . $search . '&tipo=' . $tipo . '&cliente=' . $cliente . '&data=' . $data . '&empresa=' . $empresa . '&vigenciaInicio=' . $vigencia_inicio . '&vigenciaFim=' . $vigencia_fim . '&page=' . ($currentPage + 1) . '">&raquo;</a></li>';
                                echo '<li><a href="?search=' . $search . '&tipo=' . $tipo . '&cliente=' . $cliente . '&data=' . $data . '&empresa=' . $empresa . '&vigenciaInicio=' . $vigencia_inicio . '&vigenciaFim=' . $vigencia_fim . '&page=' . $totalPages . '">&raquo;&raquo;</a></li>';
                            } else {
                                echo '<li class="disabled"><span>&raquo;</span></li>';
                                echo '<li class="disabled"><span>&raquo;&raquo;</span></li>';
                            }
                            echo '</ul>';
                            echo '</nav>';
                        } else {
                            echo "0 results";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>