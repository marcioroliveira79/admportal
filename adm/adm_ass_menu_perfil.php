<?php
session_start();

if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        $id_perfil = isset($_GET['id_perfil']) ? (int)$_GET['id_perfil'] : 0; // ID do perfil selecionado
        $delete_id = isset($_GET['delete_id']) ? (int)$_GET['delete_id'] : 0; // ID da associação para exclusão
        $mensagem = '';
        $erro_banco = '';

        if ($delete_id > 0) {
            // Excluir associação
            $query_delete = "DELETE FROM administracao.adm_perfil_menu WHERE id = $1";
            $result_delete = pg_query_params($conexao, $query_delete, [$delete_id]);
            $erro_banco = pg_last_error($conexao);

            if ($result_delete) {
                $mensagem = "Associação excluída com sucesso!";
            } else {
                $mensagem = "Erro ao excluir a associação.";
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validação do campo ordem obrigatório
            if (empty($_POST['ordem'])) {
                $mensagem = "O campo Ordem é obrigatório.";
            } else {
                $fk_menu = (int)$_POST['fk_menu'];
                $ordem = (int)$_POST['ordem'];
                $fk_usuario_alteracao = $_SESSION['global_id_usuario'];

                if ($id_perfil > 0) {
                    // Inserir nova associação
                    $query_insert = "
                        INSERT INTO administracao.adm_perfil_menu (fk_perfil, fk_menu, ordem, fk_usuario_alteracao, data_alteracao)
                        VALUES ($1, $2, $3, $4, now())
                    ";
                    $result_insert = pg_query_params($conexao, $query_insert, [$id_perfil, $fk_menu, $ordem, $fk_usuario_alteracao]);
                    $erro_banco = pg_last_error($conexao);

                    if ($result_insert) {
                        $mensagem = "Associação adicionada com sucesso!";
                    } else {
                        $mensagem = "Erro ao adicionar a associação.";
                    }
                }
            }
        }

        if ($id_perfil > 0) {
            // Carregar as associações atuais do perfil
            $query_associacoes = "
                SELECT pm.id, m.descricao AS menu_descricao, pm.ordem
                FROM administracao.adm_perfil_menu pm
                INNER JOIN administracao.adm_menu m ON m.id = pm.fk_menu
                WHERE pm.fk_perfil = $1
                ORDER BY pm.ordem ASC
            ";
            $result_associacoes = pg_query_params($conexao, $query_associacoes, [$id_perfil]);
            $erro_banco = pg_last_error($conexao);

            if (!$result_associacoes) {
                die("Erro ao carregar as associações.");
            }

            // Carregar dados do perfil
            $query_perfil = "SELECT id, descricao FROM administracao.adm_perfil WHERE id = $1";
            $result_perfil = pg_query_params($conexao, $query_perfil, [$id_perfil]);
            $erro_banco = pg_last_error($conexao);

            if ($result_perfil && pg_num_rows($result_perfil) > 0) {
                $perfil = pg_fetch_assoc($result_perfil);
            } else {
                die("Perfil não encontrado.");
            }
        } else {
            // Carregar todos os perfis
            $query_perfis = "SELECT id, descricao FROM administracao.adm_perfil ORDER BY descricao ASC";
            $result_perfis = pg_query($conexao, $query_perfis);
            $erro_banco = pg_last_error($conexao);

            if (!$result_perfis) {
                die("Erro ao carregar os perfis.");
            }
        }

        // Carregar menus
        if ($id_perfil > 0) {
            $query_menus = "SELECT id, descricao FROM administracao.adm_menu 
                            WHERE id NOT IN (
                                SELECT fk_menu FROM administracao.adm_perfil_menu WHERE fk_perfil = $1
                            )
                            ORDER BY descricao ASC";
            $result_menus = pg_query_params($conexao, $query_menus, [$id_perfil]);
        } else {
            $query_menus = "SELECT id, descricao FROM administracao.adm_menu ORDER BY descricao ASC";
            $result_menus = pg_query($conexao, $query_menus);
        }
        
        $erro_banco = pg_last_error($conexao);

        if (!$result_menus) {
            die("Erro ao carregar os menus.");
        }

        $menus = pg_fetch_all($result_menus);
        ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Associações de Menu</title>
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
        }
        .btn-submit:hover {
            background-color: #45a049;
            color: white;
        }
        .btn-edit {
            background-color: #28a745;
            color: white;
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
                <?php if (!empty($erro_banco)): ?>
                    <br><small class="text-danger"><?= htmlspecialchars($erro_banco) ?></small>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($id_perfil > 0): ?>
            <!-- Formulário para adicionar associação -->
            <div class="form-container">
                <div class="form-title"><?= htmlspecialchars($perfil['descricao']) ?></div>
                <form method="POST">
                    <div class="mb-3">
                        <label for="fk_menu" class="form-label">Menu</label>
                        <select class="form-select" id="fk_menu" name="fk_menu" required>
                            <?php foreach ($menus as $menu): ?>
                                <option value="<?= $menu['id'] ?>"><?= htmlspecialchars($menu['descricao']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ordem" class="form-label">Ordem</label>
                        <input type="number" class="form-control" id="ordem" name="ordem" min="0" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-submit btn-lg">Salvar</button>
                        <a href="?acao=<?= $acao ?>" class="btn btn-secondary btn-lg">Voltar</a>
                    </div>
                </form>
            </div>

            <!-- Lista de Associações -->
            <div class="table-container">
                <div class="form-title">Associações do Perfil</div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Ordem</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($associacao = pg_fetch_assoc($result_associacoes)): ?>
                            <tr>
                                <td><?= htmlspecialchars($associacao['menu_descricao']) ?></td>
                                <td><?= htmlspecialchars($associacao['ordem']) ?></td>
                                <td>
                                    <a href="?acao=<?= $acao ?>&delete_id=<?= $associacao['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta associação?')">Excluir</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- Lista de Perfis -->
            <div class="table-container">
                <div class="form-title">Selecione um Perfil</div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($perfil = pg_fetch_assoc($result_perfis)): ?>
                            <tr>
                                <td><?= htmlspecialchars($perfil['id']) ?></td>
                                <td><?= htmlspecialchars($perfil['descricao']) ?></td>
                                <td>
                                    <a href="?acao=<?= $acao ?>&id_perfil=<?= $perfil['id'] ?>" class="btn btn-sm btn-edit">Gerenciar Menus</a>
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
?>
