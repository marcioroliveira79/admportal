<?php
session_start();

if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        // Inicialização de variáveis
        $mensagem = '';
        $erro_banco = '';
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $registros_por_pagina = isset($_GET['registros']) ? (int)$_GET['registros'] : 10;
        $data_base    = isset($_GET['data_base']) ? trim($_GET['data_base']) : '';
        $date_collect = isset($_GET['date_collect']) ? trim($_GET['date_collect']) : '';
        $service_name = isset($_GET['service_name']) ? trim($_GET['service_name']) : '';
        $schema_name  = isset($_GET['schema_name']) ? trim($_GET['schema_name']) : '';
        $column_name  = isset($_GET['column_name']) ? trim($_GET['column_name']) : '';

        // Paginação
        $offset = ($pagina - 1) * $registros_por_pagina;

        // Consulta para contar total de registros com os filtros
        $query_count = "SELECT COUNT(*) FROM administracao.catalog_table_content WHERE 1=1";
        $params_count = [];
        if (!empty($data_base)) {
            $query_count .= " AND data_base = $" . (count($params_count) + 1);
            $params_count[] = $data_base;
        }
        if (!empty($date_collect)) {
            $query_count .= " AND date(date_collect) = $" . (count($params_count) + 1);
            $params_count[] = $date_collect;
        }
        if (!empty($service_name)) {
            $query_count .= " AND service_name = $" . (count($params_count) + 1);
            $params_count[] = $service_name;
        }
        if (!empty($schema_name)) {
            $query_count .= " AND schema_name = $" . (count($params_count) + 1);
            $params_count[] = $schema_name;
        }
        if (!empty($column_name)) {
            $query_count .= " AND column_name ILIKE '%' || $" . (count($params_count) + 1) . " || '%'";
            $params_count[] = $column_name;
        }
        $result_count = pg_query_params($conexao, $query_count, $params_count);
        $total_registros = (int)pg_fetch_result($result_count, 0, 0);
        $total_paginas = ceil($total_registros / $registros_por_pagina);

        // Consulta para carregar os registros da tabela com filtros e paginação
        $query = "SELECT * FROM administracao.catalog_table_content WHERE 1=1";
        $params = [];
        if (!empty($data_base)) {
            $query .= " AND data_base = $" . (count($params) + 1);
            $params[] = $data_base;
        }
        if (!empty($date_collect)) {
            $query .= " AND date(date_collect) = $" . (count($params) + 1);
            $params[] = $date_collect;
        }
        if (!empty($service_name)) {
            $query .= " AND service_name = $" . (count($params) + 1);
            $params[] = $service_name;
        }
        if (!empty($schema_name)) {
            $query .= " AND schema_name = $" . (count($params) + 1);
            $params[] = $schema_name;
        }
        if (!empty($column_name)) {
            $query .= " AND column_name ILIKE '%' || $" . (count($params) + 1) . " || '%'";
            $params[] = $column_name;
        }
        $query .= " ORDER BY date_collect DESC LIMIT $registros_por_pagina OFFSET $offset";

        $result = pg_query_params($conexao, $query, $params);
        if (!$result) {
            $mensagem = "Erro ao carregar os registros.";
            $erro_banco = pg_last_error($conexao);
        }
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Catalog Table Content</title>
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
                .btn-green {
                    background-color: #28a745;
                    color: white;
                    transition: background-color 0.3s ease;
                }
                .btn-green:hover {
                    background-color: #218838;
                    color: white;
                }
                .pagination .page-link {
                    background-color: #28a745;
                    color: white;
                    border: 1px solid #218838;
                    transition: background-color 0.3s ease;
                }
                .pagination .page-link:hover {
                    background-color: #218838;
                    color: white;
                }
                .pagination .active .page-link {
                    background-color: #218838;
                    color: white;
                    border-color: #218838;
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

                <!-- Filtros -->
                <form method="GET" class="row g-3">
                    <input type="hidden" name="acao" value="<?= htmlspecialchars($acao) ?>">
                    <div class="col-md-3">
                        <label for="data_base" class="form-label">Data Base</label>
                        <input type="text" name="data_base" id="data_base" class="form-control" value="<?= htmlspecialchars($data_base) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="date_collect" class="form-label">Data da Coleta</label>
                        <input type="date" name="date_collect" id="date_collect" class="form-control" value="<?= htmlspecialchars($date_collect) ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="service_name" class="form-label">Service Name</label>
                        <input type="text" name="service_name" id="service_name" class="form-control" value="<?= htmlspecialchars($service_name) ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="schema_name" class="form-label">Schema Name</label>
                        <input type="text" name="schema_name" id="schema_name" class="form-control" value="<?= htmlspecialchars($schema_name) ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="column_name" class="form-label">Column Name</label>
                        <input type="text" name="column_name" id="column_name" class="form-control" value="<?= htmlspecialchars($column_name) ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="registros" class="form-label">Registros por Página</label>
                        <input type="number" name="registros" id="registros" class="form-control" value="<?= $registros_por_pagina ?>" min="1">
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-green">Filtrar</button>
                        <a href="?acao=<?= htmlspecialchars($acao) ?>" class="btn btn-secondary">Limpar Filtros</a>
                    </div>
                </form>

                <!-- Tabela de Resultados -->
                <table class="table table-striped mt-4">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data Base</th>
                            <th>Host Name</th>
                            <th>Service Name</th>
                            <th>Schema Name</th>
                            <th>Table Name</th>
                            <th>Table Comments</th>
                            <th>Column Name</th>
                            <th>Data Type</th>
                            <th>Data Length</th>
                            <th>Column ID</th>
                            <th>Column Comments</th>
                            <th>Date Collect</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = pg_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['data_base']) ?></td>
                                <td><?= htmlspecialchars($row['host_name']) ?></td>
                                <td><?= htmlspecialchars($row['service_name']) ?></td>
                                <td><?= htmlspecialchars($row['schema_name']) ?></td>
                                <td><?= htmlspecialchars($row['table_name']) ?></td>
                                <td><?= htmlspecialchars($row['table_comments']) ?></td>
                                <td><?= htmlspecialchars($row['column_name']) ?></td>
                                <td><?= htmlspecialchars($row['data_type']) ?></td>
                                <td><?= htmlspecialchars($row['data_length']) ?></td>
                                <td><?= htmlspecialchars($row['column_id']) ?></td>
                                <td><?= htmlspecialchars($row['column_comments']) ?></td>
                                <td><?= htmlspecialchars($row['date_collect']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- Paginação -->
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                            <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                                <a class="page-link" href="?acao=<?= htmlspecialchars($acao) ?>&pagina=<?= $i ?>&registros=<?= $registros_por_pagina ?>&data_base=<?= urlencode($data_base) ?>&date_collect=<?= urlencode($date_collect) ?>&service_name=<?= urlencode($service_name) ?>&schema_name=<?= urlencode($schema_name) ?>&column_name=<?= urlencode($column_name) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
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
