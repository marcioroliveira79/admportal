<?php
// Inclui o arquivo de conexão
require_once 'db_connect.php';

try {
    // Verifica se a conexão foi estabelecida com sucesso
    if ($pdo instanceof PDO) {
        echo "Conexão com o banco de dados estabelecida com sucesso!";
    } else {
        echo "Falha na conexão com o banco de dados.";
    }
} catch (Exception $e) {
    // Captura exceções gerais, caso algo inesperado ocorra
    echo "Erro durante o teste de conexão: " . $e->getMessage();
}
?>
