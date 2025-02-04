<?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");




include("conf/funcs.php");
include("conf/conecta.php");

$idus=$_POST['idus'];



?>
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Meus Chamados em Aberto :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center" style="background-color: #FFFFFF">
					<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#DFDFDF" style="border-collapse: collapse">
						<tr>
							<td width="48" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>ID</b></td>
							<td width="74" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Suporte</b></td>
							<td width="545" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Descrição Resumida</b></td>
							<td width="81" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Usuário</b></td>
							<td width="62" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Prioridade</b></td>
							<td width="200" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Situação</b></td>
							<td width="80" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Data</b></td>
							<td width="66" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">&nbsp;</td>
						</tr>
						<?

$checa = mysql_query("
SELECT

  ch.id_chamado
, ana.primeiro_nome analista
, cri.primeiro_nome usuario_criador
, ch.status situacao_chamado
, concat(sla_s.descricao,' - ',sla_s.tempo,' ',sla_s.tipo_tempo)sla_service
, concat(sla_a.descricao,' - ',sla_a.tempo,' ',sla_a.tipo_tempo)sla_analista
, sla_a.descricao descricao_sla_analista
, ch.titulo resumo_chamado

,  date_format(ch.data_criacao,'%d/%m/%y %h:%i:%s')data_criacao
,  timediff(sysdate('%Y-%m-%d %H:%i:%s'),ch.data_criacao)tempo_criacao
,  time_to_sec(timediff(sysdate('%Y-%m-%d %H:%i:%s'),ch.data_criacao))criacao_segundos

, (SELECT date_format(visto_service_desk,'%d/%m/%y %h:%i:%s') FROM sgc_historico_chamado WHERE id_chamado = ch.id_chamado limit 1)data_atendimento_service_dek
, (SELECT timediff(sysdate('%Y-%m-%d %H:%i:%s'),visto_service_desk) FROM sgc_historico_chamado WHERE id_chamado = ch.id_chamado limit 1)tempo_service_desk

, (SELECT date_format(visto_suporte,'%d/%m/%y %h:%i:%s') FROM sgc_historico_chamado WHERE id_chamado = ch.id_chamado limit 1)visto_suporte
, (SELECT timediff(sysdate('%Y-%m-%d %H:%i:%s'),visto_suporte) FROM sgc_historico_chamado WHERE id_chamado = ch.id_chamado limit 1)tempo_suporte

, (SELECT date_format(data_criacao,'%d/%m/%y %h:%i:%s') FROM sgc_historico_chamado WHERE id_chamado = ch.id_chamado order by data_criacao desc limit 1)data_ultima_atualizacao
, (SELECT timediff(sysdate('%Y-%m-%d %H:%i:%s'),data_criacao) FROM sgc_historico_chamado WHERE id_chamado = ch.id_chamado order by data_criacao desc limit 1)tempo_ultima

, (SELECT atualizacao FROM sgc_historico_chamado WHERE id_chamado = ch.id_chamado order by data_criacao desc limit 1)ultima_atualizacao



FROM

  sgc_chamado ch
, sgc_usuario ana
, sgc_usuario cri
, sgc_unidade uni_ana
, sgc_departamento dep_ana
, sgc_unidade uni_cri
, sgc_departamento dep_cri
, sgc_sla_servicedesk sla_s
, sgc_sla_analista_usuario sla_a
where
    ch.quem_criou = $idus
and ch.quem_criou = cri.id_usuario
and ch.id_suporte = ana.id_usuario
and ana.id_unidade = uni_ana.codigo
and ana.id_departamento = dep_ana.id_departamento
and cri.id_unidade = uni_cri.codigo
and cri.id_departamento = dep_cri.id_departamento
and sla_s.id_sla_service = ch.id_sla_service
and ch.status not in ('Fechado','Suspenso')
and sla_a.id_sla_analista = (SELECT prioridade FROM sgc_historico_chamado WHERE id_chamado = ch.id_chamado order by data_criacao desc limit 1)

order by data_criacao desc


") or print(mysql_error());
                                 while($dados=mysql_fetch_array($checa)){
                                  $id_chamado = $dados['id_chamado'];
                                  $descricao = $dados['resumo_chamado'];
                                  $nome_criador = $dados['usuario_criador'];
                                  $desc_sla_c = $dados['sla_analista'];
                                  $desc_sla = $dados['descricao_sla_analista'];

                                  $data_criacao = $dados['data_criacao'];
                                  $situacao = $dados['situacao_chamado'];
                                  $suporte = $dados['analista'];
                                  $segundo = $dados['criacao_segundos'];
                                  $espera = $dados['tempo_criacao'];


$desc_sla=tabelainfo(tabelainfo($id_chamado,"sgc_historico_chamado","prioridade","id_chamado",""),"sgc_sla_analista_usuario","descricao","id_sla_analista","");



                                    if($desc_sla=="Crítica"){

                                        $cor="#FF0000";

                                      }elseif($desc_sla=="Alta"){

                                        $cor="#FF9900";

                                      }elseif($desc_sla=="Média"){

                                        $cor="#6699FF";

                                      }elseif($desc_sla=="Baixa"){

                                        $cor="#008000";

                                      }

                     	?>
						<tr>
							<td width="48" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center" bgcolor="#EEEEEE"><?echo $id_chamado?></td>
							<td width="74" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo $suporte?></td>
							<td width="545" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" align="center" bgcolor="#EEEEEE"><p align="left">&nbsp;<a href="?action=vis_chamado.php&id_chamado=<? echo $id_chamado?>"><font color="#000000"><?echo $descricao?></a></font></td>
							<td width="81" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo $nome_criador?></td>
							<td width="62" height="23" bgcolor="<?echo $cor?>" style="color: #FFFFFF; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo $desc_sla?></td>
							<td width="200" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" align="center"><?echo $situacao=tabelainfo($id_chamado,"sgc_historico_chamado","situacao","id_chamado","")?></td>
							<td width="160" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center" bgcolor="#EEEEEE"><?echo $data_criacao?></td>
							<td width="66" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><p align="center"><?echo $espera?></td>
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
?>
