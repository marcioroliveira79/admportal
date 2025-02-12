<?php
session_start();

// Verifica se o usuário está logado e se a ação foi definida
if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && isset($acao) && $acao != null) {
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        $mensagem = '';
        $erro_banco = '';

        // Processa a requisição POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                // Exclusão de atributo
                if ($_POST['action'] === 'delete_atributo') {
                    $id_atributo = (int)$_POST['id_atributo'];
                    $query_delete_atributo = "DELETE FROM administracao.adm_pseudo_tabela_atributos WHERE id = $1";
                    $result_delete_atributo = pg_query_params($conexao, $query_delete_atributo, [$id_atributo]);

                    if ($result_delete_atributo) {
                        $mensagem = "Atributo excluído com sucesso!";
                    } else {
                        $mensagem = "Erro ao excluir atributo.";
                        $erro_banco = pg_last_error($conexao);
                    }
                }
                // Exclusão de tabela (somente se não houver atributos associados)
                elseif ($_POST['action'] === 'delete_tabela') {
                    $id_tabela = (int)$_POST['id_tabela'];

                    // Verifica se existem atributos associados à tabela
                    $query_verifica_atributos = "SELECT COUNT(*) AS total FROM administracao.adm_pseudo_tabela_atributos WHERE fk_atributo = $1";
                    $result_verifica_atributos = pg_query_params($conexao, $query_verifica_atributos, [$id_tabela]);

                    if (!$result_verifica_atributos) {
                        $mensagem = "Erro ao verificar atributos associados à tabela.";
                        $erro_banco = pg_last_error($conexao);
                    } else {
                        $total_atributos = pg_fetch_result($result_verifica_atributos, 0, 'total');

                        if ($total_atributos > 0) {
                            $mensagem = "Não é possível excluir a tabela, pois existem atributos associados a ela.";
                        } else {
                            // Prossegue com a exclusão da tabela
                            $query_delete_tabela = "DELETE FROM administracao.adm_pseudo_tabela WHERE id = $1";
                            $result_delete_tabela = pg_query_params($conexao, $query_delete_tabela, [$id_tabela]);

                            if ($result_delete_tabela) {
                                $mensagem = "Tabela excluída com sucesso!";
                            } else {
                                $mensagem = "Erro ao excluir tabela.";
                                $erro_banco = pg_last_error($conexao);
                            }
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
            <title>Excluir Tabelas e Atributos</title>
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
                .btn-danger {
                    background-color: #dc3545;
                    border-color: #dc3545;
                    color: white;
                }
                .btn-danger:hover {
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
            </style>
        </head>
        <body>
        <div class="container">
            <div class="form-container">                
                <div class="form-title">Excluir Atributos</div>
                <?php if (!empty($mensagem)): ?>
                    <div class="alert alert-info text-center">
                        <?= htmlspecialchars($mensagem) ?>
                        <?php if (!empty($erro_banco)): ?>
                            <br><small class="text-danger"><?= htmlspecialchars($erro_banco) ?></small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="action" value="delete_atributo">
                    <div class="mb-3">
                        <label for="id_atributo" class="form-label">Selecione o Atributo</label>
                        <select name="id_atributo" id="id_atributo" class="form-select" required>
                            <option value="">Selecione</option>
                            <?php if ($atributos): ?>
                                <?php foreach ($atributos as $atributo): ?>
                                    <option value="<?= $atributo['id'] ?>">
                                        <?= htmlspecialchars($atributo['nome_item']) ?> (<?= htmlspecialchars($atributo['nome_tabela']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger">Excluir Atributo</button>
                </form>
            </div>

            <div class="form-container">                
                <div class="form-title">Excluir Tabelas</div>
                <form method="POST">
                    <input type="hidden" name="action" value="delete_tabela">
                    <div class="mb-3">
                        <label for="id_tabela" class="form-label">Selecione a Tabela</label>
                        <select name="id_tabela" id="id_tabela" class="form-select" required>
                            <option value="">Selecione</option>
                            <?php if ($pseudo_tabelas): ?>
                                <?php foreach ($pseudo_tabelas as $tabela): ?>
                                    <option value="<?= $tabela['id'] ?>"><?= htmlspecialchars($tabela['nome_tabela']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger">Excluir Tabela</button>
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
?>
