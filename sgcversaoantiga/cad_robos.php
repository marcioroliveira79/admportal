<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Cadastro de Robos";
$titulo_listar="Robos Já Cadastrados";
$arquivo="cad_robos.php";
$tabela="sgc_robos";
$id_item=$_GET['id_item'];

if(!isset($acao_int)){

  include("conf/Pagina.class.php");

    $sql= mysql_query("SELECT count(id_robo) t FROM sgc_robos");
    $dados=mysql_fetch_array($sql);
    $total=$dados['t'];

    $pagina = new Pagina();
    $pagina->setLimite(10);

 	$totalRegistros = $total;
	$linkPaginacao ="?action=$arquivo&id_item=$id_item";


?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_objeto.value=="")
    {
        alert ("\nDigite a descrição para o Robo.");

        document.form1.desc_objeto.style.borderColor="#FF0000";
        document.form1.desc_objeto.style.borderWidth="1px solid";

        nomeform.desc_objeto.focus();
        return false;
    }
     if (nomeform.nome_arquivo.value=="")
    {
        alert ("\nDigite o nome do arquivo robo.");

        document.form1.nome_arquivo.style.borderColor="#FF0000";
        document.form1.nome_arquivo.style.borderWidth="1px solid";

        nomeform.nome_arquivo.focus();
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

<form method="POST" name="form1" action="sgc.php?action=<?echo $arquivo?>&acao_int=cad_objeto" onSubmit="return valida_dados(this)">
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
							<td colspan="3" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
						</tr>
						<tr>
							<td width="308">
							<p align="right">Descrição Robo:&nbsp; </td>
							<td width="711" height="23" colspan="2">
							<input size="68" name="desc_objeto" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="308">
							<p align="right">Nome arquivo:&nbsp; </td>
							<td width="711" height="23" colspan="2">
							<!--webbot bot="Validation" B-Value-Required="TRUE" I-Maximum-Length="25" -->
							<input size="50" name="nome_arquivo" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="50"></td>
						</tr>
							<tr>
							<td width="308">
							<p align="right">Parametros:&nbsp;</td>
							<td width="431" height="23">
							<input size="68" name="parametros" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
							<td width="280" height="23">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" width="739">
							&nbsp;</td>
							<td width="280">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" width="739">
							<p align="center">
							<input type="submit" value="Adicionar" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
							<td width="280">
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
<p>&nbsp;</p>


<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: <?echo $titulo_listar?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="488" cellspacing="0" cellpadding="0">
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="369" height="23"><b>Descrição</b></td>
							<td width="38" height="23">&nbsp;</td>
							<td width="44" height="23">&nbsp;</td>
							<td width="18" height="23">&nbsp;</td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
                        <?
                          $checa = mysql_query("select * from sgc_robos order by quando_cadastrou asc limit ".$pagina->getPagina($_GET['pagina']).", ".$pagina->getLimite());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_objeto = $dados['id_robo'];
                                    $ler_descricao_objeto = $dados['descricao'];
                                    $ler_nome = $dados['nome'];
                                    $ler_parametros = $dados['parametros'];




                        ?>

                        <tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="369" height="23"><?echo $ler_descricao_objeto?> / <?echo $ler_nome?></td>
							<td width="38" height="23">
							<p align="center"><a href="?action=<?echo $arquivo?>&acao_int=editar&id_objeto=<?echo $id_objeto?>&id_item=<?echo $id_item?>"">
							<font color="#000000">Editar</font></a></td>
							<td width="44" height="23">
							<p align="center">
                            <a href="javascript:confirmaExclusao('?action=<?echo $arquivo?>&acao_int=excluir&id_objeto=<?echo $id_objeto?>&id_item=<?echo $id_item?>')">
                            <font color="#000000">Excluir</font></a></td>
							<td width="18" height="23">
							<p align="center"><a href="#" class="dcontexto">
							<font color="#000000">?</font>
                            <span><strong><?echo $ler_descricao_objeto?></strong>
                            </strong><p class="formata"></a>
                            </td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<?
						 }
						?>

					</table></td>
				</tr>
			</table></td>
		</tr>
	</table>
</div>
<br>
<p align="center">
<?
//----------------Paginador-------------------//

