<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Enviar Mensagem";
$titulo_listar="Histórico de Mensagens";
$id_item=$_GET['id_item'];
$arquivo="env_mensagem.php";
$tabela="sgc_mensagem";
$id_chave="id_mensagem";

include("fckeditor/fckeditor.php") ;




if(!isset($acao_int)){
?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_objeto.value=="")
    {
        alert ("\nDigite o Título da Mensagem.");

        document.form1.desc_objeto.style.borderColor="#FF0000";
        document.form1.desc_objeto.style.borderWidth="1px solid";

        nomeform.desc_objeto.focus();
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



<form method="POST" name="form1" action="sgc.php?action=<?echo $arquivo?>&acao_int=add_user" onSubmit="return valida_dados(this)">
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
							<td width="5">
							<p align="right">Título:&nbsp;</td>
							<td width="5" height="23">
							<?
							if(isset($msg)){
                               $borda="border:1px solid #FF0000;";
                            }else{
                               session_unregister('ajuda');
                               session_unregister('desc_objeto');
                            }
                            ?><input size="70" name="desc_objeto" value="<?echo $_SESSION['desc_objeto']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						<tr>
							<td width="100%" colspan="2" height="23">
							<p align="center">Mensagem</td>
						</tr>
						<tr>
							<td height="23" width="100%" colspan="2">
							<p align="right">
                            	<?
                                $descmodulo_m=strtoupper($descmodulo);
                                $oFCKeditor = new FCKeditor('ajuda');
                                $oFCKeditor->BasePath = 'fckeditor/';
                                $oFCKeditor->ToolbarSet = 'sgm';
                                //$oFCKeditor->Value = '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>--------------------------------------------------------------------------<br>Frimesa<br>'.$descmodulo_m.'<br>'.$nome_usuario.'<br>--------------------------------------------------------------------------';
                                $oFCKeditor->Width  = '100%' ;
                                $oFCKeditor->Height = '450' ;
                                $oFCKeditor->Create();
                                 ?>

                            </td>
						</tr>
						<tr>
							<td colspan="2"></td>
						</tr>
						<tr>
							<td colspan="2">
							<input type="submit" value="Próximo >>" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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


}elseif($acao_int=="add_user"){

       $id_item=$_POST['id_item'];
       $desc_objeto=$_POST['desc_objeto'];
       $ajuda=$_POST['ajuda'];
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

<script type="text/javascript">
function seleciona()

    {
    document.forms['meuFormulario'].para.options
	for(i=0;i<document.forms['meuFormulario'].para.options.length;i++){
	document.forms['meuFormulario'].para.options[i].selected=true;
	}

}
</script>

<script language="JavaScript" type="text/javascript">
function loopSelectedEMAILS()
{
  var txtSelectedValuesObj = document.getElementById('txtSelectedValuesEMAILS');
  var selectedArray = new Array();
  var selObj = document.getElementById('selSeaShellsEMAILS');
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


<script language='javascript'>
function valida_dados (nomeform)
{
    if (meuFormulario.emails.value=="")
    {
        alert ("\nVocê precisa selecionar o usuário para envio.");
        return false;
    }
return true;
}
</script>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<STYLE type="text/css">

	BODY {background: #FFFFFF ; color: black;}

	a:link {text-decoration: none; color: #363636;}
	a:visited {text-decoration: none; color: #363636;}
	a:active {text-decoration: none; color: #363636;}
	a:hover {text-decoration: underline; color: #363636;}

	a.kbase:link {text-decoration: underline; font-weight: bold; color: #000000;}
	a.kbase:visited {text-decoration: underline; font-weight: bold; color: #000000;}
	a.kbase:active {text-decoration: underline; font-weight: bold; color: #000000;}
	a.kbase:hover {text-decoration: underline; font-weight: bold; color: #000000;}


	table.border {background: #8C8984; color: black;}
	td {color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	tr {color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	td.back {background: #FFFFFF;}
	td.back2 {background: #EEEEEE;}

	td.date {background: #EEEEEE; font-family: "Arial"; font-size: 12px; color: #000000;}

	td.hf {background: #D0D0D0; font-family: "Arial"; font-size: 12px; color: #000000;}

	a.hf:link {text-decoration: none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #000000;}

	a.hf:visited {text-decoration:none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #000000;}

	a.hf:active {text-decoration: none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #000000;}

	a.hf:hover {text-decoration: underline; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #000000;}

	td.info {background: <?echo $cor_class_info=atributo('atributo23');?> ; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #FFFFFF;}

	a.info:link {text-decoration: none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #FFFFFF;}

	a.info:visited {text-decoration:none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #FFFFFF;}

	a.info:active {text-decoration: none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #FFFFFF;}

	a.info:hover {text-decoration: underline; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #FFFFFF;}

	select, option, textarea, input {font-family: Verdana, arial, helvetica, sans-serif; font-size:	11px; background: #EEEEEE; color: #000000;}

	td.cat {background: #EEEEEE; font-family: "Arial"; font-size: 12px; color: #000000;}

	td.stats {background: #EEEEEE; font-family: "Arial"; font-size: 10px; color: #000000;}

	td.error {background: #EEEEEE; color: #ff0000; font-family: "Arial"; font-size: 12px;}

	td.subcat {background: #EEEEEE; color: #000000; font-family: "Arial"; font-size: 12px;}



	input.box {border: 0px;}

	table.border2 {background: #6974b5;}
	td.install {background:#dddddd; color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	table.install {background: #000099;}
	td.head	{background:#6974b5; color: #ffffff; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	a.install:link {text-decoration: none; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #6974b5;}
	a.install:visited {text-decoration:none; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #6974b5;}
	a.install:active {text-decoration: none; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000099;}
	a.install:hover {text-decoration: underline; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000099;}

</STYLE>
</head>
</head>

<body>

			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Selecionar usuário para envio de mensagem :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">

<form name="meuFormulario" action="?action=env_mensagem.php&acao_int=cad_objeto" method="post" onsubmit='seleciona();loopSelectedEMAILS();'>



<div align='center'>
	<table border="0" width="300" height="125" cellspacing="0" cellpadding="0">
		<tr>
			<td  height="36">
			<table border="0" width="99%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="16">&nbsp;</td>
					<td>
					<p align="center"><b><font face="Verdana" size="1">::Selecione
					os usuários::</font></b></td>
					<td width="34">&nbsp;</td>
				</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
			<table border="0" width="432" cellspacing="0" cellpadding="0">
				<tr>
					<td width="17">&nbsp;</td>
					<td width="382" align="right">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">


                        <tr>
							<td rowspan="2" valign="top">
							<p align="right">
							<select size="21" name="allusers" multiple style="width: 170px; font-family: Verdana; font-size: 9px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px">

                            <?
                            $checa = mysql_query("SELECT
                             concat(us.primeiro_nome,' ',us.ultimo_nome,' - ',un.sigla)usuario
                            ,us.id_usuario
                            FROM sgc_usuario us, sgc_unidade un, sgc_departamento dp
                            where us.id_departamento = dp.id_departamento
                            and us.id_unidade = un.codigo
                            order by usuario") or print(mysql_error());
                           while($dados=mysql_fetch_array($checa)){
                                $nome = $dados['usuario'];
                                $id_usuario = $dados['id_usuario'];

                     $nome=ucwords(strtolower($nome));
                     $nome_=$nome;

                     $phpCont = strlen($nome_);
                       if($phpCont > 27){
                     $nome_ = substr($nome_,0,25)."...";
                     }


                     echo" <option value='$id_usuario'>$nome_</option>";

                     }

                     ?>
							</option>
							</select></td>
							<td width="44" valign="bottom">
							<input type='button' name='botaoET' value='>>' onClick='moveElementoDaLista(this.form.allusers,this.form.para)'  ></td>
							<td rowspan="2" width="313" valign="top">
							<p align="left">

                        	<select size="22" name="para" id="selSeaShellsEMAILS" multiple style="width: 170px;  font-family: Verdana; font-size: 9px">
                            </option>
							</select></td>
                            <input type='hidden' name='emails' id='txtSelectedValuesEMAILS'/>
                            <input type="hidden" name="id_item" value="<?echo $id_item?>"/>
                            <input type='hidden' name='desc_objeto' value='<?echo $desc_objeto?>'/>
                            <input type='hidden' name='ajuda' value='<?echo $ajuda?>'/>
                        <tr>




							<td width="44" valign="top">
                          <input type='button' name='botaoEY' value='<<' onClick='moveElementoDaLista(this.form.para,this.form.allusers)' ></td>

                        </tr>
					</table>
					</td>
					<td width="33">&nbsp;</td>
				</tr>
				<tr>
					<td width="17">&nbsp;</td>
					<td width="382" align="right">&nbsp;</td>
					<td width="33">&nbsp;</td>
				</tr>
							<tr>
							<td width="306">
							<p align="right"></td>
							<td width="713" height="23">

							<select size="1" style=" font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="sureg">
   							<option value="XXX">Usuários Selecionados</option>
                            <?
                                    $checa = mysql_query("select codigo,concat(codigo,' - ',sigla) descricao
                                    from sgc_unidade where desativado is null order by codigo desc, codigo asc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_unidade = $dados['codigo'];
                                    $ler_descricao = $dados['descricao'];
                            ?>
                            <option value="<?echo $id_unidade?>"><?echo $ler_descricao?></option>
                            <?
                            }
                            ?>
                            </select></td>
						</tr>
				
                	</tr>
					</tr>
					</tr>
					</tr>
				<tr>
					<td width="17">&nbsp;</td>
					<td width="382" align="center">
					<div align="center">
						<table border="0" width="281" cellspacing="0" cellpadding="0">
							<tr>
								<td width="107">
                                <input type="submit" value="Enviar" name="B1">
							</tr>
						</table>
					</div>
					</td>
					<td width="33">&nbsp;</td>
				</tr>
    		</table>
			</td>
		</tr>
		<tr>
		<td  height='35'>&nbsp;</td>
		</tr>
	</table></td>
				</tr>
			</table>

</body>

</form>
<p>&nbsp;</p>


</html>
<?








}elseif($acao_int=="cad_objeto"){

       $id_item=$_POST['id_item'];

       $permissao_item=acesso($idusuario,$id_item);

       if($permissao_item=="OK"){
              $sureg=$_POST['sureg'];
              $emails=$_POST['emails'];
              $desc_objeto=$_POST['desc_objeto'];
              $desc_objeto=ltrim("$desc_objeto");
              $ajuda=$_POST['ajuda'];

              if($sureg!="XXX"){
                 $emails="";
                 $checa = mysql_query("SELECT * FROM sgc_usuario WHERE id_unidade = $sureg") or print(mysql_error());
                 while($dados=mysql_fetch_array($checa)){
                    $idemails = $dados['id_usuario'];
                    $virgula=",";
                    $emails.=$idemails.$virgula;
                 }
              $emails=substr_replace($emails,' ', -1);
              }

            $cadas = mysql_query("INSERT INTO sgc_mensagem
            (titulo, mensagem, data_criacao, quem_criou)
            VALUES
            ('$desc_objeto','$ajuda',sysdate(),$idusuario)") or print(mysql_error());

            $id_mensagem=ultimo_registro('id_mensagem','sgc_mensagem','id_mensagem');

            $id_emails = explode(",", $emails);

            foreach($id_emails as $valor){

$link=tabelainfo('1','sgc_organizacao','link','id_organizacao','');
$mensagem_g="<p><font face='Courier New'  size='2'>
**************************** NOVA MENSAGEM *********************************<BR>
    Você recebeu uma nova mensagem, para visualiza-la click no link abaixo:
---------------------------------------------------------------------------<BR>
<a href='$link/sgc.php?action=caixa_entrada.php&acao_int=ver_mensagem&id_mensagem=$id_mensagem&id_item=$id_item'>$desc_objeto</a><BR>
---------------------------------------------------------------------------<BR>
</font></p>";

           $email_g=tabelainfo($valor,'sgc_usuario','email','id_usuario');
           $nome_g=tabelainfo($valor,'sgc_usuario','primeiro_nome','id_usuario');

            if(atributo('atributo10')=="ON"){


              if(tabelainfo(atributo('atributo11'),'sgc_usuario','email','id_usuario','')!=$email_g){
               $email=send_mail_smtp("$nome_g, você recebeu uma nova mensagem!",$mensagem_g,$mensagem_g,$email_g,$nome_g);
              }

            }

            $cadas = mysql_query("INSERT INTO sgc_usuarios_mensagens  (id_mensagem, id_usuario, data_criacao, quem_criou)
             VALUES
            ('$id_mensagem','$valor',sysdate(),$idusuario)") or print(mysql_error());

            $cadas = mysql_query("INSERT INTO sgc_mensagem_enviada  (id_mensagem, assunto, texto, data_envio,origem,destino)
             VALUES
            ('$id_mensagem','$desc_objeto','$ajuda',sysdate(),$idusuario,$valor)") or print(mysql_error());


          }
         header("Location: ?action=env_mensagem.php&acao_int=sucesso");

    }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=env_mensagem.php&msg=$msg");
   }
}elseif($acao_int=="sucesso"){
?>
    <div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: SUCESSO :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td>
							<p align="center">&nbsp;</p>
							<p align="center">Mensagem registrada com sucesso!</p>
							<p align="center">&nbsp;</td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>
<p>&nbsp;</p>
<?
}
  
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
