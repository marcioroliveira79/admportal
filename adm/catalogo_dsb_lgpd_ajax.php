<?php
session_start();

// Exemplo de conexao
require_once("../module/conecta.php");
$pg = new portal();
$conexao = $pg->conectar_obj();

$action = isset($_GET['action']) ? $_GET['action'] : '';
$response = ['success' => false, 'data' => []];

// Carrega os ambientes
if ($action == 'getAmbientes') {
    $query = "SELECT DISTINCT ambiente FROM administracao.catalog_vw_lgpd_marcacao ORDER BY ambiente";
    $result = pg_query($conexao, $query);
    if ($result) {
        $arr = [];
        while ($row = pg_fetch_assoc($result)) {
            $arr[] = $row['ambiente'];
        }
        $response['success'] = true;
        $response['data'] = $arr;
    }
}
// Carrega os services
elseif ($action == 'getServices') {
    $ambiente = $_GET['ambiente'] ?? '';
    $query = "
        SELECT DISTINCT service_name
        FROM administracao.catalog_vw_lgpd_marcacao
        WHERE ambiente = $1
        ORDER BY service_name
    ";
    $res = pg_query_params($conexao, $query, [$ambiente]);
    if ($res) {
        $arr = [];
        while ($row = pg_fetch_assoc($res)) {
            $arr[] = $row['service_name'];
        }
        $response['success'] = true;
        $response['data'] = $arr;
    }
}
// Carrega schemas
elseif ($action == 'getSchemas') {
    $ambiente    = $_GET['ambiente'] ?? '';
    $serviceName = $_GET['service_name'] ?? '';
    $query = "
        SELECT DISTINCT schema_name
        FROM administracao.catalog_vw_lgpd_marcacao
        WHERE ambiente = $1
          AND service_name = $2
        ORDER BY schema_name
    ";
    $res = pg_query_params($conexao, $query, [$ambiente, $serviceName]);
    if ($res) {
        $arr = [];
        while ($row = pg_fetch_assoc($res)) {
            $arr[] = $row['schema_name'];
        }
        $response['success'] = true;
        $response['data'] = $arr;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
