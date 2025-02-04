<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Criar Chamado";
$titulo_listar="Últimos 10 Chamados Abertos por Você";
$id_item=$_GET['id_item'];
$arquivo="abertura_chamado.php";
$tabela="sgc_chamado";
$id_chave="id_chamado";





if(!isset($acao_int)){
?>
<script src="prototype.js" type="text/javascript"></script>


<? $tempo=atributo('atributo2');?>


<script>
  new Ajax.PeriodicalUpdater('online','console_servicedesk_online.php',
  {
   method: 'post',
   frequency: <?echo $tempo?>
   });
</script>

<div align="center">
<div id="online" align="center">


</div>
</div>
<p>&nbsp;</p>


<?

  }
elseif($acao_int=="editar_bd"){

     $idusuario = $_SESSION['id_usuario_global'];
     $id_objeto=$_POST['id_objeto'];
     $id_item=$_POST['id_item'];

     $desc_objeto=$_POST['desc_objeto'];
     $ajuda=$_POST['ajuda'];

     session_unregister('ajuda');
     session_unregister('desc_objeto');


     $permissao_item=acesso($idusuario,$id_item);
     
     
   if($permissao_item=="OK"){

       $existe=integridade("$desc_objeto","$tabela","descricao","descricao","and $id_chave !=$id_objeto");

    if($existe=="Existe"){
     $msg="Já existe um Menu com este titulo";
     session_register("desc_objeto");
     session_register("ajuda");

     header("Location: ?action=$aquivo&acao_int=editar&id_objeto=$id_objeto&id_item=$id_item&msg=$msg&desc_objeto=$desc_objeto&ajuda=$ajuda");

    }else{


       $cadas = mysql_query("UPDATE $tabela SET descricao='$desc_objeto',ajuda='$ajuda',data_alteracao=sysdate(),quem_alterou=$idusuario where $id_chave='$id_objeto'") or print(mysql_error());
       session_unregister('ajuda');
       session_unregister('desc_objeto');
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
}




 ?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_objeto.value=="")
    {
        alert ("\nDigite a descricao para o Menu.");

        document.form1.desc_objeto.style.borderColor="#FF0000";
        document.form1.desc_objeto.style.borderWidth="1px solid";

        nomeform.desc_objeto.focus();
        return false;
    }
    if (nomeform.ajuda.value=="")
    {
        alert ("\nDigite a ajuda deste Menu");

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
					<td class="info" align="middle"><b>:: Editar <?echo $titulo?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="488" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
                            <input type='hidden' name='id_objeto' value='<?echo $id_objeto?>'></td>
						</tr>
						<tr>
							<td width="70">
							<p align="right">Menu:&nbsp; </td>
							<td width="418" height="23">
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
                            
                            }else{
                               $valor0=$_SESSION['desc_objeto'];
                               $valor01=$_SESSION['ajuda'];
                            }
                            
                            ?>
							<input size="68" name="desc_objeto" value="<?echo $valor0?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						<tr>
							<td width="488" colspan="2" height="23">
							<p align="center">Descrição do Menu(AJUDA)</td>
						</tr>
						<tr>
							<td height="23" width="70">
							<p align="right">&nbsp; </td>
							<td height="23" width="418">
							<textarea rows="6" name="ajuda" cols="67" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #FFFFFF;"><?echo $valor01?></textarea></td>
						</tr>
						<tr>
							<td colspan="2">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">
							<input type="submit" value="Editar Menu" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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
    //VER ISSO// $cadas = mysql_query("UPDATE sgc_item_menu SET where $id_chave='$id_objeto'") or print(mysql_error());
     header("Location: ?action=$arquivo&id_item=$id_item");
   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=$arquivo&id_item=$id_item&msg=$msg");
   }
   
   
}elseif($acao_int=="cad_objeto"){
  
$id_item = $_POST['id_item'];



if($_FILES['arquivo']['size'] > 0)
{
  $fileName_original = $_FILES['arquivo']['name'];
  $tmpName_original = $_FILES['arquivo']['tmp_name'];
  $fileSize_original = $_FILES['arquivo']['size'];
  $fileType_original = $_FILES['arquivo']['type'];
}
       
       
       
       //$permissao_item=acesso($idusuario,$id_item);

       $permissao_item="OK";

       if($permissao_item=="OK"){
  
       $titulo=$_POST['titulo'];
       $titulo=ltrim("$titulo");
       session_register('titulo');
       
       $descricao=$_POST['descricao'];
       session_register('descricao');

       $prioridade=$_POST['prioridade'];
       session_register('prioridade');


       $id_usuario_selecionado=$_POST['$id_usuario_selecionado'];

       if($id_usuario_selecionado==null){
       
          $id_usuario_selecionado=$idusuario;

       }
       session_register('id_usuario_selecionado');

       $checa = mysql_query("SELECT
                 id_unidade
                ,id_centro
                ,id_departamento
                FROM sgc_usuario where id_usuario=$id_usuario_selecionado") or print(mysql_error());
         while($dados=mysql_fetch_array($checa)){
         $idunidade = $dados['id_unidade'];
         $idcentro  = $dados['id_centro'];
         $iddepartamento = $dados['id_departamento'];
      }



       $cadas = mysql_query("INSERT INTO $tabela

                           (id_urgencia_usuario
                           ,id_usuario
                           ,id_unidade
                           ,id_centro
                           ,id_departamento
                           ,titulo
                           ,descricao
                           ,data_criacao
                           ,quem_criou
                           )

                           VALUES

                           ( $prioridade
                           , $idusuario
                           , $idunidade
                           , $idcentro
                           , $iddepartamento
                           , '$titulo'
                           , '$descricao'
                           , sysdate()
                           , $idusuario)") or print(mysql_error());

      session_unregister('titulo');
      session_unregister('descricao');
      session_unregister('prioridade');
      
     // header("Location: ?action=$arquivo&id_item=$id_item");


    }

    }else{
     $msg="Você não tem permissão para esta operação";
//     header("Location: ?action=$arquivo&id_item=$id_item&msg=$msg");
   }
    
    
  }


else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
