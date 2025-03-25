<?php
session_start();
require_once("../module/conecta.php");
$pg = new portal();
$conexao = $pg->conectar_obj();

$action = isset($_GET['action']) ? $_GET['action'] : '';
$response = ['success' => false, 'data' => []];

// 1) getServiceNames
if ($action == 'getServiceNames') {
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
        $arr = [];
        while ($row = pg_fetch_assoc($result)) {
            $arr[] = $row['service_name'];
        }
        $response['data'] = $arr;
        $response['success'] = true;
    }
}
// 2) getSchemas
elseif ($action == 'getSchemas') {
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
        $arr = [];
        while ($row = pg_fetch_assoc($result)) {
            $arr[] = $row['schema_name'];
        }
        $response['data'] = $arr;
        $response['success'] = true;
    }
}
// 3) getTables
elseif ($action == 'getTables') {
    $ambiente = $_GET['ambiente'] ?? '';
    $service_name = $_GET['service_name'] ?? '';
    $schema_name = $_GET['schema_name'] ?? '';

    $query = "
        SELECT 
            lg.table_name,
            SUM(
              CASE 
                WHEN (lg.contem_palavra IS TRUE OR lg.contem_atributo IS TRUE) 
                THEN 1 
                ELSE 0 
              END
            ) AS marking_count,
            SUM(
              CASE 
                WHEN (mc.id IS NOT NULL) 
                THEN 1 
                ELSE 0 
              END
            ) AS insert_mark
        FROM administracao.catalog_vw_lgpd_marcacao lg
        LEFT JOIN administracao.catalog_lgpd_marcacao mc
               ON mc.ambiente     = lg.ambiente
              AND mc.service_name = lg.service_name
              AND mc.schema_name  = lg.schema_name
              AND mc.table_name   = lg.table_name
              AND mc.column_name  = lg.column_name
        WHERE lg.ambiente     = $1
          AND lg.service_name = $2
          AND lg.schema_name  = $3
        GROUP BY lg.table_name
        ORDER BY lg.table_name
    ";
    $result = pg_query_params($conexao, $query, [$ambiente, $service_name, $schema_name]);
    if ($result) {
        $arr = [];
        while ($row = pg_fetch_assoc($result)) {
            $nomeTabela    = $row['table_name'];
            $markingCount  = (int)$row['marking_count'];
            $insertMark    = (int)$row['insert_mark'];

            if ($markingCount > 0) {
                if ($insertMark >= $markingCount) {
                    $nomeTabela = '( OK ) ' . $nomeTabela;
                } else {
                    $nomeTabela = '( ! ) ' . $nomeTabela;
                }
            }

            $arr[] = $nomeTabela;
        }
        $response['data'] = $arr;
        $response['success'] = true;
    }
}
// 4) getAttributes
elseif ($action == 'getAttributes') {
    $ambiente     = $_GET['ambiente'] ?? '';
    $service_name = $_GET['service_name'] ?? '';
    $schema_name  = $_GET['schema_name'] ?? '';
    $table_name   = $_GET['table_name'] ?? '';

    // Remove possíveis prefixos do nome da tabela
    $table_name = str_replace(['( ! ) ', '( OK ) '], '', $table_name);

    $query = "
        SELECT
            table_comments,
            column_name,
            data_type,
            data_length,
            column_comments,
            is_nullable,
            is_pk,
            is_fk,
            acao_lgpd,
            lgpd_informacao,
            acao_lgpd_atual,
            lgpd_informacao_atual,
            fk_lgpd_marcacao,
            data_base,
            host_name,
            atributo_relacionado,
            palavra_relacionada,
            nome_usuario_criador,
            lgpd_definicao,
            data_criacao_marcacao
        FROM administracao.catalog_vw_lgpd_marcacao
        WHERE ambiente = $1
          AND service_name = $2
          AND schema_name = $3
          AND table_name = $4
        ORDER BY column_id
    ";
    $params = [$ambiente, $service_name, $schema_name, $table_name];
    $result = pg_query_params($conexao, $query, $params);
    if ($result) {
        $arr = [];
        while ($row = pg_fetch_assoc($result)) {
            $arr[] = $row;
        }
        $response['data'] = $arr;
        $response['success'] = true;
    }
}
// 5) getAcoesInfos
elseif ($action == 'getAcoesInfos') {
    $query = "
        SELECT 
            al.acao_lgpd AS acao,
            lc.classificacao AS info
        FROM administracao.catalog_acao_motivo_lgpd al
        INNER JOIN administracao.catalog_lgpd_classificacao lc
            ON al.fk_lgpd_classificacao = lc.id
        WHERE al.acao_lgpd IS NOT NULL
          AND lc.classificacao IS NOT NULL
        ORDER BY al.acao_lgpd, lc.classificacao
    ";
    $result = pg_query($conexao, $query);
    if ($result) {
        $data = [];
        while ($row = pg_fetch_assoc($result)) {
            $data[] = [
                'acao' => $row['acao'],
                'info' => $row['info']
            ];
        }
        $response['data'] = $data;
        $response['success'] = true;
    }
}
// 6) insertAttribute
elseif ($action == 'insertAttribute') {
    $ambiente        = $_GET['ambiente'] ?? '';
    $service_name    = $_GET['service_name'] ?? '';
    $schema_name     = $_GET['schema_name'] ?? '';
    $table_name      = $_GET['table_name'] ?? '';

    // Remove possíveis prefixos
    $table_name = str_replace(['( ! ) ', '( OK ) '], '', $table_name);

    $column_name     = $_GET['column_name'] ?? '';
    $acao_lgpd       = $_GET['acao_lgpd'] ?? '';
    $lgpd_informacao = $_GET['lgpd_informacao'] ?? '';
    $lgpd_definicao  = $_GET['lgpd_definicao'] ?? '';
    $column_comment  = $_GET['column_comment'] ?? '';
    $data_base       = $_GET['data_base'] ?? '';
    $host_name       = $_GET['host_name'] ?? '';

    $fk_usuario_criador = $_SESSION['global_id_usuario'] ?? null;

    $insertQuery = "
        INSERT INTO administracao.catalog_lgpd_marcacao 
        (ambiente, data_base, host_name, service_name, schema_name, table_name, column_name, column_comment, acao_lgpd, lgpd_informacao, lgpd_definicao, fk_usuario_criador)
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12)
    ";
    $params = [
        $ambiente,
        $data_base,
        $host_name,
        $service_name,
        $schema_name,
        $table_name,
        $column_name,
        $column_comment,
        $acao_lgpd,
        $lgpd_informacao,
        $lgpd_definicao,
        $fk_usuario_criador
    ];
    $result = pg_query_params($conexao, $insertQuery, $params);
    if ($result) {
        $response['success'] = true;
        $response['message'] = "Registro inserido com sucesso.";
    } else {
        $response['message'] = pg_last_error($conexao);
    }
}
// 7) removeAttribute
elseif ($action == 'removeAttribute') {
    $ambiente    = $_GET['ambiente'] ?? '';
    $data_base   = $_GET['data_base'] ?? '';
    $host_name   = $_GET['host_name'] ?? '';
    $service_name= $_GET['service_name'] ?? '';
    $schema_name = $_GET['schema_name'] ?? '';
    $table_name  = $_GET['table_name'] ?? '';

    // Remove possíveis prefixos
    $table_name = str_replace(['( ! ) ', '( OK ) '], '', $table_name);

    $column_name = $_GET['column_name'] ?? '';

    $deleteQuery = "
        DELETE FROM administracao.catalog_lgpd_marcacao
         WHERE ambiente = $1
           AND data_base = $2
           AND host_name = $3
           AND service_name = $4
           AND schema_name = $5
           AND table_name = $6
           AND column_name = $7
    ";
    $params = [
        $ambiente,
        $data_base,
        $host_name,
        $service_name,
        $schema_name,
        $table_name,
        $column_name
    ];
    $result = pg_query_params($conexao, $deleteQuery, $params);
    if ($result) {
        $response['success'] = true;
        $response['message'] = "Registro removido com sucesso.";
    } else {
        $response['message'] = pg_last_error($conexao);
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>