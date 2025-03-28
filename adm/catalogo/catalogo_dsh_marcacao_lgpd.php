<?php
session_start();

// Recupera a variável $acao se vier via GET
$acao = isset($_GET['acao']) ? $_GET['acao'] : null;

// Exemplo de checagem de sessão e permissão (caso seu sistema utilize):
if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {

    // Verifica se o usuário tem acesso à tela
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {

        // Se quiser filtrar por usuário (param user=ON na URL)
        $filtrarUsuario = (isset($_GET['user']) && $_GET['user'] === 'ON');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard LGPD</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Impede que a página seja aberta fora de um iframe (opcional)
        if (window.top === window.self) {
            window.location.href = 'index.php';
        }

        // Eventos globais AJAX: mostra o overlay quando qualquer requisição iniciar
        // e esconde somente quando todas tiverem terminado.
        $(document).ajaxStart(function(){
            $('#loading').show();
        });
        $(document).ajaxStop(function(){
            $('#loading').hide();
        });
    </script>

    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .form-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 20px;
            margin: 20px auto;
            width: 90%;
        }
        .form-title h3 {
            font-size: 1.2rem;
            font-weight: 500;
            color: #555;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-select.form-select-sm {
            font-size: 12px;
            color: #444;
            background-color: #f8f8f8;
            border: 1px solid #ccc;
        }
        .form-label {
            font-size: 12px;
            color: #666;
        }
        #loading {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(255,255,255,0.7);
            z-index: 9999;
            text-align: center;
            padding-top: 20%;
            font-size: 18px;
            color: #333;
        }
        .card {
            margin-bottom: 10px;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        /* Se quiser ajustar o tamanho do segundo gráfico de pizza:
           .pie-chart-container {
               width: 300px; 
               margin: 0 auto;
           }
        */
    </style>
</head>
<body>
    <!-- Overlay de Loading -->
    <div id="loading">
        <span>Carregando...</span>
    </div>

    <div class="container">

        <!-- Formulário de Filtros -->
        <div class="form-container">
            <div class="form-title"><h3>Dashboard de Atributos LGPD</h3></div>
            <form id="filterForm" method="GET" class="row g-3">
                <?php if ($filtrarUsuario): ?>
                    <input type="hidden" name="user" value="ON">
                <?php endif; ?>

                <div class="col-md-3">
                    <label for="ambiente" class="form-label">Ambiente</label>
                    <select name="ambiente" id="ambiente" class="form-select form-select-sm" required>
                        <option value="">Selecione um Ambiente</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="service_name" class="form-label">Service Name</label>
                    <select name="service_name" id="service_name" class="form-select form-select-sm" required disabled>
                        <option value="">Selecione o Service Name</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="schema_name" class="form-label">Schema</label>
                    <select name="schema_name" id="schema_name" class="form-select form-select-sm" required disabled>
                        <option value="">Selecione o Schema</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="table_name" class="form-label">Tabela</label>
                    <select name="table_name" id="table_name" class="form-select form-select-sm" required disabled>
                        <option value="">Selecione a Tabela</option>
                    </select>
                </div>

                <!-- Botão para limpar filtros -->
                <div class="col-md-12 text-end">
                    <button type="button" id="btnClear" class="btn btn-secondary btn-sm">Limpar Filtros</button>
                </div>
            </form>
        </div>

        <!-- DASHBOARD (Cards) -->
        <div id="dashboardContainer" class="row" style="margin: 20px auto; width:90%;">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-header">Total de Atributos</div>
                    <div class="card-body">
                        <h5 class="card-title" id="dashboardQtdAtributos">0</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-header">Sugestões Sensidata</div>
                    <div class="card-body">
                        <h5 class="card-title" id="dashboardSugestoesSensidata">0</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-header">Tags (LGPD Marcação)</div>
                    <div class="card-body">
                        <h5 class="card-title" id="dashboardTagsTabela">0</h5>
                    </div>
                </div>
            </div>

            <!-- AQUI renomeamos "Atributos Marcados" para "Atributos Anotados" -->
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-header">Atributos Anotados</div>
                    <div class="card-body">
                        <h5 class="card-title" id="dashboardMarcados">0</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRÁFICOS EM CAIXAS -->
        <div class="row" style="margin: 20px auto; width:90%;">
            <!-- Gráfico de Barras (Tags x Marcados) -->
            <!-- Renomeamos para "Tags x Anotados (Barras)" -->
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">Tags x Anotados</div>
                    <div class="card-body">
                        <!-- Container fixo de 300x300 para manter proporção -->
                        <div style="position: relative; height: 300px; width: 300px; margin: 0 auto;">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Segundo Gráfico de Pizza (Atributos x Sugestões) -->
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">Atributos x Sugestões</div>
                    <div class="card-body">
                        <div style="position: relative; height: 300px; width: 300px; margin: 0 auto;">
                            <canvas id="pieChart2"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Primeiro Gráfico de Pizza (Tags x Marcados) -->
            <!-- Renomeamos para "Tags x Anotados (Pizza)" -->
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">Tags x Anotados</div>
                    <div class="card-body">
                        <div style="position: relative; height: 300px; width: 300px; margin: 0 auto;">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.container -->

    <script>
        var urlParams = new URLSearchParams(window.location.search);
        var userParam = urlParams.get('user'); // "ON" ou null

        // Variáveis globais para os gráficos
        let barChart, pieChart, pieChart2;

        $(document).ready(function(){
            // Carrega os ambientes iniciais
            loadAmbientes();

            // Inicializa os gráficos
            initCharts();

            // Eventos de mudança nos combos
            $('#ambiente').on('change', function(){
                var ambiente = $(this).val();
                resetCombosAposAmbiente();
                if(ambiente){
                    loadServiceNames(ambiente);
                } else {
                    resetDashboardValues();
                }
            });

            $('#service_name').on('change', function(){
                var ambiente = $('#ambiente').val();
                var service_name = $(this).val();
                resetCombosAposServiceName();
                if(service_name){
                    loadSchemas(ambiente, service_name);
                    updateDashboard(ambiente, service_name, '', '');
                } else {
                    resetDashboardValues();
                }
            });

            $('#schema_name').on('change', function(){
                var ambiente = $('#ambiente').val();
                var service_name = $('#service_name').val();
                var schema_name = $(this).val();
                resetComboTabela();
                if(schema_name){
                    loadTables(ambiente, service_name, schema_name);
                    updateDashboard(ambiente, service_name, schema_name, '');
                } else {
                    resetDashboardValues();
                }
            });

            $('#table_name').on('change', function(){
                var ambiente     = $('#ambiente').val();
                var service_name = $('#service_name').val();
                var schema_name  = $('#schema_name').val();
                var table_name   = $(this).val();
                if(table_name){
                    updateDashboard(ambiente, service_name, schema_name, table_name);
                } else {
                    resetDashboardValues();
                }
            });

            // Botão Limpar Filtros
            $('#btnClear').on('click', function(){
                clearAllFilters();
            });
        });

        // Inicialização dos Charts
        function initCharts() {
            // BAR CHART: Apenas "Tags" e "Marcados"
            // Renomeamos a label "Marcados" para "Anotados" no array de labels
            var barCtx = document.getElementById('barChart').getContext('2d');
            barChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: ["Tags", "Anotados"], // <-- aqui renomeado
                    datasets: [{
                        label: 'Percentual (Base: Sugestões)',
                        data: [0, 0],
                        backgroundColor: [
                            '#0dcaf0', // azul claro (tags)
                            '#198754'  // verde (anotados)
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y.toFixed(2) + '%';
                                }
                            }
                        }
                    }
                }
            });

            // PRIMEIRO PIE CHART: (Tags x Marcados), base Sugestões
            // Renomeamos "Marcados" para "Anotados" também
            var pieCtx = document.getElementById('pieChart').getContext('2d');
            pieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: ["Tags", "Anotados"], // <-- renomeado
                    datasets: [{
                        data: [0, 0],
                        backgroundColor: [
                            '#0dcaf0', // azul claro (tags)
                            '#198754'  // verde (anotados)
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = context.label || '';
                                    var value = context.parsed || 0;
                                    return label + ': ' + value.toFixed(2) + '%';
                                }
                            }
                        }
                    }
                }
            });

            // SEGUNDO PIE CHART: (Atributos x Sugestões)
            var pieCtx2 = document.getElementById('pieChart2').getContext('2d');
            pieChart2 = new Chart(pieCtx2, {
                type: 'pie',
                data: {
                    labels: ["Sugestões", "Outros Atributos"],
                    datasets: [{
                        data: [0, 0],
                        backgroundColor: [
                            '#ffc107', // amarelo (sugestões)
                            '#0d6efd'  // azul (outros)
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = context.label || '';
                                    var value = context.parsed || 0;
                                    return label + ': ' + value.toFixed(2) + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Funções de RESET
        function resetCombosAposAmbiente(){
            $('#service_name').html('<option value="">Selecione o Service Name</option>').prop('disabled', true);
            $('#schema_name').html('<option value="">Selecione o Schema</option>').prop('disabled', true);
            $('#table_name').html('<option value="">Selecione a Tabela</option>').prop('disabled', true);
        }
        function resetCombosAposServiceName(){
            $('#schema_name').html('<option value="">Selecione o Schema</option>').prop('disabled', true);
            $('#table_name').html('<option value="">Selecione a Tabela</option>').prop('disabled', true);
        }
        function resetComboTabela(){
            $('#table_name').html('<option value="">Selecione a Tabela</option>').prop('disabled', true);
        }
        function resetDashboardValues(){
            $('#dashboardQtdAtributos').text('0');
            $('#dashboardSugestoesSensidata').text('0');
            $('#dashboardTagsTabela').text('0');
            $('#dashboardMarcados').text('0');

            // Zera também os dados dos gráficos
            barChart.data.datasets[0].data = [0, 0];
            barChart.update();

            pieChart.data.datasets[0].data = [0, 0];
            pieChart.update();

            pieChart2.data.datasets[0].data = [0, 0];
            pieChart2.update();
        }
        function clearAllFilters(){
            $('#ambiente').val('');
            resetCombosAposAmbiente();
            resetDashboardValues();
            loadAmbientes();
        }

        // Funções de carregamento via AJAX
        function loadAmbientes(){
            $.ajax({
                url: 'catalogo/catalogo_dsh_marcacao_lgpd_ajax.php',
                method: 'GET',
                data: {
                    action: 'getAmbientes',
                    user: userParam
                },
                dataType: 'json',
                success: function(response){
                    if(response.success){
                        var options = '<option value="">Selecione um Ambiente</option>';
                        response.data.forEach(function(amb){
                            options += '<option value="'+amb+'">'+amb+'</option>';
                        });
                        $('#ambiente').html(options).prop('disabled', false);
                    }
                }
            });
        }
        function loadServiceNames(ambiente){
            $.ajax({
                url: 'catalogo/catalogo_dsh_marcacao_lgpd_ajax.php',
                method: 'GET',
                data: {
                    action: 'getServiceNames',
                    ambiente: ambiente,
                    user: userParam
                },
                dataType: 'json',
                success: function(response){
                    if(response.success){
                        var options = '<option value="">Selecione o Service Name</option>';
                        response.data.forEach(function(srv){
                            options += '<option value="'+srv+'">'+srv+'</option>';
                        });
                        $('#service_name').html(options).prop('disabled', false);
                    }
                }
            });
        }
        function loadSchemas(ambiente, service_name){
            $.ajax({
                url: 'catalogo/catalogo_dsh_marcacao_lgpd_ajax.php',
                method: 'GET',
                data: {
                    action: 'getSchemas',
                    ambiente: ambiente,
                    service_name: service_name,
                    user: userParam
                },
                dataType: 'json',
                success: function(response){
                    if(response.success){
                        var options = '<option value="">Selecione o Schema</option>';
                        response.data.forEach(function(sch){
                            options += '<option value="'+sch+'">'+sch+'</option>';
                        });
                        $('#schema_name').html(options).prop('disabled', false);
                    }
                }
            });
        }
        function loadTables(ambiente, service_name, schema_name){
            $.ajax({
                url: 'catalogo/catalogo_dsh_marcacao_lgpd_ajax.php',
                method: 'GET',
                data: {
                    action: 'getTables',
                    ambiente: ambiente,
                    service_name: service_name,
                    schema_name: schema_name,
                    user: userParam
                },
                dataType: 'json',
                success: function(response){
                    if(response.success){
                        var options = '<option value="">Selecione a Tabela</option>';
                        response.data.forEach(function(tbl){
                            options += '<option value="'+tbl+'">'+tbl+'</option>';
                        });
                        $('#table_name').html(options).prop('disabled', false);
                    }
                }
            });
        }

        // Função principal para atualizar o dashboard e gráficos
        function updateDashboard(ambiente, service_name, schema_name, table_name) {
            $.ajax({
                url: 'catalogo/catalogo_dsh_marcacao_lgpd_ajax.php',
                method: 'GET',
                data: {
                    action: 'getDashboardData',
                    ambiente: ambiente,
                    service_name: service_name,
                    schema_name: schema_name,
                    table_name: table_name
                },
                dataType: 'json',
                success: function(response){
                    console.log("DEBUG getDashboardData:", response.debug);
                    if(response.success){
                        // Lê os valores retornados
                        let total       = Number(response.data.qtd_atributos);
                        let sugestoes   = Number(response.data.sugestoes_sensidata);
                        let tags        = Number(response.data.tags_tabela);
                        let marcados    = Number(response.data.marcados);

                        // Exibe valores formatados com pontuação
                        $('#dashboardQtdAtributos').text(total.toLocaleString('pt-BR'));
                        $('#dashboardSugestoesSensidata').text(sugestoes.toLocaleString('pt-BR'));
                        $('#dashboardTagsTabela').text(tags.toLocaleString('pt-BR'));
                        // Aqui mantemos o ID "dashboardMarcados", mas o texto exibido agora é "Atributos Anotados"
                        $('#dashboardMarcados').text(marcados.toLocaleString('pt-BR'));

                        /*
                          GRÁFICO 1 (Bar Chart) e GRÁFICO 2 (Pie Chart):
                          "Tags" e "Anotados" em relação ao total de "Sugestões".
                          Se sugestoes = 0, evitamos divisão por zero usando denom=1.
                        */
                        let denom = sugestoes > 0 ? sugestoes : 1;
                        let tagsPct      = (tags / denom) * 100;
                        let marcadosPct  = (marcados / denom) * 100;

                        // Atualiza o Bar Chart
                        barChart.data.datasets[0].data = [tagsPct, marcadosPct];
                        barChart.update();

                        // Atualiza o Pie Chart (Tags x Anotados)
                        pieChart.data.datasets[0].data = [tagsPct, marcadosPct];
                        pieChart.update();

                        /*
                          GRÁFICO 3 (Pie Chart2):
                          "Sugestões" vs. "Outros Atributos" em relação ao TOTAL de atributos.
                        */
                        let sugPct2    = total > 0 ? (sugestoes / total) * 100 : 0;
                        let outrosPct2 = total > 0 ? ((total - sugestoes) / total) * 100 : 0;

                        pieChart2.data.datasets[0].data = [sugPct2, outrosPct2];
                        pieChart2.update();

                    } else {
                        console.log('Erro no dashboard:', response.message);
                    }
                },
                error: function(){
                    console.log('Erro ao chamar getDashboardData');
                }
            });
        }
    </script>
</body>
</html>
<?php
    } elseif ($acesso != "TELA AUTORIZADA") {
        @include("html/403.html");
    }
} else {
    header("Location: login.php");
}
?>
