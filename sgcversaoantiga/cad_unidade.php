<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Cadastro Unidade Empresa";
$titulo_listar="Unidade(s) Cadastradas";
$id_item=$_GET['id_item'];





if(!isset($acao_int)){
?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_unidade.value=="")
    {
        alert ("\nDigite a descricao Unidade.");

        document.form1.desc_unidade.style.borderColor="#FF0000";
        document.form1.desc_unidade.style.borderWidth="1px solid";

        nomeform.desc_unidade.focus();
        return false;
    }
     if (nomeform.codigo.value=="")
    {
        alert ("\nDigite código o da Unidade");

        document.form1.codigo.style.borderColor="#FF0000";
        document.form1.codigo.style.borderWidth="1px solid";

        nomeform.codigo.focus();
        return false;
    }
     if (nomeform.sigla.value=="")
    {
        alert ("\nDigite a sigla da Unidade");

        document.form1.sigla.style.borderColor="#FF0000";
        document.form1.sigla.style.borderWidth="1px solid";

        nomeform.sigla.focus();
        return false;
    }
    if (nomeform.ajuda.value=="")
    {
        alert ("\nDigite a ajuda desta Unidade");

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

<form method="POST" name="form1" action="sgc.php?action=cad_unidade.php&acao_int=cad_unidade" onSubmit="return valida_dados(this)">
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
					<table border="0" width="587" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
						</tr>
						<tr>
							<td width="145">
							<p align="right">Desc. Unidade:&nbsp;</td>
							<td width="442" height="23">
							<input size="68" name="desc_unidade" value="<?echo $_SESSION['desc_unidade']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
                         <tr>
							<td width="145">
							<p align="right">Código:&nbsp;</td>
							<td width="442" height="23">
							<input size="5" name="codigo" value="<?echo $_SESSION['codigo']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="5">
                            </td>
						</tr>
						  <tr>
							<td width="145">
							<p align="right">Sigla:&nbsp;</td>
							<td width="442" height="23">
							<input size="10" name="sigla" value="<?echo $_SESSION['sigla']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="10">
                            </td>
						</tr>
						<tr>
							<td width="587" colspan="2" height="23">
							<p align="center">Descrição da UNIDADE(AJUDA)</td>
						</tr>
						<tr>
							<td height="23" width="145">
							<p align="right">&nbsp; </td>
							<td height="23" width="442">
							<textarea rows="6" name="ajuda" cols="67" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #FFFFFF;"><?echo $_SESSION['ajuda']?></textarea></td>
						</tr>
						<tr>
							<td colspan="2">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">
							<input type="submit" value="Adicionar Unidade" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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
                          $checa = mysql_query("select * from sgc_unidade order by descricao desc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_unidade = $dados['id_unidade'];
                                    $ler_descricao = $dados['descricao'];
                                    $ler_ajuda = $dados['ajuda'];
                                    $ler_sigla = $dados['sigla'];
                                    $ler_codigo = $dados['codigo'];
                                    $desativacao = $dados['desativado'];

                        if($desativacao!=null){
                         $acao="Ativar";
                        }else{
                         $acao="Desativar";
                        }
                        
                        
                        ?>

                        <tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="369" height="23"><?echo $ler_codigo?> - <?echo $ler_descricao?> - <?echo $ler_sigla?></td>
							<td width="38" height="23">
							<p align="center"><a href="?action=cad_unidade.php&acao_int=editar&id_unidade=<?echo $id_unidade?>&id_item=<?echo $id_item?>"">
							<font color="#000000">Editar</font></a></td>
							<td width="44" height="23">
							<p align="center">
                            <a href="javascript:confirmaExclusao('?action=cad_unidade.php&acao_int=excluir&id_unidade=<?echo $id_unidade?>&id_item=<?echo $id_item?>')">
                            <font color="#000000"><?echo $acao?></font></a></td>
							<td width="18" height="23">
							<p align="center"><a href="#" class="dcontexto">
							<font color="#000000">?</font>
                            <span><strong><?echo "$ler_codigo - $ler_descricao - $ler_sigla " ?></strong> - <?echo $ler_ajuda?>
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
<?



  }
elseif($acao_int=="editar_bd"){



     $idusuario = $_SESSION['id_usuario_global'];
     $id_unidade=$_POST['id_unidade'];
     $id_item=$_POST['id_item'];

     $desc_unidade=$_POST['desc_unidade'];
     $ajuda=$_POST['ajuda'];
     $codigo=$_POST['codigo'];
     $sigla=$_POST['sigla'];
     $sigla=strtoupper(strtolower($_POST['sigla']));
     
     session_unregister('ajuda');
     session_unregister('desc_unidade');
     session_unregister('codigo');
     session_unregister('sigla');

     $codigo_antigo=tabelainfo($id_unidade,"sgc_unidade","codigo","id_unidade");

     $permissao_item=acesso($idusuario,$id_item);




     
   if($permissao_item=="OK"){




       $existe=integridade("$desc_unidade","sgc_unidade","descricao","descricao","and id_unidade !=$id_unidade");
       $integridade_codigo=integridade($codigo,"sgc_unidade","codigo","codigo","and id_unidade !=$id_unidade");
       $integridade_sigla=integridade($sigla,"sgc_unidade","sigla","sigla","and id_unidade !=$id_unidade");


     if($integridade_codigo=="Existe"){
         $msg="Este código esta sendo usado";
         header("Location: ?action=cad_unidade.php&acao_int=editar&id_unidade=$id_unidade&id_item=$id_item&msg=$msg&desc_unidade=$desc_unidade&ajuda=$ajuda");
         exit;
     }else{
      if($integridade_sigla=="Existe"){
         $msg="Esta sigla esta sendo usada";
         header("Location: ?action=cad_unidade.php&acao_int=editar&id_unidade=$id_unidade&id_item=$id_item&msg=$msg&desc_unidade=$desc_unidade&ajuda=$ajuda");
         exit;
     }else{




    if($existe=="Existe"){

     $msg="Já existe uma Unidade com esse titulo";
     session_unregister('ajuda');
     session_unregister('desc_unidade');
     session_unregister('codigo');
     session_unregister('sigla');


    header("Location: ?action=cad_unidade.php&acao_int=editar&id_unidade=$id_unidade&id_item=$id_item&msg=$msg&desc_unidade=$desc_unidade&ajuda=$ajuda");

    }
    else{


       $cadas = mysql_query("UPDATE sgc_unidade SET codigo='$codigo',descricao='$desc_unidade',sigla='$sigla',ajuda='$ajuda',data_alteracao=sysdate(),quem_alterou=$idusuario,oque_alterou='SIGLA UNIDADE CODIGO' where id_unidade='$id_unidade'") or print(mysql_error());
       session_unregister('ajuda');
       session_unregister('desc_unidade');
       session_unregister('codigo');
       session_unregister('sigla');

     header("Location: ?action=cad_unidade.php&id_item=$id_item");

    }
    
      if($codigo_antigo!=$codigo){

          $update = mysql_query("UPDATE sgc_chamado set id_unidade=$codigo where id_unidade=$codigo_antigo") or print(mysql_error());
          $update = mysql_query("UPDATE sgc_usuario set id_unidade=$codigo where id_unidade=$codigo_antigo") or print(mysql_error());

       }
    
    }
    }
   }else{
     $msg="Você não tem permissão para esta operação";
     session_unregister('ajuda');
     session_unregister('desc_unidade');
     session_unregister('codigo');
     session_unregister('sigla');

     header("Location: ?action=cad_unidade.php&id_item=$id_item&msg=$msg");
   }




}
elseif($acao_int=="editar"){
$id_unidade=$_GET['id_unidade'];
$id_item=$_GET['id_item'];

$checa = mysql_query("select * from sgc_unidade where id_unidade=$id_unidade ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $ler_descricao = $dados['descricao'];
                                    $ler_ajuda = $dados['ajuda'];
                                    $ler_codigo = $dados['codigo'];
                                    $ler_sigla = $dados['sigla'];
}




 ?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_unidade.value=="")
    {
        alert ("\nDigite a descricao Unidade.");

        document.form1.desc_unidade.style.borderColor="#FF0000";
        document.form1.desc_unidade.style.borderWidth="1px solid";

        nomeform.desc_unidade.focus();
        return false;
    }
     if (nomeform.codigo.value=="")
    {
        alert ("\nDigite código o da Unidade");

        document.form1.codigo.style.borderColor="#FF0000";
        document.form1.codigo.style.borderWidth="1px solid";

        nomeform.codigo.focus();
        return false;
    }
     if (nomeform.sigla.value=="")
    {
        alert ("\nDigite a sigla da Unidade");

        document.form1.sigla.style.borderColor="#FF0000";
        document.form1.sigla.style.borderWidth="1px solid";

        nomeform.sigla.focus();
        return false;
    }
    if (nomeform.ajuda.value=="")
    {
        alert ("\nDigite a ajuda desta Unidade");

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

<form method="POST" name="form1" action="sgc.php?action=cad_unidade.php&acao_int=editar_bd"" onSubmit="return valida_dados(this)">
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
					<table border="0" width="587" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
                            <input type='hidden' name='id_unidade' value='<?echo $id_unidade?>'></td>
						</tr>
                        <?
                         if(!isset($_SESSION['desc_unidade'])){

                               $valor0=$ler_descricao;
                               $valor01=$ler_ajuda;
                               $valor02=$ler_codigo;
                               $valor03=$ler_sigla;

                            }else{
                               $valor0=$_SESSION['desc_unidade'];
                               $valor01=$_SESSION['ajuda'];
                               $valor02=$_SESSION['codigo'];
                               $valor03=$_SESSION['sigla'];
                            }
                        ?>


                        <tr>
							<td width="145">
							<p align="right">Desc. Unidade:&nbsp;</td>
							<td width="442" height="23">
							<input size="68" name="desc_unidade" value="<?echo $valor0?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						<tr>
							<td width="145">
							<p align="right">Código:&nbsp;</td>
							<td width="442" height="23">
							<input size="5" name="codigo" value="<?echo $valor02?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="5">
						</tr>
						<tr>
							<td width="145">
							<p align="right">Sigla:&nbsp;</td>
							<td width="442" height="23">
							<input size="10" name="sigla" value="<?echo $valor03?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="10">
						</tr>
						<tr>
							<td width="587" colspan="2" height="23">
							<p align="center">Descrição da UNIDADE(AJUDA)</td>
						</tr>
						<tr>
							<td height="23" width="145">
							<p align="right">&nbsp; </td>
							<td height="23" width="442">
							<textarea rows="6" name="ajuda" cols="67" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #FFFFFF;"><?echo $valor01?></textarea></td>
						</tr>
						<tr>
							<td colspan="2">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">
							<input type="submit" value="Editar Unidade" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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
 $id_unidade=$_GET['id_unidade'];

 $permissao_item=acesso($idusuario,$id_item);
  
 if($permissao_item=="OK"){


  $checa = mysql_query("SELECT desativado FROM sgc_unidade WHERE id_unidade=$id_unidade   ") or print(mysql_error());
                           while($dados=mysql_fetch_array($checa)){
                           $acao = $dados['desativado'];
  }

  if($acao==null){

       $checa = mysql_query("SELECT count(*)contador FROM sgc_usuario us, sgc_unidade un
                             WHERE un.id_unidade=$id_unidade
                             and us.id_unidade = un.codigo  ") or print(mysql_error());
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
							<p align="center">Você não pode desativar esta
							unidade existem registros de usuários com o ID dessa
							unidade, altere os registros depois desative!!</td>
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
                          $checa = mysql_query("select concat(us.primeiro_nome,' ',us.ultimo_nome)nome,un.sigla from sgc_usuario us, sgc_unidade un
                          where un.id_unidade = $id_unidade
                          and us.id_unidade = un.codigo ") or print(mysql_error());
                           while($dados=mysql_fetch_array($checa)){
                            $nome = $dados['nome'];
                            $sigla = $dados['sigla'];
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
							<p align="center"><a href="?action=cad_unidade.php&id_item=<?echo $id_item?>"><font color="#000000">
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
        $deleta = mysql_query("UPDATE sgc_unidade SET desativado=sysdate(), data_alteracao=sysdate(), quem_alterou=$idusuario, oque_alterou='DESATIVADO' where id_unidade=$id_unidade") or print(mysql_error());
         header("Location: ?action=cad_unidade.php&id_item=$id_item");

     }
  }else{
       $deleta = mysql_query("UPDATE sgc_unidade SET desativado=null, data_alteracao=sysdate(),  quem_alterou=$idusuario, oque_alterou='ATIVADO'  where id_unidade=$id_unidade") or print(mysql_error());
        header("Location: ?action=cad_unidade.php&id_item=$id_item");
     }
   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=cad_unidade.php&id_item=$id_item&msg=$msg");
   }
   
   
  }elseif($acao_int=="cad_unidade"){
  
       $id_item=$_POST['id_item'];
       
           $permissao_item=acesso($idusuario,$id_item);


if($permissao_item=="OK"){
       
  
       $desc_unidade=$_POST['desc_unidade'];
       $desc_unidade=ltrim("$desc_unidade");
       session_register('desc_unidade');
       
       $ajuda=$_POST['ajuda'];
       session_register('ajuda');

       $codigo=$_POST['codigo'];
       session_register('codigo');
       
       $sigla=strtoupper(strtolower($_POST['sigla']));
       session_register('sigla');
       

       $integridade=integridade($desc_unidade,"sgc_unidade","descricao","descricao");
       $integridade_codigo=integridade($codigo,"sgc_unidade","codigo","codigo");
       $integridade_sigla=integridade($sigla,"sgc_unidade","sigla","sigla");

     if($integridade_sigla=="Existe"){
         header("Location: ?action=cad_unidade.php&id_item=$id_item&msg=Esta Sigla já esta cadastrada");
         exit;
     }else{

     if($integridade_codigo=="Existe"){
         header("Location: ?action=cad_unidade.php&id_item=$id_item&msg=Este Código já esta cadastrado");
         exit;
      }else{
  
    if($integridade=="Existe"){
         header("Location: ?action=cad_unidade.php&id_item=$id_item&msg=Já existe uma UNIDADE com esse nome");
         exit;
    }else{

     $cadas = mysql_query("INSERT INTO sgc_unidade (codigo, descricao, sigla, ajuda, data_criacao, quem_criou) VALUES ('$codigo','$desc_unidade','$sigla','$ajuda',sysdate(),$idusuario)") or print(mysql_error());
        session_unregister('ajuda');
        session_unregister('desc_unidade');
        session_unregister('codigo');
        session_unregister('sigla');
        header("Location: ?action=cad_unidade.php&id_item=$id_item");
        }
      }
    }

   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=cad_unidade.php&id_item=$id_item&msg=$msg");
    }
  }
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
