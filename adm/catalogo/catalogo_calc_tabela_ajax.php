<?php
session_start();


require_once __DIR__ . '/../module/conecta.php';


$pg = new portal();
$conexao = $pg->conectar_obj();

$acao   = isset($_GET['acao'])   ? $_GET['acao']   : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;

$response = ['success' => false, 'data' => null];

// Verifica sessão
if (!isset($_SESSION['global_id_usuario']) || empty($_SESSION['global_id_usuario'])) {
    $response['data'] = 'Sessão inválida ou usuário não logado.';
    echo json_encode($response);
    exit;
}

// Se quiser verificar $acao e acesso, descomente e ajuste se necessário:
// $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
// if ($acesso != "TELA AUTORIZADA") {
//     $response['data'] = 'Acesso negado.';
//     echo json_encode($response);
//     exit;
// }

switch ($action) {

    // ----------------------------------------
    // Ações para Tabela Existente
    // ----------------------------------------
    case 'getAmbientes':
        $sql = "
            SELECT DISTINCT ambiente
            FROM administracao.catalog_table_content
            WHERE ambiente IS NOT NULL
            ORDER BY ambiente
        ";
        $result = pg_query($conexao, $sql);
        if (!$result) {
            $response['data'] = pg_last_error($conexao);
        } else {
            $rows = pg_fetch_all_columns($result, 0);
            if (!$rows) {
                $rows = [];
            }
            $response['success'] = true;
            $response['data'] = $rows; 
        }
        echo json_encode($response);
        exit;

    case 'getServicos':
        $ambiente = isset($_GET['ambiente']) ? trim($_GET['ambiente']) : '';
        if (!$ambiente) {
            $response['data'] = 'Ambiente não informado.';
            echo json_encode($response);
            exit;
        }
        $sql = "
            SELECT DISTINCT service_name
            FROM administracao.catalog_table_content
            WHERE ambiente = $1
            ORDER BY service_name
        ";
        $params = [$ambiente];
        $result = pg_query_params($conexao, $sql, $params);
        if (!$result) {
            $response['data'] = pg_last_error($conexao);
        } else {
            $rows = pg_fetch_all($result);
            if (!$rows) {
                $rows = [];
            }
            $response['success'] = true;
            $response['data'] = $rows; 
        }
        echo json_encode($response);
        exit;

    case 'getSchemas':
        $ambiente    = isset($_GET['ambiente'])     ? trim($_GET['ambiente'])     : '';
        $serviceName = isset($_GET['service_name']) ? trim($_GET['service_name']) : '';
        if (!$ambiente || !$serviceName) {
            $response['data'] = 'Ambiente/Serviço não informados.';
            echo json_encode($response);
            exit;
        }
        $sql = "
            SELECT DISTINCT schema_name
            FROM administracao.catalog_table_content
            WHERE ambiente = $1
              AND service_name = $2
            ORDER BY schema_name
        ";
        $params = [$ambiente, $serviceName];
        $result = pg_query_params($conexao, $sql, $params);
        if (!$result) {
            $response['data'] = pg_last_error($conexao);
        } else {
            $rows = pg_fetch_all($result);
            if (!$rows) {
                $rows = [];
            }
            $response['success'] = true;
            $response['data'] = $rows;
        }
        echo json_encode($response);
        exit;

    case 'getTabelas':
        $ambiente    = isset($_GET['ambiente'])     ? trim($_GET['ambiente'])     : '';
        $serviceName = isset($_GET['service_name']) ? trim($_GET['service_name']) : '';
        $schemaName  = isset($_GET['schema_name'])  ? trim($_GET['schema_name'])  : '';
        if (!$ambiente || !$serviceName || !$schemaName) {
            $response['data'] = 'Parâmetros faltando (ambiente, service_name, schema_name).';
            echo json_encode($response);
            exit;
        }
        $sql = "
            SELECT DISTINCT table_name
            FROM administracao.catalog_table_content
            WHERE ambiente = $1
              AND service_name = $2
              AND schema_name  = $3
            ORDER BY table_name
        ";
        $params = [$ambiente, $serviceName, $schemaName];
        $result = pg_query_params($conexao, $sql, $params);
        if (!$result) {
            $response['data'] = pg_last_error($conexao);
        } else {
            $rows = pg_fetch_all($result);
            if (!$rows) {
                $rows = [];
            }
            $response['success'] = true;
            $response['data'] = $rows;
        }
        echo json_encode($response);
        exit;

    case 'getAtributos':
        $ambiente    = isset($_GET['ambiente'])     ? trim($_GET['ambiente'])     : '';
        $serviceName = isset($_GET['service_name']) ? trim($_GET['service_name']) : '';
        $schemaName  = isset($_GET['schema_name'])  ? trim($_GET['schema_name'])  : '';
        $tableName   = isset($_GET['table_name'])   ? trim($_GET['table_name'])   : '';
        if (!$ambiente || !$serviceName || !$schemaName || !$tableName) {
            $response['data'] = 'Parâmetros incompletos para buscar atributos.';
            echo json_encode($response);
            exit;
        }
        $sql = "
            SELECT column_name,
                   data_type,
                   data_length,
                   CASE WHEN is_nullable = 'NULL' THEN 'YES' ELSE 'NO' END AS is_nullable
            FROM administracao.catalog_table_content
            WHERE ambiente     = $1
              AND service_name = $2
              AND schema_name  = $3
              AND table_name   = $4
            ORDER BY column_id
        ";
        $params = [$ambiente, $serviceName, $schemaName, $tableName];
        $result = pg_query_params($conexao, $sql, $params);
        if (!$result) {
            $response['data'] = pg_last_error($conexao);
        } else {
            $rows = pg_fetch_all($result);
            if (!$rows) {
                $rows = [];
            }
            $response['success'] = true;
            $response['data'] = $rows;
        }
        echo json_encode($response);
        exit;

    // ----------------------------------------
    // Ações para Criar Nova Tabela
    // ----------------------------------------
    case 'getTecnologias':
        // Carrega registros de administracao.catalog_tipo_tecnlogia
        $sql = "
            SELECT id, tecnlogia, versao
            FROM administracao.catalog_tipo_tecnlogia
            ORDER BY tecnlogia, versao
        ";
        $result = pg_query($conexao, $sql);
        if (!$result) {
            $response['data'] = pg_last_error($conexao);
        } else {
            $rows = pg_fetch_all($result);
            if (!$rows) {
                $rows = [];
            }
            $response['success'] = true;
            $response['data'] = $rows;
            // Exemplo: [{id:1, tecnlogia:'Oracle', versao:'11g'}, ...]
        }
        echo json_encode($response);
        exit;

    case 'getTiposDado':
        // Carrega tipos de dado, filtrando por fk_tipo_tecnologia
        $fk_tec = isset($_GET['fk_tecnologia']) ? (int)$_GET['fk_tecnologia'] : 0;
        if ($fk_tec <= 0) {
            $response['data'] = 'fk_tecnologia inválido.';
            echo json_encode($response);
            exit;
        }
        $sql = "
            SELECT id, tipo
            FROM administracao.catalog_tipo_dado
            WHERE fk_tipo_tecnologia = $1
            ORDER BY tipo
        ";
        $params = [$fk_tec];
        $result = pg_query_params($conexao, $sql, $params);
        if (!$result) {
            $response['data'] = pg_last_error($conexao);
        } else {
            $rows = pg_fetch_all($result);
            if (!$rows) {
                $rows = [];
            }
            $response['success'] = true;
            $response['data'] = $rows;
            // Exemplo: [{id:10, tipo:'VARCHAR2'}, {id:11, tipo:'NUMBER'}, {id:12, tipo:'DATE'}]
        }
        echo json_encode($response);
        exit;

    default:
        $response['data'] = 'Ação inválida.';
        echo json_encode($response);
        exit;
}
