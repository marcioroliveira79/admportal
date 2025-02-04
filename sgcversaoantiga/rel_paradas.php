<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Relatório Gerêncial";

$id_item=$_GET['id_item'];
$arquivo="rel_paradas.php";

if(!isset($acao_int)){

    if(!isset($_POST['id_item'])){
              $id_item=$_GET['id_item'];
    }else{
              $id_item=$_POST['id_item'];
    }

?>
<div align="center">
	<form method="POST" id="form1" name='meuFormulario' action="?action=<?echo $arquivo?>&acao_int=gera_relatorio" onsubmit="return checarGeral(this)">
	<input type='hidden' name='id_item' value='<?echo $id_item?>'>
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Relatório de Paradas do Sistema :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<select size="1" name="servidor" onchange="this.form.submit();">
					<option value=""></option>
					<?
                     $checa_menu = mysql_query("select id_servidor,descricao_servidor from sgc_servidores") or print mysql_error();
                                    while($dados_menu=mysql_fetch_array($checa_menu)){
                                    $id_servidor = $dados_menu["id_servidor"];
                                    $descricao_servidor = $dados_menu["descricao_servidor"];
                    ?>
                    <option value="<?echo $id_servidor?>"><?echo $descricao_servidor?></option>
                    <?
                    }
                    ?>
                    </select></td>
				</tr>
			</table></td>
		</tr>
	</table>
</form>
</div>
<?









}elseif($acao_int=="gera_relatorio"){

$id_servidor=$_POST['servidor'];

$descricao_servidor=tabelainfo($id_servidor,"sgc_servidores","descricao_servidor","id_servidor","");
$ip_host=tabelainfo($id_servidor,"sgc_servidores","ip_host","id_servidor","");

?><div align="center">
	<form method="POST" id="form1" name='meuFormulario' action="?action=<?echo $arquivo?>&acao_int=gera_relatorio" onsubmit="return checarGeral(this)">
	<input type='hidden' name='id_item' value='<?echo $id_item?>'>
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Relatório de Paradas do Sistema :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<br>
&nbsp;<table border="1" width="300">
						<tr>
							<td bgcolor="#FFFFFF">
							<p align="center"><?echo $descricao_servidor?></td>
						</tr>
					</table>
					<p>

</p>
					<table border="1" width="164">
						<tr>
							<td colspan="2" bgcolor="#FFFFFF">
							<p align="center">Paradas por mês MANUTENÇÃO</td>
						</tr>
						<tr>
							<td align="center" bgcolor="#FFFFFF">Mês</td>
							<td align="center" bgcolor="#FFFFFF">Qtde</td>
						</tr>
						<?
                                        $checa_st = mysql_query("SELECT
                                        date_format(data_log,'%m/%y') MES
                                        ,count(id_manutencao) QUANTIA_M
                                        FROM sgc_log_servidor_manutencao
                                        WHERE ip_servidor = '$ip_host'
                                        GROUP BY date_format(data_log,'%m')
                                        order by mes desc
                                        ") or print mysql_error();
                                       while($dados_st=mysql_fetch_array($checa_st)){
                                         $mes = $dados_st["MES"];
                                         $qtde = $dados_st["QUANTIA_M"];
                                         $total=$qtde+$total;
                         ?>
						<tr>
							<td align="center" bgcolor="#FFFFFF"><?echo $mes?></td>
							<td align="center" bgcolor="#FFFFFF"><?echo $qtde?></td>
						</tr>
						<?
						}
						?><tr>
							<td align="center" bgcolor="#FFFFFF">Total</td>
							<td align="center" bgcolor="#FFFFFF"><?echo $total?></td>
						</tr>
					</table>
					<p>
</p>
					<table border="1" width="500">
						<tr>
							<td colspan="3" bgcolor="#FFFFFF">
							<p align="center">Datas das ocorrências por MANUTENÇÃO</td>
						</tr>
						<tr>
							<td align="center" bgcolor="#FFFFFF">Início ocorrência</td>
							<td align="center" bgcolor="#FFFFFF">Término ocorrência</td>
							<td align="center" bgcolor="#FFFFFF">Tempo parada</td>
						</tr>
						<?
                                        $checa_st = mysql_query("Select ip_servidor
                                        ,data_log
                                        ,if(data_remocao is not null, data_remocao,'CONSOLE') data_remocao_M
                                        ,if(timediff(data_remocao,data_log) is not null, timediff(data_remocao,data_log), 'CONSOLE') TIME_OUT
                                        FROM sgc_log_servidor_manutencao
                                        where ip_servidor = '$ip_host'
                                        order by data_log desc
                                        ") or print mysql_error();
                                       while($dados_st=mysql_fetch_array($checa_st)){
                                         $inicio = $dados_st["data_log"];
                                         $termino = $dados_st["data_remocao_M"];
                                         $tempo = $dados_st["TIME_OUT"];


                         ?>
						<tr>
							<td align="center" bgcolor="#FFFFFF"><?echo$inicio?></td>
							<td align="center" bgcolor="#FFFFFF"><?echo$termino?></td>
							<td align="center" bgcolor="#FFFFFF"><?echo$tempo?></td>
						</tr>
						<?
						}
						 $checa_st = mysql_query("Select ip_servidor
                         ,sec_to_time(SUM(time_to_sec(timediff(data_remocao,data_log)))) SOMA
                         FROM sgc_log_servidor_manutencao
                         where ip_servidor = '$ip_host'
                         and if(timediff(data_remocao,data_log) is not null, time_to_sec(timediff(data_remocao,data_log)), 'CONSOLE') != 'CONSOLE'
                         GROUP BY ip_servidor
                         order by data_log desc
                          ") or print mysql_error();
                                       while($dados_st=mysql_fetch_array($checa_st)){
                                         $tot_tempo = $dados_st["SOMA"];
                                         }

						
						?>
						<tr>
							<td align="center" bgcolor="#FFFFFF">&nbsp;</td>
							<td align="center" bgcolor="#FFFFFF"><?echo $total?></td>
							<td align="center" bgcolor="#FFFFFF"><?echo $tot_tempo?></td>
						</tr>

					</table>

                        <p>&nbsp;










                    	<table border="1" width="164">
						<tr>
							<td colspan="2" bgcolor="#FFFFFF">
							<p align="center">Paradas por mês em função de ERROS</td>
						</tr>
						<tr>
							<td align="center" bgcolor="#FFFFFF">Mês</td>
							<td align="center" bgcolor="#FFFFFF">Qtde</td>
						</tr>
						<?              $total=0;
                                        $checa_st = mysql_query("SELECT
                                      date_format(data_erro,'%m/%y') MES
                                      ,count(id_log_erro) QUANTIA_M
                                      FROM sgc_log_servidor_erro
                                      WHERE servidor = '$ip_host'
                                      GROUP BY date_format(data_erro,'%m')
                                      order by mes desc
                                        ") or print mysql_error();
                                       while($dados_st=mysql_fetch_array($checa_st)){
                                         $mes = $dados_st["MES"];
                                         $qtde = $dados_st["QUANTIA_M"];
                                         $total=$qtde+$total;
                         ?>
						<tr>
							<td align="center" bgcolor="#FFFFFF"><?echo $mes?></td>
							<td align="center" bgcolor="#FFFFFF"><?echo $qtde?></td>
						</tr>
						<?
						}
						?><tr>
							<td align="center" bgcolor="#FFFFFF">Total</td>
							<td align="center" bgcolor="#FFFFFF"><?echo $total?></td>
						</tr>
					</table>
					<p>
</p>
					<table border="1" width="500">
						<tr>
							<td colspan="3" bgcolor="#FFFFFF">
							<p align="center">Datas das ocorrências por ERROS</td>
						</tr>
						<tr>
							<td align="center" bgcolor="#FFFFFF">Início ocorrência</td>
							<td align="center" bgcolor="#FFFFFF">Término ocorrência</td>
							<td align="center" bgcolor="#FFFFFF">Tempo parada</td>
						</tr>
						<?
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
                                         $inicio = $dados_st["data_erro"];
                                         $termino = $dados_st["data_remocao_M"];
                                         $tempo = $dados_st["TIME_OUT"];


                         ?>
						<tr>
							<td align="center" bgcolor="#FFFFFF"><?echo$inicio?></td>
							<td align="center" bgcolor="#FFFFFF"><?echo$termino?></td>
							<td align="center" bgcolor="#FFFFFF"><?echo$tempo?></td>
						</tr>
						<?
						}
						 $checa_st = mysql_query("SELECT
                        sec_to_time(SUM(time_to_sec(timediff(data_liberacao,data_erro)))) SOMA
                        FROM sgc_log_servidor_erro
                        where servidor = '$ip_host'
                        and if(timediff(data_liberacao,data_erro) is not null,time_to_sec(timediff(data_liberacao,data_erro)), 'CONSOLE') !='CONSOLE'
                        order by data_erro desc
                          ") or print mysql_error();
                                       while($dados_st=mysql_fetch_array($checa_st)){
                                         $tot_tempo = $dados_st["SOMA"];
                                         }
                                         


						?>
						<tr>
							<td align="center" bgcolor="#FFFFFF">&nbsp;</td>
							<td align="center" bgcolor="#FFFFFF"><?echo $total?></td>
							<td align="center" bgcolor="#FFFFFF"><?echo $tot_tempo?></td>
						</tr>

					</table>


					<p>&nbsp;</td>
				</tr>
			</table></td>
		</tr>
	</table>


</form>

</div>
<?


}

}else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
