<?php
session_start();
// Conexão com o banco (adapte para seu projeto)

require_once __DIR__ . '/../module/conecta.php';


$pg = new portal();
$conexao = $pg->conectar_obj();

$action = isset($_GET['action']) ? $_GET['action'] : '';
$response = ['success' => false, 'data' => [], 'message' => ''];

/* 
   Assumimos que os dados estão em administracao.catalog_vw_lgpd_marcacao.
   Ajuste conforme a estrutura real do seu projeto.
*/

// 1) getAmbientes
if ($action === 'getAmbientes') {
    $filtrarUsuario = (isset($_GET['user']) && $_GET['user'] === 'ON');

    if ($filtrarUsuario) {
        $query = "
            SELECT DISTINCT c.ambiente
            FROM administracao.catalog_lgpd_acesso_schema_marcacao c
            JOIN administracao.catalog_vw_lgpd_marcacao v
              ON c.ambiente = v.ambiente
             AND c.data_base = v.data_base
             AND c.host_name = v.host_name
             AND c.service_name = v.service_name
             AND c.schema_name = v.schema_name
            WHERE c.fk_usuario = $1
            ORDER BY c.ambiente
        ";
        $result = pg_query_params($conexao, $query, [$_SESSION['global_id_usuario']]);
    } else {
        $query = "
            SELECT DISTINCT ambiente
            FROM administracao.catalog_vw_lgpd_marcacao
            ORDER BY ambiente
        ";
        $result = pg_query($conexao, $query);
    }

    if ($result) {
        $ambientes = [];
        while ($row = pg_fetch_assoc($result)) {
            $ambientes[] = $row['ambiente'];
        }
        $response['data'] = $ambientes;
        $response['success'] = true;
    } else {
        $response['message'] = pg_last_error($conexao);
    }
}

// 2) getServiceNames
elseif ($action === 'getServiceNames') {
    $ambiente = $_GET['ambiente'] ?? '';
    $filtrarUsuario = (isset($_GET['user']) && $_GET['user'] === 'ON');

    if ($filtrarUsuario) {
        $query = "
            SELECT DISTINCT c.service_name
            FROM administracao.catalog_lgpd_acesso_schema_marcacao c
            JOIN administracao.catalog_vw_lgpd_marcacao v
              ON c.ambiente = v.ambiente
             AND c.data_base = v.data_base
             AND c.host_name = v.host_name
             AND c.service_name = v.service_name
             AND c.schema_name = v.schema_name
            WHERE c.fk_usuario = $1
              AND c.ambiente = $2
            ORDER BY c.service_name
        ";
        $result = pg_query_params($conexao, $query, [$_SESSION['global_id_usuario'], $ambiente]);
    } else {
        $query = "
            SELECT DISTINCT service_name
            FROM administracao.catalog_vw_lgpd_marcacao
            WHERE ambiente = $1
            ORDER BY service_name
        ";
        $result = pg_query_params($conexao, $query, [$ambiente]);
    }

    if ($result) {
        $services = [];
        while ($row = pg_fetch_assoc($result)) {
            $services[] = $row['service_name'];
        }
        $response['data'] = $services;
        $response['success'] = true;
    } else {
        $response['message'] = pg_last_error($conexao);
    }
}

// 3) getSchemas
elseif ($action === 'getSchemas') {
    $ambiente = $_GET['ambiente'] ?? '';
    $service_name = $_GET['service_name'] ?? '';
    $filtrarUsuario = (isset($_GET['user']) && $_GET['user'] === 'ON');

    if ($filtrarUsuario) {
        $query = "
            SELECT DISTINCT c.schema_name
            FROM administracao.catalog_lgpd_acesso_schema_marcacao c
            JOIN administracao.catalog_vw_lgpd_marcacao v
              ON c.ambiente = v.ambiente
             AND c.data_base = v.data_base
             AND c.host_name = v.host_name
             AND c.service_name = v.service_name
             AND c.schema_name = v.schema_name
            WHERE c.fk_usuario = $1
              AND c.ambiente = $2
              AND c.service_name = $3
            ORDER BY c.schema_name
        ";
        $result = pg_query_params($conexao, $query, [$_SESSION['global_id_usuario'], $ambiente, $service_name]);
    } else {
        $query = "
            SELECT DISTINCT schema_name
            FROM administracao.catalog_vw_lgpd_marcacao
            WHERE ambiente = $1
              AND service_name = $2
            ORDER BY schema_name
        ";
        $result = pg_query_params($conexao, $query, [$ambiente, $service_name]);
    }

    if ($result) {
        $schemas = [];
        while ($row = pg_fetch_assoc($result)) {
            $schemas[] = $row['schema_name'];
        }
        $response['data'] = $schemas;
        $response['success'] = true;
    } else {
        $response['message'] = pg_last_error($conexao);
    }
}

