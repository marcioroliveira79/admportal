<?php
session_start();
require_once("../module/conecta.php");
require_once("../module/functions.php");

if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && isset($acao) && $acao != null) {

    // Verifica se o usuário tem acesso à tela
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {

        // Inicializa variáveis de erro e mensagem
        $mensagem = '';
        $erro_banco = '';

        // Recupera o ambiente selecionado; se não informado, utiliza "todos"
        $selected_env = isset($_GET['ambiente']) ? trim($_GET['ambiente']) : "todos";

        // Consulta para carregar os ambientes disponíveis (para o combo)
        $query_env = "SELECT DISTINCT ambiente FROM administracao.catalog_table_content ORDER BY ambiente";
        $result_env = pg_query($conexao, $query_env);
        if (!$result_env) {
            $erro_banco = pg_last_error($conexao);
        }
        $environments = pg_fetch_all($result_env);

        // Se for "todos", as consultas serão realizadas sem filtro específico; caso contrário, filtra pelo ambiente selecionado.
        if ($selected_env === "todos") {
            // 1. Número de Service Names por Ambiente (para cada ambiente)
            $query_service = "
                SELECT ambiente, COUNT(DISTINCT service_name) AS total_services
                FROM administracao.catalog_table_content
                GROUP BY ambiente
                ORDER BY ambiente
            ";
            $result_service = pg_query($conexao, $query_service);
            $services_data = pg_fetch_all($result_service);

            // 2. Número de Schemas por Service (para cada ambiente e service)
            $query_schema = "
                SELECT ambiente, service_name, COUNT(DISTINCT schema_name) AS total_schemas
                FROM administracao.catalog_table_content
                GROUP BY ambiente, service_name
                ORDER BY ambiente, service_name
            ";
            $result_schema = pg_query($conexao, $query_schema);
            $schemas_data = pg_fetch_all($result_schema);

            // 3. Número de Tabelas por Schema e Total de Atributos por Schema (para cada ambiente, service e schema)
            $query_table = "
                SELECT ambiente, service_name, schema_name, 
                       COUNT(DISTINCT table_name) AS total_tables, 
                       COUNT(*) AS total_attributes
                FROM administracao.catalog_table_content
                GROUP BY ambiente, service_name, schema_name
                ORDER BY ambiente, service_name, schema_name
            ";
            $result_table = pg_query($conexao, $query_table);
            $tables_data = pg_fetch_all($result_table);
        } else {
            // Ambiente específico selecionado
            // 1. Número de Service Names para o ambiente selecionado
            $query_service = "
                SELECT ambiente, COUNT(DISTINCT service_name) AS total_services
                FROM administracao.catalog_table_content
                WHERE ambiente = $1
                GROUP BY ambiente
            ";
            $result_service = pg_query_params($conexao, $query_service, [$selected_env]);
            $services_data = pg_fetch_all($result_service);

            // 2. Número de Schemas por Service para o ambiente selecionado
            $query_schema = "
                SELECT service_name, COUNT(DISTINCT schema_name) AS total_schemas
                FROM administracao.catalog_table_content
                WHERE ambiente = $1
                GROUP BY service_name
                ORDER BY service_name
            ";
            $result_schema = pg_query_params($conexao, $query_schema, [$selected_env]);
            $schemas_data = pg_fetch_all($result_schema);

            // 3. Número de Tabelas por Schema e Total de Atributos por Schema para o ambiente selecionado
            $query_table = "
                SELECT service_name, schema_name, 
                       COUNT(DISTINCT table_name) AS total_tables, 
                       COUNT(*) AS total_attributes
                FROM administracao.catalog_table_content
                WHERE ambiente = $1
                GROUP BY service_name, schema_name
                ORDER BY service_name, schema_name
            ";
            $result_table = pg_query_params($conexao, $query_table, [$selected_env]);
            $tables_data = pg_fetch_all($result_table);
        }

        // Calcula os totais gerais para Tabelas e Atributos na seção 3
        $total_tables_sum = 0;
        $total_attributes_sum = 0;
        if ($tables_data) {
            foreach ($tables_data as $row) {
                $total_tables_sum += (int)$row['total_tables'];
                $total_attributes_sum += (int)$row['total_attributes'];
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Objetos</title>
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { 
                    background-color: #f5f5f5; 
                    font-family: Arial, sans-serif; 
                }
                .container { 
                    margin-top: 40px; 
                }
                .card { 
                    margin-bottom: 20px; 
                }
                table { 
                    width: 100%; 
                }
                th, td { 
                    padding: 10px; 
                    border: 1px solid #ddd; 
                }
                th { 
                    background-color: #f8f9fa; 
                }
                /* Container para a tabela com barra de rolagem */
                .table-container {
                    max-height: 70vh; /* 70% da altura da viewport */
                    overflow-y: auto;
                    padding-bottom: 20px; /* Espaço extra para evitar corte de dados */
                }
            </style>
        </head>
        <body>
        <div class="container">
            <h2 class="text-center mb-4">Objetos de Banco</h2>
            <!-- Formulário de Filtros com parâmetro acao -->
            <form method="GET" class="mb-4" action="">
                <input type="hidden" name="acao" value="<?= htmlspecialchars($acao) ?>">
                <div class="row">
                    <div class="col-md-4">
                        <label for="ambiente" class="form-label">Selecione o Ambiente</label>
                        <select name="ambiente" id="ambiente" class="form-select">
                            <option value="todos" <?= ($selected_env == "todos") ? "selected" : "" ?>>Todos</option>
                            <?php if ($environments): ?>
                                <?php foreach ($environments as $env): ?>
                                    <option value="<?= htmlspecialchars($env['ambiente']) ?>" <?= ($selected_env == $env['ambiente']) ? "selected" : "" ?>>
                                        <?= htmlspecialchars($env['ambiente']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success">Filtrar</button>
                    </div>
                </div>
            </form>

            <!-- 1. Serviços por Ambiente -->
            <div class="card">
                <div class="card-header">
                    <h5>Serviços por Ambiente</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <?php if ($selected_env === "todos"): ?>
                                    <th>Ambiente</th>
                                <?php endif; ?>
                                <th>Total de Service Names</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($services_data): ?>
                                <?php foreach ($services_data as $row): ?>
                                    <tr>
                                        <?php if ($selected_env === "todos"): ?>
                                            <td><?= htmlspecialchars($row['ambiente']) ?></td>
                                        <?php endif; ?>
                                        <td><?= htmlspecialchars($row['total_services']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= ($selected_env === "todos") ? 2 : 1 ?>">Nenhum dado encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. Schemas por Service -->
            <div class="card">
                <div class="card-header">
                    <h5>Schemas por Service</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <?php if ($selected_env === "todos"): ?>
                                    <th>Ambiente</th>
                                <?php endif; ?>
                                <th>Service Name</th>
                                <th>Total de Schemas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($schemas_data): ?>
                                <?php foreach ($schemas_data as $row): ?>
                                    <tr>
                                        <?php if ($selected_env === "todos"): ?>
                                            <td><?= htmlspecialchars($row['ambiente']) ?></td>
                                        <?php endif; ?>
                                        <td><?= htmlspecialchars($row['service_name']) ?></td>
                                        <td><?= htmlspecialchars($row['total_schemas']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= ($selected_env === "todos") ? 3 : 2 ?>">Nenhum dado encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. Tabelas e Atributos por Schema (com Totais e scroll vertical) -->
            <div class="card">
                <div class="card-header">
                    <h5>Tabelas e Atributos por Schema</h5>
                </div>
                <!-- Container com barra de rolagem para garantir que os dados não fiquem encobertos -->
                <div class="card-body table-container">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <?php if ($selected_env === "todos"): ?>
                                    <th>Ambiente</th>
                                <?php endif; ?>
                                <th>Service Name</th>
                                <th>Schema Name</th>
                                <th>Total de Tabelas</th>
                                <th>Total de Atributos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($tables_data): ?>
                                <?php foreach ($tables_data as $row): ?>
                                    <tr>
                                        <?php if ($selected_env === "todos"): ?>
                                            <td><?= htmlspecialchars($row['ambiente']) ?></td>
                                        <?php endif; ?>
                                        <td><?= htmlspecialchars($row['service_name']) ?></td>
                                        <td><?= htmlspecialchars($row['schema_name']) ?></td>
                                        <td><?= htmlspecialchars($row['total_tables']) ?></td>
                                        <td><?= htmlspecialchars($row['total_attributes']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= ($selected_env === "todos") ? 5 : 4 ?>">Nenhum dado encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <!-- Footer com os totais gerais -->
                        <tfoot>
                            <tr>
                                <?php if ($selected_env === "todos"): ?>
                                    <td colspan="3" class="text-end"><strong>Total Geral:</strong></td>
                                <?php else: ?>
                                    <td colspan="2" class="text-end"><strong>Total Geral:</strong></td>
                                <?php endif; ?>
                                <td><strong><?= htmlspecialchars($total_tables_sum) ?></strong></td>
                                <td><strong><?= htmlspecialchars($total_attributes_sum) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
            <BR>
            <BR>
            <BR>
            <BR>
            <BR>
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
