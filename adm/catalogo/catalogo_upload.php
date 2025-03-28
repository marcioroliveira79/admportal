<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Configurações
$uploadKey = "123456"; // Defina sua chave de autorização
$uploadDir = "uploads/"; // Diretório onde o arquivo será salvo

// Define o retorno como JSON
header('Content-Type: application/json');

// Registra o horário de início do upload
$upload_start = microtime(true);

// Captura o IP do cliente
$client_ip = $_SERVER['REMOTE_ADDR'];

// Validação da chave de autorização (pode ser enviada por GET ou POST)
if (!isset($_REQUEST['key']) || $_REQUEST['key'] !== $uploadKey) {
    http_response_code(403);
    ob_clean();
    echo json_encode([
        'status'      => 'erro',
        'message'     => 'Chave de autorização inválida',
        'client_ip'   => $client_ip,
        'upload_time' => date("Y-m-d H:i:s")
    ]);
    exit;
}

// Verifica se o arquivo e o hash foram enviados
if (!isset($_FILES['file']) || !isset($_POST['hash'])) {
    http_response_code(400);
    ob_clean();
    echo json_encode([
        'status'      => 'erro',
        'message'     => 'Arquivo ou hash não fornecido',
        'client_ip'   => $client_ip,
        'upload_time' => date("Y-m-d H:i:s")
    ]);
    exit;
}

$file = $_FILES['file'];
$expectedHash = $_POST['hash'];

// Valida se ocorreu algum erro no upload
if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(500);
    ob_clean();
    echo json_encode([
        'status'      => 'erro',
        'message'     => 'Erro no upload do arquivo. Código: ' . $file['error'],
        'client_ip'   => $client_ip,
        'upload_time' => date("Y-m-d H:i:s")
    ]);
    exit;
}

// Calcula o hash do arquivo recebido (usando MD5 neste exemplo)
$computedHash = md5_file($file['tmp_name']);
if ($computedHash !== $expectedHash) {
    http_response_code(400);
    ob_clean();
    echo json_encode([
        'status'    => 'erro',
        'message'   => 'Integridade do arquivo falhou. Hash esperada: ' . $expectedHash . ', recebida: ' . $computedHash,
        'client_ip' => $client_ip,
        'upload_time' => date("Y-m-d H:i:s")
    ]);
    exit;
}

// Garante que o diretório de upload existe
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Define o caminho de destino para salvar o arquivo
$destination = $uploadDir . basename($file['name']);

// Move o arquivo para o diretório de destino
if (!move_uploaded_file($file['tmp_name'], $destination)) {
    http_response_code(500);
    ob_clean();
    echo json_encode([
        'status'      => 'erro',
        'message'     => 'Falha ao mover o arquivo para o destino',
        'client_ip'   => $client_ip,
        'upload_time' => date("Y-m-d H:i:s")
    ]);
    exit;
}

// Registra o horário de término do upload e calcula a duração
$upload_end = microtime(true);
$duration = $upload_end - $upload_start;
$durationFormatted = sprintf("%02d min %02d seg", floor($duration/60), $duration % 60);

// Informações adicionais do arquivo
$fileSize = $file['size']; // Tamanho do arquivo em bytes
$fileName = $file['name']; // Nome original do arquivo

ob_clean();
echo json_encode([
    'status'       => 'sucesso',
    'message'      => 'Arquivo transferido com sucesso',
    'file_path'    => $destination,
    'file_name'    => $fileName,
    'file_size'    => $fileSize,
    'upload_start' => date("Y-m-d H:i:s", floor($upload_start)),
    'upload_end'   => date("Y-m-d H:i:s", floor($upload_end)),
    'duration'     => $durationFormatted,
    'client_ip'    => $client_ip
]);

ob_end_flush();
?>
