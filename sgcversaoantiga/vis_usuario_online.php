<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];






if(!isset($acao_int)){
?>
<script src="prototype.js" type="text/javascript"></script>


<? $tempo3=atributo('atributo3');?>


<script>
  new Ajax.PeriodicalUpdater('online','list_online.php',
  {
   method: 'post',
   parameters: {idus: '<?echo $idusuario?>'},
   frequency: <?echo $tempo3?>
   });
</script>

<div align="center">
<div id="online" align="center">


</div>
</div>
<p>&nbsp;</p>


<?

  }
elseif($acao_int=="livre"){


}else{
     $msg="Você não tem permissão para esta operação";
//     header("Location: ?action=$arquivo&id_item=$id_item&msg=$msg");
   }
    
    
  }


else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
