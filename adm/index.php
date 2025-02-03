<?php
ob_start();
session_start();

// Incluindo arquivos de configuração e funções necessárias
require_once("../module/conecta.php");
require_once("../module/functions.php");

// Cria a conexão uma vez
$pg = new portal();
$conexao = $pg->conectar_obj();

if (!$conexao) {
    die("Erro ao conectar ao banco de dados.");
}

if (empty($_SESSION['global_autorizacao'])) {
    $permissao = 'nao_autorizado';
} else {
    $permissao = $_SESSION['global_autorizacao'];
}

if ($permissao == 'autorizado') {
    // Passa a conexão diretamente para o portal.php
    include("portal.php");
} else {
    // Caso contrário, redireciona para a tela de login
    $acao = getAcao();

    if (!isset($acao)) {
        include("login.php");
    } elseif ($acao == "logar") {
        if (isset($_POST["login"]) && isset($_POST["senha"])) {
            $login = anti_injection($_POST["login"]);
            $senha = anti_injection($_POST["senha"]);
            $resultLogin = LoginUsuario($login, $senha, $conexao);
            
            if ($resultLogin['aut'] == true) {
                $temposessao = AtributoSistema("parametros_sistema", "time_session", null, $conexao);
                setcookie("usuario", $login, time() + $temposessao);
                
                $_SESSION['global_autorizacao'] = $resultLogin['aut'];
                $_SESSION['global_id_usuario'] = $resultLogin['id'];
                $_SESSION['global_nome'] = $resultLogin['nome'];
                $_SESSION['global_sobre_nome'] = $resultLogin['sobre_nome'];
                $_SESSION['global_email'] = $resultLogin['email'];
                $_SESSION['global_telefone'] = $resultLogin['telefone'];
                $_SESSION['global_login'] = $resultLogin['login'];
                $_SESSION['global_id_perfil'] = $resultLogin['id_perfil'];
                $_SESSION['global_desc_perfil'] = $resultLogin['desc_perfil'];
                $_SESSION['global_path'] = (dirname(realpath(__DIR__ . '/index.php')))."\\";
                $_SESSION['global_session_id']  = session_id();

                
                LogAcesso($_SESSION['global_id_usuario'], $_SESSION['global_session_id'], 'in', $conexao);
                header("Location:index.php");
                exit;
            } else {
                $msg = $resultLogin['mensagem'];
                $_SESSION['login_error'] = $msg;
                header("Location: login.php");
                exit;
            }
        }
    }
}
?>
