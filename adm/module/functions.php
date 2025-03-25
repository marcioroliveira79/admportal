<?php

function getAcao() {
    if (!isset($_GET['acao']) && isset($_POST['acao'])) {
        return $_POST['acao'];
    } elseif (!isset($_POST['acao']) && isset($_GET['acao'])) {
        return $_GET['acao'];
    }
    return null; // Retorna null se nenhum dos parâmetros estiver definido
}


function get_real_ip()
{
    $ip = $_SERVER['HTTP_CLIENT_IP'] ?? ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']);
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($ips as $i) {
            if (!preg_match("/^(10|172\.16|192\.168)\./", $i) && filter_var($i, FILTER_VALIDATE_IP)) {
                return $i;
            }
        }
    }
    return $ip;
}


function isFileExists($fileName, $path)
{
    // Garante que o caminho esteja formatado corretamente
    $filePath = rtrim($path, '/') . '/' . $fileName;

    // Verifica se o arquivo existe
    if (file_exists($filePath)) {
        return "existe"; // Retorna true se o arquivo for encontrado
    }

    return "nao existe"; // Retorna false se o arquivo não for encontrado
}


function ItemAccess($idPerfil, $item, $conexao)
{
    if (!empty($idPerfil) && !empty($item)) {
        $query = "
        SELECT 
            CASE WHEN COUNT(1) > 0 THEN 'TELA AUTORIZADA' ELSE 'TELA NAO AUTORIZADA' END AS acesso
        FROM administracao.adm_perfil pe
        INNER JOIN administracao.adm_perfil_menu pem ON pe.id = pem.fk_perfil
        INNER JOIN administracao.adm_menu me ON me.id = pem.fk_menu
        INNER JOIN administracao.adm_item_menu ime ON ime.fk_menu = me.id
        WHERE pe.id = $1
        AND ime.link_item = $2
        ";

        $result = pg_query_params($conexao, $query, [$idPerfil, $item]);

        if ($result) {
            $row = pg_fetch_assoc($result);
            return $row['acesso']; // Converte para booleano
        }
    }

    return false; // Retorna false se os parâmetros ou resultados forem inválidos
}


function getMenusPerfil($idPerfil,$conexao)
{
    
    if (!empty($idPerfil)) {
        $query = "
            SELECT 
    me.descricao AS dsc_menu,
    me.ajuda AS ajuda_menu,
    ime.descricao_item AS dsc_item,
    CASE 
        WHEN COUNT(ac.id) > 0 
        THEN ime.link_item || '&' || string_agg(ac.action_item || '=' || ac.value_item, '&')
        ELSE ime.link_item
    END AS link_item,
    me.ordem AS ordem_menu,
    ime.ordem AS ordem_item
FROM administracao.adm_perfil pe
INNER JOIN administracao.adm_perfil_menu pem ON pe.id = pem.fk_perfil
INNER JOIN administracao.adm_menu me ON me.id = pem.fk_menu
INNER JOIN administracao.adm_item_menu ime ON ime.fk_menu = me.id
LEFT JOIN administracao.adm_item_menu_action ac ON ac.fk_item_menu = ime.id
WHERE pe.id = $1
  
GROUP BY 
    me.descricao, 
    me.ajuda, 
    ime.descricao_item, 
    ime.link_item, 
    me.ordem, 
    ime.ordem
ORDER BY me.ordem, ime.ordem;

        ";

        $result = pg_query_params($conexao, $query, [$idPerfil]);

        if ($result && pg_num_rows($result) > 0) {
            $menus = [];
            while ($row = pg_fetch_assoc($result)) {
                $menus[] = [
                    'dsc_menu' => $row['dsc_menu'],
                    'ajuda_menu' => $row['ajuda_menu'],
                    'dsc_item' => $row['dsc_item'],
                    'link_item' => $row['link_item'],
                    'ordem_menu' => $row['ordem_menu'],
                    'ordem_item' => $row['ordem_item']
                ];
            }

            return [
                'aut' => true,
                'mensagem' => null,
                'menus' => $menus
            ];
        } else {
            return ['aut' => false, 'mensagem' => 'Nenhum menu encontrado para este perfil'];
        }
    }

    return ['aut' => false, 'mensagem' => 'Informe um ID de perfil válido'];
}

