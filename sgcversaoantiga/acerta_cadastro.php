<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Editar de Usuário";
$titulo_listar="Usuários Já Cadastrados";
$arquivo="acerta_cadastro.php";
$tabela="sgc_usuario";
$id_item=$_GET['id_item'];
$id_chave="id_usuario";


if(!isset($acao_int)){

$id_usuario=$_GET['id_usuario'];
$id_item=$_GET['id_item'];

$checa = mysql_query("select * from sgc_usuario where id_usuario=$idusuario") or print(mysql_error());
         while($dados=mysql_fetch_array($checa)){
         $id_usuario = $dados['id_usuario'];
         $objeto1 = $dados['primeiro_nome'];
         $objeto2 = $dados['ultimo_nome'];
         $objeto3 = $dados['email'];
         $objeto4 = $dados['ddd'];
         $objeto5 = $dados['telefone'];
         $objeto6 = $dados['ramal'];
         $objeto7 = $dados['id_unidade'];
         $objeto8 = $dados['id_departamento'];
         $objeto9 = $dados['id_centro'];
         $objeto10 = $dados['perfil'];
         $objeto11 = $dados['externo'];
         $objeto12 = $dados['senha'];
         $objeto13 = $dados['desativacao'];
         $objeto14 = $dados['data_criacao'];
         $objeto15 = $dados['quem_alterou'];
         $objeto16 = $dados['oque_alterou'];
         $ultimo_acesso = $dados['ultimo_acesso'];

}

?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.objeto4.value=="")
    {
        alert ("\nDigite o DDD.");
        return false;
    }
     if (nomeform.objeto5.value=="")
    {
        alert ("\nDigite o Número.");
        return false;
    }
    if (nomeform.objeto6.value=="")
    {
        alert ("\nDigite o Ramal ");
        return false;
    }


return true;
}
</script>

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






<form method="POST" name="form1" action="?action=<?echo $arquivo?>&acao_int=edit_objeto" onSubmit="return valida_dados(this)">
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Confirme Seus Dados :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
                            <input type='hidden' name='id_usuario' value='<?echo $id_usuario?>'>
                            </td>
						</tr>
    					<tr>
							<td width="306">
							<p align="right">Telefone:&nbsp;&nbsp; </td>
							<td width="713" height="23">
							<input size="2" name="objeto4" value="<?echo $objeto4?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="2"><!--webbot bot="Validation" b-value-required="TRUE" i-maximum-length="9" --><input size="9" name="objeto5" value="<?echo $objeto5?>" onKeyUp="mascaraTexto(event,'9999-9999')" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="9">
							Ramal:
							<input size="4" name="objeto6" value="<?echo $objeto6?>"   style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="4"></td>
						</tr>
   						<tr>
							<td colspan="2">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">
                                   <input type="submit" value="Confirmar" name="botao2"  style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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
<p>&nbsp;</p>

<?

}elseif($acao_int=="edit_objeto"){
$ddd=$_POST['objeto4'];
$numero=$_POST['objeto5'];
$ramal=$_POST['objeto6'];

$cadas = mysql_query("UPDATE $tabela SET ddd='$ddd',telefone='$numero',ramal='$ramal',data_alteracao=sysdate(),quem_alterou=$idusuario,oque_alterou='Acerto de Telefone' where id_usuario=$idusuario") or print(mysql_error());
              header("Location: ?action=abertura_chamado.php");

}
}else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
