<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];
$id_item=$_GET['id_item'];
$arquivo="rel_estatistico_analista.php";
$titulo="Relatório Estatístico Analista";



if(!isset($acao_int)){
if(chamado_fechado_falta_enquete($idusuario)!=null){
  $id_chamado_enquete=chamado_fechado_falta_enquete($idusuario);
  header("Location: ?action=vis_chamado.php&acao_int=enquete&id_chamado=$id_chamado_enquete");
}

?>

<script language='javascript'>
function valida_dados (nomeform){
 	if (   nomeform.area.value=="Selecione" || nomeform.area.value==""){
	    alert ("\n Você precisa selecionar a área de suporte");
        return false;
	}
	if (   nomeform.analista_change.value=="Todos" || nomeform.analista_change.value==""){
	    alert ("\n Você precisa selecionar o analista");
        return false;
	}
}
</script>




<script type="text/javascript" src="conf\prototype.js"></script>
<script language="javascript"  src="ajax-area-analista-relatorio.js" type="text/javascript"></script>



<form method="POST" id="form1" name='meuFormulario' action="sgc.php?action=<?echo $arquivo?>&acao_int=visualizar" onsubmit='return valida_dados(this)' style="font-family: Verdana; font-size: 8pt">

         <input type='hidden' name='id_item' value='<?echo $id_item?>'>

<div align="center">
	<form method="POST" id="form1" name='meuFormulario' enctype="multipart/form-data"  action="?action=<?echo $arquivo?>&acao_int=buscar">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: <?echo $titulo?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="80%" cellspacing="0" cellpadding="0">
						<tr>
							<td width="2%">&nbsp;</td>
							<td height="23" width="559" colspan="2">
							<p align="center">
												<font color="#FF0000" size="1"><?echo $msg?></font></td>
							<td width="4%">&nbsp;</td>
						</tr>

						<tr>
							<td width="2%">&nbsp;</td>
							<td height="15" align="right" width="31%">

												Área de Atuação Suporte:&nbsp;</td>
							<td height="23" width="62%">
										<font size="1">
												<select size="1" name="area" Onchange="atualiza(this.value);" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                              <option value="Selecione">Selecione</option>
                            	  <?
                            $checa_menu = mysql_query("SELECT id_area_locacao,descricao FROM sgc_area_locacao order by id_area_locacao") or print mysql_error();
                                    while($dados_menu=mysql_fetch_array($checa_menu)){
                                    $id_area= $dados_menu["id_area_locacao"];
                                    $descricao= $dados_menu["descricao"];

                                ?>
     							<option value="<?echo $id_area?>"><?echo $descricao?></option>
                                <?
                           }
                        ?>
						</select></td>
							<td width="4%">&nbsp;</td>
						</tr>
						<tr>
							<td width="2%">&nbsp;</td>
							<td height="23" align="right" width="31%">Analista:&nbsp;</td>
							<td height="23" width="62%"><font size="1">
                            <div id="atualiza" >
                            </div>
         				</td>
							<td width="4%"></td>


							<tr>
								<td width="2%">&nbsp;</td>
								<td height="23" width="31%">&nbsp;</td>
								<td height="23" width="62%">
										<font size="1">
												<input type="submit" value="Buscar" name="B1"></td>
								<td width="4%">&nbsp;</td>
							</tr>
							<tr>
								<td width="2%">&nbsp;</td>
								<td height="23" width="31%">&nbsp;</td>
								<td height="23" width="62%">&nbsp;</td>
								<td width="4%">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table></td>
		</tr>
	</table>
</form>
</div>
<?




}elseif($acao_int=="visualizar"){
     $id_item=$_POST['id_item'];
     $analista_change=$_POST['analista_change'];
     $area=$_POST['area'];

     $acesso_visulizar=acesso($idusuario,$id_item);
  if($acesso_visulizar=="OK"){


  }
 }
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