Pagina::configuraPaginacao($_GET['cj'],$_GET['pagina'],$totalRegistros,$linkPaginacao, $pagina->getLimite(), $_GET['direcao']);

//--------------------------------------------//


}elseif($acao_int=="editar"){
$id_item=$_GET['id_item'];
       $permissao_item=acesso($idusuario,$id_item);


if($permissao_item=="OK"){
$id_objeto=$_GET['id_objeto'];

?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_objeto.value=="")
    {
        alert ("\nDigite a descrição para o Robo.");

        document.form1.desc_objeto.style.borderColor="#FF0000";
        document.form1.desc_objeto.style.borderWidth="1px solid";

        nomeform.desc_objeto.focus();
        return false;
    }
     if (nomeform.nome_arquivo.value=="")
    {
        alert ("\nDigite o nome do arquivo robo.");

        document.form1.nome_arquivo.style.borderColor="#FF0000";
        document.form1.nome_arquivo.style.borderWidth="1px solid";

        nomeform.nome_arquivo.focus();
        return false;
    }

return true;
}
</script>
<form method="POST" name="form1" action="sgc.php?action=<?echo $arquivo?>&acao_int=edit_objeto" onSubmit="return valida_dados(this)">
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
							<td colspan="3" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_objeto' value='<?echo $id_objeto?>'></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
						</tr>
						<tr>
							<td width="308">
							<p align="right">Descrição Robo:&nbsp; </td>
							<td width="711" height="23" colspan="2">
							<input size="68" name="desc_objeto" value="<?echo tabelainfo($id_objeto,"sgc_robos","descricao","id_robo","")?>" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="308">
							<p align="right">Nome arquivo:&nbsp; </td>
							<td width="711" height="23" colspan="2">
							<!--webbot bot="Validation" B-Value-Required="TRUE" I-Maximum-Length="25" -->
							<input size="50" name="nome_arquivo" value="<?echo tabelainfo($id_objeto,"sgc_robos","nome","id_robo","")?>" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="50"></td>
						</tr>
							<tr>
							<td width="308">
							<p align="right">Parametros:&nbsp;</td>
							<td width="431" height="23">
							<input size="68" name="parametros" value="<?echo tabelainfo($id_objeto,"sgc_robos","parametros","id_robo","")?>" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
							<td width="280" height="23">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" width="739">
							&nbsp;</td>
							<td width="280">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" width="739">
							<p align="center">
							<input type="submit" value="Adicionar" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
							<td width="280">
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
<p>&nbsp;</p>
<?









       }

}elseif($acao_int=="edit_objeto"){
$id_item=$_POST['id_item'];
$permissao_item=acesso($idusuario,$id_item);


if($permissao_item=="OK"){
$id_objeto=$_POST['id_objeto'];
$descricao_objeto=$_POST['desc_objeto'];
$nome_arquivo=$_POST['nome_arquivo'];
$parametros=$_POST['parametros'];

 $update = mysql_query("UPDATE $tabela SET
                    descricao='$descricao_objeto'
                   ,nome='$nome_arquivo'
                   ,parametros='$parametros'
                   ,quem_alterou=$idusuario
                   ,quando_alterou=sysdate()
                    where id_robo=$id_objeto") or print(mysql_error());
                    header("Location: ?action=$arquivo&id_item=$id_item");


}
}elseif($acao_int=="excluir"){

       $id_item=$_GET['id_item'];
       $permissao_item=acesso($idusuario,$id_item);


       if($permissao_item=="OK"){
            $id_objeto=$_GET['id_objeto'];
            $delete = mysql_query("DELETE from $tabela where id_robo=$id_objeto") or print(mysql_error());
            header("Location: ?action=$arquivo&id_item=$id_item");
       }

}elseif($acao_int=="cad_objeto"){

$desc_objeto=$_POST['desc_objeto'];
$nome_arquivo=$_POST['nome_arquivo'];
$parametros=$_POST['parametros'];

$cadas = mysql_query("INSERT INTO sgc_robos (descricao,nome,parametros,quem_cadastrou,quando_cadastrou)
                          VALUES ('$desc_objeto','$nome_arquivo','$parametros',$idusuario,sysdate())") or print(mysql_error());

header("Location: ?action=$arquivo&id_item=$id_item");


}


}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
