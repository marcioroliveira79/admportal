<?php
session_start();

// Verifica se o usuário está logado e se a variável $acao está definida
if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && isset($acao) && $acao != null) {

    // Verifica se o usuário tem acesso à tela
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {

        // Consulta para carregar os ambientes (primeiro combo)
        $query_ambiente = "SELECT DISTINCT ambiente FROM administracao.catalog_table_content ORDER BY ambiente";
        $result_ambiente = pg_query($conexao, $query_ambiente);
        if (!$result_ambiente) {
            $erro_banco = pg_last_error($conexao);
        }
        $ambientes = pg_fetch_all($result_ambiente);
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Dashboard de Atributos</title>
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
                .form-container, .table-container {
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
                    margin-bottom: 40px;
                    max-width: 1700px;
                    overflow-x: auto;
                    padding-bottom: 20px;
                }
                /* Reduz a fonte da listagem para caber mais informações */
                #attributesTable {
                    font-size: 12px;
                    width: 100%;
                    table-layout: fixed;
                    border-collapse: collapse;
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
                /* Reduz a largura da coluna "TIPO" (2ª coluna) */
                #attributesTable th:nth-child(2),
                #attributesTable td:nth-child(2) {
                    width: 80px !important;
                    max-width: 80px !important;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                /* Reduz a largura da coluna "TAMANHO" (3ª coluna) */
                #attributesTable th:nth-child(3),
                #attributesTable td:nth-child(3) {
                    width: 80px !important;
                    max-width: 80px !important;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                /* Reduz a largura da coluna "NULL" (4ª coluna) */
                #attributesTable th:nth-child(4),
                #attributesTable td:nth-child(4) {
                    width: 60px !important;
                    max-width: 60px !important;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                /* Reduz a largura da coluna "CHAVE" (5ª coluna) */
                #attributesTable th:nth-child(5),
                #attributesTable td:nth-child(5) {
                    width: 60px !important;
                    max-width: 60px !important;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
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

                <!-- Formulário de Filtros com 4 combos -->
                <div class="form-container">
                    <div class="form-title"><h3>Visualizador</h3></div>
                    <form id="filterForm" method="GET" class="row g-3">
                        <!-- Preserva a ação -->
                        <input type="hidden" name="acao" value="<?= htmlspecialchars($acao) ?>">
                        
                        <div class="col-md-3">
                            <label for="ambiente" class="form-label">Ambiente</label>
                            <select name="ambiente" id="ambiente" class="form-select" required>
                                <option value="">Selecione um Ambiente</option>
                                <?php if ($ambientes): ?>
                                    <?php foreach ($ambientes as $amb): ?>
                                        <option value="<?= htmlspecialchars($amb['ambiente']) ?>"><?= htmlspecialchars($amb['ambiente']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="service_name" class="form-label">Service Name</label>
                            <select name="service_name" id="service_name" class="form-select" required disabled>
                                <option value="">Selecione o Service Name</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="schema_name" class="form-label">Schema</label>
                            <select name="schema_name" id="schema_name" class="form-select" required disabled>
                                <option value="">Selecione o Schema</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="table_name" class="form-label">Tabela</label>
                            <select name="table_name" id="table_name" class="form-select" required disabled>
                                <option value="">Selecione a Tabela</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12 text-end">
                            <button type="button" id="btnConsultar" class="btn btn-success" disabled>Consultar</button>
                        </div>
                    </form>
                </div>

                <!-- Comentário da Tabela (antes “Lista de Atributos”) e listagem dos atributos -->
                <div id="attributeListContainer" class="table-container mt-4" style="display:none;">
                    <h4 id="tableComment">Comentários da Tabela</h4>
                    <table id="attributesTable" class="display">
                        <thead>
                            <tr>
                                <th>COLUNA</th>
                                <th>TIPO</th>
                                <th>TAMANHO</th>
                                <th>NULL</th>
                                <th>CHAVE</th>
                                <th>COMENTÁRIO</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div id="totalResults"></div>
                </div>
            </div>

            <script>
            $(document).ready(function(){

                // Quando o combo Ambiente é alterado, busca os Service Names
                $('#ambiente').on('change', function(){
                    var ambiente = $(this).val();
                    // Limpa e desabilita os demais combos
                    $('#service_name').html('<option value="">Selecione o Service Name</option>').prop('disabled', true);
                    $('#schema_name').html('<option value="">Selecione o Schema</option>').prop('disabled', true);
                    $('#table_name').html('<option value="">Selecione a Tabela</option>').prop('disabled', true);
                    $('#btnConsultar').prop('disabled', true);
                    
                    if(ambiente){
                        $.ajax({
                            url: 'catalogo/catalogo_busca_tabela_ajax.php',
                            method: 'GET',
                            data: { action: 'getServiceNames', ambiente: ambiente },
                            dataType: 'json',
                            success: function(response){
                                if(response.success){
                                    var options = '<option value="">Selecione o Service Name</option>';
                                    $.each(response.data, function(index, value){
                                        options += '<option value="'+value+'">'+value+'</option>';
                                    });
                                    $('#service_name').html(options).prop('disabled', false);
                                }
                            },
                            error: function(){
                                alert('Erro ao carregar os Service Names.');
                            }
                        });
                    }
                });

                // Quando o combo Service Name é alterado, busca os Schemas
                $('#service_name').on('change', function(){
                    var ambiente = $('#ambiente').val();
                    var service_name = $(this).val();
                    $('#schema_name').html('<option value="">Selecione o Schema</option>').prop('disabled', true);
                    $('#table_name').html('<option value="">Selecione a Tabela</option>').prop('disabled', true);
                    $('#btnConsultar').prop('disabled', true);
                    
                    if(service_name){
                        $.ajax({
                            url: 'catalogo/catalogo_busca_tabela_ajax.php',
                            method: 'GET',
                            data: { action: 'getSchemas', ambiente: ambiente, service_name: service_name },
                            dataType: 'json',
                            success: function(response){
                                if(response.success){
                                    var options = '<option value="">Selecione o Schema</option>';
                                    $.each(response.data, function(index, value){
                                        options += '<option value="'+value+'">'+value+'</option>';
                                    });
                                    $('#schema_name').html(options).prop('disabled', false);
                                }
                            },
                            error: function(){
                                alert('Erro ao carregar os Schemas.');
                            }
                        });
                    }
                });

                // Quando o combo Schema é alterado, busca as Tabelas
                $('#schema_name').on('change', function(){
                    var ambiente = $('#ambiente').val();
                    var service_name = $('#service_name').val();
                    var schema_name = $(this).val();
                    $('#table_name').html('<option value="">Selecione a Tabela</option>').prop('disabled', true);
                    $('#btnConsultar').prop('disabled', true);
                    
                    if(schema_name){
                        $.ajax({
                            url: 'catalogo/catalogo_busca_tabela_ajax.php',
                            method: 'GET',
                            data: { action: 'getTables', ambiente: ambiente, service_name: service_name, schema_name: schema_name },
                            dataType: 'json',
                            success: function(response){
                                if(response.success){
                                    var options = '<option value="">Selecione a Tabela</option>';
                                    $.each(response.data, function(index, value){
                                        options += '<option value="'+value+'">'+value+'</option>';
                                    });
                                    $('#table_name').html(options).prop('disabled', false);
                                }
                            },
                            error: function(){
                                alert('Erro ao carregar as Tabelas.');
                            }
                        });
                    }
                });

                // Habilita o botão Consultar quando uma Tabela for selecionada
                $('#table_name').on('change', function(){
                    if($(this).val()){
                        $('#btnConsultar').prop('disabled', false);
                    } else {
                        $('#btnConsultar').prop('disabled', true);
                    }
                });

                // Ao clicar no botão Consultar, carrega os atributos da tabela selecionada
                $('#btnConsultar').on('click', function(){
                    var ambiente = $('#ambiente').val();
                    var service_name = $('#service_name').val();
                    var schema_name = $('#schema_name').val();
                    var table_name = $('#table_name').val();

                    $.ajax({
                        url: 'catalogo/catalogo_busca_tabela_ajax.php',
                        method: 'GET',
                        data: { 
                            action: 'getAttributes', 
                            ambiente: ambiente, 
                            service_name: service_name, 
                            schema_name: schema_name, 
                            table_name: table_name 
                        },
                        dataType: 'json',
                        success: function(response){
                            // Destrói a DataTable, se existir
                            if($.fn.DataTable.isDataTable('#attributesTable')){
                                $('#attributesTable').DataTable().destroy();
                            }
                            $('#attributesTable tbody').empty();

                            if(response.success && response.data.length > 0){
                                // Atualiza o cabeçalho com o comentário da tabela (usando o valor da primeira linha)
                                var tableComment = response.data[0].table_comments ? response.data[0].table_comments : "Sem comentário";
                                $('#tableComment').html(tableComment);

                                $.each(response.data, function(index, item){
                                    var comentario = item.column_comments ? item.column_comments : '';

                                    // Coluna "ACEITA NULL"
                                    var aceitaNull = item.is_nullable;

                                    // Coluna "CHAVE"
                                    // Se is_pk === 'Y', exibe "PK"; senão se is_fk === 'Y', exibe "FK"
                                    // Se ambos forem 'Y', prioriza "PK"
                                    var chave = '';
                                    if(item.is_pk === 'Y'){
                                        chave = 'PK';
                                    } else if(item.is_fk === 'Y'){
                                        chave = 'FK';
                                    }

                                    var row = '<tr>'+
                                        '<td>'+ item.column_name +'</td>'+
                                        '<td>'+ item.data_type +'</td>'+
                                        '<td>'+ item.data_length +'</td>'+
                                        '<td>'+ aceitaNull +'</td>'+
                                        '<td>'+ chave +'</td>'+
                                        '<td>'+ comentario +'</td>'+
                                    '</tr>';
                                    $('#attributesTable tbody').append(row);
                                });
                                $('#totalResults').html('Total de resultados: ' + response.data.length);
                            } else {
                                $('#tableComment').html("Sem comentário");
                                $('#attributesTable tbody').html('<tr><td colspan="6" class="text-center">Nenhum dado encontrado.</td></tr>');
                                $('#totalResults').html('Total de resultados: 0');
                            }
                            
                            // Inicializa o DataTable com autoWidth desabilitado
                            $('#attributesTable').DataTable({
                                autoWidth: false
                            });
                            $('#attributeListContainer').show();
                        },
                        error: function(){
                            alert('Erro ao carregar os atributos da tabela.');
                        }
                    });
                });
            });
            </script>
        </body>
        <br><br><br><br>
        </html>
        <?php
    } elseif ($acesso != "TELA AUTORIZADA") {
        @include("html/403.html");
    }
} else {
    header("Location: login.php");
}
?>
