<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Cadastro de Usuário";
$titulo_listar="Centros Já Cadastrados";
$arquivo="cad_usuario.php";
$tabela="sgc_usuario";
$id_item=$_GET['id_item'];
$id_chave="id_usuario";





if(!isset($acao_int)){
?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.objeto1.value=="")
    {
        alert ("\nDigite o Nome do Usuário.");

        document.form1.objeto1.style.borderColor="#FF0000";
        document.form1.objeto1.style.borderWidth="1px solid";

        nomeform.objeto1.focus();
        return false;
    }
     if (nomeform.objeto2.value=="")
    {
        alert ("\nDigite o Sobrenome do Usuário.");

        document.form1.objeto2.style.borderColor="#FF0000";
        document.form1.objeto2.style.borderWidth="1px solid";

        nomeform.objeto2.focus();
        return false;
    }
    if (nomeform.objeto3.value=="")
    {
        alert ("\nDigite o e-mail do Usuário ");

        document.form1.objeto3.style.borderColor="#FF0000";
        document.form1.objeto3.style.borderWidth="1px solid";

        nomeform.objeto3.focus();
        return false;
    }
   if (nomeform.objeto4.value=="")
    {
        alert ("\nDigite o DDD ");

        document.form1.objeto4.style.borderColor="#FF0000";
        document.form1.objeto4.style.borderWidth="1px solid";

        nomeform.objeto4.focus();
        return false;
    }
  if (nomeform.objeto5.value=="")
    {
        alert ("\nDigite o Número do Telefone ");

        document.form1.objeto5.style.borderColor="#FF0000";
        document.form1.objeto5.style.borderWidth="1px solid";

        nomeform.objeto5.focus();
        return false;
    }
    if (nomeform.objeto12.value=="")
    {
        alert ("\nDigite a senha ");

        document.form1.objeto12.style.borderColor="#FF0000";
        document.form1.objeto12.style.borderWidth="1px solid";

        nomeform.objeto12.focus();
        return false;
    }
    if (nomeform.objeto13.value=="")
    {
        alert ("\nRe-digite a senha ");

        document.form1.objeto13.style.borderColor="#FF0000";
        document.form1.objeto13.style.borderWidth="1px solid";

        nomeform.objeto13.focus();
        return false;
    }
      if (nomeform.objeto13.value != nomeform.objeto12.value)
    {
        alert ("\nSenha Diferente");

        nomeform.objeto13.focus();
        return false;
    }


