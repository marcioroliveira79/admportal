<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Cadastro de parametros do sistema";
$titulo_listar="Parametros Já Cadastrados";
$id_item=$_GET['id_item'];
$arquivo="cad_parametros.php";
$tabela="sgc_parametros_sistema";
$id_chave="id_menu";


if(!isset($acao_int)){
?>
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
				<form method="POST" action="?action=cad_parametros.php&acao_int=editar&fild=atributo1">
					<table border="0" width="471" cellspacing="0" cellpadding="0">
						<tr>
							<td width="120">
							<p align="right">Atributo 1:&nbsp; </td>
							<td width="356" height="23">
                        <input type='hidden' name='id_item' value='<?echo $id_item?>'>
						<input size="68" name="valor" value="<?echo $valor=atributo('atributo1')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="120">
							<p align="right">Descrição:&nbsp; </td>
							<td width="356" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_1')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>
				<form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo2">
					<table border="0" width="471" cellspacing="0" cellpadding="0">
						<tr>
							<td width="120">
							<p align="right">Atributo 2:&nbsp; </td>
							<td width="356" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo2')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="120">
							<p align="right">Descrição:&nbsp; </td>
							<td width="356" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_2')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>
						<form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo3">
					<table border="0" width="471" cellspacing="0" cellpadding="0">
						<tr>
							<td width="120">
							<p align="right">Atributo 3:&nbsp; </td>
							<td width="356" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo3')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="120">
							<p align="right">Descrição:&nbsp; </td>
							<td width="356" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_3')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>
						<form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo4">
					<table border="0" width="471" cellspacing="0" cellpadding="0">
						<tr>
							<td width="120">
							<p align="right">Atributo 4:&nbsp; </td>
							<td width="356" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo4')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="120">
							<p align="right">Descrição:&nbsp; </td>
							<td width="356" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_4')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>
						<form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo5">
					<table border="0" width="471" cellspacing="0" cellpadding="0">
						<tr>
							<td width="120">
							<p align="right">Atributo 5:&nbsp; </td>
							<td width="356" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo5')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="120">
							<p align="right">Descrição:&nbsp; </td>
							<td width="356" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_5')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>


                   <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo6">
					<table border="0" width="471" cellspacing="0" cellpadding="0">
						<tr>
							<td width="120">
							<p align="right">Atributo 6:&nbsp; </td>
							<td width="356" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo6')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="120">
							<p align="right">Descrição:&nbsp; </td>
							<td width="356" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_6')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>


                    <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo7">
					<table border="0" width="471" cellspacing="0" cellpadding="0">
						<tr>
							<td width="120">
							<p align="right">Atributo 7:&nbsp; </td>
							<td width="356" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo7')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="120">
							<p align="right">Descrição:&nbsp; </td>
							<td width="356" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_7')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>

                     <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo8">
					<table border="0" width="471" cellspacing="0" cellpadding="0">
						<tr>
							<td width="120">
							<p align="right">Atributo 8:&nbsp; </td>
							<td width="356" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo8')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="120">
							<p align="right">Descrição:&nbsp; </td>
							<td width="356" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_8')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>


                    <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo9">
					<table border="0" width="471" cellspacing="0" cellpadding="0">
						<tr>
							<td width="120">
							<p align="right">Atributo 9:&nbsp; </td>
							<td width="356" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo9')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="120">
							<p align="right">Descrição:&nbsp; </td>
							<td width="356" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_9')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>


                    <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo10">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 10:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo10')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_10')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>
					
					 <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo11">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 11:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo11')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_11')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>
					
					 <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo12">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 12:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo12')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_12')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>
					
					 <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo13">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 13:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo13')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_13')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>
					
					 <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo14">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 14:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo14')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_14')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>
					
				   <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo15">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 15:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo15')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_15')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>



                    <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo16">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 16:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo16')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_16')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>


                           <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo17">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 17:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo17')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_17')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>

                    <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo18">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 18:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo18')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_18')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>


                     <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo19">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 19:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo19')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_19')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>

                    <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo20">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 20:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo20')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_20')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>


                    <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo21">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 21:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo21')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_21')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>

                      <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo22">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 22:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo22')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_22')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>


                       <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo23">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 23:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo23')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_23')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>

                       <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo24">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 24:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo24')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_24')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>

                      <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo25">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 25:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo25')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_25')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>

                    <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo26">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 26:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo26')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_26')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>


					  <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo27">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 27:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo27')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_27')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>
					
					
					  <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo28">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 28:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo28')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_28')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>
					
					
					  <form method="POST" action="sgc.php?action=cad_parametros.php&acao_int=editar&fild=atributo29">
					<table border="0" width="490" cellspacing="0" cellpadding="0">
						<tr>
							<td width="140">
							<p align="right">Atributo 29:&nbsp; </td>
							<td width="376" height="23">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
							<input size="68" name="valor" value="<?echo $valor=atributo('atributo29')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="140">
							<p align="right">Descrição:&nbsp; </td>
							<td width="376" height="23">
         					<input size="68" name="descricao" value="<?echo $valor=atributo('desc_atr_29')?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
						<tr>
							<td colspan="2">
							<input  type="submit" value="Update" name="B1" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</form>
					
					
					
					

					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
	<p>&nbsp;</div>






<?
  }
