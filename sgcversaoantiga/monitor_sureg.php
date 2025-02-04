<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Cadastro de Sureg para Monitor";
$titulo_listar="Sureg Já Cadastradas";
$id_item=$_GET['id_item'];
$arquivo="monitor_sureg.php";
$tabela="sgc_servidores";
$id_chave="id_servidor";





if(!isset($acao_int)){
?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_objeto.value=="")
    {
        alert ("\nDigite a descricao para o Template.");

        document.form1.desc_objeto.style.borderColor="#FF0000";
        document.form1.desc_objeto.style.borderWidth="1px solid";

        nomeform.desc_objeto.focus();
        return false;
    }
    if (nomeform.ajuda.value=="")
    {
        alert ("\nDigite a ajuda deste Template");

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
					<table border="0" width="576" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
						</tr>
						<tr>
							<td width="120">
							<p align="right">Sureg:&nbsp; </td>
							<td width="479" height="23">
							<?
							if(isset($msg)){
                               $borda="border:1px solid #FF0000;";
                            }else{
                               session_unregister('ajuda');
                               session_unregister('desc_objeto');
                               session_unregister('ip_host');
                               session_unregister('manut');
                               session_unregister('error');
                               session_unregister('executavel');
                               session_unregister('nuf');
                            }
                            ?>
							<input size="68" name="desc_objeto" value="<?echo $_SESSION['desc_objeto']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						<tr>
							<td width="120">
							<p align="right">IP Host:&nbsp; </td>
							<td width="479" height="23">
      						<input size="15" name="ip_host" value="<?echo $_SESSION['ip_host']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
    					<tr>
							<td width="120">
							<p align="right">Path Manut.ini:&nbsp; </td>
							<td width="479" height="23">
      						<input size="68" name="manut" value="<?echo $_SESSION['manut']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
                        <tr>
							<td width="120">
							<p align="right">Path Error.log:&nbsp;</td>
							<td width="479" height="23">
      						<input size="68" name="error" value="<?echo $_SESSION['error']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
                        <tr>
							<td width="120">
							<p align="right">NFE:&nbsp;&nbsp; </td>
							<td width="479" height="23">
      						<select size="1" name="nfe">
							<option>ON</option>
							<option>OFF</option>
							</select></td>
						</tr>
                        <tr>
							<td width="120">
							<p align="right">Executavel:&nbsp;</td>
							<td width="479" height="23">
      						<input size="30" name="executavel" value="<?echo $_SESSION['executavel']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
                        <tr>
							<td width="120">
							<p align="right">Nº IBGE UF:&nbsp;</td>
							<td width="479" height="23">
      						<!--webbot bot="Validation" s-data-type="Integer" s-number-separators="x" b-value-required="TRUE" i-minimum-length="2" i-maximum-length="2" --><input size="2" name="nuf" value="<?echo $_SESSION['nuf']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="2"></td>
						</tr>
                        <tr>
							<td width="120">
							<p align="right">Versão:&nbsp;</td>
							<td width="479" height="23">
      						<input size="30" name="versao" value="<?echo $_SESSION['versao']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
						<tr>
							<td width="576" colspan="2" height="23">
							<p align="center">Descrição da Sureg(AJUDA)</td>
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
							<td width="235" height="23"><b>Descrição</b></td>
							<td width="128" height="23"><b>Versão</b></td>
							<td width="38" height="23">&nbsp;</td>
							<td width="44" height="23">&nbsp;</td>
							<td width="18" height="23">&nbsp;</td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
                        <?
                          $checa = mysql_query("select * from $tabela order by descricao_servidor desc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_objeto = $dados["$id_chave"];
                                    $ler_descricao_objeto = $dados['descricao_servidor'];
                                    $ler_ajuda = $dados['ajuda'];
                                    $ler_ip_host = $dados['ip_host'];
                                    $ler_manut = $dados['path_manutencao'];
                                    $ler_erro = $dados['path_erro'];
                                    $ler_executavel = $dados['executavel'];
                                    $ler_nuf = $dados['nuf'];
                                    $ler_versao = $dados['versao'];


                        ?>

                        <tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="235" height="23"><?echo $ler_descricao_objeto?></td>
							<td width="128" height="23"><?echo $ler_versao?></td>
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
                            <span><strong><?echo $ler_descricao_objeto ?></strong> - <?echo $ler_ip_host?>
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
     $ip_host=$_POST['ip_host'];
     $manut=$_POST['manut'];
     $error=$_POST['error'];
     $executavel=$_POST['executavel'];
     $nuf=$_POST['nuf'];
     $versao=$_POST['versao'];
     $nfe=$_POST['nfe'];
     $data_inicio_desb=$_POST['data_inicio_desb'];
     $data_fim_desb=$_POST['data_fim_desb'];
     $todos_servidores=$_POST['todos_servidores'];
     
     $motivo_manu=$_POST['motivo_manut'];
     $data_inicio_manu=$_POST['data_inicio_manu'];
     $data_fim_manu=$_POST['data_fim_manu'];
     $todos_servidores_manut=$_POST['todos_servidores_manut'];


     session_unregister('ajuda');
     session_unregister('desc_objeto');
     session_unregister('ip_host');
     session_unregister('manut');
     session_unregister('error');
     session_unregister('executavel');
     session_unregister('nuf');
     session_unregister('versao');
     session_unregister('nfe');
     session_unregister('data_inicio_desb');
     session_unregister('data_fim_desb');


     $permissao_item=acesso($idusuario,$id_item);


   if($permissao_item=="OK"){

       $existe=integridade("$ip_host","$tabela","ip_host","ip_host","and $id_chave !=$id_objeto");

    if($existe=="Existe"){
     $msg="Já existe uma Sureg com esse IP";
     session_register("desc_objeto");
     session_register("ajuda");
     session_register('ip_host');
     session_register('manut');
     session_register('error');
     session_register('executavel');
     session_register('nuf');
     session_register('versao');
     session_register('nfe');
     session_register('data_inicio_desb');
     session_register('data_fim_desb');




     header("Location: ?action=$aquivo&acao_int=editar&id_objeto=$id_objeto&id_item=$id_item&msg=$msg&desc_objeto=$desc_objeto&ajuda=$ajuda&ip_host=$ip_host&manut=$manut&error=$error");

    }else{



       $data_inicio_desb=databd_ext($data_inicio_desb,"data_hora_minuto");
       $data_fim_desb=databd_ext($data_fim_desb,"data_hora_minuto");

       If($data_inicio_desb == null || $data_inicio_desb =="" || $data_inicio_desb =="0000-00-00 00:00:00"){
          $sql_adendo=" ,data_inicio_desbloqueio_auto = null";
       }else{
          $sql_adendo=",data_inicio_desbloqueio_auto ='$data_inicio_desb'";
       }
       
       If($data_fim_desb == null || $data_fim_desb =="" || $data_fim_desb =="0000-00-00 00:00:00"){
          $sql_adendo.=" ,data_fim_desbloqueio_auto = null";
       }else{
          $sql_adendo.=" ,data_fim_desbloqueio_auto ='$data_fim_desb'";
       }

        If($data_inicio_desb != null || $data_inicio_desb !="" || $data_inicio_desb !="0000-00-00 00:00:00"){

       if($todos_servidores=="ON"){
          $cadas = mysql_query("UPDATE $tabela SET
                                  data_alteracao=sysdate()
                                 ,quem_alterou=$idusuario
                                 $sql_adendo ")or print(mysql_error());

       }else{

       $cadas = mysql_query("UPDATE $tabela
                             SET descricao_servidor='$desc_objeto'
                                 ,ip_host='$ip_host'
                                 ,path_manutencao='$manut'
                                 ,path_erro='$error'
                                 ,ajuda='$ajuda'
                                 ,executavel='$executavel'
                                 ,nuf='$nuf'
                                 ,versao='$versao'
                                 ,nfe='$nfe'
                                 ,data_alteracao=sysdate()
                                 ,quem_alterou=$idusuario
                                 $sql_adendo
                                 where $id_chave='$id_objeto'") or print(mysql_error());
       }
       }



       $data_inicio_manu=databd_ext($data_inicio_manu,"data_hora_minuto");
       $data_fim_manu=databd_ext($data_fim_manu,"data_hora_minuto");



       If($data_inicio_manu == null || $data_inicio_manu =="" || $data_inicio_manu =="0000-00-00 00:00:00"){
          $sql_adendo_manu=" ,data_inicio_manut = null, motivo_agend_manu='$motivo_manu'";
       }else{
          $sql_adendo_manu=",data_inicio_manut ='$data_inicio_manu', motivo_agend_manu='$motivo_manu'";
       }

       If($data_fim_manu == null || $data_fim_manu =="" || $data_fim_manu =="0000-00-00 00:00:00"){
          $sql_adendo_manu.=" ,data_fim_manut = null, motivo_agend_manu='$motivo_manu'";
       }else{
          $sql_adendo_manu.=",data_fim_manut ='$data_fim_manu', motivo_agend_manu='$motivo_manu'";
       }
       
       If(strlen($data_inicio_manu) >= 16 && $data_inicio_manu!='0000-00-00 00:00:00'){
       

       $analista=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
       $sureg=tabelainfo($ip_host,"sgc_servidores","descricao_servidor","ip_host","");

       $mensagem_g="<p><font face='Courier New'  size='2'>
       ************************ AVISO de MANUTENÇÃO ***************************<BR>
          O Servidor xFac para sua unidade entrará em manutenção agendada<BR>
          ---------------------------------------------------------------------------<BR>
          Data parada..: $data_inicio_manu à $data_fim_manu<BR>
          Analista.....: $analista<BR>
          Motivo.......: $motivo_manu<BR>
          Sureg........: $sureg<BR>
          Servidor.....: $ip_host<BR>
          ---------------------------------------------------------------------------<BR>
       </font></p>";

        $checa_st1 = mysql_query("
         SELECT
           SUBSTRING(sv.nuf,1,2)
            ,concat(us.primeiro_nome,' ',us.ultimo_nome)nome
             ,us.email
              FROM sgc_servidores sv, sgc_usuario us
               where sv.ip_host = '$ip_host'
                and us.id_unidade =   SUBSTRING(sv.nuf,1,2)");
        while($dados_st1=mysql_fetch_array($checa_st1)){
         $nome_envio = $dados_st1['nome'];
         $email_envio = $dados_st1['email'];
         $email=send_mail_smtp("SGC - Seu Servidor Entrou em Manutenção",$mensagem_g,$mensagem_g,$email_envio,$nome_envio);
         }


       if($todos_servidores_manut=="ON"){
          $cadas = mysql_query("UPDATE $tabela SET
                                  data_alteracao=sysdate()
                                 ,quem_alterou=$idusuario
                                 $sql_adendo_manu ")or print(mysql_error());

       }else{

        $cadas = mysql_query("UPDATE $tabela
                             SET descricao_servidor='$desc_objeto'
                                 ,ip_host='$ip_host'
                                 ,path_manutencao='$manut'
                                 ,path_erro='$error'
                                 ,ajuda='$ajuda'
                                 ,executavel='$executavel'
                                 ,nuf='$nuf'
                                 ,versao='$versao'
                                 ,nfe='$nfe'
                                 ,data_alteracao=sysdate()
                                 ,quem_alterou=$idusuario
                                 $sql_adendo_manu
                                 where $id_chave='$id_objeto'") or print(mysql_error());
       }

       }


       session_unregister('ajuda');
       session_unregister('desc_objeto');
       session_unregister('ip_host');
       session_unregister('manut');
       session_unregister('error');
       session_unregister('executavel');
       session_unregister('nuf');
       session_unregister('versao');
       session_unregister('nfe');
       session_unregister('data_inicio_desb');
       session_unregister('data_fim_desb');
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
                                    $ler_descricao_objeto = $dados['descricao_servidor'];
                                    $ler_ip_host = $dados['ip_host'];
                                    $ler_manut = $dados['path_manutencao'];
                                    $ler_erro = $dados['path_erro'];
                                    $ler_ajuda = $dados['ajuda'];
                                    $ler_executavel = $dados['executavel'];
                                    $ler_nuf = $dados['nuf'];
                                    $ler_versao = $dados['versao'];
                                    $ler_nfe = $dados['nfe'];
                                    $ler_data_inicio_desbloqueio_auto = $dados['data_inicio_desbloqueio_auto'];
                                    $ler_data_fim_desbloqueio_auto = $dados['data_fim_desbloqueio_auto'];
                                    $ler_data_inicio_manut = $dados['data_inicio_manut'];
                                    $ler_data_fim_manut = $dados['data_fim_manut'];
                                    $ler_motivo_manut = $dados['motivo_agend_manu'];

                                    $ler_data_inicio_desbloqueio_auto = data_with_hour($ler_data_inicio_desbloqueio_auto);
                                    $ler_data_fim_desbloqueio_auto = data_with_hour($ler_data_fim_desbloqueio_auto);

                                    $ler_data_inicio_manut = data_with_hour($ler_data_inicio_manut);
                                    $ler_data_fim_manut = data_with_hour($ler_data_fim_manut);
                                    
                                    
                                    

}




 ?>
 
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
function valida_dados (nomeform)
{
    if (nomeform.desc_objeto.value=="")
    {
        alert ("\nDigite a descricao para o Servidor.");

        document.form1.desc_objeto.style.borderColor="#FF0000";
        document.form1.desc_objeto.style.borderWidth="1px solid";

        nomeform.desc_objeto.focus();
        return false;
    }
    if (nomeform.ip_host.value=="")
    {
        alert ("\nDigite o ip do servidor");

        document.form1.ajuda.style.borderColor="#FF0000";
        document.form1.ajuda.style.borderWidth="1px solid";

        nomeform.ip_host.focus();
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
					<table border="0" width="559" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
                            <input type='hidden' name='id_objeto' value='<?echo $id_objeto?>'></td>
						</tr>
						<tr>
							<td width="107">
							<p align="right">Sureg:&nbsp; </td>
							<td width="452" height="23">
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
                               $valor02=$ler_ip_host;
                               $valor03=$ler_erro;
                               $valor04=$ler_manut;
                               $valor05=$ler_executavel;
                               $valor06=$ler_nuf;
                               $valor07=$ler_versao;


                            }else{
                               $valor0=$_SESSION['desc_objeto'];
                               $valor01=$_SESSION['ajuda'];
                               $valor02=$_SESSION['ip_host'];
                               $valor03=$_SESSION['error'];
                               $valor04=$_SESSION['manut'];
                               $valor05=$_SESSION['executavel'];
                               $valor06=$_SESSION['nuf'];
                               $valor07=$_SESSION['versao'];



                            }

                            ?>
							<input size="68" name="desc_objeto" value="<?echo $valor0?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>

                        <tr>
							<td width="107">
							<p align="right">IP Host:&nbsp; </td>
							<td width="452" height="23">
         					<input size="15" name="ip_host" value="<?echo $valor02?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>

						 <tr>
							<td width="107">
							<p align="right">Manut.ini:&nbsp; </td>
							<td width="452" height="23">
         					<input size="68" name="manut" value="<?echo $valor04?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>

						 <tr>
							<td width="107">
							<p align="right">Error.log:&nbsp; </td>
							<td width="452" height="23">
         					<input size="68" name="error" value="<?echo $valor03?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>

						 <tr>
							<td width="107">
							<p align="right">NFE:</td>
							<td width="452" height="23">
         					<select size="1" name="nfe">

         					<?
                             if($ler_nfe==null){
                            ?>
                            <option>ON</option>
                            <option>OFF</option>
                            <?
                            }else{
                               if($ler_nfe=="ON"){

                               ?>
                               <option>ON</option>
                               <option>OFF</option>
                               <?

                               }else{
                               ?>
                               <option>OFF</option>
                               <option>ON</option>
                               <?

                               }

                            }
                            ?>
                            </select></td>
						</tr>

						 <tr>
							<td width="107">
							<p align="right">Executavel:&nbsp;</td>
							<td width="452" height="23">
         					<input size="30" name="executavel" value="<?echo $valor05?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>

						 <tr>
							<td width="107">
							<p align="right">Nº IBGE UF:&nbsp; </td>
							<td width="452" height="23">
         					<!--webbot bot="Validation" s-data-type="Integer" s-number-separators="x" b-value-required="TRUE" i-minimum-length="2" i-maximum-length="2" --><input size="2" name="nuf" value="<?echo $valor06?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="2"></td>
						</tr>

						 <tr>
							<td width="107">
							<p align="right">Versão:</td>
							<td width="452" height="23">
         					<input size="30" name="versao" value="<?echo $valor07?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>

						 <tr>
							<td width="107">
							&nbsp;</td>
							<td width="452" height="23">
         					&nbsp;</td>
						</tr>

						 <tr>
							<td width="107">
							<p align="right">Des Bl. Auto</td>
							<td width="452" height="23">
         					&nbsp;<!--webbot bot="Validation" b-value-required="TRUE" i-minimum-length="16" i-maximum-length="16" --><input size="16" name="data_inicio_desb" value="<?echo $ler_data_inicio_desbloqueio_auto?>"  onKeyUp="mascaraTexto(event,'99/99/9999 99:99')" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="16">
							á&nbsp;&nbsp;
							<!--webbot bot="Validation" b-value-required="TRUE" i-minimum-length="16" i-maximum-length="16" -->
         					<input size="16" name="data_fim_desb" value="<?echo $ler_data_fim_desbloqueio_auto?>" onKeyUp="mascaraTexto(event,'99/99/9999 99:99')" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="16">
							Setar para todos servidores
							<input type="checkbox" name="todos_servidores" value="ON"></td>
						</tr>

						 <tr>
							<td width="107">
							&nbsp;</td>
							<td width="452" height="23">
         					&nbsp;</td>
						</tr>

						 <tr>
							<td width="559" colspan="2" align="center">
							<fieldset style="padding: 2">
							<legend align="center">Agendar Manutenção</legend>
							<table border="0" width="97%" cellspacing="0" cellpadding="0">
								<tr>
									<td width="27%">
									<p align="right">Data Manutenção:&nbsp;&nbsp;
									</td>
									<td width="71%">
        							<input size="16" name="data_inicio_manu" value="<?echo $ler_data_inicio_manut?>"  onKeyUp="mascaraTexto(event,'99/99/9999 99:99')" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="16">
							á&nbsp;&nbsp;
           					<input size="16" name="data_fim_manu" value="<?echo $ler_data_fim_manut?>" onKeyUp="mascaraTexto(event,'99/99/9999 99:99')" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="16">&nbsp;
									Todos servidores
							<input type="checkbox" name="todos_servidores_manut" value="ON"></td>
								</tr>
								<tr>
									<td colspan="2" align="center">Motivo:&nbsp;&nbsp;
									</td>
								</tr>
								<tr>
									<td colspan="2">
									<p align="center">
									<textarea rows="6" name="motivo_manut" cols="60"><?echo $ler_motivo_manut?></textarea></td>
								</tr>
								<tr>
									<td colspan="2">&nbsp;</td>
								</tr>
							</table>
							</fieldset></td>
						</tr>

						 <tr>
							<td width="107">
							&nbsp;</td>
							<td width="452" height="23">
         					&nbsp;</td>
						</tr>

						 <tr>
							<td width="107">
							<p align="right">&nbsp; </td>
							<td width="452" height="23">
         					&nbsp;</td>
						</tr>

						<tr>
							<td width="559" colspan="2" height="23">
							<p align="center">Descrição do Menu(AJUDA)</td>
						</tr>
						<tr>
							<td height="23" width="107">
							<p align="right">&nbsp; </td>
							<td height="23" width="452">
							<textarea rows="6" name="ajuda" cols="67" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #FFFFFF;"><?echo $valor01?></textarea></td>
						</tr>
						<tr>
							<td colspan="2">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">
							<input type="submit" value="Editar Sureg" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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

       $id_item=$_POST['id_item'];

       $permissao_item=acesso($idusuario,$id_item);
       if($permissao_item=="OK"){

       $desc_objeto=$_POST['desc_objeto'];
       $desc_objeto=ltrim("$desc_objeto");

       $ip_host=$_POST['ip_host'];
       $manut=$_POST['manut'];
       $error=$_POST['error'];
       $executavel=$_POST['executavel'];
       $nuf=$_POST['nuf'];
       $versao=$_POST['versao'];
       $nfe=$_POST['nfe'];

       session_register('ip_host');
       session_register('manut');
       session_register('error');
       session_register('desc_objeto');
       session_register('executavel');
       session_register('nuf');
       session_register('versao');

       $ajuda=$_POST['ajuda'];
       session_register('ajuda');

       $integridade=integridade($ip_host,$tabela,"ip_host","ip_host");



    if($integridade=="Existe"){

      header("Location: ?action=$arquivo&id_item=$id_item&msg=Já existe uma Sureg com este IP");

    }else{

      $cadas = mysql_query("INSERT INTO $tabela (descricao_servidor, ip_host, path_manutencao, path_erro, executavel, nuf, versao, data_criacao, quem_criou, ajuda, nfe) VALUES ('$desc_objeto','$ip_host','$manut','$error','$executavel','$nuf','$versao',sysdate(),$idusuario,'$ajuda','$nfe')") or print(mysql_error());
      session_unregister('ajuda');
      session_unregister('desc_objeto');
      session_unregister('ip_host');
      session_unregister('manut');
      session_unregister('error');
      session_unregister('executavel');
      session_unregister('nuf');
      session_unregister('versao');


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
