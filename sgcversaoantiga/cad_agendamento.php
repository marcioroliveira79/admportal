<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Programas Agendados";
$titulo_listar="Programas Já Agendados";
$arquivo="cad_agendamento.php";
$tabela="sgc_agendamento";
$id_item=$_GET['id_item'];

if(!isset($acao_int)){

  include("conf/Pagina.class.php");

    $sql= mysql_query("SELECT count(id_agendamento) t FROM sgc_agendamento");
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
        alert ("\nDigite a descricao para o Agendamento.");

        document.form1.desc_objeto.style.borderColor="#FF0000";
        document.form1.desc_objeto.style.borderWidth="1px solid";

        nomeform.desc_objeto.focus();
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
							<p align="right">Descrição:&nbsp; </td>
							<td width="711" height="23" colspan="2">
							<input size="68" name="desc_objeto" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="308">
							<p align="right">Robo:&nbsp;</td>
							<td width="711" height="23" colspan="2">
							<select size="1" style=" font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="robo">
                            <?
                                    $checa = mysql_query("select id_robo,concat(descricao,' / ',nome) descricao from sgc_robos order by id_robo asc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_robo = $dados['id_robo'];
                                    $ler_descricao = $dados['descricao'];
                            ?>
                            <option value="<?echo $id_robo?>"><?echo $ler_descricao?></option>
                            <?
                            }
                            ?>
                            </select></td>
						</tr>
							<tr>
							<td width="308">
							<p align="right">Data:&nbsp;</td>
							<td width="431" height="23">
							<select size="1" style="  font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="data_agendamento">
                             <?
                                    $checa = mysql_query("select id_data_agendamento,descricao_data descricao from sgc_datas_agendamento order by id_data_agendamento asc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_data = $dados['id_data_agendamento'];
                                    $ler_descricao = $dados['descricao'];
                            ?>
                            <option value="<?echo $id_data?>"><?echo $ler_descricao?></option>
                            <?
                            }
                            ?>
                            </select></td>
							<td width="280" height="23">
							&nbsp;</td>
						</tr>
						<tr>
							<td width="100%" colspan="3" height="23">
							<p align="center">&nbsp;</td>
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
                          $checa = mysql_query("select * from sgc_agendamento order by id_agendamento asc limit ".$pagina->getPagina($_GET['pagina']).", ".$pagina->getLimite());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_objeto = $dados['id_agendamento'];
                                    $ler_descricao_objeto = $dados['descricao_agendamento'];




                        ?>

                        <tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="369" height="23"><?echo $ler_descricao_objeto?></td>
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
        alert ("\nDigite a descricao para o Agendamento.");

        document.form1.desc_objeto.style.borderColor="#FF0000";
        document.form1.desc_objeto.style.borderWidth="1px solid";

        nomeform.desc_objeto.focus();
        return false;
    }



return true;
}
</script>
<form method="POST" name="form1" action="sgc.php?action=<?echo $arquivo?>&acao_int=editar_bd" onSubmit="return valida_dados(this)">
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
                            <input type='hidden' name='id_objeto' value='<?echo $id_objeto?>'></td>
						</tr>
						<tr>
							<td width="308">
							<p align="right">Descrição:&nbsp; </td>
							<td width="711" height="23" colspan="2">
							<input size="68" value="<?echo tabelainfo($id_objeto,"sgc_agendamento","descricao_agendamento","id_agendamento","")?>" name="desc_objeto"  style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="308">
							<p align="right">Robo:&nbsp;</td>
							<td width="711" height="23" colspan="2">
							<select size="1" style=" font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="robo">
                            <?
                                    $posicao_robo=tabelainfo($id_objeto,"sgc_agendamento","id_robo","id_agendamento","");
                                    $checa = mysql_query("select id_robo,concat(descricao,' / ',nome) descricao from sgc_robos order by id_robo=$posicao_robo desc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_robo = $dados['id_robo'];
                                    $ler_descricao = $dados['descricao'];
                            ?>
                            <option value="<?echo $id_robo?>"><?echo $ler_descricao?></option>
                            <?
                            }
                            ?>
                            </select></td>
						</tr>
							<tr>
							<td width="308">
							<p align="right">Data:&nbsp;</td>
							<td width="431" height="23">
							<select size="1" style="  font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="data_agendamento">
                             <?
                                    $posicao_data=tabelainfo($id_objeto,"sgc_agendamento","id_data_agendamento","id_agendamento","");
                                    $checa = mysql_query("select id_data_agendamento,descricao_data descricao from sgc_datas_agendamento order by id_data_agendamento=$posicao_data desc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_data = $dados['id_data_agendamento'];
                                    $ler_descricao = $dados['descricao'];
                            ?>
                            <option value="<?echo $id_data?>"><?echo $ler_descricao?></option>
                            <?
                            }
                            ?>
                            </select></td>
							<td width="280" height="23">
							&nbsp;</td>
						</tr>
						<tr>
							<td width="100%" colspan="3" height="23">
							<p align="center">&nbsp;</td>
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
}elseif($acao_int=="editar_bd"){
       $id_item=$_POST['id_item'];
       $permissao_item=acesso($idusuario,$id_item);


       if($permissao_item=="OK"){
           $id_objeto=$_POST['id_objeto'];
           $descricao=$_POST['desc_objeto'];
           $id_robo=$_POST['robo'];
           $id_data=$_POST['data_agendamento'];
            $delete = mysql_query("UPDATE $tabela SET descricao_agendamento='$descricao',id_robo=$id_robo,id_data_agendamento=$id_data  where id_agendamento=$id_objeto") or print(mysql_error());
            header("Location: ?action=$arquivo&id_item=$id_item");
       }


}elseif($acao_int=="excluir"){

       $id_item=$_GET['id_item'];
       $permissao_item=acesso($idusuario,$id_item);


       if($permissao_item=="OK"){
            $id_objeto=$_GET['id_objeto'];
            $delete = mysql_query("DELETE from $tabela where id_agendamento=$id_objeto") or print(mysql_error());
            header("Location: ?action=$arquivo&id_item=$id_item");
       }

}elseif($acao_int=="cad_objeto"){

 $id_item=$_POST['id_item'];
       $permissao_item=acesso($idusuario,$id_item);


       if($permissao_item=="OK"){
            $id_objeto=$_POST['id_objeto'];
            $desc_objeto=$_POST['desc_objeto'];
            $robo=$_POST['robo'];
            $data_agendamento=$_POST['data_agendamento'];
            $cad = mysql_query("INSERT INTO $tabela (descricao_agendamento,quem_agendou,quando_agendou,id_robo,id_data_agendamento) VALUES ('$desc_objeto',$idusuario,sysdate(),$robo,$data_agendamento)") or print(mysql_error());
            header("Location: ?action=$arquivo&id_item=$id_item");
       }




}


}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
