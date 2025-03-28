<?php
ob_start();
session_start();

// Incluindo arquivos de configuração e funções necessárias

require_once __DIR__ . '/module/conecta.php';
require_once __DIR__ . '/module/functions.php';

// Cria a conexão uma vez
$pg = new portal();
$conexao = $pg->conectar_obj();
pg_set_client_encoding($conexao, "UTF8");

if (!$conexao) {
    die("Erro ao conectar ao banco de dados.");
}

// Verifica a sessão e o tempo de expiração
if (isset($_SESSION['global_inicio_sessao']) && isset($_SESSION['global_tempo_sessao'])) {
    $tempo_decorrido = time() - $_SESSION['global_inicio_sessao'];
    
    
    if(checkSession($_SESSION['global_id_usuario'], $_SESSION['global_session_id'], $conexao) == 1){
            
            updateSessionDataSaida($_SESSION['global_session_id'],$conexao);
            session_destroy(); // Destroi a sessão
            setcookie("usuario", "", time() - 3600); // Remove o cookie de usuário
            echo "<script>
                    // Verifica se o código está sendo executado dentro de um iframe
                    if (window.top !== window.self) {
                        // Redireciona a janela principal para o login
                        window.top.location.href = 'login.php?errorMessage=Expirou';
                    } else {
                        // Redireciona normalmente
                        window.location.href = 'login.php?errorMessage=Expirou';
                    }
                </script>";
            exit();

    }else{
        if ($tempo_decorrido > $_SESSION['global_tempo_sessao']) {
            // Sessão expirou
            updateSessionDataSaida($_SESSION['global_session_id'],$conexao);
            session_destroy(); // Destroi a sessão
            setcookie("usuario", "", time() - 3600); // Remove o cookie de usuário
            echo "<script>
                    // Verifica se o código está sendo executado dentro de um iframe
                    if (window.top !== window.self) {
                        // Redireciona a janela principal para o login
                        window.top.location.href = 'login.php?errorMessage=Expirou';
                    } else {
                        // Redireciona normalmente
                        window.location.href = 'login.php?errorMessage=Expirou';
                    }
                </script>";
            exit();

        } else {
            // Atualiza o tempo de início da sessão para mantê-la ativa
            $_SESSION['global_inicio_sessao'] = time();
        }
    }
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

                if (!isset($_SESSION['session_id'])) {
                    // Gerar um identificador único para a sessão
                    $_SESSION['session_id'] = bin2hex(random_bytes(16));
                }

                
                $_SESSION['global_autorizacao'] = $resultLogin['aut'];
                $_SESSION['global_id_usuario'] = $resultLogin['id'];
                $_SESSION['global_nome'] = $resultLogin['nome'];
                $_SESSION['global_sobre_nome'] = $resultLogin['sobre_nome'];
                $_SESSION['global_email'] = $resultLogin['email'];
                $_SESSION['global_telefone'] = $resultLogin['telefone'];
                $_SESSION['global_login'] = $resultLogin['login'];
                $_SESSION['global_id_perfil'] = $resultLogin['id_perfil'];
                $_SESSION['global_desc_perfil'] = $resultLogin['desc_perfil'];
                $_SESSION['global_path'] = (dirname(realpath(__DIR__ . '/index.php')))."/";
                $_SESSION['global_session_id']  =  $_SESSION['session_id'];
                $_SESSION['global_inicio_sessao'] = time(); // Hora do início da sessão
                $_SESSION['global_tempo_sessao'] = $temposessao; // Tempo de expiração da sessão em segundos

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
