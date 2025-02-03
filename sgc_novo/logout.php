<?php
OB_START();
session_start();

include("conf/conecta.php");
include("conf/functions.php");

$pg=new sgc_obj;
    $pg->conectar_obj();

$user_id=$_SESSION['global_id_usuario'];
$session_id=session_id();

LogAcesso($user_id,$session_id,'out');


session_unset();
session_destroy();
header("Location: index.php");

?>
