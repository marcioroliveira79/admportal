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
            CASE WHEN COUNT(1) = 1 THEN 'TELA AUTORIZADA' ELSE 'TELA NAO AUTORIZADA' END AS acesso
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
                ime.link_item,
                me.ordem AS ordem_menu,
                ime.ordem AS ordem_item
            FROM administracao.adm_perfil pe
            INNER JOIN administracao.adm_perfil_menu pem ON pe.id = pem.fk_perfil
            INNER JOIN administracao.adm_menu me ON me.id = pem.fk_menu
            INNER JOIN administracao.adm_item_menu ime ON ime.fk_menu = me.id
            WHERE pe.id = $1
            ORDER BY me.ordem, ime.ordem
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



function LogAcesso($id_usuario, $id_session, $act, $conexao)
{
    
    if (!isset($id_usuario, $id_session, $act)) {
        return "ERRO - Verifique os parâmetros";
    }

    if ($act === 'in') {
        $query = "INSERT INTO administracao.adm_log_acesso (fk_usuario, data_acesso, ip_acesso, session_id)
                  VALUES ($1, now(), $2, $3)";
        pg_query_params($conexao, $query, [$id_usuario, get_real_ip(), $id_session]);
        return "in";
    } elseif ($act === 'out') {
        $query = "UPDATE administracao.adm_log_acesso
                  SET data_saida = now()
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
