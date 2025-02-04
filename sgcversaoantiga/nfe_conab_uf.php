<?php
OB_START();
session_start();


if($permissao=='ok'){


$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="NF-e CONAB";

$id_item=$_GET['id_item'];
$arquivo="nfe_conab.php";



$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

?>
<script src="prototype.js" type="text/javascript"></script>
<?

if($_POST['uf_nfe']==null){

   $id_item=$_GET['id_item'];
}else{

   $id_item=$_POST['id_item'];

}


if(!isset($acao_int)){

$checa = mysql_query("
SELECT DISTINCT
descricao_servidor
,un.codigo
FROM sgc_usuario us, sgc_unidade un, sgc_servidores ser
WHERE us.id_unidade =$id_unidade_usuario
AND un.codigo = us.id_unidade
AND ser.uf = un.sigla

") or print(mysql_error());
                  while($dados=mysql_fetch_array($checa)){
                  $sureg = $dados['descricao_servidor'];
                  $uf = $dados['codigo'];
}

$sureg=str_replace("-","",$sureg);
$pg=new sgc_nfe;
      $pg->conectar_nfe("$sureg");




?>

<script src="prototype.js" type="text/javascript"></script>

<script>
  new Ajax.PeriodicalUpdater('ultimas_notas', 'nfe_conab_ajax.php',
  {
   method: 'post',
   parameters: {idus: '<?echo $idusuario?>', uf: '<?echo $uf?>', bloco: 'MONITOR'},
   frequency: <?echo $tempo=atributo('atributo3')?>
   });
</script>



<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: <?echo $titulo?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">


						<table border="1" width="53%" id="table7" bgcolor="#FFFFFF">
							<tr>
								<td width="534">
								<p align="center">Você está na sureg <?Echo $sureg?></td>
							</tr>
							<tr>
								<td width="534">&nbsp;</td>
        					</tr>
						</table>
						<p align="center">&nbsp;</p>
					<p align="center">




                    <div id="ultimas_notas">

                    </div>

                    </p>
					<p align="center">&nbsp;</td>
				</tr>
			</table></td>
		</tr>
	</table>
</div>
<?







}
}else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
