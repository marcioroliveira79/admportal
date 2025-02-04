<?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("conf/funcs.php");

$id_item=$_POST['id_item'];
$id_usuario=$_POST['idus'];

include("conf/conecta.php");
/*
$pg=new sgc_obj;
      $pg->conectar_obj();
*/

$checa_qt = mysql_query("SELECT
cnpj_origem
FROM sgc_nota_xfac where cnpj_origem is not null
group by
cnpj_origem") or print(mysql_error());
              while($dados_qt=mysql_fetch_array($checa_qt)){
                 $cnpj_origem = $dados_qt['cnpj_origem'];
                 $cnpjs.="'".$cnpj_origem."',";
}


$checa_qt = mysql_query("SELECT DATE_FORMAT(data_inclusao, '%d/%m/%Y %H:%i%:%s') as data_inclusao FROM sgc_nota_xfac ORDER BY id DESC limit 1") or print(mysql_error());
                         while($dados_qt=mysql_fetch_array($checa_qt)){
                            $data_inclusao = $dados_qt['data_inclusao'];
}

?>
<table border="1" width="100%" id="table4">
		<tr>
			<td width="505" valign="top">
	<table border="1" width="505" id="table5">
		<tr>
			<td colspan="6" bgcolor="#FFFFFF">
			<p align="center">Hoje - Última Leitura <?echo $data_inclusao?> </td>
		</tr>
		<tr>
    	<td width="40" bgcolor="#FFFFFF">
			<p align="center"><b>UF</b></td>
			<td width="98" align="center" bgcolor="#FFFFFF"><b>&nbsp;Autorizadas</b></td>
			<td width="92" align="center" bgcolor="#FFFFFF"><b>Canceladas</b></td>
			<td align="center" bgcolor="#FFFFFF" width="88"><b>&nbsp;Rejeitadas</b></td>
			<td align="center" width="112" bgcolor="#FFFFFF"><b>Transmitidas</b></td>
			<td align="center" width="106" bgcolor="#FFFFFF"><b>&nbsp;Total</b></td>
		</tr>



<?
$checa_uf = mysql_query("SELECT * FROM sgc_servidores WHERE nfe='ON' AND uf != '' AND status='ON' ORDER BY uf") or print(mysql_error());
         while($dados_uf=mysql_fetch_array($checa_uf)){
              $uf = $dados_uf['uf'];
              $nuf = $dados_uf['nuf'];

$autorizada=0;
$cancelada=0;
$rejeitada=0;
$transmitida=0;
$total=0;
$checa_qt = mysql_query("SELECT
                          uf
                         ,status_nota
                         ,DATE_FORMAT(data_nota, '%d/%m/%Y') AS data_nota
                         ,count(1) as contador
                         FROM sgc_nota_xfac WHERE DATE_FORMAT(data_nota, '%y-%m-%d') = DATE_FORMAT(SYSDATE(), '%y-%m-%d')   AND uf='$uf'
                         GROUP BY uf, status_nota, DATE_FORMAT(data_nota, '%d/%m/%Y')
                         ORDER BY uf, status_nota") or print(mysql_error());
                         while($dados_qt=mysql_fetch_array($checa_qt)){
                            $status = $dados_qt['status_nota'];
                            $data_nota = $dados_qt['data_nota'];
                            $contador = $dados_qt['contador'];

                            if($status=="Autorizada"){
                                  $autorizada=$contador;
                            }elseif($status=="Cancelada"){
                                  $cancelada=$contador;
                            }elseif($status=="Rejeitada"){
                                  $rejeitada=$contador;
                            }elseif($status=="Transmitida"){
                                  $transmitida=$contador;
                            }

}
$total =  $autorizada + $cancelada + $rejeitada + $transmitida;

   if(acesso($id_usuario,86)=="OK"){
     $link="<a href='?action=nfe_conab.php&acao_int=sureg&uf_nfe=$nuf&id_item=$id_item'>
            <font color='#000000'>$uf
			</font></a>";
    }else{
         $link="<font color='#000000'>$uf</font>";
    }

?>


		<tr>
			<td width="40" bgcolor="#FFFFFF">
			<p align="center">
            <?echo $link?>
            </td>
			<td width="98" align="center" bgcolor="#00FF00"><?echo $autorizada?></td>
			<td width="92" align="center" bgcolor="#FFFF00"><?echo $cancelada?></td>
			<td align="center" bgcolor="#FF0000" width="88"><?echo $rejeitada?></td>
			<td align="center" width="112" bgcolor="#00FFFF"><?echo $transmitida?></td>
			<td align="center" width="106" bgcolor="#FFFFFF"><?echo $total?></td>
		</tr>

<?
$tot_aut=$tot_aut+$autorizada;
$tot_can=$tot_can+$cancelada;
$tot_rej=$tot_rej+$rejeitada;
$tot_tran=$tot_tran+$transmitida;
}



$tot_tot=$tot_aut+$tot_can+$tot_rej+$tot_tran;




?>
		<tr>
			<td width="40" bgcolor="#FFFFFF">
			<p align="center"><b>Total</b></td>
			<td width="98" align="center" bgcolor="#FFFFFF"><b><?echo $tot_aut?></b></td>
			<td width="92" align="center" bgcolor="#FFFFFF"><b><?echo $tot_can?></b></td>
			<td align="center" bgcolor="#FFFFFF" width="88"><b><?echo $tot_rej?></b></td>
			<td align="center" width="112" bgcolor="#FFFFFF"><b><?echo $tot_tran?></b></td>
			<td align="center" width="106" bgcolor="#FFFFFF"><b><?echo $tot_tot?></b></td>
		</tr>
	</table>

    <table border="1" width="100%" id="table14">
    <tr>
<?



$checa_qt = mysql_query("
SELECT

CASE WHEN DAYOFWEEK(xf.data_nota) = 2 THEN 'Segunda'
      WHEN DAYOFWEEK(xf.data_nota) = 3 THEN 'Terça'
      WHEN DAYOFWEEK(xf.data_nota) = 4 THEN 'Quarta'
      WHEN DAYOFWEEK(xf.data_nota) = 5 THEN 'Quinta'
      WHEN DAYOFWEEK(xf.data_nota) = 6 THEN 'Sexta'
      WHEN DAYOFWEEK(xf.data_nota) = 7 THEN 'Sábado'
      WHEN DAYOFWEEK(xf.data_nota) = 1 THEN 'Domingo'
END as dia
,count(1) as total

FROM  sgc_nota_xfac xf
WHERE 1=1
AND xf.status_nota='Autorizada'
AND DATE_FORMAT(xf.data_nota,'%v') = DATE_FORMAT(sysdate(),'%v')
GROUP BY dia
ORDER BY DAYOFWEEK(xf.data_nota)
") or print(mysql_error());
                         while($dados_qt=mysql_fetch_array($checa_qt)){
                            $dia = $dados_qt['dia'];
                            $quantidade = $dados_qt['total'];
?>

					<td align="center" bgcolor="#FFFFFF"><b><?echo $dia?></b><BR><?echo $quantidade?></td>



				<?
				}

				?>
			</tr>
			

	</table>
	<table border="1" width="505" id="table15">
		<tr>
			<td colspan="6" bgcolor="#FFFFFF">
			<p align="center">Hoje - SAAGRA</td>
		</tr>
		<tr>
    	<td width="174" bgcolor="#FFFFFF">
			<p align="center"><b>Município</b></td>
			<td width="83" align="center" bgcolor="#FFFFFF"><b>&nbsp;Autorizadas</b></td>
			<td width="77" align="center" bgcolor="#FFFFFF"><b>Canceladas</b></td>
			<td align="center" bgcolor="#FFFFFF" width="76"><b>&nbsp;Rejeitadas</b></td>
			<td align="center" width="60" bgcolor="#FFFFFF"><b>&nbsp;Total</b></td>
		</tr>



<?
/*
$autorizada1=0;
$cancelada1=0;
$rejeitada1=0;
$transmitida1=0;
$total1=0;

$checa_qt = pg_query("SELECT
 COUNT(*) as contador
,CASE WHEN le.status_nfe='CAN' THEN 'Cancelada'
      WHEN le.status_nfe='IMP' OR le.status_nfe='RIM' THEN 'Autorizada'
      WHEN le.status_nfe='REJ' THEN 'Rejeitada'
      END as status
,to_char(msg.data_emissao,'yyyy-mm-dd') as data_emissao
,msg.municipio
,msg.uf_receptora
FROM
  eng_mensagem_eletronica msg
, nf_nfe nf
, nf_lote_nfe nfe
, nf_lote nfl
, eng_lote_mensagem_eletronica le
, nfe_resultado_processamento res
WHERE  1=1
AND nf.id_mensagem_eletronica = msg.id_mensagem_eletronica
AND nfe.id_lote_mensagem_eletronica = nf.id_lote_me_vigente
AND nfl.id_lote = nfe.lote_id
AND le.id_lote_mensagem_eletronica = nfe.id_lote_mensagem_eletronica
AND res.codigo = le.retorno_nfe
AND to_char(msg.data_emissao,'yyyy-mm-dd')  = to_char(now(),'yyyy-mm-dd')
AND msg.cnpj_emit NOT IN (".$cnpjs."'0')
GROUP BY
 status
,data_emissao
,msg.municipio
,msg.uf_receptora
ORDER BY  msg.uf_receptora, msg.municipio
");

                           while($dados_qt=pg_fetch_array($checa_qt)){
                            $uf = $dados_qt['uf_receptora'];
                            $municipio = $dados_qt['municipio'];
                            $contador1 = $dados_qt['contador'];
                            $status1 = $dados_qt['status'];


                            if($status1=="Autorizada"){
                                  $autorizada1=$contador1;
                            }elseif($status1=="Cancelada"){
                                  $cancelada1=$contador1;
                            }elseif($status1=="Rejeitada"){
                                  $rejeitada1=$contador1;
                            }




$total1 =  $autorizada1 + $cancelada1 + $rejeitada1 ;
*/

?>


		<tr>
			<td width="174" bgcolor="#FFFFFF">
			<p align="left"> <?echo "$municipio - $uf"?> </td>
			<td width="83" align="center" bgcolor="#00FF00"><?echo $autorizada1?></td>
			<td width="77" align="center" bgcolor="#FFFF00"><?echo $cancelada1?></td>
			<td align="center" bgcolor="#FF0000" width="76"><?echo $rejeitada1?></td>
			<td align="center" width="60" bgcolor="#FFFFFF"><?echo $total1?></td>
		</tr>

<?
/*
$tot_aut1=$tot_aut1+$autorizada1;
$tot_can1=$tot_can1+$cancelada1;
$tot_rej1=$tot_rej1+$rejeitada1;
}



$tot_tot1=$tot_aut1+$tot_can1+$tot_rej1;
*/



?>
		<tr>
			<td width="174" bgcolor="#FFFFFF">
			<p align="center"><b>Total</b></td>
			<td width="83" align="center" bgcolor="#FFFFFF"><b><?echo $tot_aut1?></b></td>
			<td width="77" align="center" bgcolor="#FFFFFF"><b><?echo $tot_can1?></b></td>
			<td align="center" bgcolor="#FFFFFF" width="76"><b><?echo $tot_rej1?></b></td>
			<td align="center" width="60" bgcolor="#FFFFFF"><b><?echo $tot_tot1?></b></td>
		</tr>
	</table>

			</td>
			<td valign="top">
			<table border="1" width="100%" id="table8">
				<tr>
					<td bgcolor="#FFFFFF" colspan="4">
					<p align="center"><b>Operações autorizadas e realizadas hoje</b></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" width="7%"><b>Cód</td>
					<td bgcolor="#FFFFFF" width="69%"><b>Nome</td>
					<td bgcolor="#FFFFFF" width="7%">
					<p align="center"><b>E/S</b></td>
					<td bgcolor="#FFFFFF" width="12%">
					<p align="center"><b>NF´s</b></td>
				</tr>
<?
$checa_qt = mysql_query("
SELECT

 DATE_FORMAT(data_nota, '%d/%m/%Y') as data
,xf.cod_operacao
,xf.entrada_saida
,count(1) as contador
,CASE WHEN  LENGTH(xf.nome_operacao) > 40 THEN
  CONCAT(SUBSTRING(xf.nome_operacao FROM 1 FOR 30),'...')
 ELSE
   xf.nome_operacao
 END AS nome_operacao
FROM  sgc_nota_xfac xf
WHERE 1=1
AND xf.status_nota='Autorizada'
AND DATE_FORMAT(data_nota, '%y-%m-%d') = DATE_FORMAT(CURDATE(), '%y-%m-%d')
GROUP BY
 data
,xf.cod_operacao
,nome_operacao
,xf.entrada_saida
ORDER BY contador DESC

") or print(mysql_error());
                         while($dados_qt=mysql_fetch_array($checa_qt)){
                            $data = $dados_qt['data'];
                            $cod_operacao = $dados_qt['cod_operacao'];
                            $nome_operacao = $dados_qt['nome_operacao'];
                            $entrada_saida = $dados_qt['entrada_saida'];
                            $contador = $dados_qt['contador'];
?>
				<tr>
					<td bgcolor="#FFFFFF" width="7%">
					<p align="center"><?echo $cod_operacao?></td>
					<td bgcolor="#FFFFFF" width="69%"><?echo $nome_operacao?></td>
					<td bgcolor="#FFFFFF" width="7%" align="center"><?echo $entrada_saida?></td>
					<td bgcolor="#FFFFFF" width="12%" align="center"><?echo $contador?></td>
				</tr>
<?
}
?>
			</table>
			<table border="1" width="100%" id="table9">
				<tr>
					<td bgcolor="#FFFFFF" colspan="4">
					<p align="center"><b>Operações autorizadas e realizadas ontem</b></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" width="7%"><b>Cód</b></td>
					<td bgcolor="#FFFFFF" width="69%"><b>Nome</b></td>
					<td bgcolor="#FFFFFF" width="7%">
					<p align="center"><b>E/S</b></td>
					<td bgcolor="#FFFFFF" width="12%">
					<p align="center"><b>NF´s</b></td>
				</tr>
<?
$checa_qt = mysql_query("    SELECT


 DATE_FORMAT(data_nota, '%d/%m/%Y') as data
,xf.cod_operacao
,xf.entrada_saida
,count(1) as contador
,CASE WHEN  LENGTH(xf.nome_operacao) > 40 THEN
  CONCAT(SUBSTRING(xf.nome_operacao FROM 1 FOR 30),'...')
 ELSE
   xf.nome_operacao
 END AS nome_operacao
FROM  sgc_nota_xfac xf
WHERE 1=1
AND xf.status_nota='Autorizada'
AND DATE_FORMAT(data_nota, '%y-%m-%d') = DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -1 DAY), '%y-%m-%d')
GROUP BY
 data
,xf.cod_operacao
,nome_operacao
,xf.entrada_saida
ORDER BY contador DESC

") or print(mysql_error());
                         while($dados_qt=mysql_fetch_array($checa_qt)){
                            $data = $dados_qt['data'];
                            $cod_operacao = $dados_qt['cod_operacao'];
                            $nome_operacao = $dados_qt['nome_operacao'];
                            $entrada_saida = $dados_qt['entrada_saida'];
                            $contador = $dados_qt['contador'];
?>
				<tr>
					<td bgcolor="#FFFFFF" width="7%">
					<p align="center"><?echo $cod_operacao?></td>
					<td bgcolor="#FFFFFF" width="69%"><?echo $nome_operacao?></td>
					<td bgcolor="#FFFFFF" width="7%" align="center"><?echo $entrada_saida?></td>
					<td bgcolor="#FFFFFF" width="12%" align="center"><?echo $contador?></td>
				</tr>
<?
}
?>
			</table>
			<table border="0" width="414" id="table11" cellspacing="0" cellpadding="0">
				<tr>
					<td width="203">
			<table border="1" width="207" id="table12">
				<tr>
					<td colspan="3" bgcolor="#FFFFFF">
					<p align="center"><b>NFE´s Conta</b></td>
				</tr>
				<tr>
					<td width="34%" bgcolor="#FFFFFF">
					<p align="center"><b>Hoje</b></td>
					<td width="34%" bgcolor="#FFFFFF"><b>Conta</b></td>
					<td width="22%" bgcolor="#FFFFFF">
					<p align="center"><b>Qtde</b></td>
				</tr>
<?
$checa_qt = mysql_query("
SELECT

 DATE_FORMAT(data_nota, '%d/%m/%Y') as data
,concat('(',xf.cod_programa,'.',xf.cod_atividade,'.',xf.cod_fonte,')') as prog
,count(1) as contador

FROM  sgc_nota_xfac xf
WHERE 1=1
AND xf.status_nota='Autorizada'
AND DATE_FORMAT(data_nota, '%y-%m-%d') = DATE_FORMAT(CURDATE(),'%y-%m-%d')
GROUP BY
 data
,prog
ORDER BY contador DESC , prog

") or print(mysql_error());
                         while($dados_qt=mysql_fetch_array($checa_qt)){
                            $data = $dados_qt['data'];
                            $prog = $dados_qt['prog'];
                            $contador = $dados_qt['contador'];

?>
				<tr>
					<td width="34%" bgcolor="#FFFFFF">
					<p align="center"><?echo $data?></td>
					<td width="34%" bgcolor="#FFFFFF"><?echo $prog?></td>
					<td width="22%" bgcolor="#FFFFFF">
					<p align="right"><?echo $contador?></td>
				</tr>
<?
}
?>
			</table>
					</td>
					<td>
			<table border="1" width="207" id="table13">
				<tr>
					<td colspan="3" bgcolor="#FFFFFF">
					<p align="center"><b>NFE´s Conta</b></td>
				</tr>
				<tr>
					<td width="34%" bgcolor="#FFFFFF">
					<p align="center"><b>Ontem</b></td>
					<td width="34%" bgcolor="#FFFFFF"><b>Conta</b></td>
					<td width="22%" bgcolor="#FFFFFF">
					<p align="center"><b>Qtde</b></td>
				</tr>
<?
$checa_qt = mysql_query("
SELECT

 DATE_FORMAT(data_nota, '%d/%m/%Y') as data
,concat('(',xf.cod_programa,'.',xf.cod_atividade,'.',xf.cod_fonte,')') as prog
,count(1) as contador

FROM  sgc_nota_xfac xf
WHERE 1=1
AND xf.status_nota='Autorizada'
AND DATE_FORMAT(data_nota, '%y-%m-%d') = DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -1 DAY), '%y-%m-%d')
GROUP BY
 data
,prog
ORDER BY contador DESC , prog

") or print(mysql_error());
                         while($dados_qt=mysql_fetch_array($checa_qt)){
                            $data = $dados_qt['data'];
                            $prog = $dados_qt['prog'];
                            $contador = $dados_qt['contador'];

?>
				<tr>
					<td width="34%" bgcolor="#FFFFFF">
					<p align="center"><?echo $data?></td>
					<td width="34%" bgcolor="#FFFFFF"><?echo $prog?></td>
					<td width="22%" bgcolor="#FFFFFF">
					<p align="right"><?echo $contador?></td>
				</tr>
<?
}
?>
			</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td width="505">
	<table border="1" width="505" id="table6">
		<tr>
			<td colspan="4" bgcolor="#FFFFFF">
			<p align="center"><b>5 Produtos mais movimentados</b></td>
		</tr>
		<tr>
			<td width="43" bgcolor="#FFFFFF">
			<p align="center"><b>Hoje</b></td>
			<td bgcolor="#FFFFFF" width="296"><b>Produto</b></td>
			<td width="40" bgcolor="#FFFFFF">
			<p align="center"><b>UN</b></td>
			<td width="95" bgcolor="#FFFFFF">
			<p align="center"><b>Quantidade</b></td>
		</tr>
<?

$checa_qt = mysql_query("
SELECT

 DATE_FORMAT(data_nota, '%d/%m/%Y')    as data
,it.nome_produto
,CASE WHEN  LENGTH(it.nome_produto) > 40 THEN
  CONCAT(SUBSTRING(it.nome_produto FROM 1 FOR 30),'...')
 ELSE
   it.nome_produto
 END AS nome_produto

,it.unidade_medida
,sum(it.quantidade) as quantidade
FROM sgc_servidores ser, sgc_nota_xfac xf, sgc_nota_xfac_item it
WHERE ser.uf = xf.uf AND it.idnotasgc=xf.id
AND xf.status_nota='Autorizada'
AND DATE_FORMAT(data_nota, '%y-%m-%d') = DATE_FORMAT(SYSDATE(), '%y-%m-%d')
GROUP BY
 nome_produto
,data
,it.unidade_medida
ORDER BY quantidade DESC, it.nome_produto,DATE_FORMAT(data_nota, '%d/%m/%Y') LIMIT 5


") or print(mysql_error());
                         while($dados_qt=mysql_fetch_array($checa_qt)){
                            $nome_produto = $dados_qt['nome_produto'];
                            $unidade_medida = $dados_qt['unidade_medida'];
                            $quantidade = $dados_qt['quantidade'];
                            $data = $dados_qt['data'];

?>
		<tr>
            <td width="43" bgcolor="#FFFFFF"><?Echo $data?></td>
			<td align="center" bgcolor="#FFFFFF" width="296">
			<p align="left"><?echo $nome_produto?></td>
			<td width="40" align="center" bgcolor="#FFFFFF"><?echo $unidade_medida?></td>
			<td width="95" align="right" bgcolor="#FFFFFF"><?echo $quantidade?></td>
		</tr>
<?
}
?>
	</table>


	<table border="1" width="505" id="table7">
 		<tr>
			<td width="43" bgcolor="#FFFFFF">
			<p align="center"><b>Ontem</b></td>
			<td bgcolor="#FFFFFF"><b>Produto</b></td>
			<td width="40" bgcolor="#FFFFFF">
			<p align="center"><b>UN</b></td>
			<td width="95" bgcolor="#FFFFFF">
			<p align="center"><b>Quantidade</b></td>
		</tr>
<?

$checa_qt = mysql_query("
SELECT

 DATE_FORMAT(data_nota, '%d/%m/%Y') as data
,CASE WHEN  LENGTH(it.nome_produto) > 40 THEN
  CONCAT(SUBSTRING(it.nome_produto FROM 1 FOR 30),'...')
 ELSE
   it.nome_produto
 END AS nome_produto
,it.unidade_medida
,sum(it.quantidade) as quantidade

FROM sgc_servidores ser, sgc_nota_xfac xf, sgc_nota_xfac_item it
WHERE ser.uf = xf.uf AND it.idnotasgc=xf.id
AND xf.status_nota='Autorizada'
AND DATE_FORMAT(data_nota, '%y-%m-%d') = DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -1 DAY), '%y-%m-%d')
GROUP BY
 nome_produto
,DATE_FORMAT(data_nota, '%d/%m/%Y')
,it.unidade_medida
ORDER BY quantidade DESC, it.nome_produto,DATE_FORMAT(data_nota, '%d/%m/%Y') LIMIT 5


") or print(mysql_error());
                         while($dados_qt=mysql_fetch_array($checa_qt)){
                            $nome_produto = $dados_qt['nome_produto'];
                            $unidade_medida = $dados_qt['unidade_medida'];
                            $quantidade = $dados_qt['quantidade'];
                            $data = $dados_qt['data'];

?>
		<tr>
			<td width="43" bgcolor="#FFFFFF"><?Echo $data?></td>
			<td align="center" bgcolor="#FFFFFF">
			<p align="left"><?echo $nome_produto?></td>
			<td width="40" align="center" bgcolor="#FFFFFF"><?echo $unidade_medida?></td>
			<td width="95" align="right" bgcolor="#FFFFFF"><?echo $quantidade?></td>
		</tr>
<?
}
?>
	</table>



        	</td>
			<td>&nbsp;</td>
		</tr>
	</table>
<?








