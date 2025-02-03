<?php
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $errorMessage = $_SESSION['login_error'] ?? null;
    unset($_SESSION['login_error']); // Pode ser comentado para depuração
  
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #1c5243;
            font-family: Arial, sans-serif;
        }

        .login-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1c5243;
        }

        .form-label {
            font-weight: bold;
            color: #1c5243;
        }

        .btn-primary {
            background-color: #28a167;
            border: none;
        }

        .btn-primary:hover {
            background-color: #1c5243;
        }

        .error-message {
            color: red;
            font-size: 0.9em;
            text-align: center;
            margin-bottom: 10px;
            display: <?= $errorMessage ? 'block' : 'none' ?>;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9em;
            color: #1c5243;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <!-- Mensagem de erro -->
        <div id="errorMessage" class="error-message">
            <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
        </div>
        <!-- Formulário de login -->
        <form id="loginForm" action="index.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Usuário</label>
                <input type="text" id="username" name="login" class="form-control" placeholder="Digite seu usuário" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" id="password" name="senha" class="form-control" placeholder="Digite sua senha" required>
            </div>
            <input type="hidden" name="acao" value="logar">
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
        <div class="footer">
            &copy; 2025 Unisys. Todos os direitos reservados.
        </div>
    </div>
</body>
</html>
