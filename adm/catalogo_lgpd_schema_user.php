<?php
session_start();

// Recupera a variável $acao se vier via GET.
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
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
            /* Ajustes de largura das colunas */
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
            /* A coluna DEF será a 9ª no HTML */
            #attributesTable th:nth-child(9),
            #attributesTable td:nth-child(9) {
                width: 120px;
                text-align: center;
                vertical-align: middle;
                padding: 5px;
            }
            /* Botão disabled mais opaco */
            .btn[disabled],
            .btn.disabled {
                opacity: 0.1; 
                pointer-events: none;
            }
            /* Diminui a fonte dos selects (combos) */
            .form-select.form-select-sm {
                font-size: 11px;
            }
            /* Expandir sub-linha dentro da 1ª coluna */
            .coluna-cell {
                position: relative;
            }
            .expand-icon {
                cursor: pointer;
                margin-right: 5px;
                font-weight: bold;
            }
            tr.shown .expand-icon {
                background: #e9ecef;
            }
            /* Estilo do loading overlay */
            #loading {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255,255,255,0.7);
                z-index: 9999;
                text-align: center;
                padding-top: 20%;
                font-size: 18px;
                color: #333;
            }
            </style>
        </head>
        <body>
            <!-- Overlay de Loading -->
            <div id="loading">
                <span>Carregando...</span>
            </div>
            
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
                    <div class="form-title"><h3>Marcação de atributos LGPD</h3></div>
                    <form id="filterForm" method="GET" class="row g-3">
                        <input type="hidden" name="acao" value="<?= htmlspecialchars($acao) ?>">

                        <div class="col-md-3">
                            <label for="ambiente" class="form-label">Ambiente</label>
                            <select name="ambiente" id="ambiente" class="form-select form-select-sm" required>
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
                                <th>DEF</th> <!-- Nova coluna DEF -->
                                <th>MARCAR</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div id="totalResults"></div>
                </div>
            </div>

            <script>
            // Exemplo de formatação de data/hora (caso precise)
            function formatDateTime(dateString) {
                if(!dateString) return '';
                var d = new Date(dateString);
                if(isNaN(d.getTime())) return dateString;
                var dd = String(d.getDate()).padStart(2,'0');
                var mm = String(d.getMonth()+1).padStart(2,'0');
                var yyyy = d.getFullYear();
                var hh = String(d.getHours()).padStart(2,'0');
                var mn = String(d.getMinutes()).padStart(2,'0');
                return dd + '/' + mm + '/' + yyyy + ' ' + hh + ':' + mn;
            }

            $(document).ready(function(){

                // 1) Carregamento dos combos (ambiente, service, schema, table)
                $('#ambiente').on('change', function(){
                    var ambiente = $(this).val();
                    $('#loading').show(); // Exibe o loading

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
                            },
                            complete: function(){
                                $('#loading').hide(); // Oculta o loading ao finalizar
                            }
                        });
                    } else {
                        $('#loading').hide();
                    }
                });

                $('#service_name').on('change', function(){
                    var ambiente = $('#ambiente').val();
                    var service_name = $(this).val();
                    $('#loading').show();
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
                            },
                            complete: function(){
                                $('#loading').hide();
                            }
                        });
                    } else {
                        $('#loading').hide();
                    }
                });

                $('#schema_name').on('change', function(){
                    var ambiente = $('#ambiente').val();
                    var service_name = $('#service_name').val();
                    var schema_name = $(this).val();
                    $('#loading').show();
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
                            },
                            complete: function(){
                                $('#loading').hide();
                            }
                        });
                    } else {
                        $('#loading').hide();
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
                    $('#loading').show(); // Exibe o loading ao iniciar a consulta
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
                            // Destroi o DataTable, se existir
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

                                    var dataBase       = item.data_base || '';
                                    var hostName       = item.host_name || '';
                                    var columnComment  = comentario;
                                    var acaoAtual      = item.acao_lgpd || '';
                                    var infoAtual      = item.lgpd_informacao || '';
                                    var defValue       = item.lgpd_definicao || ''; // Valor original de lgpd_definicao

                                    // Campos extras para a expansão
                                    var acaoAtualCampo = item.acao_lgpd_atual || '';
                                    var infoAtualCampo = item.lgpd_informacao_atual || '';
                                    var atributoRel    = item.atributo_relacionado || '';
                                    var palavraRel     = item.palavra_relacionada || '';
                                    var nomeCriador    = item.nome_usuario_criador || '';
                                    var dataMarcacao   = item.data_criacao_marcacao || '';

                                    // Monta a 1ª coluna com o expand-icon
                                    var firstCell = '<td class="coluna-cell" '+
                                                    'data-atributo_relacionado="'+ atributoRel +'" '+
                                                    'data-palavra_relacionada="'+ palavraRel +'" '+
                                                    'data-acao_atual="'+ acaoAtualCampo +'" '+
                                                    'data-info_atual="'+ infoAtualCampo +'" '+
                                                    'data-acao_sugerida="'+ acaoAtual +'" '+
                                                    'data-info_sugerida="'+ infoAtual +'" '+
                                                    'data-nome_criador="'+ nomeCriador +'" '+
                                                    'data-data_marcacao="'+ dataMarcacao +'" '+
                                                    'title="'+ (item.column_name || '') +'">'+
                                                    '<span class="expand-icon">+</span> '+
                                                    (item.column_name || '') +
                                                    '</td>';

                                    // Monta combo de Ação
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

                                    // Monta combo de Info (inicial)
                                    var selectInfo = '<select class="form-select form-select-sm select-info-linha">';
                                    if(infoAtual){
                                        selectInfo += '<option value="'+infoAtual+'">'+infoAtual+'</option>';
                                    } else {
                                        selectInfo += '<option value="">Selecione...</option>';
                                    }
                                    // Se existe uma ação atual, carrega as opções correspondentes
                                    if(acaoAtual && acoesInfosMap[acaoAtual]){
                                        acoesInfosMap[acaoAtual].forEach(function(info){
                                            if(info !== infoAtual){
                                                selectInfo += '<option value="'+info+'">'+info+'</option>';
                                            }
                                        });
                                    }
                                    selectInfo += '</select>';

                                    // Cria apenas um SELECT placeholder para DEF
                                    var defSelect = '<select class="form-select form-select-sm select-def-linha" disabled>'
                                                  + '<option value="">--</option>'
                                                  + '</select>';

                                    // Botão Inserir/Remover
                                    var acaoButton = '';
                                    var disabledStr = '';
                                    if(acaoAtual && infoAtual){
                                        if(item.fk_lgpd_marcacao && item.fk_lgpd_marcacao !== '0'){
                                            // Já existe registro => Botão Remover
                                            acaoButton = '<button class="btn btn-danger btn-sm remover-btn" '+
                                                         'data-column_comment="'+ columnComment +'" '+
                                                         'data-data_base="'+ dataBase +'" '+
                                                         'data-host_name="'+ hostName +'">Remover</button>';
                                            disabledStr = 'disabled';
                                        } else {
                                            // Ainda não existe => Botão Inserir
                                            acaoButton = '<button class="btn btn-success btn-sm inserir-btn" '+
                                                         'data-column_comment="'+ columnComment +'" '+
                                                         'data-data_base="'+ dataBase +'" '+
                                                         'data-host_name="'+ hostName +'">Inserir</button>';
                                        }
                                    } else {
                                        // Se Ação ou Info não estiverem selecionadas, Botão Inserir fica desabilitado
                                        acaoButton = '<button class="btn btn-success btn-sm inserir-btn" disabled '+
                                                     'data-column_comment="'+ columnComment +'" '+
                                                     'data-data_base="'+ dataBase +'" '+
                                                     'data-host_name="'+ hostName +'">Inserir</button>';
                                    }

                                    // Monta a linha
                                    var row = '<tr '+
                                              'data-def_value="'+ defValue +'">'+
                                        firstCell +
                                        '<td>'+ (item.data_type || '') +'</td>'+
                                        '<td>'+ (item.data_length || '') +'</td>'+
                                        '<td>'+ aceitaNull +'</td>'+
                                        '<td>'+ chave +'</td>'+
                                        '<td title="'+ comentario +'">'+ comentario +'</td>'+
                                        '<td>'+ selectAcao +'</td>'+
                                        '<td>'+ selectInfo +'</td>'+
                                        '<td>'+ defSelect +'</td>'+
                                        '<td>'+ acaoButton +'</td>'+
                                    '</tr>';

                                    var $rowObj = $(row);

                                    // Se disabledStr está definido => desabilita Ação, Info e DEF
                                    if(disabledStr){
                                        $rowObj.find('.select-acao-linha').prop('disabled', true);
                                        $rowObj.find('.select-info-linha').prop('disabled', true);
                                        $rowObj.find('.select-def-linha').prop('disabled', true);
                                    }

                                    // Adiciona a linha na tabela
                                    $('#attributesTable tbody').append($rowObj);
                                });

                                $('#totalResults').html('Total de resultados: ' + response.data.length);
                            } else {
                                $('#tableComment').html("Sem comentário");
                                $('#attributesTable tbody').html('<tr><td colspan="10" class="text-center">Nenhum dado encontrado.</td></tr>');
                                $('#totalResults').html('Total de resultados: 0');
                            }

                            // Inicializa DataTable
                            var dt = $('#attributesTable').DataTable({ autoWidth: false });

                            // Sempre que uma nova página for desenhada, atualiza o DEF
                            dt.on('draw.dt', function(){
                                $('#attributesTable tbody tr').each(function(){
                                    updateDef($(this));
                                });
                            });

                            // Clique no expand-icon (sub-linha)
                            $('#attributesTable tbody').on('click', '.expand-icon', function(){
                                var $cell = $(this).closest('td.coluna-cell');
                                var $tr = $cell.closest('tr');
                                var row = dt.row($tr);

                                if(row.child.isShown()){
                                    row.child.hide();
                                    $tr.removeClass('shown');
                                    $(this).text('+');
                                } else {
                                    var htmlDetails = formatDetails($cell);
                                    row.child(htmlDetails).show();
                                    $tr.addClass('shown');
                                    $(this).text('–');
                                }
                            });

                            // Após carregar todas as linhas, atualizamos o DEF de cada uma conforme a regra
                            $('#attributesTable tbody tr').each(function(){
                                updateDef($(this));
                            });

                            $('#attributeListContainer').show();
                        },
                        complete: function(){
                            $('#loading').hide(); // Oculta o loading quando a requisição finalizar
                        }
                    });
                });

                // Função para formatar sub-linha (expansão)
                function formatDetails($cell){
                    var atributoRel   = $cell.attr('data-atributo_relacionado') || '';
                    var palavraRel    = $cell.attr('data-palavra_relacionada') || '';
                    var acaoAtual     = $cell.attr('data-acao_atual') || '';
                    var infoAtual     = $cell.attr('data-info_atual') || '';
                    var acaoSugerida  = $cell.attr('data-acao_sugerida') || '';
                    var infoSugerida  = $cell.attr('data-info_sugerida') || '';
                    var nomeCriador   = $cell.attr('data-nome_criador') || '';
                    var dataMarcacao  = $cell.attr('data-data_marcacao') || '';

                    var marcacaoAtual = (acaoAtual || infoAtual) ? (acaoAtual+' '+infoAtual) : '';
                    var marcacaoSugerida = (acaoSugerida || infoSugerida) ? (acaoSugerida+' '+infoSugerida) : '';

                    var alerta = '';
                    if(marcacaoAtual && marcacaoSugerida && (marcacaoAtual.trim() !== marcacaoSugerida.trim())){
                        alerta = ' ⚠';
                    }

                    var dataMarcacaoFmt = formatDateTime(dataMarcacao);

                    var html = '<div style="padding:8px;">';
                    html += '<b>Atributo Relacionado:</b> '+ atributoRel +'<br>';
                    html += '<b>Palavra Relacionada:</b> '+ palavraRel +'<br>';
                    html += '<hr>';
                    html += '<b>Marcação atual:</b> '+ (marcacaoAtual || '(vazio)') +'<br>';
                    html += '<b>Marcação sugerida:</b> '+ (marcacaoSugerida || '(vazio)') + alerta +'<br>';
                    html += '<hr>';
                    html += '<b>Adicionado por:</b> '+ (nomeCriador || '(desconhecido)') +'<br>';
                    html += '<b>Data marcação:</b> '+ (dataMarcacaoFmt || '(vazio)') +'<br>';
                    html += '</div>';
                    return html;
                }

                // Função para atualizar o DEF de uma linha conforme a INFO e a AÇÃO
                function updateDef($linha) {
                    var isAlreadyMarked = $linha.find('.remover-btn').length > 0;
                    // Converte os valores para caixa alta e remove espaços extras para a comparação
                    var acaoVal = ($linha.find('.select-acao-linha').val() || '').trim().toUpperCase();
                    var infoVal = ($linha.find('.select-info-linha').val() || '').trim().toUpperCase();
                    var defValueOriginal = $linha.attr('data-def_value') || '';
                    var $defSelect = $linha.find('.select-def-linha');

                    // Limpa o select antes de atualizar
                    $defSelect.empty();

                    // Se o registro já existe, mostra o valor do banco e mantém desabilitado
                    if (isAlreadyMarked) {
                        var valLocked = defValueOriginal || '';
                        $defSelect.append('<option value="'+valLocked+'">'+valLocked+'</option>');
                        $defSelect.prop('disabled', true);
                        return;
                    }

                    // Regra 1: Se INFO for PESSOAL ou SENSÍVEL, o DEF deve assumir esse valor e ficar desabilitado.
                    if (infoVal === 'PESSOAL' || infoVal === 'SENSÍVEL') {
                        $defSelect.append('<option value="'+infoVal+'">'+infoVal+'</option>');
                        $defSelect.prop('disabled', true);
                        return;
                    }

                    // Regra 2: Se AÇÃO for NÃO ANONIMIZAR e INFO for CHAVE PRIMÁRIA, CHAVE ESTRANGEIRA ou REGRA DE NEGÓCIO,
                    // ou se INFO for ALTERAÇÃO, habilita o campo DEF com as opções PESSOAL, SENSÍVEL e COMUM (além da opção vazia "Selecione")
                    if ((acaoVal === 'NÃO ANONIMIZAR' &&
                        (infoVal === 'CHAVE PRIMÁRIA' || infoVal === 'CHAVE ESTRANGEIRA' || infoVal === 'REGRA DE NEGÓCIO')) || infoVal === 'ALTERAÇÃO') {
                        $defSelect.prop('disabled', false);
                        var opcoes = ['', 'PESSOAL', 'SENSÍVEL', 'COMUM'];
                        opcoes.forEach(function(opcao) {
                            if (opcao === '') {
                                $defSelect.append('<option value="">Selecione</option>');
                            } else {
                                $defSelect.append('<option value="'+opcao+'">'+opcao+'</option>');
                            }
                        });
                        if (opcoes.includes(defValueOriginal)) {
                            $defSelect.val(defValueOriginal);
                        } else {
                            $defSelect.val('');
                        }
                        return;
                    }

                    // Regra 3: Para os demais cenários, exibe o valor original de lgpd_definicao (ou "Selecione" se estiver vazio) e desabilita o campo.
                    var valFinal = defValueOriginal;
                    if (valFinal === '') {
                        valFinal = 'Selecione';
                    }
                    $defSelect.append('<option value="'+defValueOriginal+'">'+valFinal+'</option>');
                    $defSelect.prop('disabled', true);
                }

                // Quando muda AÇÃO, recarrega o combo INFO e atualiza DEF
                $(document).on('change', '.select-acao-linha', function(){
                    var $linha = $(this).closest('tr');
                    var acaoSel = $(this).val();
                    var $comboInfo = $linha.find('.select-info-linha');

                    // Recarrega as opções de INFO conforme acoesInfosMap
                    $comboInfo.empty();
                    if(!acaoSel || !acoesInfosMap[acaoSel]){
                        $comboInfo.append('<option value="">Selecione...</option>');
                    } else {
                        $comboInfo.append('<option value="">Selecione...</option>');
                        acoesInfosMap[acaoSel].forEach(function(info){
                            $comboInfo.append('<option value="'+info+'">'+info+'</option>');
                        });
                    }

                    updateDef($linha);
                    verificarHabilitarInserir($linha);
                });

                // Quando muda INFO, só atualiza DEF e o botão de inserir
                $(document).on('change', '.select-info-linha', function(){
                    var $linha = $(this).closest('tr');
                    updateDef($linha);
                    verificarHabilitarInserir($linha);
                });

                // Verifica se habilita o botão Inserir
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

                // Evento INSERIR
                $(document).on('click', '.inserir-btn', function(){
                    var btn = $(this);
                    if(btn.prop('disabled')) return;

                    var $linha = btn.closest('tr');
                    var acao_lgpd = $linha.find('.select-acao-linha').val();
                    var lgpd_informacao = $linha.find('.select-info-linha').val();
                    var lgpd_definicao = $linha.find('.select-def-linha').val() || '';

                    if(!acao_lgpd || !lgpd_informacao || !lgpd_definicao){
                        alert('Selecione uma Ação, uma Info e uma DEF antes de inserir!');
                        return;
                    }

                    // Se a ação for NÃO ANONIMIZAR e INFO for CHAVE ESTRANGEIRA/PRIMÁRIA/REGRA DE NEGÓCIO,
                    // verifica se DEF foi selecionado (PESSOAL, SENSÍVEL ou COMUM)
                    if(acao_lgpd === 'NÃO ANONIMIZAR' &&
                       (lgpd_informacao === 'CHAVE ESTRANGEIRA' || lgpd_informacao === 'CHAVE PRIMÁRIA' || lgpd_informacao === 'REGRA DE NEGÓCIO') &&
                       (!lgpd_definicao || (lgpd_definicao !== 'PESSOAL' && lgpd_definicao !== 'SENSÍVEL' && lgpd_definicao !== 'COMUM')))
                    {
                        alert('Para a ação NÃO ANONIMIZAR, selecione um valor válido em DEF (PESSOAL, SENSÍVEL ou COMUM).');
                        return;
                    }

                    var ambiente     = $('#ambiente').val();
                    var service_name = $('#service_name').val();
                    var schema_name  = $('#schema_name').val();
                    var table_name   = $('#table_name').val();

                    // Pega a coluna e remove + e –
                    var column_name = $linha.find('td:nth-child(1)').text().trim();
                    column_name = column_name.replace(/[+–]/g, '').trim();

                    var data_base      = btn.data('data_base') || '';
                    var host_name      = btn.data('host_name') || '';
                    var column_comment = btn.data('column_comment') || '';

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
                            lgpd_definicao: lgpd_definicao,
                            data_base: data_base,
                            host_name: host_name,
                            column_comment: column_comment
                       },
                       dataType: 'json',
                       success: function(response){
                           if(response.success){
                               alert(response.message);
                               btn.removeClass('btn-success inserir-btn')
                                  .addClass('btn-danger remover-btn')
                                  .text('Remover');
                               // Desabilita combos
                               $linha.find('.select-acao-linha').prop('disabled', true);
                               $linha.find('.select-info-linha').prop('disabled', true);
                               $linha.find('.select-def-linha').prop('disabled', true);
                           } else {
                               alert('Erro: ' + response.message);
                           }
                       },
                       error: function(){
                           alert('Erro ao inserir os dados.');
                       }
                    });
                });

                // Evento REMOVER
                $(document).on('click', '.remover-btn', function(){
                    var btn = $(this);
                    var $linha = btn.closest('tr');

                    var ambiente     = $('#ambiente').val();
                    var service_name = $('#service_name').val();
                    var schema_name  = $('#schema_name').val();
                    var table_name   = $('#table_name').val();

                    // Pega a coluna e remove + e –
                    var column_name = $linha.find('td:nth-child(1)').text().trim();
                    column_name = column_name.replace(/[+–]/g, '').trim();

                    var data_base      = btn.data('data_base') || '';
                    var host_name      = btn.data('host_name') || '';
                    var column_comment = btn.data('column_comment') || '';

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
                               btn.removeClass('btn-danger remover-btn')
                                  .addClass('btn-success inserir-btn')
                                  .text('Inserir')
                                  .attr('data-data_base', data_base)
                                  .attr('data-host_name', host_name)
                                  .attr('data-column_comment', column_comment);

                               $linha.find('.select-acao-linha').prop('disabled', false);
                               $linha.find('.select-info-linha').prop('disabled', false);
                               $linha.find('.select-def-linha').prop('disabled', false);

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
