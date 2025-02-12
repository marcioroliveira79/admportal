<?php
ob_start();
// Incluindo arquivos de configuração e funções necessárias
require_once("../module/conecta.php");
require_once("../module/functions.php");
echo '<meta charset="utf-8"/>';

if (!isset($conexao)) {
    die("Erro: Conexão com o banco de dados não foi transmitida.");
}

// Verificação de autorização do usuário
if (empty($_SESSION['global_autorizacao'])) {
    $permissao = 'nao_autorizado';
} else {
    $permissao = $_SESSION['global_autorizacao'];
}

// Verificação de expiração da sessão
if (isset($_SESSION['global_inicio_sessao']) && isset($_SESSION['global_tempo_sessao'])) {
    $tempo_decorrido = time() - $_SESSION['global_inicio_sessao'];

    if ($tempo_decorrido > $_SESSION['global_tempo_sessao']) {
        // Sessão expirou
        session_destroy(); // Destroi a sessão
        setcookie("usuario", "", time() - 3600); // Remove o cookie de usuário        
        $_SESSION['login_error'] = 'Sua sessão expirou';
        header("Location: login.php"); // Redireciona para a tela de login com uma mensagem
        exit;
    } else {
        // Atualiza o tempo de início da sessão
        $_SESSION['global_inicio_sessao'] = time();
    }
}

if ($permissao == 'autorizado') { 
    $nome_visualizacao = ucfirst($_SESSION['global_nome']) . " " . ucfirst($_SESSION['global_sobre_nome']);
    $id_usuario = $_SESSION['global_id_usuario'];
    $id_perfil = $_SESSION['global_id_perfil'];
    $acao = getAcao();
    $menus = getMenusPerfil($id_perfil, $conexao);

    if (empty($acao)) {
        @include("html/portal.html");
    } else {
        $acao_existe = isFileExists($acao, $_SESSION['global_path']);
        $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
        if ($acesso == "TELA AUTORIZADA") {
            if ($acao_existe == "existe") {
                @include($acao);
            } elseif ($acao_existe != "existe") {
                @include("html/404.html");
            }
        } else {
            @include("html/403.html");
        }
    }  
} else { 
    header("Location: login.php");
}
?>
