<?php
// db_connect.php

// Configurações de conexão com o banco de dados PostgreSQL
$host = 'localhost'; // Host do banco de dados
$port = '5432'; // Porta do PostgreSQL
$dbname = 'postgres'; // Nome do banco de dados
$user = 'postgres'; // Usuário do banco
$password = 'admin'; // Senha do banco

// Tenta estabelecer a conexão com o banco de dados
try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erro
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Define o fetch como array associativo
    ]);
} catch (PDOException $e) {
    // Caso ocorra um erro, exibe a mensagem e encerra o script
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
