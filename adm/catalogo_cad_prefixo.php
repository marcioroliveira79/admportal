<?php
session_start();

// Verifica se o usuário está logado e se a variável de ação ($acao) foi definida
$acao = isset($_GET['acao']) ? $_GET['acao'] : null;
if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {
    // Certifique-se de que a variável $conexao (conexão com o banco) está definida.
    // Exemplo:
    // $conexao = pg_connect("host=... dbname=... user=... password=...");

    // Valida o acesso do usuário à tela
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        // Inicializa variáveis
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $delete_id = isset($_GET['delete_id']) ? (int) $_GET['delete_id'] : 0;
        $mensagem = '';

        // Exclusão de registro
        if ($delete_id > 0) {
            // Primeiro, exclui as associações existentes
            $query_delete_assoc = "DELETE FROM administracao.catalog_ass_prefixo_tipo WHERE fk_catalogo_prefixo_tipo = $1";
            pg_query_params($conexao, $query_delete_assoc, [$delete_id]);

            $query_delete = "DELETE FROM administracao.catalog_prefixo_tipo WHERE id = $1";
            $result_delete = pg_query_params($conexao, $query_delete, [$delete_id]);
            $erro_banco = pg_last_error($conexao);
            if ($result_delete) {
                $mensagem = "Registro excluído com sucesso!";
                // Redireciona para evitar reprocessamento
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
                    prefixo, 
                    dominio, 
                    comentario,
                    fk_usuario_criador,
                    fk_usuario_alteracao,
                    TO_CHAR(data_criacao, 'DD-MM-YYYY HH24:MI') AS data_criacao,
                    TO_CHAR(data_alteracao, 'DD-MM-YYYY HH24:MI') AS data_alteracao
                FROM administracao.catalog_prefixo_tipo
                WHERE id = $1
            ";
            $result = pg_query_params($conexao, $query, [$id]);
            if (!$result || pg_num_rows($result) === 0) {
                die("Registro não encontrado.");
            }
            $registro = pg_fetch_assoc($result);

            // Carrega os tipos de dado associados ao prefixo
            $query_assoc = "SELECT fk_catalogo_tipo_dado FROM administracao.catalog_ass_prefixo_tipo WHERE fk_catalogo_prefixo_tipo = $1";
            $result_assoc = pg_query_params($conexao, $query_assoc, [$id]);
            $assoc = [];
            if ($result_assoc && pg_num_rows($result_assoc) > 0) {
                while ($row = pg_fetch_assoc($result_assoc)) {
                    $assoc[] = $row['fk_catalogo_tipo_dado'];
                }
            }
        }

        // Processa o formulário para inserção ou atualização
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Captura e sanitiza os dados do formulário
            $prefixo    = htmlspecialchars($_POST['prefixo']);
            $dominio    = htmlspecialchars($_POST['dominio']);
            $comentario = htmlspecialchars($_POST['comentario']);
            $fk_usuario = $_SESSION['global_id_usuario']; // Usuário logado

            // Os tipos de dado selecionados serão enviados via o select "selected"
            $tipo_dados = isset($_POST['tipo_dado']) ? $_POST['tipo_dado'] : [];

            // Validação: verifica se os tipos de dado selecionados pertencem à mesma tecnologia
            if (!empty($tipo_dados)) {
                $ids = implode(',', array_map('intval', $tipo_dados));
                $query_validacao = "
                    SELECT COUNT(DISTINCT t.id) AS qtd_tecnologias
                    FROM administracao.catalog_tipo_dado d
                    JOIN administracao.catalog_tipo_tecnlogia t ON d.fk_tipo_tecnologia = t.id
                    WHERE d.id IN ($ids)
                ";
                $result_validacao = pg_query($conexao, $query_validacao);
                if ($result_validacao) {
                    $row_validacao = pg_fetch_assoc($result_validacao);
                    if ((int)$row_validacao['qtd_tecnologias'] > 1) {
                        $mensagem = "Erro: Não é permitido associar tipos de dado de tecnologias diferentes.";
                    }
                }
            }

            if (empty($mensagem)) {
                if ($id > 0) {
                    // Atualização: atualiza o registro existente
                    $query_update = "
                        UPDATE administracao.catalog_prefixo_tipo
                        SET prefixo = $1,
                            dominio = $2,
                            comentario = $3,
                            fk_usuario_alteracao = $4,
                            data_alteracao = now()
                        WHERE id = $5
                    ";
                    $result_update = pg_query_params($conexao, $query_update, [$prefixo, $dominio, $comentario, $fk_usuario, $id]);
                    if ($result_update) {
                        // Atualiza as associações: apaga as existentes e insere as novas
                        $query_delete_assoc = "DELETE FROM administracao.catalog_ass_prefixo_tipo WHERE fk_catalogo_prefixo_tipo = $1";
                        pg_query_params($conexao, $query_delete_assoc, [$id]);
                        if (!empty($tipo_dados) && is_array($tipo_dados)) {
                            foreach ($tipo_dados as $td) {
                                $query_insert_assoc = "INSERT INTO administracao.catalog_ass_prefixo_tipo (fk_catalogo_prefixo_tipo, fk_catalogo_tipo_dado) VALUES ($1, $2)";
                                pg_query_params($conexao, $query_insert_assoc, [$id, (int)$td]);
                            }
                        }
                        $mensagem = "Registro atualizado com sucesso!";
                        // Redireciona para recarregar os dados atualizados (PRG)
                        geraArquivoPrefixo($conexao);
                        header("Location: index.php?acao=" . $acao . "&id=" . $id . "&mensagem=" . urlencode($mensagem));
                        exit;
                    } else {
                        $mensagem = "Erro ao atualizar o registro: " . pg_last_error($conexao);
                    }
                } else {
                    // Inserção: cadastra um novo registro e obtém o ID gerado
                    $query_insert = "
                        INSERT INTO administracao.catalog_prefixo_tipo
                            (prefixo, dominio, comentario, fk_usuario_criador, data_criacao)
                        VALUES ($1, $2, $3, $4, now())
                        RETURNING id
                    ";
                    $result_insert = pg_query_params($conexao, $query_insert, [$prefixo, $dominio, $comentario, $fk_usuario]);
                    if ($result_insert) {
                        $row = pg_fetch_assoc($result_insert);
                        $new_id = $row['id'];
                        // Insere as associações, se houver
                        if (!empty($tipo_dados) && is_array($tipo_dados)) {
                            foreach ($tipo_dados as $td) {
                                $query_insert_assoc = "INSERT INTO administracao.catalog_ass_prefixo_tipo (fk_catalogo_prefixo_tipo, fk_catalogo_tipo_dado) VALUES ($1, $2)";
                                pg_query_params($conexao, $query_insert_assoc, [$new_id, (int)$td]);
                            }
                        }
                        $mensagem = "Registro inserido com sucesso!";
                        geraArquivoPrefixo($conexao);
                        // Redireciona após a inserção
                        header("Location: index.php?acao=" . $acao . "&mensagem=" . urlencode($mensagem));
                        exit;
                    } else {
                        $mensagem = "Erro ao inserir o registro: " . pg_last_error($conexao);
                    }
                }
            }
        }

        // ===============================
        // INÍCIO: PAGINAÇÃO E BUSCA
        // ===============================
        // Parâmetros para busca e paginação
        $busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) { $page = 1; }
        $limit = 10; // Registros por página
        $offset = ($page - 1) * $limit;

        // Construir cláusula WHERE para busca
        $whereClause = "";
        $params = [];
        if (!empty($busca)) {
            $whereClause = "WHERE (p.prefixo ILIKE $1 OR p.dominio ILIKE $1 OR p.comentario ILIKE $1)";
            $params[] = '%' . $busca . '%';
        }

        // Consulta para contar o total de registros (para paginação)
        $query_count = "SELECT COUNT(*) as total FROM administracao.catalog_prefixo_tipo p " . $whereClause;
        $result_count = pg_query_params($conexao, $query_count, $params);
        $total_records = 0;
        if ($result_count) {
            $row_count = pg_fetch_assoc($result_count);
            $total_records = (int)$row_count['total'];
        }
        $total_pages = ceil($total_records / $limit);

        // Consulta para listar os registros com os tipos associados, aplicando a busca e a paginação
        $query_list = "
            SELECT 
                p.id,
                p.prefixo,
                p.dominio,
                p.comentario,
                TO_CHAR(p.data_criacao, 'DD-MM-YYYY HH24:MI') AS data_criacao,
                COALESCE(string_agg(t.tipo || ' (' || tt.tecnlogia || ')', ', '), '') AS tipos
            FROM administracao.catalog_prefixo_tipo p
            LEFT JOIN administracao.catalog_ass_prefixo_tipo ap ON p.id = ap.fk_catalogo_prefixo_tipo
            LEFT JOIN administracao.catalog_tipo_dado t ON ap.fk_catalogo_tipo_dado = t.id
            LEFT JOIN administracao.catalog_tipo_tecnlogia tt ON t.fk_tipo_tecnologia = tt.id
            " . $whereClause . "
            GROUP BY p.id, p.prefixo, p.dominio, p.comentario, p.data_criacao
            ORDER BY p.id ASC
            LIMIT $limit OFFSET $offset
        ";
        $result_list = pg_query_params($conexao, $query_list, $params);
        if (!$result_list) {
            die("Erro ao carregar a lista de registros: " . pg_last_error($conexao));
        }
        // ===============================
        // FIM: PAGINAÇÃO E BUSCA
        // ===============================

        // Consulta para carregar a lista de tipos de dado (para preencher a dual list box)
        $query_tipo_dado = "
            SELECT d.id, d.tipo, t.tecnlogia 
            FROM administracao.catalog_tipo_dado d
            LEFT JOIN administracao.catalog_tipo_tecnlogia t ON d.fk_tipo_tecnologia = t.id
            ORDER BY d.tipo ASC
        ";
        $result_tipo_dado = pg_query($conexao, $query_tipo_dado);
        if (!$result_tipo_dado) {
            die("Erro ao carregar a lista de tipos de dado: " . pg_last_error($conexao));
        }
        $tipos_dado = pg_fetch_all($result_tipo_dado);
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Cadastro de Prefixo de Tipo</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <script>
                if (window.top === window.self) {
                    // Se a página não estiver sendo exibida dentro de um iframe, redireciona para o index
                    window.location.href = 'index.php';
                }
            </script>
            <style>
                /* Ajusta a largura mínima da coluna "Data de Criação" (6ª coluna) */
                .table-listing th:nth-child(6),
                .table-listing td:nth-child(6) {
                    min-width: 150px;  /* ajuste para o valor desejado */
                    text-align: center;
                }

                /* Ajusta a largura mínima da coluna "Ações" (7ª coluna) */
                .table-listing th:nth-child(7),
                .table-listing td:nth-child(7) {
                    min-width: 120px;  /* ajuste para o valor desejado */
                    text-align: center;
                }
                .table-listing {
                    table-layout: fixed;
                    width: 100%;
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
            </style>
            <script>
                // Função para mover opções entre as caixas
                function moveOptions(fromId, toId) {
                    var fromBox = document.getElementById(fromId);
                    var toBox = document.getElementById(toId);
                    for (var i = fromBox.options.length - 1; i >= 0; i--) {
                        if (fromBox.options[i].selected) {
                            var option = fromBox.options[i];
                            var newOption = new Option(option.text, option.value);
                            toBox.add(newOption);
                            fromBox.remove(i);
                        }
                    }
                }
                // Função para selecionar todas as opções da caixa "selected" antes do envio do formulário
                function selectAllOptions(selectId) {
                    var selectBox = document.getElementById(selectId);
                    for (var i = 0; i < selectBox.options.length; i++) {
                        selectBox.options[i].selected = true;
                    }
                }
            </script>
        </head>
        <body>
            <br>
            <div class="container">
                <?php if (!empty($_GET['mensagem'])): ?>
                    <div class="alert alert-info alert-custom text-center">
                        <?= htmlspecialchars($_GET['mensagem']) ?>
                    </div>
                <?php elseif (!empty($mensagem)): ?>
                    <div class="alert alert-info alert-custom text-center">
                        <?= htmlspecialchars($mensagem) ?>
                    </div>
                <?php endif; ?>

                <?php if ($id > 0): ?>
                    <!-- Formulário de Edição -->
                    <div class="form-container">
                        <h2 class="mb-4">Editar Prefixo de Tipo</h2>
                        <form method="POST" onsubmit="selectAllOptions('selected');">
                            <div class="mb-3">
                                <label for="prefixo" class="form-label">Prefixo</label>
                                <input type="text" class="form-control" id="prefixo" name="prefixo" maxlength="100" value="<?= htmlspecialchars($registro['prefixo']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="dominio" class="form-label">Domínio</label>
                                <input type="text" class="form-control" id="dominio" name="dominio" maxlength="100" value="<?= htmlspecialchars($registro['dominio']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="comentario" class="form-label">Comentário</label>
                                <input type="text" class="form-control" id="comentario" name="comentario" maxlength="255" value="<?= htmlspecialchars($registro['comentario']) ?>" required>
                            </div>
                            <!-- Dual list box para seleção de Tipos de Dado -->
                            <div class="mb-3">
                                <label class="form-label">Tipos de Dado Associados</label>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label class="form-label">Disponíveis</label>
                                        <select id="available" class="form-select" multiple style="height:200px;">
                                            <?php
                                            if (isset($assoc)) {
                                                foreach ($tipos_dado as $td) {
                                                    if (!in_array($td['id'], $assoc)) {
                                                        echo '<option value="' . $td['id'] . '">'
                                                             . htmlspecialchars($td['tecnlogia']) . " - " . htmlspecialchars($td['tipo'])
                                                             . '</option>';
                                                    }
                                                }
                                            } else {
                                                foreach ($tipos_dado as $td) {
                                                    echo '<option value="' . $td['id'] . '">'
                                                         . htmlspecialchars($td['tecnlogia']) . " - " . htmlspecialchars($td['tipo'])
                                                         . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 text-center align-self-center">
                                        <button type="button" class="btn btn-sm btn-edit" onclick="moveOptions('available','selected')">&gt;&gt;</button>
                                        <br>
                                        <br>                                        
                                        <button type="button" class="btn btn-sm btn-edit" onclick="moveOptions('selected','available')">&lt;&lt;</button>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Selecionados</label>
                                        <select id="selected" name="tipo_dado[]" class="form-select" multiple style="height:200px;">
                                            <?php
                                            if (isset($assoc)) {
                                                foreach ($tipos_dado as $td) {
                                                    if (in_array($td['id'], $assoc)) {
                                                        echo '<option value="' . $td['id'] . '">'
                                                             . htmlspecialchars($td['tecnlogia']) . " - " . htmlspecialchars($td['tipo'])
                                                             . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Use os botões para mover os tipos de dado entre as caixas.</small>
                            </div>
                            <button type="submit" class="btn btn-edit btn-lg">Salvar</button>
                            <a href="index.php?acao=<?= $acao ?>" class="btn btn-secondary btn-lg">Voltar</a>
                        </form>
                    </div>
                <?php else: ?>
                    <!-- Formulário de Cadastro -->
                    <div class="form-container">
                        <h2 class="mb-4">Prefixo</h2>
                        <form method="POST" onsubmit="selectAllOptions('selected');">
                            <div class="mb-3">
                                <label for="prefixo" class="form-label">Prefixo</label>
                                <input type="text" class="form-control" id="prefixo" name="prefixo" maxlength="100" required>
                            </div>
                            <div class="mb-3">
                                <label for="dominio" class="form-label">Domínio</label>
                                <input type="text" class="form-control" id="dominio" name="dominio" maxlength="100" required>
                            </div>
                            <div class="mb-3">
                                <label for="comentario" class="form-label">Comentário</label>
                                <input type="text" class="form-control" id="comentario" name="comentario" maxlength="255" required>
                            </div>
                            <!-- Dual list box para seleção de Tipos de Dado -->
                            <div class="mb-3">
                                <label class="form-label">Tipos de Dado Associados</label>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label class="form-label">Disponíveis</label>
                                        <select id="available" class="form-select" multiple style="height:200px;">
                                            <?php
                                            foreach ($tipos_dado as $td) {
                                                echo '<option value="' . $td['id'] . '">'
                                                     . htmlspecialchars($td['tecnlogia']) . " - " . htmlspecialchars($td['tipo'])
                                                     . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 text-center align-self-center">
                                        <button type="button" class="btn btn-sm btn-edit" onclick="moveOptions('available','selected')">&gt;&gt;</button>
                                        <br>
                                        <br>                                        
                                        <button type="button" class="btn btn-sm btn-edit" onclick="moveOptions('selected','available')">&lt;&lt;</button>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Selecionados</label>
                                        <select id="selected" name="tipo_dado[]" class="form-select" multiple style="height:200px;">
                                            <!-- Inicialmente, nenhum item está selecionado -->
                                        </select>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Use os botões para mover os tipos de dado entre as caixas.</small>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg">Cadastrar</button>
                        </form>
                    </div>

                    <!-- Área de Busca -->
                    <div class="form-container">
                        <form method="GET" class="row g-3">
                            <input type="hidden" name="acao" value="<?= htmlspecialchars($acao) ?>">
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="busca" placeholder="Buscar por prefixo, domínio ou comentário" value="<?= htmlspecialchars($busca) ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success btn-lg">Buscar</button>
                            </div>
                        </form>
                    </div>

                    <!-- Lista de Registros com Paginação -->
                    <div class="table-container">
                        <h2 class="mb-4">Lista de Registros</h2>
                        <table class="table-listing">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Prefixo</th>
                                    <th>Domínio</th>
                                    <th>Comentário</th>
                                    <th>Tipos de Dado</th>
                                    <th>Data de Criação</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = pg_fetch_assoc($result_list)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['id']) ?></td>
                                        <td><?= htmlspecialchars($row['prefixo']) ?></td>
                                        <td><?= htmlspecialchars($row['dominio']) ?></td>
                                        <td><?= htmlspecialchars($row['comentario']) ?></td>
                                        <td><?= htmlspecialchars($row['tipos']) ?></td>
                                        <td><?= htmlspecialchars($row['data_criacao']) ?></td>
                                        <td>
                                            <a href="index.php?acao=<?= $acao ?>&id=<?= $row['id'] ?>" class="btn btn-sm btn-edit">Editar</a>
                                            <a href="index.php?acao=<?= $acao ?>&delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Tem certeza que deseja excluir este registro?')">Excluir</a>
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
                                <a href="index.php?acao=<?= $acao ?>&busca=<?= urlencode($busca) ?>&page=<?= $page - 1 ?>">&laquo; Anterior</a>
                            <?php endif; ?>

                            <?php
                                // Exibe links para cada página (pode ser ajustado para exibir um range menor, se necessário)
                                for ($i = 1; $i <= $total_pages; $i++):
                            ?>
                                <a href="index.php?acao=<?= $acao ?>&busca=<?= urlencode($busca) ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="index.php?acao=<?= $acao ?>&busca=<?= urlencode($busca) ?>&page=<?= $page + 1 ?>">Próximo &raquo;</a>
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
