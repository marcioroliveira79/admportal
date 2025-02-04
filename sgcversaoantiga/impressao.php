<?php
OB_START();
session_start();
include("conf/conecta.php");
include("conf/funcs.php");

$id_chamado = $_GET['id_chamado'];
$id_usuario = $_GET['id_usuario'];
$permissao = $_GET['permissao'];

If ($permissao == "OK"){


?>


<head>
<meta http-equiv="Content-Language" content="pt-br">
</head>
<body onload="self.print();">
<table border="0" width="629" cellspacing="0" cellpadding="0">
	<tr>
		<td width="629" bgcolor="#FFFFFF">
		<b><font face="Arial" size="2">SISGAT | Sistema Gerencial de Atendimento</font></b></td>
	</tr>
	<tr>
		<td width="629" bgcolor="#FFFFFF">
		<b><font face="Arial" size="2">Impresso por: <?echo tabelainfo($id_usuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario",""); echo " - "; echo data_with_hour(datahoje("datahora"))?> </font></b></td>
	</tr>
	<tr>
		<td width="629" bgcolor="#C0C0C0">
		<p align="center"><b><font face="Arial" size="2">Chamado Nº: <?echo $id_chamado?></font></b></td>
	</tr>
	<tr>
		<td width="629">
			<fieldset style="padding: 2">
			<table border="0" width="620" cellspacing="0" cellpadding="0">
				<tr>
					<td width="598"><font face="Arial" size="2"><b>Data de Abertura do Chamado: </b><?echo data_with_hour(tabelainfo($id_chamado,"sgc_chamado","data_criacao","id_chamado",""));?></font></td>
				</tr>
				<tr>
					<td width="598"><font face="Arial" size="2"><b>Última Atualização: </b><?echo data_with_hour(tabelainfo($id_chamado,"sgc_historico_chamado","data_criacao","id_chamado","order by id_historico desc limit 1"));?></font></td>
				</tr>
				<tr>
					<td width="598"><font face="Arial" size="2"><b>Criador Chamado:</b> <?echo tabelainfo(tabelainfo($id_chamado,"sgc_chamado","quem_criou","id_chamado",""),"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")?> <b>Telefone: </b> <?echo tabelainfo(tabelainfo($id_chamado,"sgc_chamado","quem_criou","id_chamado",""),"sgc_usuario","concat('(',ddd,')',' ',telefone)","id_usuario","")?>  <b>UF: </b><?echo tabelainfo(tabelainfo(tabelainfo($id_chamado,"sgc_chamado","quem_criou","id_chamado",""),"sgc_usuario","id_unidade","id_usuario",""),"sgc_unidade","sigla","codigo","")?> </font></td>
				</tr>
			</table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td width="629">
		&nbsp;</td>
	</tr>
	<tr>
		<td width="629" bgcolor="#C0C0C0">
		<p align="center"><b><font face="Arial" size="2">Suporte</font></b></td>
	</tr>
	<tr>
		<td width="629">
			<fieldset style="padding: 2">

<table border="0" width="620" cellspacing="0" cellpadding="0">
	<tr>
		<td width="598"><font face="Arial" size="2"><b>Área de Atuação: </b><?echo tabelainfo($id_chamado,"sgc_historico_chamado","id_suporte","id_chamado","order by id_historico desc limit 1");?></font></td>
	</tr>
	<tr>
		<td width="598"><font face="Arial" size="2"><b>Analísta: </b><?echo tabelainfo(tabelainfo($id_chamado,"sgc_historico_chamado","id_suporte","id_chamado","order by id_historico desc limit 1"),"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");?></font></td>
	</tr>
	<tr>
		<td width="598"><font face="Arial" size="2"><b>Prioridade: </b><?echo tabelainfo(tabelainfo($id_chamado,"sgc_historico_chamado","prioridade","id_chamado","order by id_historico desc limit 1"),"sgc_sla_analista_usuario","descricao","id_sla_analista","")?></font></td>
	</tr>
	<tr>
		<td width="598"><font face="Arial" size="2"><b>Situação do Chamado: </b><?echo tabelainfo($id_chamado,"sgc_historico_chamado","situacao","id_chamado","order by id_historico desc limit 1")?></font></td>
	</tr>
</table>

			</fieldset>
		</td>
	</tr>
	<tr>
		<td width="629">
			&nbsp;</td>
	</tr>
	<tr>
		<td width="629">
			<fieldset style="padding: 2">

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="629" bgcolor="#C0C0C0">
		<p align="center"><b><font face="Arial" size="2">Título </font></b></td>
	</tr>
	<tr>
		<td width="629">
		<p align="center"><font face="Arial" size="2"><?echo tabelainfo($id_chamado,"sgc_chamado","titulo","id_chamado","")?></font></td>
	</tr>
	<tr>
		<td width="629">&nbsp;</td>
	</tr>
	<tr>
		<td width="629" bgcolor="#C0C0C0">
		<p align="center"><b><font face="Arial" size="2">Descrição</font></b></td>
	</tr>
	<tr>
		<td width="621">
		<p align="center"><font face="Arial" size="2"><?echo nl2br(tabelainfo($id_chamado,"sgc_chamado","descricao","id_chamado",""))?></font></td>
	</tr>
	<tr>
		<td width="621">&nbsp;</td>
	</tr>
	<tr>
		<td width="621"><font face="Arial" size="2"><b>Obs:</b><?echo tabelainfo($id_chamado,"sgc_chamado","obs","id_chamado","")?></font></td>
	</tr>
</table>

			</fieldset>
&nbsp;</td>
	</tr>
	<tr>
		<td width="629" bgcolor="#C0C0C0">
		<p align="center"><b><font face="Arial" size="2">Atualizações:</font></b></td>
	</tr>
	<tr>
		<td width="629">&nbsp;</td>
	</tr>
	<tr>
    <?
     $checa = mysql_query("
                      select hc.acao
                    , concat(us.primeiro_nome,' ',us.ultimo_nome)usuario
                    , us1.id_usuario id_usuario_linha
                    , concat(us1.primeiro_nome,' ',us1.ultimo_nome)usuario_linha
                    , date_format(hc.data_criacao,'%d/%m/%y %H:%i')data_criacao
                    , hc.atualizacao
                  from
                     sgc_historico_chamado hc
                    ,sgc_usuario us
                    ,sgc_usuario us1
                  where hc.id_chamado=$id_chamado
                     and  us.id_usuario = hc.quem_criou
                     and  us1.id_usuario = hc.quem_criou_linha


                      order by hc.data_criacao desc
                    ") or print(mysql_error());
                    while($dados=mysql_fetch_array($checa)){
                       $acao = $dados['acao'];
                       $usuario = $dados['usuario'];
                       $id_usuario_linha = $dados['id_usuario_linha'];
                       $usuario_linha = $dados['usuario_linha'];
                       $data = $dados['data_criacao'];
                       $atualizacao= $dados['atualizacao'];
    
    ?>

    <td width="629">
			<fieldset style="padding: 2">
			<legend><b><font size="1" face="Arial"><?echo $data?>  por <?echo $usuario?></font></b></legend>
			<font size="2" face="Arial"><i><?echo nl2br($atualizacao)?></i></font></fieldset>&nbsp;</td>
	</tr>
    <?
    }
    ?>

	<tr>
		<td width="629">
			&nbsp;</td>
	</tr>
</table>
<?
}else{
?>
<body onload="FecharJanela();">


<script type='text/javascript'>
<!--
function FecharJanela()
{
ww = window.open(window.location, "_self");
ww.close();
}
-->
</script>
<?


}
?>

