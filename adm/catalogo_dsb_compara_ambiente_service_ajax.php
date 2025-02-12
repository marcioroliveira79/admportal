<?php
##catalogo_dsb_compara_ambiente_service_ajax.php
session_start();
require_once("../module/conecta.php");
require_once("../module/functions.php");

$pg = new portal();
$conexao = $pg->conectar_obj();

if (isset($_GET['ambiente'])) {
    $ambiente = trim($_GET['ambiente']);
    $query = "SELECT DISTINCT service_name FROM administracao.catalog_table_content WHERE ambiente = $1 ORDER BY service_name";
    $result = pg_query_params($conexao, $query, [$ambiente]);
    $services = pg_fetch_all($result);
    echo '<option value="">Todos</option>';
    if ($services) {
        foreach ($services as $row) {
            // Converte para MAIÚSCULO para padronização
            $service = strtoupper($row['service_name']);
            echo "<option value=\"" . htmlspecialchars($service) . "\">" . htmlspecialchars($service) . "</option>";
        }
    }
}
?>