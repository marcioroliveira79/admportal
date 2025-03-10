<?php
session_start();
require_once("../module/conecta.php");
$pg = new portal();
$conexao = $pg->conectar_obj();

$action = isset($_GET['action']) ? $_GET['action'] : '';
$response = ['success' => false, 'data' => null];

if ($action === 'getHierarchy') {

    // Query unificada para obter objetos (tabelas, views, packages, etc.),
    // incluindo MAX(object_status) para sabermos se está INVALID ou não,
    // e agora incluindo a coluna "technology".
    $query = "
    SELECT * FROM (
       SELECT
            ambiente,
            host_name,
            service_name,
            schema_name,
            table_name,
            object_type,
            table_comments,
            date_collect,
            technology,
            COUNT(column_name) AS columns_count,
            SUM(
              CASE WHEN column_comments IS NULL OR column_comments = '' THEN 1 ELSE 0 END
            ) AS missing_column_comments,
            MAX(object_status) AS object_status
        FROM administracao.catalog_table_content
        WHERE ambiente IS NOT NULL
          AND service_name IS NOT NULL
          AND schema_name IS NOT NULL
          AND table_name IS NOT NULL
        GROUP BY ambiente, host_name, service_name, schema_name, table_name, object_type, table_comments, date_collect, technology

        UNION ALL

        SELECT
            ambiente,
            host_name,
            service_name,
            schema_name,
            object_name AS table_name,
            object_type,
            '' AS table_comments,
            NOW() AS date_collect,
            '' AS technology,  -- no catalog_object_content_line não temos a tecnologia
            MAX(object_line) AS columns_count,
            0 AS missing_column_comments,
            MAX(object_status) AS object_status
        FROM administracao.catalog_object_content_line
        WHERE ambiente IS NOT NULL
          AND service_name IS NOT NULL
          AND schema_name IS NOT NULL
          AND object_name IS NOT NULL
          AND object_type NOT ILIKE 'VIEW'
        GROUP BY ambiente, host_name, service_name, schema_name, object_name, object_type
    ) as combined
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
            // Agrupar por ambiente -> service -> schema
            $byAmbiente = [];
            foreach ($rows as $r) {
                $amb = $r['ambiente'];

                // Se não existe no array, inicializa
                if (!isset($byAmbiente[$amb])) {
                    $byAmbiente[$amb] = [
                        'date_collect' => $r['date_collect'],
                        'technology'   => $r['technology'], // Armazena a tecnologia
                        'services'     => []
                    ];
                } else {
                    // Atualiza a data de coleta se esta for mais recente
                    if (strtotime($r['date_collect']) > strtotime($byAmbiente[$amb]['date_collect'])) {
                        $byAmbiente[$amb]['date_collect'] = $r['date_collect'];
                    }
                    // Se ainda não há tecnologia setada, tenta atribuir
                    if (empty($byAmbiente[$amb]['technology']) && !empty($r['technology'])) {
                        $byAmbiente[$amb]['technology'] = $r['technology'];
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

                // Adiciona o objeto (tabela, view, etc.)
                $byAmbiente[$amb]['services'][$srvNameWithIp][$sch][] = [
                    'table_name'          => $r['table_name'],
                    'columns_count'       => $r['columns_count'],
                    'table_comments'      => $r['table_comments'],
                    'object_type'         => $r['object_type'],
                    'missing_descriptions'=> (
                        $r['object_type'] !== 'TABELA EXTERNA'
                        && (empty($r['table_comments'])
                        || ((int)$r['missing_column_comments'] > 0))
                    ) ? true : false,
                    'object_status'       => $r['object_status']
                ];
            }

            // Função para agrupar PACKAGE BODY em PACKAGE
            function nestPackageBodies($items) {
                $processed = [];
                $skip = [];
                $count = count($items);
                for ($i = 0; $i < $count; $i++) {
                    if (in_array($i, $skip)) continue;
                    $item = $items[$i];
                    if (strtoupper($item['object_type']) == 'PACKAGE') {
                        // Procura um PACKAGE BODY com o mesmo nome
                        for ($j = $i + 1; $j < $count; $j++) {
                            if (strtoupper($items[$j]['object_type']) == 'PACKAGE BODY'
                                && $items[$j]['table_name'] == $item['table_name']) {
                                $item['children'] = [$items[$j]];
                                $skip[] = $j;
                                break;
                            }
                        }
                    }
                    $processed[] = $item;
                }
                return $processed;
            }

            // Monta o array final de hierarquia
            $hierarchy = [];
            foreach ($byAmbiente as $amb => $dataAmb) {
                $services = $dataAmb['services'];
                $srvArray = [];

                foreach ($services as $srvKey => $schemasVal) {
                    $schemaArr = [];
                    foreach ($schemasVal as $schemaKey => $tblsVal) {
                        // Agrupa PACKAGE BODY em PACKAGE
                        $tblsVal = nestPackageBodies($tblsVal);
                        // Ordena os objetos dentro do schema
                        usort($tblsVal, function($a, $b) {
                            $order = [
                                'TABELA'            => 1,
                                'TABELA EXTERNA'    => 2,
                                'VIEW'              => 3,
                                'VIEW MATERIALIZADA'=> 4,
                                'PACKAGE'           => 5,
                                'PACKAGE BODY'      => 6,
                                'FUNCTION'          => 7,
                                'PROCEDURE'         => 8,
                                'TRIGGER'           => 9
                            ];
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

                // Aqui adicionamos também a tecnologia no array
                // para ser usada ao exibir "ORACLE - DESENVOLVIMENTO (11)"
                $hierarchy[] = [
                    'ambiente'     => $amb,
                    'technology'   => $dataAmb['technology'] ?? '',
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
    $ambiente    = $_GET['ambiente']     ?? '';
    $serviceName = $_GET['service_name'] ?? '';
    $schemaName  = $_GET['schema_name']  ?? '';
    $tableName   = $_GET['table_name']   ?? '';

    $svc = preg_replace('/\(.*?\)/', '', $serviceName);
    $svc = trim($svc);

    // Ajuste: inclui a coluna table_size_bytes
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
               external_location,
               table_size_bytes
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

    $queryColumns = "
        SELECT DISTINCT
               column_name,
               data_type,
               data_length,
               CASE WHEN object_type = 'TABELA EXTERNA' THEN '' ELSE COALESCE(column_comments, '') END AS column_comments,
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

elseif ($action === 'getObjectDetails') {
    $ambiente    = $_GET['ambiente']     ?? '';
    $serviceName = $_GET['service_name'] ?? '';
    $schemaName  = $_GET['schema_name']  ?? '';
    $objectName  = $_GET['object_name']  ?? '';

    $svc = preg_replace('/\(.*?\)/', '', $serviceName);
    $svc = trim($svc);

    $query = "
        SELECT ambiente,
               service_name,
               schema_name,
               object_name,
               object_type,
               STRING_AGG(object_content, E'\n' ORDER BY object_line) as object_content
        FROM administracao.catalog_object_content_line
        WHERE ambiente = $1
          AND service_name = $2
          AND schema_name = $3
          AND object_name = $4
        GROUP BY ambiente, service_name, schema_name, object_name, object_type
        LIMIT 1
    ";
    $params = [$ambiente, $svc, $schemaName, $objectName];
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
    $response['success'] = true;
    $response['data'] = $row;
    echo json_encode($response);
    exit;
}

elseif ($action === 'search') {
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
                SELECT DISTINCT ambiente, service_name, schema_name, table_name, object_type
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
                    'table_name'   => $r['table_name'],
                    'object_type'  => $r['object_type']
                ];
            }
            $response['success'] = true;
            $response['data'] = $data;
        }
        elseif ($tipo === 'attribute') {
            $sql = "
                SELECT DISTINCT ambiente, service_name, schema_name, table_name, object_type
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
                    'table_name'   => $r['table_name'],
                    'object_type'  => $r['object_type']
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

elseif ($action === 'getTableRelationships') {
    $ambiente    = $_GET['ambiente']     ?? '';
    $serviceName = $_GET['service_name'] ?? '';
    $schemaName  = $_GET['schema_name']  ?? '';
    $tableName   = $_GET['table_name']   ?? '';

    $svc = preg_replace('/\(.*?\)/', '', $serviceName);
    $svc = trim($svc);

    $sql = "
        SELECT DISTINCT
            table_origin,
            attribute_origin,
            table_reference,
            attribute_reference,
            constraint_name,
            direction
        FROM administracao.catalog_table_reference
        WHERE service_name   = $1
          AND schema_origin  = $2
          AND table_origin   = $3
        ORDER BY constraint_name
    ";
    $params = [$svc, $schemaName, $tableName];
    $result = pg_query_params($conexao, $sql, $params);

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

elseif ($action === 'getSchemaRelationships') {
    $ambiente    = $_GET['ambiente'] ?? '';
    $serviceName = $_GET['service_name'] ?? '';
    $schemaName  = $_GET['schema_name'] ?? '';

    $svc = preg_replace('/\(.*?\)/', '', $serviceName);
    $svc = trim($svc);

    $sql = "
       WITH cte AS (
            SELECT 
                table_origin,
                '' as attribute_origin,
                table_reference,
                '' as attribute_reference,
                constraint_name,
                direction,
                ROW_NUMBER() OVER (
                    PARTITION BY constraint_name 
                    ORDER BY constraint_name
                ) AS rn
            FROM administracao.catalog_table_reference
            WHERE service_name = $1
            AND schema_origin = $2
        )
        SELECT table_origin,
            attribute_origin,
            table_reference,
            attribute_reference,
            constraint_name,
            direction
        FROM cte
        WHERE rn = 1
        ORDER BY constraint_name
    ";
    $params = [$svc, $schemaName];
    $result = pg_query_params($conexao, $sql, $params);
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

// =====================================================================
// NOVA AÇÃO: getServiceInfo
// =====================================================================
elseif ($action === 'getServiceInfo') {
    // Recebe parâmetros via GET
    $db   = $_GET['data_base']   ?? '';
    $host = $_GET['host_name']   ?? '';
    $srv  = $_GET['service_name'] ?? '';

    // Monta query para buscar informações no catalog_database_infos
    $sql = "
        SELECT 
             data_base
            ,host_name
            ,service_name
            ,ambiente
            ,database_creation_date AS data_criacao
            ,startup_time           AS ultimo_start
            ,host_tec               AS nome_host
            ,patch_action_time      AS data_aplicacao_patch
            ,patch_action           AS acao_patch
            ,patch_comments         AS patch_comentarios
            ,installed_components   AS componente_instalado
            ,technology as tecnologia
        FROM administracao.catalog_database_infos
        WHERE 1=1
          AND ambiente = $1
          AND host_name = $2
          AND service_name = $3
        ORDER BY patch_action_time DESC
    ";
    $params = [$db, $host, $srv];
    $result = pg_query_params($conexao, $sql, $params);

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

// =====================================================================
else {
    $response['success'] = false;
    $response['data'] = 'Ação inválida.';
    echo json_encode($response);
    exit;
}
?>
