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

        // Exclusão de registro de Tipo de Dado
        if ($delete_id > 0) {
            $query_delete = "DELETE FROM administracao.catalog_tipo_dado WHERE id = $1";
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
                    tipo, 
                    fk_tipo_tecnologia,
                    TO_CHAR(data_criacao, 'DD-MM-YYYY HH24:MI') AS data_criacao,
                    TO_CHAR(data_alteracao, 'DD-MM-YYYY HH24:MI') AS data_alteracao
                FROM administracao.catalog_tipo_dado
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
            $tipo = htmlspecialchars($_POST['tipo']);
            $fk_tipo_tecnologia = (int) $_POST['fk_tipo_tecnologia'];
            $fk_usuario = $_SESSION['global_id_usuario']; // Usuário logado

            if ($id > 0) {
                // Atualização: atualiza o registro existente
                $query_update = "
                    UPDATE administracao.catalog_tipo_dado
                    SET tipo = $1,
                        fk_tipo_tecnologia = $2,
                        fk_usuario_alteracao = $3,
                        data_alteracao = now()
                    WHERE id = $4
                ";
                $result_update = pg_query_params($conexao, $query_update, [$tipo, $fk_tipo_tecnologia, $fk_usuario, $id]);
                if ($result_update) {
                    $mensagem = "Registro atualizado com sucesso!";
                } else {
                    $mensagem = "Erro ao atualizar o registro: " . pg_last_error($conexao);
                }
            } else {
                // Inserção: cadastra um novo registro
                $query_insert = "
                    INSERT INTO administracao.catalog_tipo_dado
                        (tipo, fk_tipo_tecnologia, fk_usuario_criador, data_criacao)
                    VALUES ($1, $2, $3, now())
                ";
                $result_insert = pg_query_params($conexao, $query_insert, [$tipo, $fk_tipo_tecnologia, $fk_usuario]);
                if ($result_insert) {
                    $mensagem = "Registro inserido com sucesso!";
                } else {
                    $mensagem = "Erro ao inserir o registro: " . pg_last_error($conexao);
                }
            }
        }

        // Consulta para listar todos os registros (fazendo join para exibir a tecnologia associada)
        $query_list = "
            SELECT 
                d.id, 
                d.tipo, 
                d.fk_tipo_tecnologia,
                t.tecnlogia AS tecnologia,
                t.versao AS versao,
                TO_CHAR(d.data_criacao, 'DD-MM-YYYY HH24:MI') AS data_criacao
            FROM administracao.catalog_tipo_dado d
            LEFT JOIN administracao.catalog_tipo_tecnlogia t ON d.fk_tipo_tecnologia = t.id
            ORDER BY d.id ASC
        ";
        $result_list = pg_query($conexao, $query_list);
        if (!$result_list) {
            die("Erro ao carregar a lista de registros: " . pg_last_error($conexao));
        }

        // Consulta para carregar a lista de Tecnologias (para preencher o select)
        $query_tecnologias = "SELECT id, tecnlogia, versao FROM administracao.catalog_tipo_tecnlogia ORDER BY tecnlogia ASC";
        $result_tecnologias = pg_query($conexao, $query_tecnologias);
        if (!$result_tecnologias) {
            die("Erro ao carregar a lista de tecnologias: " . pg_last_error($conexao));
        }
        $tecnologias = pg_fetch_all($result_tecnologias);
        ?>

        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Tipo de Dado</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
                        <h2 class="mb-4">Editar Tipo de Dado</h2>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo</label>
                                <input type="text" class="form-control" id="tipo" name="tipo" maxlength="100" value="<?= htmlspecialchars($registro['tipo']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="fk_tipo_tecnologia" class="form-label">Tipo de Tecnologia</label>
                                <select class="form-select" id="fk_tipo_tecnologia" name="fk_tipo_tecnologia" required>
                                    <option value="">Selecione</option>
                                    <?php if ($tecnologias): ?>
                                        <?php foreach ($tecnologias as $tec): ?>
                                            <option value="<?= $tec['id'] ?>" <?= ($id > 0 && $registro['fk_tipo_tecnologia'] == $tec['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($tec['tecnlogia']) . " - " . htmlspecialchars($tec['versao']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-edit btn-lg">Salvar</button>
                            <a href="index.php?acao=<?= $acao ?>" class="btn btn-secondary btn-lg">Voltar</a>
                        </form>
                    </div>
                <?php else: ?>
                    <!-- Formulário de Cadastro -->
                    <div class="form-container">
                        <h2 class="mb-4">Cadastro de Tipo de Dado</h2>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo</label>
                                <input type="text" class="form-control" id="tipo" name="tipo" maxlength="100" required>
                            </div>
                            <div class="mb-3">
                                <label for="fk_tipo_tecnologia" class="form-label">Tipo de Tecnologia</label>
                                <select class="form-select" id="fk_tipo_tecnologia" name="fk_tipo_tecnologia" required>
                                    <option value="">Selecione</option>
                                    <?php if ($tecnologias): ?>
                                        <?php foreach ($tecnologias as $tec): ?>
                                            <option value="<?= $tec['id'] ?>">
                                                <?= htmlspecialchars($tec['tecnlogia']) . " - " . htmlspecialchars($tec['versao']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
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
                                    <th>Tipo</th>
                                    <th>Tipo de Tecnologia</th>
                                    <th>Data de Criação</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = pg_fetch_assoc($result_list)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['id']) ?></td>
                                        <td><?= htmlspecialchars($row['tipo']) ?></td>
                                        <td>
                                            <?= htmlspecialchars($row['tecnologia']) . " - " . htmlspecialchars($row['versao']) ?>
                                        </td>
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
        <BR>
        <BR>
        <BR>
        <BR>
        </html>
        <?php
    } elseif ($acesso != "TELA AUTORIZADA") {
        @include("html/403.html");
    }
} else {
    header("Location: login.php");
}
?>
