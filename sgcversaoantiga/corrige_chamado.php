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


<form method="POST" action="?action=corrige_chamado.php&acao_int=buscar&id_item=<?echo $id_item?>"  onSubmit="return valida_dados_busca(this)">
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Corrige Chamado :: </b></td>
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
if(confirm('Você esta prestes à alterar a linha do historico do chamado, deseja continuar?')) {
location.href = aURL;
}
}
</script>


<form method="POST" action="?action=corrige_chamado.php&acao_int=buscar&id_item=<?echo $id_item?>"  onSubmit="return valida_dados_busca(this)">
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Corrige Chamado :: </b></td>
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
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td width="79" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="23">
							<p align="center"><b>ID</b></td>
							<td width="724" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="23">
							<b>Titulo</b></td>
							<td width="88" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="5">
							<b>Linha Atual</b></td>
                            <td width="91" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="5">
							<b>Linha Correta</b></td>
                            <td width="22" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="23">
							&nbsp;</td>
						</tr>
						<?
							$checa = mysql_query("
                            SELECT
                               ch.id_chamado
                              ,ch.id_linha_historico
                              ,ch.titulo
                              ,ch.id_area_locacao
                              ,ch.id_suporte
                             ,(SELECT CASE WHEN  ch.id_linha_historico  IS NULL OR ch.id_linha_historico  < hc.id_historico   THEN
                              (SELECT id_historico FROM sgc_historico_chamado WHERE id_chamado = ch.id_chamado ORDER BY id_historico DESC LIMIT 1)
                               ELSE
                                 hc.id_historico
                               END
                               FROM sgc_historico_chamado hc WHERE hc.id_chamado = ch.id_chamado ORDER BY hc.id_historico DESC LIMIT 1) as correto

                                     FROM sgc_chamado ch
                                     WHERE 1=1
                                     AND ch.id_chamado = $key
                                    ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_chamado = $dados['id_chamado'];
                                    $descricao = $dados['titulo'];
                                    $id_linha_historico = $dados['id_linha_historico'];
                                    $correto = $dados['correto'];
                                    $id_area_locacao = $dados['id_area_locacao'];
                                    $idsuporte = $dados['id_suporte'];

                                    If($id_linha_historico=="" or $id_linha_historico != $correto ){
                                       $id_linha_historico="ERRO";
                                       $msg="Houve um erro durante a gravação da linha do historico no chamado";
                                       $botao="Ativo";
                                       $errohistorico="sim";
                                    }else{
                                       $msg="Este chamado esta OK";
                                       $botao="NaoAtivo";
                                    }
                                    if($id_area_locacao==0){
                                      $msg="ERRO - Não foi registrado a área de locação, chamado será apontado para o usuário que o parametrizou";
                                      $botao="Ativo";
                                      $errolocacao="sim";
                                    }
                                    

                                    
                                    
                                    
						?>
						<tr>
							<td width="79" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="23">
							<p align="center"><?echo $id_chamado?></td>
							<td width="724" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="23">
							<?echo $descricao?></td>
							<td width="88" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="5">
							<?echo $id_linha_historico?></td>
                            <td width="91" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="5">
							<?echo $correto?></td>


							<td width="22" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="23">

                            <? if($botao=="Ativo"){ ?>
                            <a href="javascript:confirmaExclusao('?action=corrige_chamado.php&acao_int=corrigir&id_chamado=<?echo $id_chamado?>&id_item=<?echo $id_item?>&errohistorico=<?echo $errohistorico?>&correto=<?echo $correto?>&errolocacao=<?echo $errolocacao?>&idsuporte=<?echo $idsuporte?>')">
                            <font color="#000000">Corrigir</font></a>
                            <?
                            }
                            ?>

                            </td>


						</tr>
						<?
						}
						?>
						<tr>
							<td width="1004" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" height="23" colspan="5">
							<p align="center">
							<b><?echo $msg?></b></td>


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




}elseif($acao_int=="corrigir"){

$id_chamado=$_GET['id_chamado'];
$errolocacao=$_GET['errolocacao'];
$errohistorico=$_GET['errohistorico'];
$correto=$_GET['correto'];
$idsuporte=$_GET['idsuporte'];
$id_item=$_GET['id_item'];

$idareasuporte=areasuporte($idsuporte);

   $permissao_item=acesso($idusuario,$id_item);
   if($permissao_item=="OK"){

     if($errohistorico=="sim"){
     $update = mysql_query("UPDATE sgc_chamado SET id_linha_historico ='$correto' WHERE id_chamado=$id_chamado") or print(mysql_error());   echo "<BR>";
     }
    if($errolocacao=="sim"){
     $update = mysql_query("UPDATE sgc_chamado SET id_area_locacao ='$idareasuporte' WHERE id_chamado=$id_chamado") or print(mysql_error());   echo "<BR>";
     }

     
   $msg="Chamado Nº:$id_chamado, corrigido com sucesso";
   header("Location: ?action=corrige_chamado.php&id_item=$id_item&id_chamado=$id_chamado&msg=$msg");
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
