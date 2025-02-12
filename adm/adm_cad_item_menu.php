<?php
session_start();

if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // ID do item de menu para edição
        $delete_id = isset($_GET['delete_id']) ? (int)$_GET['delete_id'] : 0; // ID do item de menu para exclusão
        $mensagem = '';
        $erro_banco = '';

        if ($delete_id > 0) {
            $query_delete = "DELETE FROM administracao.adm_item_menu WHERE id = $1";
            $result_delete = pg_query_params($conexao, $query_delete, [$delete_id]);
            $erro_banco = pg_last_error($conexao);

            if ($result_delete) {
                $mensagem = "Item de menu excluído com sucesso!";
            } else {
                $mensagem = "Erro ao excluir o item de menu.";
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fk_menu = !empty($_POST['fk_menu']) ? (int)$_POST['fk_menu'] : null;
            $descricao_item = htmlspecialchars($_POST['descricao_item']);
            $link_item = htmlspecialchars($_POST['link_item']);
            $ordem = (int)$_POST['ordem'];
            $fk_usuario_alteracao = $_SESSION['global_id_usuario'];

            if ($id > 0) {
                $query_update = "
                    UPDATE administracao.adm_item_menu
                    SET fk_menu = $1, descricao_item = $2, link_item = $3, ordem = $4, 
                        fk_usuario_alteracao = $5, data_alteracao = now()
                    WHERE id = $6
                ";
                $result_update = pg_query_params($conexao, $query_update, [$fk_menu, $descricao_item, $link_item, $ordem, $fk_usuario_alteracao, $id]);
                $erro_banco = pg_last_error($conexao);

                if ($result_update) {
                    $mensagem = "Item de menu atualizado com sucesso!";
                } else {
                    $mensagem = "Erro ao atualizar o item de menu.". pg_last_error($conexao);
                }
            } else {
                $query_insert = "
                    INSERT INTO administracao.adm_item_menu (fk_menu, descricao_item, link_item, ordem, fk_usuario_alteracao, data_alteracao)
                    VALUES ($1, $2, $3, $4, $5, now())
                ";
                $result_insert = pg_query_params($conexao, $query_insert, [$fk_menu, $descricao_item, $link_item, $ordem, $fk_usuario_alteracao]);
                $erro_banco = pg_last_error($conexao);

                if ($result_insert) {
                    $mensagem = "Item de menu cadastrado com sucesso!";
                } else {
                    $mensagem = "Erro ao cadastrar o item de menu.". pg_last_error($conexao);
                }
            }
        }

        if ($id > 0) {
            $query_item_menu = "SELECT id, fk_menu, descricao_item, link_item, ordem FROM administracao.adm_item_menu WHERE id = $1";
            $result_item_menu = pg_query_params($conexao, $query_item_menu, [$id]);
            $erro_banco = pg_last_error($conexao);

            if ($result_item_menu && pg_num_rows($result_item_menu) > 0) {
                $item_menu = pg_fetch_assoc($result_item_menu);
            } else {
                die("Item de menu não encontrado.");
            }
        } else {
            $query_itens_menu = "SELECT id, fk_menu, descricao_item, link_item, ordem FROM administracao.adm_item_menu ORDER BY fk_menu, ordem ASC";
            $result_itens_menu = pg_query($conexao, $query_itens_menu);
            $erro_banco = pg_last_error($conexao);

            if (!$result_itens_menu) {
                die("Erro ao carregar os itens de menu.");
            }
        }

        $query_menus = "SELECT id, descricao FROM administracao.adm_menu ORDER BY descricao ASC";
        $result_menus = pg_query($conexao, $query_menus);
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
    <title>Gerenciar Itens de Menu</title>
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
            max-width: 1200px;
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
            max-width: 1200px;
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

        <?php if (isset($_GET['cadastrar']) && $_GET['cadastrar'] === 'True'): ?>
            <div class="form-container">
                <div class="form-title">Adicionar Novo Item de Menu</div>
                <form method="POST">
                    <div class="mb-3">
                        <label for="fk_menu" class="form-label">Menu</label>
                        <select class="form-select" id="fk_menu" name="fk_menu">
                            <option value="">Nenhum Menu</option>
                            <?php foreach ($menus as $menu): ?>
                                <option value="<?= $menu['id'] ?>"><?= htmlspecialchars($menu['descricao']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="descricao_item" class="form-label">Descrição do Item</label>
                        <input type="text" class="form-control" id="descricao_item" name="descricao_item" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label for="link_item" class="form-label">Link do Item</label>
                        <input type="text" class="form-control" id="link_item" name="link_item" maxlength="255" required>
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
            <div class="form-container">
                <div class="form-title">Editar Item de Menu</div>
                <form method="POST">
                    <div class="mb-3">
                        <label for="fk_menu" class="form-label">Menu</label>
                        <select class="form-select" id="fk_menu" name="fk_menu">
                            <option value="">Nenhum Menu</option>
                            <?php foreach ($menus as $menu): ?>
                                <option value="<?= $menu['id'] ?>" <?= $item_menu['fk_menu'] == $menu['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($menu['descricao']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="descricao_item" class="form-label">Descrição do Item</label>
                        <input type="text" class="form-control" id="descricao_item" name="descricao_item" maxlength="255" value="<?= htmlspecialchars($item_menu['descricao_item']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="link_item" class="form-label">Link do Item</label>
                        <input type="text" class="form-control" id="link_item" name="link_item" maxlength="255" value="<?= htmlspecialchars($item_menu['link_item']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="ordem" class="form-label">Ordem</label>
                        <input type="number" class="form-control" id="ordem" name="ordem" min="0" value="<?= htmlspecialchars($item_menu['ordem']) ?>" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-submit btn-lg">Salvar</button>
                        <a href="?acao=<?= $acao ?>" class="btn btn-secondary btn-lg">Voltar</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="table-container">
                <div class="form-title">Itens de Menu</div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Menu</th>
                            <th>Descrição</th>
                            <th>Link</th>
                            <th>Ordem</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item_menu = pg_fetch_assoc($result_itens_menu)): ?>
                            <tr>
                                <td><?= htmlspecialchars($item_menu['id']) ?></td>
                                <td>
                                    <?php
                                    $menu_query = "SELECT descricao FROM administracao.adm_menu WHERE id = $1";
                                    $menu_result = pg_query_params($conexao, $menu_query, [$item_menu['fk_menu']]);
                                    echo $menu_result && pg_num_rows($menu_result) > 0 ? htmlspecialchars(pg_fetch_result($menu_result, 0, 0)) : 'Nenhum';
                                    ?>
                                </td>
                                <td><?= htmlspecialchars($item_menu['descricao_item']) ?></td>
                                <td><?= htmlspecialchars($item_menu['link_item']) ?></td>
                                <td><?= htmlspecialchars($item_menu['ordem']) ?></td>
                                <td>
                                    <a href="?acao=<?= $acao ?>&id=<?= $item_menu['id'] ?>" class="btn btn-sm btn-edit">Editar</a>
                                    <a href="?acao=<?= $acao ?>&delete_id=<?= $item_menu['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este item de menu?')">Excluir</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <a href="?acao=<?= $acao ?>&cadastrar=True" class="btn btn-submit btn-lg">Adicionar Novo Item de Menu</a>
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
