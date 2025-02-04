<?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include("conf/conecta.php");
include("conf/funcs.php");

$id_usuario=$_POST['idus'];


  $checa = mysql_query("
    SELECT
     sv.erro_status
    ,sv.manutencao_status
    ,sv.arquivo_manutencao
    ,if(sv.manutencao_status='ON','MANUTENCAO',if(sv.erro_status='ON','ERRO',''))erro
    ,if(sv.erro_status='ON','O Sistema executou uma operação ilegal',if(sv.arquivo_manutencao='ON',sv.arquivo_manutencao,''))motivo
    FROM
    sgc_usuario us
    ,sgc_unidade un
    ,sgc_servidores sv
    WHERE us.id_usuario = $id_usuario
    and un.codigo = us.id_unidade
    and sv.nuf = un.codigo") or print(mysql_error());
    while($dados=mysql_fetch_array($checa)){
    $motivo = $dados['motivo'];
    $erro_status = $dados['erro_status'];
    $manutencao_status = $dados['manutencao_status'];
    $ip = $dados['ip_host'];
    $erro = $dados['erro'];
    }
    
if($erro=="MANUTENCAO"){

?>
	<head>
	<meta http-equiv="Content-Language" content="pt-br">
	</head>
	<table border="1" width="100%" cellspacing="0" style="border-collapse: collapse" bordercolor="#000000" height="55">
		    <tr>
			<td>
        	<table border="0" width="100%" cellspacing="0" cellpadding="0" height="51">
			<tr>
			<td bgcolor="#FF9900" valign="top">
			<p align="center">
			<b>
			<font size="2" face="Verdana" color="#FFFFFF">
			A T E N Ç Ã O<a href="?action=console_servicedesk.php&id_item=<?echo $iditematributo?>"><font color="#FFFFFF"><?echo $mensagem?></font></a></font><br>
			</b><font color="#FFFFFF" face="Verdana">O Servidor para xFac de sua
			unidade esta em manutenção<br>
			Motivo: <?echo $motivo?></font></td>
			</tr>
			</table>
			</td>
	    	</tr>
           	</table>
            </td>
			</tr>
			</table>
<?
exec("sudo /var/www/xfac/sgc/monitor_maquina.sh $ip",$resultado);
}elseif($erro=="ERRO"){
?>
	<head>
	<meta http-equiv="Content-Language" content="pt-br">
	</head>
	<table border="1" width="100%" cellspacing="0" style="border-collapse: collapse" bordercolor="#000000" height="55">
		    <tr>
			<td>
        	<table border="0" width="100%" cellspacing="0" cellpadding="0" height="51">
			<tr>
			<td bgcolor="#000000" valign="top">
			<p align="center">
			<b>
			<font size="2" face="Verdana" color="#FFFFFF">
			A T E N Ç Ã O<a href="?action=console_servicedesk.php&id_item=<?echo $iditematributo?>"><font color="#FFFFFF"><?echo $mensagem?></font></a></font><br>
			</b><font color="#FFFFFF" face="Verdana">O Servidor para xFac de sua unidade executou uma operação ilegal<br>
			</font></td>
			</tr>
			</table>
			</td>
	    	</tr>
           	</table>
            </td>
			</tr>
			</table>
<?
exec("sudo /var/www/xfac/sgc/monitor_maquina.sh $ip",$resultado);
}

?>




