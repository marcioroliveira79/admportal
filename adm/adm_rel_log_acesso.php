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
        $filtro_usuario = isset($_GET['fk_usuario']) ? (int)$_GET['fk_usuario'] : 0;
        $filtro_data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
        $filtro_data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';

        // Paginação
        $offset = ($pagina - 1) * $registros_por_pagina;

        // Consulta para contar total de registros
        $query_count = "SELECT COUNT(*) FROM administracao.adm_log_acesso WHERE 1=1";
        $params_count = [];
        if ($filtro_usuario > 0) {
            $query_count .= " AND fk_usuario = $" . (count($params_count) + 1);
            $params_count[] = $filtro_usuario;
        }
        if (!empty($filtro_data_inicio)) {
            $query_count .= " AND data_acesso >= $" . (count($params_count) + 1);
            $params_count[] = $filtro_data_inicio;
        }
        if (!empty($filtro_data_fim)) {
            $query_count .= " AND data_acesso <= $" . (count($params_count) + 1);
            $params_count[] = $filtro_data_fim;
        }

        $result_count = pg_query_params($conexao, $query_count, $params_count);
        $total_registros = (int)pg_fetch_result($result_count, 0, 0);
        $total_paginas = ceil($total_registros / $registros_por_pagina);

        // Consulta de logs com filtros
        $query_logs = "
            SELECT l.id, l.fk_usuario, l.data_acesso, l.data_saida, l.ip_acesso, l.metodo_logout, u.nome
            FROM administracao.adm_log_acesso l
            JOIN administracao.adm_usuario u ON u.id = l.fk_usuario
            WHERE 1=1
        ";
        $params_logs = [];
        if ($filtro_usuario > 0) {
            $query_logs .= " AND fk_usuario = $" . (count($params_logs) + 1);
            $params_logs[] = $filtro_usuario;
        }
        if (!empty($filtro_data_inicio)) {
            $query_logs .= " AND data_acesso >= $" . (count($params_logs) + 1);
            $params_logs[] = $filtro_data_inicio;
        }
        if (!empty($filtro_data_fim)) {
            $query_logs .= " AND data_acesso <= $" . (count($params_logs) + 1);
            $params_logs[] = $filtro_data_fim;
        }
        $query_logs .= " ORDER BY l.data_acesso DESC LIMIT $registros_por_pagina OFFSET $offset";

        $result = pg_query_params($conexao, $query_logs, $params_logs);

        if (!$result) {
            $mensagem = "Erro ao carregar os logs.";
            $erro_banco = pg_last_error($conexao);
        }

        // Consulta para carregar usuários
        $query_usuarios = "SELECT id, nome FROM administracao.adm_usuario ORDER BY nome ASC";
        $result_usuarios = pg_query($conexao, $query_usuarios);

        if (!$result_usuarios) {
            $mensagem = "Erro ao carregar usuários.";
            $erro_banco = pg_last_error($conexao);
        }

        $usuarios = pg_fetch_all($result_usuarios);

        // Exclusão de logs de um usuário
        if (isset($_GET['delete_logs']) && (int)$_GET['delete_logs'] > 0) {
            $delete_usuario = (int)$_GET['delete_logs'];
            $query_delete_logs = "DELETE FROM administracao.adm_log_acesso WHERE fk_usuario = $1";
            $result_delete_logs = pg_query_params($conexao, $query_delete_logs, [$delete_usuario]);
            $erro_banco = pg_last_error($conexao);

            if ($result_delete_logs) {
                $mensagem = "Todos os logs do usuário foram apagados.";
            } else {
                $mensagem = "Erro ao apagar os logs do usuário.";
            }
        }
        ?>
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
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Logs de Acesso</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body {
                    background-color: #f5f5f5;
                    font-family: Arial, sans-serif;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1 class="text-center"></h1>

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
                    <div class="col-md-4">
                        <label for="fk_usuario" class="form-label">Usuário</label>
                        <select name="fk_usuario" id="fk_usuario" class="form-select">
                            <option value="0">Todos</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $usuario['id'] ?>" <?= $filtro_usuario == $usuario['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($usuario['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="data_inicio" class="form-label">Data Início</label>
                        <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="<?= htmlspecialchars($filtro_data_inicio) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="data_fim" class="form-label">Data Fim</label>
                        <input type="date" name="data_fim" id="data_fim" class="form-control" value="<?= htmlspecialchars($filtro_data_fim) ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="registros" class="form-label">Registros por Página</label>
                        <input type="number" name="registros" id="registros" class="form-control" value="<?= $registros_por_pagina ?>" min="1">
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-success">Filtrar</button>
                        <a href="?acao=<?= htmlspecialchars($acao) ?>" class="btn btn-secondary">Limpar Filtros</a>
                    </div>
                </form>

                <!-- Tabela de Logs -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Data Acesso</th>
                            <th>Data Saída</th>
                            <th>IP Acesso</th>
                            <th>Método Logout</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($log = pg_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($log['id']) ?></td>
                                <td><?= htmlspecialchars($log['nome']) ?></td>
                                <td><?= htmlspecialchars($log['data_acesso']) ?></td>
                                <td><?= htmlspecialchars($log['data_saida'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($log['ip_acesso'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($log['metodo_logout'] ?? '-') ?></td>
                                <td>
                                    <a href="?acao=<?= htmlspecialchars($acao) ?>&delete_logs=<?= $log['fk_usuario'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja apagar todos os logs deste usuário?')">Apagar Logs</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- Paginação -->
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                            <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                                <a class="page-link" href="?acao=<?= htmlspecialchars($acao) ?>&pagina=<?= $i ?>&registros=<?= $registros_por_pagina ?>&fk_usuario=<?= $filtro_usuario ?>&data_inicio=<?= htmlspecialchars($filtro_data_inicio) ?>&data_fim=<?= htmlspecialchars($filtro_data_fim) ?>">
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
