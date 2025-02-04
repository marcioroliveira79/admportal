<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Cadastro Centro de Custo";
$titulo_listar="Centros Já Cadastrados";
$arquivo="cad_centro_custo.php";
$tabela="sgc_centro_custo";
$id_item=$_GET['id_item'];

if(!isset($acao_int)){




    include("conf/Pagina.class.php");

    $sql= mysql_query("SELECT count(id_centro) t FROM sgc_centro_custo");
    $dados=mysql_fetch_array($sql);
    $total=$dados['t'];

    $pagina = new Pagina();
    $pagina->setLimite(10);

 	$totalRegistros = $total;
	$linkPaginacao ="?action=cad_centro_custo.php&id_item=$id_item";


?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_objeto.value=="")
    {
        alert ("\nDigite a descricao para o Centro de Custo.");

        document.form1.desc_objeto.style.borderColor="#FF0000";
        document.form1.desc_objeto.style.borderWidth="1px solid";

        nomeform.desc_objeto.focus();
        return false;
    }
     if (nomeform.codigo.value=="")
    {
        alert ("\nDigite o código para o Centro de Custo.");

        document.form1.codigo.style.borderColor="#FF0000";
        document.form1.codigo.style.borderWidth="1px solid";

        nomeform.codigo.focus();
        return false;
    }
    if (nomeform.ajuda.value=="")
    {
        alert ("\nDigite a ajuda deste Centro de Custo ");

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
							<p align="right">Descrição Centro de Custo:&nbsp; </td>
							<td width="711" height="23" colspan="2">
							<input size="68" name="desc_objeto" value="<?echo $_SESSION['desc_objeto']?>" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="308">
							<p align="right">Código:&nbsp; </td>
							<td width="711" height="23" colspan="2">
							<input size="5" name="codigo" value="<?echo $_SESSION['codigo']?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="5"></td>
						</tr>
							<tr>
							<td width="308">
							<p align="right">Área:&nbsp;</td>
							<td width="711" height="23" colspan="2">
							<select size="1" style=" font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="area">
                            <?
                                    $checa = mysql_query("select id_area,concat(codigo,'-',descricao) descricao from sgc_area_negocio order by codigo asc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_area = $dados['id_area'];
                                    $ler_descricao = $dados['descricao'];
                            ?>
                            <option value="<?echo $id_area?>"><?echo $ler_descricao?></option>
                            <?
                            }
                            ?>
                            </select></td>
						</tr>
							<tr>
							<td width="308">
							<p align="right">Tipo gasto:&nbsp;</td>
							<td width="431" height="23">
							<select size="1" style="  font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="tipo_gasto">
                             <?
                                    $checa = mysql_query("select id_gasto,concat(codigo,'-',descricao) descricao from sgc_tipo_gasto order by codigo asc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_gasto = $dados['id_gasto'];
                                    $ler_descricao = $dados['descricao'];
                            ?>
                            <option value="<?echo $id_gasto?>"><?echo $ler_descricao?></option>
                            <?
                            }
                            ?>
                            </select></td>
							<td width="280" height="23">
							&nbsp;</td>
						</tr>
						<tr>
							<td width="100%" colspan="3" height="23">
							<p align="center">Descrição do Centro de Custo(AJUDA)</td>
						</tr>
						<tr>
							<td height="23" width="308">
							<p align="right">&nbsp; </td>
							<td height="23" width="431">
							<textarea rows="6" name="ajuda" cols="67" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #FFFFFF;"><?echo $_SESSION['ajuda']?></textarea></td>
							<td height="23" width="280">
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
							<input type="submit" value="Adicionar Centro de Custo" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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
                          $checa = mysql_query("select
                          cc.id_centro
                          ,cc.ajuda
                          ,concat(cc.codigo,' - ',cc.descricao,' - Área: ',an.descricao,' - Tipo Gasto: ',tg.descricao) descricao
                          ,concat(cc.codigo,' - ',cc.descricao) resumida
                          ,desativado
                          from sgc_centro_custo cc, sgc_area_negocio an, sgc_tipo_gasto tg
                          where an.id_area = cc.id_area
                          and tg.id_gasto = cc.id_gasto
                          order by cc.descricao asc limit ".$pagina->getPagina($_GET['pagina']).", ".$pagina->getLimite());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_objeto = $dados['id_centro'];
                                    $ler_descricao_objeto = $dados['descricao'];
                                    $ler_resumida = $dados['resumida'];
                                    $ler_ajuda = $dados['ajuda'];
                                    $desativacao = $dados['desativado'];

                        $desativacao = $dados['desativado'];

                        if($desativacao!=null){
                         $acao="Ativar";
                        }else{
                         $acao="Desativar";
                        }

                        ?>

                        <tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="369" height="23"><?echo $ler_resumida?></td>
							<td width="38" height="23">
							<p align="center"><a href="?action=<?echo $arquivo?>&acao_int=editar&id_objeto=<?echo $id_objeto?>&id_item=<?echo $id_item?>"">
							<font color="#000000">Editar</font></a></td>
							<td width="44" height="23">
							<p align="center">
                            <a href="javascript:confirmaExclusao('?action=<?echo $arquivo?>&acao_int=excluir&id_objeto=<?echo $id_objeto?>&id_item=<?echo $id_item?>')">
                            <font color="#000000"><?echo $acao?></font></a></td>
							<td width="18" height="23">
							<p align="center"><a href="#" class="dcontexto">
							<font color="#000000">?</font>
                            <span><strong><?echo $ler_descricao_objeto?></strong> - <?echo $ler_ajuda?>
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


  }
elseif($acao_int=="editar_bd"){

     $idusuario = $_SESSION['id_usuario_global'];
     $id_objeto=$_POST['id_objeto'];
     $id_item=$_POST['id_item'];


     $desc_objeto=$_POST['desc_objeto'];
     $ajuda=$_POST['ajuda'];
     $codigo=$_POST['codigo'];
     $tipo_gasto=$_POST['tipo_gasto'];
     $area=$_POST['area'];
     
     session_unregister('ajuda');
     session_unregister('desc_objeto');
     session_unregister('codigo');
     session_unregister('tipo_gasto');
     session_unregister('area');


    $permissao_item=acesso($idusuario,$id_item);
    $codigo_antigo=tabelainfo($id_objeto,"sgc_centro_custo","codigo","id_centro");

   if($permissao_item=="OK"){

echo $existe_codigo=integridade("$codigo","$tabela","codigo","codigo","and id_centro!=$id_objeto");

    if($existe_codigo=="Existe"){

     $msg="Esse código já esta sendo usado";
     session_unregister('ajuda');
     session_unregister('desc_objeto');
     session_unregister('codigo');
     session_unregister('tipo_gasto');
     session_unregister('area');
     header("Location: ?action=$arquivo&acao_int=editar&id_objeto=$id_objeto&id_item=$id_item&msg=$msg&desc_objeto=$desc_objeto&ajuda=$ajuda&codigo=$codigo&tipo_gasto=$tipo_gasto&area=$area");
    

    }else{
echo "aqui";
       $cadas = mysql_query("UPDATE $tabela SET id_gasto='$tipo_gasto', id_area='$area',codigo='$codigo',descricao='$desc_objeto',ajuda='$ajuda',data_alteracao=sysdate(),quem_alterou=$idusuario,oque_alterou='DESCRICAO OU CODIGO OU GASTO OU AREA' where id_centro='$id_objeto'") or print(mysql_error());
       session_unregister('ajuda');
       session_unregister('desc_objeto');
       session_unregister('codigo');
       session_unregister('tipo_gasto');
       session_unregister('area');
       header("Location: ?action=$arquivo&id_item=$id_item");
     }

     if($codigo_antigo!=$codigo){

          $update = mysql_query("UPDATE sgc_chamado set id_centro=$codigo where id_centro=$codigo_antigo") or print(mysql_error());
          $update = mysql_query("UPDATE sgc_usuario set id_centro=$codigo where id_centro=$codigo_antigo") or print(mysql_error());

    }

   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=$arquivo&id_item=$id_item&msg=$msg");
   }




}
elseif($acao_int=="editar"){
$id_objeto=$_GET['id_objeto'];
$id_item=$_GET['id_item'];

$checa = mysql_query("select * from $tabela where id_centro=$id_objeto ") or print(mysql_error());
                                while($dados=mysql_fetch_array($checa)){
                                $ler_descricao_objeto = $dados['descricao'];
                                $ler_ajuda = $dados['ajuda'];
                                $ler_codigo = $dados['codigo'];
                                $ler_area = $dados['id_area'];
                                $ler_gasto = $dados['id_gasto'];
}




 ?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_objeto.value=="")
    {
        alert ("\nDigite a descricao para o Centro de Custo.");

        document.form1.desc_objeto.style.borderColor="#FF0000";
        document.form1.desc_objeto.style.borderWidth="1px solid";

        nomeform.desc_objeto.focus();
        return false;
    }
     if (nomeform.codigo.value=="")
    {
        alert ("\nDigite o código para o Centro de Custo.");

        document.form1.codigo.style.borderColor="#FF0000";
        document.form1.codigo.style.borderWidth="1px solid";

        nomeform.codigo.focus();
        return false;
    }
    if (nomeform.ajuda.value=="")
    {
        alert ("\nDigite a ajuda deste Centro de Custo ");

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
					<td class="info" align="middle"><b>::Editar <?echo $titulo?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
                            <input type='hidden' name='id_objeto' value='<?echo $id_objeto?>'></td>
						</tr>
						<tr>
							<td width="242">
							<p align="right">Descrição Centro de Custo:&nbsp; </td>
							<td width="551" height="23">
							<?
							if(isset($msg)){
                               $borda="border:1px solid #FF0000;";
                            }else{
                               session_unregister('ajuda');
                               session_unregister('desc_objeto');
                               session_unregister('codigo');
                            }
                             if(!isset($_SESSION['desc_objeto'])){

                               $valor0=$ler_descricao_objeto;
                               $valor01=$ler_ajuda;
                               $valor02=$ler_codigo;
                               $valor03=$ler_area;
                               $valor04=$ler_gasto;

                            }else{
                               $valor0=$_SESSION['desc_objeto'];
                               $valor01=$_SESSION['ajuda'];
                               $valor02=$_SESSION['codigo'];
                               $valor03=$_SESSION['area'];
                               $valor04=$_SESSION['tipo_gasto'];
                            }




                            ?>
							<input size="68" name="desc_objeto" value="<?echo $valor0?>" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="242">
							<p align="right">Código:&nbsp; </td>
							<td width="551" height="23">
							<input size="5" name="codigo" value="<?echo $valor02?>" style=" font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="5"></td>
						</tr>
							<tr>
							<td width="242">
							<p align="right">Área:&nbsp;</td>
							<td width="551" height="23">
							<select size="1" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="area">
                            <?
                                    $checa = mysql_query("select id_area,concat(codigo,'-',descricao) descricao from sgc_area_negocio

                                    order by id_area=$valor03 desc, codigo asc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_area = $dados['id_area'];
                                    $ler_descricao = $dados['descricao'];
                            ?>
                            <option value="<?echo $id_area?>"><?echo $ler_descricao?></option>
                            <?
                            }
                            ?>
                            </select></td>
						</tr>
							<tr>
							<td width="242">
							<p align="right">Tipo gasto:&nbsp;</td>
							<td width="551" height="23">
							<select size="1" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="tipo_gasto">
                             <?
                                    $checa = mysql_query("select id_gasto,concat(codigo,'-',descricao) descricao from sgc_tipo_gasto

                                    order by id_gasto=$valor04 desc, codigo asc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_gasto = $dados['id_gasto'];
                                    $ler_descricao = $dados['descricao'];
                            ?>
                            <option value="<?echo $id_gasto?>"><?echo $ler_descricao?></option>
                            <?
                            }
                            ?>
                            </select></td>
						</tr>
						<tr>
							<td width="793" colspan="2" height="23">
							<p align="center">Descrição do Centro de Custo(AJUDA)</td>
						</tr>
						<tr>
							<td height="23" width="242">
							<p align="right">&nbsp; </td>
							<td height="23" width="551">
							<textarea rows="6" name="ajuda" cols="67" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #FFFFFF;"><?echo $valor01?></textarea></td>
						</tr>
						<tr>
							<td colspan="2">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">
							<input type="submit" value="Editar Centro de Custo" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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
 $id_item=$_GET['id_item'];
 $id_objeto=$_GET['id_objeto'];

 $permissao_item=acesso($idusuario,$id_item);

  if($permissao_item=="OK"){
  
  $checa = mysql_query("SELECT desativado FROM sgc_centro_custo WHERE id_centro=$id_objeto") or print(mysql_error());
                           while($dados=mysql_fetch_array($checa)){
                           $acao = $dados['desativado'];
  }
  if($acao==null){

       $checa = mysql_query("SELECT count(*)contador FROM sgc_centro_custo cc, sgc_usuario ue
                             where cc.id_centro=$id_objeto
                             and ue.id_centro = cc.codigo

                            ") or print(mysql_error());
                            while($dados=mysql_fetch_array($checa)){
                            $contador = $dados['contador'];
       }
  
  
   if($contador>0){
         ?>
         <div align="center">
     	<table class="border" cellSpacing="0" cellPadding="0" width="500" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: AVISO :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="right">
					<table border="0" width="488" cellspacing="0" cellpadding="0">
						<tr>
							<td height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							<p align="center">Você não pode desativar este
							Centro de Custo existem registros de usuários com o ID desse
							Centro de Custo, altere os registros depois desative!!</td>
						</tr>
						<tr>
							<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							</td>
						</tr>
						<tr>
							<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							&nbsp;</td>
						</tr>
						<tr>
							<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							<table border="0" width="100%" cellspacing="0" cellpadding="0" height="23">
								<tr>
									<td><b>Usuário:</b></td>
									<td width="124"><b>Unidade:</b></td>
								</tr>
								<?
                          $checa = mysql_query("
                          SELECT concat(ue.primeiro_nome,' ',ue.ultimo_nome)nome,cc.descricao FROM sgc_centro_custo cc, sgc_usuario ue
                          where cc.id_centro=$id_objeto
                          and ue.id_centro = cc.codigo
                           ") or print(mysql_error());
                           while($dados=mysql_fetch_array($checa)){
                            $nome = $dados['nome'];
                            $sigla = $dados['descricao'];
                                ?>
								<tr>
									<td><?echo $nome?></td>
									<td width="124"><?echo $sigla?></td>
								</tr>
								<?
								}
								?>
							</table>
							</td>
						</tr>
						<tr>
							<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							&nbsp;</td>
						</tr>
						<tr>
							<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							<p align="center"><a href="?action=cad_centro_custo.php&id_item=<?echo $id_item?>"><font color="#000000">
							Voltar</font></a></td>
						</tr>
						<tr>
							<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							&nbsp;</td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
     <?

    }else{
       $deleta = mysql_query("UPDATE sgc_centro_custo SET desativado=sysdate() , data_alteracao=sysdate(), quem_alterou=$idusuario, oque_alterou='DESATIVADO' where id_centro=$id_objeto") or print(mysql_error());
       header("Location: ?action=$arquivo&id_item=$id_item");
   }

       }else{
       echo "aqui1";
       $deleta = mysql_query("UPDATE sgc_centro_custo SET desativado=null , data_alteracao=sysdate(), quem_alterou=$idusuario, oque_alterou='ATIVADO' where id_centro=$id_objeto") or print(mysql_error());
       header("Location: ?action=cad_centro_custo.php&id_item=$id_item");
     }


     
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
       
       $codigo=$_POST['codigo'];
       session_register('codigo');
       
       $area=$_POST['area'];
       session_register('area');
       
       $tipo_gasto=$_POST['tipo_gasto'];
       session_register('tipo_gasto');

       $integridade_codigo=integridade($codigo,$tabela,"codigo","codigo");

    if($integridade_codigo=="Existe"){

    header("Location: ?action=$arquivo&id_item=$id_item&msg=Este código já esta sendo usado");
    exit;
    }else{

      $cadas = mysql_query("INSERT INTO $tabela (id_gasto, id_area, codigo, descricao, ajuda, data_criacao, quem_criou) VALUES ($tipo_gasto,$area,'$codigo','$desc_objeto','$ajuda',sysdate(),$idusuario)") or print(mysql_error());
      session_unregister('ajuda');
      session_unregister('desc_objeto');
      session_unregister('codigo');
      session_unregister('area');
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