// 4) getTables
elseif ($action === 'getTables') {
    $ambiente = $_GET['ambiente'] ?? '';
    $service_name = $_GET['service_name'] ?? '';
    $schema_name = $_GET['schema_name'] ?? '';
    $filtrarUsuario = (isset($_GET['user']) && $_GET['user'] === 'ON');

    $query = "
        SELECT 
            lg.table_name,
            SUM(
                CASE WHEN (lg.contem_palavra IS TRUE OR lg.contem_atributo IS TRUE)
                     THEN 1 ELSE 0
                END
            ) AS marking_count,
            SUM(
                CASE WHEN (mc.id IS NOT NULL)
                     THEN 1 ELSE 0
                END
            ) AS insert_mark
        FROM administracao.catalog_vw_lgpd_marcacao lg
        LEFT JOIN administracao.catalog_lgpd_marcacao mc
               ON mc.ambiente     = lg.ambiente
              AND mc.service_name = lg.service_name
              AND mc.schema_name  = lg.schema_name
              AND mc.table_name   = lg.table_name
              AND mc.column_name  = lg.column_name
        WHERE lg.ambiente = $1
          AND lg.service_name = $2
          AND lg.schema_name = $3
        GROUP BY lg.table_name
        ORDER BY lg.table_name
    ";

    $result = pg_query_params($conexao, $query, [$ambiente, $service_name, $schema_name]);

    if ($result) {
        $tables = [];
        while ($row = pg_fetch_assoc($result)) {
            $nomeTabela   = $row['table_name'];
            $markingCount = (int)$row['marking_count'];
            $insertMark   = (int)$row['insert_mark'];

            if ($markingCount > 0) {
                if ($insertMark >= $markingCount) {
                    $nomeTabela = '( OK ) ' . $nomeTabela;
                } else {
                    $nomeTabela = '( ! ) ' . $nomeTabela;
                }
            }
            $tables[] = $nomeTabela;
        }
        $response['data'] = $tables;
        $response['success'] = true;
    } else {
        $response['message'] = pg_last_error($conexao);
    }
}

// 5) getDashboardData
elseif ($action === 'getDashboardData') {
    $ambiente     = $_GET['ambiente']     ?? '';
    $service_name = $_GET['service_name'] ?? '';
    $schema_name  = $_GET['schema_name']  ?? '';
    $table_name   = $_GET['table_name']   ?? '';

    $table_name = str_replace(['( ! ) ', '( OK ) '], '', $table_name);

    $whereClauses = [];
    $params = [];
    $paramIndex = 1;

    if (!empty($ambiente)) {
        $whereClauses[] = "UPPER(ambiente) = UPPER(\$$paramIndex)";
        $params[] = $ambiente;
        $paramIndex++;
    }
    if (!empty($service_name)) {
        $whereClauses[] = "UPPER(service_name) = UPPER(\$$paramIndex)";
        $params[] = $service_name;
        $paramIndex++;
    }
    if (!empty($schema_name)) {
        $whereClauses[] = "UPPER(schema_name) = UPPER(\$$paramIndex)";
        $params[] = $schema_name;
        $paramIndex++;
    }
    if (!empty($table_name)) {
        $whereClauses[] = "UPPER(table_name) = UPPER(\$$paramIndex)";
        $params[] = $table_name;
        $paramIndex++;
    }

    $baseQuery = "
        SELECT 
            COUNT(column_name) AS qtd_atributos,
            COUNT(*) FILTER (WHERE (contem_palavra IS TRUE OR contem_atributo IS TRUE)) AS sugestoes_sensidata,
            COUNT(*) FILTER (WHERE lgpd_marcacao IS TRUE) AS tags_tabela,
            COUNT(*) FILTER (WHERE existe_registro IS TRUE) AS marcados
        FROM administracao.catalog_vw_lgpd_marcacao
    ";

    $whereString = '';
    if (!empty($whereClauses)) {
        $whereString = ' WHERE ' . implode(' AND ', $whereClauses);
    }

    $query = $baseQuery . $whereString;
    $debugInfo = "Query:\n$query\nParams: " . print_r($params, true);
    $result = pg_query_params($conexao, $query, $params);

    if ($result) {
        $row = pg_fetch_assoc($result);
        $response['data'] = [
            'qtd_atributos'       => (int)($row['qtd_atributos']       ?? 0),
            'sugestoes_sensidata' => (int)($row['sugestoes_sensidata'] ?? 0),
            'tags_tabela'         => (int)($row['tags_tabela']         ?? 0),
            'marcados'            => (int)($row['marcados']            ?? 0)
        ];
        $response['success'] = true;
    } else {
        $response['message'] = pg_last_error($conexao);
    }

    $response['debug'] = $debugInfo;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
?>
