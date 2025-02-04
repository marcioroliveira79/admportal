<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Cadastro Item de Menu";
$titulo_listar="Iten(s) Já Cadastrado(s)";
$id_item=$_GET['id_item'];
$arquivo="cad_item_menu.php";
$tabela="sgc_item_menu";
$id_chave="id_item_menu";





if(!isset($acao_int)){
?>


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

<form method="POST" name="form1" action="sgc.php?action=cad_item_menu.php&acao_int=cad_objeto">
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
					<table border="0" width="576" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
						</tr>
						<tr>
							<td width="120">
							<p align="right">Item Menu:&nbsp; </td>
							<td width="479" height="23">
							<?
							if(isset($msg)){
                               $borda="border:1px solid #FF0000;";
                            }else{
                               session_unregister('ajuda');
                               session_unregister('desc_objeto');
                               session_unregister('arquivo_php');
                               session_unregister('link');
                            }
                            ?>
							<input size="68" name="desc_objeto" value="<?echo $_SESSION['desc_objeto']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="120">
							<p align="right">Arquivo:&nbsp; </td>
							<td width="479" height="23">
         					<input size="68" name="arquivo_php" value="<?echo $_SESSION['arquivo_php']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
							<tr>
							<td width="120">
							<p align="right">Link Completo:&nbsp; </td>
							<td width="479" height="23">
         					<input size="68" name="link" value="<?echo $_SESSION['link']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
    					</tr>
							<tr>
							<td width="120">
							<p align="right">Contador:&nbsp; </td>
							<td width="479" height="23">
         					<input size="68" name="contador" value="<?echo $_SESSION['contador']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						<tr>
							<td width="576" colspan="2" height="23">
							<p align="center">Descrição do Item de Menu(AJUDA)</td>
						</tr>
						<tr>
							<td height="23" width="70">
							<p align="right">&nbsp; </td>
							<td height="23" width="479">
							<textarea rows="6" name="ajuda" cols="67" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #FFFFFF;"><?echo $_SESSION['ajuda']?></textarea></td>
						</tr>
						<tr>
							<td colspan="2">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">
							<input type="submit" value="Adicionar Item" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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
                          $checa = mysql_query("select * from $tabela order by descricao desc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_objeto = $dados["$id_chave"];
                                    $ler_descricao_objeto = $dados['descricao'];
                                    $ler_ajuda = $dados['ajuda'];

                        
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
                            <span><strong><?echo $ler_descricao_objeto ?></strong> - <?echo $ler_ajuda?>
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
     $id_objeto=$_POST['id_objeto'];
     $id_item=$_POST['id_item'];

     $desc_objeto=$_POST['desc_objeto'];
     $ajuda=$_POST['ajuda'];

     $arquivo_php=$_POST['arquivo_php'];
     $link=$_POST['link'];
     $contador=$_POST['contador'];

     session_unregister('ajuda');
     session_unregister('desc_objeto');
     session_unregister('arquivo_php');
     session_unregister('link');
     session_unregister('contador');

       if($arquivo_php!=null and $link==null){

              $valor="?action=".$arquivo_php;
              session_register('arquivo_php');

       }elseif($arquivo_php==null and $link!=null){

              $valor=$link;
              session_register('link');

       }elseif($arquivo_php!=null and $link!=null){
             session_register('arquivo_php');
             session_register('link');
             $msg="Você deve selecionar ou arquivo ou link completo";
             header("Location: ?action=$arquivo&id_item=$id_item&id_objeto=$id_objeto&msg=$msg");
             exit;
       }

      $permissao_item=acesso($idusuario,$id_item);
     
     
   if($permissao_item=="OK"){

       $existe=integridade("$desc_objeto","$tabela","descricao","descricao","and $id_chave !=$id_objeto");

    if($existe=="Existe"){
     $msg="Já existe um deparamento com este titulo";
     session_register("desc_objeto");
     session_register("ajuda");

     header("Location: ?action=$arquivo&acao_int=editar&id_objeto=$id_objeto&id_item=$id_item&msg=$msg&desc_objeto=$desc_objeto&ajuda=$ajuda");

    }else{


       $cadas = mysql_query("UPDATE $tabela SET descricao='$desc_objeto', link_item='$valor', contador='$contador', ajuda='$ajuda',data_alteracao=sysdate(),quem_alterou=$idusuario where $id_chave='$id_objeto'") or print(mysql_error());
       session_unregister('ajuda');
       session_unregister('desc_objeto');
       session_unregister('arquivo_php');
       session_unregister('link');
       session_unregister('contador');
       header("Location: ?action=$arquivo&id_item=$id_item");

    }
   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=$arquivo&id_item=$id_item&msg=$msg");
   }




}
elseif($acao_int=="editar"){
$id_objeto=$_GET['id_objeto'];
$id_item=$_GET['id_item'];

$checa = mysql_query("select * from $tabela where $id_chave=$id_objeto ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $ler_descricao_objeto = $dados['descricao'];
                                    $ler_ajuda = $dados['ajuda'];
                                    $ler_link = $dados['link_item'];
                                    $ler_contador = $dados['contador'];
}




 ?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_objeto.value=="")
    {
        alert ("\nDigite a descricao para área locacao.");

        document.form1.desc_objeto.style.borderColor="#FF0000";
        document.form1.desc_objeto.style.borderWidth="1px solid";

        nomeform.desc_objeto.focus();
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
    if (nomeform.arquivo_php.value=="" && nomeform.link.value=="")
    {
        alert ("\nVocê deve digitar ou arquivo ou link");

        document.form1.arquivo_php.style.borderColor="#FF0000";
        document.form1.arquivo_php.style.borderWidth="1px solid";
        document.form1.link.style.borderColor="#FF0000";
        document.form1.link.style.borderWidth="1px solid";

        nomeform.link.focus();
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
					<td class="info" align="middle"><b>:: Editar <?echo $titulo?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="622" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
                            <input type='hidden' name='id_objeto' value='<?echo $id_objeto?>'></td>
						</tr>
						<tr>
							<td width="112">
							<p align="right">Item Menu:&nbsp;</td>
							<td width="510" height="23">
							<?

                            /*
                            if(isset($msg)){
                               $borda="border:1px solid #FF0000;";
                            }else{
                               session_unregister('ajuda');
                               session_unregister('desc_categoria');
                            }
                            */
                            if(!isset($_SESSION['desc_objeto'])){

                               $valor0=$ler_descricao_objeto;
                               $valor01=$ler_ajuda;
                               $valor02=$ler_link;
                               $valor03=$ler_link;
                               $valor04=$ler_contador;
                               


                             if(substr($valor03, 0, 8)=="?action="){

                              $valor02=substr($valor03, 8);
                              $valor03=null;

                            }else{

                              $valor02=null;
                              $valor03=$ler_link;

                            }







                            }else{
                                $valor0=$_SESSION['desc_objeto'];
                                $valor01=$_SESSION['ajuda'];
                                $valor02=$_SESSION['arquivo_php'];
                                $valor03=$_SESSION['link'];
                                $valor04=$_SESSION['contador'];



                             if(substr($valor03, 0, 8)=="?action="){

                              $valor02=null;

                            }else{

                              $valor03=null;

                            }

                            }











                            ?>
							<input size="68" name="desc_objeto" value="<?echo $valor0?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
							<tr>
							<td width="112">
							<p align="right">Arquivo:&nbsp; </td>
							<td width="510" height="23">
         					<input size="68" name="arquivo_php" value="<?echo $valor02?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
							<tr>
							<td width="112">
							<p align="right">Link Completo:&nbsp; </td>
							<td width="510" height="23">
         					<input size="68" name="link" value="<?echo $valor03?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						</tr>
							<tr>
							<td width="112">
							<p align="right">Contador:&nbsp; </td>
							<td width="510" height="23">
         					<input size="68" name="contador" value="<?echo $valor04?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						<tr>
							<td width="622" colspan="2" height="23">
							<p align="center">Descrição do Item(AJUDA)</td>
						</tr>
						<tr>
							<td height="23" width="112">
							<p align="right">&nbsp; </td>
							<td height="23" width="510">
							<textarea rows="6" name="ajuda" cols="67" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #FFFFFF;"><?echo $valor01?></textarea></td>
						</tr>
						<tr>
							<td colspan="2">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">
							<input type="submit" value="Editar Item" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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

 echo $permissao_item=acesso($idusuario,$id_item);
  
  if($permissao_item=="OK"){
     $deleta = mysql_query("DELETE FROM $tabela where $id_chave=$id_objeto") or print(mysql_error());

     //---------------------Deletar item na regra de template----------------------//
     $deleta = mysql_query("DELETE FROM sgc_template_regra where id_item=$id_objeto") or print(mysql_error());
     header("Location: ?action=$arquivo&id_item=$id_item");
     
     
   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=$arquivo&id_item=$id_item&msg=$msg");
   }
   
   
   
   
}elseif($acao_int=="cad_objeto"){
  
       $id_item=$_POST['id_item'];

       
       echo $permissao_item=acesso($idusuario,$id_item);


       if($permissao_item=="OK"){
  
       $desc_objeto=$_POST['desc_objeto'];
       $desc_objeto=ltrim("$desc_objeto");
       session_register('desc_objeto');
       
       $ajuda=$_POST['ajuda'];
       session_register('ajuda');
       
       $contador=$_POST['contador'];
       session_register('contador');

       $arquivo_php=$_POST['arquivo_php'];
       $link=$_POST['link'];

       if($arquivo_php!=null and $link==null){

              $valor="?action=".$arquivo_php;
              session_register('arquivo_php');

       }elseif($arquivo_php==null and $link!=null){

              $valor=$link;
              session_register('link');

       }elseif($arquivo_php!=null and $link!=null){
             session_register('arquivo_php');
             session_register('link');
             $msg="Você deve selecionar ou arquivo ou link completo";
             header("Location: ?action=$arquivo&id_item=$id_item&msg=$msg");
             exit;
       }



       $integridade=integridade($desc_objeto,"$tabela","descricao","descricao");


  
    if($integridade=="Existe"){

      header("Location: ?action=$arquivo&id_item=$id_item&msg=Já existe um item com este nome");
  
    }else{

     $cadas = mysql_query("INSERT INTO $tabela (descricao, link_item, contador, ajuda, data_criacao, quem_criou) VALUES ('$desc_objeto','$valor','$contador','$ajuda',sysdate(),$idusuario)") or print(mysql_error());
      session_unregister('ajuda');
      session_unregister('desc_objeto');
      session_unregister('arquivo_php');
      session_unregister('link');
      session_unregister('contador');
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
