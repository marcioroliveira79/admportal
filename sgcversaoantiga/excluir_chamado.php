<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];
$id_item=$_GET['id_item'];

if(!isset($acao_int)){


$msg=$_GET['msg'];



?>

<script language='javascript'>
function valida_dados_busca (nomeform)
{
    if (nomeform.chamado.value=="")
    {
        alert ("\nDigite o número do chamado para busca.");
        return false;
    }

return true;
}
</script>


<form method="POST" action="?action=excluir_chamado.php&acao_int=buscar&id_item=<?echo $id_item?>"  onSubmit="return valida_dados_busca(this)">
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Excluir Chamado :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
                    <?
                    if($msg!=null){
                    ?>
                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                    	<tr>
							<td width="24">&nbsp;</td>
							<td height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
							<td width="27">&nbsp;</td>
						</tr>
					</table>
					<?
					
					}
					?>
					<table border="0" width="162" cellspacing="0" cellpadding="0">
						<tr>
							<td width="83" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							<p align="right">Chamado#:&nbsp;</td><td width="79" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							<input size="10" name="chamado" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #EEEEEE; float:right" maxlength="10"></td>
							<td width="79" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							<input type="submit" value="Ir!" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #EEEEEE"></td>
						</tr>
						<tr>
							<td colspan="3" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							&nbsp;</td>
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
}elseif($acao_int=="buscar"){
$id_item=$_GET['id_item'];

$key=$_POST['chamado'];

?>
<script language='javascript'>
function valida_dados_busca (nomeform)
{
    if (nomeform.chamado.value=="")
    {
        alert ("\nDigite o número do chamado para busca.");
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


<form method="POST" action="?action=excluir_chamado.php&acao_int=buscar&id_item=<?echo $id_item?>"  onSubmit="return valida_dados_busca(this)">
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Excluir Chamado :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="162" cellspacing="0" cellpadding="0">
						<tr>
							<td width="83" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							<p align="right">Chamado#:&nbsp; </td>
							<td width="79" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							<!--webbot bot="Validation" B-Value-Required="TRUE" I-Maximum-Length="10" -->
							<input size="10" name="chamado" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #EEEEEE; float:right" maxlength="10"></td>
							<td width="79" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							<input type="submit" value="Ir!" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #EEEEEE"></td>
						</tr>
						<tr>
							<td colspan="3" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							&nbsp;</td>
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


<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Resultados:: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="700" cellspacing="0" cellpadding="0">
						<tr>
							<td width="79" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="23">
							<p align="center"><b>ID</b></td>
							<td width="598" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="23">
							<b>Titulo</b></td>
							<td width="22" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="23">
							&nbsp;</td>
						</tr>
						<?
							$checa = mysql_query("SELECT * FROM sgc_chamado WHERE id_chamado=$key") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_chamado = $dados['id_chamado'];
                                    $descricao = $dados['titulo'];
						?>
						<tr>
							<td width="79" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="23">
							<p align="center"><?echo $id_chamado?></td>
							<td width="598" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="23">
							<?echo $descricao?></td>
							<td width="22" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="23">
                            <a href="javascript:confirmaExclusao('?action=excluir_chamado.php&acao_int=excluir&id_chamado=<?echo $id_chamado?>&id_item=<?echo $id_item?>')">
                            <font color="#000000">Excluir</font></a></td>
							

						</tr>
						<?
						}
						?>
						</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>


<?




}elseif($acao_int=="excluir"){

$id_chamado=$_GET['id_chamado'];
$id_item=$_GET['id_item'];

   $permissao_item=acesso($idusuario,$id_item);
   if($permissao_item=="OK"){

     $delete = mysql_query("DELETE from sgc_chamado where id_chamado=$id_chamado") or print(mysql_error());   echo "<BR>";
     $delete = mysql_query("DELETE from sgc_historico_chamado where id_chamado=$id_chamado") or print(mysql_error());  echo "<BR>";
     $delete = mysql_query("DELETE from sgc_contatos_por_chamado where id_chamado=$id_chamado") or print(mysql_error());  echo "<BR>";

     
      $checa = mysql_query("select * from sgc_anexo where id_chamado=$id_chamado") or print(mysql_error());
                                while($dados=mysql_fetch_array($checa)){
                                $nome_arquivo_or = $dados['nome_arquivo'];
                                $caminho = $dados['caminho'];
                                $versao = $dados['versao'];
                                $id_anexo= $dados['id_anexo'];

      $caminho="arquivos";
      $nome_arquivo="v$versao-$nome_arquivo_or";
      unlink("$caminho/$nome_arquivo");
      $deleta = mysql_query("DELETE FROM sgc_anexo where id_anexo=$id_anexo") or print(mysql_error());

      }
   $msg="Chamado Nº:$id_chamado, excluido com sucesso";
   header("Location: ?action=excluir_chamado.php&id_item=$id_item&id_chamado=$id_chamado&msg=$msg");
   }else{
   
   if(atributo('atributo10')=="ON"){

     $nome=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
     $email_nome_adm=atributo("atributo6");
     list ($email_adm, $nome_adm) = split ('[;]',$email_nome_adm);
     $ip_real=get_real_ip();

     $email=email("ROBOT FRIMESA","roboy@frimesa.com.br","$email_adm","$nome_adm","SGC - Tentativa de viloação de Sistema",
     "<p align='center'>ATENÇÃO</p>
      <p align='center'>O usuário: <b>$nome</b>, tentou excluir um chamado sem autorização!<BR>Tela: $tela_chamada <BR> IP: $ip_real </p>");
   }
  }




  }
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
