<?php
session_start();

require_once __DIR__ . '/../module/conecta.php';
require_once __DIR__ . '/../module/functions.php';


// Conectar (exemplo usando seu objeto "portal")
$pg = new portal();
$conexao = $pg->conectar_obj();

// ----------------------------------------------------------------------
// Se o parâmetro "detail_table" estiver definido, exibe os detalhes da tabela
if (isset($_GET['detail_table'])) {

    // Recebe os parâmetros necessários para a comparação dos detalhes
    $ambiente1 = isset($_GET['ambiente1']) ? trim($_GET['ambiente1']) : "";
    $ambiente2 = isset($_GET['ambiente2']) ? trim($_GET['ambiente2']) : "";
    // Aqui, o usuário informa o nome base do serviço (sem o sufixo)
    $service   = isset($_GET['service']) ? strtoupper(trim($_GET['service'])) : "";
    $schema    = isset($_GET['schema']) ? strtoupper(trim($_GET['schema'])) : "";
    $table     = isset($_GET['detail_table']) ? strtoupper(trim($_GET['detail_table'])) : "";
    $sufixo1   = isset($_GET['sufixo1']) ? trim($_GET['sufixo1']) : "";
    $sufixo2   = isset($_GET['sufixo2']) ? trim($_GET['sufixo2']) : "";

    if (empty($ambiente1) || empty($ambiente2) || empty($service) || empty($schema) || empty($table)) {
        die("Parâmetros insuficientes para a comparação dos detalhes.");
    }

    // Para o nome da tabela: para o ambiente 2, se o sufixo não estiver presente, acrescenta-o.
    // (Se for necessário, você pode fazer algo semelhante para o ambiente 1 – aqui o código não altera o table para ambiente 1)
    $tableAmb1 = $table;
    $tableAmb2 = $table;
    

    /*
      A ideia é que a query compare o nome do serviço removendo, se presente, o sufixo informado.
      Dessa forma, se o registro em HOMOLOGACAO estiver armazenado como "MSERVICEHO" e em
      DESENVOLVIMENTO como "MSERVICEDS", ao remover os sufixos (HO e DS, respectivamente),
      ambos serão comparados com o valor base "MSERVICE".
    */
    $query_columns = "
        SELECT column_id, column_name, data_type, data_length, column_comments 
        FROM administracao.catalog_table_content 
        WHERE ambiente = $1 
          AND (
                CASE 
                  WHEN LENGTH($3) > 0 AND RIGHT(UPPER(service_name), LENGTH($3)) = $3 
                  THEN LEFT(UPPER(service_name), LENGTH(UPPER(service_name))-LENGTH($3))
                  ELSE UPPER(service_name)
                END
              ) = $2 
          AND UPPER(schema_name) = $4 
          AND UPPER(table_name) = $5 
        ORDER BY column_id
    ";
    /* 
      Parâmetros para ambiente 1:
        $1 = $ambiente1
        $2 = $service          (valor base, sem sufixo)
        $3 = $sufixo1          (sufixo a remover do final, se existir)
        $4 = $schema
        $5 = $tableAmb1
      Parâmetros para ambiente 2:
        $1 = $ambiente2
        $2 = $service          (valor base)
        $3 = $sufixo2          (sufixo a remover)
        $4 = $schema
        $5 = $tableAmb2
    */
    
    // Ambiente 1:
    $result1 = pg_query_params($conexao, $query_columns, [$ambiente1, $service, $sufixo1, $schema, $tableAmb1]);
    if (!$result1) {
        die("Erro na consulta de colunas para ambiente 1: " . pg_last_error($conexao));
    }
    $columns1 = pg_fetch_all($result1);
    
    // Ambiente 2:
    $result2 = pg_query_params($conexao, $query_columns, [$ambiente2, $service, $sufixo2, $schema, $tableAmb2]);
    if (!$result2) {
        die("Erro na consulta de colunas para ambiente 2: " . pg_last_error($conexao));
    }
    $columns2 = pg_fetch_all($result2);

    // Organiza os resultados em arrays indexados pelo nome da coluna (em MAIÚSCULO)
    $cols1 = [];
    if ($columns1) {
        foreach ($columns1 as $col) {
            $colName = strtoupper($col['column_name']);
            $cols1[$colName] = $col;
        }
    }
    $cols2 = [];
    if ($columns2) {
        foreach ($columns2 as $col) {
            $colName = strtoupper($col['column_name']);
            $cols2[$colName] = $col;
        }
    }
    // Cria uma lista unificada de colunas usando o column_id (do ambiente 1 se disponível)
    $allColumns = [];
    foreach ($cols1 as $colName => $colData) {
        $allColumns[$colName] = $colData['column_id'];
    }
    foreach ($cols2 as $colName => $colData) {
        if (!isset($allColumns[$colName])) {
            $allColumns[$colName] = $colData['column_id'];
        }
    }
    asort($allColumns);
    $orderedColumnNames = array_keys($allColumns);

    // Função para formatar os detalhes da coluna (exibe data_type, data_length e column_comments)
    function formatColumnDetails($col) {
        if (!$col) return "";
        $details = $col['data_type'];
        if ($col['data_length']) {
            $details .= "(" . $col['data_length'] . ")";
        }
        if (!empty($col['column_comments'])) {
            $details .= " - " . $col['column_comments'];
        }
        return $details;
    }

    // Monta a URL de "Voltar" com os mesmos parâmetros (removendo apenas detail_table)
    $backParams = $_GET;
    unset($backParams['detail_table']);
    $backUrl = $_SERVER['PHP_SELF'] . "?" . http_build_query($backParams);
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Detalhes da Tabela - <?= htmlspecialchars($table) ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .ok { background-color: #d4edda; }         /* Verde */
            .diff { background-color: #fff3cd; }       /* Amarelo */
            .missing { background-color: #f8d7da; }    /* Vermelho */
        </style>
    </head>
    <body>
    <div class="container mt-4">
        <h3>Detalhes da Tabela: <?= htmlspecialchars($table) ?></h3>
        <p>
            Ambiente 1: <?= htmlspecialchars($ambiente1) ?> &nbsp;|&nbsp;
            Ambiente 2: <?= htmlspecialchars($ambiente2) ?> &nbsp;|&nbsp;
            Serviço: <?= htmlspecialchars($service) ?> (usando <?= htmlspecialchars($service1) ?> / <?= htmlspecialchars($service2) ?>) &nbsp;|&nbsp;
            Schema: <?= htmlspecialchars($schema) ?>
        </p>
        <!-- Tabela de Detalhes com 4 colunas: coluna e detalhes para cada ambiente -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Coluna (<?= htmlspecialchars($ambiente1) ?>)</th>
                    <th>Detalhes (<?= htmlspecialchars($ambiente1) ?>)</th>
                    <th>Coluna (<?= htmlspecialchars($ambiente2) ?>)</th>
                    <th>Detalhes (<?= htmlspecialchars($ambiente2) ?>)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderedColumnNames as $colName):
                    $col1 = isset($cols1[$colName]) ? $cols1[$colName] : null;
                    $col2 = isset($cols2[$colName]) ? $cols2[$colName] : null;
                    
                    $details1 = formatColumnDetails($col1);
                    $details2 = formatColumnDetails($col2);
                    
                    // Define a classe da linha:
                    // - "ok" (verde) se a coluna existe em ambos os ambientes e os detalhes são iguais.
                    // - "diff" (amarelo) se existir em ambos, mas os detalhes forem diferentes.
                    // - "missing" (vermelho) se a coluna faltar em algum ambiente.
                    if ($col1 && $col2) {
                        $rowClass = ($details1 === $details2) ? "ok" : "diff";
                    } else {
                        $rowClass = "missing";
                    }
                ?>
                <tr class="<?= $rowClass ?>">
                    <td><?= $col1 ? htmlspecialchars($colName) : "" ?></td>
                    <td><?= $col1 ? htmlspecialchars($details1) : "" ?></td>
                    <td><?= $col2 ? htmlspecialchars($colName) : "" ?></td>
                    <td><?= $col2 ? htmlspecialchars($details2) : "" ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="javascript:history.back()" class="btn btn-success">Voltar</a>
    </div>
    <BR>
    <BR>
    <BR>
    <BR>
    <BR>
    </body>
    </html>
    <?php
    exit;
}

// ----------------------------------------------------------------------
// Caso contrário, exibe o dashboard principal
if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && isset($acao) && $acao != null) {

    // Verifica se o usuário tem acesso à tela
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {

        $mensagem = '';
        $erro_banco = '';

        // Consulta para carregar os ambientes disponíveis (para os combos)
        $query_env = "SELECT DISTINCT ambiente FROM administracao.catalog_table_content ORDER BY ambiente";
        $result_env = pg_query($conexao, $query_env);
        if (!$result_env) {
            die("Erro na consulta dos ambientes: " . pg_last_error($conexao));
        }
        $environments = pg_fetch_all($result_env);

        // Recupera os parâmetros do formulário
        $selected_env1 = isset($_GET['ambiente1']) ? trim($_GET['ambiente1']) : "";
        $selected_env2 = isset($_GET['ambiente2']) ? trim($_GET['ambiente2']) : "";
        $sufixo1 = isset($_GET['sufixo1']) ? trim($_GET['sufixo1']) : "";
        $sufixo2 = isset($_GET['sufixo2']) ? trim($_GET['sufixo2']) : "";
        $service_filter = isset($_GET['service_filter']) ? trim($_GET['service_filter']) : "";
        $schema_filter = isset($_GET['schema_filter']) ? trim($_GET['schema_filter']) : "";

        $service_filter_upper = strtoupper($service_filter);
        $schema_filter_upper  = strtoupper($schema_filter);

        if (!empty($selected_env1) && !empty($selected_env2) && $selected_env1 == $selected_env2) {
            $erro_banco = "Por favor, selecione dois ambientes diferentes para comparação.";
        }

        function removeSuffix($string, $sufixo) {
            if (!empty($sufixo) && substr($string, -strlen($sufixo)) === $sufixo) {
                return substr($string, 0, -strlen($sufixo));
            }
            return $string;
        }

        // Repovoar os combos de Serviço e Schema (do Ambiente 1)
        $all_services1 = [];
        $all_schemas1 = [];
        if (!empty($selected_env1)) {
            $query_all_services = "SELECT DISTINCT service_name FROM administracao.catalog_table_content WHERE ambiente = $1 ORDER BY service_name";
            $result_all_services = pg_query_params($conexao, $query_all_services, [$selected_env1]);
            if (!$result_all_services) {
                die("Erro na consulta de serviços: " . pg_last_error($conexao));
            }
            $all_services1 = pg_fetch_all($result_all_services);
            if ($all_services1) {
                foreach ($all_services1 as &$row) {
                    $row['service_name'] = strtoupper(removeSuffix($row['service_name'], $sufixo1));
                }
                unset($row);
            }
            $query_all_schemas = "SELECT DISTINCT schema_name FROM administracao.catalog_table_content WHERE ambiente = $1 ORDER BY schema_name";
            $result_all_schemas = pg_query_params($conexao, $query_all_schemas, [$selected_env1]);
            if (!$result_all_schemas) {
                die("Erro na consulta de schemas: " . pg_last_error($conexao));
            }
            $all_schemas1 = pg_fetch_all($result_all_schemas);
            if ($all_schemas1) {
                foreach ($all_schemas1 as &$row) {
                    $row['schema_name'] = strtoupper($row['schema_name']);
                }
                unset($row);
            }
        }

        // Se os dois ambientes estiverem selecionados e não houver erro, realiza as consultas para comparação
        if (!empty($selected_env1) && !empty($selected_env2) && empty($erro_banco)) {

            /* --- 1. Comparação de Serviços --- */
            $query_services1 = "SELECT DISTINCT service_name FROM administracao.catalog_table_content WHERE ambiente = $1 ORDER BY service_name";
            $result_services1 = pg_query_params($conexao, $query_services1, [$selected_env1]);
            if (!$result_services1) {
                die("Erro na consulta de serviços (ambiente 1): " . pg_last_error($conexao));
            }
            $services1 = pg_fetch_all($result_services1);
            if ($services1) {
                foreach ($services1 as &$row) {
                    $row['service_name'] = strtoupper(removeSuffix($row['service_name'], $sufixo1));
                }
                unset($row);
                if (!empty($service_filter_upper)) {
                    $services1 = array_filter($services1, function($row) use ($service_filter_upper) {
                        return $row['service_name'] == $service_filter_upper;
                    });
                }
            }
            $query_services2 = "SELECT DISTINCT service_name FROM administracao.catalog_table_content WHERE ambiente = $1 ORDER BY service_name";
            $result_services2 = pg_query_params($conexao, $query_services2, [$selected_env2]);
            if (!$result_services2) {
                die("Erro na consulta de serviços (ambiente 2): " . pg_last_error($conexao));
            }
            $services2 = pg_fetch_all($result_services2);
            if ($services2) {
                foreach ($services2 as &$row) {
                    $row['service_name'] = strtoupper(removeSuffix($row['service_name'], $sufixo2));
                }
                unset($row);
                if (!empty($service_filter_upper)) {
                    $services2 = array_filter($services2, function($row) use ($service_filter_upper) {
                        return $row['service_name'] == $service_filter_upper;
                    });
                }
            }
            $services2_arr = $services2 ? array_column($services2, 'service_name') : [];

            /* --- 2. Comparação de Schemas --- */
            $query_schemas1 = "SELECT service_name, schema_name FROM administracao.catalog_table_content WHERE ambiente = $1 GROUP BY service_name, schema_name ORDER BY service_name, schema_name";
            $result_schemas1 = pg_query_params($conexao, $query_schemas1, [$selected_env1]);
            if (!$result_schemas1) {
                die("Erro na consulta de schemas (ambiente 1): " . pg_last_error($conexao));
            }
            $schemas1 = pg_fetch_all($result_schemas1);
            if ($schemas1) {
                foreach ($schemas1 as &$row) {
                    $row['service_name'] = strtoupper(removeSuffix($row['service_name'], $sufixo1));
                    $row['schema_name'] = strtoupper($row['schema_name']);
                }
                unset($row);
                if (!empty($service_filter_upper)) {
                    $schemas1 = array_filter($schemas1, function($row) use ($service_filter_upper) {
                        return $row['service_name'] == $service_filter_upper;
                    });
                }
                if (!empty($schema_filter_upper)) {
                    $schemas1 = array_filter($schemas1, function($row) use ($schema_filter_upper) {
                        return $row['schema_name'] == $schema_filter_upper;
                    });
                }
            }
            $query_schemas2 = "SELECT service_name, schema_name FROM administracao.catalog_table_content WHERE ambiente = $1 GROUP BY service_name, schema_name ORDER BY service_name, schema_name";
            $result_schemas2 = pg_query_params($conexao, $query_schemas2, [$selected_env2]);
            if (!$result_schemas2) {
                die("Erro na consulta de schemas (ambiente 2): " . pg_last_error($conexao));
            }
            $schemas2 = pg_fetch_all($result_schemas2);
            if ($schemas2) {
                foreach ($schemas2 as &$row) {
                    $row['service_name'] = strtoupper(removeSuffix($row['service_name'], $sufixo2));
                    $row['schema_name'] = strtoupper($row['schema_name']);
                }
                unset($row);
                if (!empty($service_filter_upper)) {
                    $schemas2 = array_filter($schemas2, function($row) use ($service_filter_upper) {
                        return $row['service_name'] == $service_filter_upper;
                    });
                }
                if (!empty($schema_filter_upper)) {
                    $schemas2 = array_filter($schemas2, function($row) use ($schema_filter_upper) {
                        return $row['schema_name'] == $schema_filter_upper;
                    });
                }
            }
            $schemas2_arr = [];
            if ($schemas2) {
                foreach ($schemas2 as $row) {
                    $key = $row['service_name'] . '|' . $row['schema_name'];
                    $schemas2_arr[$key] = true;
                }
            }

            /* --- 3. Comparação de Tabelas e Atributos --- */
            $query_tables1 = "
                SELECT service_name, schema_name, table_name, COUNT(*) AS total_attributes 
                FROM administracao.catalog_table_content 
                WHERE ambiente = $1 
                GROUP BY service_name, schema_name, table_name 
                ORDER BY service_name, schema_name, table_name
            ";
            $result_tables1 = pg_query_params($conexao, $query_tables1, [$selected_env1]);
            if (!$result_tables1) {
                die("Erro na consulta de tabelas (ambiente 1): " . pg_last_error($conexao));
            }
            $tables1 = pg_fetch_all($result_tables1);
            if ($tables1) {
                foreach ($tables1 as &$row) {
                    $row['service_name'] = strtoupper(removeSuffix($row['service_name'], $sufixo1));
                    $row['schema_name']  = strtoupper($row['schema_name']);
                    $row['table_name']   = strtoupper($row['table_name']);
                }
                unset($row);
                if (!empty($service_filter_upper)) {
                    $tables1 = array_filter($tables1, function($row) use ($service_filter_upper) {
                        return $row['service_name'] == $service_filter_upper;
                    });
                }
                if (!empty($schema_filter_upper)) {
                    $tables1 = array_filter($tables1, function($row) use ($schema_filter_upper) {
                        return $row['schema_name'] == $schema_filter_upper;
                    });
                }
            }
            $query_tables2 = "
                SELECT service_name, schema_name, table_name, COUNT(*) AS total_attributes 
                FROM administracao.catalog_table_content 
                WHERE ambiente = $1 
                GROUP BY service_name, schema_name, table_name 
                ORDER BY service_name, schema_name, table_name
            ";
            $result_tables2 = pg_query_params($conexao, $query_tables2, [$selected_env2]);
            if (!$result_tables2) {
                die("Erro na consulta de tabelas (ambiente 2): " . pg_last_error($conexao));
            }
            $tables2 = pg_fetch_all($result_tables2);
            if ($tables2) {
                foreach ($tables2 as &$row) {
                    $row['service_name'] = strtoupper(removeSuffix($row['service_name'], $sufixo2));
                    $row['schema_name']  = strtoupper($row['schema_name']);
                    $row['table_name']   = strtoupper($row['table_name']);
                }
                unset($row);
                if (!empty($service_filter_upper)) {
                    $tables2 = array_filter($tables2, function($row) use ($service_filter_upper) {
                        return $row['service_name'] == $service_filter_upper;
                    });
                }
                if (!empty($schema_filter_upper)) {
                    $tables2 = array_filter($tables2, function($row) use ($schema_filter_upper) {
                        return $row['schema_name'] == $schema_filter_upper;
                    });
                }
            }
            $tables2_arr = [];
            if ($tables2) {
                foreach ($tables2 as $row) {
                    $key = $row['service_name'] . '|' . $row['schema_name'] . '|' . $row['table_name'];
                    $tables2_arr[$key] = $row['total_attributes'];
                }
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Comparação de Ambientes</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                if (window.top === window.self) {
                    // Se a página não estiver sendo exibida dentro de um iframe, redireciona para o index
                    window.location.href = 'index.php';
                }
            </script>
            <script>
            $(document).ready(function(){
                $("#ambiente1").change(function(){
                    var env = $(this).val();
                    if(env){
                        $.ajax({
                            url: 'catalogo/catalogo_dsb_compara_ambiente_service_ajax.php',
                            type: 'GET',
                            data: { ambiente: env },
                            success: function(data){
                                $("#service_filter").html(data);
                                $("#schema_filter").html('<option value="">Todos</option>');
                            }
                        });
                    } else {
                        $("#service_filter").html('<option value="">Todos</option>');
                        $("#schema_filter").html('<option value="">Todos</option>');
                    }
                });
                $("#service_filter").change(function(){
                    var env = $("#ambiente1").val();
                    var service = $(this).val();
                    if(env && service){
                        $.ajax({
                            url: 'catalogo/catalogo_dsb_compara_ambiente_schema_ajax.php',
                            type: 'GET',
                            data: { ambiente: env, service: service },
                            success: function(data){
                                $("#schema_filter").html(data);
                            }
                        });
                    } else {
                        $("#schema_filter").html('<option value="">Todos</option>');
                    }
                });
                // Não há submissão automática; o usuário clica em "Comparar"
            });
            </script>
            <style>
                body { background-color: #f5f5f5; font-family: Arial, sans-serif; }
                .container { margin-top: 40px; }
                .card { margin-bottom: 20px; }
                table { width: 100%; }
                th, td { padding: 10px; border: 1px solid #ddd; }
                th { background-color: #f8f9fa; }
                .table-container { max-height: 70vh; overflow-y: auto; padding-bottom: 20px; }
                .missing { background-color: #f8d7da; }
                .difference { background-color: #fff3cd; }
                .ok { background-color: #d4edda; }
            </style>
        </head>
        <body>
        <div class="container">
            <h2 class="text-center mb-4">Comparação de Ambientes</h2>
            <!-- Formulário de Filtros -->
            <form method="GET" class="mb-4" action="">
                <input type="hidden" name="acao" value="<?= htmlspecialchars($acao) ?>">
                <div class="row">
                    <!-- Ambiente 1 -->
                    <div class="col-md-2">
                        <label for="ambiente1" class="form-label">Ambiente 1</label>
                        <select name="ambiente1" id="ambiente1" class="form-select">
                            <option value="">Selecione</option>
                            <?php if ($environments): ?>
                                <?php foreach ($environments as $env): ?>
                                    <option value="<?= htmlspecialchars($env['ambiente']) ?>" <?= ($selected_env1 == $env['ambiente']) ? "selected" : "" ?>>
                                        <?= htmlspecialchars($env['ambiente']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <!-- Ambiente 2 -->
                    <div class="col-md-2">
                        <label for="ambiente2" class="form-label">Ambiente 2</label>
                        <select name="ambiente2" id="ambiente2" class="form-select">
                            <option value="">Selecione</option>
                            <?php if ($environments): ?>
                                <?php foreach ($environments as $env): 
                                    if ($env['ambiente'] == $selected_env1) continue;
                                ?>
                                    <option value="<?= htmlspecialchars($env['ambiente']) ?>" <?= ($selected_env2 == $env['ambiente']) ? "selected" : "" ?>>
                                        <?= htmlspecialchars($env['ambiente']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <!-- Sufixos -->
                    <div class="col-md-2">
                        <label for="sufixo1" class="form-label">Sufixo (Amb. 1)</label>
                        <input type="text" name="sufixo1" id="sufixo1" class="form-control" placeholder="Ex: DS" value="<?= htmlspecialchars($sufixo1) ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="sufixo2" class="form-label">Sufixo (Amb. 2)</label>
                        <input type="text" name="sufixo2" id="sufixo2" class="form-control" placeholder="Ex: HO" value="<?= htmlspecialchars($sufixo2) ?>">
                    </div>
                    <!-- Filtros adicionais para Ambiente 1 -->
                    <div class="col-md-2">
                        <label for="service_filter" class="form-label">Serviço (Amb. 1)</label>
                        <select name="service_filter" id="service_filter" class="form-select">
                            <option value="">Todos</option>
                            <?php
                            if (!empty($all_services1)) {
                                foreach ($all_services1 as $row) {
                                    $sel = ($service_filter_upper == $row['service_name']) ? ' selected' : '';
                                    echo '<option value="'.htmlspecialchars($row['service_name']).'"'.$sel.'>'.htmlspecialchars($row['service_name']).'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="schema_filter" class="form-label">Schema (Amb. 1)</label>
                        <select name="schema_filter" id="schema_filter" class="form-select">
                            <option value="">Todos</option>
                            <?php
                            if (!empty($all_schemas1)) {
                                foreach ($all_schemas1 as $row) {
                                    $sel = ($schema_filter_upper == strtoupper($row['schema_name'])) ? ' selected' : '';
                                    echo '<option value="'.htmlspecialchars(strtoupper($row['schema_name'])).'"'.$sel.'>'.htmlspecialchars(strtoupper($row['schema_name'])).'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Botão de Comparar -->
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success">Comparar</button>
                    </div>
                </div>
            </form>
    
            <?php if (!empty($erro_banco)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($erro_banco) ?></div>
            <?php endif; ?>
    
            <?php if (!empty($selected_env1) && !empty($selected_env2) && empty($erro_banco)): ?>
    
                <!-- 1. Comparação de Serviços -->
                <div class="card">
                    <div class="card-header">
                        <h5>Comparação de Serviços</h5>
                        <small><?= htmlspecialchars($selected_env1) ?> vs <?= htmlspecialchars($selected_env2) ?></small>
                    </div>
                    <div class="card-body table-container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Service Name (<?= htmlspecialchars($selected_env1) ?>)</th>
                                    <th>Status em <?= htmlspecialchars($selected_env2) ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($services1): ?>
                                    <?php foreach ($services1 as $row):
                                        $service = $row['service_name'];
                                        $exists = in_array($service, $services2_arr);
                                    ?>
                                        <tr class="<?= $exists ? 'ok' : 'missing' ?>">
                                            <td><?= htmlspecialchars($service) ?></td>
                                            <td><?= $exists ? 'Presente' : 'Ausente' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="2">Nenhum serviço encontrado no ambiente <?= htmlspecialchars($selected_env1) ?>.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
    
                <!-- 2. Comparação de Schemas -->
                <div class="card">
                    <div class="card-header">
                        <h5>Comparação de Schemas</h5>
                        <small><?= htmlspecialchars($selected_env1) ?> vs <?= htmlspecialchars($selected_env2) ?></small>
                    </div>
                    <div class="card-body table-container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Service Name</th>
                                    <th>Schema Name (<?= htmlspecialchars($selected_env1) ?>)</th>
                                    <th>Status em <?= htmlspecialchars($selected_env2) ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($schemas1): ?>
                                    <?php foreach ($schemas1 as $row):
                                        $key = $row['service_name'] . '|' . $row['schema_name'];
                                        $exists = isset($schemas2_arr[$key]);
                                    ?>
                                        <tr class="<?= $exists ? 'ok' : 'missing' ?>">
                                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                                            <td><?= htmlspecialchars($row['schema_name']) ?></td>
                                            <td><?= $exists ? 'Presente' : 'Ausente' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3">Nenhum schema encontrado no ambiente <?= htmlspecialchars($selected_env1) ?>.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
    
                <!-- 3. Comparação de Tabelas e Atributos -->
                <div class="card">
                    <div class="card-header">
                        <h5>Comparação de Tabelas e Atributos</h5>
                        <small><?= htmlspecialchars($selected_env1) ?> vs <?= htmlspecialchars($selected_env2) ?></small>
                    </div>
                    <div class="card-body table-container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Service Name</th>
                                    <th>Schema Name</th>
                                    <th>Table Name (<?= htmlspecialchars($selected_env1) ?>)</th>
                                    <th>Atributos (<?= htmlspecialchars($selected_env1) ?>)</th>
                                    <th>Status em <?= htmlspecialchars($selected_env2) ?></th>
                                    <th>Atributos (<?= htmlspecialchars($selected_env2) ?>)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($tables1): ?>
                                    <?php foreach ($tables1 as $row):
                                        $key = $row['service_name'] . '|' . $row['schema_name'] . '|' . $row['table_name'];
                                        $exists = isset($tables2_arr[$key]);
                                        $attributes1 = $row['total_attributes'];
                                        $attributes2 = $exists ? $tables2_arr[$key] : 0;
                                        if (!$exists) {
                                            $status = 'Ausente';
                                        } elseif ($attributes1 != $attributes2) {
                                            $status = 'Diferente';
                                        } else {
                                            $status = 'Presente';
                                        }
                                    ?>
                                        <tr class="<?= !$exists ? 'missing' : ($attributes1 != $attributes2 ? 'difference' : 'ok') ?>">
                                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                                            <td><?= htmlspecialchars($row['schema_name']) ?></td>
                                            <td>
                                                <a href="<?= htmlspecialchars($_SERVER['PHP_SELF'] . "?" . http_build_query(array_merge($_GET, ['detail_table' => $row['table_name'], 'service' => $row['service_name'], 'schema' => $row['schema_name']]))); ?>">
                                                    <?= htmlspecialchars($row['table_name']) ?>
                                                </a>
                                            </td>
                                            <td><?= htmlspecialchars($attributes1) ?></td>
                                            <td><?= htmlspecialchars($status) ?></td>
                                            <td><?= $exists ? htmlspecialchars($attributes2) : '-' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6">Nenhuma tabela encontrada no ambiente <?= htmlspecialchars($selected_env1) ?>.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
    
            <?php endif; ?>
            <br><br><br>
        </div>
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
