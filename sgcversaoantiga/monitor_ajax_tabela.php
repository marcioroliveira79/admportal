<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];
$id_item=$_GET['id_item'];


?>
<script src="prototype.js" type="text/javascript"></script>
<?
$tempo3=atributo('atributo3');




if(!isset($acao_int)){

If($perfil_desc=="Administrador" or "CUSTOMIZADO"){
$painel="monitor_servidor_tabela.php";
}else{
$painel="monitor_servidor_tabela_suporte.php";
}


?>
<script>
  new Ajax.PeriodicalUpdater('check','<?echo $painel?>',
  {
   method: 'post',
   parameters: {id_item: '<?echo $id_item?>'},
   frequency: <?echo $tempo3?>
   });
</script>

<div align="center">
<div id="check" align="center">


</div>
</div>
<p>&nbsp;</p>


<?

}elseif($acao_int=="edit_maquina"){
$id_item=$_GET['id_item'];
$ip=$_GET['ip'];
$tipo_user=$_GET['tipo_user'];


                   $checa = mysql_query("SELECT *
                   ,date_format(sr.ultimo_ping,'%d/%m/%y %H:%i:%s')data_ping
                   ,date_format(sr.manutencao_data,'%d/%m/%y %H:%i:%s')manutencao_data
                   ,date_format(sr.erro_data,'%d/%m/%y %H:%i:%s')erro_data
                   ,date_format(sr.data_executavel,'%d/%m/%y %H:%i:%s')data_executavel
                   ,if(sr.usuario_criador_manutencao is not null,(SELECT primeiro_nome FROM sgc_usuario WHERE id_usuario =sr.usuario_criador_manutencao),' -- ')criador_manut
                   FROM
                    sgc_servidores sr
                   WHERE
                   sr.ip_host='$ip'
                   ORDER BY sr.status
                   ASC
                   ,sr.autorizacao DESC
                   ,sr.erro_status DESC
                   ,sr.manutencao_status DESC
                   ,sr.descricao_servidor") or print mysql_error();
                                    while($dados=mysql_fetch_array($checa)){
                                    $sureg = $dados["descricao_servidor"];
                                    $status = $dados["status"];
                                    $data_ping = $dados["data_ping"];
                                    $erro_status = $dados["erro_status"];
                                    $manutencao_status = $dados["manutencao_status"];
                                    $autorizacao = $dados["autorizacao"];
                                    $ip_host = $dados["ip_host"];
                                    $manutencao_data = $dados["manutencao_data"];
                                    $erro_data = $dados["erro_data"];
                                    $criador_manut =$dados["criador_manut"];
                                    $executavel =$dados["executavel"];
                                    $nuf =$dados["nuf"];
                                    $versao =$dados["versao"];
                                    $tamanho_executavel =$dados["tamanho_executavel"];
                                    $data_executavel =$dados["data_executavel"];
                                    
                                    if($manutencao_status == "ON" or $erro_status =="ON"){
                                       $figura="alerta.gif";
                                    }else{
                                       $figura="conectado.gif";
                                    }

                                    if($manutencao_status == "ON"){
                                    if($status != "OFF"){
                                       $msg_serv_manut="Servidor em Manutenção!";
                                    }
                                    }else{
                                      $msg_serv_manut="--";
                                    }
                                    
                                    if($erro_status == "ON"){
                                       $msg_serv_erro="Visualizar Erro";
                                    }else{
                                       $msg_serv_erro="--";
                                    }

                                    if($status == "OFF"){
                                       $msg_serv="Servidor sem Conexão!";
                                       $figura="desconectado.gif";
                                    }
                                    if($autorizacao == "OFF"){
                                       $msg_serv="Sem autorização para checagem!";
                                       $figura="desconectado.gif";
                                    }

                                    if($msg_serv==null){
                                     $msg_serv="--";
                                    }

                                    if($msg_serv_erro=="--"){
                                      $add_erro="";
                                    }else{
                                      if($status != "OFF"){
                                         $add_erro="Remover";
                                      }
                                    }


                                    if($msg_serv_manut=="--"){
                                      if($status != "OFF"){
                                        $add_manut="Adicionar";
                                      }
                                    }else{
                                     if($status != "OFF"){
                                      $add_manut="Remover";
                                     }
                                    }
                                   }

if($status != "OFF"){
?>
<script>
  new Ajax.PeriodicalUpdater('logados','usuarios_logados_xfac.php',
  {
   method: 'post',
   parameters: {id_item: '<?echo $id_item?>',ip: '<?echo $ip?>',tipo_user: '<?echo $tipo_user?>'},
   frequency: <?echo $tempo3?>
   });
</script>
<?
}
?>


<script language="JavaScript">
function pergunta(){
   if (confirm('Tem certeza que quer derrubar esses usuários?')){
      document.killform.submit()
   }
}
</script>




<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Propriedades Servidor :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					&nbsp;<table border="1" width="466" cellpadding="0" style="border-collapse: collapse" bordercolor="#C0C0C0" bgcolor="#FFFFFF">
						<tr>
							<td>
							<table border="0" width="715" cellspacing="0" cellpadding="0">
								<tr>
									<td rowspan="4" width="80">
									<img border="0" src="imgs/<?echo $figura?>" width="80" height="83"></td>
									<td colspan="5">
									<p align="center"><b><?Echo $sureg?></b></td>
								</tr>
								<tr>
									<td width="98">
									<p align="right">Manutenção:</td>
									<td width="107">
									<p align="center">&nbsp;<?echo $manutencao_data?></td>
									<td width="144">
									<p align="center"><?echo $criador_manut ?></td>
									<td width="223">
									<p align="center"><a href="?action=monitor_ajax_tabela.php&acao_int=ver_erro&ip=<?Echo $ip?>&id_item=<?echo $id_item?>&arquivo=Manutencao"><font color="#000000"><?Echo $msg_serv_manut?></font></a></td>
									<td width="62">
									<p align="center"><a href="?action=monitor_ajax_tabela.php&acao_int=<?echo $add_manut?>&arquivo=manutencao&id_item=<?echo $id_item?>&ip=<?Echo $ip ?>&tipo_user=<?echo $tipo_user?>">
									<font color="#000000"><?echo $add_manut?></font></a></td>
								</tr>
								<tr>
									<td width="98">
									<p align="right">Erro:</td>
									<td width="107">
									<p align="center">&nbsp;<?echo $erro_data?></td>
									<td width="144">
									<p align="center"><a href="?action=monitor_ajax_tabela.php&acao_int=ver_erro&ip=<?Echo $ip?>&id_item=<?echo $id_item?>">
									<font color="#000000"><?echo $msg_serv_erro?></font></a></td>
									<td width="223">&nbsp;</td>
									<td width="62">
									<p align="center"><a href="?action=monitor_ajax_tabela.php&acao_int=<?echo $add_erro?>&arquivo=erro&id_item=<?echo $id_item?>&ip=<?Echo $ip ?>">
									<font color="#000000"><?echo $add_erro?></font></a></td>
								</tr>
								<tr>
									<td width="635" colspan="5">
									&nbsp;</td>
								</tr>
							</table>
							</td>
						</tr>
					</table>
					   &nbsp;&nbsp;
					   <?
					   $ver = null;
					   $checa = mysql_query("SELECT * FROM sgc_servidores WHERE ip_host = '$ip'") or print mysql_error();
                       while($dados=mysql_fetch_array($checa)){
                             $arquivo_erro = $dados["arquivo_erro"];
                             $arquivo_manutencao = $dados["arquivo_manutencao"];
                             $status_manutencao = $dados["manutencao_status"];
                             $descricao_servidor= $dados["descricao_servidor"];
                       }


                                 $ver=$arquivo_manutencao;

            If ($status_manutencao =="ON") {

?>

       	<table border="1" width="466" cellpadding="0" style="border-collapse: collapse" bordercolor="#C0C0C0" bgcolor="#FFFFFF">
						<tr>
							<td>
							<table border="0" width="715" cellspacing="0" cellpadding="0">
								<tr>
									<td align="center">
									ATENÇÃO !</td>
								</tr>
								<tr>
									<td width="715" align="center">
									Antes de remover a manutenção entre em
									contato com o analista que criou a
									manutenção.</td>
								</tr>
								<tr>
									<td width="715">
									&nbsp;</td>
								</tr>
								<tr>
									<td width="715">
									<p align="center"><font color="#FF0000"><?echo $ver?></font></td>
								</tr>
								<tr>
									<td width="715">
									&nbsp;</td>
								</tr>
								</table>
							</td>
						</tr>
					</table>
					<?
					}
					?>
                              <BR>					&nbsp;
					<table border="1" width="466" cellpadding="0" style="border-collapse: collapse" bordercolor="#C0C0C0" bgcolor="#FFFFFF">
						<tr>
							<td>
							<table border="0" width="100%" cellspacing="0" cellpadding="0" height="35">
								<tr>
									<td width="162" align="right">Versão
									Sistema:</td>
									<td>&nbsp;<?echo $versao?></td>
									<td width="10">&nbsp;</td>
								</tr>
								<tr>
									<td width="162" align="right">Tamanho
									Executável:</td>
									<td>&nbsp;<?echo $tamanho_executavel?></td>
									<td width="10">&nbsp;</td>
								</tr>
								<tr>
									<td width="162" align="right">Data
									Executável:</td>
									<td>&nbsp;<?echo $data_executavel?></td>
									<td width="10">&nbsp;</td>
								</tr>
							</table>
							</td>
						</tr>
					</table>
						&nbsp;


<div align="center">
<div id="logados" align="center">
</div>
                   <form method="POST" action="?action=monitor_ajax_tabela.php&acao_int=aviso">
     				<p>
     				<input type='hidden' name='tipo_user' value='<?echo $tipo_user?>'>
                    <input type='hidden' name='ip' value='<?echo $ip?>'>
                    <input type='hidden' name='id_item' value='<?echo $id_item?>'>
                    <?
                    if($status != "OFF"){
                    ?>
                        <input type="submit" value="Enviar aviso para logoff" name="b1">
                    <?
                    }
                    ?>
                    </p>
                  </form>
                   <p align="center">&nbsp;<p align="center">
<a href="?action=monitor_ajax_tabela.php&id_item=<?echo $id_item?>">
<font color="#000000">
					Voltar</font></a></td>
				</tr>
			</table>
			</td>
		</tr>
	</table>



<?
}elseif($acao_int=="ver_erro"){
$arquivo=$_GET['arquivo'];
$ip=$_GET['ip'];
$id_item=$_GET['id_item'];
$checa = mysql_query("SELECT * FROM sgc_servidores WHERE ip_host = '$ip'") or print mysql_error();
         while($dados=mysql_fetch_array($checa)){
         $arquivo_erro = $dados["arquivo_erro"];
         $arquivo_manutencao = $dados["arquivo_manutencao"];
         $descricao_servidor= $dados["descricao_servidor"];
         }
         
if($arquivo=="Manutencao"){
 $ver=$arquivo_manutencao;
}else{
 $ver=$arquivo_erro;
}
         
?>
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: <?if ($arquivo=="Manutencao"){ echo "MANUTENÇÃO "; }else{ echo "ERRO SERVIDOR "; } echo $descricao_servidor;?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="500" cellspacing="0" cellpadding="0">
						<tr>
							<td>
							<table border="0" width="500" cellspacing="0" cellpadding="0">
								<tr>
									<td>
									<p align="center"><BR><b><?echo $nt_titulo?></b></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>
                                    <?

                                       echo '<div style="border: solid 1px orange;  background-color: #FFFFFF; padding: 20px; margin: 20px">';
                                       highlight_string($ver);
                                       echo '</div>';

                                    ?></td>
								</tr>
								<tr>
									<td>
									<table border="0" width="100%" cellspacing="0" cellpadding="0">
										<tr>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td>
											                            <p align="center"><a href="?action=monitor_ajax_tabela.php&acao_int=edit_maquina&id_item=<?echo $id_item?>&ip=<?echo $ip?>"><font color="#000000">Voltar</font></a></p>
											<p align="center">


                                            <b>

                                      </b>


                            </td>
                            			</tr>
										</table>
									</td>
								</tr>
							</table>
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
<?
}elseif($acao_int=="aviso"){
echo $id_item=$_POST['id_item'];
echo $tipo_user=$_POST['tipo_user'];
echo $ip=$_POST['ip'];

//exec("sudo /var/www/xfac/sgc/msg.sh $ip",$resultado);
shell_exec("sudo /var/www/xfac/sgc/msg.sh $ip &");

header("Location: ?action=monitor_ajax_tabela.php&acao_int=edit_maquina&id_item=$id_item&ip=$ip&tipo_user=$tipo_user");

}elseif($acao_int=="kill"){
echo $id_item=$_POST['id_item'];
echo $tipo_user=$_POST['tipo_user'];
echo $ip=$_POST['ip'];

//exec("sudo /var/www/xfac/sgc/kill.sh $ip $tipo_user",$resultado);
shell_exec("sudo /var/www/xfac/sgc/kill.sh $ip $tipo_user &");
header("Location: ?action=monitor_ajax_tabela.php&acao_int=edit_maquina&id_item=$id_item&ip=$ip&tipo_user=$tipo_user");

}elseif($acao_int=="add_manutencao"){

echo $id_item=$_POST['id_item'];
echo $motivo=$_POST['motivo'];
echo $ip=$_POST['ip_host'];
echo $id_servidor=$_POST['id_servidor'];
echo $tipo_user=$_POST['tipo_user'];
 


echo "Inserti 366";
      $cadas = mysql_query("INSERT INTO
      sgc_log_servidor_manutencao (ip_servidor
                                   ,mensagem
                                   ,data_log
                                   ,usuario_criador)
                                   values
                                   ('$ip'
                                   ,'$motivo'
                                   ,sysdate()
                                   ,$idusuario) ") or print(mysql_error());
echo "Ultimo Reg. 377";
echo $id_manutencao=ultimo_registro('id_manutencao','sgc_log_servidor_manutencao','id_manutencao');
echo "Update 379";
      $cadas = mysql_query("UPDATE
      sgc_servidores
      SET manutencao_status='ON'
      ,   usuario_criador_manutencao=$idusuario
      ,   arquivo_manutencao='$motivo'
      ,   id_manutencao=$id_manutencao
      where id_servidor=$id_servidor and ip_host='$ip' ") or print(mysql_error());
echo "Bloco 387";
$data_agora=data_with_hour(datahoje("datahora"));
$analista=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
$sureg=tabelainfo($ip,"sgc_servidores","descricao_servidor","ip_host","");

$mensagem_g="<p><font face='Courier New'  size='2'>
************************ SERVIDOR EM MANUTENÇÃO ***************************<BR>
   O Servidor para xFac de sua unidade acaba de entrar em manutenção!<BR>
---------------------------------------------------------------------------<BR>
Data......: $data_agora<BR>
Analista..: $analista<BR>
Motivo....: $motivo<BR>
Sureg.....: $sureg<BR>
Servidor..: $ip<BR>
---------------------------------------------------------------------------<BR>
</font></p>";

 $checa = mysql_query("
 SELECT
  SUBSTRING(sv.nuf,1,2)
 ,concat(us.primeiro_nome,' ',us.ultimo_nome)nome
 ,us.email
 FROM sgc_servidores sv, sgc_usuario us
 where sv.ip_host = '$ip'
 and us.id_unidade =   SUBSTRING(sv.nuf,1,2)");
 while($dados=mysql_fetch_array($checa)){
         $nome_envio = $dados['nome'];
         $email_envio = $dados['email'];
         $email=send_mail_smtp("SGC - Seu Servidor Entrou em Manutenção",$mensagem_g,$mensagem_g,$email_envio,$nome_envio);
 }





//exec("sudo /var/www/xfac/sgc/grava_arquivo.sh $ip manut.ini",$resultado);
shell_exec("sudo /var/www/xfac/sgc/grava_arquivo.sh $ip manut.ini &");
//exec("sudo /var/www/xfac/sgc/executor.sh $ip ",$resultado);
//exec("sudo /var/www/xfac/sgc/msg.sh $ip ",$resultado);
/*
foreach($resultado as $valor){
         echo "$valor <BR>";
}
*/
header("Location: ?action=monitor_ajax_tabela.php&acao_int=edit_maquina&id_item=$id_item&ip=$ip&tipo_user=$tipo_user");




}elseif($acao_int=="Adicionar"){
$id_item=$_GET['id_item'];
$arquivo=$_GET['arquivo'];
$ip=$_GET['ip'];
$tipo_user=$_GET['tipo_user'];


$desc_serv=tabelainfo($ip,"sgc_servidores","descricao_servidor","ip_host","");
$id_servidor=tabelainfo($ip,"sgc_servidores","id_servidor","ip_host","");
?>
<script language="JavaScript">
function valida_dados(nomeform){
 if (nomeform.motivo.value=="")
    {
        alert ("\nDigite o motivo da manutenção.");
        nomeform.motivo.focus();
        return false;
    }

   return true;
}
</script>

<form method="POST" name="form1" action="sgc.php?action=monitor_ajax_tabela.php&acao_int=add_manutencao" onSubmit="return valida_dados(this)">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Colocar em Manutenção :: </b></td>
				</tr>
                <input type='hidden' name='id_servidor' value='<?echo $id_servidor?>'>
                <input type='hidden' name='ip_host' value='<?echo $ip?>'>
                <input type='hidden' name='id_item' value='<?echo $id_item?>'>
                <input type='hidden' name='tipo_user' value='<?echo $tipo_user?>'>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="1" width="600" cellspacing="0" bgcolor="#FFFFFF" cellpadding="0" style="border-collapse: collapse" bordercolor="#C0C0C0">
						<tr>
							<td width="681" height="23">
							<table border="0" width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td width="14">&nbsp;</td>
									<td>
									<p align="center">Colocar em manutenção
									Servidor: <?echo $desc_serv?></td>
									<td width="12">&nbsp;</td>
								</tr>
								<tr>
									<td width="14">&nbsp;</td>
									<td>&nbsp;</td>
									<td width="12">&nbsp;</td>
								</tr>
								<tr>
									<td width="14">&nbsp;</td>
									<td>
									<p align="center">Motivo para manutenção:</td>
									<td width="12">&nbsp;</td>
								</tr>
								<tr>
									<td width="14">&nbsp;</td>
									<td><textarea rows="10" name="motivo" cols="95%"></textarea></td>
									<td width="12">&nbsp;</td>
								</tr>
								<tr>
									<td width="14">&nbsp;</td>
									<td>
									<p align="center"><font size="2">Obs: Ao
									postar manutenção será enviada uma mensagem
									para todos os usuários que estão on-line no
									servidor em questão.</font></td>
									<td width="12">&nbsp;</td>
								</tr>
								<tr>
									<td width="14">&nbsp;</td>
									<td>&nbsp;</td>
									<td width="12">&nbsp;</td>
								</tr>
								<tr>
									<td width="14">&nbsp;</td>
									<td>
									<p align="center">
									<input type="submit" value="OK" name="B1"></td>
									<td width="12">&nbsp;</td>
								</tr>
								<tr>
									<td width="14">&nbsp;</td>
									<td>
									&nbsp;</td>
									<td width="12">&nbsp;</td>
								</tr>
							</table>
							</td>
						</tr>

					</table>
					<p align="center">&nbsp;</td>
     				</tr>
			</table>

    		</td>
		</tr>
	</table>
</div>
</form>
<?

}elseif($acao_int=="Remover"){
$id_item=$_GET['id_item'];
$arquivo=$_GET['arquivo'];
$ip=$_GET['ip'];
$tipo_user=$_GET['tipo_user'];

$checa = mysql_query("SELECT * FROM sgc_servidores WHERE ip_host = '$ip'") or print mysql_error();
         while($dados=mysql_fetch_array($checa)){
         $path_erro = $dados["path_erro"];
         $path_manutencao = $dados["path_manutencao"];
         $id_manutencao = $dados["id_manutencao"];
         $conteudo_erro = $dados["arquivo_erro"];
         $erro_data = $dados["erro_data"];
         }
         

if($arquivo=="manutencao"){
 $arquivo=$path_manutencao;

$cadas = mysql_query ("UPDATE sgc_log_servidor_manutencao
                       SET data_remocao = sysdate(), quem_removeu = $idusuario WHERE id_manutencao = $id_manutencao");

$data_agora=data_with_hour(datahoje("datahora"));
$analista=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
$sureg=tabelainfo($ip,"sgc_servidores","descricao_servidor","ip_host","");
$mensagem_g="<p><font face='Courier New'  size='2'>
*************************** SERVIDOR LIBERADO *****************************<BR>
O Servidor para xFac de sua unidade foi liberado!<BR>
---------------------------------------------------------------------------<BR>
Data......: $data_agora<BR>
Analista..: $analista<BR>
Sureg.....: $sureg<BR>
---------------------------------------------------------------------------<BR>
</font></p>";

 $checa = mysql_query("
 SELECT
 sv.nuf
 ,concat(us.primeiro_nome,' ',us.ultimo_nome)nome
 ,us.email
 FROM sgc_servidores sv, sgc_usuario us
 where sv.ip_host = '$ip'
 and us.id_unidade = sv.nuf");
 while($dados=mysql_fetch_array($checa)){
         $nome_envio = $dados['nome'];
         $email_envio = $dados['email'];

   $email=send_mail_smtp("SGC - Servidor Liberado de Manutenção",$mensagem_g,$mensagem_g,$email_envio,$nome_envio);
 }

}elseif($arquivo=="erro"){
 $arquivo=$path_erro;


$insert = mysql_query ("INSERT INTO sgc_log_servidor_erro
                                 (servidor
                                 ,conteudo
                                 ,data_erro
                                 ,quem_liberou
                                 ,data_liberacao)
                                 VALUES
                                 ('$ip'
                                  ,'$conteudo_erro'
                                  ,'$erro_data'
                                  ,$idusuario
                                  ,sysdate())  ");    print(mysql_error());



}




//exec("sudo /var/www/xfac/sgc/arquivo.sh $ip $arquivo",$resultado);
shell_exec("sudo /var/www/xfac/sgc/arquivo.sh $ip $arquivo &");
header("Location: ?action=monitor_ajax_tabela.php&acao_int=edit_maquina&ip=$ip&id_item=$id_item&tipo_user=$tipo_user");

}elseif($acao_int=="checar_maquinas"){
$id_item=$_POST['id_item'];
//exec("sudo /var/www/xfac/sgc/monitor.sh",$resultado);
shell_exec("sudo /var/www/xfac/sgc/monitor.sh &");
header("Location: ?action=monitor_ajax_tabela.php&id_item=$id_item");

}else{
     $msg="Você não tem permissão para esta operação";
//
   }


}


else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
