<?php
session_start();

if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        // Inicializa variáveis
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $delete_id = isset($_GET['delete_id']) ? (int)$_GET['delete_id'] : 0;
        $mensagem = '';

        // Exclusão de usuário
        if ($delete_id > 0) {
            // Identifica constraints que fazem referência à tabela `adm_usuario`
            $query_constraints = "
                SELECT conname AS constraint_name, conrelid::regclass AS table_name, a.attname AS column_name
                FROM pg_constraint c
                JOIN pg_attribute a ON a.attnum = ANY(c.conkey) AND a.attrelid = c.confrelid
                WHERE confrelid = 'administracao.adm_usuario'::regclass;
            ";
            $result_constraints = pg_query($conexao, $query_constraints);

            if ($result_constraints && pg_num_rows($result_constraints) > 0) {
                $has_related_data = false;

                // Verifica cada tabela relacionada
                while ($constraint = pg_fetch_assoc($result_constraints)) {
                    $table_name = $constraint['table_name'];
                    $column_name = $constraint['column_name'];

                    $query_check_related = "SELECT 1 FROM $table_name WHERE $column_name = $1 LIMIT 1";
                    $result_check_related = pg_query_params($conexao, $query_check_related, [$delete_id]);

                    if ($result_check_related && pg_num_rows($result_check_related) > 0) {
                        $has_related_data = true;
                        break;
                    }
                }

                if ($has_related_data) {
                    // Desativa o usuário em vez de excluí-lo
                    $fk_usuario_alteracao = $_SESSION['global_id_usuario']; // Usuário que realizou a alteração
                    $query_deactivate = "UPDATE administracao.adm_usuario SET ativo = false, data_desativacao = now(), fk_usuario_alteracao = $1 WHERE id = $2";
                    $result_deactivate = pg_query_params($conexao, $query_deactivate, [$fk_usuario_alteracao, $delete_id]);

                    if ($result_deactivate) {
                        $mensagem = "O usuário possui dados relacionados em outras tabelas e foi desativado.";
                    } else {
                        $mensagem = "Erro ao desativar o usuário.";
                    }
                } else {
                    // Exclui o usuário, pois não há dados relacionados
                    $query_delete = "DELETE FROM administracao.adm_usuario WHERE id = $1";
                    $result_delete = pg_query_params($conexao, $query_delete, [$delete_id]);
                    $erro_banco = pg_last_error($conexao);

                    if ($result_delete) {
                        $mensagem = "Usuário excluído com sucesso!";
                    } else {
                        $mensagem = "Erro ao excluir o usuário.";
                    }
                }
            } else {
                $mensagem = "Erro ao verificar as constraints do banco de dados.";
            }
        }

        // Se houver ID no GET, carrega os dados do usuário para edição
        if ($id > 0) {
            $query = "
                SELECT u.nome, u.sobre_nome, u.email, u.telefone, u.login, u.senha, u.ativo,
                       TO_CHAR(u.data_criacao, 'DD-MM-YYYY HH24:MI') AS data_criacao,
                       TO_CHAR(u.data_atualizacao, 'DD-MM-YYYY HH24:MI') AS data_atualizacao,
                       TO_CHAR(u.data_desativacao, 'DD-MM-YYYY HH24:MI') AS data_desativacao,
                       ua.login AS ultimo_alterador
                FROM administracao.adm_usuario u
                LEFT JOIN administracao.adm_usuario ua ON u.fk_usuario_alteracao = ua.id
                WHERE u.id = $1
            ";
            $result = pg_query_params($conexao, $query, [$id]);

            if (!$result || pg_num_rows($result) === 0) {
                die("Usuário não encontrado.");
            }

            $usuario = pg_fetch_assoc($result);

            // Carregar os perfis disponíveis
            $query_perfis = "SELECT id, descricao FROM administracao.adm_perfil ORDER BY descricao ASC";
            $result_perfis = pg_query($conexao, $query_perfis);

            if (!$result_perfis) {
                die("Erro ao carregar os perfis.");
            }
            $perfis = pg_fetch_all($result_perfis);

            // Obter o perfil atual do usuário
            $query_perfil_atual = "
                SELECT pf.id, pf.descricao
                FROM administracao.adm_usuario_perfil upf
                INNER JOIN administracao.adm_perfil pf ON pf.id = upf.fk_perfil
                WHERE upf.fk_usuario = $1
            ";
            $result_perfil_atual = pg_query_params($conexao, $query_perfil_atual, [$id]);

            $perfil_atual = pg_fetch_assoc($result_perfil_atual);

            // Verifica se o formulário de edição foi enviado
           // Verifica se o formulário de edição foi enviado
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nome = htmlspecialchars($_POST['nome']);
                $sobre_nome = htmlspecialchars($_POST['sobre_nome']);
                $email = htmlspecialchars($_POST['email']);
                $telefone = htmlspecialchars($_POST['telefone']);
                $login = htmlspecialchars($_POST['login']);
                $senha = !empty($_POST['senha']) ? htmlspecialchars($_POST['senha']) : $usuario['senha'];
                $ativo = isset($_POST['ativo']) ? 'true' : 'false';
                $fk_usuario_alteracao = $_SESSION['global_id_usuario'];
                $data_desativacao = $ativo === 'false' ? 'now()' : "'1900-01-01 23:23:23'";
                $perfil = (int)$_POST['perfil']; // Captura o ID do perfil selecionado

                // Atualiza os dados do usuário
                $query_update = "
                    UPDATE administracao.adm_usuario
                    SET nome = $1, sobre_nome = $2, email = $3, telefone = $4, 
                        login = $5, senha = $6, ativo = $7, 
                        data_desativacao = $8, data_atualizacao = now(), fk_usuario_alteracao = $9
                    WHERE id = $10
                ";
                $result_update = pg_query_params(
                    $conexao,
                    $query_update,
                    [$nome, $sobre_nome, $email, $telefone, $login, $senha, $ativo, $data_desativacao, $fk_usuario_alteracao, $id]
                );

                if ($result_update) {
                    
                    
                    $perfil = isset($_POST['perfil']) && (int)$_POST['perfil'] > 0 ? (int)$_POST['perfil'] : null;

                    // Verifica se o perfil já existe na tabela adm_usuario_perfil
                    $query_check_perfil = "SELECT 1 FROM administracao.adm_usuario_perfil WHERE fk_usuario = $1";
                    $result_check_perfil = pg_query_params($conexao, $query_check_perfil, [$id]);

                    if ($result_check_perfil && pg_num_rows($result_check_perfil) > 0) {
                        if ($perfil !== null) {
                            // Atualiza o perfil existente
                            $query_update_perfil = "
                                UPDATE administracao.adm_usuario_perfil 
                                SET fk_perfil = $1, fk_usuario_alteracao = $fk_usuario_alteracao, data_alteracao = now() 
                                WHERE fk_usuario = $2
                            ";
                            $result_update_perfil = pg_query_params($conexao, $query_update_perfil, [$perfil, $id]);
                            $erro_banco = pg_last_error($conexao);
                        } else {
                            // Remove o perfil associado
                            $query_delete_perfil = "DELETE FROM administracao.adm_usuario_perfil WHERE fk_usuario = $1";
                            $result_delete_perfil = pg_query_params($conexao, $query_delete_perfil, [$id]);
                            $erro_banco = pg_last_error($conexao);
                        }
                    } elseif ($perfil !== null) {
                        // Insere um novo perfil, se não existir e um perfil for selecionado
                        $query_insert_perfil = "
                            INSERT INTO administracao.adm_usuario_perfil (fk_usuario, fk_perfil, fk_usuario_alteracao, data_alteracao) 
                            VALUES ($1, $2, $fk_usuario_alteracao, now())
                        ";
                        $result_insert_perfil = pg_query_params($conexao, $query_insert_perfil, [$id, $perfil]);
                        $erro_banco = pg_last_error($conexao);
                    }
                    // Atualiza o perfil atual para refletir a mudança no formulário
                    $query_perfil_atual = "
                        SELECT pf.id, pf.descricao
                        FROM administracao.adm_usuario_perfil upf
                        INNER JOIN administracao.adm_perfil pf ON pf.id = upf.fk_perfil
                        WHERE upf.fk_usuario = $1
                    ";
                    $result_perfil_atual = pg_query_params($conexao, $query_perfil_atual, [$id]);
                    $perfil_atual = pg_fetch_assoc($result_perfil_atual); // Atualiza o perfil atual no PHP
                
                    $mensagem = "Usuário e perfil atualizados com sucesso!";
                } else {
                    $mensagem = "Erro ao atualizar o usuário.";
                    $erro_banco = pg_last_error($conexao);
                }
                
            }

        } else {
            // Carrega a lista de usuários
            $query = "
                SELECT 
                    u.id, 
                    u.nome, 
                    u.sobre_nome, 
                    u.email, 
                    u.telefone, 
                    u.ativo,
                    pf.descricao AS perfil
                FROM 
                    administracao.adm_usuario u
                LEFT JOIN 
                    administracao.adm_usuario_perfil upf ON u.id = upf.fk_usuario
                LEFT JOIN 
                    administracao.adm_perfil pf ON upf.fk_perfil = pf.id
                ORDER BY 
                    u.nome ASC
            ";

            $result = pg_query($conexao, $query);

            if (!$result) {
                die("Erro ao carregar a lista de usuários.");
            }
        }
        ?>

        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Listar, Editar e Excluir Usuários</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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

                /* Container do formulário de edição */
                .form-container {
                    max-width: 1000px;
                    margin: 40px auto;
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                    margin-bottom: 20px;
                    margin-top: 10px;
                }

                /* Container da tabela de listagem */
                .table-container {
                    max-width: 90%; /* Expande o espaço horizontal */
                    margin: 40px auto;
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                    margin-bottom: 20px;
                }

                .table-listing {
                    width: 100%; /* Faz a tabela ocupar todo o espaço disponível */
                    border-collapse: collapse;
                    margin-top: 20px;
                }

                th, td {
                    padding: 12px; /* Adiciona espaço interno nas células */
                    text-align: left;
                    border: 1px solid #ddd; /* Bordas suaves */
                    vertical-align: middle; /* Centraliza verticalmente o conteúdo */
                }

                th {
                    background-color: #f8f9fa; /* Fundo claro para cabeçalho */
                    font-weight: bold;
                }

                td {
                    word-wrap: break-word; /* Quebra texto longo */
                }

                .btn-edit {
                    background-color: #28a745;
                    color: white;
                }

                .btn-edit:hover {
                    background-color: #218838;
                    color: white;
                }

                .btn-delete {
                    background-color: #dc3545;
                    color: white;
                }

                .btn-delete:hover {
                    background-color: #c82333;
                    color: white;
                }

                .form-title {
                    text-align: center;
                    margin-bottom: 20px;
                    font-weight: bold;
                    font-size: 24px;
                    color: #4a4a4a;
                }

                .alert {
                    background-color: #d9edf7; /* Fundo azul claro */
                    color: #31708f; /* Texto azul escuro */
                    border: 1px solid #bce8f1; /* Borda azul clara */
                    border-radius: 4px;
                    padding: 15px; /* Espaçamento interno */
                    max-width: 1000px; /* Limita a largura do alerta */
                    margin: 10px auto; /* Centraliza horizontalmente e adiciona margem superior/inferior */
                    text-align: center; /* Centraliza o texto */
                }

                

                /* Responsividade para a tabela */
                @media (max-width: 768px) {
                    .table-container {
                        max-width: 100%;
                        padding: 10px;
                    }

                    .table-listing {
                        font-size: 14px;
                    }

                    th, td {
                        padding: 8px;
                    }
                }
            </style>
           <script>
                // Alternar visibilidade da senha
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

                // Validação de senha
                function validateForm(event) {
                    const senha = document.getElementById('senha').value;
                    const confirmSenha = document.getElementById('confirm_senha').value;

                    if (senha !== confirmSenha) {
                        event.preventDefault();
                        alert("As senhas não coincidem. Por favor, corrija.");
                    }
                }
            </script>
        </head>
        <body>
            <div class="container">
            <?php if (!empty($mensagem)): ?>
                    <div class="alert alert-info">
                        <?= htmlspecialchars($mensagem) ?>
                        <?php if (isset($erro_banco)): ?>
                            <br>
                            <small class="text-danger"><?= htmlspecialchars($erro_banco) ?></small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
              
            <?php if ($id > 0): ?>
            <!-- Formulário de Edição -->
            <div class="form-container">
            <div class="form-title">Editar Usuário</div>
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
                        <input type="email" class="form-control" id="email" name="email" maxlength="250" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" maxlength="15" value="<?= htmlspecialchars($usuario['telefone']) ?>" required>
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
                        <small class="form-text text-muted">Preencha somente se desejar alterar a senha.</small>
                    </div>
                    <div class="col-md-6 mb-3 password-group">
                        <label for="confirm_senha" class="form-label">Confirme a Senha</label>
                        <input type="password" class="form-control" id="confirm_senha" name="confirm_senha" maxlength="20">
                        <i class="bi bi-eye toggle-password" id="toggleConfirmSenha" onclick="togglePasswordVisibility('confirm_senha', 'toggleConfirmSenha')"></i>
                    </div>                                        
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="perfil" class="form-label">Perfil</label>
                        <select class="form-select" id="perfil" name="perfil" >
                            <!-- Opção para nenhum perfil -->
                            <option value="" <?= !$perfil_atual ? 'selected' : '' ?>>Sem Perfil</option>
                            <?php foreach ($perfis as $perfil): ?>
                                <option value="<?= $perfil['id'] ?>" <?= $perfil_atual && $perfil_atual['id'] == $perfil['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($perfil['descricao']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="ativo" class="form-label">Ativo</label>
                        <input type="checkbox" id="ativo" name="ativo" <?= $usuario['ativo'] === 't' ? 'checked' : '' ?>>
                    </div>
                </div>
                <div class="action-buttons">
                    <button type="submit" class="btn btn-edit btn-lg">Salvar</button>
                    <a href="index.php?acao=list_usuario.php" class="btn btn-secondary btn-back btn-lg">Voltar</a>
                </div>
            </form>
                    </div>
                <?php else: ?>
            <!-- Lista de Usuários -->
            <div class="table-container">
                <div class="form-title">Usuários</div>
                <table class="table-listing">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Sobrenome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Ativo</th>
                            <th>Perfil</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($usuario = pg_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($usuario['id']) ?></td>
                                <td><?= htmlspecialchars($usuario['nome']) ?></td>
                                <td><?= htmlspecialchars($usuario['sobre_nome']) ?></td>
                                <td><?= htmlspecialchars($usuario['email']) ?></td>
                                <td><?= htmlspecialchars($usuario['telefone']) ?></td>
                                <td><?= ($usuario['ativo'] === 't' || $usuario['ativo'] === true || $usuario['ativo'] === 1) ? 'Sim' : 'Não' ?></td>
                                <td><?= htmlspecialchars($usuario['perfil'] ?? 'Sem perfil') ?></td>
                                <td>
                                    <a href="index.php?acao=<?= $acao ?>&id=<?= $usuario['id'] ?>" class="btn btn-sm btn-edit">Editar</a>
                                    <a href="index.php?acao=<?= $acao ?>&delete_id=<?= $usuario['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
                <?php endif; ?>
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
