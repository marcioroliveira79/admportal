<?php
// Dados de conexão atualizados:
$host     = 'postgres_db'; // Use o nome do container do PostgreSQL
$port     = '5432';
$dbname   = 'portal_homologacao';
$user     = 'usr_homologacao';
$password = 'Portal2025';

$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

// Tenta conectar
$conn = pg_connect($conn_string);

// Verifica se a conexão foi bem-sucedida
if (!$conn) {
    // Para evitar o uso do pg_last_error() sem conexão, exiba uma mensagem simples
    die("Erro ao conectar no PostgreSQL.");
} else {
    echo "Conexão realizada com sucesso!";
}
?>
