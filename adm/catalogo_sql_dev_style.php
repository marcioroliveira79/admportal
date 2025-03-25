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
          <title>Catálogo estilo SQL Developer</title>
          <!-- Bootstrap CSS -->
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
          <!-- jQuery -->
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
          <!-- Ícones (usando Font Awesome) -->
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
          <!-- Chart.js -->
          <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
          <!-- Mermaid (para diagramas) -->
          <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
          <!-- D3.js (para zoom/pan no diagrama do schema) -->
          <script src="https://d3js.org/d3.v7.min.js"></script>
          <script>
            // Inicializa o Mermaid sem processar automaticamente
            mermaid.initialize({
              maxEdges: 1000,
              startOnLoad: true
            });
          </script>
          <script>
            if (window.top === window.self) {
                // Se a página não estiver sendo exibida dentro de um iframe, redireciona para o index
                window.location.href = 'index.php';
            }
        </script>
          
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
            .container-fluid {
              height: 100%;
              display: flex;
              flex-direction: row;
            }
            #sidebar {
              background-color: #ffffff;
              border-right: 0px solid #ddd;
              width: 400px;
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
            .search-form {
              margin-top: 10px;
              padding: 10px;
              border: 1px solid #ddd;
              border-radius: 5px;
              background-color: #f9f9f9;
            }
            #ambientesLoader {
              font-size: 12px;
              color: #888;
              margin-top: 5px;
              display: block;
            }
            #dragBar {
              width: 5px;
              cursor: col-resize;
              background-color: #ccc;
              height: 100%;
            }
            #timeLegend {
              margin-top: 20px;
              font-size: 12px;
            }
            #timeLegend i {
              margin-right: 5px;
            }
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
            .tree-children {
              margin-left: 20px;
              display: none;
            }
            .expanded > .tree-children {
              display: block;
            }
            #mainContent {
              flex: 1;
              padding: 20px;
              overflow: auto;
              padding-bottom: 50px;
            }
            #mainContent h4 {
              font-weight: bold;
              margin-bottom: 20px;
              color: #4a4a4a;
            }
            #detailsContainer {
              background-color: #ffffff;
              border-radius: 10px;
              box-shadow: 0 4px 6px rgba(0,0,0,0.1);
              padding: 20px;
              min-height: 200px;
              padding-bottom: 50px;
            }
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
            #detailsTable th:nth-child(1),
            #detailsTable th:nth-child(2),
            #detailsTable th:nth-child(3) {
              width: 120px;
              white-space: nowrap;
            }
            .loading {
              text-align: center;
              font-style: italic;
              color: #888;
              padding: 20px;
            }
            #keyLegend {
              margin-top: 10px;
              font-size: 12px;
            }
            #keyLegend i {
              margin-right: 5px;
            }
            #relationshipContainer {
              margin-bottom: 30px;
            }
            #growthChart {
              display: block;
              margin-bottom: 30px;
            }
            .mermaid svg {
              cursor: grab;
            }
          </style>
        </head>
        <body>
          <div class="container-fluid">
            <div id="sidebar">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Explorar</h5>
                <button class="btn btn-sm btn-outline-secondary" type="button"
                        data-bs-toggle="collapse" data-bs-target="#searchContainer"
                        aria-expanded="false" aria-controls="searchContainer">
                  <i class="fa fa-search"></i>
                </button>
              </div>
              <div class="collapse" id="searchContainer">
                <div class="search-form">
                  <div class="mb-2">
                    <label for="ambienteBusca" class="form-label"><strong>Ambiente</strong></label>
                    <select id="ambienteBusca" class="form-select form-select-sm">
                      <option value="">Carregando...</option>
                    </select>
                    <div id="ambientesLoader">Carregando ambientes...</div>
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
                  <button class="btn btn-success btn-sm" id="btnSearch">Buscar</button>
                </div>
              </div>
              <div id="treeContainer"></div>
              <div id="timeLegend">
                <p><strong>Legenda de Data de Coleta:</strong></p>
                <p><i class="fa fa-server text-primary"></i> Coletado nas últimas 24 horas</p>
                <p><i class="fa fa-server text-warning"></i> Coletado entre 24 e 48 horas</p>
                <p><i class="fa fa-server text-danger"></i> Coletado há mais de 48 horas</p>
              </div>
            </div>
            <div id="dragBar"></div>
            <div id="mainContent">
              <h4 id="tablePath">Selecione um item para visualizar o caminho</h4>
              <div id="detailsContainer">
                <p>Selecione algo no painel esquerdo para ver detalhes aqui...</p>
              </div>
            </div>
          </div>
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
          <script>
          // Funções Auxiliares
          function toTitleCase(str) {
            return str.toLowerCase().replace(/\b\w/g, function(letter) { return letter.toUpperCase(); });
          }
          function formatCodeBlock(code, removeExtraLines = true, leaveOneBlankLine = false) {
            if (removeExtraLines) {
              if (leaveOneBlankLine) {
                code = code.replace(/(\n\s*\n\s*)+/g, "\n\n");
              } else {
                code = code.replace(/(\n\s*\n\s*)+/g, "\n");
              }
            }
            var lines = code.split("\n");
            var lineNumbersHtml = '';
            for (var i = 0; i < lines.length; i++) {
              lineNumbersHtml += (i + 1) + '<br>';
            }
            var codeHtml = lines.join("<br>");
            var html = '<div style="display: flex; font-family: monospace; background-color:#f8f9fa; padding:15px; border-radius:5px;">';
            html += '<div style="text-align: right; padding-right: 10px; border-right: 1px solid #ddd;">' + lineNumbersHtml + '</div>';
            html += '<div style="padding-left: 10px; white-space: pre-wrap;">' + codeHtml + '</div>';
            html += '</div>';
            return html;
          }
          function sanitizeMermaid(str) {
            let s = String(str || '').replace(/[\n\r]+/g, ' ');
            s = s.replace(/"/g, '\\"');
            s = s.replace(/[^A-Za-z0-9_\.,:\- ]/g, '_');
            return s;
          }
          function formatDateTime(dt) {
            if(!dt) return '';
            var d = new Date(dt);
            return d.toLocaleString();
          }
          function formatBytes(bytes) {
            if (isNaN(bytes) || bytes <= 0) return "0 Bytes";
            const k = 1024;
            const sizes = ["Bytes", "KB", "MB", "GB", "TB"];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            const index = (i < sizes.length) ? i : sizes.length - 1;
            const value = bytes / Math.pow(k, index);
            return value.toFixed(2) + " " + sizes[index];
          }
          // Redimensionar Sidebar
          let isResizing = false;
          let startX = 0;
          let startWidth = 0;
          const sidebar = document.getElementById('sidebar');
          const dragBar = document.getElementById('dragBar');
          dragBar.addEventListener('mousedown', function(e) {
            isResizing = true;
            startX = e.clientX;
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
          // Ao carregar a página
          $(document).ready(function(){
            loadAmbientes();
            loadHierarchy();
            $('#btnSearch').on('click', function(){
              var amb = $('#ambienteBusca').val();
              var tipo = $('#tipoBusca').val();
              var txt = $('#searchText').val();
              if(!amb || !tipo || !txt) {
                alert("Preencha todos os campos da busca!");
                return;
              }
              $('#tablePath').text('Resultados da busca');
              $.ajax({
                url: 'catalogo/catalogo_sql_dev_style_ajax.php',
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
                    var tipoNome = (tipo === 'schema') ? 'Schemas'
                                  : (tipo === 'table') ? 'Tabelas'
                                  : (tipo === 'attribute') ? 'Atributos'
                                  : 'Desconhecido';
                    var html = '<p><strong>' + results.length + ' resultado(s) encontrado(s) para ' + tipoNome + ':</strong></p>';
                    if(results.length === 0) {
                      $('#detailsContainer').html(html);
                      return;
                    }
                    html += '<ul>';
                    results.forEach(function(r){
                      if(tipo === 'schema') {
                        html += '<li>' + r.ambiente + '/' + r.service_name + '/' + r.schema_name + '/</li>';
                      } else {
                        var pathText = r.ambiente + '/' + r.service_name + '/' + r.schema_name + '/' + r.table_name;
                        html += '<li><a href="#" class="tableLink" data-amb="' + r.ambiente + '" data-srv="' + r.service_name + '" data-sch="' + r.schema_name + '" data-tbl="' + r.table_name + '" data-type="' + (r.object_type || "TABELA") + '">' + pathText + '</a></li>';
                      }
                    });
                    html += '</ul>';
                    $('#detailsContainer').html(html);
                    $('.tableLink').on('click', function(e){
                      e.preventDefault();
                      var amb = $(this).data('amb');
                      var srv = $(this).data('srv');
                      var sch = $(this).data('sch');
                      var tbl = $(this).data('tbl');
                      var type = $(this).data('type');
                      showTableDetails(amb, srv, sch, tbl, type);
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
          });
          // Carregar Ambientes
          function loadAmbientes(){
            $("#ambienteBusca").prop('disabled', true);
            $("#ambientesLoader").show();
            $.ajax({
              url: 'catalogo/catalogo_sql_dev_style_ajax.php',
              method: 'GET',
              data: { action: 'getAmbientes' },
              dataType: 'json',
              success: function(resp) {
                $("#ambienteBusca").prop('disabled', false);
                $("#ambientesLoader").hide();
                if(resp.success) {
                  var ambientes = resp.data;
                  var $select = $('#ambienteBusca');
                  $select.empty();
                  $select.append('<option value="">Selecione...</option>');
                  ambientes.forEach(function(amb){
                    $select.append('<option value="' + amb + '">' + amb + '</option>');
                  });
                } else {
                  $('#ambienteBusca').html('<option value="">Erro ao carregar</option>');
                }
              },
              error: function() {
                $("#ambienteBusca").prop('disabled', false);
                $("#ambientesLoader").hide();
                $('#ambienteBusca').html('<option value="">Erro ao carregar</option>');
              }
            });
          }
          // Carregar Hierarquia
          function loadHierarchy(){
            $('#treeContainer').html('<div class="loading"><i class="fa fa-spinner fa-spin"></i> Carregando...</div>');
            $.ajax({
              url: 'catalogo/catalogo_sql_dev_style_ajax.php',
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
          // Montar a Árvore
          function buildTree(data) {
            var $ul = $('<ul class="list-unstyled"></ul>');
            data.forEach(function(ambObj) {
              var dataBaseForHistory = ambObj.ambiente;
              let rawDate = ambObj.date_collect || "";
              rawDate = rawDate.replace(" ", "T");
              if (rawDate.indexOf('.') !== -1) {
                rawDate = rawDate.split('.')[0];
              }
              var envDate = new Date(rawDate);
              var now = new Date();
              var diffHours = (now - envDate) / (1000 * 60 * 60);
              if (diffHours < 0) { diffHours = 0; }
              var envIconClass = "fa-server";
              if (diffHours < 24) { envIconClass += " text-primary"; }
              else if (diffHours < 48) { envIconClass += " text-warning"; }
              else { envIconClass += " text-danger"; }
              var countChildren = ambObj.children ? ambObj.children.length : 0;
              var tech = ambObj.technology ? ambObj.technology.toUpperCase() + " - " : "";
              var ambLabel = tech + ambObj.ambiente + " (" + countChildren + ")";
              if (diffHours >= 48) {
                var collectDate = envDate.toLocaleDateString();
                ambLabel += " - " + collectDate;
              }
              var $liAmb = createTreeNode(envIconClass, ambLabel);
              $liAmb.children('.tree-node').on('click', function(e) {
                e.stopPropagation();
                $(this).parent().toggleClass('expanded');
              });
              if (ambObj.children && ambObj.children.length > 0) {
                var $childUL = $('<ul class="tree-children list-unstyled"></ul>');
                ambObj.children.forEach(function(serviceObj) {
                  var serviceLabel = serviceObj.service_name + " (" + (serviceObj.children ? serviceObj.children.length : 0) + ")";
                  var $liSrv = createTreeNode('fa-database text-success', serviceLabel);
                  $liSrv.children('.tree-node').on('click', function(e) {
                    e.stopPropagation();
                    $(this).parent().toggleClass('expanded');
                  });
                  var $srvInfoIcon = $('<i class="fa fa-info-circle ms-2" style="cursor:pointer;" title="Ver Informações do Banco"></i>');
                  $liSrv.children('.tree-node').append($srvInfoIcon);
                  var hostName = serviceObj.service_name.match(/\((.*?)\)/)?.[1] || '???';
                  var serviceRaw = serviceObj.service_name.replace(/\(.*?\)/, '').trim();
                  $srvInfoIcon.on('click', function(e) {
                    e.stopPropagation();
                    showServiceInfo(ambObj.ambiente, hostName, serviceRaw);
                  });
                  // Ícone para histórico de schemas
                  var $schemaHistIcon = $('<i class="fa fa-history ms-2" style="cursor:pointer;" title="Histórico de Mudanças de Schema"></i>');
                  $liSrv.children('.tree-node').append($schemaHistIcon);
                  $schemaHistIcon.on('click', function(e) {
                    e.stopPropagation();
                    showSchemaHistoryInfo(hostName, serviceRaw, ambObj.ambiente);
                  });
                  if (serviceObj.children && serviceObj.children.length > 0) {
                    var $childUL2 = $('<ul class="tree-children list-unstyled"></ul>');
                    serviceObj.children.forEach(function(schemaObj) {
                      var $liSch = createTreeNode('fa-sitemap text-warning', schemaObj.schema_name);
                      $liSch.children('.tree-node').on('click', function(e) {
                        e.stopPropagation();
                        $(this).parent().toggleClass('expanded');
                      });
                      // Ícone para relacionamentos do schema
                      var $schemaRelIcon = $('<i class="fa fa-project-diagram schema-rel-icon" style="margin-left:5px; cursor:pointer;" title="Ver Relacionamentos do Schema"></i>');
                      $liSch.children('.tree-node').append($schemaRelIcon);
                      $schemaRelIcon.on('click', function(e) {
                        e.stopPropagation();
                        showSchemaRelationships(ambObj.ambiente, serviceObj.service_name, schemaObj.schema_name);
                      });
                      // Ícone para histórico de alterações de tabela
                      var $tableHistIcon = $('<i class="fa fa-history ms-2" style="cursor:pointer;" title="Histórico de Alterações de Tabela"></i>');
                      $liSch.children('.tree-node').append($tableHistIcon);
                      $tableHistIcon.on('click', function(e) {
                        e.stopPropagation();
                        showTableChangeHistory(hostName, serviceRaw, ambObj.ambiente, schemaObj.schema_name);
                      });
                      if (schemaObj.children && schemaObj.children.length > 0) {
                        var $schemaChildUL = $('<ul class="tree-children list-unstyled"></ul>');
                        var groups = {};
                        schemaObj.children.forEach(function(tblObj) {
                          var type = tblObj.object_type ? tblObj.object_type.toUpperCase() : 'TABELA';
                          if (!groups[type]) { groups[type] = []; }
                          groups[type].push(tblObj);
                        });
                        var order = {
                          'TABELA': 1,
                          'TABELA EXTERNA': 2,
                          'VIEW': 3,
                          'VIEW MATERIALIZADA': 4,
                          'PACKAGE': 5,
                          'PACKAGE BODY': 6,
                          'FUNCTION': 7,
                          'PROCEDURE': 8,
                          'TRIGGER': 9
                        };
                        var groupKeys = Object.keys(groups).sort(function(a, b) {
                          var weightA = order[a] || 999;
                          var weightB = order[b] || 999;
                          return weightA - weightB;
                        });
                        groupKeys.forEach(function(type) {
                          var items = groups[type];
                          items.sort(function(a, b) {
                            return a.table_name.localeCompare(b.table_name);
                          });
                          var labelGrupo = type + " (" + items.length + ")";
                          var iconGrupo = "";
                          switch (type) {
                            case "TABELA": iconGrupo = "fa-table text-info"; break;
                            case "TABELA EXTERNA": iconGrupo = "fa-external-link-alt text-secondary"; break;
                            case "VIEW": iconGrupo = "fa-eye text-primary"; break;
                            case "VIEW MATERIALIZADA": iconGrupo = "fa-eye text-warning"; break;
                            case "PACKAGE": iconGrupo = "fa-box text-dark"; break;
                            case "PACKAGE BODY": iconGrupo = "fa-boxes text-dark"; break;
                            case "FUNCTION": iconGrupo = "fa-code text-success"; break;
                            case "PROCEDURE": iconGrupo = "fa-cogs text-success"; break;
                            case "TRIGGER": iconGrupo = "fa-bolt text-danger"; break;
                            default: iconGrupo = "fa-table text-info";
                          }
                          var $liGrupo = createTreeNode(iconGrupo, labelGrupo);
                          $liGrupo.children('.tree-node').on('click', function(e) {
                            e.stopPropagation();
                            $(this).parent().toggleClass('expanded');
                          });
                          var $groupChildUL = $('<ul class="tree-children list-unstyled"></ul>');
                          items.forEach(function(tblObj) {
                            var tableLabel = "";
                            var newTypes = ['PACKAGE','PACKAGE BODY','FUNCTION','PROCEDURE','TRIGGER'];
                            if (newTypes.indexOf(tblObj.object_type.toUpperCase()) >= 0) {
                              tableLabel = tblObj.table_name;
                            } else {
                              tableLabel = tblObj.table_name + " (" + (tblObj.columns_count || 0) + ")";
                              if (tblObj.missing_descriptions && tblObj.object_type !== 'TABELA EXTERNA') {
                                tableLabel += ' <i class="fa fa-exclamation-triangle text-danger" title="Tabela com falta de descrições"></i>';
                              }
                              // Adiciona o ícone de histórico de atributos
                              tableLabel += ' <i class="fa fa-history ms-2" style="cursor:pointer;" title="Histórico de Atributos"></i>';
                            }
                            if (tblObj.object_status && tblObj.object_status.toUpperCase() === 'INVALID') {
                              tableLabel = '<span style="text-decoration: line-through; color: red;">' + tableLabel + '</span>';
                            }
                            var $liTbl = createTreeNode(iconGrupo, tableLabel);
                            $liTbl.children('.tree-node').on('click', function(e) {
                              e.stopPropagation();
                              showTableDetails(
                                ambObj.ambiente,
                                serviceObj.service_name,
                                schemaObj.schema_name,
                                tblObj.table_name,
                                tblObj.object_type
                              );
                            });
                            // Vincula o clique no ícone de histórico de atributos
                            $liTbl.find('.fa-history').on('click', function(e) {
                              e.stopPropagation();
                              showAttributeHistory(ambObj.ambiente, serviceObj.service_name, schemaObj.schema_name, tblObj.table_name);
                            });
                            $groupChildUL.append($liTbl);
                          });
                          $liGrupo.append($groupChildUL);
                          $schemaChildUL.append($liGrupo);
                        });
                        $liSch.append($schemaChildUL);
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
          // Cria nó de árvore
          function createTreeNode(iconClass, labelText){
            var $li = $('<li class="mb-1"></li>');
            var $div = $('<div class="tree-node"></div>');
            if(iconClass.indexOf("fa-table") !== -1 || iconClass.indexOf("fa-external-link-alt") !== -1 || iconClass.indexOf("fa-eye") !== -1) {
              var plainText = labelText.replace(/<[^>]+>/g, '');
              $div.attr("title", plainText);
            }
            $div.append('<i class="fa ' + iconClass + '"></i> ' + labelText);
            $li.append($div);
            return $li;
          }
          // Mostrar Detalhes de Tabela/Objeto
          function showTableDetails(ambiente, serviceName, schemaName, tableName, objectType) {
            var svc = serviceName.replace(/\(.*?\)/, '').trim();
            var path = ambiente + '/' + svc + '/' + schemaName + '/' + tableName;
            $('#tablePath').text(path);
            $('#detailsContainer').html('<div class="loading">Carregando...</div>');
            if (objectType && ['PACKAGE', 'PACKAGE BODY', 'FUNCTION', 'PROCEDURE', 'TRIGGER'].includes(objectType.toUpperCase())) {
              $.ajax({
                url: 'catalogo/catalogo_sql_dev_style_ajax.php',
                method: 'GET',
                data: {
                  action: 'getObjectDetails',
                  ambiente: ambiente,
                  service_name: serviceName,
                  schema_name: schemaName,
                  object_name: tableName
                },
                dataType: 'json',
                success: function(resp) {
                  if(resp.success && resp.data){
                    var obj = resp.data;
                    var rawCode = obj.object_content || '';
                    rawCode = rawCode.replace(/(\n\s*\n\s*)+/g, "\n\n");
                    var formattedCode = formatCodeBlock(rawCode);
                    var html = '<h5>' + obj.object_type + ' - ' + obj.object_name + '</h5>';
                    html += '<button id="copyCodeButton" class="btn btn-sm btn-outline-secondary" style="margin-bottom:10px;">Copiar Código</button>';
                    html += formattedCode;
                    $('#detailsContainer').html(html);
                    $('#copyCodeButton').click(function(){
                      navigator.clipboard.writeText(rawCode).then(function(){
                        alert("Código copiado!");
                      }, function(err){
                        alert("Erro ao copiar o código: " + err);
                      });
                    });
                  } else {
                    $('#detailsContainer').html('<p>Nenhum detalhe encontrado.</p>');
                  }
                },
                error: function(){
                  $('#detailsContainer').html('<p>Erro ao obter detalhes do objeto.</p>');
                }
              });
            }
            else {
              $.ajax({
                url: 'catalogo/catalogo_sql_dev_style_ajax.php',
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
                    var rawType = tbl.object_type || '';
                    var rawTypeUpper = rawType.toUpperCase();
                    if(rawTypeUpper === 'VIEW MATERIALIZADA') { rawType = 'View Materializada'; }
                    else if(rawTypeUpper === 'VIEW') { rawType = 'View'; }
                    else if(rawTypeUpper === 'TABELA EXTERNA') { rawType = 'Tabela Externa'; }
                    else if(rawTypeUpper === 'TABELA') { rawType = 'Tabela'; }
                    var objLabel = rawType ? rawType : 'Tabela';
                    html += '<tr><th>' + objLabel + '</th><td>' + (tbl.table_name || '') + '</td></tr>';
                    var tableComment = tbl.table_comments || '';
                    if(tbl.object_type !== 'TABELA EXTERNA' && !tbl.table_comments) {
                      tableComment = '<i class="fa fa-exclamation-triangle text-danger" title="Tabela com falta de descrições"></i>';
                    }
                    html += '<tr><th>Comentário</th><td>' + tableComment + '</td></tr>';
                    if(rawTypeUpper === 'TABELA EXTERNA') {
                      html += '<tr><th>Diretório Externo</th><td>' + (tbl.external_directory || '') + '</td></tr>';
                      html += '<tr><th>Caminho do Diretório Externo</th><td>' + (tbl.external_directory_path || '') + '</td></tr>';
                      html += '<tr><th>Local Externo</th><td>' + (tbl.external_location || '') + '</td></tr>';
                    }
                    html += '<tr><th>Data de Criação</th><td>' + formatDateTime(tbl.table_creation_date) + '</td></tr>';
                    html += '<tr><th>Últ. DDL aplicado</th><td>' + formatDateTime(tbl.table_last_ddl_time) + '</td></tr>';
                    var recordCountFormatted = '0';
                    if(tbl.record_count) { recordCountFormatted = parseInt(tbl.record_count, 10).toLocaleString('pt-BR'); }
                    html += '<tr><th>Qtd. de Registros</th><td>' + recordCountFormatted + '</td></tr>';
                    if (rawTypeUpper === 'TABELA' || rawTypeUpper === 'VIEW MATERIALIZADA') {
                      var sizeInBytes = parseInt(tbl.table_size_bytes || '0', 10);
                      var sizeFormatted = formatBytes(sizeInBytes);
                      html += '<tr><th>Tamanho</th><td>' + sizeFormatted + '</td></tr>';
                    }
                    html += '</table>';
                    if(tbl.columns && tbl.columns.length > 0) {
                      html += '<h5></h5>';
                      html += '<table id="columnsTable">';
                      html += '<tr><th>Coluna</th><th>Tipo</th><th>Tamanho</th><th>Nulo</th><th>Único</th><th>Chave</th><th>Comentários</th></tr>';
                      tbl.columns.forEach(function(col){
                        var chave = '';
                        if(col.is_pk === 'Y' && col.is_fk === 'Y') {
                          chave = '<i class="fa fa-key" style="color:gold;"></i> <i class="fa fa-key" style="color:green;"></i>';
                        } else if(col.is_pk === 'Y') { chave = '<i class="fa fa-key" style="color:gold;"></i>'; }
                        else if(col.is_fk === 'Y') { chave = '<i class="fa fa-key" style="color:green;"></i>'; }
                        var colComment = col.column_comments;
                        if(tbl.object_type !== 'TABELA EXTERNA' && !col.column_comments) {
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
                      html += '<div id="keyLegend"><p><small><i class="fa fa-key" style="color:gold;"></i> = PK &nbsp;&nbsp; <i class="fa fa-key" style="color:green;"></i> = FK</small></p></div>';
                    }
                    html += '<hr>';
                    html += '<button class="btn btn-sm btn-outline-primary mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#chartContainer" aria-expanded="false" aria-controls="chartContainer">';
                    html += '  <i class="fa fa-chart-line"></i> Crescimento';
                    html += '</button>';
                    html += '<div class="collapse" id="chartContainer" data-amb="' + ambiente + '" data-srv="' + serviceName + '" data-sch="' + schemaName + '" data-tbl="' + tableName + '">';
                    html += '  <canvas id="growthChart" style="max-width: 100%;"></canvas>';
                    html += '</div>';
                    if (rawTypeUpper === 'VIEW' || rawTypeUpper === 'VIEW MATERIALIZADA') {
                      html += '<button class="btn btn-sm btn-outline-secondary mb-2 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#viewCodeContainer" aria-expanded="false" aria-controls="viewCodeContainer">';
                      html += '  <i class="fa fa-code"></i> Código';
                      html += '</button>';
                      html += '<div class="collapse" id="viewCodeContainer" data-amb="' + ambiente + '" data-srv="' + serviceName + '" data-sch="' + schemaName + '" data-tbl="' + tableName + '">';
                      html += '  <div id="viewCodeBlock" class="mermaid" style="min-height: 150px; padding:10px; background-color:#f9f9f9; border:1px solid #ddd; border-radius:5px;">';
                      html += '    <i class="fa fa-spinner fa-spin"></i> Carregando...';
                      html += '  </div>';
                      html += '</div>';
                    }
                    if (rawTypeUpper === 'TABELA') {
                      html += '<button class="btn btn-sm btn-outline-secondary mb-2 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#relationshipContainer" aria-expanded="false" aria-controls="relationshipContainer">';
                      html += '  <i class="fa fa-project-diagram"></i> Relacionamento';
                      html += '</button>';
                      html += '<div class="collapse" id="relationshipContainer" data-amb="' + ambiente + '" data-srv="' + serviceName + '" data-sch="' + schemaName + '" data-tbl="' + tableName + '">';
                      html += '  <div id="relationshipDiagram" class="mermaid" style="min-height: 150px; padding:10px; background-color:#f9f9f9; border:1px solid #ddd; border-radius:5px;">';
                      html += '    <i class="fa fa-spinner fa-spin"></i> Carregando...';
                      html += '  </div>';
                      html += '</div>';
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
          }
          // Carrega histórico de registros (gráfico)
          function loadTableHistory(ambiente, serviceName, schemaName, tableName) {
            $.ajax({
              url: 'catalogo/catalogo_sql_dev_style_ajax.php',
              method: 'GET',
              data: {
                action: 'getTableHistory',
                ambiente: ambiente,
                service_name: serviceName,
                schema_name: schemaName,
                table_name: tableName
              },
              dataType: 'json',
              success: function(resp) {
                if(resp.success && resp.data && resp.data.length > 0) {
                  var data = resp.data;
                  var labels = [];
                  var values = [];
                  data.forEach(function(item) {
                    var dt = new Date(item.date_collect);
                    labels.push(dt.toLocaleDateString());
                    values.push(parseInt(item.record_count, 10));
                  });
                  var canvas = document.getElementById('growthChart');
                  if(!canvas) { console.error("Canvas #growthChart não encontrado!"); return; }
                  var ctx = canvas.getContext('2d');
                  if (window.growthChart && typeof window.growthChart.destroy === 'function') {
                    window.growthChart.destroy();
                  }
                  window.growthChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                      labels: labels,
                      datasets: [{
                        label: 'Qtd. de Registros',
                        data: values,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                        tension: 0.1
                      }]
                    },
                    options: {
                      scales: {
                        x: { title: { display: true, text: 'Data de Coleta' } },
                        y: { title: { display: true, text: 'Qtd. de Registros' }, beginAtZero: true }
                      }
                    }
                  });
                } else {
                  $('#growthChart').replaceWith('<p>Histórico não disponível.</p>');
                }
              },
              error: function() {
                $('#growthChart').replaceWith('<p>Erro ao carregar histórico.</p>');
              }
            });
          }
          // Carrega código da view
          function loadViewCode(ambiente, serviceName, schemaName, viewName) {
            $('#viewCodeBlock').html('<i class="fa fa-spinner fa-spin"></i> Carregando...');
            $.ajax({
              url: 'catalogo/catalogo_sql_dev_style_ajax.php',
              method: 'GET',
              data: {
                action: 'getObjectDetails',
                ambiente: ambiente,
                service_name: serviceName,
                schema_name: schemaName,
                object_name: viewName
              },
              dataType: 'json',
              success: function(resp) {
                if (resp.success && resp.data) {
                  var obj = resp.data;
                  var rawCode = obj.object_content || '';
                  rawCode = rawCode.replace(/(\n\s*\n\s*)+/g, "\n\n");
                  var formattedCode = formatCodeBlock(rawCode);
                  var html = '<h5>' + (obj.object_type || 'VIEW') + ' - ' + obj.object_name + '</h5>';
                  html += '<button id="copyViewCodeButton" class="btn btn-sm btn-outline-secondary mb-2">Copiar Código</button>';
                  html += formattedCode;
                  $('#viewCodeBlock').html(html);
                  $('#copyViewCodeButton').click(function(){
                    navigator.clipboard.writeText(rawCode).then(function(){
                      alert("Código copiado!");
                    }, function(err){
                      alert("Erro ao copiar o código: " + err);
                    });
                  });
                } else {
                  $('#viewCodeBlock').html('<p>Nenhum código encontrado para a VIEW.</p>');
                }
              },
              error: function() {
                $('#viewCodeBlock').html('<p>Erro ao carregar o código da VIEW.</p>');
              }
            });
          }
          // Carrega relacionamentos de uma tabela
          function loadTableRelationships(ambiente, serviceName, schemaName, tableName) {
            $('#relationshipDiagram').html('<i class="fa fa-spinner fa-spin"></i> Carregando...');
            $.ajax({
              url: 'catalogo/catalogo_sql_dev_style_ajax.php',
              method: 'GET',
              data: {
                action: 'getTableRelationships',
                ambiente: ambiente,
                service_name: serviceName,
                schema_name: schemaName,
                table_name: tableName
              },
              dataType: 'json',
              success: function(resp) {
                if (resp.success && resp.data && resp.data.length > 0) {
                  let relationships = resp.data;
                  let diagramText = "graph LR\n";
                  let edges = new Set();
                  relationships.forEach(function(rel) {
                    let origin = sanitizeMermaid(rel.table_origin);
                    let reference = sanitizeMermaid(rel.table_reference);
                    let constraint = sanitizeMermaid(rel.constraint_name);
                    let direction = rel.direction;
                    let attrOrigin = sanitizeMermaid(rel.attribute_origin || '');
                    let label = constraint;
                    if(attrOrigin) { label += " (" + attrOrigin + ")"; }
                    let edgeKey = `${origin}||${label}||${reference}||${direction}`;
                    if(!edges.has(edgeKey)) {
                      edges.add(edgeKey);
                      if (direction === ">") { diagramText += `${origin}--"${label}"-->${reference}\n`; }
                      else if (direction === "<") { diagramText += `${reference}--"${label}"-->${origin}\n`; }
                      else { diagramText += `${origin}--"${label}"-->${reference}\n`; }
                    }
                  });
                  if(diagramText.length > 100000){
                    $('#relationshipDiagram').html('<p>Diagrama muito grande para ser exibido.</p>');
                    return;
                  }
                  let mermaidHtml = '<div class="mermaid">' + diagramText + '</div>';
                  $('#relationshipDiagram').html(mermaidHtml);
                  mermaid.init(undefined, $('#relationshipDiagram').find('.mermaid'));
                } else {
                  $('#relationshipDiagram').html('<p>Não foram encontrados relacionamentos para esta tabela.</p>');
                }
              },
              error: function() {
                $('#relationshipDiagram').html('<p>Erro ao carregar relacionamentos.</p>');
              }
            });
          }
          // Carrega relacionamentos de um schema
          function loadSchemaRelationships(ambiente, serviceName, schemaName) {
            $('#detailsContainer').html('<div class="loading"><i class="fa fa-spinner fa-spin"></i> Carregando relacionamentos do schema...</div>');
            $.ajax({
              url: 'catalogo/catalogo_sql_dev_style_ajax.php',
              method: 'GET',
              data: {
                action: 'getSchemaRelationships',
                ambiente: ambiente,
                service_name: serviceName,
                schema_name: schemaName
              },
              dataType: 'json',
              success: function(resp) {
                if(resp.success && resp.data && resp.data.length > 0) {
                  let relationships = resp.data;
                  let diagramText = "graph LR\n";
                  let edges = new Set();
                  relationships.forEach(function(rel) {
                    let origin = sanitizeMermaid(rel.table_origin);
                    let reference = sanitizeMermaid(rel.table_reference);
                    let constraint = sanitizeMermaid(rel.constraint_name);
                    let direction = rel.direction;
                    let attrOrigin = sanitizeMermaid(rel.attribute_origin || '');
                    let label = constraint;
                    if(attrOrigin) { label += " (" + attrOrigin + ")"; }
                    let edgeKey = `${origin}||${label}||${reference}||${direction}`;
                    if(!edges.has(edgeKey)) {
                      edges.add(edgeKey);
                      if(direction === ">") { diagramText += `${origin}--"${label}"-->${reference}\n`; }
                      else if(direction === "<") { diagramText += `${reference}--"${label}"-->${origin}\n`; }
                      else { diagramText += `${origin}--"${label}"-->${reference}\n`; }
                    }
                  });
                  if(diagramText.length > 100000){
                    $('#detailsContainer').html('<p>Diagrama muito grande para ser exibido.</p>');
                    return;
                  }
                  let mermaidHtml = '<div class="mermaid">' + diagramText + '</div>';
                  $('#detailsContainer').html(mermaidHtml);
                  mermaid.init(undefined, $('#detailsContainer').find('.mermaid'));
                  setTimeout(function() {
                    const svgs = d3.selectAll('#detailsContainer .mermaid svg');
                    svgs.each(function() {
                      let svg = d3.select(this);
                      svg.html("<g>" + svg.html() + "</g>");
                      let inner = svg.select("g");
                      let zoom = d3.zoom().on("zoom", function(event) { inner.attr("transform", event.transform); });
                      svg.call(zoom);
                    });
                  }, 300);
                } else {
                  $('#detailsContainer').html('<p>Não foram encontrados relacionamentos para este schema.</p>');
                }
              },
              error: function() {
                $('#detailsContainer').html('<p>Erro ao carregar relacionamentos do schema.</p>');
              }
            });
          }
          // Encapsula loadSchemaRelationships
          function showSchemaRelationships(ambiente, serviceName, schemaName) {
            var path = ambiente + '/' + serviceName + '/' + schemaName;
            $('#tablePath').text(path);
            loadSchemaRelationships(ambiente, serviceName, schemaName);
          }

          // Função para exibir informações do banco (sem botão de histórico)
          function showServiceInfo(dataBase, hostName, serviceName) {
            $('#tablePath').text(dataBase + '/' + serviceName);
            $('#detailsContainer').html('<div class="loading">Carregando informações do banco...</div>');
            $.ajax({
              url: 'catalogo/catalogo_sql_dev_style_ajax.php',
              method: 'GET',
              data: { action: 'getServiceInfo', data_base: dataBase, host_name: hostName, service_name: serviceName },
              dataType: 'json',
              success: function(resp) {
                if(!resp.success) {
                  $('#detailsContainer').html('<p>Erro: ' + resp.data + '</p>');
                  return;
                }
                var rows = resp.data;
                if(!rows || rows.length === 0) {
                  $('#detailsContainer').html('<p>Nenhum dado encontrado em <strong>catalog_database_infos</strong>.</p>');
                  return;
                }
                var first = rows[0];
                var techIcon = '';
                var tecnologia = (first.tecnologia || '').toLowerCase();
                if (tecnologia === 'oracle') {
                  techIcon = '<img src="imgs/oracle.jpg" style="width:80px;" alt="Oracle">';
                } else if (tecnologia === 'postgres' || tecnologia === 'postgresql') {
                  techIcon = '<img src="imgs/postgres.jpg" style="width:80px;" alt="PostgreSQL">';
                } else {
                  techIcon = '<i class="fa fa-database fa-2x" style="color:#999;"></i>';
                }
                var html = '<div style="display:flex; align-items:center; margin-bottom:10px;">';
                html += techIcon;
                html += '<h5 style="margin-left:10px;">Informações do Banco</h5>';
                html += '</div>';
                html += '<table class="table table-bordered table-sm" style="width:100%; font-size: 13px;">';
                html += '<tr><th>Ambiente</th><td>' + (first.ambiente || '') + '</td></tr>';
                html += '<tr><th>Data Criação</th><td>' + (formatDateTime(first.data_criacao) || '') + '</td></tr>';
                html += '<tr><th>Último Start</th><td>' + (formatDateTime(first.ultimo_start) || '') + '</td></tr>';
                html += '<tr><th>Host</th><td>' + (first.nome_host || '') + '</td></tr>';
                html += '<tr><th>Data Coleta Informações</th><td>' + (formatDateTime(first.data_coleta) || '') + '</td></tr>';
                html += '</table>';
                html += '<h6>Histórico de Patches / Ações</h6>';
                html += '<table class="table table-bordered table-sm" style="width:100%; font-size: 13px;">';
                html += '<thead><tr>';
                html += '<th>Data Aplicação</th><th>Ação</th><th>Comentários</th>';
                html += '</tr></thead>';
                html += '<tbody>';
                rows.forEach(function(r) {
                  html += '<tr>';
                  html += '<td>' + (formatDateTime(r.data_aplicacao_patch) || '') + '</td>';
                  html += '<td>' + (r.acao_patch || '') + '</td>';
                  html += '<td>' + (r.patch_comentarios || '') + '</td>';
                  html += '</tr>';
                });
                html += '</tbody></table>';
                if (first.componente_instalado) {
                  html += '<h6>Componentes Instalados</h6>';
                  html += '<p style="white-space: pre-wrap; font-size: 13px;">' + first.componente_instalado + '</p>';
                }
                $('#detailsContainer').html(html);
              },
              error: function() {
                $('#detailsContainer').html('<p>Erro ao obter informações do banco.</p>');
              }
            });
          }

          // 1) Funções para histórico de schemas
          function showSchemaHistoryInfo(host_name, service_name, ambiente) {
            loadSchemaHistory(host_name, service_name, ambiente);
          }
          function loadSchemaHistory(host_name, service_name, ambiente) {
            $('#tablePath').text(ambiente + '/' + host_name + '/' + service_name);
            var html = '<div style="margin-bottom:10px;">';
            html += '<label for="schemaHistoryDateFilter"><strong>Data de Coleta:</strong></label> ';
            html += '<select id="schemaHistoryDateFilter" class="form-select form-select-sm" style="max-width:300px;">';
            html += '<option value="">Selecione uma data...</option>';
            html += '</select>';
            html += '<br><div id="schemaHistoryTableContainer"><p>Selecione uma data para visualizar os detalhes.</p></div>';
            $('#detailsContainer').html(html);

            $.ajax({
              url: 'catalogo/catalogo_sql_dev_style_ajax.php',
              method: 'GET',
              data: { action: 'getSchemaHistory', host_name: host_name, service_name: service_name, ambiente: ambiente },
              dataType: 'json',
              success: function(resp) {
                if(resp.success && resp.data) {
                  var history = resp.data;
                  if(history.length === 0) {
                    $('#schemaHistoryTableContainer').html('<p>Nenhum histórico de schemas encontrado.</p>');
                    return;
                  }
                  // Ordena por data de coleta
                  history.sort(function(a,b){
                    return new Date(a.date_collect) - new Date(b.date_collect);
                  });
                  var distinctDates = {};
                  history.forEach(function(item) {
                    var d = new Date(item.date_collect);
                    var dateStr = d.toLocaleDateString();
                    distinctDates[dateStr] = true;
                  });
                  var dates = Object.keys(distinctDates);
                  dates.sort(function(a, b) {
                    return new Date(a) - new Date(b);
                  });
                  dates.forEach(function(dateStr) {
                    $('#schemaHistoryDateFilter').append('<option value="'+ dateStr +'">'+ dateStr +'</option>');
                  });
                  $('#schemaHistoryDateFilter').on('change', function(){
                    var selectedDate = $(this).val();
                    if(selectedDate === ""){
                      $('#schemaHistoryTableContainer').html('<p>Selecione uma data para visualizar os detalhes.</p>');
                      return;
                    }
                    var filteredHistory = history.filter(function(item){
                      var d = new Date(item.date_collect);
                      return d.toLocaleDateString() === selectedDate;
                    });
                    var newTableHtml = buildSchemaHistoryTable(filteredHistory);
                    $('#schemaHistoryTableContainer').html(newTableHtml);
                  });
                } else {
                  $('#detailsContainer').html('<p>Erro ao carregar o histórico de schemas: ' + resp.data + '</p>');
                }
              },
              error: function() {
                $('#detailsContainer').html('<p>Erro ao carregar o histórico de schemas.</p>');
              }
            });
          }
          function buildSchemaHistoryTable(history) {
            var tableHtml = '<table class="table table-bordered table-sm" style="width:100%; font-size: 13px;">';
            tableHtml += '<thead><tr>';
            tableHtml += '<th>Tipo da Mudança</th><th>Schema (antigo → novo)</th><th>Data Coleta</th>';
            tableHtml += '</tr></thead>';
            tableHtml += '<tbody>';
            history.forEach(function(item) {
              tableHtml += '<tr>';              
              tableHtml += '<td>' + item.change_type + '</td>';
              tableHtml += '<td>' + item.schema_name + (item.new_name ? (' → ' + item.new_name) : '') + '</td>';
              tableHtml += '<td>' + formatDateTime(item.date_collect) + '</td>';
              tableHtml += '</tr>';
            });
            tableHtml += '</tbody></table>';
            return tableHtml;
          }

          // 2) Funções para histórico de tabelas (no nível do schema)
          function showTableChangeHistory(host_name, service_name, ambiente, schema_name) {
            var path = ambiente + '/' + service_name + '/' + schema_name + ' (Histórico de Tabela)';
            $('#tablePath').text(path);
            loadTableChangeHistory(host_name, service_name, ambiente, schema_name);
          }
          function loadTableChangeHistory(host_name, service_name, ambiente, schema_name) {
            var html = '<div style="margin-bottom:10px;">';
            html += '<label for="tableHistoryDateFilter"><strong>Data de Coleta:</strong></label> ';
            html += '<select id="tableHistoryDateFilter" class="form-select form-select-sm" style="max-width:300px;">';
            html += '<option value="">Selecione uma data...</option>';
            html += '</select>';
            html += '<br><div id="tableHistoryTableContainer"><p>Selecione uma data para visualizar os detalhes.</p></div>';
            $('#detailsContainer').html(html);
            
            $.ajax({
              url: 'catalogo/catalogo_sql_dev_style_ajax.php',
              method: 'GET',
              data: { 
                action: 'getTableHist', 
                host_name: host_name, 
                service_name: service_name, 
                ambiente: ambiente, 
                schema_name: schema_name 
              },
              dataType: 'json',
              success: function(resp) {
                if(resp.success && resp.data) {
                  var history = resp.data;
                  if(history.length === 0) {
                    $('#tableHistoryTableContainer').html('<p>Nenhum histórico de tabelas encontrado.</p>');
                    return;
                  }
                  history.sort(function(a,b){
                    return new Date(a.date_collect) - new Date(b.date_collect);
                  });
                  var distinctDates = {};
                  history.forEach(function(item) {
                    var d = new Date(item.date_collect);
                    var dateStr = d.toLocaleDateString();
                    distinctDates[dateStr] = true;
                  });
                  var dates = Object.keys(distinctDates);
                  dates.sort(function(a, b) {
                    return new Date(a) - new Date(b);
                  });
                  dates.forEach(function(dateStr) {
                    $('#tableHistoryDateFilter').append('<option value="'+ dateStr +'">'+ dateStr +'</option>');
                  });
                  $('#tableHistoryDateFilter').on('change', function(){
                    var selectedDate = $(this).val();
                    if(selectedDate === ""){
                      $('#tableHistoryTableContainer').html('<p>Selecione uma data para visualizar os detalhes.</p>');
                      return;
                    }
                    var filteredHistory = history.filter(function(item){
                      var d = new Date(item.date_collect);
                      return d.toLocaleDateString() === selectedDate;
                    });
                    var newTableHtml = buildTableHistoryTable(filteredHistory);
                    $('#tableHistoryTableContainer').html(newTableHtml);
                  });
                } else {
                  $('#detailsContainer').html('<p>Erro ao carregar o histórico de tabelas: ' + resp.data + '</p>');
                }
              },
              error: function() {
                $('#detailsContainer').html('<p>Erro ao carregar o histórico de tabelas.</p>');
              }
            });
          }
          // Função para exibir o histórico de atributos de uma tabela
          function showAttributeHistory(ambiente, service_name, schema_name, table_name) {
            var hostName = service_name.match(/\((.*?)\)/)?.[1] || '???';
            var serviceRaw = service_name.replace(/\(.*?\)/, '').trim();
            var path = ambiente + '/' + serviceRaw + '/' + schema_name + '/' + table_name + ' (Histórico de Atributos)';
            $('#tablePath').text(path);
            loadAttributeHistory(hostName, serviceRaw, ambiente, schema_name, table_name);
          }
          // Função para carregar via AJAX o histórico de atributos
          function loadAttributeHistory(hostName, serviceRaw, ambiente, schema_name, table_name) {
            var html = '<div style="margin-bottom:10px;">';
            html += '<label for="attributeHistoryDateFilter"><strong>Data de Coleta:</strong></label> ';
            html += '<select id="attributeHistoryDateFilter" class="form-select form-select-sm" style="max-width:300px;">';
            html += '<option value="">Selecione uma data...</option>';
            html += '</select>';
            html += '<br><div id="attributeHistoryTableContainer"><p>Selecione uma data para visualizar os detalhes.</p></div>';
            $('#detailsContainer').html(html);

            $.ajax({
              url: 'catalogo/catalogo_sql_dev_style_ajax.php',
              method: 'GET',
              data: { 
                action: 'getAttributeHist', 
                host_name: hostName, 
                service_name: serviceRaw, 
                ambiente: ambiente, 
                schema_name: schema_name, 
                table_name: table_name 
              },
              dataType: 'json',
              success: function(resp) {
                if(resp.success && resp.data) {
                  var history = resp.data;
                  if(history.length === 0) {
                    $('#attributeHistoryTableContainer').html('<p>Nenhum histórico de atributos encontrado.</p>');
                    return;
                  }
                  history.sort(function(a, b) {
                    return new Date(a.date_collect) - new Date(b.date_collect);
                  });
                  var distinctDates = {};
                  history.forEach(function(item) {
                    var d = new Date(item.date_collect);
                    var dateStr = d.toLocaleDateString();
                    distinctDates[dateStr] = true;
                  });
                  var dates = Object.keys(distinctDates);
                  dates.sort(function(a, b) {
                    return new Date(a) - new Date(b);
                  });
                  dates.forEach(function(dateStr) {
                    $('#attributeHistoryDateFilter').append('<option value="'+ dateStr +'">'+ dateStr +'</option>');
                  });
                  $('#attributeHistoryDateFilter').on('change', function(){
                    var selectedDate = $(this).val();
                    if(selectedDate === ""){
                      $('#attributeHistoryTableContainer').html('<p>Selecione uma data para visualizar os detalhes.</p>');
                      return;
                    }
                    var filteredHistory = history.filter(function(item){
                      var d = new Date(item.date_collect);
                      return d.toLocaleDateString() === selectedDate;
                    });
                    var newTableHtml = buildAttributeHistoryTable(filteredHistory);
                    $('#attributeHistoryTableContainer').html(newTableHtml);
                  });
                } else {
                  $('#detailsContainer').html('<p>Erro ao carregar o histórico de atributos: ' + resp.data + '</p>');
                }
              },
              error: function() {
                $('#detailsContainer').html('<p>Erro ao carregar o histórico de atributos.</p>');
              }
            });
          }
          // Função para montar a tabela do histórico de atributos
          function buildAttributeHistoryTable(history) {
            var tableHtml = '<table class="table table-bordered table-sm" style="width:100%; font-size: 13px;">';
            tableHtml += '<thead><tr>';
            tableHtml += '<th>Tipo da Mudança</th><th>Atributo (antigo → novo)</th><th>Data Coleta</th>';
            tableHtml += '</tr></thead>';
            tableHtml += '<tbody>';
            history.forEach(function(item) {
              tableHtml += '<tr>';              
              tableHtml += '<td>' + item.change_type + '</td>';
              tableHtml += '<td>' + item.object_name + (item.new_name ? (' → ' + item.new_name) : '') + '</td>';
              tableHtml += '<td>' + formatDateTime(item.date_collect) + '</td>';
              tableHtml += '</tr>';
            });
            tableHtml += '</tbody></table>';
            return tableHtml;
          }
          // Função para montar a tabela do histórico de tabelas
          function buildTableHistoryTable(history) {
            var tableHtml = '<table class="table table-bordered table-sm" style="width:100%; font-size: 13px;">';
            tableHtml += '<thead><tr>';
            tableHtml += '<th>Tipo da Mudança</th><th>Schema</th><th>Tabela (antigo → novo)</th><th>Data Coleta</th>';
            tableHtml += '</tr></thead>';
            tableHtml += '<tbody>';
            history.forEach(function(item) {
              tableHtml += '<tr>';              
              tableHtml += '<td>' + item.change_type + '</td>';
              tableHtml += '<td>' + item.schema_name + '</td>';
              tableHtml += '<td>' + item.object_name + (item.new_name ? (' → ' + item.new_name) : '') + '</td>';
              tableHtml += '<td>' + formatDateTime(item.date_collect) + '</td>';
              tableHtml += '</tr>';
            });
            tableHtml += '</tbody></table>';
            return tableHtml;
          }

          // Eventos de exibição dos colapsáveis
          $(document).on('shown.bs.collapse', '#chartContainer', function () {
            let $this = $(this);
            let ambiente    = $this.data('amb');
            let serviceName = $this.data('srv');
            let schemaName  = $this.data('sch');
            let tableName   = $this.data('tbl');
            loadTableHistory(ambiente, serviceName, schemaName, tableName);
            let $mainContent = $('#mainContent');
            $mainContent.animate({ scrollTop: $mainContent.scrollTop() + $this.offset().top - $mainContent.offset().top }, 500);
          });
          $(document).on('shown.bs.collapse', '#viewCodeContainer', function () {
            let $this = $(this);
            let ambiente    = $this.data('amb');
            let serviceName = $this.data('srv');
            let schemaName  = $this.data('sch');
            let tableName   = $this.data('tbl');
            loadViewCode(ambiente, serviceName, schemaName, tableName);
          });
          $(document).on('shown.bs.collapse', '#relationshipContainer', function () {
            let $this = $(this);
            let ambiente    = $this.data('amb');
            let serviceName = $this.data('srv');
            let schemaName  = $this.data('sch');
            let tableName   = $this.data('tbl');
            loadTableRelationships(ambiente, serviceName, schemaName, tableName);
            let $mainContent = $('#mainContent');
            $mainContent.animate({ scrollTop: $mainContent.scrollTop() + $this.offset().top - $mainContent.offset().top }, 500);
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
