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
          <script>
            // Inicializa o Mermaid sem processar automaticamente
            mermaid.initialize({ startOnLoad: false });
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
            /* .container-fluid ajustado para ocupar 100% */
            .container-fluid {
              height: 100%;
              display: flex; /* coloca sidebar, dragBar e mainContent lado a lado */
              flex-direction: row;
            }
            /* Sidebar inicial: 400px, mas será ajustado ao arrastar */
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
            /* Formulário de busca */
            .search-form {
              margin-top: 10px;
              padding: 10px;
              border: 1px solid #ddd;
              border-radius: 5px;
              background-color: #f9f9f9;
            }
            /* Loader para ambientes */
            #ambientesLoader {
              font-size: 12px;
              color: #888;
              margin-top: 5px;
              display: block;
            }
            /* Drag bar */
            #dragBar {
              width: 5px;
              cursor: col-resize;
              background-color: #ccc;
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
              /* Adicione um espaço extra no final, p.ex. 50px */
              padding-bottom: 50px;
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
              /* Espaço extra ao final */
              padding-bottom: 50px;
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
            #relationshipContainer {
              margin-bottom: 30px; /* Espaço após o container de relacionamento */
            }
            #growthChart {
              display: block; /* garante que o canvas seja um bloco */
              margin-bottom: 30px; /* Espaço após o gráfico */
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
          // ================
          // Funções Auxiliares
          // ================
          function toTitleCase(str) {
            return str.toLowerCase().replace(/\b\w/g, function(letter) { return letter.toUpperCase(); });
          }

          // Formata blocos de código com numeração de linhas
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

          // Remove caracteres problemáticos para o Mermaid
          function sanitizeMermaid(str) {
            // Remove quebras de linha
            let s = String(str || '').replace(/[\n\r]+/g, ' ');
            // Substitui aspas duplas por \"
            s = s.replace(/"/g, '\\"');
            // Remove/transforma tudo que não for [A-Za-z0-9_.,:\- ] em "_"
            s = s.replace(/[^A-Za-z0-9_\.,:\- ]/g, '_');
            return s;
          }

          function formatDateTime(dt) {
            if(!dt) return '';
            var d = new Date(dt);
            return d.toLocaleString();
          }

          // ================
          // Redimensionar Sidebar
          // ================
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

          // ================
          // Ao carregar a página
          // ================
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

          // ================
          // Carregar Ambientes
          // ================
          function loadAmbientes(){
            $("#ambienteBusca").prop('disabled', true);
            $("#ambientesLoader").show();
            $.ajax({
              url: 'catalogo_sql_dev_style_ajax.php',
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

          // ================
          // Carregar Hierarquia
          // ================
          function loadHierarchy(){
            $('#treeContainer').html('<div class="loading"><i class="fa fa-spinner fa-spin"></i> Carregando...</div>');
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

          // ================
          // Montar a Árvore
          // ================
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
                      var $liSch = createTreeNode('fa-sitemap text-warning', schemaObj.schema_name);
                      $liSch.children('.tree-node').on('click', function(e){
                        e.stopPropagation();
                        $(this).parent().toggleClass('expanded');
                      });

                      if(schemaObj.children && schemaObj.children.length > 0){
                        // Agrupar por tipo
                        var groups = {};
                        schemaObj.children.forEach(function(tblObj) {
                          var type = tblObj.object_type ? tblObj.object_type.toUpperCase() : 'TABELA';
                          if(!groups[type]) {
                            groups[type] = [];
                          }
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
                        var groupKeys = Object.keys(groups).sort(function(a, b){
                          var weightA = order[a] || 999;
                          var weightB = order[b] || 999;
                          return weightA - weightB;
                        });

                        var $schemaChildUL = $('<ul class="tree-children list-unstyled"></ul>');
                        groupKeys.forEach(function(type) {
                          var items = groups[type];
                          // Ordena alfabeticamente
                          items.sort(function(a,b) {
                            return a.table_name.localeCompare(b.table_name);
                          });
                          var labelGrupo = type + " (" + items.length + ")";
                          var iconGrupo = "";
                          switch(type){
                            case "TABELA":
                              iconGrupo = "fa-table text-info";
                              break;
                            case "TABELA EXTERNA":
                              iconGrupo = "fa-external-link-alt text-secondary";
                              break;
                            case "VIEW":
                              iconGrupo = "fa-eye text-primary";
                              break;
                            case "VIEW MATERIALIZADA":
                              iconGrupo = "fa-eye text-warning";
                              break;
                            case "PACKAGE":
                              iconGrupo = "fa-box text-dark";
                              break;
                            case "PACKAGE BODY":
                              iconGrupo = "fa-boxes text-dark";
                              break;
                            case "FUNCTION":
                              iconGrupo = "fa-code text-success";
                              break;
                            case "PROCEDURE":
                              iconGrupo = "fa-cogs text-success";
                              break;
                            case "TRIGGER":
                              iconGrupo = "fa-bolt text-danger";
                              break;
                            default:
                              iconGrupo = "fa-table text-info";
                          }
                          var $liGrupo = createTreeNode(iconGrupo, labelGrupo);
                          $liGrupo.children('.tree-node').on('click', function(e){
                            e.stopPropagation();
                            $(this).parent().toggleClass('expanded');
                          });

                          var $groupChildUL = $('<ul class="tree-children list-unstyled"></ul>');
                          items.forEach(function(tblObj){
                            var tableLabel = "";
                            var newTypes = ['PACKAGE','PACKAGE BODY','FUNCTION','PROCEDURE','TRIGGER'];
                            if(newTypes.indexOf(tblObj.object_type.toUpperCase()) >= 0) {
                              tableLabel = tblObj.table_name;
                            } else {
                              tableLabel = tblObj.table_name + " (" + (tblObj.columns_count || 0) + ")";
                              if(tblObj.missing_descriptions && tblObj.object_type !== 'TABELA EXTERNA'){
                                tableLabel += ' <i class="fa fa-exclamation-triangle text-danger" title="Tabela com falta de descrições"></i>';
                              }
                            }

                            // >>> AQUI verificamos se object_status = 'INVALID'
                            if (tblObj.object_status && tblObj.object_status.toUpperCase() === 'INVALID') {
                              // Riscar em vermelho
                              tableLabel = '<span style="text-decoration: line-through; color: red;">' + tableLabel + '</span>';
                            }

                            var $liTbl = createTreeNode(iconGrupo, tableLabel);
                            $liTbl.children('.tree-node').on('click', function(e){
                              e.stopPropagation();
                              showTableDetails(
                                ambObj.ambiente,
                                serviceObj.service_name,
                                schemaObj.schema_name,
                                tblObj.table_name,
                                tblObj.object_type
                              );
                            });
                            // PACKAGE com children (PACKAGE BODY)
                            if(tblObj.children && tblObj.children.length > 0) {
                              var $childList = $('<ul class="tree-children list-unstyled"></ul>');
                              tblObj.children.forEach(function(childObj){
                                var childLabel = childObj.table_name;
                                var $childLi = createTreeNode("fa-boxes text-dark", childLabel);
                                $childLi.children('.tree-node').on('click', function(e){
                                  e.stopPropagation();
                                  showTableDetails(
                                    ambObj.ambiente,
                                    serviceObj.service_name,
                                    schemaObj.schema_name,
                                    childObj.table_name,
                                    childObj.object_type
                                  );
                                });
                                $childList.append($childLi);
                              });
                              $liTbl.append($childList);
                            }
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

          // ================
          // Mostrar Detalhes
          // ================
          function showTableDetails(ambiente, serviceName, schemaName, tableName, objectType) {
            var svc = serviceName.replace(/\(.*?\)/, '').trim();
            var path = ambiente + '/' + svc + '/' + schemaName + '/' + tableName;
            $('#tablePath').text(path);
            $('#detailsContainer').html('<div class="loading">Carregando...</div>');
            
            // Se for objeto de código (Package, Procedure, etc.)
            if (objectType && ['PACKAGE', 'PACKAGE BODY', 'FUNCTION', 'PROCEDURE', 'TRIGGER'].includes(objectType.toUpperCase())) {
              $.ajax({
                url: 'catalogo_sql_dev_style_ajax.php',
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
              // TABELA, VIEW etc.
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
                    
                    var rawType = tbl.object_type || '';
                    if(rawType.toUpperCase() === 'VIEW MATERIALIZADA') {
                        rawType = 'View Materializada';
                    } else if(rawType.toUpperCase() === 'VIEW') {
                        rawType = 'View';
                    } else if(rawType.toUpperCase() === 'TABELA EXTERNA') {
                        rawType = 'Tabela Externa';
                    } else if(rawType.toUpperCase() === 'TABELA') {
                        rawType = 'Tabela';
                    }
                    var objLabel = rawType ? rawType : 'Tabela';
                    html += '<tr><th>' + objLabel + '</th><td>' + (tbl.table_name || '') + '</td></tr>';

                    var tableComment = tbl.table_comments || '';
                    if(tbl.object_type !== 'TABELA EXTERNA' && !tbl.table_comments) {
                      tableComment = '<i class="fa fa-exclamation-triangle text-danger" title="Tabela com falta de descrições"></i>';
                    }
                    html += '<tr><th>Comentário</th><td>' + tableComment + '</td></tr>';

                    if(tbl.object_type === 'TABELA EXTERNA') {
                      html += '<tr><th>Diretório Externo</th><td>' + (tbl.external_directory || '') + '</td></tr>';
                      html += '<tr><th>Caminho do Diretório Externo</th><td>' + (tbl.external_directory_path || '') + '</td></tr>';
                      html += '<tr><th>Local Externo</th><td>' + (tbl.external_location || '') + '</td></tr>';
                    }

                    html += '<tr><th>Data de Criação</th><td>' + formatDateTime(tbl.table_creation_date) + '</td></tr>';
                    html += '<tr><th>Últ. DDL aplicado</th><td>' + formatDateTime(tbl.table_last_ddl_time) + '</td></tr>';

                    var recordCountFormatted = '0';
                    if(tbl.record_count) {
                      recordCountFormatted = parseInt(tbl.record_count, 10).toLocaleString('pt-BR');
                    }
                    html += '<tr><th>Qtd. de Registros</th><td>' + recordCountFormatted + '</td></tr>';
                    html += '</table>';

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

                    // Botão de Crescimento
                    html += '<hr>';
                    html += '<button class="btn btn-sm btn-outline-primary mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#chartContainer" aria-expanded="false" aria-controls="chartContainer">';
                    html += '  <i class="fa fa-chart-line"></i> Crescimento';
                    html += '</button>';
                    html += '<div class="collapse" id="chartContainer" data-amb="' + ambiente + '" data-srv="' + serviceName + '" data-sch="' + schemaName + '" data-tbl="' + tableName + '">';
                    html += '  <canvas id="growthChart" style="max-width: 100%;"></canvas>';
                    html += '</div>';

                    // Se for VIEW, botão "Código"
                    if (rawType.toUpperCase() === 'VIEW' || rawType.toUpperCase() === 'VIEW MATERIALIZADA') {
                      html += '<button class="btn btn-sm btn-outline-secondary mb-2 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#viewCodeContainer" aria-expanded="false" aria-controls="viewCodeContainer">';
                      html += '  <i class="fa fa-code"></i> Código';
                      html += '</button>';
                      html += '<div class="collapse" id="viewCodeContainer" data-amb="' + ambiente + '" data-srv="' + serviceName + '" data-sch="' + schemaName + '" data-tbl="' + tableName + '">';
                      html += '  <div id="viewCodeBlock" class="mermaid" style="min-height: 150px; padding:10px; background-color:#f9f9f9; border:1px solid #ddd; border-radius:5px;">';
                      html += '    <i class="fa fa-spinner fa-spin"></i> Carregando...';
                      html += '  </div>';
                      html += '</div>';
                    }

                    // Se for TABELA, botão "Relacionamento"
                    if (rawType.toUpperCase() === 'TABELA') {
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

          // Eventos de collapse
          $(document).on('shown.bs.collapse', '#chartContainer', function () {
            let $this = $(this);
            let ambiente    = $this.data('amb');
            let serviceName = $this.data('srv');
            let schemaName  = $this.data('sch');
            let tableName   = $this.data('tbl');
            loadTableHistory(ambiente, serviceName, schemaName, tableName);

            // Rolar o #mainContent até o chartContainer
            let $mainContent = $('#mainContent');
            $mainContent.animate({
              scrollTop: $mainContent.scrollTop() + $this.offset().top - $mainContent.offset().top
            }, 500);
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

            // Rolar o #mainContent até o relationshipContainer
            let $mainContent = $('#mainContent');
            $mainContent.animate({
              scrollTop: $mainContent.scrollTop() + $this.offset().top - $mainContent.offset().top
            }, 500);
          });

          // ================
          // Funções AJAX para Crescimento, Código, Relacionamento
          // ================
          function loadTableHistory(ambiente, serviceName, schemaName, tableName) {
            $.ajax({
              url: 'catalogo_sql_dev_style_ajax.php',
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
                  if(!canvas) {
                    console.error("Canvas #growthChart não encontrado!");
                    return;
                  }
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
                        x: {
                          title: {
                            display: true,
                            text: 'Data de Coleta'
                          }
                        },
                        y: {
                          title: {
                            display: true,
                            text: 'Qtd. de Registros'
                          },
                          beginAtZero: true
                        }
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

          function loadViewCode(ambiente, serviceName, schemaName, viewName) {
            $('#viewCodeBlock').html('<i class="fa fa-spinner fa-spin"></i> Carregando...');
            $.ajax({
              url: 'catalogo_sql_dev_style_ajax.php',
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

          // >>> Função atualizada para relacionamentos, incluindo os atributos
          function loadTableRelationships(ambiente, serviceName, schemaName, tableName) {
            $('#relationshipDiagram').html('<i class="fa fa-spinner fa-spin"></i> Carregando...');
            $.ajax({
              url: 'catalogo_sql_dev_style_ajax.php',
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

                  // Conjunto para evitar linhas duplicadas
                  let edges = new Set();

                  relationships.forEach(function(rel) {
                    let origin     = sanitizeMermaid(rel.table_origin);
                    let reference  = sanitizeMermaid(rel.table_reference);
                    let constraint = sanitizeMermaid(rel.constraint_name);
                    let direction  = rel.direction; // "<" ou ">"

                    // Captura os atributos
                    let attrOrigin    = sanitizeMermaid(rel.attribute_origin || '');
                    let attrReference = sanitizeMermaid(rel.attribute_reference || '');

                    // Cria o rótulo combinando constraint e os atributos (se disponíveis)
                    let label = constraint;
                    if(attrOrigin && attrReference) {
                      //label += " (" + attrOrigin + " -> " + attrReference + ")";
                      label += " (" + attrOrigin + ")";
                    }

                    // Cria uma chave única para essa aresta
                    let edgeKey = `${origin}||${label}||${reference}||${direction}`;
                    if(!edges.has(edgeKey)) {
                      edges.add(edgeKey);

                      if (direction === ">") {
                        // origin é pai
                        diagramText += `${origin}--"${label}"-->${reference}\n`;
                      } else if (direction === "<") {
                        // origin é filho
                        diagramText += `${reference}--"${label}"-->${origin}\n`;
                      } else {
                        // sem direção definida
                        diagramText += `${origin}--"${label}"-->${reference}\n`;
                      }
                    }
                  });

                  // Em vez de usar .text(), criamos uma <div class="mermaid"> com o diagrama
                  let mermaidHtml = '<div class="mermaid">' + diagramText + '</div>';
                  $('#relationshipDiagram').html(mermaidHtml);

                  // Renderiza o diagrama apenas no novo elemento
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
