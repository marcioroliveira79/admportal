<?php
session_start();
require_once("../module/conecta.php");
$pg = new portal();
$conexao = $pg->conectar_obj();

$action = isset($_GET['action']) ? $_GET['action'] : '';
$response = ['success' => false, 'data' => null];

if ($action === 'getHierarchy') {
    // Retorna a hierarquia de ambientes -> serviços -> schemas -> tabelas
    // Agora também retorna object_type para identificar os diferentes tipos
    $query = "
        SELECT
            ambiente,
            host_name,
            service_name,
            schema_name,
            table_name,
            object_type,
            table_comments,
            date_collect,
            COUNT(column_name) AS columns_count,
            SUM(CASE WHEN column_comments IS NULL OR column_comments = '' THEN 1 ELSE 0 END) AS missing_column_comments
        FROM administracao.catalog_table_content
        WHERE ambiente IS NOT NULL
          AND service_name IS NOT NULL
          AND schema_name IS NOT NULL
          AND table_name IS NOT NULL
        GROUP BY ambiente, host_name, service_name, schema_name, table_name, object_type, table_comments, date_collect
        ORDER BY ambiente, service_name, schema_name, table_name
    ";
    $result = pg_query($conexao, $query);
    if (!$result) {
        $response['success'] = false;
        $response['data'] = pg_last_error($conexao);
    } else {
        $rows = pg_fetch_all($result);
        if (!$rows) {
            $response['success'] = true;
            $response['data'] = [];
        } else {
            $byAmbiente = [];
            foreach ($rows as $r) {
                $amb = $r['ambiente'];
                if (!isset($byAmbiente[$amb])) {
                    $byAmbiente[$amb] = [
                        'date_collect' => $r['date_collect'],
                        'services' => []
                    ];
                } else {
                    if (strtotime($r['date_collect']) > strtotime($byAmbiente[$amb]['date_collect'])) {
                        $byAmbiente[$amb]['date_collect'] = $r['date_collect'];
                    }
                }
                $ip = $r['host_name'] ?: '???';
                $srvNameWithIp = $r['service_name'] . ' (' . $ip . ')';
                if (!isset($byAmbiente[$amb]['services'][$srvNameWithIp])) {
                    $byAmbiente[$amb]['services'][$srvNameWithIp] = [];
                }
                $sch = $r['schema_name'];
                if (!isset($byAmbiente[$amb]['services'][$srvNameWithIp][$sch])) {
                    $byAmbiente[$amb]['services'][$srvNameWithIp][$sch] = [];
                }
                // Define missing_descriptions somente se não for TABELA EXTERNA
                $byAmbiente[$amb]['services'][$srvNameWithIp][$sch][] = [
                    'table_name'          => $r['table_name'],
                    'columns_count'       => $r['columns_count'],
                    'table_comments'      => $r['table_comments'],
                    'object_type'         => $r['object_type'],
                    'missing_descriptions'=> ($r['object_type'] !== 'TABELA EXTERNA' && (empty($r['table_comments']) || ((int)$r['missing_column_comments'] > 0))) ? true : false
                ];
            }
            $hierarchy = [];
            foreach ($byAmbiente as $amb => $dataAmb) {
                $services = $dataAmb['services'];
                $srvArray = [];
                foreach ($services as $srvKey => $schemasVal) {
                    $schemaArr = [];
                    foreach ($schemasVal as $schemaKey => $tblsVal) {
                        // Ordena os objetos dentro do schema
                        usort($tblsVal, function($a, $b) {
                            $order = array(
                                'TABELA' => 1,
                                'TABELA EXTERNA' => 2,
                                'VIEW' => 3,
                                'VIEW MATERIALIZADA' => 4
                            );
                            $wA = isset($order[$a['object_type']]) ? $order[$a['object_type']] : 999;
                            $wB = isset($order[$b['object_type']]) ? $order[$b['object_type']] : 999;
                            if ($wA === $wB) {
                                return strcmp($a['table_name'], $b['table_name']);
                            }
                            return $wA - $wB;
                        });
                        $schemaArr[] = [
                            'schema_name' => $schemaKey,
                            'children'    => $tblsVal
                        ];
                    }
                    $srvArray[] = [
                        'service_name' => $srvKey,
                        'children'     => $schemaArr
                    ];
                }
                $hierarchy[] = [
                    'ambiente'     => $amb,
                    'date_collect' => $dataAmb['date_collect'],
                    'children'     => $srvArray
                ];
            }
            $response['success'] = true;
            $response['data'] = $hierarchy;
        }
    }
    echo json_encode($response);
    exit;
}
elseif ($action === 'getTableDetails') {
    // Retorna detalhes de uma tabela específica
    $ambiente    = $_GET['ambiente']     ?? '';
    $serviceName = $_GET['service_name'] ?? '';
    $schemaName  = $_GET['schema_name']  ?? '';
    $tableName   = $_GET['table_name']   ?? '';

    // Extrai IP e ajusta service_name (caso tenha algo entre parênteses)
    if (preg_match('/\((.*?)\)$/', $serviceName, $m)) {
        $ip = trim($m[1]);
    }
    $svc = preg_replace('/\(.*?\)/', '', $serviceName);
    $svc = trim($svc);

    $query = "
        SELECT ambiente,
               service_name,
               schema_name,
               table_name,
               table_comments,
               table_creation_date,
               table_last_ddl_time,
               record_count,
               object_type,
               external_directory,
               external_directory_path,
               external_location
        FROM administracao.catalog_table_content
        WHERE ambiente = $1
          AND service_name = $2
          AND schema_name  = $3
          AND table_name   = $4
        LIMIT 1
    ";
    $params = [$ambiente, $svc, $schemaName, $tableName];
    $result = pg_query_params($conexao, $query, $params);
    if (!$result) {
        $response['success'] = false;
        $response['data'] = pg_last_error($conexao);
        echo json_encode($response);
        exit;
    }
    $row = pg_fetch_assoc($result);
    if (!$row) {
        $response['success'] = true;
        $response['data'] = null;
        echo json_encode($response);
        exit;
    }

    // Consulta das colunas da tabela
    $queryColumns = "
        SELECT DISTINCT
               column_name,
               data_type,
               data_length,
               column_comments,
               is_nullable,
               is_unique,
               is_pk,
               is_fk,
               column_id
        FROM administracao.catalog_table_content
        WHERE ambiente = $1
          AND service_name = $2
          AND schema_name  = $3
          AND table_name   = $4
        ORDER BY column_id
    ";
    $resultColumns = pg_query_params($conexao, $queryColumns, $params);
    if (!$resultColumns) {
        $columns = [];
    } else {
        $columns = pg_fetch_all($resultColumns);
        if (!$columns) {
            $columns = [];
        }
    }
    $row['columns'] = $columns;

    $response['success'] = true;
    $response['data'] = $row;
    echo json_encode($response);
    exit;
}
elseif ($action === 'search') {
    // Ação para buscar por schema, tabela ou atributo
    $amb = $_GET['ambiente'] ?? '';
    $tipo = $_GET['tipo'] ?? '';
    $texto = $_GET['texto'] ?? '';

    if (!$amb || !$tipo || !$texto) {
        $response['success'] = false;
        $response['data'] = 'Parâmetros de busca inválidos.';
        echo json_encode($response);
        exit;
    }

    $pattern = $texto;
    try {
        if ($tipo === 'schema') {
            $sql = "
                SELECT DISTINCT ambiente, service_name, schema_name
                FROM administracao.catalog_table_content
                WHERE ambiente = $1
                  AND schema_name ILIKE $2
                ORDER BY ambiente, service_name, schema_name
            ";
            $params = [$amb, $pattern];
            $result = pg_query_params($conexao, $sql, $params);
            $rows = ($result) ? pg_fetch_all($result) : [];
            if (!$rows) $rows = [];
            $data = [];
            foreach ($rows as $r) {
                $data[] = [
                    'ambiente'     => $r['ambiente'],
                    'service_name' => $r['service_name'],
                    'schema_name'  => $r['schema_name']
                ];
            }
            $response['success'] = true;
            $response['data'] = $data;
        }
        elseif ($tipo === 'table') {
            $sql = "
                SELECT DISTINCT ambiente, service_name, schema_name, table_name
                FROM administracao.catalog_table_content
                WHERE ambiente = $1
                  AND table_name ILIKE $2
                ORDER BY ambiente, service_name, schema_name, table_name
            ";
            $params = [$amb, $pattern];
            $result = pg_query_params($conexao, $sql, $params);
            $rows = ($result) ? pg_fetch_all($result) : [];
            if (!$rows) $rows = [];
            $data = [];
            foreach ($rows as $r) {
                $data[] = [
                    'ambiente'     => $r['ambiente'],
                    'service_name' => $r['service_name'],
                    'schema_name'  => $r['schema_name'],
                    'table_name'   => $r['table_name']
                ];
            }
            $response['success'] = true;
            $response['data'] = $data;
        }
        elseif ($tipo === 'attribute') {
            $sql = "
                SELECT DISTINCT ambiente, service_name, schema_name, table_name
                FROM administracao.catalog_table_content
                WHERE ambiente = $1
                  AND column_name ILIKE $2
                ORDER BY ambiente, service_name, schema_name, table_name
            ";
            $params = [$amb, $pattern];
            $result = pg_query_params($conexao, $sql, $params);
            $rows = ($result) ? pg_fetch_all($result) : [];
            if (!$rows) $rows = [];
            $data = [];
            foreach ($rows as $r) {
                $data[] = [
                    'ambiente'     => $r['ambiente'],
                    'service_name' => $r['service_name'],
                    'schema_name'  => $r['schema_name'],
                    'table_name'   => $r['table_name']
                ];
            }
            $response['success'] = true;
            $response['data'] = $data;
        }
        else {
            $response['success'] = false;
            $response['data'] = 'Tipo de busca inválido.';
        }
    } catch (Exception $e) {
        $response['success'] = false;
        $response['data'] = $e->getMessage();
    }
    echo json_encode($response);
    exit;
}
elseif ($action === 'getAmbientes') {
    $sql = "
        SELECT DISTINCT ambiente
        FROM administracao.catalog_table_content
        WHERE ambiente IS NOT NULL
        ORDER BY ambiente
    ";
    $result = pg_query($conexao, $sql);
    if (!$result) {
        $response['success'] = false;
        $response['data'] = pg_last_error($conexao);
    } else {
        $rows = pg_fetch_all_columns($result, 0);
        if (!$rows) {
            $rows = [];
        }
        $response['success'] = true;
        $response['data'] = $rows;
    }
    echo json_encode($response);
    exit;
}
elseif ($action === 'getTableHistory') {
    // Retorna o histórico de record_count para uma tabela
    $ambiente    = $_GET['ambiente']     ?? '';
    $serviceName = $_GET['service_name'] ?? '';
    $schemaName  = $_GET['schema_name']  ?? '';
    $tableName   = $_GET['table_name']   ?? '';

    if (!$ambiente || !$serviceName || !$schemaName || !$tableName) {
        $response['success'] = false;
        $response['data'] = 'Parâmetros inválidos.';
        echo json_encode($response);
        exit;
    }

    // Remove possíveis parênteses do serviceName
    $svc = preg_replace('/\(.*?\)/', '', $serviceName);
    $svc = trim($svc);

    $query = "
        SELECT date_collect, record_count
        FROM administracao.catalog_hist_stats_tabela
        WHERE ambiente = $1
          AND service_name = $2
          AND schema_name = $3
          AND table_name = $4
        ORDER BY date_collect ASC
    ";
    $params = [$ambiente, $svc, $schemaName, $tableName];
    $result = pg_query_params($conexao, $query, $params);
    if (!$result) {
        $response['success'] = false;
        $response['data'] = pg_last_error($conexao);
    } else {
        $rows = pg_fetch_all($result);
        if (!$rows) {
            $rows = [];
        }
        $response['success'] = true;
        $response['data'] = $rows;
    }
    echo json_encode($response);
    exit;
}
else {
    $response['success'] = false;
    $response['data'] = 'Ação inválida.';
    echo json_encode($response);
    exit;
}
