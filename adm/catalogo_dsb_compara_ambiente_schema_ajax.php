
<?php
##catalogo_dsb_compara_ambiente_schema_ajax.php
session_start();
require_once("module/conecta.php");
require_once("module/functions.php");

$pg = new portal();
$conexao = $pg->conectar_obj();
if (isset($_GET['ambiente']) && isset($_GET['service'])) {
    $ambiente = trim($_GET['ambiente']);
    $service = trim($_GET['service']);
    $query = "SELECT DISTINCT schema_name FROM administracao.catalog_table_content WHERE ambiente = $1 AND UPPER(service_name) = $2 ORDER BY schema_name";
    $result = pg_query_params($conexao, $query, [$ambiente, strtoupper($service)]);
    $schemas = pg_fetch_all($result);
    echo '<option value="">Todos</option>';
    if ($schemas) {
        foreach ($schemas as $row) {
            $schema = strtoupper($row['schema_name']);
            echo "<option value=\"" . htmlspecialchars($schema) . "\">" . htmlspecialchars($schema) . "</option>";
        }
    }
}
?>
