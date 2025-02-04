<?php
OB_START();
session_start();

?>
<script src="prototype.js" type="text/javascript"></script>
<?


if($permissao=='ok'){

$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];
$titulo="NF-e CONAB";
$id_item=$_GET['id_item'];
$arquivo="nfe_conab.php";

$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];


if(!isset($acao_int)){

$checa_qt = mysql_query("SELECT DATE_FORMAT(data_inclusao, '%d/%m/%Y %H:%i%:%s') as data_inclusao FROM sgc_nota_xfac ORDER BY id DESC limit 1") or print(mysql_error());
                         while($dados_qt=mysql_fetch_array($checa_qt)){
                            $data_inclusao = $dados_qt['data_inclusao'];
}
?>
<script src="prototype.js" type="text/javascript"></script>




<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: <?echo $titulo?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">

<div align="center" id="corpo">
</div><p>
        <BR>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>

<script>
  new Ajax.PeriodicalUpdater('corpo', 'nfe_todas_ufs_ajax.php',
  {
   parameters: {idus: '<?echo $idusuario?>', id_item: '<?echo $id_item?>'},
   method: 'post',
   frequency: <?echo $tempo=atributo('atributo3')?>
   });
</script>

<?

}elseif($acao_int=="sureg"){





}
}else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
