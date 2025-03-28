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
                    tipo_atributo,
                    tipo_definicao,
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

            // Classificação LGPD (campo hidden)
            $fk_lgpd_classificacao = (int) $_POST['fk_lgpd_classificacao'];

            // Normaliza os valores para evitar problemas de espaços ou capitalização
            $tipo_atributo = strtoupper(trim($_POST['tipo_atributo']));
            $tipo_definicao = strtoupper(trim($_POST['tipo_definicao']));

            $fk_usuario = $_SESSION['global_id_usuario']; // Usuário logado

            // Validação: se a atribuição for EXCLUDENTE, a definição deve ser ATRIBUTO
            if ($tipo_atributo === "EXCLUDENTE" && $tipo_definicao !== "ATRIBUTO") {
                $mensagem = "Para atribuição EXCLUDENTE, a definição deve ser ATRIBUTO.";
            } else {
                if ($id > 0) {
                    // Atualização: atualiza o registro existente
                    $query_update = "
                        UPDATE administracao.catalog_atributo_classificado_lgpd
                        SET atributo = $1,
                            fk_lgpd_classificacao = $2,
                            tipo_atributo = $3,
                            tipo_definicao = $4,
                            fk_usuario_alteracao = $5,
                            data_alteracao = now()
                        WHERE id = $6
                    ";
                    $result_update = pg_query_params($conexao, $query_update, [
                        $atributo,
                        $fk_lgpd_classificacao,
                        $tipo_atributo,
                        $tipo_definicao,
                        $fk_usuario,
                        $id
                    ]);
                    if ($result_update) {
                        $mensagem = "Registro atualizado com sucesso!";
                        geraArquivoPalavraLGPD($conexao);
                    } else {
                        $mensagem = "Erro ao atualizar o registro: " . pg_last_error($conexao);
                    }
                } else {
                    // Inserção: cadastra um novo registro
                    $query_insert = "
                        INSERT INTO administracao.catalog_atributo_classificado_lgpd
                            (atributo, fk_lgpd_classificacao, tipo_atributo, tipo_definicao, fk_usuario_criador, data_criacao)
                        VALUES ($1, $2, $3, $4, $5, now())
                    ";
                    $result_insert = pg_query_params($conexao, $query_insert, [
                        $atributo,
                        $fk_lgpd_classificacao,
                        $tipo_atributo,
                        $tipo_definicao,
                        $fk_usuario
                    ]);
                    if ($result_insert) {
                        $mensagem = "Registro inserido com sucesso!";
                        geraArquivoPalavraLGPD($conexao);
                    } else {
                        $mensagem = "Erro ao inserir o registro: " . pg_last_error($conexao);
                    }
                }
            }
        }

        /* ----- PARÂMETROS PARA BUSCA E PAGINAÇÃO ----- */
        $busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
        $tipo_atributo_search = isset($_GET['tipo_atributo']) ? trim($_GET['tipo_atributo']) : '';
        $tipo_definicao_search = isset($_GET['tipo_definicao']) ? trim($_GET['tipo_definicao']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) { $page = 1; }
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        if ($limit < 1) { $limit = 10; }
        $offset = ($page - 1) * $limit;

        // Monta a cláusula WHERE dinamicamente
        $whereParts = [];
        $params = [];
        if (!empty($busca)) {
            $whereParts[] = "(a.atributo ILIKE $" . (count($params)+1) . " OR l.classificacao ILIKE $" . (count($params)+1) . ")";
            $params[] = '%' . $busca . '%';
        }
        if (!empty($tipo_atributo_search)) {
            $whereParts[] = "a.tipo_atributo = $" . (count($params)+1);
            $params[] = $tipo_atributo_search;
        }
        if (!empty($tipo_definicao_search)) {
            $whereParts[] = "a.tipo_definicao = $" . (count($params)+1);
            $params[] = $tipo_definicao_search;
        }
        $whereClause = '';
        if (!empty($whereParts)) {
            $whereClause = "WHERE " . implode(" AND ", $whereParts);
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
                a.tipo_atributo,
                a.tipo_definicao,
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
        // Supondo que "INDEFINIDO" = id=4, "PESSOAL"=1, "SENSÍVEL"=2
        $query_classificacoes = "SELECT id, classificacao FROM administracao.catalog_lgpd_classificacao WHERE id in (4,1,2) ORDER BY classificacao ASC";
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
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
            <script>
                if (window.top === window.self) {
                    // Se a página não estiver sendo exibida dentro de um iframe, redireciona para o index
                    window.location.href = 'index.php';
                }
            </script>
            <style>
                .pagination {
                    display: flex;
                    justify-content: center;
                    margin-top: 20px;
                }
                .pagination a, .pagination span {
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
                .table-listing th:nth-child(1),
                .table-listing td:nth-child(1) { width: 5%; }
                .table-listing th:nth-child(2),
                .table-listing td:nth-child(2) { width: 30%; }
                .table-listing th:nth-child(3),
                .table-listing td:nth-child(3) { width: 10%; }
                .table-listing th:nth-child(4),
                .table-listing td:nth-child(4) { width: 10%; }
                .table-listing th:nth-child(5),
                .table-listing td:nth-child(5) { width: 10%; }
                .table-listing th:nth-child(6),
                .table-listing td:nth-child(6) { width: 15%; }
                .table-listing th:nth-child(7),
                .table-listing td:nth-child(7) {
                    width: 15%;
                    min-width: 150px;
                    text-align: center;
                    white-space: nowrap;
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
                .help-icon {
                    font-size: 1.5rem;
                    color: #0c5460;
                    cursor: pointer;
                    margin-right: 10px;
                }
                .readonly-select {
                    pointer-events: none;
                    background-color: #e9ecef;
                    color: #6c757d;
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

                <?php
                // Definir valores iniciais para Classificação e Definição
                $classValue = $id > 0 ? $registro['fk_lgpd_classificacao'] : "";
                $defValue = $id > 0 ? $registro['tipo_definicao'] : "ATRIBUTO";
                $atribValue = $id > 0 ? $registro['tipo_atributo'] : "INCLUSIVO";
                ?>

                <?php if ($id > 0): ?>
                    <!-- Formulário de Edição -->
                    <div class="form-container">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-question-circle-fill help-icon" data-bs-toggle="modal" data-bs-target="#helpModal"></i>
                            <h2 class="mb-0">Editar Atributo LGPD</h2>
                        </div>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="atributo" class="form-label">Atributo</label>
                                <input type="text" class="form-control" id="atributo" name="atributo" maxlength="255"
                                       value="<?= htmlspecialchars($registro['atributo']) ?>" required>
                            </div>

                            <!-- Classificação: select de exibição + input hidden -->
                            <div class="mb-3">
                                <label class="form-label">Classificação LGPD</label>
                                <select class="form-select" id="fk_lgpd_classificacao_display"></select>
                                <input type="hidden" id="fk_lgpd_classificacao" name="fk_lgpd_classificacao"
                                       value="<?= htmlspecialchars($classValue) ?>">
                            </div>

                            <!-- Atribuição -->
                            <div class="mb-3">
                                <label for="tipo_atributo" class="form-label">Atribuição</label>
                                <select class="form-select" id="tipo_atributo" name="tipo_atributo" required>
                                    <option value="INCLUSIVO" <?= ($atribValue === "INCLUSIVO") ? 'selected' : '' ?>>INCLUSIVO</option>
                                    <option value="EXCLUDENTE" <?= ($atribValue === "EXCLUDENTE") ? 'selected' : '' ?>>EXCLUDENTE</option>
                                </select>
                            </div>

                            <!-- Definição: select de exibição + input hidden -->
                            <div class="mb-3">
                                <label class="form-label">Definição</label>
                                <select class="form-select" id="tipo_definicao_display">
                                    <option value="ATRIBUTO" <?= ($defValue === "ATRIBUTO") ? 'selected' : '' ?>>ATRIBUTO</option>
                                    <option value="DICIONARIO" <?= ($defValue === "DICIONARIO") ? 'selected' : '' ?>>DICIONARIO</option>
                                </select>
                                <input type="hidden" id="tipo_definicao" name="tipo_definicao"
                                       value="<?= htmlspecialchars($defValue) ?>">
                            </div>

                            <button type="submit" class="btn btn-success btn-lg">Salvar</button>
                            <a href="index.php?acao=<?= $acao ?>" class="btn btn-success btn-lg">Voltar</a>
                        </form>
                    </div>
                <?php else: ?>
                    <!-- Formulário de Cadastro -->
                    <?php
                    $classValue = "";
                    $defValue = "ATRIBUTO";
                    $atribValue = "INCLUSIVO";
                    ?>
                    <div class="form-container">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-question-circle-fill help-icon" data-bs-toggle="modal" data-bs-target="#helpModal"></i>
                            <h2 class="mb-0">Cadastrar Atributo LGPD</h2>
                        </div>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="atributo" class="form-label">Atributo</label>
                                <input type="text" class="form-control" id="atributo" name="atributo" maxlength="255" required>
                            </div>

                            <!-- Classificação: select de exibição + input hidden -->
                            <div class="mb-3">
                                <label class="form-label">Classificação LGPD</label>
                                <select class="form-select" id="fk_lgpd_classificacao_display"></select>
                                <input type="hidden" id="fk_lgpd_classificacao" name="fk_lgpd_classificacao" value="">
                            </div>

                            <!-- Atribuição -->
                            <div class="mb-3">
                                <label class="form-label">Atribuição</label>
                                <select class="form-select" id="tipo_atributo" name="tipo_atributo" required>
                                    <option value="INCLUSIVO" selected>INCLUSIVO</option>
                                    <option value="EXCLUDENTE">EXCLUDENTE</option>
                                </select>
                            </div>

                            <!-- Definição: select de exibição + input hidden -->
                            <div class="mb-3">
                                <label class="form-label">Definição</label>
                                <select class="form-select" id="tipo_definicao_display">
                                    <option value="ATRIBUTO" selected>ATRIBUTO</option>
                                    <option value="DICIONARIO">DICIONARIO</option>
                                </select>
                                <input type="hidden" id="tipo_definicao" name="tipo_definicao" value="ATRIBUTO">
                            </div>

                            <button type="submit" class="btn btn-success btn-lg">Cadastrar</button>
                        </form>
                    </div>
                <?php endif; ?>

                <!-- Formulário de Busca e Seleção de Limite -->
                <div class="form-container">
                    <form method="GET" id="formBusca" class="row g-3">
                        <input type="hidden" name="acao" value="<?= htmlspecialchars($acao) ?>">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="busca" placeholder="Buscar por atributo ou classificação" value="<?= htmlspecialchars($busca) ?>">
                        </div>
                        <!-- Combo para selecionar Tipo Atributo na busca -->
                        <div class="col-md-2">
                            <select name="tipo_atributo" class="form-select">
                                <option value="">Todos os tipos de atributo</option>
                                <option value="INCLUSIVO" <?= ($tipo_atributo_search == "INCLUSIVO") ? 'selected' : '' ?>>INCLUSIVO</option>
                                <option value="EXCLUDENTE" <?= ($tipo_atributo_search == "EXCLUDENTE") ? 'selected' : '' ?>>EXCLUDENTE</option>
                            </select>
                        </div>
                        <!-- Combo para selecionar Tipo Definição na busca -->
                        <div class="col-md-2">
                            <select name="tipo_definicao" class="form-select">
                                <option value="">Todos os tipos de definição</option>
                                <option value="ATRIBUTO" <?= ($tipo_definicao_search == "ATRIBUTO") ? 'selected' : '' ?>>ATRIBUTO</option>
                                <option value="DICIONARIO" <?= ($tipo_definicao_search == "DICIONARIO") ? 'selected' : '' ?>>DICIONARIO</option>
                            </select>
                        </div>
                        <div class="col-md-2">
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
                                <th>Atribuição</th>
                                <th>Definição</th>
                                <th>LGPD</th>
                                <th>Data de Criação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = pg_fetch_assoc($result_list)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['atributo']) ?></td>
                                    <td><?= htmlspecialchars($row['tipo_atributo']) ?></td>
                                    <td><?= htmlspecialchars($row['tipo_definicao']) ?></td>
                                    <td><?= htmlspecialchars($row['classificacao']) ?></td>
                                    <td><?= htmlspecialchars($row['data_criacao']) ?></td>
                                    <td>
                                        <a href="index.php?acao=<?= $acao ?>&busca=<?= urlencode($busca) ?>&tipo_atributo=<?= urlencode($tipo_atributo_search) ?>&tipo_definicao=<?= urlencode($tipo_definicao_search) ?>&limit=<?= $limit ?>&page=<?= $page ?>&id=<?= $row['id'] ?>" class="btn btn-sm btn-edit">Editar</a>
                                        <a href="index.php?acao=<?= $acao ?>&busca=<?= urlencode($busca) ?>&tipo_atributo=<?= urlencode($tipo_atributo_search) ?>&tipo_definicao=<?= urlencode($tipo_definicao_search) ?>&limit=<?= $limit ?>&page=<?= $page ?>&delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Tem certeza que deseja excluir este registro?')">Excluir</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if (pg_num_rows($result_list) == 0): ?>
                                <tr>
                                    <td colspan="7" class="text-center">Nenhum registro encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Paginação -->
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="index.php?acao=<?= $acao ?>&busca=<?= urlencode($busca) ?>&tipo_atributo=<?= urlencode($tipo_atributo_search) ?>&tipo_definicao=<?= urlencode($tipo_definicao_search) ?>&limit=<?= $limit ?>&page=<?= $page - 1 ?>">&laquo; Anterior</a>
                        <?php endif; ?>

                        <?php
                        $maxPagesToShow = 10;
                        $endPage = ($total_pages > $maxPagesToShow) ? $maxPagesToShow : $total_pages;
                        for ($i = 1; $i <= $endPage; $i++):
                        ?>
                            <a href="index.php?acao=<?= $acao ?>&busca=<?= urlencode($busca) ?>&tipo_atributo=<?= urlencode($tipo_atributo_search) ?>&tipo_definicao=<?= urlencode($tipo_definicao_search) ?>&limit=<?= $limit ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($total_pages > $maxPagesToShow): ?>
                            <span>...</span>
                            <a href="index.php?acao=<?= $acao ?>&busca=<?= urlencode($busca) ?>&tipo_atributo=<?= urlencode($tipo_atributo_search) ?>&tipo_definicao=<?= urlencode($tipo_definicao_search) ?>&limit=<?= $limit ?>&page=<?= $total_pages ?>"><?= $total_pages ?></a>
                        <?php endif; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="index.php?acao=<?= $acao ?>&busca=<?= urlencode($busca) ?>&tipo_atributo=<?= urlencode($tipo_atributo_search) ?>&tipo_definicao=<?= urlencode($tipo_definicao_search) ?>&limit=<?= $limit ?>&page=<?= $page + 1 ?>">Próximo &raquo;</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Modal de Ajuda -->
            <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="helpModalLabel">Ajuda - Cadastro de Atributos LGPD</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                  </div>
                  <div class="modal-body">
                    <p>Nesta tela você pode cadastrar atributos que serão utilizados na marcação de dados conforme a LGPD, permitindo configurar regras específicas de inclusão ou exclusão.</p>
                    <p><strong>Atribuição</strong></p>
                    <ul>
                        <li><strong>EXCLUDENTE:</strong> O atributo não será relacionado na marcação de LGPD. Se o algoritmo detectar este atributo, ele será ignorado.  
                            - Quando EXCLUDENTE, a <strong>Definição</strong> é forçada em ATRIBUTO e a <strong>Classificação</strong> é forçada em INDEFINIDO.
                        </li>
                        <li><strong>INCLUSIVO:</strong> O atributo será considerado para LGPD. Além disso, você poderá definir se ele é <strong>PESSOAL</strong> ou <strong>SENSÍVEL</strong> na Classificação e escolher <strong>ATRIBUTO</strong> ou <strong>DICIONARIO</strong> para a Definição.</li>
                    </ul>
                    <p><strong>Definição</strong></p>
                    <ul>
                        <li><strong>ATRIBUTO:</strong> A comparação será feita de forma exata, ou seja, o valor encontrado deverá corresponder integralmente ao atributo cadastrado.</li>
                        <li><strong>DICIONÁRIO:</strong> A comparação será parcial (como %ATRIBUTO%), permitindo identificar o atributo mesmo quando fizer parte de outro termo.</li>
                    </ul>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Script para controlar Classificação e Definição -->
            <script>
                // Carrega todas as classificações do PHP
                // Ex: [ {id: "4", classificacao: "INDEFINIDO"}, {id: "1", classificacao: "PESSOAL"}, {id: "2", classificacao: "SENSÍVEL"} ]
                var allClassOptions = <?php echo json_encode($classificacoes); ?>;

                // Separa "INDEFINIDO" (EXCLUDENTE) das inclusivas (PESSOAL, SENSÍVEL)
                var indefinidoOption = allClassOptions.filter(function(opt) {
                    return opt.classificacao.toUpperCase() === "INDEFINIDO";
                })[0];
                var inclusiveClassOptions = allClassOptions.filter(function(opt) {
                    return opt.classificacao.toUpperCase() !== "INDEFINIDO";
                });

                // Função para popular o select de exibição da Classificação
                function populateClassDisplaySelect(selectElem, options, initialValue) {
                    selectElem.innerHTML = "";
                    options.forEach(function(opt) {
                        var option = document.createElement("option");
                        option.value = opt.id;
                        option.text = opt.classificacao;
                        selectElem.appendChild(option);
                    });
                    if (initialValue) {
                        for (var i = 0; i < selectElem.options.length; i++) {
                            if (selectElem.options[i].value === initialValue) {
                                selectElem.selectedIndex = i;
                                break;
                            }
                        }
                    }
                }

                document.addEventListener("DOMContentLoaded", function() {
                    var atribElem = document.getElementById('tipo_atributo');
                    var defDisplay = document.getElementById('tipo_definicao_display');
                    var defHidden = document.getElementById('tipo_definicao');

                    var classDisplay = document.getElementById('fk_lgpd_classificacao_display');
                    var classHidden = document.getElementById('fk_lgpd_classificacao');

                    // Valores iniciais (caso edição)
                    var initialClassValue = classHidden.value; 
                    var initialDefValue = defHidden.value;

                    // Preenche classDisplay com base no valor inicial
                    function refreshClassDisplay() {
                        // Se EXCLUDENTE -> forçar INDEFINIDO
                        if (atribElem.value === "EXCLUDENTE") {
                            // Força "INDEFINIDO"
                            populateClassDisplaySelect(classDisplay, [indefinidoOption], indefinidoOption.id);
                            classDisplay.classList.add("readonly-select");
                            classHidden.value = indefinidoOption.id;
                        } else {
                            // INCLUSIVO -> PESSOAL, SENSÍVEL
                            populateClassDisplaySelect(classDisplay, inclusiveClassOptions, initialClassValue);
                            classDisplay.classList.remove("readonly-select");
                            // Se initialClassValue não está em [1,2], seleciona o primeiro
                            if (!inclusiveClassOptions.some(function(opt) { return opt.id === initialClassValue; })) {
                                // Força a 1ª
                                classHidden.value = inclusiveClassOptions[0].id;
                                classDisplay.value = inclusiveClassOptions[0].id;
                            } else {
                                classHidden.value = classDisplay.value;
                            }
                        }
                    }

                    // Sincroniza a classificação do select de exibição com o hidden
                    classDisplay.addEventListener('change', function() {
                        classHidden.value = classDisplay.value;
                    });

                    // Sincroniza a definição do select de exibição com o hidden
                    defDisplay.addEventListener('change', function() {
                        defHidden.value = defDisplay.value;
                    });

                    // Ao mudar a atribuição
                    atribElem.addEventListener('change', function() {
                        if (atribElem.value === "EXCLUDENTE") {
                            // Força ATRIBUTO na definição
                            defDisplay.value = "ATRIBUTO";
                            defDisplay.classList.add("readonly-select");
                            defHidden.value = "ATRIBUTO";

                        } else {
                            defDisplay.classList.remove("readonly-select");
                            // Mantém o valor que o usuário escolheu ou default
                            defHidden.value = defDisplay.value;
                        }
                        refreshClassDisplay();
                    });

                    // Inicial
                    // Se no load já estiver EXCLUDENTE, forçamos ATRIBUTO
                    if (atribElem.value === "EXCLUDENTE") {
                        defDisplay.value = "ATRIBUTO";
                        defHidden.value = "ATRIBUTO";
                        defDisplay.classList.add("readonly-select");
                    }
                    else {
                        defDisplay.classList.remove("readonly-select");
                    }
                    // Carrega Classificação
                    refreshClassDisplay();
                });
            </script>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
