<?php
OB_START();
session_start();


if($permissao=='ok'){
$arquivo="busca_fone.php";
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Busca FONE";
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
									<td width="311" align="right">Nome:</td>
									<td>&nbsp;<input type="text" name="nome"  size="50"  maxlength="50" style="background-color: #FFFFFF"></td>
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

$nome=$_POST['nome'];

$nome=ltrim($nome);

if($nome==""){
header("Location: ?action=$arquivo");
}








if(strstr($nome,"+")){
   $pecas = explode("+", $nome);
foreach ($pecas as $value) {

    if(strlen($value)==2){
      $uf=$value;
    }else{
       if(strstr($value,"@")){
         $email=$value;
       }else{
         $nome_sep = $value;
       }
    }
  }
}else{
   if(strstr($nome,"@")){
         $email=$nome;
   }else{
        $nome_sep=$nome;
   }
}








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
									<td width="311" align="right">Nome:</td>
									<td>&nbsp;<input type="text" name="nome"  size="50"  maxlength="50" style="background-color: #FFFFFF"></td>
									<td width="20">&nbsp;</td>
								</tr>

							</td>
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
$total = 0;
?>
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Resultado :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td width="100%" height="23">
							<table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <?



                                
                                if($uf != null){
                                   $uf="and u.sigla = '$uf' ";
                                }

                                if($email != null){
                                   $email=" and su.email like '%$email%' ";

                                }
                                if($nome_sep != null){
                                   $nome_sep=" and CONCAT(su.primeiro_nome,su.ultimo_nome) like '%$nome_sep%' ";
                                }


                                $checa1 = mysql_query("
                                SELECT concat(primeiro_nome,' ',ultimo_nome,' - ',sigla,' - ',dp.descricao)nome,
                                if(ddd is not null and ddd != 0 and telefone is not null and telefone != 0
                                ,concat('(',ddd,')',' ',telefone,' - ',ramal,' - ',email)
                                ,concat('Registro Tel. Incompleto',' - ',email))fone
                                FROM
                                sgc_usuario su
                                , sgc_unidade u
                                , sgc_departamento dp
                                where 1=1

                                $nome_sep
                                $uf
                                $email

                                and u.codigo = su.id_unidade
                                and dp.id_departamento = su.id_departamento

                                order by nome ") or print(mysql_error());
                                while($dados1=mysql_fetch_array($checa1)){
                                $nome = $dados1['nome'];
                                $telefone = $dados1['fone'];
                                $total++;
                                ?>
                                <tr>
									<td width="430" align="right" height="23">
									<p align="left"><?echo $nome?></td>
									<td height="23">
									<p align="left"><?echo $telefone?></td>
									<td width="20" height="23">&nbsp;</td>
								</tr>
								<?

                                }

                                ?>
								<tr>
									<td width="430" align="right" height="23">
									<font color="#0000FF">Total de registro(s)
									encontrado(s):</font></td>
									<td height="23"><font color="#0000FF">&nbsp;<?echo $total?></font></td>
									<td width="20" height="23">&nbsp;</td>
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
<?





}
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
