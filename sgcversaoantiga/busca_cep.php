<?php
OB_START();
session_start();


if($permissao=='ok'){
$arquivo="busca_cep.php";
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Busca CEP";
$id_item=$_GET['id_item'];



if(!isset($acao_int)){
?>
<script language='javascript'>
function mascaraTexto(evento, mascara){

   var campo, valor, i, tam, caracter;

   if (document.all) // Internet Explorer
      campo = evento.srcElement;
   else // Nestcape, Mozzila
       campo= evento.target;

   valor = campo.value;
   tam = valor.length;

   for(i=0;i<mascara.length;i++){
      caracter = mascara.charAt(i);
      if(caracter!="9")
         if(i<tam & caracter!=valor.charAt(i))
            campo.value = valor.substring(0,i) + caracter + valor.substring(i,tam);

   }

}
</script>
<form method="POST" enctype="multipart/form-data" id="form1" name='meuFormulario' action="sgc.php?action=<?echo $arquivo?>&acao_int=consultar" >
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
									<td width="311" align="right">CEP:</td>
									<td>&nbsp;<input type="text" name="cep" onKeyUp="mascaraTexto(event,'99999-999')" size="10"  maxlength="9" style="background-color: #FFFFFF"></td>
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
									<input type="submit" value="Buscar" name="B1"></td>
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



}elseif($acao_int=="consultar"){

$cep=$_POST['cep'];

echo $url = file_get_contents('http://www.buscarcep.com.br/?cep='.$cep.'&formato=xml&chave');
// echo $url = file_get_contents('http://ceplivre.pc2consultoria.com/index.php?module=cep&cep='.$cep.'&formato=xml');




}
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
