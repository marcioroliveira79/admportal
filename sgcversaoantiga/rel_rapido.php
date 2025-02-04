<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Relatório Rápido";
$id_item=$_GET['id_item'];
$arquivo="rel_rapido.php";
$tabela="sgc_menu";
$id_chave="id_menu";


if(!isset($acao_int)){
?>
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

<form method="POST" name="form1" action="sgc.php?action=<?echo $arquivo?>&acao_int=gerar" onSubmit="return valida_dados(this)">
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
					<table border="0" width="488" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
						</tr>
						<tr>
							<td width="180">
							<p align="right">Data:&nbsp;</td>
							<td width="308" height="23">


		     	<select size="1" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="data">
                               <?
                                    $checa = mysql_query("SELECT  distinct
                                     date_format(data_criacao,'%d/%m/%Y')data_formatada
                                     ,date_format(data_criacao,'%Y-%m-%d')data_banco
                                      FROM sgc_chamado order by data_criacao desc
                                       ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $data_formatada = $dados['data_formatada'];
                                    $data_banco = $dados['data_banco'];
                            ?>
                            <option value="<?echo $data_banco?>"><?echo $data_formatada?></option>
                            <?
                            }
                            ?>


                            </select></td>
						</tr>
						<tr>
							<td width="180">
							<p align="right">Enviar Para Gerência:&nbsp; </td>
							<td width="308" height="23">


							<select size="1" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" name="gerencia">
                            <option value="NAO">Não</option>
                            <option value="SIM">Sim</option>


                            </select></td>
						</tr>
						<tr>
							<td width="488" colspan="2" height="23">
							<p align="center">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">
							<input type="submit" value="Gerar" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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

  }
