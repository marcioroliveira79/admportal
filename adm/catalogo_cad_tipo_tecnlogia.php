<?php
session_start();

// Verifica se o usuário está logado e se a variável de ação ($acao) foi definida
$acao = isset($_GET['acao']) ? $_GET['acao'] : null;
if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {
    // Valida o acesso do usuário à tela
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        // Inicializa variáveis
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $delete_id = isset($_GET['delete_id']) ? (int) $_GET['delete_id'] : 0;
        $mensagem = '';

        // Exclusão de registro de Tipo de Tecnologia
        if ($delete_id > 0) {
            $query_delete = "DELETE FROM administracao.catalog_tipo_tecnlogia WHERE id = $1";
            $result_delete = pg_query_params($conexao, $query_delete, [$delete_id]);
            $erro_banco = pg_last_error($conexao);
            if ($result_delete) {
                $mensagem = "Registro excluído com sucesso!";
            } else {
                $mensagem = "Erro ao excluir o registro: " . $erro_banco;
            }
        }

        // Se houver ID no GET, carrega os dados para edição
        if ($id > 0) {
            $query = "
                SELECT 
                    id, 
                    tecnlogia, 
                    versao,
                    TO_CHAR(data_criacao, 'DD-MM-YYYY HH24:MI') AS data_criacao,
                    TO_CHAR(data_alteracao, 'DD-MM-YYYY HH24:MI') AS data_alteracao
                FROM administracao.catalog_tipo_tecnlogia
                WHERE id = $1
            ";
            $result = pg_query_params($conexao, $query, [$id]);
            if (!$result || pg_num_rows($result) === 0) {
                die("Registro não encontrado.");
            }
            $registro = pg_fetch_assoc($result);
        }

        // Processa o formulário para inserção ou atualização
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Captura e sanitiza os dados do formulário
            $tecnlogia = htmlspecialchars($_POST['tecnlogia']);
            $versao    = htmlspecialchars($_POST['versao']);
            $fk_usuario = $_SESSION['global_id_usuario']; // Usuário logado

            if ($id > 0) {
                // Atualização: atualiza o registro existente
                $query_update = "
                    UPDATE administracao.catalog_tipo_tecnlogia
                    SET tecnlogia = $1,
                        versao = $2,
                        fk_usuario_alteracao = $3,
                        data_alteracao = now()
                    WHERE id = $4
                ";
                $result_update = pg_query_params($conexao, $query_update, [$tecnlogia, $versao, $fk_usuario, $id]);
                if ($result_update) {
                    $mensagem = "Registro atualizado com sucesso!";
                } else {
                    $mensagem = "Erro ao atualizar o registro: " . pg_last_error($conexao);
                }
            } else {
                // Inserção: cadastra um novo registro
                $query_insert = "
                    INSERT INTO administracao.catalog_tipo_tecnlogia
                        (tecnlogia, versao, fk_usuario_criador, data_criacao)
                    VALUES ($1, $2, $3, now())
                ";
                $result_insert = pg_query_params($conexao, $query_insert, [$tecnlogia, $versao, $fk_usuario]);
                if ($result_insert) {
                    $mensagem = "Registro inserido com sucesso!";
                } else {
                    $mensagem = "Erro ao inserir o registro: " . pg_last_error($conexao);
                }
            }
        }

        // Consulta para listar todos os registros
        $query_list = "
            SELECT 
                id, 
                tecnlogia, 
                versao,
                TO_CHAR(data_criacao, 'DD-MM-YYYY HH24:MI') AS data_criacao
            FROM administracao.catalog_tipo_tecnlogia
            ORDER BY id ASC
        ";
        $result_list = pg_query($conexao, $query_list);
        if (!$result_list) {
            die("Erro ao carregar a lista de registros: " . pg_last_error($conexao));
        }
        ?>

        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Cadastro de Tipo de Tecnologia</title>
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
                    max-width: 90%;
                    margin: 40px auto;
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                    margin-bottom: 20px;
                }
                .alert-custom {
                    max-width: 90%;
                    margin: 40px auto;
                    background-color: #d1ecf1; /* Exemplo: cor de fundo personalizada */
                    color: #0c5460;           /* Exemplo: cor do texto personalizada */
                    border: 1px solid #bee5eb;
                    font-weight: bold;
                }
                .table-container {
                    max-width: 90%;
                    margin: 40px auto;
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                    margin-bottom: 20px;
                }
                .table-listing {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                th, td {
                    padding: 12px;
                    text-align: left;
                    border: 1px solid #ddd;
                    vertical-align: middle;
                }
                th {
                    background-color: #f8f9fa;
                    font-weight: bold;
                }
                .btn-edit {
                    background-color: #28a745;
                    color: white;
                }
                .btn-edit:hover {
                    background-color: #218838;
                }
                .btn-delete {
                    background-color: #dc3545;
                    color: white;
                }
                .btn-delete:hover {
                    background-color: #c82333;
                }
            </style>
        </head>
        <body>
            <br>
            <div class="container">
            <?php if (!empty($mensagem)): ?>
                <div class="alert alert-info alert-custom text-center">
                    <?= htmlspecialchars($mensagem) ?>
                </div>
            <?php endif; ?>

                <?php if ($id > 0): ?>
                    <!-- Formulário de Edição -->
                    <div class="form-container">
                        <h2 class="mb-4">Editar Tipo de Tecnologia</h2>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="tecnlogia" class="form-label">Tecnologia</label>
                                <input type="text" class="form-control" id="tecnlogia" name="tecnlogia" maxlength="100" value="<?= htmlspecialchars($registro['tecnlogia']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="versao" class="form-label">Versão</label>
                                <input type="text" class="form-control" id="versao" name="versao" maxlength="50" value="<?= htmlspecialchars($registro['versao']) ?>" required>
                            </div>
                            <button type="submit" class="btn btn-edit btn-lg">Salvar</button>
                            <a href="index.php?acao=<?= $acao ?>" class="btn btn-secondary btn-lg">Voltar</a>
                        </form>
                    </div>
                <?php else: ?>
                    <!-- Formulário de Cadastro -->
                    <div class="form-container">
                        <h2 class="mb-4">Tipo de Tecnologia</h2>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="tecnlogia" class="form-label">Tecnologia</label>
                                <input type="text" class="form-control" id="tecnlogia" name="tecnlogia" maxlength="100" required>
                            </div>
                            <div class="mb-3">
                                <label for="versao" class="form-label">Versão</label>
                                <input type="text" class="form-control" id="versao" name="versao" maxlength="50" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg">Cadastrar</button>
                        </form>
                    </div>

                    <!-- Lista de Registros -->
                    <div class="table-container">
                        <h2 class="mb-4"></h2>
                        <table class="table-listing">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tecnologia</th>
                                    <th>Versão</th>
                                    <th>Data de Criação</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = pg_fetch_assoc($result_list)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['id']) ?></td>
                                        <td><?= htmlspecialchars($row['tecnlogia']) ?></td>
                                        <td><?= htmlspecialchars($row['versao']) ?></td>
                                        <td><?= htmlspecialchars($row['data_criacao']) ?></td>
                                        <td>
                                            <a href="index.php?acao=<?= $acao ?>&id=<?= $row['id'] ?>" class="btn btn-sm btn-edit">Editar</a>
                                            <a href="index.php?acao=<?= $acao ?>&delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Tem certeza que deseja excluir este registro?')">Excluir</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                <?php if (pg_num_rows($result_list) == 0): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Nenhum registro encontrado.</td>
                                    </tr>
                                <?php endif; ?>
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
