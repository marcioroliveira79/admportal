<?php
session_start();

// Verifica se o usuário está logado e se a variável $acao está definida
if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && isset($acao) && $acao != null) {
    
    // Verifica se o usuário tem acesso à tela
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {

        // Inicialização de variáveis e parâmetros de filtro
        $mensagem = '';
        $erro_banco = '';
        $ambiente_selecionado = isset($_GET['ambiente']) ? trim($_GET['ambiente']) : '';
        $atributo_busca       = isset($_GET['atributo']) ? trim($_GET['atributo']) : '';

        // Consulta para carregar os ambientes distintos (para o combo)
        $query_ambiente = "SELECT DISTINCT ambiente FROM administracao.catalog_table_content ORDER BY ambiente";
        $result_ambiente = pg_query($conexao, $query_ambiente);
        if (!$result_ambiente) {
            $erro_banco = pg_last_error($conexao);
        }
        $ambientes = pg_fetch_all($result_ambiente);

        // Variável para armazenar os dados do gráfico
        $dashboard_data = [];
        if (!empty($ambiente_selecionado) && !empty($atributo_busca)) {
            // Query para o gráfico
            $query_dashboard = "
                SELECT service_name, data_type, COUNT(*) AS count,
                       COUNT(*) * 100.0 / SUM(COUNT(*)) OVER (PARTITION BY service_name) AS percentage
                FROM administracao.catalog_table_content
                WHERE ambiente = $1
                  AND column_name ILIKE '%' || $2 || '%'
                GROUP BY service_name, data_type
                ORDER BY service_name, data_type;
            ";
            $result_dashboard = pg_query_params($conexao, $query_dashboard, [$ambiente_selecionado, $atributo_busca]);
            if ($result_dashboard) {
                $dashboard_data = pg_fetch_all($result_dashboard);
            } else {
                $mensagem = "Erro ao carregar os dados do dashboard: " . pg_last_error($conexao);
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Dashboard de Atributos</title>
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <!-- Chart.js -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <!-- jQuery -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <!-- DataTables CSS e JS -->
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"/>
            <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
            <script>
                if (window.top === window.self) {
                    // Se a página não estiver sendo exibida dentro de um iframe, redireciona para o index
                    window.location.href = 'index.php';
                }
            </script>
            <style>
                body {
                    background-color: #f5f5f5;
                    font-family: Arial, sans-serif;
                }
                .form-container, .chart-container, .table-container {
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    padding: 20px;
                    margin: 40px auto;
                    max-width: 900px;
                }
                .form-title {
                    text-align: center;
                    margin-bottom: 20px;
                    font-weight: bold;
                    color: #4a4a4a;
                }
                /* Container da tabela com espaço extra para a rolagem */
                #attributeListContainer {
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                    margin: 40px auto;
                    margin-bottom: 40px; /* Espaço extra abaixo do container */
                    max-width: 1700px;
                    overflow-x: auto;
                    padding-bottom: 20px; /* Espaço extra para que o total não fique colado à barra */
                }
                #attributeListContainer h4 {
                    text-align: center;
                    font-weight: bold;
                    margin-bottom: 20px;
                }
                #totalResults {
                    font-size: 16px;
                    margin-top: 10px;
                    margin-bottom: 10px;
                    text-align: right;
                }
                #attributesTable {
                    width: 100%;
                    border-collapse: collapse;
                }
                #attributesTable th, #attributesTable td {
                    padding: 10px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                #attributesTable th {
                    background-color: #f8f9fa;
                    font-weight: bold;
                }
                #attributesTable tr:hover {
                    background-color: #f1f1f1;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <?php if (!empty($mensagem)): ?>
                    <div class="alert alert-info text-center">
                        <?= htmlspecialchars($mensagem) ?>
                        <?php if (!empty($erro_banco)): ?>
                            <br><small class="text-danger"><?= htmlspecialchars($erro_banco) ?></small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Formulário de Filtros -->
                <div class="form-container">
                    <div class="form-title"><h3>Dashboard de Atributos</h3></div>
                    <form method="GET" class="row g-3">
                        <!-- Preserva a ação -->
                        <input type="hidden" name="acao" value="<?= htmlspecialchars($acao) ?>">
                        <div class="col-md-6">
                            <label for="ambiente" class="form-label">Ambiente</label>
                            <select name="ambiente" id="ambiente" class="form-select" required>
                                <option value="">Selecione um Ambiente</option>
                                <?php if ($ambientes): ?>
                                    <?php foreach ($ambientes as $amb): ?>
                                        <option value="<?= htmlspecialchars($amb['ambiente']) ?>" <?= ($ambiente_selecionado == $amb['ambiente']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($amb['ambiente']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="atributo" class="form-label">Atributo</label>
                            <input type="text" name="atributo" id="atributo" class="form-control" value="<?= htmlspecialchars($atributo_busca) ?>" placeholder="Ex.: CNPJ" required>
                        </div>
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-success">Filtrar</button>
                        </div>
                    </form>
                </div>

                <?php if (!empty($dashboard_data)): ?>
                    <!-- Gráfico -->
                    <div class="chart-container mt-4">
                        <canvas id="dashboardChart"></canvas>
                    </div>
                    <script>
                        // Dados do dashboard vindos do PHP
                        const dashboardData = <?= json_encode($dashboard_data) ?>;
                        
                        // Cria um array de labels com os service_names (únicos)
                        const labels = [...new Set(dashboardData.map(item => item.service_name))];
                        
                        // Cria os datasets para cada data_type encontrado
                        const datasets = [...new Set(dashboardData.map(item => item.data_type))].map(type => ({
                            label: type,
                            data: labels.map(label => {
                                const item = dashboardData.find(i => i.service_name === label && i.data_type === type);
                                return item ? parseInt(item.count) : 0;
                            }),
                            backgroundColor: '#' + Math.floor(Math.random() * 16777215).toString(16)
                        }));
                        
                        // Inicializa o gráfico
                        const ctx = document.getElementById('dashboardChart').getContext('2d');
                        const dashboardChart = new Chart(ctx, {
                            type: 'bar',
                            data: { labels, datasets },
                            options: { 
                                responsive: true,
                                scales: { y: { beginAtZero: true } },
                                // Evento de clique para atualizar a lista
                                onClick: (evt, activeElements) => {
                                    if (activeElements.length > 0) {
                                        // Obtém o primeiro elemento clicado
                                        const firstPoint = activeElements[0];
                                        const serviceName = dashboardChart.data.labels[firstPoint.index];
                                        const dataType = dashboardChart.data.datasets[firstPoint.datasetIndex].label;
                                        
                                        // Obtém os valores dos filtros
                                        const ambiente = document.getElementById('ambiente').value;
                                        const atributo = document.getElementById('atributo').value;
                                        
                                        // Chamada AJAX para buscar os detalhes
                                        $.ajax({
                                            url: 'catalogo/catalogo_dsb_tabela_ajax.php', // Ajuste o caminho se necessário
                                            method: 'GET',
                                            data: {
                                                ambiente: ambiente,
                                                service_name: serviceName,
                                                data_type: dataType,
                                                atributo: atributo
                                            },
                                            dataType: 'json',
                                            success: function(response) {
                                                // Se o DataTable já estiver inicializado, destrói-o
                                                if ($.fn.DataTable.isDataTable('#attributesTable')) {
                                                    $('#attributesTable').DataTable().destroy();
                                                }
                                                // Limpa o corpo da tabela
                                                $('#attributesTable tbody').empty();
                                                
                                                // Preenche a tabela com os novos dados
                                                if (response.data && response.data.length > 0) {
                                                    $.each(response.data, function(index, item) {
                                                        const row = `<tr>
                                                            <td>${item.ambiente}</td>
                                                            <td>${item.service_name}</td>
                                                            <td>${item.schema_name}</td>
                                                            <td>${item.table_name}</td>
                                                            <td>${item.column_name}</td>
                                                            <td>${item.data_type}</td>
                                                            <td>${item.data_length}</td>
                                                        </tr>`;
                                                        $('#attributesTable tbody').append(row);
                                                    });
                                                    $('#totalResults').html('Total de resultados: ' + response.total);
                                                } else {
                                                    $('#attributesTable tbody').html('<tr><td colspan="7" class="text-center">Nenhum dado encontrado.</td></tr>');
                                                    $('#totalResults').html('Total de resultados: 0');
                                                }
                                                // Re-inicializa o DataTable
                                                $('#attributesTable').DataTable();
                                                
                                                // Exibe o container da tabela (caso esteja oculto)
                                                $('#attributeListContainer').show();
                                            },
                                            error: function(xhr, status, error) {
                                                alert('Erro ao carregar os dados: ' + error);
                                            }
                                        });
                                    }
                                }
                            }
                        });
                    </script>
                <?php endif; ?>

                <!-- Lista de Atributos (inicialmente oculta) -->
                <div id="attributeListContainer" class="table-container mt-4" style="display:none;">
                    <h4>Lista de Atributos</h4>
                    <table id="attributesTable" class="display">
                        <thead>
                            <tr>
                                <th>AMBIENTE</th>
                                <th>SERVICE_NAME</th>
                                <th>SCHEMA_NAME</th>
                                <th>TABLE_NAME</th>
                                <th>COLUMN_NAME</th>
                                <th>DATA_TYPE</th>
                                <th>DATA_LENGTH</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div id="totalResults"></div>
                </div>
            </div>            
        </body>
        <br>
        <br>
        <br>
        <br>
        </html>
        <?php
    } elseif ($acesso != "TELA AUTORIZADA") {
        @include("html/403.html");
    }
} else {
    header("Location: login.php");
}
?>