function checkSession($id_usuario, $id_session, $conexao)
{
    // Verifica se os parâmetros são válidos
    if (empty($id_usuario) || empty($id_session)) {
        return 0; // Retorna 0 se os parâmetros estiverem vazios
    }

    // Query para verificar sessões encerradas
    $query = "
        SELECT COUNT(*) AS encerrado
        FROM administracao.adm_log_acesso
        WHERE fk_usuario = $1
          AND session_id = $2
          AND metodo_logout LIKE 'TEMPO SESSAO%'        
    ";

    // Executa a consulta no banco de dados
    $result = pg_query_params($conexao, $query, [$id_usuario, $id_session]);

    // Verifica se houve erro na consulta
    if (!$result) {
        error_log("Erro na consulta checkSession: " . pg_last_error($conexao));
        return 0; // Retorna 0 em caso de erro
    }

    // Verifica se a consulta retornou resultados
    if (pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);

        // Verifica se a chave 'encerrado' está presente no resultado
        if (isset($row['encerrado'])) {
            return (int)$row['encerrado']; // Retorna o valor de 'encerrado' como inteiro
        }
    }

    // Retorna 0 se não houver resultados ou a chave não existir
    return 0;
}

function updateSessionDataSaida($id_session, $conexao)
{
    // Verifica se o parâmetro é válido
    if (empty($id_session)) {
        return false; // Retorna false se o parâmetro estiver vazio
    }

    // Query para atualizar o campo data_saida
    $query = "
        UPDATE administracao.adm_log_acesso
        SET data_saida = NOW()
        WHERE session_id = $1
    ";

    // Executa a query parametrizada para evitar SQL Injection
    $result = pg_query_params($conexao, $query, [$id_session]);

    // Verifica se a query foi executada com sucesso
    if (!$result) {
        error_log("Erro na consulta updateSessionDataSaida: " . pg_last_error($conexao));
        return false; // Retorna false em caso de erro
    }

    // Retorna true se a atualização foi bem-sucedida
    return true;
}


function LoginUsuario($login, $senha, $conexao)
{
    if (!empty($login) && !empty($senha)) {
        $query = "SELECT us.id, us.nome, us.sobre_nome, us.email, us.telefone, us.login,
                         us.ativo, pl.id as id_perfil, pl.nome as desc_perfil
                  FROM administracao.adm_usuario us
                  LEFT JOIN administracao.adm_usuario_perfil uf ON us.id = uf.fk_usuario
                  LEFT JOIN administracao.adm_perfil pl ON uf.fk_perfil = pl.id
                  WHERE us.login = $1 AND us.senha = $2";
        
        $result = pg_query_params($conexao, $query, [$login, $senha]);

        if ($result && pg_num_rows($result) > 0) {
            $dados = pg_fetch_assoc($result);

            // Verifica se o usuário está ativo
            if ($dados['ativo'] === 'f') {
                return [
                    'aut' => false,
                    'mensagem' => 'Usuário inativo. Por favor, entre em contato com o administrador.'
                ];
            }

            return [
                'aut' => true,
                'mensagem' => null,
                'id' => $dados['id'],
                'nome' => $dados['nome'],
                'sobre_nome' => $dados['sobre_nome'],
                'email' => $dados['email'],
                'telefone' => $dados['telefone'],
                'login' => $dados['login'],
                'id_perfil' => $dados['id_perfil'],
                'desc_perfil' => $dados['desc_perfil']
            ];
        } else {
            return ['aut' => false, 'mensagem' => 'Usuário ou senha inválido'];
        }
    }

    return ['aut' => false, 'mensagem' => 'Informe o usuário e a senha'];
}

