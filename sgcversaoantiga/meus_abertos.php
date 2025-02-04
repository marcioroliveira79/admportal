<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Meus Chamados em Aberto";
$id_item=$_GET['id_item'];





if(!isset($acao_int)){

if(chamado_fechado_falta_enquete($idusuario)!=null){
  $id_chamado_enquete=chamado_fechado_falta_enquete($idusuario);
  header("Location: ?action=vis_chamado.php&acao_int=enquete&id_chamado=$id_chamado_enquete");
}
/*
if(questionamento($idusuario)!=null){
  $id_quest=questionamento($idusuario);
  header("Location: ?action=vis_chamado.php&acao_int=questionamento&id_quest=$id_quest");
}
*/



?>
<script src="prototype.js" type="text/javascript"></script>
 <? $tempo=atributo('atributo2');?>
<script>
  new Ajax.PeriodicalUpdater('online','meus_abertos_online.php',
  {
   method: 'post',
   parameters: {idus: '<?echo $idusuario?>'},
   frequency: <?echo $tempo?>
   });
</script>

<div align="center">
<div id="online" align="center">


</div>
</div>
<p>&nbsp;</p>

<?



}
elseif($acao_int=="editar_bd"){


}


}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
