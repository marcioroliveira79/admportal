<?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include("conf/conecta.php");

include("conf/funcs.php");


?>
<div align="center">
<div id="online" align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Novos Chamados :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="100%" cellpadding="0">
						<tr>
							<td width="9" height="23" align="center">&nbsp;</td>
							<td width="90" height="23" align="center">
							<p align="left">&nbsp; </td>
							<td width="75" height="23" align="center">
							Usuário</td>
							<td width="398" height="23" align="center">
							Título</td>
							<td width="68" height="23" align="center">
							Urgência</td>
							<td width="108" height="23" align="center">Data
							Abertura</td>
							<td width="66" height="23" align="center">Espera</td>
							<td width="11" height="23">&nbsp;</td>
						</tr>
                        <?
                          $checa = mysql_query("
                          select
                           ch.id_chamado
                          ,ch.data_criacao
                          ,ch.titulo
                          ,sla.descricao
                          ,us.primeiro_nome
                          ,time_to_sec(TIMEDIFF(sysdate('%Y-%m-%d %H:%i:%s'),ch.data_criacao))segundo
                          ,TIMEDIFF(sysdate('%Y-%m-%d %H:%i:%s'),ch.data_criacao)Espera
                          from
                            sgc_chamado ch
                          , sgc_sla_analista_usuario sla
                          , sgc_usuario us
                          where  ch.id_chamado   not in ( SELECT hch.id_chamado FROM sgc_historico_chamado hch WHERE  hch.id_chamado = ch.id_chamado )
                            and ch.id_urgencia_usuario = sla.id_sla_analista
                            and us.id_usuario = ch.id_usuario
                            and ch.status='LIMBO'
                            order by ch.data_criacao desc");

                                    while($dados=mysql_fetch_array($checa)){
                                    $id_chamado = $dados['id_chamado'];
                                    $data_criacao = $dados['data_criacao'];
                                    $titulo = $dados['titulo'];
                                    $urgencia = $dados['descricao'];
                                    $primeiro_nome = $dados['primeiro_nome'];
                                    $segundos = $dados['segundo'];
                                    $espera = $dados['Espera'];


          $checa_sla_service_desck = mysql_query("
          SELECT
           if(tipo_tempo='Minutos',round(tempo*60,0),if(tipo_tempo='Horas',round(tempo*3600,0),round(tempo*86400,0)))tempo1
           ,round(if(tipo_tempo='Minutos',round(tempo*60,0),if(tipo_tempo='Horas',round(tempo*3600,0),round(tempo*86400,0)))/2,0) tempo2
           ,round(if(tipo_tempo='Minutos',round(tempo*60,0),if(tipo_tempo='Horas',round(tempo*3600,0),round(tempo*86400,0)))/3,0) tempo3
           FROM sgc_sla_servicedesk  WHERE
           (curtime() not between hora_final and hora_inicio and hora_final<hora_inicio)
            or
            (curtime() between hora_inicio and hora_final and hora_final>hora_inicio)");
                         while($dados_sla=mysql_fetch_array($checa_sla_service_desck)){
                           $tempo1 = $dados_sla['tempo1'];
                           $tempo2 = $dados_sla['tempo2'];
                           $tempo3 = $dados_sla['tempo3'];
                         }




                                    if($segundos>=$tempo3 and $segundos<$tempo2){

                                      $cor_espera="#FF9900";

                                    }elseif($segundos>$tempo2){

                                       $cor_espera="#FF0000";

                                    }elseif($segundos<$tempo1){

                                       $cor_espera="#008000";

                                    }



                                    if($urgencia=="Crítica"){

                                        $cor="#FF0000";

                                      }elseif($urgencia=="Alta"){

                                        $cor="#FF9900";

                                      }elseif($urgencia=="Média"){

                                        $cor="#6699FF";

                                      }elseif($urgencia=="Baixa"){

                                        $cor="#008000";

                                      }

                        ?>

                        <tr>
							<td width="9" height="23"></td>
							<td width="90" height="23" bgcolor="#C0C0C0">&nbsp;
                            <a href="?action=parametrizacao.php&id_chamado=<?echo $id_chamado?>"><font color="#000000">Categorização</font></a>&nbsp;</td>
							<td width="75" height="23" bgcolor="#C0C0C0">
							<p align="center"><?echo $primeiro_nome?></td>
							<td width="398" height="23" bgcolor="#C0C0C0">
							<p align="center">&nbsp;<?echo $titulo?></td>
							<td width="68" height="23" bgcolor="<?echo $cor?>">
							<p align="center">&nbsp;<font color="#FFFFFF"><?echo $urgencia?></font></td>
							<td width="108" height="23" align="center" bgcolor="#C0C0C0">
							<?echo $data_criacao=data_with_hour($data_criacao)?></td>
							<td width="66" height="23" align="center" bgcolor="<?echo $cor_espera?>">
							<b><font color="#FFFFFF"><?echo $espera?></font></b></td>
							<td width="11" height="23">&nbsp;</td>
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
</div>
	<table border="0" width="720" cellspacing="0" cellpadding="0">
	<tr>
		<td width="58"> </td>
		<td><p align='center'><font face='Verdana' size='1'><br>

        </td>
		<td width="42"></td>
	</tr>
</table>
<p>&nbsp;</p>


<?
