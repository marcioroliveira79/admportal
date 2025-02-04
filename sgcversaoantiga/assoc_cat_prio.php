<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Associação de Categorias e Prioridades";
$titulo_listar="Regras Já Cadastradas";
$id_item=$_GET['id_item'];
$arquivo="assoc_cat_prio.php";
$tabela="sgc_ass_cat_prio";
$id_chave="id_associacao";


if(!isset($acao_int)){

if(!isset($_POST['usuario_menu'])){
  $usuario_menu=$_GET['id_usuario'];
  $id_item=$_GET['id_item'];

}else{
  $usuario_menu=$_POST['usuario_menu'];
  $id_item=$_POST['id_item'];
}


?>

<script type="text/javascript" src="conf\prototype.js"></script>
<script language="javascript"  src="ajax-cate-prio.js" type="text/javascript"></script>



<script language='javascript'>
function confirmaExclusao(aURL) {
if(confirm('Você esta prestes a apagar este registro,deseja continuar?')) {
location.href = aURL;
}
}
</script>

<form method="POST" id="form1" name='meuFormulario' action="sgc.php?action=<?echo $arquivo?>&acao_int=cad_bd_regra" onsubmit='seleciona();loopSelectedAS()' style="font-family: Verdana; font-size: 8pt">

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
					<table border="0" width="488" cellspacing="0" cellpadding="0">

                        <tr>
							<td height="23" width="66">
							<p align="right">Categoria:&nbsp;
							<font color="#FF0000"><?echo $msg?></font></td>
                                       </td>
							<td height="23" width="418">

                            <select size="1" name="categoria"  Onchange="atualiza(this.value);"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                                 <option value="#">Selecione a Categoria</option>
                                 <?
                         $checa = mysql_query("select ct.id_categoria, ct.descricao from  sgc_categoria ct") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_objeto = $dados['id_categoria'];
                                    $ler_descricao_objeto = $dados['descricao'];


                        ?>

                        <option value="<?echo $id_objeto ?>"><?echo $ler_descricao_objeto?></option>

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

 $categoria=$_POST['categoria'];
 $prioridade=$_POST['prioridade'];


echo "Categoria: $categoria <br>";
echo "Prioridade: $prioridade <br><br>";

 $permissao_item=acesso($idusuario,$id_item);

 if($permissao_item=="OK"){

  $regra=tabelainfo($categoria,"sgc_ass_cat_prio","id_associacao","id_categoria"," ");

  if($regra!="<font face='Verdana' size='1' color='#FFFFFF'><span style='background-color: #FF0000'>&nbsp;Referência Inválida! </span></font>"){

     $update = mysql_query("UPDATE sgc_ass_cat_prio set id_categoria=$categoria, id_prioridade=$prioridade,quem_alterou=$idusuario,data_alteracao=sysdate() where id_associacao = $regra") or print(mysql_error());

  }else{
  
     $cadastro = mysql_query("INSERT INTO sgc_ass_cat_prio (id_categoria,id_prioridade,quem_criou,data_criacao) VALUES ($categoria,$prioridade,$idusuario,sysdate())") or print(mysql_error());
  
   }
   header("Location: ?action=assoc_cat_prio.php&id_item=$id_item");
  }else{
   $msg="Você não tem permissão para alteração";
   header("Location: ?action=assoc_cat_prio.php&id_item=$id_item&msg=$msg");
  }

  }
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>

</BODY>
</HTML>
