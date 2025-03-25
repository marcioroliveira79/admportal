<?php
session_start();
require_once("module/conecta.php");
require_once("module/functions.php");

date_default_timezone_set('America/Sao_Paulo');
$dataHora = date("d-m-Y H:i:s");

$pg = new portal();
$conexao = $pg->conectar_obj();

if (!$conexao) {
    die("Erro ao conectar ao banco de dados.");
}

$responses = []; // Array para armazenar todas as respostas

// No início do ping.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST)) {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);
    if ($input) {
        $_POST = $input;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log('POST Data: ' . print_r($_POST, true));
}

if (!isset($_POST['idus']) || !isset($_POST['session'])) {
    $responses[] = [
        'success' => false,
        'message' => 'Parâmetros idus ou session não fornecidos.',
        'ID' => $_POST['idus'] ?? null,
        'Session' => $_POST['session'] ?? null
    ];
    // Se desejar interromper a execução após esse erro, use exit()
    // exit();
}

$id_usuario = (int)$_POST['idus'];
$session_id = $_POST['session'];
$ip = $_POST['ip'] ?? $_SERVER['REMOTE_ADDR'];

$tempo_offline = AtributoSistema("parametros_sistema", "tempo_offline", null, $conexao);
$tempo_sessao = AtributoSistema("parametros_sistema", "time_session", null, $conexao);

$query = "
    SELECT data_acesso,
           CASE 
             WHEN data_ping IS NULL THEN ROUND(EXTRACT(EPOCH FROM (NOW() - data_acesso))) 
             ELSE ROUND(EXTRACT(EPOCH FROM (NOW() - data_ping))) 
           END diff_seconds,
           id,
           session_id
    FROM administracao.adm_log_acesso 
    WHERE fk_usuario = $1             
    ORDER BY id DESC
    LIMIT 1
";
$result = pg_query_params($conexao, $query, [$id_usuario]);

if (!$result) {
    $responses[] = [
        'success' => false,
        'message' => 'Erro na consulta: ' . pg_last_error($conexao),
        'id_usuario' => $id_usuario,
        'session_id' => $session_id,
        'Data Execucao' => $dataHora
    ];
} else {
    $row = pg_fetch_assoc($result);
    
    if ($row) {
        $diff = $row['diff_seconds'];
        $id_log = $row['id'];
        $id_session_ultima = $row['session_id'];

        if ($diff > $tempo_offline && $id_session_ultima != $session_id ) {
            $query_offline = "
                UPDATE administracao.adm_log_acesso 
                SET data_saida = now(), data_ping = now(), metodo_logout='TEMPO OFFLINE 1'
                WHERE fk_usuario = $1 
                  AND id = $2               
            ";
            pg_query_params($conexao, $query_offline, [$id_usuario, $id_log]);

            $responses[] = [
                'success' => false,
                'message' => 'Usuário offline.',
                'id_usuario' => $id_usuario,
                'session_id' => $session_id,
                'tempo_offline' => $tempo_offline,
                'dif' => $diff,
                'ID Log' => $id_log,
                'update' => 'USUARIO OFFLINE',
                'Data Execucao' => $dataHora
            ];
        } else {
            $query_ping = "
                UPDATE administracao.adm_log_acesso 
                SET data_ping = now(), ip_acesso = $3, data_saida = null
                WHERE fk_usuario = $1 
                  AND id = $2               
            ";
            $result_ping = pg_query_params($conexao, $query_ping, [$id_usuario, $id_log, $ip]);

            if ($result_ping) {
                $responses[] = [
                    'success' => true,
                    'message' => 'Ping atualizado.',
                    'id_usuario' => $id_usuario,
                    'session_id' => $session_id,
                    'tempo_offline' => $tempo_offline,
                    'dif' => $diff,
                    'ID Log' => $id_log,
                    'update' => 'USUARIO ONLINE',
                    'Data Execucao' => $dataHora
                ];
            } else {
                $responses[] = [
                    'success' => false,
                    'message' => 'Erro ao atualizar ping: ' . pg_last_error($conexao),
                    'id_usuario' => $id_usuario,
                    'session_id' => $session_id,
                    'Data Execucao' => $dataHora
                ];
            }
        }
    } else {
        $responses[] = [
            'success' => false,
            'message' => 'Registro de acesso não encontrado.',
            'id_usuario' => $id_usuario,
            'session_id' => $session_id,
            'Data Execucao' => $dataHora
        ];
    }
}

$query_update_offline = "
    UPDATE administracao.adm_log_acesso
    SET data_saida = NOW(), metodo_logout='TEMPO OFFLINE 2'    
    WHERE 1=1
      AND (EXTRACT(EPOCH FROM (NOW() - data_ping)) > $1 OR EXTRACT(EPOCH FROM (NOW() - data_acesso)) > $1)
      AND (data_saida IS NULL AND data_ping IS NULL)  
";

$result_update_offline = pg_query_params($conexao, $query_update_offline, [$tempo_offline]);

if ($result_update_offline) {
    $responses[] = [
        'success' => true,
        'message' => 'Data saida atualizado.',
        'tempo_offline' => $tempo_offline,
        'update' => 'USUARIO OFFLINE'
    ];
} else {
    $responses[] = [
        'success' => false,
        'message' => 'Erro ao atualizar ping: ' . pg_last_error($conexao),
        'update' => 'USUARIO OFFLINE'
    ];
}

$query_update_sessao = "
    UPDATE administracao.adm_log_acesso
    SET data_saida = NOW(),  metodo_logout='TEMPO SESSAO'      
    WHERE 1=1
      AND (EXTRACT(EPOCH FROM (NOW() - data_ping)) > $1 OR EXTRACT(EPOCH FROM (NOW() - data_acesso)) > $1)
      AND data_saida IS NULL  
";

$result_update_sessao = pg_query_params($conexao, $query_update_sessao, [$tempo_sessao]);

if ($result_update_sessao) {
    $responses[] = [
        'success' => true,
        'message' => 'Data saida atualizado.',
        'tempo_sessao' => $tempo_sessao,
        'update' => 'USUARIO DESCONECTADO'
    ];
} else {
    $responses[] = [
        'success' => false,
        'message' => 'Erro ao atualizar ping: ' . pg_last_error($conexao),
        'update' => 'USUARIO DESCONECTADO'
    ];
}

// Envia a resposta única com todos os logs
header('Content-Type: application/json');
echo json_encode($responses);
exit();
