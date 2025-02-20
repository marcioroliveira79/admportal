<?php
session_start();

// Recupera a variável $acao, se estiver definida via GET.
$acao = isset($_GET['acao']) ? $_GET['acao'] : null;

// Verifica se o usuário está logado e se a variável $acao está definida
if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && isset($acao) && $acao != null) {

    // Verifica se o usuário tem acesso à tela
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {

        // Consulta para carregar os ambientes (primeiro combo)
        $query_ambiente = "SELECT DISTINCT ambiente FROM administracao.catalog_vw_lgpd_marcacao ORDER BY ambiente";
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

            <style>
            body {
                background-color: #f5f5f5;
                font-family: Arial, sans-serif;
            }
            .form-container,
            .table-container {
                background-color: #ffffff;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                padding: 20px;
                margin: 20px auto;
                width: 90%;
            }
            .form-title {
                text-align: center;
                margin-bottom: 20px;
                font-weight: bold;
                color: #4a4a4a;
            }
            #attributeListContainer {
                background-color: #ffffff;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                margin: 20px auto 40px auto;
                padding: 20px;
                width: 100%;
                overflow-x: auto;
                padding-bottom: 20px;
                font-size: 11px;
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
                table-layout: fixed;
            }
            #attributesTable th,
            #attributesTable td {
                padding: 10px;
                text-align: left;
                border: 1px solid #ddd;
                font-size: 11px;
            }
            #attributesTable th {
                background-color: #f8f9fa;
                font-weight: bold;
            }
            #attributesTable tr:hover {
                background-color: #f1f1f1;
            }
            #attributesTable th:nth-child(1),
            #attributesTable td:nth-child(1) {
                width: 180px !important;
                max-width: 180px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            #attributesTable th:nth-child(2),
            #attributesTable td:nth-child(2) {
                width: 50px !important;
                max-width: 50px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            #attributesTable th:nth-child(3),
            #attributesTable td:nth-child(3) {
                width: 60px !important;
                max-width: 60px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            #attributesTable th:nth-child(4),
            #attributesTable td:nth-child(4) {
                width: 80px !important;
                max-width: 80px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            #attributesTable th:nth-child(5),
            #attributesTable td:nth-child(5) {
                width: 40px !important;
                max-width: 40px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            #attributesTable th:nth-child(6),
            #attributesTable td:nth-child(6) {
                width: 250px !important;
                max-width: 250px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            #attributesTable th:nth-child(9),
            #attributesTable td:nth-child(9) {
                width: 100px;
                text-align: center;
                vertical-align: middle;
                padding: 5px;
            }

            /* Deixa botão disabled mais opaco */
            .btn[disabled],
            .btn.disabled {
                opacity: 0.5;
                pointer-events: none;
            }

            /* Diminui a fonte dos selects (combos) */
            .form-select.form-select-sm {
                font-size: 11px; /* Ajuste conforme desejar */
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
                    <div class="form-title"><h3>LGPD Apuração</h3></div>
                    <form id="filterForm" method="GET" class="row g-3">
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

                <!-- Comentário da Tabela e listagem dos atributos -->
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
                                <th>AÇÃO</th>
                                <th>INFO</th>
                                <th>MARCAR</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div id="totalResults"></div>
                </div>
            </div>

            <script>
            $(document).ready(function(){

                // 1) Carregamento dos combos de ambiente, service, schema, table
                $('#ambiente').on('change', function(){
                    var ambiente = $(this).val();
                    $('#service_name').html('<option value="">Selecione o Service Name</option>').prop('disabled', true);
                    $('#schema_name').html('<option value="">Selecione o Schema</option>').prop('disabled', true);
                    $('#table_name').html('<option value="">Selecione a Tabela</option>').prop('disabled', true);
                    $('#btnConsultar').prop('disabled', true);
                    
                    if(ambiente){
                        $.ajax({
                            url: 'catalogo_lgpd_vw_ajax.php',
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
                            }
                        });
                    }
                });

                $('#service_name').on('change', function(){
                    var ambiente = $('#ambiente').val();
                    var service_name = $(this).val();
                    $('#schema_name').html('<option value="">Selecione o Schema</option>').prop('disabled', true);
                    $('#table_name').html('<option value="">Selecione a Tabela</option>').prop('disabled', true);
                    $('#btnConsultar').prop('disabled', true);
                    
                    if(service_name){
                        $.ajax({
                            url: 'catalogo_lgpd_vw_ajax.php',
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
                            }
                        });
                    }
                });

                $('#schema_name').on('change', function(){
                    var ambiente = $('#ambiente').val();
                    var service_name = $('#service_name').val();
                    var schema_name = $(this).val();
                    $('#table_name').html('<option value="">Selecione a Tabela</option>').prop('disabled', true);
                    $('#btnConsultar').prop('disabled', true);
                    
                    if(schema_name){
                        $.ajax({
                            url: 'catalogo_lgpd_vw_ajax.php',
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
                            }
                        });
                    }
                });

                $('#table_name').on('change', function(){
                    if($(this).val()){
                        $('#btnConsultar').prop('disabled', false);
                    } else {
                        $('#btnConsultar').prop('disabled', true);
                    }
                });

                // 2) Carregar relação Ação-Info (getAcoesInfos)
                var acoesInfosMap = {};
                var listaAcoesUnicas = [];

                $.ajax({
                    url: 'catalogo_lgpd_vw_ajax.php',
                    method: 'GET',
                    data: { action: 'getAcoesInfos' },
                    dataType: 'json',
                    success: function(response){
                        if(response.success){
                            var tempMap = {};
                            response.data.forEach(function(obj){
                                var acao = obj.acao;
                                var info = obj.info;
                                if(!tempMap[acao]){
                                    tempMap[acao] = [];
                                }
                                if(tempMap[acao].indexOf(info) === -1){
                                    tempMap[acao].push(info);
                                }
                            });
                            acoesInfosMap = tempMap;
                            listaAcoesUnicas = Object.keys(acoesInfosMap);
                        }
                    }
                });

                // 3) Botão Consultar -> getAttributes
                $('#btnConsultar').on('click', function(){
                    var ambiente = $('#ambiente').val();
                    var service_name = $('#service_name').val();
                    var schema_name = $('#schema_name').val();
                    var table_name = $('#table_name').val();

                    $.ajax({
                        url: 'catalogo_lgpd_vw_ajax.php',
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
                            if($.fn.DataTable.isDataTable('#attributesTable')){
                                $('#attributesTable').DataTable().destroy();
                            }
                            $('#attributesTable tbody').empty();

                            if(response.success && response.data.length > 0){
                                var tableComment = response.data[0].table_comments ? response.data[0].table_comments : "Sem comentário";
                                $('#tableComment').html(tableComment);

                                $.each(response.data, function(index, item){
                                    var comentario     = item.column_comments || '';
                                    var aceitaNull     = item.is_nullable || '';
                                    var chave          = '';
                                    if(item.is_pk === 'Y'){ chave = 'PK'; }
                                    else if(item.is_fk === 'Y'){ chave = 'FK'; }

                                    // Vamos capturar data_base, host_name e column_comment
                                    var dataBase       = item.data_base || '';
                                    var hostName       = item.host_name || '';
                                    var columnComment  = comentario; // Ou item.column_comments

                                    var acaoAtual      = item.acao_lgpd || '';
                                    var infoAtual      = item.lgpd_informacao || '';

                                    // Monta combo de Ação (tamanho menor com .form-select-sm)
                                    var selectAcao = '<select class="form-select form-select-sm select-acao-linha">';
                                    if(acaoAtual){
                                        selectAcao += '<option value="'+acaoAtual+'">'+acaoAtual+'</option>';
                                    } else {
                                        selectAcao += '<option value="">Selecione...</option>';
                                    }
                                    listaAcoesUnicas.forEach(function(acao){
                                        if(acao !== acaoAtual){
                                            selectAcao += '<option value="'+acao+'">'+acao+'</option>';
                                        }
                                    });
                                    selectAcao += '</select>';

                                    // Monta combo de Info (tamanho menor com .form-select-sm)
                                    var selectInfo = '<select class="form-select form-select-sm select-info-linha">';
                                    if(infoAtual){
                                        selectInfo += '<option value="'+infoAtual+'">'+infoAtual+'</option>';
                                    } else {
                                        selectInfo += '<option value="">Selecione...</option>';
                                    }
                                    if(acaoAtual && acoesInfosMap[acaoAtual]){
                                        acoesInfosMap[acaoAtual].forEach(function(info){
                                            if(info !== infoAtual){
                                                selectInfo += '<option value="'+info+'">'+info+'</option>';
                                            }
                                        });
                                    }
                                    selectInfo += '</select>';

                                    // Define o botão (Inserir/Remover) e combos habilitados ou não
                                    var acaoButton = '';
                                    var disabledStr = '';
                                    if(acaoAtual && infoAtual){
                                        if(item.fk_lgpd_marcacao && item.fk_lgpd_marcacao !== '0'){
                                            // Registro inserido -> Remover
                                            acaoButton = '<button class="btn btn-danger btn-sm remover-btn" '+
                                                         'data-column_comment="'+ columnComment +'" '+
                                                         'data-data_base="'+ dataBase +'" '+
                                                         'data-host_name="'+ hostName +'">Remover</button>';
                                            disabledStr = 'disabled'; // combos desabilitados
                                        } else {
                                            // Possui acao e info, mas não está inserido -> Inserir
                                            acaoButton = '<button class="btn btn-success btn-sm inserir-btn" '+
                                                         'data-column_comment="'+ columnComment +'" '+
                                                         'data-data_base="'+ dataBase +'" '+
                                                         'data-host_name="'+ hostName +'">Inserir</button>';
                                        }
                                    } else {
                                        // Ainda não há acao_lgpd ou info -> botão inserir desabilitado
                                        acaoButton = '<button class="btn btn-success btn-sm inserir-btn" disabled '+
                                                     'data-column_comment="'+ columnComment +'" '+
                                                     'data-data_base="'+ dataBase +'" '+
                                                     'data-host_name="'+ hostName +'">Inserir</button>';
                                    }

                                    var row = '<tr>'+
                                        '<td>'+ (item.column_name || '') +'</td>'+
                                        '<td>'+ (item.data_type || '') +'</td>'+
                                        '<td>'+ (item.data_length || '') +'</td>'+
                                        '<td>'+ aceitaNull +'</td>'+
                                        '<td>'+ chave +'</td>'+
                                        '<td title="'+ comentario +'">'+ comentario +'</td>'+
                                        '<td>'+ selectAcao +'</td>'+
                                        '<td>'+ selectInfo +'</td>'+
                                        '<td>'+ acaoButton +'</td>'+
                                    '</tr>';

                                    var $rowObj = $(row);
                                    // Se combos devem ficar desabilitados
                                    if(disabledStr){
                                        $rowObj.find('.select-acao-linha').prop('disabled', true);
                                        $rowObj.find('.select-info-linha').prop('disabled', true);
                                    }
                                    $('#attributesTable tbody').append($rowObj);
                                });

                                $('#totalResults').html('Total de resultados: ' + response.data.length);
                            } else {
                                $('#tableComment').html("Sem comentário");
                                $('#attributesTable tbody').html('<tr><td colspan="9" class="text-center">Nenhum dado encontrado.</td></tr>');
                                $('#totalResults').html('Total de resultados: 0');
                            }
                            
                            $('#attributesTable').DataTable({ autoWidth: false });
                            $('#attributeListContainer').show();
                        }
                    });
                });

                // 4) Filtra combo Info ao mudar Ação
                $(document).on('change', '.select-acao-linha', function(){
                    var $linha = $(this).closest('tr');
                    var acaoSelecionada = $(this).val();
                    var $comboInfo = $linha.find('.select-info-linha');

                    $comboInfo.empty();
                    if(!acaoSelecionada || !acoesInfosMap[acaoSelecionada]){
                        $comboInfo.append('<option value="">Selecione...</option>');
                    } else {
                        $comboInfo.append('<option value="">Selecione...</option>');
                        acoesInfosMap[acaoSelecionada].forEach(function(info){
                            $comboInfo.append('<option value="'+info+'">'+info+'</option>');
                        });
                    }
                    verificarHabilitarInserir($linha);
                });

                // 5) Ao mudar combo Info, checa se habilita Inserir
                $(document).on('change', '.select-info-linha', function(){
                    var $linha = $(this).closest('tr');
                    verificarHabilitarInserir($linha);
                });

                // Habilita o botão Inserir se ambos combos tiverem valor
                function verificarHabilitarInserir($linha){
                    var acaoVal = $linha.find('.select-acao-linha').val() || '';
                    var infoVal = $linha.find('.select-info-linha').val() || '';
                    var $btnInserir = $linha.find('.inserir-btn');

                    if($btnInserir.length){
                        if(acaoVal && infoVal){
                            $btnInserir.prop('disabled', false);
                        } else {
                            $btnInserir.prop('disabled', true);
                        }
                    }
                }

                // 6) Clique em Inserir
                $(document).on('click', '.inserir-btn', function(){
                    var btn = $(this);
                    // Se o botão estiver desabilitado, não faz nada
                    if(btn.prop('disabled')) return;

                    var $linha = btn.closest('tr');
                    var acao_lgpd = $linha.find('.select-acao-linha').val();
                    var lgpd_informacao = $linha.find('.select-info-linha').val();

                    // Verificação final
                    if(!acao_lgpd || !lgpd_informacao){
                        alert('Selecione uma Ação e uma Info antes de inserir!');
                        return;
                    }

                    var ambiente     = $('#ambiente').val();
                    var service_name = $('#service_name').val();
                    var schema_name  = $('#schema_name').val();
                    var table_name   = $('#table_name').val();
                    var column_name  = $linha.find('td:nth-child(1)').text().trim();

                    // Recupera data_base, host_name, column_comment do botão
                    var data_base       = btn.data('data_base') || '';
                    var host_name       = btn.data('host_name') || '';
                    var column_comment  = btn.data('column_comment') || '';

                    $.ajax({
                       url: 'catalogo_lgpd_vw_ajax.php',
                       method: 'GET',
                       data: {
                            action: 'insertAttribute',
                            ambiente: ambiente,
                            service_name: service_name,
                            schema_name: schema_name,
                            table_name: table_name,
                            column_name: column_name,
                            acao_lgpd: acao_lgpd,
                            lgpd_informacao: lgpd_informacao,
                            data_base: data_base,
                            host_name: host_name,
                            column_comment: column_comment
                       },
                       dataType: 'json',
                       success: function(response){
                           if(response.success){
                               alert(response.message);
                               // Troca para "Remover"
                               btn.removeClass('btn-success inserir-btn')
                                  .addClass('btn-danger remover-btn')
                                  .text('Remover');
                               // Desabilita combos
                               $linha.find('.select-acao-linha').prop('disabled', true);
                               $linha.find('.select-info-linha').prop('disabled', true);
                           } else {
                               alert('Erro: ' + response.message);
                           }
                       },
                       error: function(){
                           alert('Erro ao inserir os dados.');
                       }
                    });
                });

                // 7) Clique em Remover
                $(document).on('click', '.remover-btn', function(){
                    var btn = $(this);
                    var $linha = btn.closest('tr');

                    var ambiente     = $('#ambiente').val();
                    var service_name = $('#service_name').val();
                    var schema_name  = $('#schema_name').val();
                    var table_name   = $('#table_name').val();
                    var column_name  = $linha.find('td:nth-child(1)').text().trim();

                    // Recupera data_base, host_name, column_comment do botão
                    var data_base       = btn.data('data_base') || '';
                    var host_name       = btn.data('host_name') || '';
                    var column_comment  = btn.data('column_comment') || '';

                    $.ajax({
                       url: 'catalogo_lgpd_vw_ajax.php',
                       method: 'GET',
                       data: {
                            action: 'removeAttribute',
                            ambiente: ambiente,
                            service_name: service_name,
                            schema_name: schema_name,
                            table_name: table_name,
                            column_name: column_name,
                            data_base: data_base,
                            host_name: host_name
                       },
                       dataType: 'json',
                       success: function(response){
                           if(response.success){
                               alert(response.message);
                               // Volta para "Inserir"
                               // Mas precisamos preservar data_base, host_name, column_comment
                               btn.removeClass('btn-danger remover-btn')
                                  .addClass('btn-success inserir-btn')
                                  .text('Inserir')
                                  .attr('data-data_base', data_base)
                                  .attr('data-host_name', host_name)
                                  .attr('data-column_comment', column_comment);

                               // Reabilita combos
                               $linha.find('.select-acao-linha').prop('disabled', false);
                               $linha.find('.select-info-linha').prop('disabled', false);

                               // Se combos não têm valor, desabilita o botão
                               var acaoVal = $linha.find('.select-acao-linha').val() || '';
                               var infoVal = $linha.find('.select-info-linha').val() || '';
                               if(!acaoVal || !infoVal){
                                   btn.prop('disabled', true);
                               }
                           } else {
                               alert('Erro: ' + response.message);
                           }
                       },
                       error: function(){
                           alert('Erro ao remover os dados.');
                       }
                    });
                });
            });
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
