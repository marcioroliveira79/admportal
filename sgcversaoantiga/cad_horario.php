<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Cadastro Gestão de Horários";
$titulo_listar="Horários Já Cadastrados";
$id_item=$_GET['id_item'];





if(!isset($acao_int)){
?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_horario.value=="")
    {
        alert ("\nDigite a descricao para o horário de atendimento.");

        document.form1.desc_horario.style.borderColor="#FF0000";
        document.form1.desc_horario.style.borderWidth="1px solid";

        nomeform.desc_horario.focus();
        return false;
    }

     if (nomeform.inicio.value=="")
    {
        alert ("\nDigite o horário de inicio");

        document.form1.inicio.style.borderColor="#FF0000";
        document.form1.inicio.style.borderWidth="1px solid";

        nomeform.inicio.focus();
        return false;
    }
     if (nomeform.final.value=="")
    {
        alert ("\nDigite o horário final");

        document.form1.final.style.borderColor="#FF0000";
        document.form1.final.style.borderWidth="1px solid";

        nomeform.final.focus();
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
  .style1 {
	text-align: right;
}
  -->
</style>
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

<div class="style1">

<form method="POST" name="form1" action="sgc.php?action=cad_horario.php&acao_int=cad_horario" onSubmit="return valida_dados(this)">
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
					<table border="0" cellspacing="0" cellpadding="0" style="width: 553px">
						<tr>
							<td colspan="4" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
						</tr>
						<tr>
							<td style="width: 133px">
							<p align="right">Desc. Horário:&nbsp; </td>
							<td width="418" height="23" colspan="3">
							<?
							if(isset($msg)){
                               $borda="border:1px solid #FF0000;";
                            }else{
                               session_unregister('ajuda');
                               session_unregister('desc_horario');
                               session_unregister('inicio');
                               session_unregister('final');
                            }
                            ?>
							<input size="68" name="desc_horario" value="<?echo $_SESSION['desc_horario']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						<tr>
							<td style="width: 133px" class="style1">
							<p align="right">Inicio:&nbsp;</td>
							<td width="46" height="23">
                            <!--webbot bot="Validation" b-value-required="TRUE" i-minimum-length="5" i-maximum-length="5" --><input size="5" name="inicio"  onKeyUp="mascaraTexto(event,'99:99')" value="<?echo $_SESSION['inicio']?>" style=" &lt;?echo $borda?&gt; font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF; width: 40px;" maxlength="5"></td>
							<td width="38" height="23">
							<p align="right">Final:&nbsp;</td>
							<td width="355" height="23">
							<!--webbot bot="Validation" b-value-required="TRUE" i-minimum-length="5" i-maximum-length="5" --><input size="5" name="final" onKeyUp="mascaraTexto(event,'99:99')" value="<?echo $_SESSION['final']?>" style=" &lt;?echo $borda?&gt; font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF; width: 40px;" maxlength="5"></td>
						</tr>
						<tr>
							<td width="488" colspan="4" height="23">
							<p align="center">Descrição do Campo Horário(AJUDA)</td>
						</tr>
						<tr>
							<td height="23" style="width: 133px">
							<p align="right"></td>
							<td height="23" width="418" colspan="3">
							<textarea rows="6" name="ajuda" cols="67" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #FFFFFF;"><?echo $_SESSION['ajuda']?></textarea></td>
						</tr>
						<tr>
							<td colspan="4">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="4">
							<input type="submit" value="Adicionar Horário" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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
							<td width="269" height="23"><b>Descrição</b></td>
							<td width="47" height="23">&nbsp;</td>
							<td width="47" height="23">&nbsp;</td>
							<td width="38" height="23">&nbsp;</td>
							<td width="44" height="23">&nbsp;</td>
							<td width="18" height="23">&nbsp;</td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
                        <?
                          $checa = mysql_query("select * from sgc_horario order by hora_inicio,hora_fim desc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_horario = $dados['id_horario'];
                                    $ler_horario = $dados['descricao'];
                                    $ler_ajuda = $dados['ajuda'];
                                    $ler_inicio = $dados['hora_inicio'];
                                    $ler_fim = $dados['hora_fim'];


                        ?>

                        <tr>
                            <td width="9" height="23">&nbsp;</td>
							<td width="247" height="23"><?echo $ler_horario?></td>
							<td width="58" height="23"><?echo $ler_inicio?></td>
							<td width="44" height="23"><?echo $ler_fim?></td>
							<td width="56" height="23">
							<p align="center"><a href="?action=cad_horario.php&acao_int=editar&id_horario=<?echo $id_horario?>&id_item=<?echo $id_item?>"">
							<font color="#000000">Editar</font></a></td>
							<td width="44" height="23">
							<p align="center">
                            <a href="javascript:confirmaExclusao('?action=cad_horario.php&acao_int=excluir&id_horario=<?echo $id_horario?>&id_item=<?echo $id_item?>')">
                            <font color="#000000">Excluir</font></a></td>
							<td width="18" height="23">
							<p align="center"><a href="#" class="dcontexto">
							<font color="#000000">?</font>
                            <span><strong><?echo $ler_horario ?></strong> - <?echo $ler_ajuda?>
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
     $id_horario=$_POST['id_horario'];
     $id_item=$_POST['id_item'];

     $desc_horario=$_POST['desc_horario'];
     $ajuda=$_POST['ajuda'];
     $inicio=$_POST['inicio'];
     $final=$_POST['final'];

     session_unregister('ajuda');
     session_unregister('desc_horario');
     session_unregister('inicio');
     session_unregister('final');


     $permissao_item=acesso($idusuario,$id_item);
     
     
   if($permissao_item=="OK"){

     echo  $existe=integridade("$desc_horario","sgc_horario","descricao","descricao"," and id_horario !=$id_horario");

    if($existe=="Existe"){

     $msg="Já existe horario com esse titutlo";
     session_register("desc_horario");
     session_register("ajuda");
     session_register("inicio");
     session_register("final");

     header("Location: ?action=cad_horario.php&acao_int=editar&id_horario=$id_horario&id_item=$id_item&msg=$msg&desc_horario=$desc_horario&ajuda=$ajuda&inicio=$inicio&final=$final");

    }else{


       $cadas = mysql_query("UPDATE sgc_horario SET descricao='$desc_horario',hora_inicio='$inicio', hora_fim='$final', ajuda='$ajuda',data_alteracao=sysdate(),quem_alterou=$idusuario,oque_alterou='ÁREA OU AJUDA' where id_horario='$id_horario'") or print(mysql_error());
       session_unregister('ajuda');
       session_unregister('desc_horario');
       session_unregister('inicio');
       session_unregister('final');
       header("Location: ?action=cad_horario.php&id_item=$id_item");

    }
   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=cad_horario.php&id_item=$id_item&msg=$msg");
   }




}
elseif($acao_int=="editar"){
 $id_horario=$_GET['id_horario'];
 $id_item=$_GET['id_item'];

$checa = mysql_query("select * from sgc_horario where id_horario=$id_horario ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $ler_horario = $dados['descricao'];
                                    $ler_ajuda = $dados['ajuda'];
                                    $ler_hora_inicio = $dados['hora_inicio'];
                                    $ler_hora_final = $dados['hora_fim'];
}




 ?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_horario.value=="")
    {
        alert ("\nDigite a descricao para o horário de atendimento.");

        document.form1.desc_horario.style.borderColor="#FF0000";
        document.form1.desc_horario.style.borderWidth="1px solid";

        nomeform.desc_horario.focus();
        return false;
    }

     if (nomeform.inicio.value=="")
    {
        alert ("\nDigite o horário de inicio");

        document.form1.inicio.style.borderColor="#FF0000";
        document.form1.inicio.style.borderWidth="1px solid";

        nomeform.inicio.focus();
        return false;
    }
     if (nomeform.final.value=="")
    {
        alert ("\nDigite o horário final");

        document.form1.final.style.borderColor="#FF0000";
        document.form1.final.style.borderWidth="1px solid";

        nomeform.final.focus();
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

<form method="POST" name="form1" action="sgc.php?action=cad_horario.php&acao_int=editar_bd" onSubmit="return valida_dados(this)">
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
					<table border="0" cellspacing="0" cellpadding="0" style="width: 553px">
						<tr>
							<td colspan="4" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
                            <input type='hidden' name='id_horario' value='<?echo $id_horario?>'></td>
						</tr>
						<tr>
							<td style="width: 133px">
							<p align="right">Desc. Horário:&nbsp; </td>
							<td width="418" height="23" colspan="3">
							<?
                           if(!isset($_SESSION['desc_horario'])){

                               $valor0=$ler_horario;
                               $valor01=$ler_ajuda;
                               $valor02=$ler_hora_inicio;
                               $valor03=$ler_hora_final;

                            }else{
                               $valor0=$_SESSION['desc_horario'];
                               $valor01=$_SESSION['ajuda'];
                               $valor02=$_SESSION['inicio'];
                               $valor03=$_SESSION['final'];
                            }
                            ?>
							<input size="68" name="desc_horario" value="<?echo $valor0?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						<tr>
							<td style="width: 133px" class="style1">
							<p align="right">Inicio:&nbsp;</td>
							<td width="46" height="23">
                            <!--webbot bot="Validation" b-value-required="TRUE" i-minimum-length="5" i-maximum-length="5" --><input size="5" name="inicio"  onKeyUp="mascaraTexto(event,'99:99')" value="<?echo $valor02?>" style=" &lt;?echo $borda?&gt; font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF; width: 40px;" maxlength="5"></td>
							<td width="38" height="23">
							<p align="right">Final:&nbsp;</td>
							<td width="355" height="23">
							<!--webbot bot="Validation" b-value-required="TRUE" i-minimum-length="5" i-maximum-length="5" --><input size="5" name="final" onKeyUp="mascaraTexto(event,'99:99')" value="<?echo $valor03?>" style=" &lt;?echo $borda?&gt; font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF; width: 40px;" maxlength="5"></td>
						</tr>
						<tr>
							<td width="488" colspan="4" height="23">
							<p align="center">Descrição do Campo Horário(AJUDA)</td>
						</tr>
						<tr>
							<td height="23" style="width: 133px">
							<p align="right"></td>
							<td height="23" width="418" colspan="3">
							<textarea rows="6" name="ajuda" cols="67" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #FFFFFF;"><?echo $valor01?></textarea></td>
						</tr>
						<tr>
							<td colspan="4">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="4">
							<input type="submit" value="Editar Horário" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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
 $id_horario=$_GET['id_horario'];

 echo $permissao_item=acesso($idusuario,$id_item);
  
  if($permissao_item=="OK"){
     $deleta = mysql_query("DELETE FROM sgc_horario where id_horario=$id_horario") or print(mysql_error());
     header("Location: ?action=cad_horario.php&id_item=$id_item");
   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=cad_horario.php&id_item=$id_item&msg=$msg");
   }
   
   
  }elseif($acao_int=="cad_horario"){
  
       $id_item=$_POST['id_item'];
       
       
       $permissao_item=acesso($idusuario,$id_item);


       if($permissao_item=="OK"){
  
       $desc_horario=$_POST['desc_horario'];

       $inicio=$_POST['inicio'];
       session_register('inicio');
       $final=$_POST['final'];
       session_register('final');
       
       $desc_horario=ltrim("$desc_horario");
       session_register('desc_horario');
       
       $ajuda=$_POST['ajuda'];
       session_register('ajuda');

       $integridade=integridade($desc_horario,"sgc_horario","descricao","descricao");

       $horario = mysql_query("select * from sgc_horario where hora_inicio >= '$inicio' and hora_fim <= '$final' ") or print mysql_error();
       $row = mysql_num_rows($horario);
       


  
    if($integridade=="Existe"){

      header("Location: ?action=cad_horario.php&id_item=$id_item&msg=Já existe um horario com esse nome");
      exit;
      
      if($row>0){
      header("Location: ?action=cad_horario.php&id_item=$id_item&msg=Este horário já esté em uso");
      exit;
      }

  
    }else{
    
     if($row>0){
     header("Location: ?action=cad_horario.php&id_item=$id_item&msg=Este horário já esté em uso");
     exit;
     }

     $cadas = mysql_query("INSERT INTO sgc_horario (descricao, hora_inicio, hora_fim, ajuda, data_criacao, quem_criou) VALUES ('$desc_horario','$inicio','$final','$ajuda',sysdate(),$idusuario)") or print(mysql_error());
      session_unregister('ajuda');
      session_unregister('desc_horario');
      session_unregister('inicio');
      session_unregister('final');
      header("Location: ?action=cad_horario.php&id_item=$id_item");
    }

  }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=cad_horario.php&id_item=$id_item&msg=$msg");
   }


  }
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
