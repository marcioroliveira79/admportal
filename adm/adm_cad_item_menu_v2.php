<?php
session_start();

if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        // Parâmetros para edição/exclusão
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Para edição
        $delete_id = isset($_GET['delete_id']) ? (int)$_GET['delete_id'] : 0; // Para exclusão
        $mensagem = '';
        $erro_banco = '';

        // Exclusão de item de menu
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

        // Processamento de formulário (inserção/atualização)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fk_menu = !empty($_POST['fk_menu']) ? (int)$_POST['fk_menu'] : null;
            $descricao_item = htmlspecialchars($_POST['descricao_item']);
            $link_item = htmlspecialchars($_POST['link_item']);
            $ordem = (int)$_POST['ordem'];
            $fk_usuario_alteracao = $_SESSION['global_id_usuario'];

            // Se for edição, atualiza; se não, insere
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
                    $mensagem = "Erro ao atualizar o item de menu. " . pg_last_error($conexao);
                }
                $item_id = $id;
            } else {
                // Inserindo e recuperando o ID inserido com RETURNING
                $query_insert = "
                    INSERT INTO administracao.adm_item_menu (fk_menu, descricao_item, link_item, ordem, fk_usuario_alteracao, data_alteracao)
                    VALUES ($1, $2, $3, $4, $5, now())
                    RETURNING id
                ";
                $result_insert = pg_query_params($conexao, $query_insert, [$fk_menu, $descricao_item, $link_item, $ordem, $fk_usuario_alteracao]);
                $erro_banco = pg_last_error($conexao);

                if ($result_insert) {
                    $item_id = pg_fetch_result($result_insert, 0, 0);
                    $mensagem = "Item de menu cadastrado com sucesso!";
                } else {
                    $mensagem = "Erro ao cadastrar o item de menu. " . pg_last_error($conexao);
                }
            }

            // Processamento de parâmetros (action/value)
            // Em modo de edição, remove sempre os parâmetros existentes para o item
            if ($id > 0 || !empty($result_insert)) {
                $query_delete_params = "DELETE FROM administracao.adm_item_menu_action WHERE fk_item_menu = $1";
                pg_query_params($conexao, $query_delete_params, [$item_id]);
            }

            // Insere os parâmetros enviados, se houver
            if (isset($_POST['action_item']) && isset($_POST['value_item'])) {
                for ($i = 0; $i < count($_POST['action_item']); $i++) {
                    $action_item = trim($_POST['action_item'][$i]);
                    $value_item = trim($_POST['value_item'][$i]);
                    if ($action_item !== '' && $value_item !== '') {
                        $query_param = "
                            INSERT INTO administracao.adm_item_menu_action 
                            (fk_item_menu, action_item, value_item, fk_usuario_alteracao, data_alteracao)
                            VALUES ($1, $2, $3, $4, now())
                        ";
                        pg_query_params($conexao, $query_param, [$item_id, $action_item, $value_item, $fk_usuario_alteracao]);
                    }
                }
            }
        }

        // Se for edição, carrega os dados do item e seus parâmetros
        if ($id > 0) {
            $query_item_menu = "SELECT id, fk_menu, descricao_item, link_item, ordem FROM administracao.adm_item_menu WHERE id = $1";
            $result_item_menu = pg_query_params($conexao, $query_item_menu, [$id]);
            $erro_banco = pg_last_error($conexao);
            if ($result_item_menu && pg_num_rows($result_item_menu) > 0) {
                $item_menu = pg_fetch_assoc($result_item_menu);
            } else {
                die("Item de menu não encontrado.");
            }
            // Carrega os parâmetros existentes para o item
            $query_params = "SELECT action_item, value_item FROM administracao.adm_item_menu_action WHERE fk_item_menu = $1";
            $result_params = pg_query_params($conexao, $query_params, [$id]);
            $existing_parameters = ($result_params && pg_num_rows($result_params) > 0) ? pg_fetch_all($result_params) : [];
        }

        // Carrega os menus para o combo
        $query_menus = "SELECT id, descricao FROM administracao.adm_menu ORDER BY descricao ASC";
        $result_menus = pg_query($conexao, $query_menus);
        $erro_banco = pg_last_error($conexao);
        if (!$result_menus) {
            die("Erro ao carregar os menus.");
        }
        $menus = pg_fetch_all($result_menus);

        // FILTRO e PAGINAÇÃO PARA A LISTAGEM
        $menu_filter = isset($_GET['menu_filter']) ? (int)$_GET['menu_filter'] : 0;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $where_clause = "";
        $params = [];
        if ($menu_filter > 0) {
            $where_clause = "WHERE fk_menu = $1";
            $params[] = $menu_filter;
        }

        $query_count = "SELECT COUNT(*) FROM administracao.adm_item_menu " . $where_clause;
        $result_count = pg_query_params($conexao, $query_count, $params);
        if (!$result_count) {
            die("Erro ao contar os itens de menu.");
        }
        $total_items = (int) pg_fetch_result($result_count, 0, 0);
        $total_pages = ceil($total_items / $limit);

        $query_itens_menu = "SELECT id, fk_menu, descricao_item, link_item, ordem FROM administracao.adm_item_menu " 
                            . $where_clause . " ORDER BY fk_menu, ordem ASC LIMIT $limit OFFSET $offset";
        $result_itens_menu = pg_query_params($conexao, $query_itens_menu, $params);
        $erro_banco = pg_last_error($conexao);
        if (!$result_itens_menu) {
            die("Erro ao carregar os itens de menu.");
        }
        ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Itens de Menu</title>
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
        .paginator {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .paginator a, .paginator span {
            margin: 0 5px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #4a4a4a;
        }
        .paginator a:hover {
            background-color: #f0f0f0;
        }
        .paginator .current-page {
            background-color: #4caf50;
            color: white;
            border-color: #4caf50;
        }
        .parametro-pair {
            margin-bottom: 10px;
        }
        .parametro-pair input {
            margin-right: 5px;
            margin-bottom: 5px;
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

         <!-- Filtro por Menu -->
         <div class="form-container">
             <form method="GET" class="row g-3 align-items-center">
                 <input type="hidden" name="acao" value="<?= htmlspecialchars($acao) ?>">
                 <div class="col-auto">
                     <label for="menu_filter" class="col-form-label">Filtrar por Menu:</label>
                 </div>
                 <div class="col-auto">
                     <select class="form-select" id="menu_filter" name="menu_filter">
                         <option value="0">Todos os Menus</option>
                         <?php foreach ($menus as $menu): ?>
                             <option value="<?= $menu['id'] ?>" <?= ($menu_filter == $menu['id']) ? 'selected' : '' ?>>
                                 <?= htmlspecialchars($menu['descricao']) ?>
                             </option>
                         <?php endforeach; ?>
                     </select>
                 </div>
                 <div class="col-auto">
                     <button type="submit" class="btn btn-submit">Filtrar</button>
                 </div>
             </form>
         </div>

         <!-- Listagem paginada -->
         <?php if (!isset($_GET['cadastrar']) && !isset($_GET['id'])): ?>
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
                                 <a href="?acao=<?= htmlspecialchars($acao) ?>&id=<?= $item_menu['id'] ?>" class="btn btn-sm btn-edit">Editar</a>
                                 <a href="?acao=<?= htmlspecialchars($acao) ?>&delete_id=<?= $item_menu['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este item de menu?')">Excluir</a>
                             </td>
                         </tr>
                     <?php endwhile; ?>
                 </tbody>
             </table>
             
             <!-- Paginador -->
             <div class="paginator">
                 <?php if ($page > 1): ?>
                     <a href="?acao=<?= htmlspecialchars($acao) ?>&menu_filter=<?= $menu_filter ?>&page=<?= $page - 1 ?>">Anterior</a>
                 <?php else: ?>
                     <span>Anterior</span>
                 <?php endif; ?>

                 <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                     <?php if ($i == $page): ?>
                         <span class="current-page"><?= $i ?></span>
                     <?php else: ?>
                         <a href="?acao=<?= htmlspecialchars($acao) ?>&menu_filter=<?= $menu_filter ?>&page=<?= $i ?>"><?= $i ?></a>
                     <?php endif; ?>
                 <?php endfor; ?>

                 <?php if ($page < $total_pages): ?>
                     <a href="?acao=<?= htmlspecialchars($acao) ?>&menu_filter=<?= $menu_filter ?>&page=<?= $page + 1 ?>">Próxima</a>
                 <?php else: ?>
                     <span>Próxima</span>
                 <?php endif; ?>
             </div>
             <br>                    
             <div class="text-center">
                 <a href="?acao=<?= htmlspecialchars($acao) ?>&id=0" class="btn btn-submit btn-lg">Adicionar Novo Item de Menu</a>
             </div>
         </div>
         <br>
         <br>
         <br>
         <?php endif; ?>

         <!-- Formulário de cadastro/edição -->
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
                     <!-- Seção de Parâmetros -->
                     <div class="mb-3">
                         <label class="form-label">Parâmetros</label>
                         <div id="parametros-container">
                             <!-- Em cadastro, inicialmente nenhum parâmetro -->
                         </div>
                         <button type="button" id="add-parametro" class="btn btn-secondary">Adicionar Parâmetro</button>
                     </div>
                     <div class="text-center">
                         <button type="submit" class="btn btn-submit btn-lg">Salvar</button>
                         <a href="javascript:history.go(-2)" class="btn btn-secondary btn-lg">Voltar</a>
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
                     <!-- Seção de Parâmetros -->
                     <div class="mb-3">
                         <label class="form-label">Parâmetros</label>
                         <div id="parametros-container">
                             <?php if (isset($existing_parameters) && !empty($existing_parameters)): ?>
                                 <?php foreach ($existing_parameters as $param): ?>
                                     <div class="parametro-pair">
                                         <input type="text" name="action_item[]" placeholder="Parâmetro" value="<?= htmlspecialchars($param['action_item']) ?>" required>
                                         <input type="text" name="value_item[]" placeholder="Valor" value="<?= htmlspecialchars($param['value_item']) ?>" required>
                                         <button type="button" class="btn btn-danger remove-parametro">Remover</button>
                                     </div>
                                 <?php endforeach; ?>
                             <?php endif; ?>
                         </div>
                         <button type="button" id="add-parametro" class="btn btn-secondary">Adicionar Parâmetro</button>
                     </div>
                     <div class="text-center">
                         <button type="submit" class="btn btn-submit btn-lg">Salvar</button>
                         <a href="javascript:history.go(-2)" class="btn btn-secondary btn-lg">Voltar</a>
                     </div>
                 </form>
             </div>
         <?php endif; ?>
    </div>
    
    <!-- Script para adicionar e remover campos de parâmetros -->
    <script>
    document.getElementById('add-parametro').addEventListener('click', function(){
        var container = document.getElementById('parametros-container');
        var div = document.createElement('div');
        div.className = 'parametro-pair';
        div.innerHTML = '<input type="text" name="action_item[]" placeholder="Parâmetro" required> <input type="text" name="value_item[]" placeholder="Valor" required> <button type="button" class="btn btn-danger remove-parametro">Remover</button>';
        container.appendChild(div);
    });

    document.getElementById('parametros-container').addEventListener('click', function(e){
        if(e.target && e.target.matches("button.remove-parametro")){
            e.target.parentNode.remove();
        }
    });
    </script>
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