function getOS($userAgent) {
    $osArray = array(
        '/windows nt 10/i'      => 'Windows 10',
        '/windows nt 6.3/i'     => 'Windows 8.1',
        '/windows nt 6.2/i'     => 'Windows 8',
        '/windows nt 6.1/i'     => 'Windows 7',
        '/windows nt 6.0/i'     => 'Windows Vista',
        '/windows nt 5.2/i'     => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     => 'Windows XP',
        '/windows xp/i'         => 'Windows XP',
        '/windows nt 5.0/i'     => 'Windows 2000',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i'        => 'Mac OS 9',
        '/linux/i'              => 'Linux',
        '/ubuntu/i'             => 'Ubuntu',
        '/iphone/i'             => 'iPhone',
        '/ipod/i'               => 'iPod',
        '/ipad/i'               => 'iPad',
        '/android/i'            => 'Android',
        '/blackberry/i'         => 'BlackBerry',
        '/webos/i'              => 'Mobile'
    );

    $os = "Desconhecido";
    foreach ($osArray as $regex => $value) {
        if (preg_match($regex, $userAgent)) {
            $os = $value;
            break;
        }
    }
    return $os;
}


function getBrowser($userAgent) {
    $browserArray = array(
        '/edge/i'      => 'Edge',
        '/msie/i'      => 'Internet Explorer',
        '/trident/i'   => 'Internet Explorer', // Para versões mais recentes do IE
        '/firefox/i'   => 'Firefox',
        '/safari/i'    => 'Safari',
        '/chrome/i'    => 'Chrome',
        '/opera/i'     => 'Opera',
        '/netscape/i'  => 'Netscape',
        '/maxthon/i'   => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i'    => 'Navegador móvel'
    );

    $browser = "Desconhecido";
    foreach ($browserArray as $regex => $value) {
        if (preg_match($regex, $userAgent)) {
            $browser = $value;
            break;
        }
    }
    return $browser;
}

function LogAcesso($id_usuario, $id_session, $act, $conexao){
    if (!isset($id_usuario, $id_session, $act)) {
        return "ERRO - Verifique os parâmetros";
    }

    if ($act === 'in') {
        // Captura informações do user agent para identificar SO e navegador
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $sistema_operacional = getOS($userAgent);
        $navegador = getBrowser($userAgent);

        $query = "INSERT INTO administracao.adm_log_acesso (
                      fk_usuario, data_acesso, ip_acesso, session_id, sistema_operacional, navegador
                  ) VALUES (
                      $1, now(), $2, $3, $4, $5
                  )";
        pg_query_params($conexao, $query, [
            $id_usuario,
            get_real_ip(), // Função já existente que retorna o IP real do usuário
            $id_session,
            $sistema_operacional,
            $navegador
        ]);
        return "in";
    } elseif ($act === 'out') {
        $query = "UPDATE administracao.adm_log_acesso
                  SET data_saida = now(), metodo_logout = 'LOGOUT WEB'
                  WHERE fk_usuario = $1 AND session_id = $2 AND data_saida IS NULL";
        pg_query_params($conexao, $query, [$id_usuario, $id_session]);
        return "out";
    } elseif ($act === 'id') {
        $query = "SELECT id FROM administracao.adm_log_acesso
                  WHERE fk_usuario = $1 AND session_id = $2 AND data_saida IS NULL";
        $result = pg_query_params($conexao, $query, [$id_usuario, $id_session]);
        if ($result) {
            $dados = pg_fetch_assoc($result);
            return $dados['id'] ?? null;
        }
    } elseif ($act === 'dump') {
        return "ACT: $act, ID_USU: $id_usuario, ID_SESSION: $id_session";
    }

    return "ERRO - Verifique os parâmetros";
}



function anti_injection($sql)
{
    $sql = preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/i", "", $sql);
    $sql = trim($sql);
    $sql = strip_tags($sql);
    $sql = addslashes($sql);
    return $sql;
}


