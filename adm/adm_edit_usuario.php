<?php
session_start();
if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) and $acao != null) {
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        // Carrega os dados do usuário atual
        $usuario_id = $_SESSION['global_id_usuario'];
        $query = "
            SELECT u.nome, u.sobre_nome, u.email, u.telefone, u.login, u.senha, 
                   TO_CHAR(u.data_criacao, 'DD-MM-YYYY HH24:MI') AS data_criacao,
                   TO_CHAR(u.data_atualizacao, 'DD-MM-YYYY HH24:MI') AS data_atualizacao,
                   u.fk_usuario_alteracao, u.ativo,
                   ua.login AS ultimo_alterador
            FROM administracao.adm_usuario u
            LEFT JOIN administracao.adm_usuario ua ON u.fk_usuario_alteracao = ua.id
            WHERE u.id = $1
        ";
        $result = pg_query_params($conexao, $query, [$usuario_id]);

        if ($result && pg_num_rows($result) > 0) {
            $usuario = pg_fetch_assoc($result);
        } else {
            die("Erro ao carregar os dados do usuário.");
        }

        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = htmlspecialchars($_POST['nome']);
            $sobre_nome = htmlspecialchars($_POST['sobre_nome']);
            $email = htmlspecialchars($_POST['email']);
            $telefone = htmlspecialchars($_POST['telefone']);
            $login = htmlspecialchars($_POST['login']);            
            $senha = !empty($_POST['senha']) ? htmlspecialchars($_POST['senha']) : $usuario['senha'];
            $ativo = isset($_POST['ativo']) ? 'true' : 'false';
            $fk_usuario_alteracao = $_SESSION['global_id_usuario'];

            // Verifica se o novo login ou e-mail já existem para outro usuário
            $query_verifica_login = "SELECT id FROM administracao.adm_usuario WHERE login = $1 AND id != $2";
            $result_verifica_login = pg_query_params($conexao, $query_verifica_login, [$login, $usuario_id]);

            $query_verifica_email = "SELECT id FROM administracao.adm_usuario WHERE email = $1 AND id != $2";
            $result_verifica_email = pg_query_params($conexao, $query_verifica_email, [$email, $usuario_id]);

            if ($result_verifica_login && pg_num_rows($result_verifica_login) > 0) {
                $mensagem = "O login já está em uso. Por favor, escolha outro.";
            } elseif ($result_verifica_email && pg_num_rows($result_verifica_email) > 0) {
                $mensagem = "O e-mail já está em uso. Por favor, escolha outro.";
            } else {
                // Atualiza os dados do usuário
                $query_update = "
                    UPDATE administracao.adm_usuario
                    SET nome = $1, sobre_nome = $2, email = $3, telefone = $4, 
                        login = $5, senha = $6, ativo = $7, 
                        data_atualizacao = now(), fk_usuario_alteracao = $8
                    WHERE id = $9
                ";
                $result_update = pg_query_params(
                    $conexao,
                    $query_update,
                    [$nome, $sobre_nome, $email, $telefone, $login, $senha, $ativo, $fk_usuario_alteracao, $usuario_id]
                );

                if ($result_update) {
                    $mensagem = "Usuário atualizado com sucesso!";
                    // Atualiza os dados do usuário no banco para recarregar os campos
                    $result = pg_query_params($conexao, $query, [$usuario_id]);
                    $usuario = pg_fetch_assoc($result);
                } else {
                    $mensagem = "Erro ao atualizar o usuário. Por favor, tente novamente.";
                    $erro_banco = pg_last_error($conexao);
                }
            }
        }

        ?>
        <!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
                if (window.top === window.self) {
                    // Se a página não estiver sendo exibida dentro de um iframe, redireciona para o index
                    window.location.href = 'index.php';
                }
            </script>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .form-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 800px;
            margin: 40px auto;
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 24px;
            color: #4a4a4a;
        }
        .btn-submit {
            background-color: #4caf50;
            border-color: #4caf50;
            color: white;
        }
        .btn-submit:hover {
            background-color: #45a049;
            color: white;
        }
        
        .password-group {
            position: relative;
        }

        .password-group input {
            width: 100%;
            padding-right: 40px; /* Deixe espaço para o ícone */
        }

        .password-group .toggle-password {
            position: absolute;
            top: 50%; /* Centraliza verticalmente */
            right: 10px; /* Ajusta a posição na borda direita */
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 20px;
            color: #888;
        }
    </style>
    <script>
        // Alternar visualização da senha
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        }

        // Validar confirmação de senha
        function validateForm(event) {
            const senha = document.getElementById('senha').value;
            const confirmSenha = document.getElementById('confirm_senha').value;

            if (senha !== confirmSenha) {
                event.preventDefault();
                alert("As senhas não coincidem. Por favor, verifique.");
            }
        }

        // Formatação de telefone
        function formatPhone(input) {
            let value = input.value.replace(/\D/g, ''); // Remove caracteres não numéricos

            if (value.length > 10) { // Celular com 11 dígitos
                input.value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7, 11)}`;
            } else if (value.length > 6) { // Telefone fixo com 10 dígitos
                input.value = `(${value.slice(0, 2)}) ${value.slice(2, 6)}-${value.slice(6)}`;
            } else if (value.length > 2) { // Somente o DDD
                input.value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
            } else {
                input.value = value;
            }
        }
    </script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="form-container">
        <div class="form-title">Editar Meu Usuário</div>
        <?php if (isset($mensagem)): ?>
            <div class="alert alert-info text-center">
                <?= htmlspecialchars($mensagem) ?>
                <?php if (isset($erro_banco)): ?>
                    <br><small class="text-danger"><?= htmlspecialchars($erro_banco) ?></small>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <form method="POST" onsubmit="validateForm(event)">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" maxlength="50" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="sobre_nome" class="form-label">Sobrenome</label>
                    <input type="text" class="form-control" id="sobre_nome" name="sobre_nome" maxlength="150" value="<?= htmlspecialchars($usuario['sobre_nome']) ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" maxlength="50" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" maxlength="15" value="<?= htmlspecialchars($usuario['telefone']) ?>" oninput="formatPhone(this)" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="login" class="form-label">Login</label>
                    <input type="text" class="form-control" id="login" name="login" maxlength="20" value="<?= htmlspecialchars($usuario['login']) ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3 password-group">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" maxlength="20">
                    <i class="bi bi-eye toggle-password" id="toggleSenha" onclick="togglePasswordVisibility('senha', 'toggleSenha')"></i>
                </div>
                <div class="col-md-6 mb-3 password-group">
                    <label for="confirm_senha" class="form-label">Confirme a Senha</label>
                    <input type="password" class="form-control" id="confirm_senha" name="confirm_senha" maxlength="20">
                    <i class="bi bi-eye toggle-password" id="toggleConfirmSenha" onclick="togglePasswordVisibility('confirm_senha', 'toggleConfirmSenha')"></i>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="ativo" class="form-label">Ativo</label>
                    <input type="checkbox" id="ativo" name="ativo" <?= $usuario['ativo'] === 't' ? 'checked' : '' ?>>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-submit btn-lg">Salvar</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>

        <?php
    } elseif ($acesso != "TELA AUTORIZADA") {
        @include("html/403.html");
    }
} else {
    header("Location: login.php");
}
