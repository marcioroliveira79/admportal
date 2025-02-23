<?php
session_start();

// Recupera a variável $acao se vier via GET
$acao = isset($_GET['acao']) ? $_GET['acao'] : null;

if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {

    // Verifica se o usuário tem acesso à tela
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Meus Schemas - LGPD</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            .btn[disabled],
            .btn.disabled {
                opacity: 0.1;
                pointer-events: none;
            }
            .form-select.form-select-sm {
                font-size: 11px;
            }
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
            </style>
        </head>
        <body>
            <div class="container">
                <div class="form-container">
                    <div class="form-title"><h3>Meus Schemas - LGPD</h3></div>
                    <form id="filterForm" method="GET" class="row g-3">
                        <input type="hidden" name="acao" value="<?= htmlspecialchars($acao) ?>">

                        <div class="col-md-3">
                            <label for="ambiente" class="form-label">Ambiente</label>
                            <select name="ambiente" id="ambiente" class="form-select form-select-sm" required>
                                <option value="">Carregando...</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="service_name" class="form-label">Service Name</label>
                            <select name="service_name" id="service_name" class="form-select form-select-sm" required disabled>
                                <option value="">Selecione</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="schema_name" class="form-label">Schema</label>
                            <select name="schema_name" id="schema_name" class="form-select form-select-sm" required disabled>
                                <option value="">Selecione</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="table_name" class="form-label">Tabela</label>
                            <select name="table_name" id="table_name" class="form-select form-select-sm" required disabled>
                                <option value="">Selecione</option>
                            </select>
                        </div>
                        <div class="col-md-12 text-end">
                            <button type="button" id="btnConsultar" class="btn btn-success" disabled>Consultar</button>
                        </div>
                    </form>
                </div>

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

                $.ajax({
                    url: 'catalogo_lgpd_schema_user_ajax.php',
                    method: 'GET',
                    data: { action: 'getUserAmbientes' },
                    dataType: 'json',
                    success: function(resp){
                        if(resp.success){
                            var options = '<option value="">Selecione um Ambiente</option>';
                            resp.data.forEach(function(amb){
                                options += '<option value="'+ amb +'">'+ amb +'</option>';
                            });
                            $('#ambiente').html(options);
                        } else {
                            $('#ambiente').html('<option value="">Nenhum Ambiente</option>');
                        }
                    },
                    error: function(){
                        $('#ambiente').html('<option value="">Erro</option>');
                    }
                });

                $('#ambiente').on('change', function(){
                    var ambiente = $(this).val();
                    $('#service_name').html('<option value="">Selecione</option>').prop('disabled', true);
                    $('#schema_name').html('<option value="">Selecione</option>').prop('disabled', true);
                    $('#table_name').html('<option value="">Selecione</option>').prop('disabled', true);
                    $('#btnConsultar').prop('disabled', true);

                    if(ambiente){
                        $.ajax({
                            url: 'catalogo_lgpd_schema_user_ajax.php',
                            method: 'GET',
                            data: { action: 'getUserServices', ambiente: ambiente },
                            dataType: 'json',
                            success: function(resp){
                                if(resp.success){
                                    var options = '<option value="">Selecione</option>';
                                    resp.data.forEach(function(srv){
                                        options += '<option value="'+ srv +'">'+ srv +'</option>';
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
                    $('#schema_name').html('<option value="">Selecione</option>').prop('disabled', true);
                    $('#table_name').html('<option value="">Selecione</option>').prop('disabled', true);
                    $('#btnConsultar').prop('disabled', true);

                    if(service_name){
                        $.ajax({
                            url: 'catalogo_lgpd_schema_user_ajax.php',
                            method: 'GET',
                            data: {
                                action: 'getUserSchemas',
                                ambiente: ambiente,
                                service_name: service_name
                            },
                            dataType: 'json',
                            success: function(resp){
                                if(resp.success){
                                    var options = '<option value="">Selecione</option>';
                                    resp.data.forEach(function(sch){
                                        options += '<option value="'+ sch +'">'+ sch +'</option>';
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
                    $('#table_name').html('<option value="">Selecione</option>').prop('disabled', true);
                    $('#btnConsultar').prop('disabled', true);

                    if(schema_name){
                        $.ajax({
                            url: 'catalogo_lgpd_schema_user_ajax.php',
                            method: 'GET',
                            data: {
                                action: 'getTables',
                                ambiente: ambiente,
                                service_name: service_name,
                                schema_name: schema_name
                            },
                            dataType: 'json',
                            success: function(resp){
                                if(resp.success){
                                    var options = '<option value="">Selecione</option>';
                                    resp.data.forEach(function(tbl){
                                        options += '<option value="'+ tbl +'">'+ tbl +'</option>';
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

                var acoesInfosMap = {};
                var listaAcoesUnicas = [];

                $.ajax({
                    url: 'catalogo_lgpd_schema_user_ajax.php',
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

                $('#btnConsultar').on('click', function(){
                    var ambiente = $('#ambiente').val();
                    var service_name = $('#service_name').val();
                    var schema_name = $('#schema_name').val();
                    var table_name = $('#table_name').val();

                    $.ajax({
                        url: 'catalogo_lgpd_schema_user_ajax.php',
                        method: 'GET',
                        data: {
                            action: 'getAttributes',
                            ambiente: ambiente,
                            service_name: service_name,
                            schema_name: schema_name,
                            table_name: table_name
                        },
                        dataType: 'json',
                        success: function(resp){
                            if($.fn.DataTable.isDataTable('#attributesTable')){
                                $('#attributesTable').DataTable().destroy();
                            }
                            $('#attributesTable tbody').empty();

                            if(resp.success && resp.data.length > 0){
                                var tableComment = resp.data[0].table_comments ? resp.data[0].table_comments : "Sem comentário";
                                $('#tableComment').html(tableComment);

                                resp.data.forEach(function(item){
                                    var comentario = item.column_comments || '';
                                    var aceitaNull = item.is_nullable || '';
                                    var chave = '';
                                    if(item.is_pk === 'Y'){ chave = 'PK'; }
                                    else if(item.is_fk === 'Y'){ chave = 'FK'; }

                                    var dataBase = item.data_base || '';
                                    var hostName = item.host_name || '';
                                    var columnComment = comentario;
                                    var acaoAtual = item.acao_lgpd || '';
                                    var infoAtual = item.lgpd_informacao || '';

                                    var acaoAtualCampo  = item.acao_lgpd_atual || '';
                                    var infoAtualCampo  = item.lgpd_informacao_atual || '';
                                    var atributoRel     = item.atributo_relacionado || '';
                                    var palavraRel      = item.palavra_relacionada || '';

                                    // Novos campos para a sub-linha:
                                    var nomeCriador     = item.nome_usuario_criador || '';
                                    var dataMarcacao    = item.data_criacao_marcacao || '';

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

                                    var acaoButton = '';
                                    var disabledStr = '';
                                    if(acaoAtual && infoAtual){
                                        if(item.fk_lgpd_marcacao && item.fk_lgpd_marcacao !== '0'){
                                            acaoButton = '<button class="btn btn-danger btn-sm remover-btn" '+
                                                         'data-column_comment="'+ columnComment +'" '+
                                                         'data-data_base="'+ dataBase +'" '+
                                                         'data-host_name="'+ hostName +'">Remover</button>';
                                            disabledStr = 'disabled';
                                        } else {
                                            acaoButton = '<button class="btn btn-success btn-sm inserir-btn" '+
                                                         'data-column_comment="'+ columnComment +'" '+
                                                         'data-data_base="'+ dataBase +'" '+
                                                         'data-host_name="'+ hostName +'">Inserir</button>';
                                        }
                                    } else {
                                        acaoButton = '<button class="btn btn-success btn-sm inserir-btn" disabled '+
                                                     'data-column_comment="'+ columnComment +'" '+
                                                     'data-data_base="'+ dataBase +'" '+
                                                     'data-host_name="'+ hostName +'">Inserir</button>';
                                    }

                                    var row = '<tr>'+
                                        firstCell +
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
                                    if(disabledStr){
                                        $rowObj.find('.select-acao-linha').prop('disabled', true);
                                        $rowObj.find('.select-info-linha').prop('disabled', true);
                                    }
                                    $('#attributesTable tbody').append($rowObj);
                                });

                                $('#totalResults').html('Total de resultados: ' + resp.data.length);
                            } else {
                                $('#tableComment').html("Sem comentário");
                                $('#attributesTable tbody').html('<tr><td colspan="9" class="text-center">Nenhum dado encontrado.</td></tr>');
                                $('#totalResults').html('Total de resultados: 0');
                            }

                            var dt = $('#attributesTable').DataTable({ autoWidth: false });
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

                            $('#attributeListContainer').show();
                        }
                    });
                });

                function formatDetails($cell){
                    var atributoRel   = $cell.attr('data-atributo_relacionado') || '';
                    var palavraRel    = $cell.attr('data-palavra_relacionada') || '';
                    var acaoAtual     = $cell.attr('data-acao_atual') || '';
                    var infoAtual     = $cell.attr('data-info_atual') || '';
                    var acaoSugerida  = $cell.attr('data-acao_sugerida') || '';
                    var infoSugerida  = $cell.attr('data-info_sugerida') || '';
                    var nomeCriador   = $cell.attr('data-nome_criador') || '';
                    var dataMarcacao  = $cell.attr('data-data_marcacao') || '';
                    var marcacaoAtual = (acaoAtual || infoAtual) ? (acaoAtual + ' ' + infoAtual) : '';
                    var marcacaoSugerida = (acaoSugerida || infoSugerida) ? (acaoSugerida + ' ' + infoSugerida) : '';
                    var alerta = '';
                    if(marcacaoAtual && marcacaoSugerida && (marcacaoAtual.trim() !== marcacaoSugerida.trim())){
                        alerta = ' ⚠';
                    }
                    var dataMarcacaoFormatada = formatDateTime(dataMarcacao);

                    var html = '<div style="padding:8px;">';
                    html += '<b>Atributo Relacionado:</b> ' + atributoRel + '<br>';
                    html += '<b>Palavra Relacionada:</b> ' + palavraRel + '<br>';
                    html += '<hr>';
                    html += '<b>Marcação atual:</b> ' + (marcacaoAtual || '(vazio)') + '<br>';
                    html += '<b>Marcação sugerida:</b> ' + (marcacaoSugerida || '(vazio)') + alerta + '<br>';
                    html += '<hr>';
                    html += '<b>Adicionado por:</b> ' + (nomeCriador || '(desconhecido)') + '<br>';
                    html += '<b>Data marcação:</b> ' + (dataMarcacaoFormatada || '(vazio)') + '<br>';
                    html += '</div>';
                    return html;
                }

                $(document).on('change', '.select-acao-linha', function(){
                    var $linha = $(this).closest('tr');
                    var acaoSel = $(this).val();
                    var $comboInfo = $linha.find('.select-info-linha');

                    $comboInfo.empty();
                    if(!acaoSel || !acoesInfosMap[acaoSel]){
                        $comboInfo.append('<option value="">Selecione...</option>');
                    } else {
                        $comboInfo.append('<option value="">Selecione...</option>');
                        acoesInfosMap[acaoSel].forEach(function(info){
                            $comboInfo.append('<option value="'+info+'">'+info+'</option>');
                        });
                    }
                    verificarHabilitarInserir($linha);
                });

                $(document).on('change', '.select-info-linha', function(){
                    var $linha = $(this).closest('tr');
                    verificarHabilitarInserir($linha);
                });

                function verificarHabilitarInserir($linha){
                    var acaoVal = $linha.find('.select-acao-linha').val() || '';
                    var infoVal = $linha.find('.select-info-linha').val() || '';
                    var $btn = $linha.find('.inserir-btn');
                    if($btn.length){
                        if(acaoVal && infoVal){
                            $btn.prop('disabled', false);
                        } else {
                            $btn.prop('disabled', true);
                        }
                    }
                }

                $(document).on('click', '.inserir-btn', function(){
                    var btn = $(this);
                    if(btn.prop('disabled')) return;

                    var $linha = btn.closest('tr');
                    var acao_lgpd = $linha.find('.select-acao-linha').val();
                    var lgpd_informacao = $linha.find('.select-info-linha').val();
                    if(!acao_lgpd || !lgpd_informacao){
                        alert('Selecione Ação e Info!');
                        return;
                    }

                    var ambiente = $('#ambiente').val();
                    var service_name = $('#service_name').val();
                    var schema_name = $('#schema_name').val();
                    var table_name = $('#table_name').val();
                    var column_name = $linha.find('td:nth-child(1)').text().trim();
                    column_name = column_name.replace(/[+–]/g, '').trim();
                    var data_base = btn.data('data_base') || '';
                    var host_name = btn.data('host_name') || '';
                    var column_comment = btn.data('column_comment') || '';

                    $.ajax({
                        url: 'catalogo_lgpd_schema_user_ajax.php',
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
                        success: function(resp){
                            if(resp.success){
                                alert(resp.message);
                                btn.removeClass('btn-success inserir-btn')
                                   .addClass('btn-danger remover-btn')
                                   .text('Remover');
                                $linha.find('.select-acao-linha').prop('disabled', true);
                                $linha.find('.select-info-linha').prop('disabled', true);
                            } else {
                                alert('Erro: ' + resp.message);
                            }
                        },
                        error: function(){
                            alert('Erro ao inserir.');
                        }
                    });
                });

                $(document).on('click', '.remover-btn', function(){
                    var btn = $(this);
                    var $linha = btn.closest('tr');

                    var ambiente = $('#ambiente').val();
                    var service_name = $('#service_name').val();
                    var schema_name = $('#schema_name').val();
                    var table_name = $('#table_name').val();
                    var column_name = $linha.find('td:nth-child(1)').text().trim();
                    column_name = column_name.replace(/[+–]/g, '').trim();
                    var data_base = btn.data('data_base') || '';
                    var host_name = btn.data('host_name') || '';
                    var column_comment = btn.data('column_comment') || '';

                    $.ajax({
                        url: 'catalogo_lgpd_schema_user_ajax.php',
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
                        success: function(resp){
                            if(resp.success){
                                alert(resp.message);
                                btn.removeClass('btn-danger remover-btn')
                                   .addClass('btn-success inserir-btn')
                                   .text('Inserir')
                                   .attr('data-data_base', data_base)
                                   .attr('data-host_name', host_name)
                                   .attr('data-column_comment', column_comment);

                                $linha.find('.select-acao-linha').prop('disabled', false);
                                $linha.find('.select-info-linha').prop('disabled', false);

                                var acaoVal = $linha.find('.select-acao-linha').val() || '';
                                var infoVal = $linha.find('.select-info-linha').val() || '';
                                if(!acaoVal || !infoVal){
                                    btn.prop('disabled', true);
                                }
                            } else {
                                alert('Erro: ' + resp.message);
                            }
                        },
                        error: function(){
                            alert('Erro ao remover.');
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
