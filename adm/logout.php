<?php
OB_START();
session_start();

require_once("module/conecta.php");
require_once("module/functions.php");

$pg=new portal;
$conexao =  $pg->conectar_obj();

$user_id=$_SESSION['global_id_usuario'];
$session_id=$_SESSION['global_session_id'];

LogAcesso($user_id,$session_id,'out', $conexao);

session_unset();
session_destroy();
header("Location: index.php");

?>
