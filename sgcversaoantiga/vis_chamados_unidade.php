<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Seus Chamados";
$titulo_listar="Horários Já Cadastrados";
$id_item=$_GET['id_item'];


$id_unidade_usuario=unidade_usuario($idusuario);
$sigla_unidade=tabelainfo($id_unidade_usuario,"sgc_unidade","sigla","codigo","");




if(!isset($acao_int)){
if(chamado_fechado_falta_enquete($idusuario)!=null){
  $id_chamado_enquete=chamado_fechado_falta_enquete($idusuario);
  header("Location: ?action=vis_chamado.php&acao_int=enquete&id_chamado=$id_chamado_enquete");
}


?>

<table class="border" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
<form method="POST" id="form1" action="sgc.php?action=vis_chamados_unidade.php&acao_int=visualizar" ">
							<tr>
								<td>
								<table border="0" cellpadding="5" cellspacing="1" width="100%">
									<tr>
										<td class="info" colspan="1" align="center">
										<b>Visualizar todos Chamados da sua Unidade</b></td>
									</tr>
									<tr>
										<td class="cat" align="right">
										<font size="1">
										<table border="0" width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												<p align="center">
												Escolha o status do chamado</td>
												<td width="40">&nbsp;</td>
											</tr>
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												&nbsp;</td>
												<td width="40">&nbsp;</td>
											</tr>
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												<p align="center">
												<select size="1" name="situacao" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                              <option ></option>
                              <option value="Todos">Todos</option>
    						  <option value="Enviado Para Analista">Enviado Analista</option>
                              <option value="Aceito - Em Andamento">Aceito - Em Andamento</option>
                              <option value="Concluido">Concluido</option>
                              <option value="Fechado">Fechado</option>
                              <option value="Suspenso">Suspenso</option>
     						  <option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>
						</select></td>
												<td width="40">&nbsp;</td>
											</tr>
												<tr>
												<td width="43">&nbsp;</td>
												<td>
												&nbsp;</td>
												<td width="40">&nbsp;</td>
											</tr>
														<tr>
												<td width="43">&nbsp;</td>
												<td>
												<p align="center">
					<select size="1" name="area_atuacao" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" >
                              <option ></option>
                              <option value="Todos">Todos</option>
                              <?
                         $checa = mysql_query("select id_area_locacao,descricao from  sgc_area_locacao") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_objeto = $dados['id_area_locacao'];
                                    $ler_descricao_objeto = $dados['descricao'];


                         ?>
                         <option value="<?echo $id_objeto?>"><?echo $ler_descricao_objeto?></option>
                         <?
                         }
                         ?>
						</select></td>
												<td width="40">&nbsp;</td>
											</tr>
          <tr>
												<td width="43">&nbsp;</td>
												<td>
												<p align="center">
												<input type="Submit" value="Enviar" name="B1"></td>
												<td width="40">&nbsp;</td>
											</tr>

										</table>
										</td>
									</tr>
        						</table>
		    	    			</td>
			    			</tr>

                       </form>
						</table>
						<BR>
</head>
<body>

<?
$count=0;
$colors[0]="'#A5DBFC',";
$colors[1]="'#CDE8FA',";
$colors[2]="'#D9F0FA',";
$colors[3]="'#0099F9',";
$colors[4]="'#1BA4F9',";
$colors[5]="'#41B2FA',";
$colors[6]="'#63C1FA',";
$colors[7]="'#83CDFA'";


