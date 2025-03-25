<?php
session_start();

// Verifica se veio a variável $acao via GET (ou POST)
$acao = isset($_GET['acao']) ? $_GET['acao'] : null;

// Verifica se o usuário está logado e se $acao existe
if (!isset($_SESSION['global_id_usuario']) || empty($_SESSION['global_id_usuario']) || !$acao) {
    header("Location: login.php");
    exit;
}

// Verifica acesso à tela
$acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
$acao_existe = isFileExists($acao, $_SESSION['global_path']);

if ($acesso != "TELA AUTORIZADA") {
    @include("html/403.html");
    exit;
}

// Se chegou aqui, usuário tem acesso. Monta a tela:
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Calculadora de Tamanho de Tabelas</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Font Awesome (para ícone de spinner) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        .container {
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
        /* Toggle sem ON/OFF */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: #fff;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #2196f3;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        .toggle-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .toggle-container .switch {
            margin-right: 8px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-title">
        <h3>Calculadora de Tamanho de Tabelas</h3>
    </div>

    <!-- Toggle para escolher entre "Usar Tabela Existente" e "Criar Nova Tabela" -->
    <div class="text-center mb-4 toggle-container">
      <label class="switch">
        <input type="checkbox" id="toggleExistingTable">
        <span class="slider"></span>
      </label>
      <span id="toggleLabel">Simular Tabela</span>
    </div>

    <!-- ===========================
         FORMULÁRIO: Tabela Existente
         =========================== -->
    <div id="existingTableForm" style="display:none;">
        <input type="hidden" id="acaoHidden" value="<?= htmlspecialchars($acao) ?>">

        <!-- Ambiente -->
        <div class="mb-3">
            <label for="ambienteSelect" class="form-label">Ambiente</label>
            <select class="form-select" id="ambienteSelect">
                <option value="">Selecione...</option>
            </select>
        </div>
        <!-- Serviço -->
        <div class="mb-3">
            <label for="servicoSelect" class="form-label">Serviço</label>
            <select class="form-select" id="servicoSelect">
                <option value="">Selecione um Ambiente primeiro</option>
            </select>
        </div>
        <!-- Schema -->
        <div class="mb-3">
            <label for="schemaSelect" class="form-label">Schema</label>
            <select class="form-select" id="schemaSelect">
                <option value="">Selecione um Serviço primeiro</option>
            </select>
        </div>
        <!-- Tabela -->
        <div class="mb-3">
            <label for="tabelaSelect" class="form-label">Tabela</label>
            <select class="form-select" id="tabelaSelect">
                <option value="">Selecione um Schema primeiro</option>
            </select>
        </div>

        <!-- Botão Buscar Atributos -->
        <div class="text-end">
            <button type="button" class="btn btn-success" id="btnBuscarAtributos">
                Buscar Atributos
            </button>
        </div>

        <!-- Tabela de atributos (preenchida via AJAX) -->
        <div class="table-responsive mt-4" style="display:none;" id="existingTableAttributesContainer">
            <table class="table table-bordered" id="existingTableAttributes">
                <thead>
                    <tr>
                        <th>Nome da Coluna</th>
                        <th>Tipo</th>
                        <th>Tamanho</th>
                        <th>Nulo?</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Campos de cálculo (Nulos, Inserções) -->
        <div id="calcParamsContainer" style="display:none;">
          <div class="row mt-3">
            <div class="col-md-4">
              <label for="nulosMin" class="form-label">Estimativa Mínima de Nulos (%)</label>
              <input type="number" class="form-control" id="nulosMin" placeholder="Ex.: 10" min="0" max="100" />
            </div>
            <div class="col-md-4">
              <label for="nulosMax" class="form-label">Estimativa Máxima de Nulos (%)</label>
              <input type="number" class="form-control" id="nulosMax" placeholder="Ex.: 50" min="0" max="100" />
            </div>
            <div class="col-md-4">
              <label for="dailyInserts" class="form-label">Inserções Diárias</label>
              <input type="number" class="form-control" id="dailyInserts" placeholder="Ex.: 1000" min="1" />
            </div>
          </div>
          <!-- Linha para override de inserções -->
          <div class="row mt-3">
            <div class="col-md-6">
              <label for="totalInserts" class="form-label">Total de Registros (override)</label>
              <input type="number" class="form-control" id="totalInserts" placeholder="Ex.: 30000" min="1">
            </div>
            <div class="col-md-6">
              <label for="overridePeriod" class="form-label">Período (dias) para override</label>
              <input type="number" class="form-control" id="overridePeriod" placeholder="Ex.: 30" min="1">
            </div>
          </div>
          <!-- Botão Calcular -->
          <div class="text-end mt-3" id="btnCalcularExistenteContainer" style="display:none;">
            <button type="button" class="btn btn-success" id="btnCalcularExistente">
              Calcular
            </button>
          </div>
        </div>
    </div>
    <!-- /FORMULÁRIO: Tabela Existente -->

    <!-- ===========================
         FORMULÁRIO: Nova Tabela
         =========================== -->
    <div id="newTableForm" style="display:none;">
        <h5></h5>
        <!-- Selecionar Tecnologia -->
        <div class="mb-3">
            <label for="novaTecSelect" class="form-label">Tecnologia</label>
            <select class="form-select" id="novaTecSelect">
                <option value="">Selecione a Tecnologia...</option>
            </select>
        </div>

        <!-- Nome da Tabela -->
        <div class="mb-3 mt-3">
            <label for="tableName" class="form-label">Nome da Tabela</label>
            <input type="text" class="form-control" id="tableName" placeholder="Ex.: MINHA_TABELA" />
        </div>

        <!-- Botão "Adicionar Atributo" -->
        <div class="mb-3">
            <button type="button" class="btn btn-success" id="btnAddAttribute">
                Adicionar Atributo
            </button>
        </div>

        <!-- Tabela de Atributos (Nova Tabela) -->
        <div class="table-responsive">
            <table class="table table-bordered" id="attributesTable">
                <thead>
                    <tr>
                        <th>Nome da Coluna</th>
                        <th>Tipo de Dado</th>
                        <th>Tamanho</th>
                        <th>Nulo?</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Campos de cálculo (Nulos, Inserções) -->
        <div id="calcParamsContainerNova" style="display:none;">
          <div class="row mt-3">
            <div class="col-md-4">
              <label for="nulosMinNova" class="form-label">Estimativa Mínima de Nulos (%)</label>
              <input type="number" class="form-control" id="nulosMinNova" placeholder="Ex.: 10" min="0" max="100" />
            </div>
            <div class="col-md-4">
              <label for="nulosMaxNova" class="form-label">Estimativa Máxima de Nulos (%)</label>
              <input type="number" class="form-control" id="nulosMaxNova" placeholder="Ex.: 50" min="0" max="100" />
            </div>
            <div class="col-md-4">
              <label for="dailyInsertsNova" class="form-label">Inserções Diárias</label>
              <input type="number" class="form-control" id="dailyInsertsNova" placeholder="Ex.: 1000" min="1" />
            </div>
          </div>
          <!-- Linha para override em Nova Tabela -->
          <div class="row mt-3">
            <div class="col-md-6">
              <label for="totalInsertsNova" class="form-label">Total de Registros (override)</label>
              <input type="number" class="form-control" id="totalInsertsNova" placeholder="Ex.: 30000" min="1">
            </div>
            <div class="col-md-6">
              <label for="overridePeriodNova" class="form-label">Período (dias) para override</label>
              <input type="number" class="form-control" id="overridePeriodNova" placeholder="Ex.: 30" min="1">
            </div>
          </div>
          <!-- Botão Calcular -->
          <div class="text-end mt-3" id="btnCalcularNovaContainer" style="display:none;">
            <button type="button" class="btn btn-success" id="btnCalcularNova2">
              Calcular
            </button>
          </div>
        </div>
    </div>
    <!-- /FORMULÁRIO: Nova Tabela -->

    <!-- Área de Resultado -->
    <div class="mt-4" id="resultadoArea" style="display:none;">
        <div class="alert alert-info" id="resultadoTexto"></div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="helpModalLabel">Ajuda - Diferença entre o estimado e o real</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <p><strong>Por que o valor estimado pode diferir do valor real?</strong></p>
            <p>O cálculo apresentado é uma estimativa que utiliza alguns pressupostos, e podem ocorrer diferenças entre o valor calculado e o valor efetivamente utilizado na base de dados. Entre os fatores que podem influenciar essa diferença, destacam-se:</p>
            <p><strong>Variação na Taxa de Inserção:</strong><br>
            O cálculo assume um número constante de inserções diárias ou um valor total para um período fixo. Na prática, a quantidade de registros inseridos pode variar de dia para dia, alterando o tamanho real da tabela.</p>
            <p><strong>Médias de Uso dos Tipos de Dados:</strong><br>
            São utilizadas médias para calcular o tamanho de cada tipo de dado. Por exemplo, para campos numéricos ou de data, um tamanho fixo é considerado. Entretanto, os dados reais podem variar em tamanho, dependendo dos valores armazenados.</p>
            <p><strong>Percentual de Valores Nulos:</strong><br>
            As estimativas de nulos (mínima e máxima) são baseadas em pressupostos. Se a proporção de dados nulos for diferente da estimada, o espaço efetivamente ocupado poderá variar.</p>
            <p><strong>Overhead de Armazenamento:</strong><br>
            O cálculo se concentra apenas no tamanho dos dados, sem levar em conta o overhead gerado por índices, metadados e estruturas internas do banco de dados, que podem aumentar o espaço físico utilizado.</p>
            <p><strong>Fragmentação e Compressão:</strong><br>
            O mecanismo de armazenamento da base de dados pode aplicar compressão ou, ao contrário, apresentar fragmentação, o que pode causar variações entre o valor teórico e o espaço efetivamente utilizado.</p>
            <p><strong>Especificidades do Tipo de Dado VARCHAR2:</strong><br>
              <strong>Tamanho Definido vs. Tamanho Real:</strong> O campo VARCHAR2 é utilizado para armazenar cadeias de caracteres de tamanho variável. O valor definido (por exemplo, VARCHAR2(100)) especifica o número máximo de caracteres, mas o tamanho real em bytes pode variar de acordo com o conjunto de caracteres e a codificação utilizada.<br>
              <strong>Conjunto de Caracteres e Multi-Byte:</strong> Se o banco de dados utiliza um conjunto de caracteres multi-byte (como UTF-8), um mesmo caractere pode ocupar mais de um byte. Assim, mesmo que o campo permita 100 caracteres, o espaço ocupado pode ser superior dependendo dos caracteres armazenados.<br>
              <strong>Armazenamento Interno:</strong> Em alguns casos, o Oracle pode aplicar otimizações ou ter um overhead específico para colunas VARCHAR2, que não é considerado no cálculo simplificado da estimativa.
            </p>
            <p>Esses fatores mostram que o valor calculado é uma aproximação útil para o planejamento, mas que o tamanho real da tabela pode ser influenciado por diversas variáveis do ambiente e do próprio banco de dados.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
</div>
<br>
<br>
<br>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // ======================
  // Função para formatar MB ou GB
  // ======================
  function formatSize(bytes) {
    const MB = bytes / (1024 * 1024);
    if (MB < 1024) {
      return MB.toFixed(2) + ' MB';
    } else {
      const GB = MB / 1024;
      return GB.toFixed(2) + ' GB';
    }
  }

  // ======================
  // Referências e Inicialização
  // ======================
  const toggleExistingTable = document.getElementById('toggleExistingTable');
  const existingTableForm   = document.getElementById('existingTableForm');
  const newTableForm        = document.getElementById('newTableForm');
  const toggleLabel         = document.getElementById('toggleLabel');

  // Tabela Existente
  const ambienteSelect      = document.getElementById('ambienteSelect');
  const servicoSelect       = document.getElementById('servicoSelect');
  const schemaSelect        = document.getElementById('schemaSelect');
  const tabelaSelect        = document.getElementById('tabelaSelect');
  const btnBuscarAtributos  = document.getElementById('btnBuscarAtributos');
  const existingTableAttributesContainer = document.getElementById('existingTableAttributesContainer');
  const existingTableAttributes = document.querySelector('#existingTableAttributes tbody');
  const calcParamsContainer = document.getElementById('calcParamsContainer');
  const btnCalcularExistenteContainer = document.getElementById('btnCalcularExistenteContainer');
  const btnCalcularExistente = document.getElementById('btnCalcularExistente');
  const nulosMinInput  = document.getElementById('nulosMin');
  const nulosMaxInput  = document.getElementById('nulosMax');
  const dailyInsertsInput = document.getElementById('dailyInserts');

  // Nova Tabela
  const novaTecSelect       = document.getElementById('novaTecSelect');
  const tableNameInput      = document.getElementById('tableName');
  const btnAddAttribute     = document.getElementById('btnAddAttribute');
  const attributesTBody     = document.querySelector('#attributesTable tbody');
  const calcParamsContainerNova = document.getElementById('calcParamsContainerNova');
  const btnCalcularNovaContainer = document.getElementById('btnCalcularNovaContainer');
  const btnCalcularNova2    = document.getElementById('btnCalcularNova2');
  const nulosMinNovaInput   = document.getElementById('nulosMinNova');
  const nulosMaxNovaInput   = document.getElementById('nulosMaxNova');
  const dailyInsertsNovaInput = document.getElementById('dailyInsertsNova');

  // Área de Resultado
  const resultadoArea  = document.getElementById('resultadoArea');
  const resultadoTexto = document.getElementById('resultadoTexto');

  // Ação do back-end
  const acaoValue = document.getElementById('acaoHidden') ? document.getElementById('acaoHidden').value : '';

  let globalTiposDado = [];
  let selectedTecnologiaID = '';

  // ======================
  // Event Listeners para override (desabilitar o campo de inserções diárias se preenchido)
  // ======================
  document.getElementById('totalInserts').addEventListener('input', function() {
    if (this.value.trim() !== "") {
       dailyInsertsInput.disabled = true;
    } else {
       dailyInsertsInput.disabled = false;
    }
  });
  document.getElementById('totalInsertsNova').addEventListener('input', function() {
    if (this.value.trim() !== "") {
       dailyInsertsNovaInput.disabled = true;
    } else {
       dailyInsertsNovaInput.disabled = false;
    }
  });

  // ======================
  // Toggle
  // ======================
  toggleExistingTable.checked = false;
  existingTableForm.style.display = 'none';
  newTableForm.style.display = 'block';
  toggleLabel.textContent = 'Simular Tabela';

  toggleExistingTable.addEventListener('change', function() {
    if (this.checked) {
      existingTableForm.style.display = 'block';
      newTableForm.style.display = 'none';
      toggleLabel.textContent = 'Usar Tabela Existente';
      carregarAmbientes();
    } else {
      existingTableForm.style.display = 'none';
      newTableForm.style.display = 'block';
      toggleLabel.textContent = 'Simular Tabela';
      carregarTecnologias();
    }
    resetAllFields();
  });

  function resetAllFields() {
    resultadoArea.style.display = 'none';

    // Tabela Existente
    existingTableAttributesContainer.style.display = 'none';
    calcParamsContainer.style.display = 'none';
    btnCalcularExistenteContainer.style.display = 'none';
    existingTableAttributes.innerHTML = '';
    nulosMinInput.value = '';
    nulosMaxInput.value = '';
    dailyInsertsInput.value = '';
    document.getElementById('totalInserts').value = '';
    document.getElementById('overridePeriod').value = '';
    dailyInsertsInput.disabled = false;

    // Nova Tabela
    tableNameInput.value = '';
    attributesTBody.innerHTML = '';
    calcParamsContainerNova.style.display = 'none';
    btnCalcularNovaContainer.style.display = 'none';
    nulosMinNovaInput.value = '';
    nulosMaxNovaInput.value = '';
    dailyInsertsNovaInput.value = '';
    document.getElementById('totalInsertsNova').value = '';
    document.getElementById('overridePeriodNova').value = '';
    dailyInsertsNovaInput.disabled = false;

    ambienteSelect.innerHTML = '<option value="">Selecione...</option>';
    servicoSelect.innerHTML  = '<option value="">Selecione um Ambiente primeiro</option>';
    schemaSelect.innerHTML   = '<option value="">Selecione um Serviço primeiro</option>';
    tabelaSelect.innerHTML   = '<option value="">Selecione um Schema primeiro</option>';

    novaTecSelect.innerHTML = '<option value="">Selecione a Tecnologia...</option>';
    globalTiposDado = [];
    selectedTecnologiaID = '';
  }

  // ======================
  // Carregar Ambientes (Tabela Existente)
  // ======================
  function carregarAmbientes() {
    ambienteSelect.innerHTML = '<option value="">Carregando...</option>';
    $.ajax({
      url: 'catalogo_calc_tabela_ajax.php',
      method: 'GET',
      data: { acao: acaoValue, action: 'getAmbientes' },
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          const ambientes = resp.data;
          ambienteSelect.innerHTML = '<option value="">Selecione...</option>';
          ambientes.forEach(function(amb) {
            const opt = document.createElement('option');
            opt.value = amb;
            opt.textContent = amb;
            ambienteSelect.appendChild(opt);
          });
        } else {
          ambienteSelect.innerHTML = '<option value="">Erro ao carregar</option>';
          alert(resp.data || 'Erro ao carregar ambientes.');
        }
      },
      error: function(xhr, status, error) {
        ambienteSelect.innerHTML = '<option value="">Erro ao carregar</option>';
        alert('Erro ao carregar ambientes: ' + error);
      }
    });
  }

  ambienteSelect.addEventListener('change', function() {
    const ambiente = this.value;
    servicoSelect.innerHTML = '<option value="">Selecione um Ambiente primeiro</option>';
    schemaSelect.innerHTML  = '<option value="">Selecione um Serviço primeiro</option>';
    tabelaSelect.innerHTML  = '<option value="">Selecione um Schema primeiro</option>';
    if (!ambiente) return;

    $.ajax({
      url: 'catalogo_calc_tabela_ajax.php',
      method: 'GET',
      data: { acao: acaoValue, action: 'getServicos', ambiente: ambiente },
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          const servicos = resp.data;
          servicoSelect.innerHTML = '<option value="">Selecione...</option>';
          servicos.forEach(function(srv) {
            const opt = document.createElement('option');
            opt.value = srv.service_name;
            opt.textContent = srv.service_name;
            servicoSelect.appendChild(opt);
          });
        } else {
          servicoSelect.innerHTML = '<option value="">Erro ao carregar</option>';
        }
      },
      error: function() {
        servicoSelect.innerHTML = '<option value="">Erro ao carregar</option>';
      }
    });
  });

  servicoSelect.addEventListener('change', function() {
    const ambiente = ambienteSelect.value;
    const service_name = this.value;
    schemaSelect.innerHTML = '<option value="">Selecione um Serviço primeiro</option>';
    tabelaSelect.innerHTML = '<option value="">Selecione um Schema primeiro</option>';
    if (!ambiente || !service_name) return;

    $.ajax({
      url: 'catalogo_calc_tabela_ajax.php',
      method: 'GET',
      data: { acao: acaoValue, action: 'getSchemas', ambiente: ambiente, service_name: service_name },
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          const schemas = resp.data;
          schemaSelect.innerHTML = '<option value="">Selecione...</option>';
          schemas.forEach(function(sch) {
            const opt = document.createElement('option');
            opt.value = sch.schema_name;
            opt.textContent = sch.schema_name;
            schemaSelect.appendChild(opt);
          });
        } else {
          schemaSelect.innerHTML = '<option value="">Erro ao carregar</option>';
        }
      },
      error: function() {
        schemaSelect.innerHTML = '<option value="">Erro ao carregar</option>';
      }
    });
  });

  schemaSelect.addEventListener('change', function() {
    const ambiente = ambienteSelect.value;
    const service_name = servicoSelect.value;
    const schema_name  = this.value;
    tabelaSelect.innerHTML = '<option value="">Selecione um Schema primeiro</option>';
    if (!ambiente || !service_name || !schema_name) return;

    $.ajax({
      url: 'catalogo_calc_tabela_ajax.php',
      method: 'GET',
      data: { acao: acaoValue, action: 'getTabelas', ambiente: ambiente, service_name: service_name, schema_name: schema_name },
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          const tabelas = resp.data;
          tabelaSelect.innerHTML = '<option value="">Selecione...</option>';
          tabelas.forEach(function(tab) {
            const opt = document.createElement('option');
            opt.value = tab.table_name;
            opt.textContent = tab.table_name;
            tabelaSelect.appendChild(opt);
          });
        } else {
          tabelaSelect.innerHTML = '<option value="">Erro ao carregar</option>';
        }
      },
      error: function() {
        tabelaSelect.innerHTML = '<option value="">Erro ao carregar</option>';
      }
    });
  });

  // Botão Buscar Atributos
  btnBuscarAtributos.addEventListener('click', function() {
    const ambiente = ambienteSelect.value;
    const servico  = servicoSelect.value;
    const schema   = schemaSelect.value;
    const tabela   = tabelaSelect.value;

    if (!ambiente || !servico || !schema || !tabela) {
      alert('Selecione todos os campos para buscar atributos.');
      return;
    }

    btnBuscarAtributos.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Buscando...';
    btnBuscarAtributos.disabled = true;

    $.ajax({
      url: 'catalogo_calc_tabela_ajax.php',
      method: 'GET',
      data: { acao: acaoValue, action: 'getAtributos', ambiente: ambiente, service_name: servico, schema_name: schema, table_name: tabela },
      dataType: 'json',
      success: function(resp) {
        btnBuscarAtributos.innerHTML = 'Buscar Atributos';
        btnBuscarAtributos.disabled = false;

        if (resp.success && resp.data) {
          existingTableAttributes.innerHTML = '';
          if (resp.data.length > 0) {
            resp.data.forEach(function(col) {
              const tr = document.createElement('tr');
              tr.innerHTML = `
                <td>${col.column_name}</td>
                <td>${col.data_type}</td>
                <td>${col.data_length || ''}</td>
                <td>${col.is_nullable === 'YES' ? 'SIM' : 'NÃO'}</td>
              `;
              existingTableAttributes.appendChild(tr);
            });
            existingTableAttributesContainer.style.display = 'block';
            calcParamsContainer.style.display = 'block';
            btnCalcularExistenteContainer.style.display = 'block';
            resultadoArea.style.display = 'none';
          } else {
            alert('Nenhum atributo encontrado para essa tabela.');
          }
        } else {
          alert('Erro: ' + (resp.data || 'Não foi possível carregar atributos.'));
        }
      },
      error: function(xhr, status, error) {
        btnBuscarAtributos.innerHTML = 'Buscar Atributos';
        btnBuscarAtributos.disabled = false;
        alert('Erro ao buscar atributos: ' + error);
      }
    });
  });

  // Botão Calcular (Tabela Existente) com override e linha extra para período digitado
  btnCalcularExistente.addEventListener('click', function() {
    btnCalcularExistente.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Calculando...';
    btnCalcularExistente.disabled = true;

    setTimeout(() => {
      const nulosMin = parseFloat(nulosMinInput.value);
      const nulosMax = parseFloat(nulosMaxInput.value);

      // Coleta os valores override e daily
      const totalInsertsOverride = parseFloat(document.getElementById('totalInserts').value);
      const overridePeriod = parseFloat(document.getElementById('overridePeriod').value);
      const dailyInserts = parseFloat(dailyInsertsInput.value);

      if (isNaN(nulosMin) || isNaN(nulosMax) ||
          nulosMin < 0 || nulosMin > 100 ||
          nulosMax < 0 || nulosMax > 100 ||
          (isNaN(totalInsertsOverride) || isNaN(overridePeriod) || overridePeriod <= 0) && (isNaN(dailyInserts) || dailyInserts < 1)
         ) {
        alert('Preencha corretamente as estimativas (0-100%) e as inserções diárias (>0) ou utilize o override.');
        btnCalcularExistente.innerHTML = 'Calcular';
        btnCalcularExistente.disabled = false;
        return;
      }
      if (nulosMax <= nulosMin) {
        alert('A Estimativa Máxima de Nulos (%) deve ser maior que a Estimativa Mínima de Nulos (%).');
        btnCalcularExistente.innerHTML = 'Calcular';
        btnCalcularExistente.disabled = false;
        return;
      }

      const rows = existingTableAttributes.querySelectorAll('tr');
      if (rows.length === 0) {
        alert('Nenhum atributo encontrado para cálculo.');
        btnCalcularExistente.innerHTML = 'Calcular';
        btnCalcularExistente.disabled = false;
        return;
      }

      let totalBytesMin = 0; 
      let totalBytesMax = 0; 

      rows.forEach(row => {
        const cols = row.querySelectorAll('td');
        const dataType = (cols[1] || {}).textContent || '';
        const length   = parseInt((cols[2] || {}).textContent) || 0;
        const isNullableText = (cols[3] || {}).textContent || 'NÃO';
        
        let usage = 0;
        switch (dataType.toUpperCase()) {
          case 'VARCHAR':
          case 'VARCHAR2':
          case 'CHAR':
            usage = length;
            break;
          case 'NUMBER':
            usage = 8;
            break;
          case 'DATE':
            usage = 7;
            break;
          default:
            usage = 1;
        }

        if (isNullableText === 'SIM') {
          const portionMin = (100 - nulosMin) / 100.0;
          const portionMax = (100 - nulosMax) / 100.0;
          totalBytesMin += usage * portionMin;
          totalBytesMax += usage * portionMax;
        } else {
          totalBytesMin += usage;
          totalBytesMax += usage;
        }
      });

      const days30  = 30;
      const days180 = 180;
      const days365 = 365;
      let effectiveDailyInserts;

      if (!isNaN(totalInsertsOverride) && !isNaN(overridePeriod) && overridePeriod > 0) {
          effectiveDailyInserts = totalInsertsOverride / overridePeriod;
      } else {
          effectiveDailyInserts = dailyInserts;
      }

      const total30Min  = totalBytesMin  * effectiveDailyInserts * days30;
      const total180Min = totalBytesMin  * effectiveDailyInserts * days180;
      const total365Min = totalBytesMin  * effectiveDailyInserts * days365;

      const total30Max  = totalBytesMax  * effectiveDailyInserts * days30;
      const total180Max = totalBytesMax  * effectiveDailyInserts * days180;
      const total365Max = totalBytesMax  * effectiveDailyInserts * days365;

      let html = `<h5>Estimativas de Crescimento</h5>`;
      html += `<p><strong>Parâmetros:</strong><br>`;
      html += `- Mín Nulos = ${nulosMin}%<br>`;
      html += `- Máx Nulos = ${nulosMax}%<br>`;
      if (!isNaN(totalInsertsOverride) && !isNaN(overridePeriod) && overridePeriod > 0) {
          html += `- Override: ${totalInsertsOverride} registros em ${overridePeriod} dias`;
      } else {
          html += `- Inserções Diárias = ${dailyInserts}`;
      }
      html += `</p>`;

      html += '<table class="table table-bordered">';
      html += '  <thead><tr><th>Período</th><th>Estimativa Mín</th><th>Estimativa Máx</th><th>Registros</th></tr></thead>';
      html += '  <tbody>';
      html += `    <tr><td>30 dias</td><td>${formatSize(total30Min)}</td><td>${formatSize(total30Max)}</td><td>${Math.floor(effectiveDailyInserts * days30)}</td></tr>`;
      html += `    <tr><td>6 meses (180 dias)</td><td>${formatSize(total180Min)}</td><td>${formatSize(total180Max)}</td><td>${Math.floor(effectiveDailyInserts * days180)}</td></tr>`;
      html += `    <tr><td>1 ano (365 dias)</td><td>${formatSize(total365Min)}</td><td>${formatSize(total365Max)}</td><td>${Math.floor(effectiveDailyInserts * days365)}</td></tr>`;
      // Linha extra para o período digitado no override
      if (!isNaN(totalInsertsOverride) && !isNaN(overridePeriod) && overridePeriod > 0) {
          const totalOverrideMin = totalBytesMin * effectiveDailyInserts * overridePeriod;
          const totalOverrideMax = totalBytesMax * effectiveDailyInserts * overridePeriod;
          html += `<tr><td>${overridePeriod} dias (override)</td><td>${formatSize(totalOverrideMin)}</td><td>${formatSize(totalOverrideMax)}</td><td>${Math.floor(effectiveDailyInserts * overridePeriod)}</td></tr>`;
      }
      html += '  </tbody>';
      html += '</table>';

      // Adiciona o botão de Help ao final da tabela
      html += '<div class="text-end mt-3"><button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#helpModal">Help</button></div>';

      resultadoTexto.innerHTML = html;
      resultadoArea.style.display = 'block';

      btnCalcularExistente.innerHTML = 'Calcular';
      btnCalcularExistente.disabled = false;
    }, 50);
  });

  // ========== Nova Tabela: Carregar Tecnologias ==========
  function carregarTecnologias() {
    novaTecSelect.innerHTML = '<option value="">Carregando...</option>';
    $.ajax({
      url: 'catalogo_calc_tabela_ajax.php',
      method: 'GET',
      data: { acao: acaoValue, action: 'getTecnologias' },
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          const tecnologias = resp.data;
          novaTecSelect.innerHTML = '<option value="">Selecione a Tecnologia...</option>';
          tecnologias.forEach(function(tec) {
            const opt = document.createElement('option');
            opt.value = tec.id;
            opt.textContent = tec.tecnlogia + ' ' + tec.versao;
            novaTecSelect.appendChild(opt);
          });
        } else {
          novaTecSelect.innerHTML = '<option value="">Erro ao carregar</option>';
          alert(resp.data || 'Erro ao carregar tecnologias.');
        }
      },
      error: function() {
        novaTecSelect.innerHTML = '<option value="">Erro ao carregar</option>';
      }
    });
  }

  novaTecSelect.addEventListener('change', function() {
    const tecID = this.value;
    selectedTecnologiaID = tecID;
    if (!tecID) {
      globalTiposDado = [];
      return;
    }
    $.ajax({
      url: 'catalogo_calc_tabela_ajax.php',
      method: 'GET',
      data: { acao: acaoValue, action: 'getTiposDado', fk_tecnologia: tecID },
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          globalTiposDado = resp.data; 
        } else {
          globalTiposDado = [];
          alert(resp.data || 'Erro ao carregar tipos de dado.');
        }
      },
      error: function() {
        globalTiposDado = [];
        alert('Erro ao carregar tipos de dado.');
      }
    });
  });

  // Botão "Adicionar Atributo" (Nova Tabela)
  btnAddAttribute.addEventListener('click', function() {
    if (!selectedTecnologiaID) {
      alert('Selecione a tecnologia antes de adicionar um atributo.');
      return;
    }
    if (globalTiposDado.length === 0) {
      alert('Não há tipos de dado carregados para esta tecnologia.');
      return;
    }

    const tr = document.createElement('tr');
    let selectTipoHTML = `<select class="form-select tipoDadoSelect">`;
    selectTipoHTML += `<option value="">Selecione...</option>`;
    globalTiposDado.forEach(function(td) {
      selectTipoHTML += `<option value="${td.tipo}">${td.tipo}</option>`;
    });
    selectTipoHTML += `</select>`;

    tr.innerHTML = `
      <td>
        <input type="text" class="form-control" placeholder="Ex.: COLUNA_X">
      </td>
      <td>
        ${selectTipoHTML}
      </td>
      <td>
        <input type="number" class="form-control attrLengthInput" placeholder="Tamanho" min="1">
      </td>
      <td class="text-center">
        <input type="checkbox" checked>
      </td>
      <td class="text-center">
        <button type="button" class="btn btn-danger btn-sm btnRemoveAttr">
          Remover
        </button>
      </td>
    `;
    attributesTBody.appendChild(tr);

    const tipoSelect = tr.querySelector('.tipoDadoSelect');
    const lengthInput = tr.querySelector('.attrLengthInput');

    tipoSelect.addEventListener('change', function() {
      const val = this.value.toUpperCase();
      if (['DATE','TIMESTAMP','BLOB'].includes(val)) {
        lengthInput.value = '';
        lengthInput.disabled = true;
      } else {
        lengthInput.disabled = false;
      }
    });

    if (attributesTBody.querySelectorAll('tr').length === 1) {
      calcParamsContainerNova.style.display = 'block';
      btnCalcularNovaContainer.style.display = 'block';
    }
  });

  attributesTBody.addEventListener('click', function(e) {
    if (e.target.classList.contains('btnRemoveAttr')) {
      e.target.closest('tr').remove();
    }
    if (attributesTBody.querySelectorAll('tr').length === 0) {
      calcParamsContainerNova.style.display = 'none';
      btnCalcularNovaContainer.style.display = 'none';
    }
  });

  // Botão Calcular (Nova Tabela) com override e linha extra para período digitado
  btnCalcularNova2.addEventListener('click', function() {
    btnCalcularNova2.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Calculando...';
    btnCalcularNova2.disabled = true;

    setTimeout(() => {
      const tableName = tableNameInput.value.trim();
      if (!tableName) {
        alert('Informe o nome da tabela.');
        btnCalcularNova2.innerHTML = 'Calcular';
        btnCalcularNova2.disabled = false;
        return;
      }

      const rows = attributesTBody.querySelectorAll('tr');
      if (rows.length === 0) {
        alert('Adicione ao menos um atributo.');
        btnCalcularNova2.innerHTML = 'Calcular';
        btnCalcularNova2.disabled = false;
        return;
      }

      const nulosMin = parseFloat(nulosMinNovaInput.value);
      const nulosMax = parseFloat(nulosMaxNovaInput.value);
      const totalInsertsOverride = parseFloat(document.getElementById('totalInsertsNova').value);
      const overridePeriod = parseFloat(document.getElementById('overridePeriodNova').value);
      const dailyInserts = parseFloat(dailyInsertsNovaInput.value);

      if (isNaN(nulosMin) || isNaN(nulosMax) ||
          nulosMin < 0 || nulosMin > 100 ||
          nulosMax < 0 || nulosMax > 100 ||
          (isNaN(totalInsertsOverride) || isNaN(overridePeriod) || overridePeriod <= 0) && (isNaN(dailyInserts) || dailyInserts < 1)
         ) {
        alert('Preencha corretamente as estimativas (0-100%) e as inserções diárias (>0) ou utilize o override.');
        btnCalcularNova2.innerHTML = 'Calcular';
        btnCalcularNova2.disabled = false;
        return;
      }
      if (nulosMax <= nulosMin) {
        alert('A Estimativa Máxima de Nulos (%) deve ser maior que a Estimativa Mínima de Nulos (%).');
        btnCalcularNova2.innerHTML = 'Calcular';
        btnCalcularNova2.disabled = false;
        return;
      }

      const mediaBytesPorTipo = {
        'DATE': 7,
        'TIMESTAMP': 10,
        'BLOB': 1073741824
      };

      let totalBytesMin = 0;
      let totalBytesMax = 0;
      let valid = true;

      rows.forEach(row => {
        const cols = row.querySelectorAll('td');
        const tipoSelect    = cols[1].querySelector('select');
        const lengthInput   = cols[2].querySelector('input');
        const nuloCheckbox  = cols[3].querySelector('input');

        const dataType = (tipoSelect.value || '').toUpperCase();

        if (!dataType) {
          alert('Selecione o tipo de dado em todos os atributos.');
          valid = false;
          return;
        }

        let usage = 0;
        if (['DATE','TIMESTAMP','BLOB'].includes(dataType)) {
          usage = mediaBytesPorTipo[dataType] || 1;
        } else {
          const lengthVal = parseInt(lengthInput.value) || 0;
          if (lengthVal <= 0) {
            alert(`O tipo "${dataType}" requer um tamanho > 0.`);
            valid = false;
            return;
          }
          usage = lengthVal;
        }

        if (nuloCheckbox.checked) {
          const portionMin = (100 - nulosMin) / 100.0;
          const portionMax = (100 - nulosMax) / 100.0;
          totalBytesMin += usage * portionMin;
          totalBytesMax += usage * portionMax;
        } else {
          totalBytesMin += usage;
          totalBytesMax += usage;
        }
      });

      if (!valid) {
        btnCalcularNova2.innerHTML = 'Calcular';
        btnCalcularNova2.disabled = false;
        return;
      }

      const days30  = 30;
      const days180 = 180;
      const days365 = 365;
      let effectiveDailyInserts;

      if (!isNaN(totalInsertsOverride) && !isNaN(overridePeriod) && overridePeriod > 0) {
          effectiveDailyInserts = totalInsertsOverride / overridePeriod;
      } else {
          effectiveDailyInserts = dailyInserts;
      }

      const total30Min  = totalBytesMin  * effectiveDailyInserts * days30;
      const total180Min = totalBytesMin  * effectiveDailyInserts * days180;
      const total365Min = totalBytesMin  * effectiveDailyInserts * days365;

      const total30Max  = totalBytesMax  * effectiveDailyInserts * days30;
      const total180Max = totalBytesMax  * effectiveDailyInserts * days180;
      const total365Max = totalBytesMax  * effectiveDailyInserts * days365;

      let html = `<h5>Estimativas de Crescimento (Nova Tabela)</h5>`;
      html += `<p><strong>Tabela:</strong> ${tableName}<br>`;
      html += `- Mín Nulos = ${nulosMin}%<br>`;
      html += `- Máx Nulos = ${nulosMax}%<br>`;
      if (!isNaN(totalInsertsOverride) && !isNaN(overridePeriod) && overridePeriod > 0) {
          html += `- Override: ${totalInsertsOverride} registros em ${overridePeriod} dias`;
      } else {
          html += `- Inserções Diárias = ${dailyInserts}`;
      }
      html += `</p>`;

      html += '<table class="table table-bordered">';
      html += '  <thead><tr><th>Período</th><th>Estimativa Mín</th><th>Estimativa Máx</th><th>Registros</th></tr></thead>';
      html += '  <tbody>';
      html += `    <tr><td>30 dias</td><td>${formatSize(total30Min)}</td><td>${formatSize(total30Max)}</td><td>${Math.floor(effectiveDailyInserts * days30)}</td></tr>`;
      html += `    <tr><td>6 meses (180 dias)</td><td>${formatSize(total180Min)}</td><td>${formatSize(total180Max)}</td><td>${Math.floor(effectiveDailyInserts * days180)}</td></tr>`;
      html += `    <tr><td>1 ano (365 dias)</td><td>${formatSize(total365Min)}</td><td>${formatSize(total365Max)}</td><td>${Math.floor(effectiveDailyInserts * days365)}</td></tr>`;
      if (!isNaN(totalInsertsOverride) && !isNaN(overridePeriod) && overridePeriod > 0) {
          const totalOverrideMin = totalBytesMin * effectiveDailyInserts * overridePeriod;
          const totalOverrideMax = totalBytesMax * effectiveDailyInserts * overridePeriod;
          html += `<tr><td>${overridePeriod} dias (override)</td><td>${formatSize(totalOverrideMin)}</td><td>${formatSize(totalOverrideMax)}</td><td>${Math.floor(effectiveDailyInserts * overridePeriod)}</td></tr>`;
      }
      html += '  </tbody>';
      html += '</table>';

      // Adiciona o botão de Help ao final da tabela
      html += '<div class="text-end mt-3"><button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#helpModal">Help</button></div>';

      resultadoTexto.innerHTML = html;
      resultadoArea.style.display = 'block';

      btnCalcularNova2.innerHTML = 'Calcular';
      btnCalcularNova2.disabled = false;
    }, 50);
  });

  // Se toggleExistingTable inicia "false", carregamos tecnologias
  carregarTecnologias();
</script>
</body>
<br>
<br>
<br>
<br>
<br>
</html>