return true;
}
</script>
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
							<td width="306">
							<p align="right">Nome:&nbsp;&nbsp; </td>
							<td width="713" height="23" colspan="2">
							<input size="10" name="objeto1" value="<?echo $_SESSION['objeto1']?>" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"maxlength="10"></td>
						</tr>
							<tr>
							<td width="306">
							<p align="right">Sobrenome:&nbsp;&nbsp; </td>
							<td width="312" height="23">
							<input size="60" name="objeto2" value="<?echo $_SESSION['objeto2']?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="60"></td>
							<td width="401" height="23">
							&nbsp;</td>
						</tr>
							<tr>
							<td width="306">
							<p align="right">e-mail:&nbsp;&nbsp; </td>
							<td width="312" height="23">
							<input size="60" name="objeto3" value="<?echo $_SESSION['objeto3']?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="60"></td>
							<td width="401" height="23">
							&nbsp;</td>
						</tr>
							<tr>
							<td width="306">
							<p align="right">Telefone:&nbsp;&nbsp; </td>
							<td width="312" height="23">
							<input size="2" name="objeto4" value="<?echo $_SESSION['objeto4']?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="2"><!--webbot bot="Validation" b-value-required="TRUE" i-maximum-length="9" --><input size="9" name="objeto5" value="<?echo $_SESSION['objeto5']?>" onKeyUp="mascaraTexto(event,'9999-9999')" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="9">
							Ramal:
							<input size="4" name="objeto6" value="<?echo $_SESSION['objeto6']?>"   style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="4"></td>
							<td width="401" height="23">
							&nbsp;</td>
						</tr>
							<tr>
							<td width="306">
							<p align="right">Unidade:&nbsp;&nbsp;&nbsp; </td>
							<td width="312" height="23">
							<select size="1" style=" font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="objeto7">
                            <?
                                    $checa = mysql_query("select codigo,concat(codigo,'-',descricao) descricao from  sgc_unidade  where desativado is null order by codigo asc  ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_unidade = $dados['codigo'];
                                    $ler_descricao = $dados['descricao'];
                            ?>
                            <option value="<?echo $id_unidade?>"><?echo $ler_descricao?></option>
                            <?
                            }
                            ?>
                            </select></td>
							<td width="401" height="23">
							&nbsp;</td>
						</tr>
						</tr>
							<tr>
							<td width="306">
							<p align="right">Departamento:&nbsp;&nbsp;&nbsp; </td>
							<td width="312" height="23">
							<select size="1" style=" font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="objeto8">
                            <?
                                    $checa = mysql_query("select id_departamento,descricao from sgc_departamento where desativado is null order by descricao asc  ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_departamento = $dados['id_departamento'];
                                    $ler_descricao = $dados['descricao'];
                            ?>
                            <option value="<?echo $id_departamento?>"><?echo $ler_descricao?></option>
                            <?
                            }
                            ?>
                            </select></td>
							<td width="401" height="23">
							&nbsp;</td>
						</tr>
							<tr>
							<td width="306">
							<p align="right">Centro de Custo:&nbsp;&nbsp;&nbsp; </td>
							<td width="312" height="23">
							<select size="1" style=" font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="objeto9">
                            <?
                          $checa = mysql_query("select
                            cc.id_centro
                           ,cc.ajuda
                           ,cc.codigo
                           ,concat(cc.codigo,' - ',cc.descricao,' - Área: ',an.descricao,' - Tipo Gasto: ',tg.descricao) descricao
                           ,concat(cc.codigo,' - ',cc.descricao) resumida
                           from sgc_centro_custo cc, sgc_area_negocio an, sgc_tipo_gasto tg
                           where an.id_area = cc.id_area
                           and tg.id_gasto = cc.id_gasto
                           and cc.desativado is null
                           order by cc.codigo asc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_cc = $dados['codigo'];
                                    $ler_resumida = $dados['resumida'];

                            ?>
                            <option value="<?echo $id_cc?>"><?echo $ler_resumida?></option>
                            <?
                            }
                            ?>
                            </select></td>
							<td width="401" height="23">
							&nbsp;</td>
						</tr>
							</tr>
							<tr>
							<td width="306">
							<p align="right">Responsabilidade:&nbsp;&nbsp;&nbsp; </td>
							<td width="312" height="23">
							  <?


                            if(alter_resp($idusuario)=="SIM"){
                               $flag=null;
                            }else{
                               $flag="disabled";

                            }
                            ?>
							<select size="1" <?echo $flag?> style=" font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="objeto10">
                            <?
                                    $checa = mysql_query("SELECT * FROM sgc_template_menu ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_template = $dados['id_template'];
                                    $ler_descricao = $dados['descricao'];

                            ?>
                            <option value="<?echo $id_template?>"><?echo $ler_descricao?></option>
                            <?
                            }
                            ?>
                            </select></td>
							<td width="401" height="23">
							&nbsp;</td>
					    </tr>
							</tr>
							<tr>
							<td width="306">
							<p align="right">Acesso Externo:&nbsp;&nbsp;&nbsp; </td>
							<td width="312" height="23">
							<select size="1" style=" font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="objeto11">
                            <option value="SIM">SIM</option>
                            <option value="NAO">NÃO</option>
                            </select></td>
							<td width="401" height="23">
							&nbsp;</td>
						</tr>
						<tr>
							<td width="306">
							<p align="right">Senha:&nbsp;&nbsp; </td>
							<td width="312" height="23">
							<input type="password" size="20" name="objeto12" value="<?echo $_SESSION['objeto12']?>" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"maxlength="20"></td>
							<td width="401" height="23">
							&nbsp;</td>
						</tr>
    					<tr>
							<td width="306">
							<p align="right">Confirmar Senha:&nbsp;&nbsp; </td>
							<td width="312" height="23">
							<input type="password" size="20" name="objeto13" value="<?echo $_SESSION['objeto13']?>" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"maxlength="20"></td>
							<td width="401" height="23">
							&nbsp;</td>
						</tr>


     						<tr>
							<td colspan="2" width="618">
							&nbsp;</td>
							<td width="401">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" width="618">
							<input type="submit" value="Cadastrar" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
							<td width="401">
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
   }else{
     $msg="Você não tem permissão para esta operação";
  //   header("Location: ?action=$arquivo&id_item=$id_item&msg=$msg");
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
							<td colspan="2" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
						</tr>
						<tr>
							<td width="242">
							<p align="right">Nome:&nbsp; </td>
							<td width="551" height="23">
							<input size="68" name="desc_objeto" value="<?echo $_SESSION['desc_objeto']?>" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="242">
							<p align="right">Sobrenome:&nbsp; </td>
							<td width="551" height="23">
							<input size="82" name="codigo" value="<?echo $_SESSION['codigo']?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="5"></td>
						</tr>
							<tr>
							<td width="242">
							<p align="right">e-mail:</td>
							<td width="551" height="23">
							<input size="82" name="codigo0" value="<?echo $_SESSION['codigo']?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="5"></td>
						</tr>
							<tr>
							<td width="242">
							<p align="right">Telefone:</td>
							<td width="551" height="23">
							<!--webbot bot="Validation" B-Value-Required="TRUE" I-Maximum-Length="2" -->
							<input size="2" name="ddd" value="<?echo $_SESSION['codigo']?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="2"><!--webbot bot="Validation" B-Value-Required="TRUE" I-Maximum-Length="9" --><input size="9" name="fone" value="<?echo $_SESSION['codigo']?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="9">
							Ramal:
							<!--webbot bot="Validation" B-Value-Required="TRUE" I-Maximum-Length="4" -->
							<input size="4" name="fone0" value="<?echo $_SESSION['codigo']?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="4"></td>
						</tr>
							<tr>
							<td width="242">
							<p align="right">Área:&nbsp;</td>
							<td width="551" height="23">
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
							<td width="242">
							<p align="right">Tipo gasto:&nbsp;</td>
							<td width="551" height="23">
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
						</tr>
						<tr>
							<td width="793" colspan="2" height="23">
							<p align="center">Descrição do Centro de Custo(AJUDA)</td>
						</tr>
						<tr>
							<td height="23" width="242">
							<p align="right">&nbsp; </td>
							<td height="23" width="551">
							<textarea rows="6" name="ajuda" cols="67" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #FFFFFF;"><?echo $_SESSION['ajuda']?></textarea></td>
						</tr>
						<tr>
							<td colspan="2">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">
							<input type="submit" value="Adicionar Centro de Custo" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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
     $deleta = mysql_query("DELETE FROM $tabela where id_centro=$id_objeto") or print(mysql_error());
     header("Location: ?action=$arquivo&id_item=$id_item");
   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=$arquivo&id_item=$id_item&msg=$msg");
   }


  }elseif($acao_int=="cad_objeto"){

       $id_item=$_POST['id_item'];
       $permissao_item=acesso($idusuario,$id_item);


       if($permissao_item=="OK"){

    $objeto1=$_POST['objeto1'];
       session_register('objeto1');

    $objeto2=$_POST['objeto2'];
       session_register('objeto2');

    $objeto3=$_POST['objeto3'];
       session_register('objeto3');

    $objeto4=$_POST['objeto4'];
       session_register('objeto4');

    $objeto5=$_POST['objeto5'];
       session_register('objeto5');

    $objeto6=$_POST['objeto6'];
       session_register('objeto6');

    $objeto7=$_POST['objeto7'];
       session_register('objeto7');

    $objeto8=$_POST['objeto8'];
       session_register('objeto8');

    $objeto9=$_POST['objeto9'];
       session_register('objeto9');

    $objeto10=$_POST['objeto10'];
       session_register('objeto10');

    $objeto11=$_POST['objeto11'];
       session_register('objeto11');

    $objeto12=$_POST['objeto12'];
       session_register('objeto12');

    $objeto13=$_POST['objeto13'];
       session_register('objeto13');




    $integridade_codigo=integridade($objeto3,$tabela,"email","email");

    if($integridade_codigo=="Existe"){

    header("Location: ?action=$arquivo&id_item=$id_item&msg=Este usuário já esta cadastrado");
    exit;
    }else{

      $cadas = mysql_query("INSERT INTO $tabela
                          ( id_departamento
                          , id_unidade
                          , id_centro
                          , primeiro_nome
                          , ultimo_nome
                          , email
                          , senha
                          , ddd
                          , telefone
                          , ramal
                          , externo
                          , perfil
                          , data_criacao
                          , quem_criou


                          ) VALUES (
                           $objeto8
                          ,$objeto7
                          ,$objeto9
                          ,'$objeto1'
                          ,'$objeto2'
                          ,'$objeto3'
                          ,'$objeto12'
                          ,$objeto4
                          ,'$objeto5'
                          ,$objeto6
                          ,'$objeto11'
                          ,$objeto10
                          ,sysdate()
                          ,$idusuario)") or print(mysql_error());

       
       
      session_unregister('objeto1');
      session_unregister('objeto2');
      session_unregister('objeto3');
      session_unregister('objeto4');
      session_unregister('objeto5');
      session_unregister('objeto6');
      session_unregister('objeto7');
      session_unregister('objeto8');
      session_unregister('objeto9');
      session_unregister('objeto10');
      session_unregister('objeto11');
      session_unregister('objeto12');
      session_unregister('objeto13');

if(atributo('atributo10')=="ON"){
 $nome_adm="SGC";
 $email_adm="sgc@frimesa.com.br";
 $email_dest="$objeto3";
 $nome="$objeto1";
 $txtAssunto="Cadastro SGC Concluído";
 $link=organizacao('link');

 $mensagem="<p><font face='Verdana' size='1'>Seu cadastro foi realizado com sucesso no SGC
 'Sistema Gerencial de Chamados'<br>
 login: $objeto3<br>
 senha: $objeto12<br>
 click aqui para acessar --&gt; $link </font></p>";

 $email=email($nome_adm,$email_adm,$email_dest,$nome,$txtAssunto,$mensagem);
      
}

  ?>
  <div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Cadastro Realizado Com
					Sucesso :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					&nbsp;<table border="1" width="370"  cellspacing="3" cellpadding="0" bordercolor="#FF0000">
						<tr>
							<td>
							<table border="0" width="366" cellspacing="0" cellpadding="0">
								<tr>
									<td width="61">
									<p align="right">Nome:</td>
									<td width="289"><?echo "$objeto1 $objeto2";?></td>
								</tr>
								<tr>
									<td width="61">
									<p align="right">e-mail:</td>
									<td width="289"><?echo"$objeto3";?></td>
								</tr>
								<tr>
									<td width="61">
									<p align="right">Senha:</td>
									<td width="289"><?echo"$objeto12";?></td>
								</tr>
								<tr>
									<td width="61">&nbsp;</td>
									<td width="289">&nbsp;</td>
								</tr>
								<tr>
									<td width="350" colspan="2">
									<p align="center"><a href="?action=cad_usuario.php">
									<font color="#000000">Voltar</font></a></td>
								</tr>
							</table>
							</td>
						</tr>
					</table>
					<p>&nbsp;</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>
<?
  
//      header("Location: ?action=$arquivo&id_item=$id_item");

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