function AtributoSistema($nome_tabela, $nome_item, $id_item, $conexao) {

    if (isset($nome_item) && isset($nome_tabela)) {
        $query = "SELECT tba.valor_item 
                  FROM administracao.adm_pseudo_tabela tb
                  INNER JOIN administracao.adm_pseudo_tabela_atributos tba ON tb.id = tba.fk_atributo
                  WHERE tb.nome_tabela = $1 AND tba.nome_item = $2";
        $result = pg_query_params($conexao, $query, [$nome_tabela, $nome_item]);

        if ($result && pg_num_rows($result) > 0) {
            $dados = pg_fetch_assoc($result);
            return $dados['valor_item'];
        }
        return null;
    } elseif (isset($id_item)) {
        $query = "SELECT tba.id 
                  FROM administracao.adm_pseudo_tabela tb
                  INNER JOIN administracao.adm_pseudo_tabela_atributos tba ON tb.id = tba.fk_atributo
                  WHERE tba.id = $1";
        $result = pg_query_params($conexao, $query, [$id_item]);

        if ($result && pg_num_rows($result) > 0) {
            $dados = pg_fetch_assoc($result);
            return $dados['id'];
        }
        return null;
    }

    return "Você deve passar o nome ou o ID do item!";
}
function geraArquivoPrefixo($conexao) {
    $query_json = "
      WITH cte AS (
          SELECT 
            '\"' || p.prefixo || '\": { \"tipo\": [' ||
              string_agg('\"' || upper(td.tipo) || '\"', ', ' ORDER BY td.tipo)
            || '], \"comentario\": \"' || p.dominio || ' - ' || p.comentario || '\" }' AS linha,
            row_number() OVER (ORDER BY p.prefixo) AS rn,
            count(*) OVER () AS total
          FROM administracao.catalog_prefixo_tipo p
          LEFT JOIN administracao.catalog_ass_prefixo_tipo ass 
            ON ass.fk_catalogo_prefixo_tipo = p.id
          LEFT JOIN administracao.catalog_tipo_dado td 
            ON ass.fk_catalogo_tipo_dado = td.id
          GROUP BY 
            p.prefixo, 
            p.dominio, 
            p.comentario
      ), linhas AS (
          SELECT linha || CASE WHEN rn < total THEN ',' ELSE '' END AS resultado
          FROM cte
      )
      SELECT '{' || string_agg(resultado, E'\n') || '}' AS final_result
      FROM linhas;
    ";

    $result_json = pg_query($conexao, $query_json);
    if ($result_json) {
        // Busca apenas a primeira linha (única) com a coluna final_result
        $row = pg_fetch_assoc($result_json);
        $finalResult = $row['final_result'];

        // Cria a pasta "data" se não existir
        if (!file_exists('data')) {
            mkdir('data', 0777, true);
        }

        // Salva o JSON no arquivo
        file_put_contents('data/prefixo.txt', $finalResult);
    }
}


function geraArquivoPalavraLGPD($conexao) {
    $query_json = "
    	WITH cte AS (
        SELECT 
          '\"' || lg.atributo || '\": \"' || clg.classificacao || '\"' AS linha,
          row_number() OVER (ORDER BY lg.atributo) AS rn,
          count(*) OVER () AS total
        FROM administracao.catalog_atributo_classificado_lgpd lg
        LEFT JOIN administracao.catalog_lgpd_classificacao clg 
          ON lg.fk_lgpd_classificacao = clg.id AND lg.tipo_definicao ='DICIONARIO'
      )
      SELECT '{' || string_agg(linha || CASE WHEN rn < total THEN ',' ELSE '' END, E'\n') || '}' AS final_result
      FROM cte;
    ";

    $result_json = pg_query($conexao, $query_json);
    if ($result_json) {
        // Busca apenas a primeira linha (única) com a coluna final_result
        $row = pg_fetch_assoc($result_json);
        $finalResult = $row['final_result'];
        // Cria a pasta "data" se não existir
        if (!file_exists('data')) {
            mkdir('data', 0777, true);
        }
        file_put_contents('data/lgpd.txt', $finalResult);
    }
}
?>