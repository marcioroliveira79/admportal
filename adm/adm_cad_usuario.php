<?php
session_start();

if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = htmlspecialchars($_POST['nome']);
            $sobre_nome = htmlspecialchars($_POST['sobre_nome']);
            $email = htmlspecialchars($_POST['email']);
            $telefone = htmlspecialchars($_POST['telefone']);
            $login = htmlspecialchars($_POST['login']);
            $senha = htmlspecialchars($_POST['senha']);
            $ativo = isset($_POST['ativo']) ? 'true' : 'false';
            $fk_usuario_criacao = $_SESSION['global_id_usuario'];

            if (strlen($senha) < 6) {
                $mensagem = "A senha deve ter pelo menos 6 caracteres.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mensagem = "O e-mail informado não é válido.";
            } else {
                $query_verifica_login = "SELECT id FROM administracao.adm_usuario WHERE login = $1";
                $result_verifica_login = pg_query_params($conexao, $query_verifica_login, [$login]);
                $query_verifica_email = "SELECT id FROM administracao.adm_usuario WHERE email = $1";
                $result_verifica_email = pg_query_params($conexao, $query_verifica_email, [$email]);

                if ($result_verifica_login && pg_num_rows($result_verifica_login) > 0) {
                    $mensagem = "O login já está em uso. Por favor, escolha outro.";
                } elseif ($result_verifica_email && pg_num_rows($result_verifica_email) > 0) {
                    $mensagem = "O e-mail já está em uso. Por favor, escolha outro.";
                } else {
                    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
                    $query_insert = "
                        INSERT INTO administracao.adm_usuario (
                            nome, sobre_nome, email, telefone, login, senha, ativo, data_criacao, fk_usuario_criacao
                        ) VALUES ($1, $2, $3, $4, $5, $6, $7, now(), $8)
                    ";
                    $result_insert = pg_query_params(
                        $conexao,
                        $query_insert,
                        [$nome, $sobre_nome, $email, $telefone, $login, $senha_criptografada, $ativo, $fk_usuario_criacao]
                    );

                    if ($result_insert) {
                        $mensagem = "Usuário adicionado com sucesso!";
                        $nome = $sobre_nome = $email = $telefone = $login = $senha = '';
                        $ativo = true;
                    } else {
                        $mensagem = "Erro ao adicionar o usuário. Por favor, tente novamente.";
                        $erro_banco = pg_last_error($conexao);
                    }
                }
            }
        }
        ?>

        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Adicionar Usuário</title>
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
            </style>
            <script>
                function formatPhone(input) {
                    let value = input.value.replace(/\D/g, ''); // Remove caracteres não numéricos

                    if (value.length > 10) { // Celular com 11 dígitos
                        input.value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7, 11)}`;
                    } else if (value.length > 6) { // Telefone fixo com 10 dígitos
                        input.value = `(${value.slice(0, 2)}) ${value.slice(2, 6)}-${value.slice(6, 10)}`;
                    } else if (value.length > 2) { // Somente o DDD
                        input.value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
                    } else {
                        input.value = value;
                    }
                }
            </script>
        </head>
        <body>
            

        <div class="container">
                <div class="form-container">
                    <div class="form-title">Cadastrar Usuário</div>
                    <?php if (isset($mensagem)): ?>
                        <div class="alert alert-info text-center">
                            <?= htmlspecialchars($mensagem) ?>
                            <?php if (isset($erro_banco)): ?>
                                <br><small class="text-danger"><?= htmlspecialchars($erro_banco) ?></small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>


                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" maxlength="50" value="<?= htmlspecialchars($nome ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sobre_nome" class="form-label">Sobrenome</label>
                                <input type="text" class="form-control" id="sobre_nome" name="sobre_nome" maxlength="150" value="<?= htmlspecialchars($sobre_nome ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" maxlength="250" value="<?= htmlspecialchars($email ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" maxlength="15" value="<?= htmlspecialchars($telefone ?? '') ?>" oninput="formatPhone(this)" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="login" class="form-label">Login</label>
                                <input type="text" class="form-control" id="login" name="login" maxlength="250" value="<?= htmlspecialchars($login ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="text" class="form-control" id="senha" name="senha" maxlength="20" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="ativo" class="form-label">Ativo</label>
                                <input type="checkbox" id="ativo" name="ativo" <?= isset($ativo) && $ativo === true ? 'checked' : '' ?>>
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
