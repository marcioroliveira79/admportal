<?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include("conf/conecta.php");
include("conf/funcs.php");

$titulo="Monitor Servidores";
$titulo_listar="Sureg Já Cadastradas";
$id_item=$_POST['id_item'];
$arquivo="monitor_servidor_tabela.php";
$tabela="sgc_servidores";
$id_chave="id_servidor";

$hora_agora = datahoje("hora");

if($hora_agora > "08:00" && $hora_agora < "19:00" ){
   $time = "18000";
}else{
   $time = "36000";
}

function acerta($var){

$checa = mysql_query("SELECT * FROM sgc_servidores
WHERE data_inicio_desbloqueio_auto != '0000-00-00 00:00:00' and data_fim_desbloqueio_auto != '0000-00-00 00:00:00'
or data_inicio_desbloqueio_auto <= sysdate() and data_fim_desbloqueio_auto >= sysdate()
or data_inicio_desbloqueio_auto <= sysdate() and data_fim_desbloqueio_auto is null
") or print mysql_error();
while($dados=mysql_fetch_array($checa)){
  $id_servidor = $dados["id_servidor"];
  $ip_host = $dados["ip_host"];
  $path_erro = $dados["path_erro"];
  $path_manutencao = $dados["path_manutencao"];
  $id_manutencao = $dados["id_manutencao"];
  $conteudo_erro = $dados["arquivo_erro"];
  $erro_data = $dados["erro_data"];
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


IF($status=="ON" || $autorizacao == "ON" && $manutencao_status == "OFF" || $erro_status =="ON" ){

  IF($erro_status == "ON"){

   $data_agora=data_with_hour(datahoje("datahora"));
   $analista=tabelainfo(42,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
   $sureg=tabelainfo($ip_host,"sgc_servidores","descricao_servidor","ip_host","");
      $data_agora=datahoje('datahora');
      $mensagem_g="<p><font face='Courier New'  size='2'>
                                       ************************ ERRO REMOVIDO AUTOMATICAMENTE*********************<BR>
                                                        O Servidor $sureg teve seu erro removido<BR>
                                       ---------------------------------------------------------------------------<BR>
                                       Data Operação.............: $data_agora<BR>
                                       ---------------------------------------------------------------------------<BR>
                                       </font></p>";
$emails_gerencia = atributo('atributo24');
$amails_gerencia = split ('[;]',$emails_gerencia);

foreach($amails_gerencia as $valor){
  $nome_envio=tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","email","");
  $email=send_mail_smtp("SISGAT - Servidor $sureg com ERRO",$mensagem_g,$mensagem_g,$valor,$nome_envio);
}
   $arquivo=$path_erro;
   $insert = mysql_query ("INSERT INTO sgc_log_servidor_erro
                                 (servidor
                                 ,conteudo
                                 ,data_erro
                                 ,quem_liberou
                                 ,data_liberacao)
                                 VALUES
                                 ('$ip_host'
                                  ,'$conteudo_erro'
                                  ,'$erro_data'
                                  ,42
                                  ,sysdate())  ");


//   exec("sudo /var/www/xfac/sgc/arquivo.sh $ip_host $arquivo",$resultado);

   shell_exec("sudo /var/www/xfac/sgc/arquivo.sh $ip_host $arquivo &");

  }

}


}
return null;
}

$acerta = acerta("acerta");

?>



<form method="POST" name="form1" action="sgc.php?action=monitor_ajax_tabela.php&acao_int=checar_maquinas" onSubmit="return valida_dados(this)">
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
					<table border="1" width="703" cellspacing="0" bgcolor="#FFFFFF">
     	<tr>
							<td width="71" height="23">&nbsp;</td>
							<td width="116" height="23">
							<p align="center"><b>Ping</b></td>
							<td width="35" height="23">
							<p align="center">
							<b>Nfe</b></td>
							<td width="73" height="23">
							<p align="center"><b>Servidor</b></td>
    						<td width="29" height="10">
							<p align="center"><b>BD</b></td>
							<td height="23" width="162">
							<p align="center"><b>Tam/Data</b></td>
							<td height="23" width="252" colspan="3">&nbsp;</td>
						</tr>
						 <?
                   $checa = mysql_query("
                   SELECT *,nfe,date_format(ultimo_ping,'%d/%m/%y %H:%i:%s')data_ping
                   ,concat(tamanho_executavel,' ',date_format(data_executavel,'%d/%m/%y %H:%i:%s'))tamanho_data
                   ,if(usuario_criador_manutencao is not null,(SELECT primeiro_nome FROM sgc_usuario
                   WHERE id_usuario = usuario_criador_manutencao),' -- ')criador_manut
                   FROM sgc_servidores
                   ORDER BY status ASC
                   , autorizacao DESC
                   , erro_status DESC
                   , manutencao_status DESC
                   , descricao_servidor") or print mysql_error();
                                    while($dados=mysql_fetch_array($checa)){
                                    $sureg = $dados["descricao_servidor"];
                                    $status = $dados["status"];
                                    $falha_conexao_data = $dados["falha_conexao_data"];
                                    $data_ping = $dados["data_ping"];
                                    $erro_status = $dados["erro_status"];
                                    $erro_data = $dados["erro_data"];
                                    $arquivo_erro = $dados["arquivo_erro"];
                                    $manutencao_status = $dados["manutencao_status"];
                                    $manutencao_data = $dados["manutencao_data"];
                                    $criador_manut = $dados["criador_manut"];
                                    $arquivo_manutencao = $dados["arquivo_manutencao"];
                                    $autorizacao = $dados["autorizacao"];
                                    $ip_host = $dados["ip_host"];
                                    $tamanho_data = $dados["tamanho_data"];
                                    $nfe = $dados["nfe"];

                                    if($nfe=="OFF" || $nfe==""){
                                     $nfe="";
                                    }


                                    /*
                                    If($ip_host == "10.1.1.250"){
                                       $status = "OFF";
                                       $falha_conexao_data = "2009-11-06 09:03:00";
                                    }
                                    */

                                    if($manutencao_status == "ON" or $erro_status =="ON"){
                                       $figura="ball_y.gif";
                                    }else{
                                       $figura="ball_g.gif";
                                    }
//----------------------------------------Manutencao
                                    if($manutencao_status == "ON"){

                                       $msg_serv="Servidor em Manutenção!";

                                       if($criador_manut=="--"){
                                        $criador_manut="Modo Console - Não há como identificar";
                                       }

                                       $checa_st = mysql_query("SELECT
                                        date_format(data_log,'%m/%y') MES
                                        ,count(id_manutencao) QUANTIA_M
                                        FROM sgc_log_servidor_manutencao
                                        WHERE ip_servidor = '$ip_host'
                                        GROUP BY date_format(data_log,'%m')
                                        order by mes desc
                                        ") or print mysql_error();
                                       while($dados_st=mysql_fetch_array($checa_st)){
                                         $mes_array .= $dados_st["MES"]." - ".$dados_st["QUANTIA_M"] ."<BR>";
                                       }

                                       $checa_st = mysql_query("SELECT
                                        ip_servidor
                                        ,data_log
                                        ,if(data_remocao is not null, data_remocao,'CONSOLE') data_remocao_M
                                        ,if(timediff(data_remocao,data_log) is not null, timediff(data_remocao,data_log), 'CONSOLE') TIME_OUT
                                        FROM sgc_log_servidor_manutencao
                                        where ip_servidor = '$ip_host'
                                        order by data_log desc
                                        ") or print mysql_error();
                                       while($dados_st=mysql_fetch_array($checa_st)){
                                         $dias_array .= $dados_st["data_log"]." - ".$dados_st["data_remocao_M"]." - ".$dados_st["TIME_OUT"]."<BR>";
                                       }

                                       $mensagem_g="<p><font face='Courier New'  size='2'>
                                       ************************** SERVIDOR EM MANUTENÇÃO *************************<BR>
                                                        O Servidor $sureg entrou em manutenção!<BR>
                                       ---------------------------------------------------------------------------<BR>
                                       Data Manutenção.............: $manutencao_data<BR>
                                       Quem locou em manutenção....: $criador_manut<BR>
                                       ---------------------------------------------------------------------------<BR>
                                       Motivo manutenção:<BR>$arquivo_manutencao<BR><BR>

                                       ---------------------------------------------------------------------------<BR>
                                       Informações sobre este servidor:<BR><BR>

                                       Quantidade de manutenção mês:<BR>
                                       $mes_array<BR>
                                       Data ocorrência manutenção:<BR>
                                       $dias_array
                                       ---------------------------------------------------------------------------<BR>
                                       </font></p>";
                                       $mes_array=null;
                                       $dias_array=null;





                                    $count=0;
                                    $checa_st = mysql_query("
                                    select
                                     data_parada
                                    ,if(time_to_sec(timediff(sysdate(),data_envio)) is null,0,time_to_sec(timediff(sysdate(),data_envio))) tempo_envio
                                    from sgc_log_aviso_email where data_parada = '$manutencao_data'
                                    and tipo_parada = SUBSTRING('$msg_serv',13,1)
                                    and ip='$ip_host'
                                    ") or print mysql_error();
                                    while($dados_st=mysql_fetch_array($checa_st)){
                                       $data_parada = $dados_st["data_parada"];
                                       $tempo_envio = $dados_st["tempo_envio"];
                                       $count++;
                                    }

                                    if($count==0){
                                    if($data_parada!="0000-00-00 00:00:00"){
                                       $insert = mysql_query("INSERT INTO sgc_log_aviso_email (data_envio,tipo_parada,data_parada,ip) values (sysdate(),'M','$manutencao_data','$ip_host') ") or print mysql_error();
                                    }

                                     $emails_gerencia = atributo('atributo24');
                                     $amails_gerencia = split ('[;]',$emails_gerencia);

                                      foreach($amails_gerencia as $valor){
                                      $nome_envio=tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","email","");
                                          $email=send_mail_smtp("SISGAT - Servidor $sureg em Manutenção",$mensagem_g,$mensagem_g,$valor,$nome_envio);
                                      }


                                    }else{

                                       if($tempo_envio > $time){
                                       $update = mysql_query("UPDATE sgc_log_aviso_email SET data_envio=sysdate() WHERE data_parada='$manutencao_data' and ip='$ip_host'") or print mysql_error();
                                        $emails_gerencia = atributo('atributo24');
                                        $amails_gerencia = split ('[;]',$emails_gerencia);


                                         foreach($amails_gerencia as $valor){
                                           $nome_envio=tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","email","");
                                            $email=send_mail_smtp("SISGAT - Servidor $sureg continua em Manutenção",$mensagem_g,$mensagem_g,$valor,$nome_envio);
                                          }
                                        }
                                      }
                                    }

//-----------------------------------------------Erro

                                    if($erro_status == "ON"){

                                    $msg_serv="Servidor com ERRO!";

                                    $count=0;
                                    $checa_st = mysql_query("
                                    select
                                     data_parada
                                    ,if(time_to_sec(timediff(sysdate(),data_envio)) is null,0,time_to_sec(timediff(sysdate(),data_envio))) tempo_envio
                                    from sgc_log_aviso_email where data_parada = '$erro_data'
                                    and tipo_parada = SUBSTRING('$msg_serv',14,1)
                                    and ip='$ip_host'
                                    ") or print mysql_error();
                                    while($dados_st=mysql_fetch_array($checa_st)){
                                       $data_parada = $dados_st["data_parada"];
                                       $tempo_envio = $dados_st["tempo_envio"];
                                       $count++;
                                    }



                                    $checa_st = mysql_query("SELECT
                                      date_format(data_erro,'%m/%y') MES
                                      ,count(id_log_erro) QUANTIA_M
                                      FROM sgc_log_servidor_erro
                                      WHERE servidor = '$ip_host'
                                      GROUP BY date_format(data_erro,'%m')
                                      order by mes desc
                                        ") or print mysql_error();
                                       while($dados_st=mysql_fetch_array($checa_st)){
                                         $mes_array .= $dados_st["MES"]." - ".$dados_st["QUANTIA_M"] ."<BR>";
                                       }


                                       $checa_st = mysql_query("SELECT
                                        servidor
                                        ,data_erro
                                        ,if(data_liberacao is not null, data_liberacao,'CONSOLE') data_remocao_M
                                        ,if(timediff(data_liberacao,data_erro) is not null, timediff(data_liberacao,data_erro), 'CONSOLE') TIME_OUT
                                        FROM sgc_log_servidor_erro
                                        where servidor = '$ip_host'
                                        order by data_erro desc
                                        ") or print mysql_error();
                                       while($dados_st=mysql_fetch_array($checa_st)){
                                         $dias_array .= $dados_st["data_erro"]." - ".$dados_st["data_remocao_M"]." - ".$dados_st["TIME_OUT"]."<BR>";
                                       }



                                       $mensagem_g="<p><font face='Courier New'  size='2'>
                                       ************************** SERVIDOR COM ERRO ******************************<BR>
                                                        O Servidor $sureg esta com Erro!<BR>
                                       ---------------------------------------------------------------------------<BR>
                                       Data erro...................: $erro_data<BR>
                                       ---------------------------------------------------------------------------<BR>
                                       Informações sobre este servidor:<BR><BR>

                                       Quantidade de erros mês:<BR>
                                       $mes_array<BR>
                                       Data ocorrência:<BR>
                                       $dias_array
                                       ---------------------------------------------------------------------------<BR>
                                       </font></p>";
                                       $mes_array=null;
                                       $dias_array=null;



                                    if($count==0){
                                     if($data_parada!="0000-00-00 00:00:00"){
                                        $insert = mysql_query("INSERT INTO sgc_log_aviso_email (data_envio,tipo_parada,data_parada,ip) values (sysdate(),'E','$erro_data','$ip_host') ") or print mysql_error();
                                     }
                                      $emails_gerencia = atributo('atributo24');
                                      $amails_gerencia = split ('[;]',$emails_gerencia);

                                      foreach($amails_gerencia as $valor){
                                      $nome_envio=tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","email","");
                                          $email=send_mail_smtp("SISGAT - Servidor $sureg com ERRO",$mensagem_g,$mensagem_g,$valor,$nome_envio);
                                      }



                                    }else{

                                     if($tempo_envio > $time){
                                     $update = mysql_query("UPDATE sgc_log_aviso_email SET data_envio=sysdate() WHERE data_parada='$erro_data' and ip='$ip_host'") or print mysql_error();
                                        $emails_gerencia = atributo('atributo24');
                                        $amails_gerencia = split ('[;]',$emails_gerencia);


                                         foreach($amails_gerencia as $valor){
                                           $nome_envio=tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","email","");
                                            $email=send_mail_smtp("SISGAT - Servidor $sureg continua com ERRO",$mensagem_g,$mensagem_g,$valor,$nome_envio);
                                         }
                                       }
                                    }






                                    }

//-----------------------------------------------Conexao

                                    if($status == "OFF"){
                                       $msg_serv="SERVIDOR SEM CONEXÃO";
                                       $figura="ball_r.gif";

                                    $count=0;
                                    $checa_st = mysql_query("
                                    select
                                     data_parada
                                    ,time_to_sec(timediff(sysdate(),data_envio)) tempo_envio
                                    from sgc_log_aviso_email where data_parada = '$falha_conexao_data'
                                    and tipo_parada = SUBSTRING('$msg_serv',14,1)
                                    and ip='$ip_host'
                                    ") or print mysql_error();
                                    while($dados_st=mysql_fetch_array($checa_st)){
                                       $data_parada = $dados_st["data_parada"];
                                       $tempo_envio = $dados_st["tempo_envio"];
                                       $count++;
                                    }

                                       $mensagem_g="<p><font face='Courier New'  size='2'>
                                       ************************ SERVIDOR SEM CONEXÃO *****************************<BR>
                                                        O Servidor $sureg esta sem conexao!<BR>
                                       ---------------------------------------------------------------------------<BR>
                                       Data falha...................: $falha_conexao_data<BR>
                                       ---------------------------------------------------------------------------<BR>

                                       </font></p>";

                                    if($count==0){

                                      if($data_parada !="0000-00-00 00:00:00" || $data_parada=="" ){
                                        $insert = mysql_query("INSERT INTO sgc_log_aviso_email (data_envio,tipo_parada,data_parada,ip) values (sysdate(),'C','$falha_conexao_data','$ip_host') ") or print mysql_error();
                                      }

                                      $emails_gerencia = atributo('atributo24');
                                      $amails_gerencia = split ('[;]',$emails_gerencia);

                                      foreach($amails_gerencia as $valor){
                                      $nome_envio=tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","email","");
                                          $email=send_mail_smtp("SISGAT - Servidor $sureg sem conexão",$mensagem_g,$mensagem_g,$valor,$nome_envio);
                                      }

                                    }else{

                                      if($tempo_envio > $time ){

                                         echo "$ip_host, $falha_conexao_data";
                                         $update = mysql_query("UPDATE sgc_log_aviso_email SET data_envio=sysdate() WHERE data_parada='$falha_conexao_data' and ip='$ip_host'") or print mysql_error();
                                         $emails_gerencia = atributo('atributo24');
                                         $amails_gerencia = split ('[;]',$emails_gerencia);


                                         foreach($amails_gerencia as $valor){
                                           $nome_envio=tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","email","");
                                            $email=send_mail_smtp("SISGAT - Servidor $sureg continua sem conexão",$mensagem_g,$mensagem_g,$valor,$nome_envio);
                                         }
                                       }
                                    }







                                    }
                                    if($autorizacao == "OFF"){
                                       $msg_serv="Sem autorização para checagem!";
                                       $figura="ball_r.gif";
                                    }

                                    if($msg_serv==null){
                                     $msg_serv="--";
                                    }
						?>
     <tr>
							<td width="71" height="23">
							<p align="center"><?echo $sureg?></td>
							<td width="116" height="23">
							<p align="center"><?echo $data_ping?></td>
							<td width="35" height="23">
							<p align="center"><?Echo $nfe?></td>
							<td width="73" height="23">
							<p align="center">
							<img border="0" src="imgs/<?echo $figura?>" width="20" height="20"></td>
							<td height="23" width="29">

							<p align="center"><img border="0" src="imgs/<?echo $figura?>" width="20" height="20"></td>
							<td width="49" height="23">


							<p align="center"><?echo $tamanho_data?></td>
							<td height="23" width="137">
							<p align="center">
							<?echo $msg_serv; $msg_serv=null; $figura=null?></td>
							<td height="23" width="46">
							<p align="center"><a href="?action=monitor_ajax_tabela.php&acao_int=edit_maquina&id_item=<?echo $id_item?>&ip=<?echo $ip_host?>&tipo_user=xfac"">
							<font color="#000000">Editar</font></a></td>
							<td height="23" width="60">
							<p align="center"><a href="?action=monitor_ajax.php&acao_int=checar_maquina_tabela&id_item=<?echo $id_item?>&ip=<?echo $ip_host?>">
							<font color="#000000">Atualizar</font></a></td>
						</tr>

						<?
						}
						?>

					</table>
					<p align="center">&nbsp;</td>
     				</tr>
			</table>

    		</td>
		</tr>
	</table>
</div>
</form>
&nbsp;</p>













