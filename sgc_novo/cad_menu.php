<?php
OB_START();
session_start();

if(empty($_SESSION['global_autorizacao'])) {
    $permissao='nao_autorizado';
}else{
   $permissao = $_SESSION['global_autorizacao'];
}
 
if($permissao=='autorizado'){ 
 
   //Pega GET ou POST da acao interna
  if(!isset($_GET['acao_int']) && isset($_POST['acao_int'])){
    $acao_int=$_POST['acao_int'];
  }elseif(!isset($_POST['acao_int']) && isset($_GET['acao_int'])){
    $acao_int=$_GET['acao_int'];
  }
  
  if(empty($acao_int)){
    
	@include("html/cad_menu.html");

  }elseif($acao_int=="TELA1"){

    echo "bem vindo tela 1";
    

  }
  

}else{ 
 
    echo "Não autorizado";

}
?>