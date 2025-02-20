<?php
session_start();
require_once("../module/conecta.php");
$pg = new portal();
$conexao = $pg->conectar_obj();

$action = isset($_GET['action']) ? $_GET['action'] : '';
$response = ['success' => false, 'data' => []];

// AÇÕES EXISTENTES (getServiceNames, getSchemas, getTables, getAttributes)
if ($action == 'getServiceNames') {
    $ambiente = $_GET['ambiente'];
    $query = "SELECT DISTINCT service_name 
              FROM administracao.catalog_vw_lgpd_marcacao
              WHERE ambiente = $1
              ORDER BY service_name";
    $result = pg_query_params($conexao, $query, [$ambiente]);
    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $response['data'][] = $row['service_name'];
        }
        $response['success'] = true;
    }
} elseif ($action == 'getSchemas') {
    $ambiente = $_GET['ambiente'];
    $service_name = $_GET['service_name'];
    $query = "SELECT DISTINCT schema_name
              FROM administracao.catalog_vw_lgpd_marcacao
              WHERE ambiente = $1 
                AND service_name = $2
              ORDER BY schema_name";
    $result = pg_query_params($conexao, $query, [$ambiente, $service_name]);
    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $response['data'][] = $row['schema_name'];
        }
        $response['success'] = true;
    }
} elseif ($action == 'getTables') {
    $ambiente = $_GET['ambiente'];
    $service_name = $_GET['service_name'];
    $schema_name = $_GET['schema_name'];
    $query = "SELECT DISTINCT table_name
              FROM administracao.catalog_vw_lgpd_marcacao
              WHERE ambiente = $1
                AND service_name = $2
                AND schema_name = $3
              ORDER BY table_name";
    $result = pg_query_params($conexao, $query, [$ambiente, $service_name, $schema_name]);
    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $response['data'][] = $row['table_name'];
        }
        $response['success'] = true;
    }
} elseif ($action == 'getAttributes') {
    $ambiente = $_GET['ambiente'];
    $service_name = $_GET['service_name'];
    $schema_name = $_GET['schema_name'];
    $table_name = $_GET['table_name'];

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
            fk_lgpd_marcacao,
            data_base,
            host_name
        FROM administracao.catalog_vw_lgpd_marcacao
        WHERE ambiente = $1
          AND service_name = $2
          AND schema_name = $3
          AND table_name = $4
        ORDER BY column_id
    ";
    $result = pg_query_params($conexao, $query, [$ambiente, $service_name, $schema_name, $table_name]);
    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $response['data'][] = $row;
        }
        $response['success'] = true;
    }
}

// NOVA AÇÃO getAcoesInfos (join de catalog_acao_motivo_lgpd e catalog_lgpd_classificacao)
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
        $lista = [];
        while ($row = pg_fetch_assoc($result)) {
            $lista[] = [
                'acao' => $row['acao'],
                'info' => $row['info']
            ];
        }
        $response['data'] = $lista;
        $response['success'] = true;
    }
}

// INSERIR
elseif ($action == 'insertAttribute') {
    $ambiente         = $_GET['ambiente'];
    $service_name     = $_GET['service_name'];
    $schema_name      = $_GET['schema_name'];
    $table_name       = $_GET['table_name'];
    $column_name      = $_GET['column_name'];
    $acao_lgpd        = $_GET['acao_lgpd'];
    $lgpd_informacao  = $_GET['lgpd_informacao'];
    $column_comment   = isset($_GET['column_comment']) ? $_GET['column_comment'] : '';
    $data_base        = isset($_GET['data_base']) ? $_GET['data_base'] : '';
    $host_name        = isset($_GET['host_name']) ? $_GET['host_name'] : '';

    $fk_usuario_criador = isset($_SESSION['global_id_usuario']) ? $_SESSION['global_id_usuario'] : null;

    $insertQuery = "INSERT INTO administracao.catalog_lgpd_marcacao 
        (ambiente, data_base, host_name, service_name, schema_name, table_name, column_name, column_comment, acao_lgpd, lgpd_informacao, fk_usuario_criador)
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)";
    $result = pg_query_params($conexao, $insertQuery, [
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
        $fk_usuario_criador
    ]);
    if ($result) {
        $response['success'] = true;
        $response['message'] = "Registro inserido com sucesso.";
    } else {
        $response['message'] = pg_last_error($conexao);
    }
}
// REMOVER
elseif ($action == 'removeAttribute') {
    $ambiente      = $_GET['ambiente'];
    $data_base     = $_GET['data_base'];
    $host_name     = $_GET['host_name'];
    $service_name  = $_GET['service_name'];
    $schema_name   = $_GET['schema_name'];
    $table_name    = $_GET['table_name'];
    $column_name   = $_GET['column_name'];

    $deleteQuery = "DELETE FROM administracao.catalog_lgpd_marcacao 
         WHERE ambiente = $1
           AND data_base = $2
           AND host_name = $3
           AND service_name = $4
           AND schema_name = $5
           AND table_name = $6
           AND column_name = $7";
    $result = pg_query_params($conexao, $deleteQuery, [
        $ambiente,
        $data_base,
        $host_name,
        $service_name,
        $schema_name,
        $table_name,
        $column_name
    ]);
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