elseif($acao_int=="gerar"){
if($_GET['data']==null){
  $data=$_POST['data'];
}else{
  $data=$_GET['data'];
}
if($_GET['gerencia']==null){
  $gerencia=$_POST['gerencia'];
}else{
  $gerencia=$_GET['gerencia'];
}
if($_GET['id_item']==null){
  $id_item=$_POST['id_item'];
}else{
  $id_item=$_GET['id_item'];
}


    $checa = mysql_query("SELECT COUNT(*)COUNT FROM sgc_chamado WHERE  date_format(data_criacao,'%Y-%m-%d') = '$data' ") or print(mysql_error());
    while($dados=mysql_fetch_array($checa)){
      $chamados_abertos_hoje = $dados['COUNT'];
    }

     $checa = mysql_query("
     SELECT COUNT(*)COUNT FROM sgc_chamado ch, sgc_historico_chamado hc
     WHERE date_format(hc.data_criacao,'%Y-%m-%d') = '$data'
     and hc.id_historico = id_linha_historico
     and ch.status='Fechado'
     and  date_format(hc.data_criacao,'%Y-%m-%d') = date_format(ch.data_criacao,'%Y-%m-%d')
      ") or print(mysql_error());
    while($dados=mysql_fetch_array($checa)){
      $chamados_fechados_hoje = $dados['COUNT'];
    }
      $checa = mysql_query("SELECT COUNT(*)COUNT FROM sgc_chamado ch  WHERE date_format(ch.data_criacao,'%Y-%m-%d') <= '$data'
   ") or print(mysql_error());
    while($dados=mysql_fetch_array($checa)){
      $total_abertos_ate_hoje = $dados['COUNT'];
    }
    $checa = mysql_query("
    SELECT COUNT(*)COUNT FROM sgc_chamado ch
    WHERE date_format(ch.data_criacao,'%Y-%m-%d') <= '$data'
    and ch.status='Fechado'") or print(mysql_error());
    while($dados=mysql_fetch_array($checa)){
      $total_abertos_ate_hoje_fechado = $dados['COUNT'];
    }
    $checa = mysql_query("
     SELECT COUNT(*)COUNT FROM sgc_chamado ch
     WHERE date_format(ch.data_criacao,'%Y-%m-%d') <= '$data'

     ") or print(mysql_error());
    while($dados=mysql_fetch_array($checa)){
      $total_abertos_ate_hoje_aberto = $dados['COUNT'];
    }

     $checa = mysql_query("
       SELECT COUNT(*)COUNT,ROUND(SUM(hc.nota_enquete)/COUNT(*),0)MEDIA_NOTA,MAX(hc.nota_enquete)MAIOR_NOTA,MIN(hc.nota_enquete)MENOR_NOTA FROM sgc_chamado ch, sgc_historico_chamado hc
     WHERE date_format(hc.data_criacao,'%Y-%m-%d') = '$data'
     and hc.id_historico = id_linha_historico
     and ch.status='Fechado'
     and  date_format(hc.data_criacao,'%Y-%m-%d') = date_format(ch.data_criacao,'%Y-%m-%d')
     and hc.nota_enquete is not null

     ") or print(mysql_error());
    while($dados=mysql_fetch_array($checa)){
      $media_nota_data = $dados['MEDIA_NOTA'];
      $maior_nota_data = $dados['MAIOR_NOTA'];
      $menor_nota_data = $dados['MENOR_NOTA'];
      $chamado_fechado_com_nota = $dados['COUNT'];
    }
    
    
     $checa = mysql_query("
           SELECT
     COUNT(*)COUNT,ROUND(SUM(hc.nota_enquete)/ COUNT(*),0)MEDIA_NOTA
     ,max(hc.nota_enquete)MAIOR
     ,min(hc.nota_enquete)MENOR
     FROM sgc_chamado ch, sgc_historico_chamado hc
     WHERE date_format(ch.data_criacao,'%Y-%m-%d') <= '$data'
     and hc.id_historico = id_linha_historico
     and ch.status='Fechado'
     and hc.nota_enquete is not null

     ") or print(mysql_error());
    while($dados=mysql_fetch_array($checa)){
      $media_nota_ate_data = $dados['MEDIA_NOTA'];
      $maior_nota_ate_data = $dados['MAIOR'];
      $menor_nota_ate_data = $dados['MENOR'];

    }

      $checa = mysql_query("SELECT count(*) TOTAL FROM  sgc_acesso ac WHERE date_format(ac.data_acesso,'%Y-%m-%d') <= '$data'") or print(mysql_error());
     while($dados=mysql_fetch_array($checa)){
      $acessos_ate_data = $dados['TOTAL'];

    }
    
     $checa = mysql_query("SELECT count(*) TOTAL,INTERVAL -1 DAY + '$data' DATA_1,INTERVAL 1 DAY + '$data' DATA_0  FROM  sgc_acesso ac WHERE date_format(ac.data_acesso,'%Y-%m-%d') <= INTERVAL -1 DAY + '$data'") or print(mysql_error());
     while($dados=mysql_fetch_array($checa)){
      $acessos_ate_data_1 = $dados['TOTAL'];
      $data_1 = $dados['DATA_1'];
      $data_0 = $dados['DATA_0'];

    }

    
?>

<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: <?echo invertedata($data)?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" width="100%">
							</td>
						</tr>
						<tr>
							<td width="252" align="right">
							Total Chamados até <?echo invertedata($data)?>:&nbsp;&nbsp; </td>
							<td width="643">
							<?echo   $total_abertos_ate_hoje?></td>
						</tr>

         <tr>
							<td width="252" align="right">
							Total Fechados Chamados até <?echo invertedata($data)?>:&nbsp;&nbsp; </td>
							<td width="643">
							<?echo   $total_abertos_ate_hoje_fechado?></td>
						</tr>
							<tr>
							<td width="252" align="right">
							&nbsp;</td>
							<td width="643">
							&nbsp;</td>
						</tr>
						<tr>
							<td width="252" align="right">
							Criados <?echo invertedata($data)?>:&nbsp;&nbsp; </td>
							<td width="643">
							<?echo $chamados_abertos_hoje?></td>
						</tr>
						<tr>
							<td width="252" align="right">
							Fechados  <?echo invertedata($data)?>:&nbsp;&nbsp; </td>
							<td width="643">
							<?echo $chamados_fechados_hoje?></td>
						</tr>
                        	<tr>
							<td width="252" align="right">
							Fechados Com Nota: <?echo invertedata($data)?>:&nbsp;&nbsp; </td>
							<td width="643">
							<?echo $chamado_fechado_com_nota?></td>
						</tr>

						<tr>
							<td width="252" align="right">
							&nbsp;</td>
							<td width="643">
							&nbsp;</td>
						</tr>
						<tr>
							<td width="252" align="right">
							Satisfação Fechados <?echo invertedata($data)?>:&nbsp;&nbsp; </td>
							<td width="643">
							<?echo $media_nota_data?></td>
						</tr>
						<tr>
							<td width="252" align="right">
							Maior Nota <?echo invertedata($data)?>:&nbsp;&nbsp; </td>
							<td width="643">
							<?Echo $maior_nota_data?></td>
						</tr>
						<tr>
							<td width="252" align="right">
							Menor Nota  <?echo invertedata($data)?>:&nbsp;&nbsp; </td>
							<td width="643">
							<?Echo $menor_nota_data?></td>
						</tr>
						<tr>
							<td width="252" align="right">
							&nbsp;</td>
							<td width="643">
							&nbsp;</td>
						</tr>
						<tr>
							<td width="252" align="right">
							Satisfação Geral até a data <?echo invertedata($data)?>:&nbsp;&nbsp; </td>
							<td width="643">
							<?echo $media_nota_ate_data?></td>
						</tr>
						<tr>
							<td width="252" align="right">
							&nbsp;</td>
							<td width="643">
							&nbsp;</td>
						</tr>
						<tr>
							<td width="252" align="right">
							&nbsp;Total de acessos <?echo invertedata($data)?>:&nbsp;&nbsp; </td>
							<td width="643">
							 <?echo $acessos_ate_data ?></td>
						</tr>
						<tr>
							<td width="252" align="right">
							&nbsp;Acessos dia anterior <?echo invertedata($data_1)?>:&nbsp;&nbsp; </td>
							<td width="643">
							<?echo $acessos_ate_data_1?></td>
						</tr>
						<tr>
							<td width="252" align="right">
							&nbsp;</td>
							<td width="643">
							&nbsp;</td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>
<div align="center">
	<table border="0" width="60" cellspacing="0" cellpadding="0" bordercolor="#000000">
		<tr>
            <?
              $checa = mysql_query("SELECT count(*) TOTAL FROM  sgc_chamado WHERE date_format(data_criacao,'%Y-%m-%d') >= INTERVAL 1 DAY + '$data'") or print(mysql_error());
              while($dados=mysql_fetch_array($checa)){
                $amanha = $dados['TOTAL'];
              }
               $checa = mysql_query("SELECT count(*) TOTAL FROM  sgc_chamado WHERE date_format(data_criacao,'%Y-%m-%d') <= INTERVAL -1 DAY + '$data'") or print(mysql_error());
              while($dados=mysql_fetch_array($checa)){
                $ontem = $dados['TOTAL'];
              }
            ?>
		
			<td>&nbsp;
            <?
            if($ontem>0){
            ?>
            <a href="?action=rel_rapido.php&acao_int=gerar&data=<?Echo $data_1?>&gerencia=NAO&id_item=<?echo $id_item?>"><font color="#000000"><span style="text-decoration: none">&lt;&lt;</span></font></a>&nbsp;&nbsp;
            <?
            }
            ?>
    		</td>
			<td>&nbsp;</td>

    		<td>&nbsp;
            <?
            if($amanha>0){
            ?>
            <a href="?action=rel_rapido.php&acao_int=gerar&data=<?Echo $data_0?>&gerencia=NAO&id_item=<?echo $id_item?>"><font color="#000000"><span style="text-decoration: none">&gt;&gt;</span></font></a>&nbsp;&nbsp;
            <?
            }
            ?>

    		</td>
		</tr>
	</table>
</div>
<?
if($gerencia=="SIM"){

$data_invertida=invertedata($data);
$data_invertida_1=invertedata($data_1);

$mensagem="<p><font face='Courier New'  size='2'>
****************** REALATÓRIO SISGAT $data_invertida *********************<BR>

Total Chamados até $data_invertida............: $total_abertos_ate_hoje<BR>
Total Fechados Chamados até $data_invertida...: $total_abertos_ate_hoje_fechado<BR><BR>

Criados $data_invertida.......................: $chamados_abertos_hoje<BR>
Fechados $data_invertida......................: $chamados_fechados_hoje<BR>
Fechados Com Nota $data_invertida.............: $chamado_fechado_com_nota<BR><BR>

Satisfação Fechados $data_invertida...........: $media_nota_data%<BR>
Maior Nota $data_invertida....................: $maior_nota_data<BR>
Menor Nota $data_invertida....................: $menor_nota_data<BR><BR>

Satisfação Geral até a data $data_invertida...: $media_nota_ate_data%<BR><BR>

Total de acessos $data_invertida..............: $acessos_ate_data<BR>
Acessos dia anterior $data_invertida_1..........: $acessos_ate_data_1<BR><BR>

---------------------------------------------------------------------
</font></p>
";


if(atributo('atributo10')=="ON"){

  $emails_gerencia = atributo('atributo24');
  $amails_gerencia = split ('[;]',$emails_gerencia);


echo "Enviador por e-mail para: <BR>";
  foreach($amails_gerencia as $valor){
echo "$valor <BR>";


   $email=send_mail_smtp("SISGAT - Relatório diário de chamados - $data_invertida"
                         ,"$mensagem"
                         ,"$mensagem"
                         ,$valor
                         ,tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","email","")
                         );

  }

 }
}
?>

<?



}
elseif($acao_int=="editar"){

}
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
