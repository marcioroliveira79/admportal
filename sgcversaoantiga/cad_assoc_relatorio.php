<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Permissões de Relatório";
$titulo_listar="Permissões Já Cadastradas";
$id_item=$_GET['id_item'];
$arquivo="cad_assoc_relatorio.php";
$tabela="sgc_assoc_relatorios";
$id_chave="id_associc";





if(!isset($acao_int)){
?>
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

<form method="POST" id="form1" action="sgc.php?action=<?echo $arquivo?>&acao_int=select_menu" style="font-family: Verdana; font-size: 8pt">


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
					<table border="0" width="488" cellspacing="0" cellpadding="0">
						<tr>
							<td height="23" width="66">
							<p align="right">Usuário:&nbsp;
							<font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
							<td height="23" width="418">

                            <select size="1" name="usuario_menu"  onchange="this.form.submit();" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                                 <option value="#">Selecione o Usuário</option>
                                 <?
                          $checa = mysql_query("select id_usuario,concat(primeiro_nome,' ',ultimo_nome)nome from sgc_usuario order by primeiro_nome desc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_objeto = $dados['id_usuario'];
                                    $ler_descricao = $dados['nome'];

                        ?>

                        <option value="<?echo $id_objeto?>"><?echo $ler_descricao?></option>

                        <?
                        }
                        ?>

                            </select>


                            </td>
						</tr>
						<tr>
							<td width="70">
							<p align="right">&nbsp;&nbsp; </td>
							<td width="418" height="23">
                            <div id="atualiza">
                            &nbsp;</div>
                            </td>
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
}elseif($acao_int=="select_menu"){

if(!isset($_POST['usuario_menu'])){
  $usuario_menu=$_GET['id_usuario'];
  $id_item=$_GET['id_item'];

}else{
  $usuario_menu=$_POST['usuario_menu'];
  $id_item=$_POST['id_item'];
}
  

?>

<script type="text/javascript" src="conf\prototype.js"></script>
<script language="javascript"  src="ajax-usuario-relatorio.js" type="text/javascript"></script>



<script language='javascript'>
function confirmaExclusao(aURL) {
if(confirm('Você esta prestes a apagar este registro,deseja continuar?')) {
location.href = aURL;
}
}
</script>



<script>
function moveElementoDaLista(objFrom, objTo) {
try {
for (i = 0; i < objFrom.options.length; i++) {
if (objFrom.options[i].selected == true) {
no = new Option();
no.value = objFrom.options[i].value;
no.text = objFrom.options[i].text;
objTo.options[objTo.options.length] = no;
for (j = i + 1; j < objFrom.options.length; j++) {
objFrom.options[j - 1].value = objFrom.options[j].value;
objFrom.options[j - 1].text = objFrom.options[j].text;
objFrom.options[j - 1].selected = objFrom.options[j].selected;
}
objFrom.options[objFrom.options.length - 1] = null;
i--;
}
}
} catch(e) {
alert("Ocorreu um erro executando o método 'moveElementoDaLista(objFrom, objTo)'." +
"\nCausa:\n" + e);
}
}
</script>

<script language="JavaScript" type="text/javascript">
function loopSelectedAS()
{
  var txtSelectedValuesObj = document.getElementById('txtSelectedValuesAS');
  var selectedArray = new Array();
  var selObj = document.getElementById('selSeaShellsAS');
  var i;
  var count = 0;
  for (i=0; i<selObj.options.length; i++) {
    if (selObj.options[i].selected) {
      selectedArray[count] = selObj.options[i].value;
      count++;
    }
  }
  txtSelectedValuesObj.value = selectedArray;
}
</script>


<script type="text/javascript">
function seleciona(){
    document.forms['meuFormulario'].selecionados.options
	for(i=0;i<document.forms['meuFormulario'].selecionados.options.length;i++){
		document.forms['meuFormulario'].selecionados.options[i].selected=true;
	}
}
</script>

<script language="JavaScript" type="text/javascript">
function loopSelectedAS()
{
  var txtSelectedValuesObj = document.getElementById('txtSelectedValuesAS');
  var selectedArray = new Array();
  var selObj = document.getElementById('selSeaShellsAS');
  var i;
  var count = 0;
  for (i=0; i<selObj.options.length; i++) {
    if (selObj.options[i].selected) {
      selectedArray[count] = selObj.options[i].value;
      count++;
    }
  }
  txtSelectedValuesObj.value = selectedArray;
}
</script>




<form method="POST" id="form1" name='meuFormulario' action="sgc.php?action=<?echo $arquivo?>&acao_int=cad_bd_regra" onsubmit='seleciona();loopSelectedAS()' style="font-family: Verdana; font-size: 8pt">

         <input type='hidden' name='usuario' value='<?echo $usuario_menu?>'/>
         <input type='hidden' name='id_item' value='<?echo $id_item?>'>

<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Adicionar Usuário a <?echo $titulo?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="488" cellspacing="0" cellpadding="0">
                        <tr>
							<td height="23" width="66">
							<p align="right">Usuário:&nbsp;
							<font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
							<td height="23" width="418"><?echo tabelainfo($usuario_menu,'sgc_usuario','primeiro_nome','id_usuario')?> </td>
						</tr>
                        <tr>
							<td height="23" width="66">
							<p align="right">Relatório:&nbsp;
							<font color="#FF0000"><?echo $msg?></font></td>
                                       </td>
							<td height="23" width="418">

                            <select size="1" name="menu"  Onchange="atualiza(this.value);"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                                 <option value="#">Selecione o Menu</option>
                                 <? $id_menu_relatorio=atributo("atributo18");
                         $checa = mysql_query("
                         select rm.id_menu, im.id_item_menu,im.descricao
                         from sgc_regra_menu rm, sgc_item_menu im  where rm.id_menu = $id_menu_relatorio
                         and rm.id_item_menu = im.id_item_menu
                         ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_objeto = $dados['id_item_menu'];
                                    $ler_descricao_objeto = $dados['descricao'];


                        ?>

                        <option value="<?echo "$id_objeto/$usuario_menu"?>"><?echo $ler_descricao_objeto?></option>

                        <?
                        }
                        ?>

                            </select>


                            </td>
						</tr>
    						<tr>
							<td width="488" colspan="2" height="23">
							<p align="center">&nbsp;</td>
						</tr>
						<tr>
					<td height="23" width="488" colspan="2">
                    <div id="atualiza">
                    </div>
                      	</td>
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



}elseif($acao_int=="cad_bd_regra"){
 $idusuario = $_SESSION['id_usuario_global'];
 $id_item=$_POST['id_item'];

 $usuario=$_POST['usuario_select'];
 $relatorio=$_POST['rel_select'];
 
 $conjunto_selecionado=$_POST['conjunto_selecionado'];
 $vetor_conjunto = explode(",", $conjunto_selecionado);

echo "Usuario: $usuario <br>";
echo "Relatorio: $relatorio <br><br>";

 $permissao_item=acesso($idusuario,$id_item);

 if($permissao_item=="OK"){


$deleta = mysql_query("DELETE FROM sgc_assc_relatorios where id_controlador=$usuario and id_item=$relatorio ") or print(mysql_error());


echo $result = count($vetor_conjunto);


foreach($vetor_conjunto as $value){
 echo "Item:$value <br>";

if($result>0 and $value !=null){

     $cadas = mysql_query("INSERT INTO sgc_assc_relatorios (id_controlador, id_controlado, id_item, data_criacao,quem_criou)
                         VALUES ($usuario,$value,$relatorio,sysdate(),$idusuario)") or print(mysql_error());
 }

}
header("Location: ?action=$arquivo&acao_int=select_menu&id_item=$id_item&id_usuario=$usuario");
}

}elseif($acao_int=="editar_bd"){

     $idusuario = $_SESSION['id_usuario_global'];
     $id_objeto=$_POST['id_objeto'];
     $id_item=$_POST['id_item'];

     $desc_objeto=$_POST['desc_objeto'];
     $ajuda=$_POST['ajuda'];

     session_unregister('ajuda');
     session_unregister('desc_objeto');


     $permissao_item=acesso($idusuario,$id_item);
     
     
   if($permissao_item=="OK"){

       $existe=integridade("$desc_objeto","sgc_item_categoria","descricao","descricao","and $id_chave !=$id_objeto");

    if($existe=="Existe"){
     $msg="Já existe um deparamento com este titulo";
     session_register("desc_objeto");
     session_register("ajuda");

     header("Location: ?action=cad_item_categoria.php&acao_int=editar&id_objeto=$id_objeto&id_item=$id_item&msg=$msg&desc_objeto=$desc_objeto&ajuda=$ajuda");

    }else{


       $cadas = mysql_query("UPDATE sgc_item_categoria SET descricao='$desc_objeto',ajuda='$ajuda',data_alteracao=sysdate(),quem_alterou=$idusuario,oque_alterou='DEPARAMENTO OU AJUDA' where $id_chave='$id_objeto'") or print(mysql_error());
       session_unregister('ajuda');
       session_unregister('desc_objeto');
       header("Location: ?action=cad_item_categoria.php&id_item=$id_item");

    }
   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=cad_item_categoria.php&id_item=$id_item&msg=$msg");
   }




}
elseif($acao_int=="editar"){
$id_objeto=$_GET['id_objeto'];
$id_item=$_GET['id_item'];

$checa = mysql_query("select * from sgc_item_categoria where $id_chave=$id_objeto ") or print(mysql_error());
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
        alert ("\nDigite a descricao para o item.");

        document.form1.desc_objeto.style.borderColor="#FF0000";
        document.form1.desc_objeto.style.borderWidth="1px solid";

        nomeform.desc_objeto.focus();
        return false;
    }
    if (nomeform.ajuda.value=="")
    {
        alert ("\nDigite a ajuda deste item");

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
							<p align="right">Item:&nbsp; </td>
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
							<p align="center">Descrição do Item(AJUDA)</td>
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
							<td colspan="2"></td>
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
     $deleta = mysql_query("DELETE FROM sgc_item_categoria where $id_chave=$id_objeto") or print(mysql_error());
     header("Location: ?action=cad_item_categoria.php&id_item=$id_item");
   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=cad_item_categoria.php&id_item=$id_item&msg=$msg");
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

       $integridade=integridade($desc_objeto,"sgc_item_categoria","descricao","descricao");


  
    if($integridade=="Existe"){

      header("Location: ?action=cad_item_categoria.php&id_item=$id_item&msg=Já existe um departamento com este nome");
  
    }else{

     $cadas = mysql_query("INSERT INTO sgc_item_categoria (descricao, ajuda, data_criacao, quem_criou) VALUES ('$desc_objeto','$ajuda',sysdate(),$idusuario)") or print(mysql_error());
      session_unregister('ajuda');
      session_unregister('desc_objeto');
      header("Location: ?action=cad_item_categoria.php&id_item=$id_item");
    }

    }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=cad_item_categoria.php&id_item=$id_item&msg=$msg");
   }
    
    
  }
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
