<?php
session_start();

// Exemplo de checagem de sessão e acesso:
$acao = isset($_GET['acao']) ? $_GET['acao'] : null;
if (!isset($_SESSION['global_id_usuario']) || empty($_SESSION['global_id_usuario']) || !$acao) {
    header("Location: login.php");
    exit;
}

// Verifica acesso (exemplo):
$acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
if ($acesso != "TELA AUTORIZADA") {
    @include("html/403.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Catálogo estilo SQL Developer</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Ícones (usando Font Awesome) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    /* Garante que html/body ocupem 100% da tela */
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
    }
    body {
      background-color: #f5f5f5;
      font-family: Arial, sans-serif;
    }
    /* .container-fluid ajustado para ocupar 100% */
    .container-fluid {
      height: 100%;
      display: flex;        /* permite colocar sidebar, dragBar e mainContent lado a lado */
      flex-direction: row;
    }
    /* Sidebar inicial: 400px, mas será ajustado ao arrastar */
    #sidebar {
      background-color: #ffffff;
      border-right: 1px solid #ddd;
      width: 400px; /* Largura inicial */
      padding: 15px;
      box-shadow: 2px 0 5px rgba(0,0,0,0.1);
      height: 100%;
      overflow-y: auto;
      box-sizing: border-box;
    }
    #sidebar h5 {
      margin-bottom: 20px;
      font-weight: bold;
      color: #4a4a4a;
    }
    /* Formulário de busca */
    .search-form {
      margin-top: 10px;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background-color: #f9f9f9;
    }
    /* Drag bar */
    #dragBar {
      width: 5px;
      cursor: col-resize;
      background-color: #ccc;
      /* Para garantir que ocupe 100% da altura */
      height: 100%;
    }
    /* Legenda de Data de Coleta na sidebar */
    #timeLegend {
      margin-top: 20px;
      font-size: 12px;
    }
    #timeLegend i {
      margin-right: 5px;
    }
    /* Tree nodes */
    .tree-node {
      cursor: pointer;
      margin: 5px 0;
      display: flex;
      align-items: center;
      white-space: nowrap;
    }
    .tree-node i {
      margin-right: 8px;
    }
    /* Child nodes */
    .tree-children {
      margin-left: 20px;
      display: none;
    }
    .expanded > .tree-children {
      display: block;
    }
    /* Main content flex:1 para ocupar o resto */
    #mainContent {
      flex: 1;
      padding: 20px;
      overflow: auto;
    }
    #mainContent h4 {
      font-weight: bold;
      margin-bottom: 20px;
      color: #4a4a4a;
    }
    /* Details container */
    #detailsContainer {
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      padding: 20px;
      min-height: 200px;
    }
    /* Details tables */
    #detailsTable,
    #columnsTable {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    #detailsTable th,
    #detailsTable td,
    #columnsTable th,
    #columnsTable td {
      padding: 8px;
      text-align: left;
      border: 1px solid #ddd;
      font-size: 12px;
    }
    #detailsTable th,
    #columnsTable th {
      background-color: #f8f9fa;
      font-weight: bold;
    }
    /* Reduzir largura das colunas Ambiente, Service e Schema */
    #detailsTable th:nth-child(1),
    #detailsTable th:nth-child(2),
    #detailsTable th:nth-child(3) {
      width: 120px;
      white-space: nowrap;
    }
    /* Loading */
    .loading {
      text-align: center;
      font-style: italic;
      color: #888;
      padding: 20px;
    }
    /* Legenda de chaves nos detalhes */
    #keyLegend {
      margin-top: 10px;
      font-size: 12px;
    }
    #keyLegend i {
      margin-right: 5px;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <!-- Sidebar -->
    <div id="sidebar">
      <!-- Título e botão para expandir/ocultar a busca -->
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0"><i class="fa-solid fa-database"></i> Ambientes</h5>
        <!-- Botão que expande/colapsa o formulário de busca -->
        <button class="btn btn-sm btn-outline-secondary" type="button"
                data-bs-toggle="collapse" data-bs-target="#searchContainer"
                aria-expanded="false" aria-controls="searchContainer">
          <i class="fa fa-search"></i>
        </button>
      </div>

      <!-- Formulário de busca (inicia colapsado) -->
      <div class="collapse" id="searchContainer">
        <div class="search-form">
          <div class="mb-2">
            <label for="ambienteBusca" class="form-label"><strong>Ambiente</strong></label>
            <select id="ambienteBusca" class="form-select form-select-sm">
              <!-- Será preenchido dinamicamente via AJAX -->
              <option value="">Carregando...</option>
            </select>
          </div>
          <div class="mb-2">
            <label for="tipoBusca" class="form-label"><strong>Tipo de busca</strong></label>
            <select id="tipoBusca" class="form-select form-select-sm">
              <option value="">Selecione...</option>
              <option value="schema">Schema</option>
              <option value="table">Tabela</option>
              <option value="attribute">Atributo</option>
            </select>
          </div>
          <div class="mb-2">
            <label for="searchText" class="form-label"><strong>Buscar</strong></label>
            <input type="text" class="form-control form-control-sm" id="searchText" placeholder="Use % para wildcard...">
          </div>
          <!-- Botão de busca verde -->
          <button class="btn btn-success btn-sm" id="btnSearch">Buscar</button>
        </div>
      </div>

      <div id="treeContainer"></div>
      <!-- Legenda de Data de Coleta -->
      <div id="timeLegend">
        <p><strong>Legenda de Data de Coleta:</strong></p>
        <p><i class="fa fa-server text-primary"></i> Coletado nas últimas 24 horas</p>
        <p><i class="fa fa-server text-warning"></i> Coletado entre 24 e 48 horas</p>
        <p><i class="fa fa-server text-danger"></i> Coletado há mais de 48 horas</p>
      </div>
    </div>

    <!-- Divisor para redimensionar -->
    <div id="dragBar"></div>

    <!-- Main Content -->
    <div id="mainContent">
      <!-- Cabeçalho que mostra o caminho completo da tabela -->
      <h4 id="tablePath">Detalhes</h4>
      <div id="detailsContainer">
        <p>Selecione algo no painel esquerdo para ver detalhes aqui...</p>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
  // Script para redimensionar o sidebar
  let isResizing = false;
  let startX = 0;
  let startWidth = 0;

  const sidebar = document.getElementById('sidebar');
  const dragBar = document.getElementById('dragBar');

  dragBar.addEventListener('mousedown', function(e) {
    isResizing = true;
    startX = e.clientX;
    // Largura atual do sidebar
    startWidth = parseInt(window.getComputedStyle(sidebar, null).getPropertyValue('width'), 10);
  });

  document.addEventListener('mousemove', function(e) {
    if(!isResizing) return;
    const dx = e.clientX - startX;
    const newWidth = startWidth + dx;
    if(newWidth > 200 && newWidth < 1000) {
      sidebar.style.width = newWidth + 'px';
    }
  });

  document.addEventListener('mouseup', function() {
    isResizing = false;
  });

  $(document).ready(function(){

    // 1) Preenche dinamicamente a combo de ambientes
    loadAmbientes();

    // 2) Carrega a hierarquia inicial
    loadHierarchy();

    // Clique do botão "Buscar"
    $('#btnSearch').on('click', function(){
      var amb = $('#ambienteBusca').val();
      var tipo = $('#tipoBusca').val();
      var txt = $('#searchText').val();

      if(!amb || !tipo || !txt) {
        alert("Preencha todos os campos da busca!");
        return;
      }

      // Reseta o título do path para indicar busca
      $('#tablePath').text('Resultados da busca');

      // Faz a chamada AJAX para a busca
      $.ajax({
        url: 'catalogo_sql_dev_style_ajax.php',
        method: 'GET',
        data: {
          action: 'search',
          ambiente: amb,
          tipo: tipo,
          texto: txt
        },
        dataType: 'json',
        success: function(resp) {
          if(resp.success) {
            var results = resp.data;
            // Define rótulo para exibir se é schema, tabela ou atributo
            var tipoNome = (tipo === 'schema') ? 'Schemas' :
                           (tipo === 'table') ? 'Tabelas' :
                           (tipo === 'attribute') ? 'Atributos' : 'Desconhecido';
            var html = '<p><strong>' + results.length + ' resultado(s) encontrado(s) para ' + tipoNome + ':</strong></p>';
            
            if(results.length === 0) {
              $('#detailsContainer').html(html);
              return;
            }
            // Monta listagem
            html += '<ul>';
            results.forEach(function(r){
              if(tipo === 'schema') {
                // Exemplo: HOMOLOGACAO/DBGCIHO/DBCONSTRUTORA/
                html += '<li>' + r.ambiente + '/' + r.service_name + '/' + r.schema_name + '/</li>';
              } else {
                // Se for tabela ou atributo, adiciona link
                // Exemplo: HOMOLOGACAO/DBGCIHO/DBCONSTRUTORA/RCF_CONTRATO
                var pathText = r.ambiente + '/' + r.service_name + '/' + r.schema_name + '/' + r.table_name;
                html += '<li><a href="#" class="tableLink" data-amb="' + r.ambiente + '" data-srv="' + r.service_name + '" data-sch="' + r.schema_name + '" data-tbl="' + r.table_name + '">' + pathText + '</a></li>';
              }
            });
            html += '</ul>';

            $('#detailsContainer').html(html);

            // Quando clicar no link, carrega o detalhe
            $('.tableLink').on('click', function(e){
              e.preventDefault();
              var amb = $(this).data('amb');
              var srv = $(this).data('srv');
              var sch = $(this).data('sch');
              var tbl = $(this).data('tbl');
              showTableDetails(amb, srv, sch, tbl);
            });
          } else {
            $('#detailsContainer').html('<p>Erro na busca: ' + resp.data + '</p>');
          }
        },
        error: function() {
          $('#detailsContainer').html('<p>Erro ao realizar a busca.</p>');
        }
      });
    });

    function loadAmbientes(){
      // Chama a nova ação getAmbientes para preencher o combo
      $.ajax({
        url: 'catalogo_sql_dev_style_ajax.php',
        method: 'GET',
        data: { action: 'getAmbientes' },
        dataType: 'json',
        success: function(resp) {
          if(resp.success) {
            var ambientes = resp.data; // array de strings
            var $select = $('#ambienteBusca');
            $select.empty(); // limpa
            $select.append('<option value="">Selecione...</option>');
            ambientes.forEach(function(amb){
              $select.append('<option value="' + amb + '">' + amb + '</option>');
            });
          } else {
            // Se falhar, deixa o combo com erro
            $('#ambienteBusca').html('<option value="">Erro ao carregar</option>');
          }
        },
        error: function() {
          $('#ambienteBusca').html('<option value="">Erro ao carregar</option>');
        }
      });
    }

    function loadHierarchy(){
      $.ajax({
        url: 'catalogo_sql_dev_style_ajax.php',
        method: 'GET',
        data: { action: 'getHierarchy' },
        dataType: 'json',
        success: function(resp) {
          if(resp.success) {
            buildTree(resp.data);
          } else {
            $('#treeContainer').html('<p>Não há dados de conexão.</p>');
          }
        },
        error: function() {
          $('#treeContainer').html('<p>Erro ao carregar conexões.</p>');
        }
      });
    }

    function buildTree(data) {
      var $ul = $('<ul class="list-unstyled"></ul>');
      data.forEach(function(ambObj){
        var envDate = new Date(ambObj.date_collect);
        var now = new Date();
        var diffHours = (now - envDate) / (1000 * 60 * 60);
        var envIconClass = "fa-server";
        if(diffHours < 24) {
          envIconClass += " text-primary";
        } else if(diffHours < 48) {
          envIconClass += " text-warning";
        } else {
          envIconClass += " text-danger";
        }
        var ambLabel = ambObj.ambiente + " (" + (ambObj.children ? ambObj.children.length : 0) + ")";
        if(diffHours >= 48) {
          ambLabel += " - " + new Date(ambObj.date_collect).toLocaleDateString();
        }
        var $liAmb = createTreeNode(envIconClass, ambLabel);
        $liAmb.children('.tree-node').on('click', function(e){
          e.stopPropagation();
          $(this).parent().toggleClass('expanded');
        });
        if(ambObj.children && ambObj.children.length > 0){
          var $childUL = $('<ul class="tree-children list-unstyled"></ul>');
          ambObj.children.forEach(function(serviceObj){
            var serviceLabel = serviceObj.service_name + " (" + (serviceObj.children ? serviceObj.children.length : 0) + ")";
            var $liSrv = createTreeNode('fa-database text-success', serviceLabel);
            $liSrv.children('.tree-node').on('click', function(e){
              e.stopPropagation();
              $(this).parent().toggleClass('expanded');
            });
            if(serviceObj.children && serviceObj.children.length > 0){
              var $childUL2 = $('<ul class="tree-children list-unstyled"></ul>');
              serviceObj.children.forEach(function(schemaObj){
                var schemaLabel = schemaObj.schema_name + " (" + (schemaObj.children ? schemaObj.children.length : 0) + ")";
                var $liSch = createTreeNode('fa-sitemap text-warning', schemaLabel);
                $liSch.children('.tree-node').on('click', function(e){
                  e.stopPropagation();
                  $(this).parent().toggleClass('expanded');
                });
                if(schemaObj.children && schemaObj.children.length > 0){
                  var $childUL3 = $('<ul class="tree-children list-unstyled"></ul>');
                  schemaObj.children.forEach(function(tblObj){
                    var tableLabel = tblObj.table_name + " (" + (tblObj.columns_count || 0) + ")";
                    // Se a tabela ou algum atributo estiver sem comentário, adiciona o ícone de alerta
                    if(tblObj.missing_descriptions) {
                      tableLabel += ' <i class="fa fa-exclamation-triangle text-danger" title="Tabela com falta de descrições"></i>';
                    }
                    var $liTbl = createTreeNode('fa-table text-info', tableLabel);
                    $liTbl.children('.tree-node').on('click', function(e){
                      e.stopPropagation();
                      showTableDetails(
                        ambObj.ambiente,
                        serviceObj.service_name,
                        schemaObj.schema_name,
                        tblObj.table_name
                      );
                    });
                    $childUL3.append($liTbl);
                  });
                  $liSch.append($childUL3);
                }
                $childUL2.append($liSch);
              });
              $liSrv.append($childUL2);
            }
            $childUL.append($liSrv);
          });
          $liAmb.append($childUL);
        }
        $ul.append($liAmb);
      });
      $('#treeContainer').empty().append($ul);
    }

    function createTreeNode(iconClass, labelText){
      var $li = $('<li class="mb-1"></li>');
      var $div = $('<div class="tree-node"></div>');
      
      // Se for tabela, define um title sem tags HTML
      if(iconClass.indexOf("fa-table") !== -1) {
        var plainText = labelText.replace(/<[^>]+>/g, '');
        $div.attr("title", plainText);
      }

      $div.append('<i class="fa ' + iconClass + '"></i> ' + labelText);
      $li.append($div);
      return $li;
    }

    function showTableDetails(ambiente, serviceName, schemaName, tableName){
      var svc = serviceName.replace(/\(.*?\)/, '').trim();
      var path = ambiente + '/' + svc + '/' + schemaName + '/' + tableName;
      $('#tablePath').text(path);
      $('#detailsContainer').html('<div class="loading">Carregando...</div>');
      $.ajax({
        url: 'catalogo_sql_dev_style_ajax.php',
        method: 'GET',
        data: {
          action: 'getTableDetails',
          ambiente: ambiente,
          service_name: serviceName,
          schema_name: schemaName,
          table_name: tableName
        },
        dataType: 'json',
        success: function(resp){
          if(resp.success && resp.data){
            var tbl = resp.data;
            var html = '<table id="detailsTable">';
            html += '<tr><th>Ambiente</th><td>' + (tbl.ambiente || '') + '</td></tr>';
            html += '<tr><th>Service Name</th><td>' + (tbl.service_name || '') + '</td></tr>';
            html += '<tr><th>Schema</th><td>' + (tbl.schema_name || '') + '</td></tr>';
            html += '<tr><th>Tabela</th><td>' + (tbl.table_name || '') + '</td></tr>';

            // Comentário da tabela
            var tableComment = tbl.table_comments || '';
            if(!tbl.table_comments) {
              tableComment = '<i class="fa fa-exclamation-triangle text-danger" title="Tabela com falta de descrições"></i>';
            }
            html += '<tr><th>Comentário</th><td>' + tableComment + '</td></tr>';

            // Data de Criação e Últ. DDL
            html += '<tr><th>Data de Criação</th><td>' + formatDateTime(tbl.table_creation_date) + '</td></tr>';
            html += '<tr><th>Últ. DDL aplicado</th><td>' + formatDateTime(tbl.table_last_ddl_time) + '</td></tr>';

            // Qtd. de Registros (formatado)
            var recordCountFormatted = '0';
            if(tbl.record_count) {
              recordCountFormatted = parseInt(tbl.record_count, 10).toLocaleString('pt-BR');
            }
            html += '<tr><th>Qtd. de Registros</th><td>' + recordCountFormatted + '</td></tr>';

            html += '</table>';
            
            // Exibe as colunas
            if(tbl.columns && tbl.columns.length > 0) {
              html += '<h5></h5>';
              html += '<table id="columnsTable">';
              html += '<tr><th>Coluna</th><th>Tipo</th><th>Tamanho</th><th>Nulo</th><th>Único</th><th>Chave</th><th>Comentários</th></tr>';
              tbl.columns.forEach(function(col){
                var chave = '';
                if(col.is_pk === 'Y' && col.is_fk === 'Y') {
                  chave = '<i class="fa fa-key" style="color:gold;"></i> <i class="fa fa-key" style="color:green;"></i>';
                } else if(col.is_pk === 'Y') {
                  chave = '<i class="fa fa-key" style="color:gold;"></i>';
                } else if(col.is_fk === 'Y') {
                  chave = '<i class="fa fa-key" style="color:green;"></i>';
                }

                var colComment = col.column_comments;
                if(!col.column_comments) {
                  colComment = '<i class="fa fa-exclamation-triangle text-danger" title="Falta descrição para este atributo"></i>';
                }

                html += '<tr>';
                html += '<td>' + (col.column_name || '') + '</td>';
                html += '<td>' + (col.data_type || '') + '</td>';
                html += '<td>' + (col.data_length || '') + '</td>';
                html += '<td>' + (col.is_nullable || '') + '</td>';
                html += '<td>' + (col.is_unique || '') + '</td>';
                html += '<td>' + chave + '</td>';
                html += '<td>' + colComment + '</td>';
                html += '</tr>';
              });
              html += '</table>';
              // Legenda de chaves abaixo da tabela de atributos
              html += '<div id="keyLegend"><p><small><i class="fa fa-key" style="color:gold;"></i> = PK &nbsp;&nbsp; <i class="fa fa-key" style="color:green;"></i> = FK</small></p></div>';
            }
            $('#detailsContainer').html(html);
          } else {
            $('#detailsContainer').html('<p>Nenhum detalhe encontrado.</p>');
          }
        },
        error: function(){
          $('#detailsContainer').html('<p>Erro ao obter detalhes da tabela.</p>');
        }
      });
    }

    function formatDateTime(dt) {
      if(!dt) return '';
      var d = new Date(dt);
      return d.toLocaleString();
    }
  });
  </script>
</body>
</html>
