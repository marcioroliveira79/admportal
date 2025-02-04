<?php
OB_START();
session_start();


if($permissao=='ok'){
$arquivo="teste_email.php";
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Teste de e-mail";
$id_item=$_GET['id_item'];



if(!isset($acao_int)){
?>
<form method="POST" enctype="multipart/form-data" id="form1" name='meuFormulario' action="sgc.php?action=<?echo $arquivo?>&acao_int=enviar" >
                           <input type="hidden" name="conjunto_selecionado" id="txtSelectedValuesAS"/>
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
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td width="100%" height="23">
							<table border="0" width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td width="311" align="right">E-mail
									destino:</td>
									<td>&nbsp;<input type="text" name="email" size="50" style="background-color: #FFFFFF"></td>
									<td width="20">&nbsp;</td>
								</tr>
								<tr>
									<td width="311" align="right">Assunto:</td>
									<td>&nbsp;<input type="text" name="assunto" size="50" style="background-color: #FFFFFF"></td>
									<td width="20">&nbsp;</td>
								</tr>
								<tr>
									<td width="311" align="right">Mensagem:</td>
									<td>&nbsp;<input type="text" name="mensagem" size="60" style="background-color: #FFFFFF"></td>
									<td width="20">&nbsp;</td>
								</tr>
								<tr>
									<td width="311" align="right">&nbsp;</td>
									<td>&nbsp;</td>
									<td width="20">&nbsp;</td>
								</tr>
								<tr>
									<td width="311" align="right">&nbsp;</td>
									<td>
									<input type="submit" value="Enviar" name="B1"></td>
									<td width="20">&nbsp;</td>
								</tr>
							</table>
							</td>
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



}elseif($acao_int=="enviar"){
echo "<b>Email destino: </b>";echo $email_destino=$_POST['email']; echo "<BR>";
echo "<b>Assunto: </b>";echo $assunto=$_POST['assunto'];     echo "<BR>";
echo "<b>Mensagem: </b>"; echo $mensagem=$_POST['mensagem'];   echo "<BR>";

echo send_mail_smtp ($assunto,$mensagem,$mensagem,$email_destino,"teste");
echo "<BR>";
}
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
