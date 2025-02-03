<?php

OB_START();
session_start();

include("conf/conecta.php");
include("conf/functions.php");
include("conf/dados.php");
@include("html/charset.html");



if(empty($_SESSION['global_autorizacao'])) {
    $permissao='nao_autorizado';
}else{
   $permissao = $_SESSION['global_autorizacao'];
}
 
if($permissao=='autorizado'){ 
 
    //Pega GET ou POST da xItem interna
  if(!isset($_GET['xItem']) && isset($_POST['xItem'])){
    $xItem=$_POST['xItem'];
  }elseif(!isset($_POST['xItem']) && isset($_GET['xItem'])){
    $xItem=$_GET['xItem'];
  }
  
    $nome_visualizxItem=ucfirst($_SESSION['global_nome'])." ".ucfirst($_SESSION['global_sobre_nome']);
	$id_usuario=$_SESSION['global_id_usuario'];
	$id_perfil=$_SESSION['global_id_perfil'];
    
	$pg=new sgc_obj_fc;
    $pg->conectar_obj();
  
  if(empty($xItem)){
    
	  @include("html/portal.html");

  }else{
    
	   $autTela = getTelaAut($id_usuario,$id_perfil,$xItem);
	
	
	if ($autTela['aut'] == true ){
		
		header("Location:portal.php?acao_int=".$autTela['arquivo']."");
		
	}else{
	
	  Echo "Não Autorizado";
    }		
    
    
  }
  
  //Pega GET ou POST da acao_int interna
  if(!isset($_GET['acao_int']) && isset($_POST['acao_int'])){
    $acao_int=$_POST['acao_int'];
  }elseif(!isset($_POST['acao_int']) && isset($_GET['acao_int'])){
    $acao_int=$_GET['acao_int'];
  }
  
  if(empty($acao_int)){
    
	  echo "aqui  xxxx";

  }else{
	 
	  echo "aqui 1";	 
	  
  }  
  

}else{ 
 
    echo "n autorizado";

}
?>