$checa = mysql_query("
SELECT status,count(status)total FROM sgc_chamado ch
where 1=1 and ch.id_unidade = $id_unidade_usuario
group by status
")or print(mysql_error());
  while($dados=mysql_fetch_array($checa)){
  $status= $dados['status'];
  $status= removeAccentuation($status);
  $total= $dados['total'];
  $grafico[$count]="['$status',$total],";
  $count++;
 }



if($count>1){

?>


<div id="graph" align="center">Loading graph...</div>

<script type="text/javascript">
	var myData =
    new Array(
    <?
    foreach ($grafico as $v) {
    $count--;
    if($count==0){
      echo substr("$v", 0, -1);
    }else{
      echo $v;
    }
    $count_colors++;
    }
    ?>

    );


	var colors =
    [
    <?
    while($count_colors!=0){
    echo $colors[$count_colors];
    $count_colors--;
    }

    ?>
    ];
	var myChart = new JSChart('graph', 'pie');
	myChart.setDataArray(myData);
	myChart.colorizePie(colors);
	myChart.setTitle('Status dos Chamados da Unidade <?echo "$sigla_unidade"?> - <?echo data_with_hour(datahoje('datahora')); ?>');
	myChart.setTitleColor('#003300');
	myChart.setTitleFontSize(11);
	myChart.setTextPaddingTop(30);
	myChart.setPieUnitsColor('#003300');
	myChart.setPieValuesColor('#003300');
	myChart.setSize(616, 321);
	myChart.setPiePosition(308, 190);
	myChart.setPieRadius(85);
	myChart.setBackgroundImage('imgs/chart_bg.jpg');
	myChart.draw();
</script>

</body>
</html>
<?
}elseif($count==0){
?>
<div align="center">
	<table border="0" width="616" cellspacing="0" cellpadding="0" height="321">
		<tr>
			<td background="imgs/chart_bg.jpg" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>
					<p align="center"><font size="2" face="Arial">Não existe
					informações para sua unidade</font></td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
			</table>
			<p>&nbsp;</p>
			<p>&nbsp;</td>
		</tr>
	</table>
</div>
<?
}elseif($count==1){
?>
<div align="center">
	<table border="0" width="616" cellspacing="0" cellpadding="0" height="321">
		<tr>
			<td background="imgs/chart_bg.jpg" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>
					<p align="center"><font size="2" face="Arial">Chamado(s) 100% <?echo $status?> - Total: <?echo $total?> </font></td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td width="14">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
			</table>
			<p>&nbsp;</p>
			<p>&nbsp;</td>
		</tr>
	</table>
</div>
<?


}





}elseif($acao_int=="visualizar"){


include("conf/Pagina.class.php");


if($_POST['area_atuacao']==null){

if($_GET['area_atuacao']=="Todos" or $_GET['area_atuacao']==null){

 $adendo_0=null;

}else{

 $area_env=$_GET['area_atuacao'];
 $adendo_0=" and id_area_locacao=$area_env";


}


}else{

if($_POST['area_atuacao']=="Todos" or $_POST['area_atuacao']==null){

 $adendo_0=null;

}else{

 $area_env=$_POST['area_atuacao'];
 $adendo_0="and id_area_locacao=$area_env";

}


}


//----------------------------------------------------------------------//


if($_POST['situacao']==null){




if($_GET['situacao']=="Todos" or $_GET['situacao']==null){

 $adendo=null;

}else{

 $status_env=$_GET['situacao'];
 $adendo=" and status='$status_env'";


}



}else{

if($_POST['situacao']=="Todos" or $_POST['situacao']==null){

 $adendo=null;

}else{

 $status_env=$_POST['situacao'];
 $adendo=" and status='$status_env'";

}


}
$sql= mysql_query("SELECT count(*) t FROM  sgc_chamado ch where 1=1 $adendo $adendo_0 and ch.id_unidade = $id_unidade_usuario ");
    $dados=mysql_fetch_array($sql);
    $total=$dados['t'];


    $pagina = new Pagina();
    $pagina->setLimite(20);
    
    $totalRegistros = $total;
	$linkPaginacao ="?action=vis_chamados_unidade.php&acao_int=visualizar&id_item=$id_item&situacao=$status_env&area_atuacao=$area_env";


?>
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Todos os Chamados do Sistema - <?echo $sigla_unidade?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center" style="background-color: #FFFFFF">
					<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#DFDFDF" style="border-collapse: collapse">
   			<tr>
							<td width="48" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>ID</b></td>
							<td width="74" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Suporte</b></td>
							<td width="425" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Descrição Resumida</b></td>
							<td width="121" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Usuário</b></td>
							<td width="62" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Prioridade</b></td>
							<td width="158" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Situação</b></td>
							<td width="120" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Data</b></td>
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
, date_format(ch.data_criacao,'%d/%m/%y %h:%i:%s')data_criacao




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

    ch.quem_criou = cri.id_usuario
and ch.id_suporte = ana.id_usuario
and ana.id_unidade = uni_ana.codigo
and ana.id_departamento = dep_ana.id_departamento
and cri.id_unidade = uni_cri.codigo
and cri.id_departamento = dep_cri.id_departamento
and sla_s.id_sla_service = ch.id_sla_service
$adendo
$adendo_0
and ch.id_unidade = $id_unidade_usuario
and sla_a.id_sla_analista =  (SELECT prioridade FROM sgc_historico_chamado WHERE id_historico = ch.id_linha_historico )

order by  ch.status in ('Aceito - Em Andamento','Enviado Para Analista') desc,ch.status='Aguardando Resposta - Usuário' desc, ch.status='Suspenso' desc, ch.data_criacao limit ".$pagina->getPagina($_GET['pagina']).", ".$pagina->getLimite());
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
                                      if($situacao=="Fechado" or $situacao=="Concluido"){
                                        $cor_linha="bgcolor='#CCF3A0'";


                                      }elseif($situacao=="Aguardando Resposta - Usuário" or $situacao=="Suspenso") {
                                        $cor_linha="bgcolor='#F1E11D'";
                                      }else{
                                        $cor_linha=null;

                                      }



                     	?>
    	<tr>
							<td width="48" height="23"  <?echo $cor_linha?>    style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center" bgcolor="#EEEEEE"><?echo $id_chamado?></td>
							<td width="74" height="23"  <?echo $cor_linha?>    style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo $suporte?></td>
							<td width="425" height="23" <?echo $cor_linha?>    style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" align="center" bgcolor="#EEEEEE"><p align="left">&nbsp;<a href="?action=vis_chamado.php&id_chamado=<? echo $id_chamado?>"><font color="#000000"><?echo $descricao?></a></font></td>
							<td width="121" height="23"  <?echo $cor_linha?>    style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo $nome_criador?></td>
							<td width="62" height="23" bgcolor="<?echo $cor?>" style="color: #FFFFFF; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo $desc_sla?></td>
							<td width="158" height="23" <?echo $cor_linha?>    style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" align="center"><?echo $situacao=tabelainfo($id_chamado,"sgc_historico_chamado","situacao","id_chamado","  order by id_historico desc limit 1")?></td>
							<td width="120" height="23" <?echo $cor_linha?>    style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center" bgcolor="#EEEEEE"><?echo $data_criacao?></td>
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
<br>
<p align="center">
<?
//----------------Paginador-------------------//

Pagina::configuraPaginacao($_GET['cj'],$_GET['pagina'],$totalRegistros,$linkPaginacao, $pagina->getLimite(), $_GET['direcao']);

//--------------------------------------------//



}
elseif($acao_int=="editar_bd"){


}


}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
