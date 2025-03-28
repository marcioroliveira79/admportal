<?php
session_start();
require_once __DIR__ . '/../module/conecta.php';


$pg = new portal();
$conexao = $pg->conectar_obj();


$action = isset($_GET['action']) ? $_GET['action'] : '';
$response = ['success' => false, 'data' => []];

if ($action == 'getServiceNames') {
    $ambiente = $_GET['ambiente'];
    $query = "SELECT DISTINCT service_name FROM administracao.catalog_table_content WHERE ambiente = $1 ORDER BY service_name";
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
    $query = "SELECT DISTINCT schema_name FROM administracao.catalog_table_content WHERE ambiente = $1 AND service_name = $2 ORDER BY schema_name";
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
    $query = "SELECT DISTINCT table_name FROM administracao.catalog_table_content WHERE ambiente = $1 AND service_name = $2 AND schema_name = $3 ORDER BY table_name";
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
    // A consulta agora retorna também table_comments e column_comments
    $query = "SELECT table_comments, column_name, data_type, data_length, column_comments, is_nullable, is_pk, is_fk  
              FROM administracao.catalog_table_content 
              WHERE ambiente = $1 AND service_name = $2 AND schema_name = $3 AND table_name = $4 
              ORDER BY column_id";
    $result = pg_query_params($conexao, $query, [$ambiente, $service_name, $schema_name, $table_name]);
    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $response['data'][] = $row;
        }
        $response['success'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
