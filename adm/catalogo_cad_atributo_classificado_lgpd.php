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
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $delete_id = isset($_GET['delete_id']) ? (int)$_GET['delete_id'] : 0;
        $mensagem = '';

        // Exclusão de registro
        if ($delete_id > 0) {
            $query_delete = "DELETE FROM administracao.catalog_atributo_classificado_lgpd WHERE id = $1";
            $result_delete = pg_query_params($conexao, $query_delete, [$delete_id]);
            $erro_banco = pg_last_error($conexao);
            if ($result_delete) {
                $mensagem = "Registro excluído com sucesso!";
                header("Location: index.php?acao=" . $acao . "&mensagem=" . urlencode($mensagem));
                exit;
            } else {
                $mensagem = "Erro ao excluir o registro: " . $erro_banco;
            }
        }

        // Se houver ID no GET, carrega os dados para edição
        if ($id > 0) {
            $query = "
                SELECT 
                    id,
                    atributo,
                    fk_lgpd_classificacao,
                    TO_CHAR(data_criacao, 'DD-MM-YYYY HH24:MI') AS data_criacao,
                    TO_CHAR(data_alteracao, 'DD-MM-YYYY HH24:MI') AS data_alteracao
                FROM administracao.catalog_atributo_classificado_lgpd
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
            $atributo = htmlspecialchars($_POST['atributo']);
            $fk_lgpd_classificacao = (int) $_POST['fk_lgpd_classificacao'];
            $fk_usuario = $_SESSION['global_id_usuario']; // Usuário logado

            if ($id > 0) {
                // Atualização: atualiza o registro existente
                $query_update = "
                    UPDATE administracao.catalog_atributo_classificado_lgpd
                    SET atributo = $1,
                        fk_lgpd_classificacao = $2,
                        fk_usuario_alteracao = $3,
                        data_alteracao = now()
                    WHERE id = $4
                ";
                $result_update = pg_query_params($conexao, $query_update, [$atributo, $fk_lgpd_classificacao, $fk_usuario, $id]);
                if ($result_update) {
                    $mensagem = "Registro atualizado com sucesso!";
                } else {
                    $mensagem = "Erro ao atualizar o registro: " . pg_last_error($conexao);
                }
            } else {
                // Inserção: cadastra um novo registro
                $query_insert = "
                    INSERT INTO administracao.catalog_atributo_classificado_lgpd
                        (atributo, fk_lgpd_classificacao, fk_usuario_criador, data_criacao)
                    VALUES ($1, $2, $3, now())
                ";
                $result_insert = pg_query_params($conexao, $query_insert, [$atributo, $fk_lgpd_classificacao, $fk_usuario]);
                if ($result_insert) {
                    $mensagem = "Registro inserido com sucesso!";
                } else {
                    $mensagem = "Erro ao inserir o registro: " . pg_last_error($conexao);
                }
            }
        }

        /* ----- PARÂMETROS PARA BUSCA E PAGINAÇÃO ----- */
        $busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) { $page = 1; }
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        if ($limit < 1) { $limit = 10; }
        $offset = ($page - 1) * $limit;

        // Construir cláusula WHERE para busca (por atributo ou classificação)
        $whereClause = "";
        $params = [];
        if (!empty($busca)) {
            $whereClause = "WHERE (a.atributo ILIKE $1 OR l.classificacao ILIKE $1)";
            $params[] = '%' . $busca . '%';
        }

        // Consulta para contar o total de registros
        $query_count = "
            SELECT COUNT(*) as total
            FROM administracao.catalog_atributo_classificado_lgpd a
            LEFT JOIN administracao.catalog_lgpd_classificacao l ON a.fk_lgpd_classificacao = l.id
            $whereClause
        ";
        $result_count = pg_query_params($conexao, $query_count, $params);
        $total_records = 0;
        if ($result_count) {
            $row_count = pg_fetch_assoc($result_count);
            $total_records = (int)$row_count['total'];
        }
        $total_pages = ceil($total_records / $limit);

        // Consulta para listar os registros com join, aplicando a busca e a paginação
        $query_list = "
            SELECT 
                a.id,
                a.atributo,
                a.fk_lgpd_classificacao,
                l.classificacao,
                TO_CHAR(a.data_criacao, 'DD-MM-YYYY HH24:MI') AS data_criacao
            FROM administracao.catalog_atributo_classificado_lgpd a
            LEFT JOIN administracao.catalog_lgpd_classificacao l ON a.fk_lgpd_classificacao = l.id
            $whereClause
            ORDER BY a.id ASC
            LIMIT $limit OFFSET $offset
        ";
        $result_list = pg_query_params($conexao, $query_list, $params);
        if (!$result_list) {
            die("Erro ao carregar a lista de registros: " . pg_last_error($conexao));
        }

        // Consulta para carregar a lista de classificações LGPD para o select
        $query_classificacoes = "SELECT id, classificacao FROM administracao.catalog_lgpd_classificacao ORDER BY classificacao ASC";
        $result_classificacoes = pg_query($conexao, $query_classificacoes);
        if (!$result_classificacoes) {
            die("Erro ao carregar a lista de classificações: " . pg_last_error($conexao));
        }
        $classificacoes = pg_fetch_all($result_classificacoes);
        ?>

        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Cadastro de Atributo Classificado LGPD</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                /* Estilo do paginador com cores verdes */
                .pagination {
                    display: flex;
                    justify-content: center;
                    margin-top: 20px;
                }
                .pagination {
                    display: flex;
                    justify-content: center;
                    margin-top: 20px;
                }
                .pagination a {
                    margin: 0 5px;
                    padding: 8px 12px;
                    background-color: #f8f9fa;
                    border: 1px solid #ddd;
                    color: #218838;
                    text-decoration: none;
                }
                .pagination a.active {
                    background-color: #218838;
                    color: white;
                    border-color: #218838;
                }
                .pagination a:hover {
                    background-color: #ddd;
                }
                /* Ajustes para a tabela */
                .table-listing {
                    table-layout: fixed;
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                .table-listing th, .table-listing td {
                    padding: 12px;
                    border: 1px solid #ddd;
                    vertical-align: middle;
                    text-align: left;
                }
                .table-listing th {
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
                /* Se desejar ajustar as colunas de Data de Criação e Ações */
                .table-listing th:nth-child(4),
                .table-listing td:nth-child(4) {
                    min-width: 150px;
                    text-align: center;
                }
                .table-listing th:nth-child(5),
                .table-listing td:nth-child(5) {
                    min-width: 120px;
                    text-align: center;
                }
                body {
                    background-color: #f5f5f5;
                    font-family: Arial, sans-serif;
                }
                .form-container {
                    max-width: 90%;
                    margin: 40px auto;
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    padding: 20px;
                    margin-bottom: 20px;
                }
                .alert-custom {
                    max-width: 90%;
                    margin: 40px auto;
                    background-color: #d1ecf1;
                    color: #0c5460;
                    border: 1px solid #bee5eb;
                    font-weight: bold;
                }
                .table-container {
                    max-width: 90%;
                    margin: 40px auto;
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    padding: 20px;
                    margin-bottom: 20px;
                }
            </style>
        </head>
        <body>
            <br>
            <div class="container">
                <?php if (!empty($mensagem)): ?>
                    <div class="alert alert-success alert-custom text-center">
                        <?= htmlspecialchars($mensagem) ?>
                    </div>
                <?php endif; ?>

                <?php if ($id > 0): ?>
                    <!-- Formulário de Edição -->
                    <div class="form-container">
                        <h2 class="mb-4">Editar Atributo Classificado LGPD</h2>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="atributo" class="form-label">Atributo</label>
                                <input type="text" class="form-control" id="atributo" name="atributo" maxlength="255" value="<?= htmlspecialchars($registro['atributo']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="fk_lgpd_classificacao" class="form-label">Classificação LGPD</label>
                                <select class="form-select" id="fk_lgpd_classificacao" name="fk_lgpd_classificacao" required>
                                    <option value="">Selecione</option>
                                    <?php if ($classificacoes): ?>
                                        <?php foreach ($classificacoes as $class): ?>
                                            <option value="<?= $class['id'] ?>" <?= ($id > 0 && $registro['fk_lgpd_classificacao'] == $class['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($class['classificacao']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg">Salvar</button>
                            <a href="index.php?acao=<?= $acao ?>" class="btn btn-success btn-lg">Voltar</a>
                        </form>
                    </div>
                <?php else: ?>
                    <!-- Formulário de Cadastro -->
                    <div class="form-container">
                        <h2 class="mb-4">Classificado LGPD</h2>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="atributo" class="form-label">Atributo</label>
                                <input type="text" class="form-control" id="atributo" name="atributo" maxlength="255" required>
                            </div>
                            <div class="mb-3">
                                <label for="fk_lgpd_classificacao" class="form-label">Classificação LGPD</label>
                                <select class="form-select" id="fk_lgpd_classificacao" name="fk_lgpd_classificacao" required>
                                    <option value="">Selecione</option>
                                    <?php if ($classificacoes): ?>
                                        <?php foreach ($classificacoes as $class): ?>
                                            <option value="<?= $class['id'] ?>">
                                                <?= htmlspecialchars($class['classificacao']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg">Cadastrar</button>
                        </form>
                    </div>

                    <!-- Formulário de Busca e Seleção de Limite -->
                    <div class="form-container">
                        <form method="GET" id="formBusca" class="row g-3">
                            <input type="hidden" name="acao" value="<?= htmlspecialchars($acao) ?>">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="busca" placeholder="Buscar por atributo ou classificação" value="<?= htmlspecialchars($busca) ?>">
                            </div>
                            <div class="col-md-3">
                                <select name="limit" class="form-select">
                                    <option value="5" <?= ($limit == 5 ? 'selected' : '') ?>>5 registros</option>
                                    <option value="10" <?= ($limit == 10 ? 'selected' : '') ?>>10 registros</option>
                                    <option value="20" <?= ($limit == 20 ? 'selected' : '') ?>>20 registros</option>
                                    <option value="50" <?= ($limit == 50 ? 'selected' : '') ?>>50 registros</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success btn-lg">Buscar</button>
                            </div>
                        </form>
                    </div>

                    <!-- Lista de Registros com Paginação -->
                    <div class="table-container">
                        <h2 class="mb-4"></h2>
                        <table class="table-listing">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Atributo</th>
                                    <th>Classificação LGPD</th>
                                    <th>Data de Criação</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = pg_fetch_assoc($result_list)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['id']) ?></td>
                                        <td><?= htmlspecialchars($row['atributo']) ?></td>
                                        <td><?= htmlspecialchars($row['classificacao']) ?></td>
                                        <td><?= htmlspecialchars($row['data_criacao']) ?></td>
                                        <td>
                                            <a href="index.php?acao=<?= $acao ?>&id=<?= $row['id'] ?>" class="btn btn-sm btn-edit">Editar</a>
                                            <a href="index.php?acao=<?= $acao ?>&delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-delete"  onclick="return confirm('Tem certeza que deseja excluir este registro?')">Excluir</a>
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

                        <!-- Paginação -->
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="index.php?acao=<?= $acao ?>&busca=<?= urlencode($busca) ?>&limit=<?= $limit ?>&page=<?= $page - 1 ?>">&laquo; Anterior</a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="index.php?acao=<?= $acao ?>&busca=<?= urlencode($busca) ?>&limit=<?= $limit ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="index.php?acao=<?= $acao ?>&busca=<?= urlencode($busca) ?>&limit=<?= $limit ?>&page=<?= $page + 1 ?>">Próximo &raquo;</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <br><br><br>
        </body>
        </html>
        <?php
    } else {
        @include("html/403.html");
    }
} else {
    header("Location: login.php");
}
?>
