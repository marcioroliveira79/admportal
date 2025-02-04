<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];
$id_item=$_GET['id_item'];






if(!isset($acao_int)){
?>
<script src="prototype.js" type="text/javascript"></script>


<? $tempo3=atributo('atributo3');?>


<script>
  new Ajax.PeriodicalUpdater('check','monitor_servidor.php',
  {
   method: 'post',
   parameters: {id_item: '<?echo $id_item?>'},
   frequency: <?echo $tempo3?>
   });
</script>

<div align="center">
<div id="check" align="center">


</div>
</div>
<p>&nbsp;</p>


<?
}
elseif($acao_int=="checar_maquina_tabela"){
$id_item=$_GET['id_item'];
 $ip=$_GET['ip'];

exec("sudo /var/www/xfac/sgc/monitor_maquina.sh $ip",$resultado);
//shell_exec("sudo /var/www/xfac/sgc/monitor_maquina.sh $ip &");
header("Location: ?action=monitor_ajax_tabela.php&id_item=$id_item");
}
elseif($acao_int=="checar_maquinas_tabela"){
$id_item=$_POST['id_item'];
exec("sudo /var/www/xfac/sgc/monitor.sh",$resultado);
//shell_exec("sudo /var/www/xfac/sgc/monitor.sh &");
header("Location: ?action=monitor_ajax_tabela.php&id_item=$id_item");

}
elseif($acao_int=="checar_maquinas"){
$id_item=$_POST['id_item'];
exec("sudo /var/www/xfac/sgc/monitor.sh",$resultado);
//shell_exec("sudo /var/www/xfac/sgc/monitor.sh &");
header("Location: ?action=monitor_ajax.php&id_item=$id_item");

}else{
     $msg="Você não tem permissão para esta operação";
//
   }


  }


else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
