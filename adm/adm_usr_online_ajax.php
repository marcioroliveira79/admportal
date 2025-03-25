<?php
session_start();
require_once("module/conecta.php");

header('Content-Type: application/json');

$pg = new portal();
$conexao = $pg->conectar_obj();

if (!$conexao) {
    die("Erro ao conectar ao banco de dados.");
}


// Consulta os dados da view
$query = "
        SELECT 
        nome_formatado, 
        TO_CHAR(data_acesso, 'DD/MM/YYYY HH24:MI:SS') AS data_acesso, 
        TO_CHAR(data_saida, 'DD/MM/YYYY HH24:MI:SS') AS data_saida, 
        TO_CHAR(data_ping, 'DD/MM/YYYY HH24:MI:SS') AS data_ping, 
        status
        FROM administracao.status_usuario_vw
        WHERE status = 'online'
        ORDER BY data_acesso DESC;

";
$result = pg_query($conexao, $query);

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro na consulta: ' . pg_last_error($conexao)
    ]);
    exit();
}

$data = [];
while ($row = pg_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode([
    'success' => true,
    'data' => $data
]);
exit();
?>
