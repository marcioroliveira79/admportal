<?php
session_start();
require_once __DIR__ . '/../module/conecta.php';
require_once __DIR__ . '/../module/functions.php';

$pg = new portal();
$conexao = $pg->conectar_obj();

// Verifica se os parâmetros necessários foram informados
if (!isset($_GET['ambiente'], $_GET['service_name'], $_GET['data_type'], $_GET['atributo'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Parâmetros ausentes.']);
    exit;
}

$ambiente    = $_GET['ambiente'];
$serviceName = $_GET['service_name'];
$dataType    = $_GET['data_type'];
$atributo    = $_GET['atributo'];

// Consulta que retorna os atributos conforme os filtros
$query = "
    SELECT ambiente, service_name, schema_name, table_name, column_name, data_type, data_length
    FROM administracao.catalog_table_content
    WHERE ambiente = $1
      AND service_name = $2
      AND data_type = $3
      AND column_name ILIKE '%' || $4 || '%'
    ORDER BY schema_name, table_name, column_name
";
$result = pg_query_params($conexao, $query, [$ambiente, $serviceName, $dataType, $atributo]);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => pg_last_error($conexao)]);
    exit;
}

$data = pg_fetch_all($result) ?: [];
$total = count($data);

header('Content-Type: application/json');
echo json_encode(['data' => $data, 'total' => $total]);
?>
