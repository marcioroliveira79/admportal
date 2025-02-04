<?php
OB_START();
session_start();

include("conf/conecta.php");
include("conf/funcs.php");

$mysql=new sgc;
       $mysql->conectar();


$session_id = session_id();
$id_acesso=ultimo_registro('id_acesso','sgc_acesso','id_acesso');
$log_acesso = mysql_query("UPDATE sgc_acesso SET data_saida=sysdate() WHERE id_acesso=$id_acesso and session='$session_id'") or print(mysql_error());

session_start();
session_unset();
session_destroy();
header("Location: index.php");
?>
