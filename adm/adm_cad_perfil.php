<?php
session_start();

if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // ID do perfil para edição
        $delete_id = isset($_GET['delete_id']) ? (int)$_GET['delete_id'] : 0; // ID do perfil para exclusão
        $mensagem = '';

        if ($delete_id > 0) {
            // Verifica se há referências ao perfil em outras tabelas
            $query_constraints = "
                SELECT conname AS constraint_name, conrelid::regclass AS table_name, a.attname AS column_name
                FROM pg_constraint c
                JOIN pg_attribute a ON a.attnum = ANY(c.conkey) AND a.attrelid = c.confrelid
                WHERE confrelid = 'administracao.adm_perfil'::regclass;
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
                    $mensagem = "O perfil não pode ser excluído porque está associado a outras tabelas. Por favor, desassocie-o primeiro.";
                } else {
                    // Exclui o perfil, pois não há dados relacionados
                    $query_delete = "DELETE FROM administracao.adm_perfil WHERE id = $1";
                    $result_delete = pg_query_params($conexao, $query_delete, [$delete_id]);

                    if ($result_delete) {
                        $mensagem = "Perfil excluído com sucesso!";
                    } else {
                        $mensagem = "Erro ao excluir o perfil: " . pg_last_error($conexao);
                    }
                }
            } else {
                $mensagem = "Erro ao verificar referências no banco de dados.";
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = htmlspecialchars($_POST['nome']);
            $descricao = htmlspecialchars($_POST['descricao']);
            $fk_usuario_alteracao = $_SESSION['global_id_usuario'];

            if ($id > 0) {
                // Atualizar o perfil
                $query_update = "
                    UPDATE administracao.adm_perfil
                    SET nome = $1, descricao = $2, fk_usuario_alteracao = $3, data_alteracao = now()
                    WHERE id = $4
                ";
                $result_update = pg_query_params($conexao, $query_update, [$nome, $descricao, $fk_usuario_alteracao, $id]);

                if ($result_update) {
                    $mensagem = "Perfil atualizado com sucesso!";
                } else {
                    $mensagem = "Erro ao atualizar o perfil: " . pg_last_error($conexao);
                }
            } else {
                // Inserir novo perfil
                $query_insert = "
                    INSERT INTO administracao.adm_perfil (nome, descricao, fk_usuario_alteracao, data_alteracao)
                    VALUES ($1, $2, $3, now())
                ";
                $result_insert = pg_query_params($conexao, $query_insert, [$nome, $descricao, $fk_usuario_alteracao]);

                if ($result_insert) {
                    $mensagem = "Perfil cadastrado com sucesso!";
                } else {
                    $mensagem = "Erro ao cadastrar o perfil: " . pg_last_error($conexao);
                }
            }
        }

        if ($id > 0) {
            // Carregar os dados do perfil para edição
            $query_perfil = "SELECT id, nome, descricao FROM administracao.adm_perfil WHERE id = $1";
            $result_perfil = pg_query_params($conexao, $query_perfil, [$id]);

            if ($result_perfil && pg_num_rows($result_perfil) > 0) {
                $perfil = pg_fetch_assoc($result_perfil);
            } else {
                die("Perfil não encontrado.");
            }
        } else {
            // Carregar todos os perfis para listagem
            $query_perfis = "SELECT id, nome, descricao FROM administracao.adm_perfil ORDER BY nome ASC";
            $result_perfis = pg_query($conexao, $query_perfis);

            if (!$result_perfis) {
                die("Erro ao carregar os perfis.");
            }
        }
        ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Perfis</title>
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
    .form-container, .table-container {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 40px auto;
        max-width: 800px;
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
        color: white;
        transition: background-color 0.3s ease; /* Animação para suavizar a transição */
    }
    .btn-submit:hover {
        background-color: #45a049;
        color: white; /* Mantém o texto branco */
    }
    .btn-edit {
        background-color: #28a745;
        color: white;
        transition: background-color 0.3s ease;
    }
    .btn-edit:hover {
        background-color: #218838;
        color: white; /* Mantém o texto branco */
    }
    .alert {
        background-color: #d9edf7; /* Fundo azul claro */
        color: #31708f; /* Texto azul escuro */
        border: 1px solid #bce8f1; /* Borda azul clara */
        border-radius: 4px;
        padding: 15px; /* Espaçamento interno */
        max-width: 800px; /* Limita a largura do alerta */
        margin: 10px auto; /* Centraliza horizontalmente e adiciona margem superior/inferior */
        text-align: center; /* Centraliza o texto */
    }
</style>

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


        <?php if (isset($_GET['cadastrar']) && $_GET['cadastrar'] === 'True'): ?>
            <!-- Formulário de Cadastro -->
            <div class="form-container">
                <div class="form-title">Adicionar Novo Perfil</div>
                <form method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" maxlength="50" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" maxlength="250" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-submit btn-lg">Salvar</button>
                        <a href="?acao=<?= $acao ?>" class="btn btn-secondary btn-lg">Voltar</a>
                    </div>
                </form>
            </div>
        <?php elseif (isset($_GET['id']) && is_numeric($_GET['id'])): ?>
            <!-- Formulário de Edição -->
            <div class="form-container">
                <div class="form-title">Editar Perfil</div>
                <form method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" maxlength="50" value="<?= htmlspecialchars($perfil['nome']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" maxlength="250" value="<?= htmlspecialchars($perfil['descricao']) ?>" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-submit btn-lg">Salvar</button>
                        <a href="?acao=<?= $acao ?>" class="btn btn-secondary btn-lg">Voltar</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <!-- Lista de Perfis -->
            <div class="table-container">
                <div class="form-title">Perfis</div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($perfil = pg_fetch_assoc($result_perfis)): ?>
                            <tr>
                                <td><?= htmlspecialchars($perfil['id']) ?></td>
                                <td><?= htmlspecialchars($perfil['nome']) ?></td>
                                <td><?= htmlspecialchars($perfil['descricao']) ?></td>
                                <td>
                                    <a href="?acao=<?= $acao ?>&id=<?= $perfil['id'] ?>" class="btn btn-sm btn-edit">Editar</a>
                                    <a href="?acao=<?= $acao ?>&delete_id=<?= $perfil['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este perfil?')">Excluir</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <a href="?acao=<?= $acao ?>&cadastrar=True" class="btn btn-submit btn-lg">Adicionar Novo Perfil</a>
                </div>
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
?>
