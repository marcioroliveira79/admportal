<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Cadastro de Gestores de Homologação";
$titulo_listar="Gestores já cadastrados";
$arquivo="cad_gestor.php";
$tabela="sgc_gestor_homologacao";
$id_item=$_GET['id_item'];

if(!isset($acao_int)){
?>

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


<form method="POST" id="form1" name='meuFormulario' action="sgc.php?action=<?echo $arquivo?>&acao_int=cad_objeto" onsubmit='seleciona();loopSelectedAS()' >
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
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
					&nbsp;
					<table border="0" width="500" cellspacing="0" cellpadding="0">
                                    <tr align="right">
									<td width="14">&nbsp;</td>
									<td width="209" valign="top">&nbsp;</td>
									<td>&nbsp;</td>
									<td width="207" valign="top">
                                    </td>
									<td width="13">&nbsp;</td>
						    		</tr>
<tr>
<td width="14">&nbsp;</td>
<td width="209" valign="top">
<span style="background-color: #FFFFFF">
<select style="border-style:solid; border-width:1px; font-size: 9px; width: 207; font-family: Verdana; height: 258; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px  " multiple size="21" name="todos" >
<?




     $checa = mysql_query("SELECT us.id_usuario,concat(us.primeiro_nome,' ',us.ultimo_nome)nome
FROM sgc_usuario us
where us.id_usuario
not in (SELECT id_usuario FROM sgc_gestor_homologacao ) order by us.primeiro_nome, us.ultimo_nome") or print(mysql_error());
                           while($dados=mysql_fetch_array($checa)){
                           $id_usuario_busca = $dados['id_usuario'];
                           $descricao_p = $dados["nome"];

                           ?>

                           <option value="<?echo $id_usuario_busca?>"><?echo $descricao_p?></option>

                           <?
                           }
                           ?>
                           </select></span></td>
						   <td>

                           <p align="center">
                           <input type='button' name='botaoET' onClick='moveElementoDaLista(this.form.todos,this.form.selecionados)' value='>>'><br>
                           <input type='button' name='botaoEY' onClick='moveElementoDaLista(this.form.selecionados,this.form.todos)' value='<<'>

						   </td>
						   <td width="207" valign="top">
						   <p align="right">
						   <span style="background-color: #FFFFFF">
						   <select style="border-style:solid; border-width:1px; font-size: 9px; width: 205; font-family: Verdana; height: 258; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" id='selSeaShellsAS' multiple size="21" name="selecionados">
                           <?
                           $checa = mysql_query("SELECT us.id_usuario,concat(us.primeiro_nome,' ',us.ultimo_nome)nome FROM sgc_usuario us
                           where us.id_usuario in (SELECT id_usuario FROM sgc_gestor_homologacao) order by us.primeiro_nome, us.ultimo_nome                           ") or print(mysql_error());
                           while($dados=mysql_fetch_array($checa)){
                           $id_usuario_assoc = $dados['id_usuario'];
                           $descricao_p1 = $dados["nome"];

                           ?>

                           <option value="<?echo $id_usuario_assoc?>"><?echo $descricao_p1?></option>

                           <?
                           }
                           ?>
                          <input type='hidden' name='conjunto_selecionado' id='txtSelectedValuesAS'/>
                          </select>

                          </span></td>
									<td width="13">&nbsp;</td>
								</tr>
								<tr>
									<td width="14">&nbsp;</td>
									<td width="209" valign="top">&nbsp;</td>
									<td>&nbsp;</td>
									<td width="207" valign="top">
                                  <input type="submit" value="Salvar" name="B1" style="float: right"></td>
									<td width="13">&nbsp;</td>
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
}elseif($acao_int=="cad_objeto"){


echo $id_item=$_POST['id_item'];
$conjunto_selecionado=$_POST['conjunto_selecionado'];


$vetor_conjunto = explode(",", $conjunto_selecionado);
$deleta = mysql_query("DELETE FROM sgc_gestor_homologacao") or print(mysql_error());
$result = count($vetor_conjunto);


foreach($vetor_conjunto as $value){

if($result>0 and $value !=null){
  $cadas = mysql_query("INSERT INTO sgc_gestor_homologacao (id_usuario, data_cadastro, quem_cadastrou) VALUES ($value,sysdate(),$idusuario)") or print(mysql_error());
}
}
header("Location: ?action=$arquivo&id_item=$id_item");
}


}else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
