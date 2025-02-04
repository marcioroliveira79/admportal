<?php
OB_START();
session_start();
//error_reporting(0);
include("conf/conecta.php");

$permissao = $_SESSION['permissao_global'];

if($permissao=='ok'){

header("Location: sgc.php");


}
else {



if(!isset($_GET['acao'])){

 $acao=$_POST['acao'];

}elseif(!isset($_POST['acao'])){

 $acao=$_GET['acao'];

}


if(!isset($acao)){
$mysql=new sgc;
$mysql->conectar();

$checa = mysql_query("SELECT atributo23 FROM sgc_parametros_sistema ") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
         $cor = $dados["atributo23"];
}

?>

<head>

<style>
<!--
	table.border {background: #8C8984; color: black;}
	td {color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	td.hf {background: #D0D0D0; font-family: "Arial"; font-size: 12px; color: #000000;}

	td.back {background: #FFFFFF;}
	td.info {background: <?echo $cor?> ; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #FFFFFF;}

	td.back2 {background: #EEEEEE;}

-->
</style>

</head>

<script type="text/javascript">
function setFocus(){
document.getElementById("login").focus();
}
</script>


<body topmargin="0" leftmargin="0" onload="setFocus();">


<table border="0" width="100%" cellspacing="0" cellpadding="0" background="imgs/f_cabecalho.jpg">
	<tr>
		<td width="1001">
		<img src="imgs/logo_sistema.jpg" /></td>
		<td width="132">
		<img src="imgs/logo_conab.jpg" width="126" height="59" align="right"></td>
	</tr>
</table>




<form action="index.php?acao=logar" method="post">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" align="center" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="hf" align="middle"><strong>Sistema Gerencial de Atendimento</strong></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="back">
					<table width="100%" border="0">
						<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
							<td class="back" vAlign="top"><br>
							&nbsp;<table class="border" cellSpacing="0" cellPadding="0" width="40%" align="center" border="0">
								<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
									<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
									<table cellSpacing="1" cellPadding="5" width="100%" border="0">
										<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
											<td class="info" align="left"><b>
											Sistema de Suporte login</b></td>
										</tr>
										<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
											<td class="back2">
											<table cellSpacing="0" cellPadding="6" width="100%" border="0">
												<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
													<td class="back2" align="right">
													Login:</td>
													<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
													<input size="55" id="login" name="login" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; font-weight: bold; color: #000000; border: 1px solid #8C8984; background: #EEEEEE"></td>
												</tr>
												<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
													<td class="back2" align="right">
													Senha:</td>
													<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
													<input type="password" size="12" value name="senha" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; font-weight: bold; color: #000000; border: 1px solid #8C8984; background: #EEEEEE"></td>
												</tr>
												<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
													<td class="back2" align="middle" colSpan="2">
													<input type="submit" value="Enviar"  style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; font-weight: bold; color: #000000; border: 1px solid #8C8984; background: #EEEEEE"></td>
												</tr>
                                                <?
                                                if($_GET['result']){
                                                  $msg=$_GET['result'];

                                                ?>
                                                <tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
													<td class="back2" align="middle" colSpan="2">
													<b><font color="#FF0000"><?echo $msg?> </font></b></td>
												</tr>
                                                <?
                                                }
                                                ?>


											</table>
											</td>
										</tr>
									</table>
									</td>
								</tr>
							</table>
                          	<br></center></td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</form>
<?
}elseif($acao=="send_mail"){
include("conf/funcs.php");
$mysql=new sgc;
$mysql->conectar();

$email=$_POST['email'];
$id_usuario=tabelainfo($email,'sgc_usuario','id_usuario','email',$and);

if($id_usuario=="<font face='Verdana' size='1' color='#FFFFFF'><span style='background-color: #FF0000'>&nbsp;Refer�ncia Inv�lida! </span></font>"){

$titulo="ERRO";
$msg="O e-mail digitado n�o existe";
$voltar="?acao=esqueci";

}else{

$senha=tabelainfo($email,'sgc_usuario','senha','email',$and);
$url=organizacao("link");
$email_env=email_sgc(atributo('atributo11'),$id_usuario,"SGC - Reenvio de Senha ","Reenvio de senha<BR> Login: $email<BR> Senha: $senha<BR><a href='$url'><font color='#000000'>$url</font></a>");


$titulo="Sucesso";
$msg="O e-mail com sua senha foi enviado";
$voltar="index.php";



}


?>
<head>

<style>
<!--
	table.border {background: #8C8984; color: black;}
	td {color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	td.hf {background: #D0D0D0; font-family: "Arial"; font-size: 12px; color: #000000;}

	td.back {background: #FFFFFF;}
	td.info {background: #336666; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #FFFFFF;}

	td.back2 {background: #EEEEEE;}

-->
</style>

</head>
<div align="center">
<table class="border" cellSpacing="0" cellPadding="0" width="500" border="0">
								<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
									<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
									<table cellSpacing="1" cellPadding="5" width="500" border="0">
										<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
											<td class="info" align="left">
											<p align="center"><b><?echo $titulo?></b></td>
										</tr>
										<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
											<td class="back2">
					<table border="0" width="100%" cellspacing="0" cellpadding="0" height="23">

						<tr>
							<td width="794">
							<p align="center"><font color="#FF0000"><?echo $msg?><br>
&nbsp;</font></td>
						</tr>
						</tr>

					    </tr>


						<tr>
							<td width="640">
							<p align="center"><a href="<?echo $voltar?>">
							<font color="#000000">Voltar</font></a></td>
						</tr>
					</table>
											</td>
										</tr>
									</table>
									</td>
								</tr>
							</table>
</div>

<?



}elseif($acao=="esqueci"){
?>
<head>

<style>
<!--
	table.border {background: #8C8984; color: black;}
	td {color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	td.hf {background: #D0D0D0; font-family: "Arial"; font-size: 12px; color: #000000;}

	td.back {background: #FFFFFF;}
	td.info {background: #336666; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #FFFFFF;}

	td.back2 {background: #EEEEEE;}

-->
</style>

</head>
<form method="POST" name="form1" action="index.php?acao=send_mail" onSubmit="return valida_dados(this)">
<div align="center">
<table class="border" cellSpacing="0" cellPadding="0" width="500" border="0">
								<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
									<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
									<table cellSpacing="1" cellPadding="5" width="500" border="0">
										<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
											<td class="info" align="left">
											<p align="center"><b>Esqueci Minha
											Senha</b></td>
										</tr>
										<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
											<td class="back2">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            </td>
						</tr>
						<tr>
							<td width="306">
							<p align="right">e-mail:&nbsp;&nbsp; </td>
							<td width="488" height="23">
							<input size="69" name="email" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"maxlength="60"></td>
						</tr>
						</tr>

					    </tr>


						<tr>
							<td colspan="2" width="640">
							<input type="submit" value="Reenviar" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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



}elseif($acao=="cad_usuario"){
OB_START();
session_start();
include("conf/funcs.php");
$mysql=new sgc;
$mysql->conectar();

    $objeto1=$_POST['objeto1'];
    $objeto1=ucwords(strtolower($objeto1));
       session_register('objeto1');

    $objeto2=$_POST['objeto2'];
    $objeto2=ucwords(strtolower($objeto2));
       session_register('objeto2');

    $objeto3=$_POST['objeto3'];
    $objeto3=strtolower($objeto3);
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


$email_rec=tabelainfo($objeto3,'sgc_usuario','email','email','');

if($email_rec!=$objeto3){

      $msg=null;
      session_unregister('objeto1');
      session_unregister('objeto2');
      session_unregister('objeto3');
      session_unregister('objeto4');
      session_unregister('objeto5');
      session_unregister('objeto6');
      session_unregister('objeto7');
      session_unregister('objeto8');
      session_unregister('objeto9');

  $perfil=atributo('atributo12');
  $senha=gerasenha();
  $cadas = mysql_query("INSERT INTO sgc_usuario
                          ( id_departamento
                          , id_unidade
                          , id_centro
                          , primeiro_nome
                          , ultimo_nome
                          , email
                          , senha
                          , desativacao
                          , ddd
                          , telefone
                          , ramal
                          , externo
                          , perfil
                          , data_criacao
                          , quem_alterou
                          , oque_alterou

                          ) VALUES (
                           $objeto8
                          ,$objeto7
                          ,$objeto9
                          ,'$objeto1'
                          ,'$objeto2'
                          ,'$objeto3'
                          ,'$senha'
                          ,sysdate()
                          ,$objeto4
                          ,'$objeto5'
                          ,$objeto6
                          ,'SIM'
                          ,$perfil
                          ,sysdate()
                          ,0
                          ,'Autocadastro - Aguardando confirma��o'

                               )") or print(mysql_error());




?>
<head>

<style>
<!--
	table.border {background: #8C8984; color: black;}
	td {color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	td.hf {background: #D0D0D0; font-family: "Arial"; font-size: 12px; color: #000000;}

	td.back {background: #FFFFFF;}
	td.info {background: #336666; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #FFFFFF;}

	td.back2 {background: #EEEEEE;}

-->
</style>

</head>
<BR><BR>
<table class="border" cellSpacing="0" cellPadding="0" width="500" align="center" border="0">
<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
<table cellSpacing="1" cellPadding="5" width="100%" border="0">
<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
<td class="info" align="left">
<p align="center"><b>Cadastro Conclu�do</b></td>
</tr>
<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
<td class="back2">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td height="23">
	<p align="center">&nbsp;<p align="center">
	<font color="#FF0000">Cadastro conclu�do com sucesso, ap�s valida��o de dados, voc� receber� <br>em seu e-mail uma senha para acesso.</font><p align="center">
	<font color="#FF0000">Obrigado.</font><p align="center">
	<font color="#FF0000"><a href="index.php">
	<font color="#000000">Voltar</font></a></font><p align="center">&nbsp;</td>
    </td>
</tr>
	</tr>
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
   $msg="Esse e-mail j� esta cadastrado!";
   header("Location: index.php?acao=registro&msg=$msg");

}

}elseif($acao=="registro"){
OB_START();
session_start();
$mysql=new sgc;
$mysql->conectar();



?>
<head>

<style>
<!--
	table.border {background: #8C8984; color: black;}
	td {color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	td.hf {background: #D0D0D0; font-family: "Arial"; font-size: 12px; color: #000000;}

	td.back {background: #FFFFFF;}
	td.info {background: #336666; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #FFFFFF;}

	td.back2 {background: #EEEEEE;}

-->
</style>

</head>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.objeto1.value=="")
    {
        alert ("\nDigite o Nome do Usu�rio.");

        document.form1.objeto1.style.borderColor="#FF0000";
        document.form1.objeto1.style.borderWidth="1px solid";

        nomeform.objeto1.focus();
        return false;
    }
     if (nomeform.objeto2.value=="")
    {
        alert ("\nDigite o Sobrenome do Usu�rio.");

        document.form1.objeto2.style.borderColor="#FF0000";
        document.form1.objeto2.style.borderWidth="1px solid";

        nomeform.objeto2.focus();
        return false;
    }
    if (nomeform.objeto3.value=="")
    {
        alert ("\nDigite o e-mail do Usu�rio ");

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
        alert ("\nDigite o N�mero do Telefone ");

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
if(confirm('Voc� esta prestes a apagar este registro,deseja continuar?')) {
location.href = aURL;
}
}
</script>



<form method="POST" name="form1" action="index.php?acao=cad_usuario" onSubmit="return valida_dados(this)">
<table class="border" cellSpacing="0" cellPadding="0" width="61%" align="center" border="0">
								<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
									<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
									<table cellSpacing="1" cellPadding="5" width="100%" border="0">
										<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
											<td class="info" align="left">
											<p align="center"><b>Sistema de Suporte login - Autocadastro</b></td>
										</tr>
										<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
											<td class="back2">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="3" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            </td>
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
                            if($_SESSION['objeto7']!=null){
                                  $objeto7=$_SESSION['objeto7'];
                            }else{
                                  $objeto7="null";
                            }

                                    $checa = mysql_query("select codigo,concat(codigo,'-',descricao) descricao from  sgc_unidade
                                    where desativado is null order by
                                    codigo=$objeto7 desc
                                    ,codigo asc ") or print(mysql_error());
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
                             if($_SESSION['objeto8']!=null){
                                  $objeto8=$_SESSION['objeto8'];
                            }else{
                                  $objeto8="null";
                            }

                                    $checa = mysql_query("select id_departamento,descricao from sgc_departamento where desativado is null
                                      order by
                                      id_departamento=$objeto8 desc
                                      ,descricao asc   ") or print(mysql_error());
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

                            if($_SESSION['objeto9']!=null){
                                  $objeto9=$_SESSION['objeto9'];
                            }else{
                                  $objeto9="null";
                            }

                          $checa = mysql_query("select
                          cc.id_centro
                          ,cc.ajuda
                          ,cc.codigo
                          ,concat(cc.codigo,' - ',cc.descricao,' - �rea: ',an.descricao,' - Tipo Gasto: ',tg.descricao) descricao
                          ,concat(cc.codigo,' - ',cc.descricao) resumida
                          from sgc_centro_custo cc, sgc_area_negocio an, sgc_tipo_gasto tg
                          where an.id_area = cc.id_area
                          and tg.id_gasto = cc.id_gasto
                          and cc.desativado is null
                          order by
                           cc.codigo=$objeto9 desc
                           ,cc.codigo asc ") or print(mysql_error());
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
</form>
<p>&nbsp;</p>
<?


}elseif($acao=="logar"){
$mysql=new sgc;
$mysql->conectar();

include("conf/funcs.php");
include("conf/func_ldap.php");

//---------------------------------------------Tempo Sess�o-----------------------------------//
$checa = mysql_query("SELECT atributo17 FROM sgc_parametros_sistema") or print(mysql_error());
  while($dados=mysql_fetch_array($checa)){
       $temposessao = $dados['atributo17'];
  }
//-------------------------------------------------------------------------------------------//





     function anti_injection($sql)
     {
     // remove palavras que contenham sintaxe sql
     $sql = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"),"",$sql);
     $sql = trim($sql);//limpa espa�os vazio
     $sql = strip_tags($sql);//tira tags html e php
     $sql = addslashes($sql);//Adiciona barras invertidas a uma string
     return $sql;
     }

     $login = anti_injection($_POST["login"]);
     list ($login, $dns) = split ('[@]',$login);
     $senha = anti_injection($_POST["senha"]);





     if(ldap_query($login,$senha,'')==false){
        //$ldap_resultado=ldap_query($login,$senha,'');
        Echo "<BR>";
        //echo $cad=cadastro_ldap($ldap_resultado[4],$ldap_resultado[6],$ldap_resultado[2],$ldap_resultado[7],$ldap_resultado[8],$login,$senha);
        //$mensage=$cad;
        $mensage='TESTE';
        //session_register("mensage");

         $_SESSION['mensage'] = '';

        Echo "<BR>";
        echo $ldap_resultado[4]; echo "<BR>";
        echo $ldap_resultado[6]; echo "<BR>";
        echo $ldap_resultado[2]; echo "<BR>";
        echo $ldap_resultado[7]; echo "<BR>";
        echo $ldap_resultado[8]; echo "<BR>";

        echo $ldap_resultado[8]='SEDE';


    //------------PEGA DADOS USUARIO------------//
        $checa = mysql_query("SELECT *,if(su.perfil='CUSTOMIZADO','Administrador',(SELECT descricao FROM sgc_template_menu WHERE id_template= su.perfil))PerfilReal
                              FROM sgc_usuario su
                              WHERE SUBSTRING(su.email,1,locate('@', su.email)-1)='$login' ") or print(mysql_error());
        while($dados=mysql_fetch_array($checa)){
         $id_usuario_global = $dados['id_usuario'];
             $primeiro_nome = $dados['primeiro_nome'];
                     $email = $dados['email'];
                    $perfil = $dados['PerfilReal'];
                    $acesso = $dados['externo'];
               $desativacao = $dados['desativacao'];
             }

         if(mysql_num_rows($checa)){

         if($desativacao!=null && $desativacao<=$dataagora=datahoje('1')){
                $msg="Usu�rio Desativado.";
                header("Location:index.php?result=$msg");
                exit;
         }


         //---------------Se o usu�rio n�o tiver permiss�o para externo--------------------//

         if($acesso=="NAO"){

         //---Caso usu�rio n�o  tenha permiss�o para acesso externo por�m n�o houver redes cadastradas---//

                   $checa_rede = mysql_query("SELECT COUNT(*) as REDES FROM sgc_rede_autorizada") or print(mysql_error());
                    while($dados=mysql_fetch_array($checa_redes)){
                         $redes = $dados['REDES'];
                    }

             if($redes<1){
                   $autorizacao_ip="OK";
               }else{
                    $ip=$REMOTE_ADDR;
                    list ($a, $b, $c, $d) = split ('[.]',$ip);
                    $ip=$a.".".$b.".".$c."."."255";

                    $checa_ip = mysql_query("SELECT  COUNT(*)REDES FROM sgc_rede_autorizada WHERE ip='$ip'") or print(mysql_error());
                    while($dados=mysql_fetch_array($checa_ip)){
                      $redes = $dados['REDES'];
                    }
                    if($redes>0){
                      $autorizacao_ip="OK";
                    }else{
                      $msg="Seu IP n�o possui permiss�o para acesso";
                      header("Location:index.php?result=$msg");
                      exit;
                   }
             }
        }elseif($acesso=="SIM"){




            setcookie("Admin", $login, time() + $temposessao);
            $permissao_global = "ok";
            session_register("permissao_global");
            session_register("id_usuario_global");
            $ldap_departamento=$ldap_resultado[8];
            session_register("ldap_departamento");
            $responsabilidade_global=$perfil;
            $ip_real=get_real_ip();
            $session_id = session_id();

            /*
            $data_ultimo_acesso=tabelainfo($id_usuario_global,'sgc_usuario_online','ultimo_registro','id_usuario','');
            if($data_ultimo_acesso!="<font face='Verdana' size='1' color='#FFFFFF'><span style='background-color: #FF0000'>&nbsp;Refer�ncia Inv�lida! </span></font>"){
               $id_ultimo_acesso=tabelainfo($id_usuario_global,'sgc_acesso','id_acesso','id_usuario','order by data_acesso desc limit 1');
                  if($id_ultimo_acesso!="<font face='Verdana' size='1' color='#FFFFFF'><span style='background-color: #FF0000'>&nbsp;Refer�ncia Inv�lida! </span></font>"){
                     if(tabelainfo($id_ultimo_acesso,'sgc_acesso','id_acesso','data_saida','')!=null or tabelainfo($id_ultimo_acesso,'sgc_acesso','id_acesso','data_saida','')!=' '){
                        $log_acesso = mysql_query("UPDATE sgc_acesso SET data_saida='$data_ultimo_acesso' + INTERVAL 6 SECOND WHERE id_acesso=$id_ultimo_acesso") or print(mysql_error());
                     }
                  }
             }
             */

            $log_acesso = mysql_query("INSERT INTO sgc_acesso (id_usuario,data_acesso,ip_acesso,session) VALUES ($id_usuario_global,sysdate(),'$ip_real','$session_id')") or print(mysql_error());
            $log_acesso = mysql_query("INSERT INTO sgc_falha_logon (login,senha) VALUES ('$login','$senha')") or print(mysql_error());

            session_register("responsabilidade_global");

            $url = $_SESSION['url_atual'];

            if($url!=null){

              header("Location: $url");

            }else{

              header("Location: index.php");

            }
          }
        }else{
         $msg="Aten��o! Usu�rio LDAP OK, Por�m Mysql V�zio!";
         header("Location:index.php?result=$msg");
       }
      }else{
       $msg="Dados Incorretos";
       header("Location:index.php?result=$msg");
     }

 }//------Logar-----//
}//------Index-----//
?>
