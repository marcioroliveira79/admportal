<?php
session_start();
require_once("module/conecta.php");
$pg = new portal();
$conexao = $pg->conectar_obj();

$action = isset($_GET['action']) ? $_GET['action'] : '';
$response = ['success' => false, 'data' => []];

// ========== AÇÕES REAPROVEITADAS ==========
// getServiceNames
if ($action == 'getServiceNames') {
    $ambiente = $_GET['ambiente'];
    $query = "SELECT DISTINCT service_name
              FROM administracao.catalog_vw_lgpd_marcacao
              WHERE ambiente = $1
              ORDER BY service_name";
    $result = pg_query_params($conexao, $query, [$ambiente]);
    if ($result) {
        $arr = [];
        while ($row = pg_fetch_assoc($result)) {
            $arr[] = $row['service_name'];
        }
        $response['data'] = $arr;
        $response['success'] = true;
    }
}
// getSchemas
elseif ($action == 'getSchemas') {
    $ambiente = $_GET['ambiente'];
    $service_name = $_GET['service_name'];
    $query = "SELECT DISTINCT schema_name
              FROM administracao.catalog_vw_lgpd_marcacao
              WHERE ambiente = $1 AND service_name = $2
              ORDER BY schema_name";
    $result = pg_query_params($conexao, $query, [$ambiente, $service_name]);
    if ($result) {
        $arr = [];
        while ($row = pg_fetch_assoc($result)) {
            $arr[] = $row['schema_name'];
        }
        $response['data'] = $arr;
        $response['success'] = true;
    }
}
// getDbHost
elseif ($action == 'getDbHost') {
    $ambiente = $_GET['ambiente'];
    $service_name = $_GET['service_name'];
    $schema_name = $_GET['schema_name'];
    $query = "
        SELECT data_base, host_name
        FROM administracao.catalog_vw_lgpd_marcacao
        WHERE ambiente = $1
          AND service_name = $2
          AND schema_name = $3
        LIMIT 1
    ";
    $params = [$ambiente, $service_name, $schema_name];
    $result = pg_query_params($conexao, $query, $params);
    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $response['data'] = [
            'data_base' => $row['data_base'],
            'host_name' => $row['host_name']
        ];
        $response['success'] = true;
    } else {
        $response['data'] = [
            'data_base' => '',
            'host_name' => ''
        ];
        $response['success'] = true;
    }
}
// getUsersForSchemaAccess
elseif ($action == 'getUsersForSchemaAccess') {
    $ambiente = $_GET['ambiente'];
    $service_name = $_GET['service_name'];
    $schema_name = $_GET['schema_name'];

    $query = "
        SELECT 
            u.id AS user_id,
            (u.nome || ' ' || u.sobre_nome) AS nome_completo,
            u.email,
            CASE WHEN c.id IS NOT NULL THEN true ELSE false END AS has_access
        FROM administracao.adm_usuario u
        LEFT JOIN administracao.catalog_lgpd_acesso_schema_marcacao c
          ON c.fk_usuario = u.id
         AND c.ambiente = $1
         AND c.service_name = $2
         AND c.schema_name = $3
        WHERE u.ativo = true
        ORDER BY u.nome
    ";
    $params = [$ambiente, $service_name, $schema_name];
    $result = pg_query_params($conexao, $query, $params);
    if ($result) {
        $arr = [];
        while ($row = pg_fetch_assoc($result)) {
            $arr[] = [
                'id' => $row['user_id'],
                'nome_completo' => $row['nome_completo'],
                'email' => $row['email'],
                'has_access' => ($row['has_access'] === 't')
            ];
        }
        $response['data'] = $arr;
        $response['success'] = true;
    }
}
// insertAccess
elseif ($action == 'insertAccess') {
    $ambiente = $_GET['ambiente'];
    $data_base = $_GET['data_base'];
    $host_name = $_GET['host_name'];
    $service_name = $_GET['service_name'];
    $schema_name = $_GET['schema_name'];
    $fk_usuario = $_GET['fk_usuario'];

    $fk_usuario_criador = isset($_SESSION['global_id_usuario']) ? $_SESSION['global_id_usuario'] : null;

    $insert = "
        INSERT INTO administracao.catalog_lgpd_acesso_schema_marcacao
        (ambiente, data_base, host_name, service_name, schema_name, fk_usuario, fk_usuario_criador)
        VALUES ($1, $2, $3, $4, $5, $6, $7)
    ";
    $params = [$ambiente, $data_base, $host_name, $service_name, $schema_name, $fk_usuario, $fk_usuario_criador];
    $result = pg_query_params($conexao, $insert, $params);
    if ($result) {
        $response['success'] = true;
        $response['message'] = "Acesso concedido com sucesso.";
    } else {
        $response['message'] = pg_last_error($conexao);
    }
}
// removeAccess
elseif ($action == 'removeAccess') {
    $ambiente = $_GET['ambiente'];
    $data_base = $_GET['data_base'];
    $host_name = $_GET['host_name'];
    $service_name = $_GET['service_name'];
    $schema_name = $_GET['schema_name'];
    $fk_usuario = $_GET['fk_usuario'];

    $delete = "
        DELETE FROM administracao.catalog_lgpd_acesso_schema_marcacao
         WHERE ambiente = $1
           AND data_base = $2
           AND host_name = $3
           AND service_name = $4
           AND schema_name = $5
           AND fk_usuario = $6
    ";
    $params = [$ambiente, $data_base, $host_name, $service_name, $schema_name, $fk_usuario];
    $result = pg_query_params($conexao, $delete, $params);
    if ($result) {
        $response['success'] = true;
        $response['message'] = "Acesso removido com sucesso.";
    } else {
        $response['message'] = pg_last_error($conexao);
    }
}

// ========== NOVAS AÇÕES PARA DETALHES NO MODAL ==========

// getUserDetailAccess -> retorna todos os registros do user
elseif ($action == 'getUserDetailAccess') {
    $fk_usuario = $_GET['fk_usuario'];
    $query = "
        SELECT 
            c.id,
            c.ambiente,
            c.service_name,
            c.schema_name,
            to_char(c.data_criacao, 'YYYY-MM-DD HH24:MI:SS') AS data_criacao,
            COALESCE(u2.nome || ' ' || u2.sobre_nome, 'Desconhecido') AS criador_nome
        FROM administracao.catalog_lgpd_acesso_schema_marcacao c
        LEFT JOIN administracao.adm_usuario u2
          ON c.fk_usuario_criador = u2.id
        WHERE c.fk_usuario = $1
        ORDER BY c.data_criacao DESC
    ";
    $params = [$fk_usuario];
    $result = pg_query_params($conexao, $query, $params);
    if ($result) {
        $arr = [];
        while ($row = pg_fetch_assoc($result)) {
            $arr[] = [
                'id' => $row['id'],
                'ambiente' => $row['ambiente'],
                'service_name' => $row['service_name'],
                'schema_name' => $row['schema_name'],
                'data_criacao' => $row['data_criacao'],
                'criador_nome' => $row['criador_nome']
            ];
        }
        $response['data'] = $arr;
        $response['success'] = true;
    }
}
// removeAccessById -> deleta pelo id
elseif ($action == 'removeAccessById') {
    $id = $_GET['id'];
    $deleteQuery = "DELETE FROM administracao.catalog_lgpd_acesso_schema_marcacao WHERE id = $1";
    $result = pg_query_params($conexao, $deleteQuery, [$id]);
    if ($result) {
        $response['success'] = true;
        $response['message'] = "Acesso removido com sucesso.";
    } else {
        $response['message'] = pg_last_error($conexao);
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
