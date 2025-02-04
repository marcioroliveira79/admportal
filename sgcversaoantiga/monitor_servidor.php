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
$arquivo="monitor_servidor.php";
$tabela="sgc_servidores";
$id_chave="id_servidor";


?>


<form method="POST" name="form1" action="sgc.php?action=monitor_ajax.php&acao_int=checar_maquinas">
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0" >
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: <?echo $titulo?> :: </b></td>
				</tr>
                <input type='hidden' name='id_item' value='<?echo $id_item?>'>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					&nbsp;
                  <?
                   $checa = mysql_query("SELECT *,date_format(ultimo_ping,'%d/%m/%y %H:%i:%s')data_ping FROM sgc_servidores ORDER BY status ASC, autorizacao DESC, erro_status DESC, manutencao_status DESC, descricao_servidor") or print mysql_error();
                                    while($dados=mysql_fetch_array($checa)){
                                    $sureg = $dados["descricao_servidor"];
                                    $status = $dados["status"];
                                    $data_ping = $dados["data_ping"];
                                    $erro_status = $dados["erro_status"];
                                    $manutencao_status = $dados["manutencao_status"];
                                    $autorizacao = $dados["autorizacao"];

                                    if($manutencao_status == "ON" or $erro_status =="ON"){
                                       $figura="alerta.gif";
                                    }else{
                                       $figura="conectado.gif";
                                    }

                                    if($manutencao_status == "ON"){
                                       $msg_serv="Servidor em Manutenção!";
                                    }
                                    if($erro_status == "ON"){
                                       $msg_serv="Servidor com ERRO!";
                                    }

                                    if($status == "OFF"){
                                       $msg_serv="Servidor sem Conexão!";
                                       $figura="desconectado.gif";
                                    }
                                    if($autorizacao == "OFF"){
                                       $msg_serv="Sem autorização para checagem!";
                                       $figura="desconectado.gif";
                                    }


                   ?>
                    <table border="1" bgcolor="#FFFFFF" width="466" cellpadding="0" style="border-collapse: collapse" bordercolor="#C0C0C0">
						<tr>
							<td>
							<table border="0" width="465" cellspacing="0" cellpadding="0">
								<tr>
									<td rowspan="4" width="80">
									<img border="0" src="imgs/<?echo $figura?>" width="80" height="83"></td>
									<td colspan="2">
									<p align="center"><b><?echo $sureg?></b></td>
								</tr>
								<tr>
									<td width="98">
									<p align="right">Status:</td>
									<td width="265">&nbsp;<?echo $status?></td>
								</tr>
								<tr>
									<td width="98">
									<p align="right">Último Ping:</td>
									<td width="265">&nbsp;<?echo $data_ping?></td>
								</tr>
								<tr>
									<td width="363" colspan="2">
									<p align="center"><font color="#FF0000"><b><? echo $msg_serv; $msg_serv=null; $figura=null ?></b><BR><?echo "$manut_msg"?></font></td>
								</tr>
							</table>
							</td>
						</tr>
					</table>
                       &nbsp;
						<?
		               	}
		             	?>

 						<p align="center"><button name="B1" onclick='this.disabled=true'>Checar</button></td>
					<p align="center"></td>
				</tr>
			</table>


			</td>
		</tr>
	</table>
</div>
</form>











