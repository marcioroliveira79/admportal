<?php
OB_START();
session_start();
//error_reporting(0);
include("conf/conecta.php");
include("conf/functions.php");
@include("html/charset.html");

if(empty($_SESSION['global_autorizacao'])) {
   $permissao='nao_autorizado';
}else{
  $permissao = $_SESSION['global_autorizacao'];
}

if($permissao=='autorizado'){ // Se permissão OK chama a pagina SGC

  header("Location: sgc.php");

}else{ // Caso contrário começa as opções

  //Pega GET ou POST
  if(!isset($_GET['acao']) && isset($_POST['acao'])){
    $acao=$_POST['acao'];
  }elseif(!isset($_POST['acao']) && isset($_GET['acao'])){
    $acao=$_GET['acao'];
  }

  if(!isset($acao)){ // Se caso acão for null, tela de login
    
	$pg=new sgc_obj;
    $pg->conectar_obj();

    
    include("html/login.html");


  }elseif($acao=="logar"){

    if(isset($_POST["login"]) && isset($_POST["senha"])){
      
      $login = anti_injection($_POST["login"]);
      $senha = anti_injection($_POST["senha"]);

      //-------------autorizacao---------------//
      $resultLogin = LoginUsuario($login,$senha);
      
      if( ($resultLogin['aut']) == true){

        //--------------------Pega tempo de sessão----------------------------//
        $temposessao=AtributoSistema("parametros_sistema","time_session",null); 
        
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
        
        $session_id = session_id();

        LogAcesso(($_SESSION['global_id_usuario']), "'$session_id'", 'in');


        header("Location:index.php");

      }else{
        
        $msg=$resultLogin['mensagem'];
        //header("Location:index.php?result=$msg&login=$login");
        exit;

      }

    }else{

    }
  

  }//------Logar-----//
}//------Index-----//
?>
