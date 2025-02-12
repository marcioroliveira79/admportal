<?php
session_start();

if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        $mensagem = '';
        $erro_banco = '';

        // Gerenciar tabelas e atributos
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'create_or_edit_tabela') {
                    // Criar ou editar pseudo tabela
                    $id_tabela = isset($_POST['id_tabela']) ? (int)$_POST['id_tabela'] : 0;
                    $nome_tabela = htmlspecialchars($_POST['nome_tabela']);
                    $descricao = htmlspecialchars($_POST['descricao']);

                    // Verificar se o nome da tabela já existe
                    $query_check_nome = "SELECT COUNT(*) FROM administracao.adm_pseudo_tabela WHERE nome_tabela = $1";
                    $params_check_nome = [$nome_tabela];
                    if ($id_tabela > 0) {
                        $query_check_nome .= " AND id != $2";
                        $params_check_nome[] = $id_tabela;
                    }
                    $result_check_nome = pg_query_params($conexao, $query_check_nome, $params_check_nome);
                    $exists_nome = (int)pg_fetch_result($result_check_nome, 0, 0) > 0;

                    if ($exists_nome) {
                        $mensagem = "Já existe uma tabela com o nome '{$nome_tabela}'. Por favor, escolha outro nome.";
                    } else {
                        if ($id_tabela > 0) {
                            // Editar tabela
                            $query_update = "UPDATE administracao.adm_pseudo_tabela SET nome_tabela = $1, descricao = $2 WHERE id = $3";
                            $result_update = pg_query_params($conexao, $query_update, [$nome_tabela, $descricao, $id_tabela]);

                            $mensagem = $result_update ? "Tabela atualizada com sucesso!" : "Erro ao atualizar tabela.";
                            $erro_banco = $result_update ? '' : pg_last_error($conexao);
                        } else {
                            // Criar tabela
                            $query_create = "INSERT INTO administracao.adm_pseudo_tabela (nome_tabela, descricao) VALUES ($1, $2)";
                            $result_create = pg_query_params($conexao, $query_create, [$nome_tabela, $descricao]);

                            $mensagem = $result_create ? "Nova tabela criada com sucesso!" : "Erro ao criar tabela.";
                            $erro_banco = $result_create ? '' : pg_last_error($conexao);
                        }
                    }
                } elseif ($_POST['action'] === 'create_or_edit_atributo') {
                    // Criar ou editar atributo
                    $id_atributo = isset($_POST['id_atributo']) ? (int)$_POST['id_atributo'] : 0;
                    $fk_tabela = (int)$_POST['fk_tabela'];
                    $nome_item = htmlspecialchars($_POST['nome_item']);
                    $descricao = htmlspecialchars($_POST['descricao']);
                    $valor_item = htmlspecialchars($_POST['valor_item']);

                    if ($id_atributo > 0) {
                        // Editar atributo
                        $query_update = "UPDATE administracao.adm_pseudo_tabela_atributos 
                                         SET nome_item = $1, descricao = $2, valor_item = $3 
                                         WHERE id = $4";
                        $result_update = pg_query_params($conexao, $query_update, [$nome_item, $descricao, $valor_item, $id_atributo]);

                        $mensagem = $result_update ? "Atributo atualizado com sucesso!" : "Erro ao atualizar atributo.";
                        $erro_banco = $result_update ? '' : pg_last_error($conexao);
                    } else {
                        // Garantir que não haja duplicatas
                        $query_check = "SELECT COUNT(*) FROM administracao.adm_pseudo_tabela_atributos 
                                        WHERE fk_atributo = $1 AND nome_item = $2";
                        $result_check = pg_query_params($conexao, $query_check, [$fk_tabela, $nome_item]);
                        $exists = (int)pg_fetch_result($result_check, 0, 0) > 0;

                        if ($exists) {
                            $mensagem = "Este atributo já existe para a tabela selecionada.";
                        } else {
                            $query_create = "INSERT INTO administracao.adm_pseudo_tabela_atributos 
                                             (fk_atributo, nome_item, descricao, valor_item) 
                                             VALUES ($1, $2, $3, $4)";
                            $result_create = pg_query_params($conexao, $query_create, [$fk_tabela, $nome_item, $descricao, $valor_item]);

                            $mensagem = $result_create ? "Novo atributo criado com sucesso!" : "Erro ao criar atributo.";
                            $erro_banco = $result_create ? '' : pg_last_error($conexao);
                        }
                    }
                }
            }
        }

        // Listar tabelas
        $query_tabelas = "SELECT * FROM administracao.adm_pseudo_tabela ORDER BY nome_tabela ASC";
        $result_tabelas = pg_query($conexao, $query_tabelas);
        $pseudo_tabelas = pg_fetch_all($result_tabelas);

        // Listar atributos
        $query_atributos = "
            SELECT a.*, t.nome_tabela 
            FROM administracao.adm_pseudo_tabela_atributos a
            JOIN administracao.adm_pseudo_tabela t ON a.fk_atributo = t.id
            ORDER BY t.nome_tabela, a.nome_item ASC";
        $result_atributos = pg_query($conexao, $query_atributos);
        $atributos = pg_fetch_all($result_atributos);

        ?>

        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Gerenciar Pseudo Tabelas</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
                .btn-submit {
                    background-color: #4caf50;
                    border-color: #4caf50;
                    color: white;
                }
                .btn-submit:hover {
                    background-color: #45a049;
                    color: white;
                }
                .form-title {
                    text-align: center;
                    margin-bottom: 20px;
                    font-weight: bold;
                    font-size: 24px;
                    color: #4a4a4a;
                }
            </style>
        </head>
        <body>
        <div class="container">
            <div class="form-container">                
                <div class="form-title">Criar ou Editar Pseudo Tabela</div>
                <?php if (!empty($mensagem)): ?>
                    <div class="alert alert-info text-center">
                        <?= htmlspecialchars($mensagem) ?>
                        <?php if (!empty($erro_banco)): ?>
                            <br><small class="text-danger"><?= htmlspecialchars($erro_banco) ?></small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="action" value="create_or_edit_tabela">
                    <div class="mb-3">
                        <label for="id_tabela" class="form-label">Tabela</label>
                        <select name="id_tabela" id="id_tabela" class="form-select">
                            <option value="0">Nova Tabela</option>
                            <?php foreach ($pseudo_tabelas as $tabela): ?>
                                <option value="<?= $tabela['id'] ?>"><?= htmlspecialchars($tabela['nome_tabela']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nome_tabela" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome_tabela" name="nome_tabela" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" required>
                    </div>
                    <button type="submit" class="btn btn-submit">Salvar</button>
                </form>
            </div>

            <div class="form-container">                
                <div class="form-title">Criar ou Editar Atributo</div>
                <form method="POST">
                    <input type="hidden" name="action" value="create_or_edit_atributo">
                    <div class="mb-3">
                        <label for="id_atributo" class="form-label">Atributo</label>
                        <select name="id_atributo" id="id_atributo" class="form-select">
                            <option value="0">Novo Atributo</option>
                            <?php foreach ($atributos as $atributo): ?>
                                <option value="<?= $atributo['id'] ?>">
                                    <?= htmlspecialchars($atributo['nome_item']) ?> (<?= htmlspecialchars($atributo['nome_tabela']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fk_tabela" class="form-label">Tabela</label>
                        <select name="fk_tabela" id="fk_tabela" class="form-select" required>
                            <?php foreach ($pseudo_tabelas as $tabela): ?>
                                <option value="<?= $tabela['id'] ?>"><?= htmlspecialchars($tabela['nome_tabela']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nome_item" class="form-label">Nome do Atributo</label>
                        <input type="text" class="form-control" id="nome_item" name="nome_item" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" required>
                    </div>
                    <div class="mb-3">
                        <label for="valor_item" class="form-label">Valor</label>
                        <input type="text" class="form-control" id="valor_item" name="valor_item" required>
                    </div>
                    <button type="submit" class="btn btn-submit">Salvar</button>
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
