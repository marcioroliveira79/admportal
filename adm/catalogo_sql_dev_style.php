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
              /* Espaço extra no final */
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
              <!-- Cabeçalho que mostra o caminho completo do item selecionado -->
              <h4 id="tablePath">Selecione um item para visualizar o caminho</h4>
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

          // Função para formatar bytes (B, KB, MB, GB...)
          function formatBytes(bytes) {
            if (isNaN(bytes) || bytes <= 0) return "0 Bytes";
            const k = 1024;
            const sizes = ["Bytes", "KB", "MB", "GB", "TB"];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            const index = (i < sizes.length) ? i : sizes.length - 1;
            const value = bytes / Math.pow(k, index);
            return value.toFixed(2) + " " + sizes[index];
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

            data.forEach(function(ambObj) {
              let rawDate = ambObj.date_collect || "";
              rawDate = rawDate.replace(" ", "T");
              if (rawDate.indexOf('.') !== -1) {
                rawDate = rawDate.split('.')[0];
              }
              var envDate = new Date(rawDate);
              var now = new Date();
              var diffHours = (now - envDate) / (1000 * 60 * 60);
              if (diffHours < 0) {
                diffHours = 0;
              }
              var envIconClass = "fa-server";
              if (diffHours < 24) {
                envIconClass += " text-primary";
              } else if (diffHours < 48) {
                envIconClass += " text-warning";
              } else {
                envIconClass += " text-danger";
              }
              var countChildren = ambObj.children ? ambObj.children.length : 0;
              var ambLabel = ambObj.ambiente + " (" + countChildren + ")";
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

                  if (serviceObj.children && serviceObj.children.length > 0) {
                    var $childUL2 = $('<ul class="tree-children list-unstyled"></ul>');

                    serviceObj.children.forEach(function(schemaObj) {
                      var $liSch = createTreeNode('fa-sitemap text-warning', schemaObj.schema_name);
                      $liSch.children('.tree-node').on('click', function(e) {
                        e.stopPropagation();
                        $(this).parent().toggleClass('expanded');
                      });
                      // Adiciona o ícone de relacionamento do schema
                      var $schemaRelIcon = $('<i class="fa fa-project-diagram schema-rel-icon" style="margin-left:5px; cursor:pointer;" title="Ver Relacionamentos do Schema"></i>');
                      $liSch.find('.tree-node').append($schemaRelIcon);
                      $schemaRelIcon.on('click', function(e) {
                        e.stopPropagation();
                        showSchemaRelationships(ambObj.ambiente, serviceObj.service_name, schemaObj.schema_name);
                      });
                      
                      if (schemaObj.children && schemaObj.children.length > 0) {
                        var $schemaChildUL = $('<ul class="tree-children list-unstyled"></ul>');

                        var groups = {};
                        schemaObj.children.forEach(function(tblObj) {
                          var type = tblObj.object_type ? tblObj.object_type.toUpperCase() : 'TABELA';
                          if (!groups[type]) {
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

                            if (tblObj.children && tblObj.children.length > 0) {
                              var $childList = $('<ul class="tree-children list-unstyled"></ul>');
                              tblObj.children.forEach(function(childObj) {
                                var childLabel = childObj.table_name;
                                var $childLi = createTreeNode("fa-boxes text-dark", childLabel);
                                $childLi.children('.tree-node').on('click', function(e) {
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
                    var rawTypeUpper = rawType.toUpperCase();

                    if(rawTypeUpper === 'VIEW MATERIALIZADA') {
                        rawType = 'View Materializada';
                    } else if(rawTypeUpper === 'VIEW') {
                        rawType = 'View';
                    } else if(rawTypeUpper === 'TABELA EXTERNA') {
                        rawType = 'Tabela Externa';
                    } else if(rawTypeUpper === 'TABELA') {
                        rawType = 'Tabela';
                    }
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
                    if(tbl.record_count) {
                      recordCountFormatted = parseInt(tbl.record_count, 10).toLocaleString('pt-BR');
                    }
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

          // Função para carregar relacionamentos de tabela
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
                  let edges = new Set();

                  relationships.forEach(function(rel) {
                    let origin     = sanitizeMermaid(rel.table_origin);
                    let reference  = sanitizeMermaid(rel.table_reference);
                    let constraint = sanitizeMermaid(rel.constraint_name);
                    let direction  = rel.direction;
                    let attrOrigin = sanitizeMermaid(rel.attribute_origin || '');
                    let label = constraint;
                    if(attrOrigin) {
                      label += " (" + attrOrigin + ")";
                    }
                    let edgeKey = `${origin}||${label}||${reference}||${direction}`;
                    if(!edges.has(edgeKey)) {
                      edges.add(edgeKey);
                      if (direction === ">") {
                        diagramText += `${origin}--"${label}"-->${reference}\n`;
                      } else if (direction === "<") {
                        diagramText += `${reference}--"${label}"-->${origin}\n`;
                      } else {
                        diagramText += `${origin}--"${label}"-->${reference}\n`;
                      }
                    }
                  });
                  // Verifica se o texto gerado ultrapassa um limite (ex: 15000 caracteres)
                  if(diagramText.length > 15000){
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

          // Função para carregar relacionamentos do schema
          function loadSchemaRelationships(ambiente, serviceName, schemaName) {
            $('#detailsContainer').html('<div class="loading"><i class="fa fa-spinner fa-spin"></i> Carregando relacionamentos do schema...</div>');
            $.ajax({
              url: 'catalogo_sql_dev_style_ajax.php',
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
                    if(attrOrigin) {
                      label += " (" + attrOrigin + ")";
                    }
                    let edgeKey = `${origin}||${label}||${reference}||${direction}`;
                    if(!edges.has(edgeKey)) {
                      edges.add(edgeKey);
                      if(direction === ">") {
                        diagramText += `${origin}--"${label}"-->${reference}\n`;
                      } else if(direction === "<") {
                        diagramText += `${reference}--"${label}"-->${origin}\n`;
                      } else {
                        diagramText += `${origin}--"${label}"-->${reference}\n`;
                      }
                    }
                  });
                  // Verifica se o texto gerado ultrapassa um limite
                  if(diagramText.length > 15000){
                    $('#detailsContainer').html('<p>Diagrama muito grande para ser exibido.</p>');
                    return;
                  }
                  let mermaidHtml = '<div class="mermaid">' + diagramText + '</div>';
                  $('#detailsContainer').html(mermaidHtml);
                  mermaid.init(undefined, $('#detailsContainer').find('.mermaid'));
                } else {
                  $('#detailsContainer').html('<p>Não foram encontrados relacionamentos para este schema.</p>');
                }
              },
              error: function() {
                $('#detailsContainer').html('<p>Erro ao carregar relacionamentos do schema.</p>');
              }
            });
          }

          // Função que chama a função de carregar relacionamentos do schema
          // Agora atualiza o cabeçalho para mostrar o caminho do schema selecionado
          function showSchemaRelationships(ambiente, serviceName, schemaName) {
            var path = ambiente + '/' + serviceName + '/' + schemaName;
            $('#tablePath').text(path);
            loadSchemaRelationships(ambiente, serviceName, schemaName);
          }

          // Eventos de collapse
          $(document).on('shown.bs.collapse', '#chartContainer', function () {
            let $this = $(this);
            let ambiente    = $this.data('amb');
            let serviceName = $this.data('srv');
            let schemaName  = $this.data('sch');
            let tableName   = $this.data('tbl');
            loadTableHistory(ambiente, serviceName, schemaName, tableName);

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

            let $mainContent = $('#mainContent');
            $mainContent.animate({
              scrollTop: $mainContent.scrollTop() + $this.offset().top - $mainContent.offset().top
            }, 500);
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
