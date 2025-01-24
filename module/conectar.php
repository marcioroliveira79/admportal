<?php
// Inclui o arquivo de conexão
include 'db_connect.php';

// Obtém os dados enviados pelo formulário
$username = $_POST['username'];
$password = $_POST['password'];

try {
    // Consulta para verificar se o usuário existe e obter a senha armazenada
    $sql = "SELECT PK_USUARIO, SENHA FROM PORTAL.USUARIO WHERE LOGIN = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verifica se a senha informada corresponde à senha armazenada (hash)
        if (password_verify($password, $user['SENHA_USUARIO'])) {
            // Insere o registro de login na tabela de log
            $sqlLog = "INSERT INTO PORTAL.LOG_ACESSO_USUARIO (PK_USUARIO, DATA_LOGIN) VALUES (:pk_usuario, NOW())";
            $stmtLog = $conn->prepare($sqlLog);
            $stmtLog->bindParam(':pk_usuario', $user['PK_USUARIO'], PDO::PARAM_INT);
            $stmtLog->execute();

            // Redireciona ou exibe mensagem de sucesso
            echo "Login bem-sucedido! Bem-vindo, " . htmlspecialchars($username) . ".";
        } else {
            // Senha incorreta
            echo "Usuário ou senha inválidos.";
        }
    } else {
        // Usuário não encontrado
        echo "Usuário ou senha inválidos.";
    }
} catch (PDOException $e) {
    echo "Erro ao processar o login: " . $e->getMessage();
}

// Fecha a conexão
$conn = null;
?>
