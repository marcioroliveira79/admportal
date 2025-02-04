<?php
session_start();
require_once("../module/conecta.php");
require_once("../module/functions.php");

// Cria a conexão com o banco de dados
$pg = new portal();
$conexao = $pg->conectar_obj();

if (!$conexao) {
    die("Erro ao conectar ao banco de dados.");
}

// No início do ping.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST)) {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);
    if ($input) {
        $_POST = $input;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Exibe os valores recebidos para depuração
    error_log('POST Data: ' . print_r($_POST, true));
}

// Verifica se os parâmetros foram enviados pelo POST
if (!isset($_POST['idus']) || !isset($_POST['session'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Parâmetros idus ou session não fornecidos.',
        'ID' => $_POST['idus'],
        'Session' => $_POST['session']
    ]);
    exit();
}

// Define as variáveis recebidas
$id_usuario = (int)$_POST['idus']; // ID do usuário
$session_id = $_POST['session'];  // ID da sessão
$ip = $_POST['ip'] ?? $_SERVER['REMOTE_ADDR']; // IP do usuário


// Recupera o tempo de inatividade (em segundos) para considerar o usuário offline
$tempo_offline = AtributoSistema("parametros_sistema", "tempo_offline", null, $conexao);


$query_update_offline = "
    UPDATE administracao.adm_log_acesso
    SET data_saida = NOW()
    WHERE data_ping IS NOT NULL 
      AND EXTRACT(EPOCH FROM (NOW() - data_ping)) > $1
      AND data_saida IS NULL
      AND session_id != $2;
";

$result_update_offline = pg_query_params($conexao, $query_update_offline, [$tempo_offline, $session_id]);

// Consulta o registro de log de acesso do usuário
$query = "
    SELECT data_acesso,
    ROUND(EXTRACT(EPOCH FROM (NOW() - data_ping))) AS diff_seconds
    FROM administracao.adm_log_acesso 
    WHERE fk_usuario = $1 
      AND session_id = $2       
    ORDER BY data_acesso DESC 
    LIMIT 1
";
$result = pg_query_params($conexao, $query, [$id_usuario, $session_id]);

header('Content-Type: application/json');

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro na consulta: ' . pg_last_error($conexao),
        'id_usuario' => $id_usuario,
        'session_id' => $session_id
    ]);
    exit();
}

$row = pg_fetch_assoc($result);

if ($row) {
    // Calcula a diferença entre o último acesso e o tempo atual
    $data_acesso_timestamp = strtotime($row['data_acesso']);
    $diff = $row['diff_seconds'];

    if ($diff > $tempo_offline) {
        // Marca o usuário como offline
        $query_offline = "
            UPDATE administracao.adm_log_acesso 
            SET data_saida = null, data_ping = now()
            WHERE fk_usuario = $1 
              AND session_id = $2 
              AND data_saida IS NOT NULL
        ";
        pg_query_params($conexao, $query_offline, [$id_usuario, $session_id]);

        echo json_encode([
            'success' => false,
            'message' => 'Usuário offline.',
            'id_usuario' => $id_usuario,
            'session_id' => $session_id,
            'tempo_offline' => $tempo_offline,
            'dif' => $diff,
            'update 1' => 'UP1'
        ]);
    } else {
        // Atualiza o campo data_ping com o horário atual
        $query_ping = "
            UPDATE administracao.adm_log_acesso 
            SET data_ping = now(), ip_acesso = $3 , data_saida = null
            WHERE fk_usuario = $1 
              AND session_id = $2 
              AND data_saida IS NOT NULL
        ";
        $result_ping = pg_query_params($conexao, $query_ping, [$id_usuario, $session_id, $ip]);

        if ($result_ping) {
            echo json_encode([
                'success' => true,
                'message' => 'Ping atualizado.',
                'id_usuario' => $id_usuario,
                'session_id' => $session_id,
                'tempo_offline' => $tempo_offline,
                'dif' => $diff,
                'update 2' => 'UP2'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao atualizar ping: ' . pg_last_error($conexao),
                'id_usuario' => $id_usuario,
                'session_id' => $session_id
            ]);
        }
    }
} else {
    // Caso nenhum registro seja encontrado
    echo json_encode([
        'success' => false,
        'message' => 'Registro de acesso não encontrado.',
        'id_usuario' => $id_usuario,
        'session_id' => $session_id
    ]);
}
exit();
