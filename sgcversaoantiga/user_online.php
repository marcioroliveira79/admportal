<?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include("conf/conecta.php");
include("conf/funcs.php");

$idus=$_POST['idus'];
$ip=$_POST['ip'];
$session=$_POST['session'];

$user_existe=tabelainfo($idus,'sgc_usuario_online','id_usuario','id_usuario','');
$session_banco=tabelainfo($idus,'sgc_usuario_online','session','id_usuario','');

if($session_banco!=$session){
    $msg="Outra pessoa logou com esse USUÁRIO";
}

if($user_existe=="<font face='Verdana' size='1' color='#FFFFFF'><span style='background-color: #FF0000'>&nbsp;Referência Inválida! </span></font>"){
 $result=mysql_query("INSERT INTO sgc_usuario_online (id_usuario,ultimo_registro,ip,session) VALUES ($idus,sysdate(),'$ip','$session')");
}else{
 $result=mysql_query("UPDATE sgc_usuario_online SET ultimo_registro=sysdate(), ip='$ip', session='$session' where id_usuario=$user_existe");
}



?>
