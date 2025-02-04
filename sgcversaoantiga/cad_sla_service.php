<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Cadastro SLA Service Desk";
$titulo_listar="SLA´s Service Desk Cadastradas";
$id_item=$_GET['id_item'];





if(!isset($acao_int)){
?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_sla.value=="")
    {
        alert ("\nDigite a descricao para SLA.");

        document.form1.desc_sla.style.borderColor="#FF0000";
        document.form1.desc_sla.style.borderWidth="1px solid";

        nomeform.desc_sla.focus();
        return false;
    }
     if (nomeform.tempo_resp.value=="")
    {
        alert ("\nDigite o tempo para resposta do SLA");

        document.form1.tempo_resp.style.borderColor="#FF0000";
        document.form1.tempo_resp.style.borderWidth="1px solid";

        nomeform.tempo_resp.focus();
        return false;
    }
    if (nomeform.ajuda.value=="")
    {
        alert ("\nDigite a ajuda desta categoria");

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

<form method="POST" name="form1" action="sgc.php?action=cad_sla_service.php&acao_int=cad_sla_service" onSubmit="return valida_dados(this)">
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
							<p align="right">Desc SLA:&nbsp;</td>
							<td width="442" height="23">
							<input size="68" name="desc_sla" value="<?echo $_SESSION['desc_sla']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						<tr>
							<td width="145">
							<p align="right">Tempo Resposta:&nbsp;</td>
							<td width="442" height="23">
							<input size="5" name="tempo_resp" value="<?echo $_SESSION['tempo_resp']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="5"><select size="1"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  name="tipo_tempo">
							<option>Dias</option>
							<option>Horas</option>
							<option>Minutos</option>
							</select></td>
						</tr>
						<tr>
							<td width="587" colspan="2" height="23">
							<p align="center">Descrição do SLA(AJUDA)</td>
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
							<input type="submit" value="Adicionar SLA" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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
                          $checa = mysql_query("select * from sgc_sla_servicedesk order by tempo desc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_service = $dados['id_sla_service'];
                                    $ler_descricao = $dados['descricao'];
                                    $ler_ajuda = $dados['ajuda'];
                                    $ler_tempo = $dados['tempo'];
                                    $ler_tipo_tempo = $dados['tipo_tempo'];

                        
                        ?>

                        <tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="369" height="23"><?echo $ler_descricao?> - <?echo $ler_tempo?> <?echo $ler_tipo_tempo?> </td>
							<td width="38" height="23">
							<p align="center"><a href="?action=cad_sla_service.php&acao_int=editar&id_sla_service=<?echo $id_service?>&id_item=<?echo $id_item?>"">
							<font color="#000000">Editar</font></a></td>
							<td width="44" height="23">
							<p align="center">
                            <a href="javascript:confirmaExclusao('?action=cad_sla_service.php&acao_int=excluir&id_sla_service=<?echo $id_service?>&id_item=<?echo $id_item?>')">
                            <font color="#000000">Excluir</font></a></td>
							<td width="18" height="23">
							<p align="center"><a href="#" class="dcontexto">
							<font color="#000000">?</font>
                            <span><strong><?echo $ler_descricao ?></strong> - <?echo $ler_ajuda?>
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
echo     $id_sla_service=$_POST['id_sla_service'];
     $id_item=$_POST['id_item'];

echo     $desc_sla=$_POST['desc_sla'];
     $ajuda=$_POST['ajuda'];
     $tempo_resp=$_POST['tempo_resp'];
     $tipo_tempo=$_POST['tipo_tempo'];

     session_unregister('ajuda');
     session_unregister('desc_sla');
     session_unregister('tempo_resp');
     session_unregister('tipo_tempo');


     $permissao_item=acesso($idusuario,$id_item);
     
     
   if($permissao_item=="OK"){

     echo  $existe=integridade("$desc_sla","sgc_sla_servicedesk","descricao","descricao","and id_sla_service !=$id_sla_service");
    if($existe=="Existe"){

     $msg="Já existe uma SLA com esse titulo";
     session_unregister('ajuda');
     session_unregister('desc_sla');
     session_unregister('tempo_resp');
     session_unregister('tipo_tempo');

    header("Location: ?action=cad_sla_service.php&acao_int=editar&id_sla_service=$id_sla_service&id_item=$id_item&msg=$msg&desc_sla=$desc_sla&ajuda=$ajuda");

    }else{


       $cadas = mysql_query("UPDATE sgc_sla_servicedesk SET descricao='$desc_sla',ajuda='$ajuda',tempo='$tempo_resp',tipo_tempo='$tipo_tempo',data_alteracao=sysdate(),quem_alterou=$idusuario,oque_alterou='DESC OU TEMPO OU AJUDA' where id_sla_service='$id_sla_service'") or print(mysql_error());
       session_unregister('ajuda');
       session_unregister('desc_sla');
       session_unregister('tempo_resp');
       session_unregister('tipo_tempo');
     header("Location: ?action=cad_sla_service.php&id_item=$id_item");

    }
   }else{
     $msg="Você não tem permissão para esta operação";
     session_unregister('ajuda');
     session_unregister('desc_sla');
     session_unregister('tempo_resp');
     session_unregister('tipo_tempo');
     header("Location: ?action=cad_sla_service.php&id_item=$id_item&msg=$msg");
   }




}
elseif($acao_int=="editar"){
$id_sla_service=$_GET['id_sla_service'];
$id_item=$_GET['id_item'];

$checa = mysql_query("select * from sgc_sla_servicedesk where id_sla_service=$id_sla_service ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $ler_descricao = $dados['descricao'];
                                    $ler_ajuda = $dados['ajuda'];
                                    $ler_tempo = $dados['tempo'];
                                    $ler_tipo_tempo = $dados['tipo_tempo'];
}




 ?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_sla.value=="")
    {
        alert ("\nDigite a descricao para SLA.");

        document.form1.desc_sla.style.borderColor="#FF0000";
        document.form1.desc_sla.style.borderWidth="1px solid";

        nomeform.desc_sla.focus();
        return false;
    }
     if (nomeform.tempo_resp.value=="")
    {
        alert ("\nDigite o tempo para resposta do SLA");

        document.form1.tempo_resp.style.borderColor="#FF0000";
        document.form1.tempo_resp.style.borderWidth="1px solid";

        nomeform.tempo_resp.focus();
        return false;
    }
    if (nomeform.ajuda.value=="")
    {
        alert ("\nDigite a ajuda desta categoria");

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

<form method="POST" name="form1" action="sgc.php?action=cad_sla_service.php&acao_int=editar_bd"" onSubmit="return valida_dados(this)">
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
                            <input type='hidden' name='id_sla_service' value='<?echo $id_sla_service?>'></td>
						</tr>
                        <?
                         if(!isset($_SESSION['desc_sla'])){

                               $valor0=$ler_descricao;
                               $valor01=$ler_ajuda;
                               $valor02=$ler_tempo;
                               $valor03=$ler_tipo_tempo;

                            }else{
                               $valor0=$_SESSION['desc_sla'];
                               $valor01=$_SESSION['ajuda'];
                               $valor02=$_SESSION['tempo_resp'];
                               $valor03=$_SESSION['tipo_tempo'];
                            }
                        ?>


                        <tr>
							<td width="145">
							<p align="right">Desc SLA:&nbsp;</td>
							<td width="442" height="23">
							<input size="68" name="desc_sla" value="<?echo $valor0?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						<tr>
							<td width="145">
							<p align="right">Tempo Resposta:&nbsp;</td>
							<td width="442" height="23">
							<input size="5" name="tempo_resp" value="<?echo $valor02?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="5">
                            <select size="1"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  name="tipo_tempo" >
                            <?
                            if($valor03=="Dias"){
                            ?>
                            <option>Dias</option>
							<option>Horas</option>
							<option>Minutos</option>
							<?

                            
                            }elseif($valor03=="Horas"){
                            ?>
                            <option>Horas</option>
                            <option>Dias</option>
							<option>Minutos</option>
							<?
                            }elseif($valor03=="Minutos"){
                            ?>
                            <option>Minutos</option>
                            <option>Horas</option>
                            <option>Dias</option>
							<?
                            }
                            ?>
							</select></td>
						</tr>
						<tr>
							<td width="587" colspan="2" height="23">
							<p align="center">Descrição do SLA(AJUDA)</td>
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
							<input type="submit" value="Editar SLA" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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
 echo $id_sla_service=$_GET['id_sla_service'];

 echo $permissao_item=acesso($idusuario,$id_item);
  
  if($permissao_item=="OK"){
     $deleta = mysql_query("DELETE FROM sgc_sla_servicedesk where id_sla_service=$id_sla_service") or print(mysql_error());
     header("Location: ?action=cad_sla_service.php&id_item=$id_item");
   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=cad_sla_service.php&id_item=$id_item&msg=$msg");
   }
   
   
  }elseif($acao_int=="cad_sla_service"){
  
       $id_item=$_POST['id_item'];
  
      $permissao_item=acesso($idusuario,$id_item);


      if($permissao_item=="OK"){
  
       $desc_sla=$_POST['desc_sla'];
       $desc_sla=ltrim("$desc_sla");
       session_register('desc_sla');
       
       $ajuda=$_POST['ajuda'];
       session_register('ajuda');

       $tempo_resp=$_POST['tempo_resp'];
       session_register('tempo_resp');
       
       $tipo_tempo=$_POST['tipo_tempo'];
       session_register('tipo_tempo');




       $integridade=integridade($desc_sla,"sgc_sla_servicedesk","descricao","descricao");


  
    if($integridade=="Existe"){

      header("Location: ?action=cad_sla_service.php&id_item=$id_item&msg=Já existe um SLA com esse nome");
  
    }else{

     $cadas = mysql_query("INSERT INTO sgc_sla_servicedesk (descricao, ajuda, tempo, tipo_tempo, data_criacao, quem_criou) VALUES ('$desc_sla','$ajuda','$tempo_resp','$tipo_tempo',sysdate(),$idusuario)") or print(mysql_error());
      session_unregister('ajuda');
      session_unregister('desc_sla');
      session_unregister('tempo_resp');
      session_unregister('tipo_tempo');
      header("Location: ?action=cad_sla_service.php&id_item=$id_item");
    }

   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=cad_sla_service.php&id_item=$id_item&msg=$msg");
   }

  }
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
