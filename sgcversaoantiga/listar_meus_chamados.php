<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Seus Chamados";
$titulo_listar="Horários Já Cadastrados";
$id_item=$_GET['id_item'];





if(!isset($acao_int)){

include("conf/Pagina.class.php");

$id_usuario=$_POST['idus'];

if($_POST['situacao']==null || $_POST['situacao']==""){
  $situacao=$_GET['situacao'];
}else{
  $situacao=$_POST['situacao'];
}


if($situacao==null || $situacao==""){
 $situacao="Todos";
}


?>
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Somente Chamados para EU - ANALÍSTA :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center" style="background-color: #FFFFFF">
					<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#DFDFDF" style="border-collapse: collapse">
                        <form method="POST" action="sgc.php?action=listar_meus_chamados.php&id_item=<?echo $id_item?>" >
        					<p align="left"><select size="1" name="situacao" onchange="this.form.submit();">
                          <?
                          if($situacao=="Todos"){
                          ?>
                          <option>Todos</option>
						  <option>Abertos</option>
		    	          <option>Fechados e Concluidos</option>
                          <?
                          }elseif($situacao=="Abertos"){
                          ?>
						  <option>Abertos</option>
                          <option>Todos</option>
		    	          <option>Fechados e Concluidos</option>
                          <?
                          }elseif($situacao=="Fechados e Concluidos"){
                          ?>
                          <option>Fechados e Concluidos</option>
                		  <option>Abertos</option>
                          <option>Todos</option>
                          <?
                          }else{
                          ?>
                          <option>Todos</option>
     					  <option>Abertos</option>
		    	          <option>Fechados e Concluidos</option>
		    	          <?
                          }
                          ?>
                            </select></p>
						</form>


                        <tr>
							<td width="48" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>ID</b></td>
							<td width="74" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Suporte</b></td>
							<td width="400" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Descrição Resumida</b></td>
							<td width="81" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Usuário</b></td>
							<td width="62" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Prioridade</b></td>
							<td width="130" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Situação</b></td>
							<td width="140" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Data</b></td>
							<td width="110" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">&nbsp;</td>
						</tr>
						<?


if($situacao!="Todos"){
   if($situacao=="Abertos"){
      $adendo=" AND ch.status not in ('Fechado','Concluido') ";
   }else{
      $adendo=" AND ch.status in ('Fechado','Concluido') ";
   }
}else{
   $adendo="";
}

$sql= mysql_query("SELECT
  count(*) t
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
    ch.id_suporte = $idusuario
and ch.quem_criou = cri.id_usuario
and ch.id_suporte = ana.id_usuario
$adendo
and ana.id_unidade = uni_ana.codigo
and ana.id_departamento = dep_ana.id_departamento
and cri.id_unidade = uni_cri.codigo
and cri.id_departamento = dep_cri.id_departamento
and sla_s.id_sla_service = ch.id_sla_service
and sla_a.id_sla_analista = (SELECT prioridade FROM sgc_historico_chamado WHERE id_chamado = ch.id_chamado order by data_criacao desc limit 1)

");
    $dados=mysql_fetch_array($sql);
    $total=$dados['t'];


    $pagina = new Pagina();
    $pagina->setLimite(18);

    $totalRegistros = $total;
	$linkPaginacao ="?action=listar_meus_chamados.php&situacao=$situacao&id_item=$id_item";



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
    ch.id_suporte = $idusuario
and ch.quem_criou = cri.id_usuario
and ch.id_suporte = ana.id_usuario
$adendo
and ana.id_unidade = uni_ana.codigo
and ana.id_departamento = dep_ana.id_departamento
and cri.id_unidade = uni_cri.codigo
and cri.id_departamento = dep_cri.id_departamento
and sla_s.id_sla_service = ch.id_sla_service
and sla_a.id_sla_analista = (SELECT prioridade FROM sgc_historico_chamado WHERE id_chamado = ch.id_chamado order by data_criacao desc limit 1)

order by ch.status !='Fechado' desc,ch.id_chamado desc , ch.status, data_criacao

limit ".$pagina->getPagina($_GET['pagina']).", ".$pagina->getLimite());

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

if($situacao=="Fechado"){
$checa_tempo = mysql_query("
select
 id_chamado
,if(
if
(c.tempo_gasto<0 or c.tempo_gasto is null
,time_to_sec((SELECT  TIMEDIFF(ch.data_criacao,c.data_criacao) FROM sgc_historico_chamado ch where ch.id_historico = c.id_linha_historico))
,c.tempo_gasto
)>86400,
CONCAT(
floor(time_to_sec(if
(c.tempo_gasto<0 or c.tempo_gasto is null
,(SELECT  TIMEDIFF(ch.data_criacao,c.data_criacao) FROM sgc_historico_chamado ch where ch.id_historico = c.id_linha_historico)
,SEC_TO_TIME(c.tempo_gasto)))/86400)
,
if(
floor(time_to_sec(if
(c.tempo_gasto<0 or c.tempo_gasto is null
,(SELECT  TIMEDIFF(ch.data_criacao,c.data_criacao) FROM sgc_historico_chamado ch where ch.id_historico = c.id_linha_historico)
,SEC_TO_TIME(c.tempo_gasto)))/86400)>1,' dias ',' dia ')

,SEC_TO_TIME(ABS((FLOOR(TIME_TO_SEC(if
(c.tempo_gasto<0 or c.tempo_gasto is null
,(SELECT  TIMEDIFF(ch.data_criacao,c.data_criacao) FROM sgc_historico_chamado ch where ch.id_historico = c.id_linha_historico)
,SEC_TO_TIME(c.tempo_gasto)))/86400)*86400)
-
TIME_TO_SEC(if
(c.tempo_gasto<0 or c.tempo_gasto is null
,(SELECT  TIMEDIFF(ch.data_criacao,c.data_criacao) FROM sgc_historico_chamado ch where ch.id_historico = c.id_linha_historico)
,SEC_TO_TIME(c.tempo_gasto))))))

,
if
(c.tempo_gasto<0 or c.tempo_gasto is null
,(SELECT  TIMEDIFF(ch.data_criacao,c.data_criacao) FROM sgc_historico_chamado ch where ch.id_historico = c.id_linha_historico)
,SEC_TO_TIME(c.tempo_gasto))

)Tempo


from sgc_chamado c where c.status = 'Fechado'
and c.id_chamado ='$id_chamado'

") or print(mysql_error());
while($dados_1=mysql_fetch_array($checa_tempo)){
      $espera = $dados_1['Tempo'];
 }
}

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
                                      if($situacao=="Fechado" ){
                                        $cor_linha="bgcolor='#CCF3A0'";
                                      }elseif($situacao=="Concluido"){
                                        $cor_linha="bgcolor='#CCF3E9'";
                                      }elseif($situacao=="Aguardando Resposta - Usuário" or $situacao=="Suspenso") {
                                        $cor_linha="bgcolor='#F1E11D'";
                                      }else{
                                        $cor_linha=null;

                                      }


                     	?>
						<tr>
							<td width="48" height="23" <?echo $cor_linha?> style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center" bgcolor="#EEEEEE"><?echo $id_chamado?></td>
							<td width="74" height="23" <?echo $cor_linha?> style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo $suporte?></td>
							<td width="400" height="23" <?echo $cor_linha?> style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" align="center" bgcolor="#EEEEEE"><p align="left">&nbsp;<a href="?action=vis_chamado.php&id_chamado=<? echo $id_chamado?>"><font color="#000000"><?echo $descricao?></font></a></td>
							<td width="81" height="23" <?echo $cor_linha?> style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo $nome_criador?></td>
							<td width="62" height="23" bgcolor="<?echo $cor?>" style="color: #FFFFFF; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo $desc_sla?></td>
							<td width="130" height="23" <?echo $cor_linha?> style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" align="center"><?echo $situacao=tabelainfo($id_chamado,"sgc_historico_chamado","situacao","id_chamado"," order by id_historico desc limit 1 ")?></td>
							<td width="140" height="23" <?echo $cor_linha?> style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center" bgcolor="#EEEEEE"><?echo $data_criacao?></td>
							<td width="100" height="23" <?echo $cor_linha?> style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><p align="center"><?echo $espera?></td>
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