elseif($acao_int=="editar"){

     $fild=$_GET['fild'];
     $idusuario = $_SESSION['id_usuario_global'];
     $id_item=$_POST['id_item'];
     
     
     $valor=$_POST['valor'];
     $descricao=$_POST['descricao'];




     $ultimo = substr("$fild",8,8);




    $permissao_item=acesso($idusuario,$id_item);

    if($permissao_item=="OK"){
   
   $cadas = mysql_query("UPDATE sgc_parametros_sistema SET
    atributo$ultimo='$valor'
   ,desc_atr_$ultimo='$descricao'
   ") or print(mysql_error());

     header("Location: ?action=$arquivo&id_item=$id_item");

   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=$arquivo&id_item=$id_item&msg=$msg");
   }




}
elseif($acao_int=="editar"){
$id_objeto=$_GET['id_objeto'];
$id_item=$_GET['id_item'];

$checa = mysql_query("select * from $tabela where $id_chave=$id_objeto ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $ler_descricao_objeto = $dados['descricao'];
                                    $ler_ajuda = $dados['ajuda'];
}




 ?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_objeto.value=="")
    {
        alert ("\nDigite a descricao para o Menu.");

        document.form1.desc_objeto.style.borderColor="#FF0000";
        document.form1.desc_objeto.style.borderWidth="1px solid";

        nomeform.desc_objeto.focus();
        return false;
    }
    if (nomeform.ajuda.value=="")
    {
        alert ("\nDigite a ajuda deste Menu");

        document.form1.ajuda.style.borderColor="#FF0000";
        document.form1.ajuda.style.borderWidth="1px solid";

        nomeform.ajuda.focus();
        return false;
    }


return true;
}
</script>

<script language='javascript'>
function confirmaExclusao(aURL) {
if(confirm('Você esta prestes a apagar este registro,deseja continuar?')) {
location.href = aURL;
}
}
</script>

<style type="text/css">
<!--
  .formata { /* esta classe é somente
               para formatar a fonte */
  font: 12px arial, verdana, helvetica, sans-serif;
  }
  a.dcontexto{
  position:relative;
  font:12px arial, verdana, helvetica, sans-serif;
  padding:0;
  color:#039;
  text-decoration:none;
  border-bottom:2px dotted #039;
  cursor:help;
  z-index:24;
  }
  a.dcontexto:hover{
  background:transparent;
  z-index:25;
  }
  a.dcontexto span{display: none}
  a.dcontexto:hover span{
  display:block;
  position:absolute;
  width:230px;
  top:3em;
  text-align:justify;
  left:0;
  font: 12px arial, verdana, helvetica, sans-serif;
  padding:5px 10px;
  border:1px solid #999;
  background:#e0ffff;
  color:#000;
  }
  -->
</style>

<form method="POST" name="form1" action="sgc.php?action=<?echo $arquivo?>&acao_int=editar_bd" onSubmit="return valida_dados(this)">
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Editar <?echo $titulo?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="488" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
                            <input type='hidden' name='id_objeto' value='<?echo $id_objeto?>'></td>
						</tr>
						<tr>
							<td width="70">
							<p align="right">Menu:&nbsp; </td>
							<td width="418" height="23">
							<?

                            /*
                            if(isset($msg)){
                               $borda="border:1px solid #FF0000;";
                            }else{
                               session_unregister('ajuda');
                               session_unregister('desc_categoria');
                            }
                            */
                            if(!isset($_SESSION['desc_objeto'])){
                            
                               $valor0=$ler_descricao_objeto;
                               $valor01=$ler_ajuda;
                            
                            }else{
                               $valor0=$_SESSION['desc_objeto'];
                               $valor01=$_SESSION['ajuda'];
                            }
                            
                            ?>
							<input size="68" name="desc_objeto" value="<?echo $valor0?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						<tr>
							<td width="488" colspan="2" height="23">
							<p align="center">Descrição do Menu(AJUDA)</td>
						</tr>
						<tr>
							<td height="23" width="70">
							<p align="right">&nbsp; </td>
							<td height="23" width="418">
							<textarea rows="6" name="ajuda" cols="67" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #FFFFFF;"><?echo $valor01?></textarea></td>
						</tr>
						<tr>
							<td colspan="2">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">
							<input type="submit" value="Editar Menu" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>
</form>
<?


 }elseif($acao_int=="excluir"){

 $idusuario = $_SESSION['id_usuario_global'];
 $id_item=$_POST['id_item'];
 $id_objeto=$_GET['id_objeto'];

 echo $permissao_item=acesso($idusuario,$id_item);
  
  if($permissao_item=="OK"){
     $deleta = mysql_query("DELETE FROM $tabela where $id_chave=$id_objeto") or print(mysql_error());
    //VER ISSO// $cadas = mysql_query("UPDATE sgc_item_menu SET where $id_chave='$id_objeto'") or print(mysql_error());
     header("Location: ?action=$arquivo&id_item=$id_item");
   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=$arquivo&id_item=$id_item&msg=$msg");
   }
   
   
  }elseif($acao_int=="cad_objeto"){
  
       $id_item=$_POST['id_item'];
       
       $permissao_item=acesso($idusuario,$id_item);
       if($permissao_item=="OK"){
  
       $desc_objeto=$_POST['desc_objeto'];
       $desc_objeto=ltrim("$desc_objeto");
       session_register('desc_objeto');
       
       $ajuda=$_POST['ajuda'];
       session_register('ajuda');

       $integridade=integridade($desc_objeto,$tabela,"descricao","descricao");


  
    if($integridade=="Existe"){

      header("Location: ?action=$arquivo&id_item=$id_item&msg=Já existe um Menu com este nome");
  
    }else{

      $cadas = mysql_query("INSERT INTO $tabela (descricao, ajuda, data_criacao, quem_criou) VALUES ('$desc_objeto','$ajuda',sysdate(),$idusuario)") or print(mysql_error());
      session_unregister('ajuda');
      session_unregister('desc_objeto');
      header("Location: ?action=$arquivo&id_item=$id_item");
    }

    }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=$arquivo&id_item=$id_item&msg=$msg");
   }
    
    
  }
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
