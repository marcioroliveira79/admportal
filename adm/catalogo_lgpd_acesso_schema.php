<?php
session_start();

// Recupera a variável $acao se vier via GET
$acao = isset($_GET['acao']) ? $_GET['acao'] : null;

if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {

    // Funções de verificação de acesso (exemplo)
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {

        // Carrega ambientes do catalog_vw_lgpd_marcacao
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
            <title>Acesso de Usuários a Schemas</title>
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
            #userAccessContainer {
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
            #userAccessContainer h4 {
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
            #userTable {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }
            #userTable th,
            #userTable td {
                padding: 10px;
                text-align: left;
                border: 1px solid #ddd;
                font-size: 11px;
            }
            #userTable th {
                background-color: #f8f9fa;
                font-weight: bold;
            }
            #userTable tr:hover {
                background-color: #f1f1f1;
            }
            /* Largura das colunas */
            #userTable th:nth-child(1),
            #userTable td:nth-child(1) {
                width: 180px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            #userTable th:nth-child(2),
            #userTable td:nth-child(2) {
                width: 250px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            #userTable th:nth-child(3),
            #userTable td:nth-child(3) {
                width: 100px;
                text-align: center;
                vertical-align: middle;
                padding: 5px;
            }
            .btn[disabled],
            .btn.disabled {
                opacity: 0.3;
                pointer-events: none;
            }

            /* Modal table for details */
            #userDetailTable th,
            #userDetailTable td {
                padding: 8px;
                text-align: left;
                border: 1px solid #ccc;
                font-size: 12px;
            }
            #userDetailTable th {
                background-color: #f8f9fa;
                font-weight: bold;
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

                <!-- Formulário de Filtros: Ambiente, Service, Schema -->
                <div class="form-container">
                    <div class="form-title"><h3>Acesso de Usuários a Schemas</h3></div>
                    <form id="filterForm" method="GET" class="row g-3">
                        <input type="hidden" name="acao" value="<?= htmlspecialchars($acao) ?>">
                        
                        <div class="col-md-3">
                            <label for="ambiente" class="form-label">Ambiente</label>
                            <select name="ambiente" id="ambiente" class="form-select" required>
                                <option value="">Selecione o Ambiente</option>
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
                                <option value="">Selecione</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="schema_name" class="form-label">Schema</label>
                            <select name="schema_name" id="schema_name" class="form-select" required disabled>
                                <option value="">Selecione</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 text-end">
                            <label class="form-label">&nbsp;</label><br/>
                            <button type="button" id="btnConsultar" class="btn btn-success" disabled>Consultar</button>
                        </div>
                    </form>
                </div>

                <!-- HIDDEN inputs para data_base e host_name -->
                <input type="hidden" id="data_base" value="">
                <input type="hidden" id="host_name" value="">

                <!-- Listagem de usuários e seu acesso -->
                <div id="userAccessContainer" class="table-container mt-4" style="display:none;">
                    <h4 id="schemaTitle"></h4>
                    <table id="userTable" class="display">
                        <thead>
                            <tr>
                                <th>NOME</th>
                                <th>EMAIL</th>
                                <th>ACESSO</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div id="totalResults"></div>
                </div>
            </div>

            <!-- MODAL para detalhes do usuário -->
            <div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="userDetailModalLabel">Detalhes de Acesso do Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <table id="userDetailTable" class="table table-striped">
                      <thead>
                        <tr>
                          <th>Ambiente</th>
                          <th>Service Name</th>
                          <th>Schema</th>
                          <th>Data Criação</th>
                          <th>Criado Por</th>
                          <th>Remover</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Bootstrap JS (para modal) -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

            <script>
            $(document).ready(function(){

                // ========== 1) Combos de Ambiente, Service, Schema ============
                $('#ambiente').on('change', function(){
                    var ambiente = $(this).val();
                    $('#service_name').html('<option value="">Selecione</option>').prop('disabled', true);
                    $('#schema_name').html('<option value="">Selecione</option>').prop('disabled', true);
                    $('#btnConsultar').prop('disabled', true);
                    
                    if(ambiente){
                        $.ajax({
                            url: 'catalogo_lgpd_acesso_schema_ajax.php',
                            method: 'GET',
                            data: { action: 'getServiceNames', ambiente: ambiente },
                            dataType: 'json',
                            success: function(response){
                                if(response.success){
                                    var options = '<option value="">Selecione</option>';
                                    $.each(response.data, function(i, val){
                                        options += '<option value="'+val+'">'+val+'</option>';
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
                    $('#btnConsultar').prop('disabled', true);

                    if(service_name){
                        $.ajax({
                            url: 'catalogo_lgpd_acesso_schema_ajax.php',
                            method: 'GET',
                            data: { action: 'getSchemas', ambiente: ambiente, service_name: service_name },
                            dataType: 'json',
                            success: function(response){
                                if(response.success){
                                    var options = '<option value="">Selecione</option>';
                                    $.each(response.data, function(i, val){
                                        options += '<option value="'+val+'">'+val+'</option>';
                                    });
                                    $('#schema_name').html(options).prop('disabled', false);
                                }
                            }
                        });
                    }
                });

                $('#schema_name').on('change', function(){
                    if($(this).val()){
                        $('#btnConsultar').prop('disabled', false);
                    } else {
                        $('#btnConsultar').prop('disabled', true);
                    }
                });

                // ========== 2) Ao clicar em Consultar ============
                $('#btnConsultar').on('click', function(){
                    var $btn = $(this);
                    var ambiente = $('#ambiente').val();
                    var service_name = $('#service_name').val();
                    var schema_name = $('#schema_name').val();

                    // Mostra spinner no botão
                    $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Consultando...')
                        .prop('disabled', true);

                    // Primeiro, buscar data_base e host_name
                    $.ajax({
                        url: 'catalogo_lgpd_acesso_schema_ajax.php',
                        method: 'GET',
                        data: {
                            action: 'getDbHost',
                            ambiente: ambiente,
                            service_name: service_name,
                            schema_name: schema_name
                        },
                        dataType: 'json',
                        success: function(resp){
                            if(resp.success){
                                $('#data_base').val(resp.data.data_base);
                                $('#host_name').val(resp.data.host_name);
                            } else {
                                $('#data_base').val('');
                                $('#host_name').val('');
                            }
                        },
                        error: function(){
                            $('#data_base').val('');
                            $('#host_name').val('');
                        },
                        complete: function(){
                            getUsersAccess(ambiente, service_name, schema_name, function(){
                                $btn.html('Consultar').prop('disabled', false);
                            });
                        }
                    });
                });

                function getUsersAccess(ambiente, service_name, schema_name, callback){
                    $.ajax({
                        url: 'catalogo_lgpd_acesso_schema_ajax.php',
                        method: 'GET',
                        data: {
                            action: 'getUsersForSchemaAccess',
                            ambiente: ambiente,
                            service_name: service_name,
                            schema_name: schema_name
                        },
                        dataType: 'json',
                        success: function(response){
                            if($.fn.DataTable.isDataTable('#userTable')){
                                $('#userTable').DataTable().destroy();
                            }
                            $('#userTable tbody').empty();

                            $('#schemaTitle').text('Acesso de Usuários ao Schema: ' + schema_name);

                            if(response.success && response.data.length > 0){
                                $.each(response.data, function(index, user){
                                    // user: { id, nome_completo, email, has_access }

                                    // Aqui criamos um link no NOME
                                    var userLink = '<a href="#" class="user-details-link" data-user-id="'+ user.id +'">'+
                                                   (user.nome_completo || '') +'</a>';

                                    var btnAccess = '';
                                    if(user.has_access === true){
                                        btnAccess = '<button class="btn btn-danger btn-sm remover-btn">Remover</button>';
                                    } else {
                                        btnAccess = '<button class="btn btn-success btn-sm inserir-btn">Inserir</button>';
                                    }

                                    var row = '<tr data-user-id="'+ user.id +'">'+
                                        '<td>'+ userLink +'</td>'+ // link no nome
                                        '<td>'+ (user.email || '') +'</td>'+
                                        '<td>'+ btnAccess +'</td>'+
                                    '</tr>';
                                    $('#userTable tbody').append(row);
                                });
                                $('#totalResults').html('Total de usuários: ' + response.data.length);
                            } else {
                                $('#schemaTitle').text('Acesso de Usuários ao Schema: ' + schema_name + ' (Nenhum usuário encontrado)');
                                $('#userTable tbody').html('<tr><td colspan="3" class="text-center">Nenhum usuário encontrado.</td></tr>');
                                $('#totalResults').html('Total de usuários: 0');
                            }

                            $('#userAccessContainer').show();
                            $('#userTable').DataTable({ autoWidth: false });
                        },
                        complete: function(){
                            if(typeof callback === 'function'){
                                callback();
                            }
                        }
                    });
                }

                // ========== 3) Clique em Inserir (dar acesso) ===========
                $(document).on('click', '.inserir-btn', function(){
                    var btn = $(this);
                    var linha = btn.closest('tr');
                    var userId = linha.data('user-id');

                    var ambiente = $('#ambiente').val();
                    var service_name = $('#service_name').val();
                    var schema_name = $('#schema_name').val();
                    var data_base = $('#data_base').val() || '';
                    var host_name = $('#host_name').val() || '';

                    $.ajax({
                        url: 'catalogo_lgpd_acesso_schema_ajax.php',
                        method: 'GET',
                        data: {
                            action: 'insertAccess',
                            ambiente: ambiente,
                            data_base: data_base,
                            host_name: host_name,
                            service_name: service_name,
                            schema_name: schema_name,
                            fk_usuario: userId
                        },
                        dataType: 'json',
                        success: function(resp){
                            if(resp.success){
                                alert(resp.message);
                                // Troca botão para Remover
                                btn.removeClass('btn-success inserir-btn')
                                   .addClass('btn-danger remover-btn')
                                   .text('Remover');
                            } else {
                                alert('Erro: ' + resp.message);
                            }
                        },
                        error: function(){
                            alert('Erro ao inserir acesso.');
                        }
                    });
                });

                // ========== 4) Clique em Remover (revogar acesso) ===========
                $(document).on('click', '.remover-btn', function(){
                    var btn = $(this);
                    var linha = btn.closest('tr');
                    var userId = linha.data('user-id');

                    var ambiente = $('#ambiente').val();
                    var service_name = $('#service_name').val();
                    var schema_name = $('#schema_name').val();
                    var data_base = $('#data_base').val() || '';
                    var host_name = $('#host_name').val() || '';

                    $.ajax({
                        url: 'catalogo_lgpd_acesso_schema_ajax.php',
                        method: 'GET',
                        data: {
                            action: 'removeAccess',
                            ambiente: ambiente,
                            data_base: data_base,
                            host_name: host_name,
                            service_name: service_name,
                            schema_name: schema_name,
                            fk_usuario: userId
                        },
                        dataType: 'json',
                        success: function(resp){
                            if(resp.success){
                                alert(resp.message);
                                // Troca botão para Inserir
                                btn.removeClass('btn-danger remover-btn')
                                   .addClass('btn-success inserir-btn')
                                   .text('Inserir');
                            } else {
                                alert('Erro: ' + resp.message);
                            }
                        },
                        error: function(){
                            alert('Erro ao remover acesso.');
                        }
                    });
                });

                // ========== 5) Ao clicar no Nome do usuário -> abre modal com todos os acessos =============
                $(document).on('click', '.user-details-link', function(e){
                    e.preventDefault();
                    var userId = $(this).data('user-id');

                    // Abre o modal
                    var myModal = new bootstrap.Modal(document.getElementById('userDetailModal'), {
                        keyboard: false
                    });
                    myModal.show();

                    // Limpa a tabela
                    $('#userDetailTable tbody').empty();

                    // Chama AJAX para buscar todos os acessos do usuário
                    $.ajax({
                        url: 'catalogo_lgpd_acesso_schema_ajax.php',
                        method: 'GET',
                        data: {
                            action: 'getUserDetailAccess',
                            fk_usuario: userId
                        },
                        dataType: 'json',
                        success: function(resp){
                            if(resp.success && resp.data.length > 0){
                                // Preenche a tabela
                                $.each(resp.data, function(index, item){
                                    // item: { id, ambiente, service_name, schema_name, data_criacao, criador_nome }
                                    var row = '<tr data-access-id="'+ item.id +'">'+
                                        '<td>'+ (item.ambiente || '') +'</td>'+
                                        '<td>'+ (item.service_name || '') +'</td>'+
                                        '<td>'+ (item.schema_name || '') +'</td>'+
                                        '<td>'+ (item.data_criacao || '') +'</td>'+
                                        '<td>'+ (item.criador_nome || '') +'</td>'+
                                        '<td><button class="btn btn-danger btn-sm remove-detail-access" data-access-id="'+ item.id +'">Remover</button></td>'+
                                    '</tr>';
                                    $('#userDetailTable tbody').append(row);
                                });
                            } else {
                                $('#userDetailTable tbody').html('<tr><td colspan="6" class="text-center">Nenhum acesso encontrado.</td></tr>');
                            }
                        },
                        error: function(){
                            alert('Erro ao buscar detalhes de acesso.');
                        }
                    });
                });

                // ========== 6) Clique em Remover dentro do modal de detalhes =============
                $(document).on('click', '.remove-detail-access', function(){
                    var accessId = $(this).data('access-id');
                    var btn = $(this);

                    if(!accessId) return;

                    // Remove via AJAX
                    $.ajax({
                        url: 'catalogo_lgpd_acesso_schema_ajax.php',
                        method: 'GET',
                        data: {
                            action: 'removeAccessById',
                            id: accessId
                        },
                        dataType: 'json',
                        success: function(resp){
                            if(resp.success){
                                alert(resp.message);
                                // Remove a linha
                                btn.closest('tr').remove();
                            } else {
                                alert('Erro: ' + resp.message);
                            }
                        },
                        error: function(){
                            alert('Erro ao remover acesso (detalhes).');
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
