<?php
OB_START();
session_start();

$permissao=$_POST['permissao'];
$idusuario=$_POST['idus'];
$url=$_POST['url'];

$conexao = mysql_connect('mysql.conab.gov.br','xfac','xfacsalvador') or die ("Não foi possível conectar com o MySQL!");
            mysql_select_db('db_sgc') or die ("Banco de dados inexistente");


function chamados_em_atrazo($idusuario){
$count=0;
$checa = mysql_query ("SELECT * FROM sgc_chamado ch, sgc_historico_chamado hc
WHERE ch.id_chamado = hc.id_chamado
AND hc.id_historico = ch.id_linha_historico
AND ch.id_suporte = $idusuario
AND ch.status not in ('Fechado','Concluido','Suspenso','Aguardando Resposta - Usuário')
AND time_to_sec(timediff(sysdate(),ch.data_criacao)) > 259200")or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
    $count++;
}

$checa_aviso = mysql_query("SELECT time_to_sec(timediff(sysdate(),data_aviso))tempo FROM sgc_aviso_tela
WHERE tela_aviso = 'ATRASOS'
AND id_usuario = $idusuario order by id_aviso desc limit 1")or print(mysql_error());
while($dados_aviso=mysql_fetch_array($checa_aviso)){
      $tempo = $dados_aviso['tempo'];
}

If($tempo != null && $tempo < 350 ){
  return "NAO";
}elseif($tempo > 350 && $count > 0){
  return "SIM";
}elseif($tempo == "" && $count > 0){
  return "SIM";
}else{
  return "NAO";
}



}


if($permissao=='ok'){
$acao_int=$_POST['acao_int'];


if(!isset($acao_int)){



}elseif($acao_int=="banner"){

if(chamados_em_atrazo($idusuario)=="SIM"){

?>




<script type="text/javascript">
function fecharId(div)
{
	document.getElementById(div).style.display = "none";
}
</script>

<style type="text/css">
.style2 {
	color: #FFFFFF;
	font-size: 8px;
}
</style>
</head>
</head>
<body>

<div id="showimage"  style="width:520px;margin-left:-260px;position:absolute;z-index:3;border:1px solid #800000;left:50%;top:80px;">
	<div style="background:#FF0000;color:#ffffff;padding:5px">
	<div style="float:LEFT;font-size:10px;"><font face="Arial"><b>A T E N Ç Ã O</b></font></div>
	<div style="float:right;font-size:10px;" ><font face="Arial"><b><a href="#" onclick="document.getElementById('showimage').style.visibility='hidden'" style="color:#FFFFFF">	Fechar X</a></b></font></div>
	<div style="clear:both"></div>
</div>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
			<table border="0" width="100%" style="border-collapse: collapse" bgcolor="#FFFFFF">
				<tr>
					<td>
					<p align="center"><b><font face="Arial" size="2">Você possui chamado(s) aberto(s) há mais de 3 dias. Favor feche-o(s) ou conclua o(s) mesmo(s).</font></b></td>
				</tr>
				<tr>
					<td>
					<div align="center">
						<table border="1" width="508" cellspacing="1" style="border-collapse: collapse" bgcolor="#E8EAE9">
							<tr>
								<td width="51"><font face="Arial" size="2">Nº
								</font></td>
								<td width="376"><font face="Arial" size="2">
								Status</font></td>
								<td width="66" align="center">
								<font face="Arial" size="2">Data</font></td>
							</tr>
							<?
							$checa = mysql_query ("SELECT
                            ch.id_chamado
                           ,ch.status
                           ,date_format(ch.data_criacao,'%d/%m/%Y') criacao

                            FROM sgc_chamado ch, sgc_historico_chamado hc
                            WHERE ch.id_chamado = hc.id_chamado
                            AND hc.id_historico = ch.id_linha_historico
                            AND ch.id_suporte = $idusuario
                            AND ch.status not in ('Fechado','Concluido','Suspenso','Aguardando Resposta - Usuário')
                            AND time_to_sec(timediff(sysdate(),ch.data_criacao)) > 259200")or print(mysql_error());
                            while($dados=mysql_fetch_array($checa)){
                               $id_chamado = $dados['id_chamado'];
                                      $status = $dados['status'];
                                            $criacao = $dados['criacao'];
                                                  $suporte = $dados['ch.id_suporte'];
                                                  $criacao_linha = $dados['hc.quem_criou_linha'];
                            ?>
							<tr >
								<td width="51"><font face="Arial" size="2"><a href="sgc.php?action=vis_chamado.php&id_chamado=<?echo $id_chamado?>"><?echo $id_chamado?></a> </font></td>
								<td width="376"><font face="Arial" size="2">&nbsp;<a href="sgc.php?action=vis_chamado.php&id_chamado=<?echo $id_chamado?>"><?echo $status?></a> </font></td>
								<td width="66">
								<p align="center"><font face="Arial" size="2"><a href="sgc.php?action=vis_chamado.php&id_chamado=<?echo $id_chamado?>"><?echo $criacao?></a> </font></td>
							</tr>
							<?
							}
							?>
						</table>
					</div>
					</td>
				</tr>
				<tr>
					<td>
					<p align="center"><b><font face="Arial" size="2">Você será lembrado novamente em 10 minutos!</font></b></td>
				</tr>
			</table>
			</td>
		</tr>
</table>
</div>




<?
$insert = mysql_query ("INSERT INTO sgc_aviso_tela (data_aviso,tela_aviso,id_usuario) VALUES (sysdate(),'ATRASOS',$idusuario)")or print(mysql_error());
 }
}
}else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
