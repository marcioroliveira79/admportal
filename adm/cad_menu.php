<?php
session_start();

if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // ID do menu para edição
        $delete_id = isset($_GET['delete_id']) ? (int)$_GET['delete_id'] : 0; // ID do menu para exclusão
        $mensagem = '';

        if ($delete_id > 0) {
            // Verifica se há referências ao menu em outras tabelas
            $query_constraints = "
                SELECT conname AS constraint_name, conrelid::regclass AS table_name, a.attname AS column_name
                FROM pg_constraint c
                JOIN pg_attribute a ON a.attnum = ANY(c.conkey) AND a.attrelid = c.confrelid
                WHERE confrelid = 'administracao.adm_menu'::regclass;
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
                    $mensagem = "O menu não pode ser excluído porque está associado a outras tabelas. Por favor, desassocie-o primeiro.";
                } else {
                    // Exclui o menu, pois não há dados relacionados
                    $query_delete = "DELETE FROM administracao.adm_menu WHERE id = $1";
                    $result_delete = pg_query_params($conexao, $query_delete, [$delete_id]);

                    if ($result_delete) {
                        $mensagem = "Menu excluído com sucesso!";
                    } else {
                        $mensagem = "Erro ao excluir o menu: " . pg_last_error($conexao);
                    }
                }
            } else {
                $mensagem = "Erro ao verificar referências no banco de dados.";
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descricao = htmlspecialchars($_POST['descricao']);
            $ajuda = htmlspecialchars($_POST['ajuda']);
            $ordem = (int)$_POST['ordem'];
            $fk_usuario_alteracao = $_SESSION['global_id_usuario'];

            if ($id > 0) {
                // Atualizar o menu
                $query_update = "
                    UPDATE administracao.adm_menu
                    SET descricao = $1, ajuda = $2, ordem = $3, fk_usuario_alteracao = $4, data_alteracao = now()
                    WHERE id = $5
                ";
                $result_update = pg_query_params($conexao, $query_update, [$descricao, $ajuda, $ordem, $fk_usuario_alteracao, $id]);

                if ($result_update) {
                    $mensagem = "Menu atualizado com sucesso!";
                } else {
                    $mensagem = "Erro ao atualizar o menu: " . pg_last_error($conexao);
                }
            } else {
                // Inserir novo menu
                $query_insert = "
                    INSERT INTO administracao.adm_menu (descricao, ajuda, ordem, fk_usuario_alteracao, data_alteracao)
                    VALUES ($1, $2, $3, $4, now())
                ";
                $result_insert = pg_query_params($conexao, $query_insert, [$descricao, $ajuda, $ordem, $fk_usuario_alteracao]);

                if ($result_insert) {
                    $mensagem = "Menu cadastrado com sucesso!";
                } else {
                    $mensagem = "Erro ao cadastrar o menu: " . pg_last_error($conexao);
                }
            }
        }

        if ($id > 0) {
            // Carregar os dados do menu para edição
            $query_menu = "SELECT id, descricao, ajuda, ordem FROM administracao.adm_menu WHERE id = $1";
            $result_menu = pg_query_params($conexao, $query_menu, [$id]);

            if ($result_menu && pg_num_rows($result_menu) > 0) {
                $menu = pg_fetch_assoc($result_menu);
            } else {
                die("Menu não encontrado.");
            }
        } else {
            // Carregar todos os menus para listagem
            $query_menus = "SELECT id, descricao, ajuda, ordem FROM administracao.adm_menu ORDER BY ordem ASC";
            $result_menus = pg_query($conexao, $query_menus);

            if (!$result_menus) {
                die("Erro ao carregar os menus.");
            }
        }
        ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Menus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
            max-width: 1000px;
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
            transition: background-color 0.3s ease;
        }
        .btn-submit:hover {
            background-color: #45a049;
            color: white;
        }
        .btn-edit {
            background-color: #28a745;
            color: white;
            transition: background-color 0.3s ease;
        }
        .btn-edit:hover {
            background-color: #218838;
            color: white;
        }
        .alert {
            background-color: #d9edf7;
            color: #31708f;
            border: 1px solid #bce8f1;
            border-radius: 4px;
            padding: 15px;
            max-width: 800px;
            margin: 10px auto;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">

        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['cadastrar']) && $_GET['cadastrar'] === 'True'): ?>
            <!-- Formulário de Cadastro -->
            <div class="form-container">
                <div class="form-title">Adicionar Novo Menu</div>
                <form method="POST">
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" maxlength="50" required>
                    </div>
                    <div class="mb-3">
                        <label for="ajuda" class="form-label">Ajuda</label>
                        <input type="text" class="form-control" id="ajuda" name="ajuda" maxlength="150" required>
                    </div>
                    <div class="mb-3">
                        <label for="ordem" class="form-label">Ordem</label>
                        <input type="number" class="form-control" id="ordem" name="ordem" min="0" value="0" required>
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
                <div class="form-title">Editar Menu</div>
                <form method="POST">
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" maxlength="50" value="<?= htmlspecialchars($menu['descricao']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="ajuda" class="form-label">Ajuda</label>
                        <input type="text" class="form-control" id="ajuda" name="ajuda" maxlength="150" value="<?= htmlspecialchars($menu['ajuda']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="ordem" class="form-label">Ordem</label>
                        <input type="number" class="form-control" id="ordem" name="ordem" min="0" value="<?= htmlspecialchars($menu['ordem']) ?>" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-submit btn-lg">Salvar</button>
                        <a href="?acao=<?= $acao ?>" class="btn btn-secondary btn-lg">Voltar</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <!-- Lista de Menus -->
            <div class="table-container">
                <div class="form-title">Menus</div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descrição</th>
                            <th>Ajuda</th>
                            <th>Ordem</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($menu = pg_fetch_assoc($result_menus)): ?>
                            <tr>
                                <td><?= htmlspecialchars($menu['id']) ?></td>
                                <td><?= htmlspecialchars($menu['descricao']) ?></td>
                                <td><?= htmlspecialchars($menu['ajuda']) ?></td>
                                <td><?= htmlspecialchars($menu['ordem']) ?></td>
                                <td>
                                    <a href="?acao=<?= $acao ?>&id=<?= $menu['id'] ?>" class="btn btn-sm btn-edit">Editar</a>
                                    <a href="?acao=<?= $acao ?>&delete_id=<?= $menu['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este menu?')">Excluir</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <a href="?acao=<?= $acao ?>&cadastrar=True" class="btn btn-submit btn-lg">Adicionar Novo Menu</a>
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
