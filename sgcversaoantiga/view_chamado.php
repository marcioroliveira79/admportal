<?php
OB_START();
session_start();
include("conf/conecta.php");
include("conf/funcs.php");

$mysql=new sgc;
       $mysql->conectar();

//------------------------------------------------------------//
  $id_usuario = $_GET['id_usuario'];
  $id_chamado = $_GET['id_chamado'];
//------------------------------------------------------------//

$checa = mysql_query("SELECT
  ch.id_chamado
, ch.titulo
, ch.id_suporte
, ch.id_categoria
, ch.id_usuario dono
, ch.descricao
, al.id_area_locacao
, al.descricao desc_area
, ch.obs
, hc.id_categoria
, us1.id_usuario id_analista
, (SELECT descricao FROM sgc_categoria where id_categoria= hc.id_categoria)categoria
, hc.situacao
, us.primeiro_nome
, slaa.descricao prioridade
, date_format(hc.data_criacao,'%d/%m/%y %h:%i')ultima_atualizacao
, date_format(ch.data_criacao,'%d/%m/%y %h:%i')data_criacao
, time_to_sec(TIMEDIFF(sysdate('%Y-%m-%d %H:%i:%s'),ch.data_criacao))segundo
, TIMEDIFF(sysdate('%Y-%m-%d %H:%i:%s'),ch.data_criacao)Espera
, concat(us1.primeiro_nome,' ',us1.ultimo_nome,' - ',dp.descricao,' - ',un.descricao)suporte
, concat(us.primeiro_nome,' ',us.ultimo_nome,' - ',dp1.descricao,' - ',un1.descricao)usuario
, us.email email_usuario
, concat('(',us.ddd,')',' ',us.telefone,' Ramal: ',us.ramal)telefone
FROM
  sgc_chamado ch
, sgc_historico_chamado hc
, sgc_usuario us
, sgc_usuario us1
, sgc_sla_analista_usuario slaa
, sgc_unidade un
, sgc_centro_custo cc
, sgc_departamento dp
, sgc_unidade un1
, sgc_centro_custo cc1
, sgc_departamento dp1
, sgc_area_locacao al
, sgc_associacao_area_analista aa

where hc.id_chamado=$id_chamado
and hc.id_chamado = ch.id_chamado
and us.id_usuario = ch.quem_criou
and slaa.id_sla_analista = hc.prioridade
and us1.id_usuario = hc.id_suporte

and us1.id_unidade = un.codigo
and us1.id_departamento = dp.id_departamento
and us1.id_centro = cc.id_centro

and us.id_unidade = un1.codigo
and us.id_departamento = dp1.id_departamento
and us.id_centro = cc1.id_centro

and aa.id_analista=ch.id_suporte
and al.id_area_locacao= ch.id_area_locacao
and aa.id_area = al.id_area_locacao
and hc.id_historico=ch.id_linha_historico

") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
$id_chamado         = $dados['id_chamado'];
$data_chamado       = $dados['data_criacao'];
$ultima_atualizacao = $dados['ultima_atualizacao'];
$suporte            = $dados['suporte'];
$prioridade         = $dados['prioridade'];
$situacao           = $dados['situacao'];
$usuario            = $dados['usuario'];
$email              = $dados['email_usuario'];
$telefone           = $dados['telefone'];
$titulo             = $dados['titulo'];
$obs                = $dados['obs'];
$descricao_cha      = $dados['descricao'];
$id_area_locacao    = $dados['id_area_locacao'];
$desc_area          = $dados['desc_area'];
$dono               = $dados['dono'];
$analista_ch        = $dados['id_suporte'];
$id_analista        = $dados['id_analista'];
$id_categoria       = $dados['id_categoria'];
$categoria       = $dados['categoria'];
$count++;
}

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
    <STYLE type="text/css">

	BODY {background: #FFFFFF ; color: black;}

	a:link {text-decoration: none; color: #363636;}
	a:visited {text-decoration: none; color: #363636;}
	a:active {text-decoration: none; color: #363636;}
	a:hover {text-decoration: underline; color: #363636;}

	a.kbase:link {text-decoration: underline; font-weight: bold; color: #000000;}
	a.kbase:visited {text-decoration: underline; font-weight: bold; color: #000000;}
	a.kbase:active {text-decoration: underline; font-weight: bold; color: #000000;}
	a.kbase:hover {text-decoration: underline; font-weight: bold; color: #000000;}


	table.border {background: #8C8984; color: black;}
	td {color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	tr {color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	td.back {background: #FFFFFF;}
	td.back2 {background: #EEEEEE;}

	td.date {background: #EEEEEE; font-family: "Arial"; font-size: 12px; color: #000000;}

	td.hf {background: #D0D0D0; font-family: "Arial"; font-size: 12px; color: #000000;}

	a.hf:link {text-decoration: none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #000000;}

	a.hf:visited {text-decoration:none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #000000;}

	a.hf:active {text-decoration: none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #000000;}

	a.hf:hover {text-decoration: underline; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #000000;}

	td.info {background: #336666; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #FFFFFF;}

	a.info:link {text-decoration: none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #FFFFFF;}

	a.info:visited {text-decoration:none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #FFFFFF;}

	a.info:active {text-decoration: none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #FFFFFF;}

	a.info:hover {text-decoration: underline; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #FFFFFF;}

	select, option, textarea, input {font-family: Verdana, arial, helvetica, sans-serif; font-size:	11px; background: #EEEEEE; color: #000000;}

	td.cat {background: #EEEEEE; font-family: "Arial"; font-size: 12px; color: #000000;}

	td.stats {background: #EEEEEE; font-family: "Arial"; font-size: 10px; color: #000000;}

	td.error {background: #EEEEEE; color: #ff0000; font-family: "Arial"; font-size: 12px;}

	td.subcat {background: #EEEEEE; color: #000000; font-family: "Arial"; font-size: 12px;}



	input.box {border: 0px;}

	table.border2 {background: #6974b5;}
	td.install {background:#dddddd; color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	table.install {background: #000099;}
	td.head	{background:#6974b5; color: #ffffff; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	a.install:link {text-decoration: none; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #6974b5;}
	a.install:visited {text-decoration:none; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #6974b5;}
	a.install:active {text-decoration: none; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000099;}
	a.install:hover {text-decoration: underline; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000099;}

</STYLE>
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Chamado # <?echo $id_chamado?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="right">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="131" height="23">
							<p align="right">Usuário:</td>
							<td width="868" height="23">&nbsp;<?echo $usuario?></td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="131" height="23">
							<p align="right">Data Chamado:</td>
							<td width="868" height="23">&nbsp;<?echo $data_chamado?></td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="131" height="23">
							<p align="right">Prioridade:</td>
							<td width="868" height="23">&nbsp;<?echo $prioridade?></td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="131" height="23">
							<p align="right">Categoria: </td>
							<td width="868" height="23">&nbsp;<?echo $categoria?></td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="131" height="23">
							<p align="right">Analista:</td>
							<td width="868" height="23">&nbsp;<?echo $suporte?></td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="131" height="23">
							<p align="right">Status:</td>
							<td width="868" height="23">&nbsp;<?echo $situacao?></td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="131" height="23">&nbsp;</td>
							<td width="868" height="23">&nbsp;</td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td class="info" width="999" height="23" colspan="2">
							<p align="center">Título</td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="999" height="23" colspan="2">
							<p align="center">&nbsp;<?echo $titulo?></td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="131" height="23">&nbsp;</td>
							<td width="868" height="23">&nbsp;</td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td class="info" width="999" height="23" colspan="2">
							<p align="center">Descrição</td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="999" height="23" colspan="2">
							<p align="center">&nbsp;<?echo $descricao_cha?></td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="131" height="23">&nbsp;</td>
							<td width="868" height="23">&nbsp;</td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td class="info" width="999" height="23" colspan="2">
							<p align="center">Atualização</td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
                        	<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="999" height="23" colspan="2">

                            						<?

                   $checa = mysql_query("
                  select hc.acao
                    , concat(us.primeiro_nome,' ',us.ultimo_nome)usuario
                    , concat(us1.primeiro_nome,' ',us1.ultimo_nome)usuario_linha
                    , date_format(hc.data_criacao,'%d/%m/%y %h:%i')data_criacao
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
                       $usuario_linha = $dados['usuario_linha'];
                       $data = $dados['data_criacao'];
                       $atualizacao= $dados['atualizacao'];

                        $t++;
                        if ($t % 2 == 0) {$cor="";}
                        else             {$cor="#FFFFFF";}
                           ?>
                        	<table border="0" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse">
								<tr>
									<td bgcolor="#FFFFFF"><p align="left"><font size="1"><b>&nbsp;&nbsp;</b><?echo $data?> por <?echo $usuario_linha?></font></td>
								</tr>
								<tr>
									<td bgcolor="#FFFFFF"><i>&nbsp;&nbsp;<?echo $atualizacao?></i></td>
								</tr>
      						</table>
      						<br>
                            <?
                            }
                            ?>
    						</td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
<?


?>
