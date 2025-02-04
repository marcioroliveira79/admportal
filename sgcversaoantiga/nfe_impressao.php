<?php
OB_START();
session_start();
include("conf/conecta.php");
include("conf/funcs.php");

$sureg = $_GET['sureg'];
$id_usuario = $_GET['id_usuario'];
$permissao = $_GET['permissao'];

If ($permissao == "OK"){

$pg=new sgc_nfe;
      $pg->conectar_nfe($sureg);


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
		<p align="center"><b><font face="Arial" size="2">Dados NF-e <?echo $sureg?>
		</font></b></td>
	</tr>
	<tr>
		<td width="629">
			<fieldset style="padding: 2">
			<table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0">

				<?

                                    $checa = pg_query("
SELECT

 trim(CASE WHEN nf.modelonotafiscal = '55' AND nf.emissaopropria THEN
     CASE WHEN nf.status = '1' THEN 'Gerada      '
          WHEN nf.status = '2' THEN 'Transmitida '
          WHEN nf.status = '3' THEN 'Autorizada  '
          WHEN nf.status = '4' THEN 'Cancelada   '
          WHEN nf.status = '5' THEN 'Contingencia'
          WHEN nf.status = '6' THEN 'Inutilizada '
          WHEN nf.status = '7' THEN 'Rejeitada   '
      END
ELSE
     CASE WHEN nf.status = '1' THEN 'Gravada    '
          WHEN nf.status = '2' THEN 'Impressa   '
          WHEN nf.status = '3' THEN 'Escriturada'
          WHEN nf.status = '4' THEN 'Cancelada  '
          WHEN nf.status = '5' THEN 'Estornada  '
          WHEN nf.status = '6' THEN 'Lanc.Indev.'
          WHEN nf.status = '7' THEN 'Rejeitada  '
    END
END) AS statusnota
,count(nf.id) as total

FROM
   tb_nota_fiscal_eletronica nfe
 , tb_nota_fiscal nf
 , tb_conta ct
 , tb_cnpj cn
 , tb_fonte ft
 , tb_atividade at
 , tb_regra re
 , tb_operacao op
 , tb_usuario us
WHERE nf.id = nfe.idnotafiscal
AND ct.id = nf.idconta
AND re.id = nf.idregra
AND ft.id = ct.idfonte
AND at.id = ct.idatividade
AND cn.id = ct.idcnpj
AND re.idoperacao = op.id
AND us.id = nf.idusuario
AND TO_CHAR(DATE_TRUNC('day',nf.datagravacao),'dd/mm/yyyy') = TO_CHAR(DATE_TRUNC('day',NOW()),'dd/mm/yyyy')
GROUP BY statusnota
ORDER BY statusnota


 ");
       while($dados=pg_fetch_array($checa)){
             $statusnota = $dados['statusnota'];
             $total = $dados['total'];


                ?>
				<tr>
					<td width="185">
					<p align="right"><font face="Arial" size="2"><?echo $statusnota?>(s):</font></td>
					<td><font face="Arial" size="2">&nbsp;<?Echo $total?></font></td>
				</tr>
				<?
				$totalNotas=$totalNotas+$total;
				}
				?>
						<tr>
					<td width="185">
					<p align="right"><font face="Arial" size="2">Até o momento
					foram emitidas:</td>
					<td><font face="Arial" size="2">&nbsp;<?Echo $totalNotas?></font></td>
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
		<p align="center"><b><font face="Arial" size="2">Notas Por Usuário</font></b></td>
	</tr>
	<tr>
		<td width="629">
			<fieldset style="padding: 2">

<table border="0" width="100%" id="table2" cellspacing="0" cellpadding="0">
<?
   $checa = pg_query("

SELECT
 us.usuario
,us.id
,count(nf.id) as total
FROM
   tb_nota_fiscal_eletronica nfe
 , tb_nota_fiscal nf
 , tb_conta ct
 , tb_cnpj cn
 , tb_fonte ft
 , tb_atividade at
 , tb_regra re
 , tb_operacao op
 , tb_usuario us
WHERE nf.id = nfe.idnotafiscal
AND ct.id = nf.idconta
AND re.id = nf.idregra
AND ft.id = ct.idfonte
AND at.id = ct.idatividade
AND cn.id = ct.idcnpj
AND re.idoperacao = op.id
AND us.id = nf.idusuario
AND TO_CHAR(DATE_TRUNC('day',nf.datagravacao),'dd/mm/yyyy') = TO_CHAR(DATE_TRUNC('day',NOW()),'dd/mm/yyyy')
GROUP BY us.usuario ,us.id
ORDER BY total DESC


 ");
       while($dados=pg_fetch_array($checa)){
             $total_usuario = $dados['total'];
             $usuario = $dados['usuario'];
             $usuario_id = $dados['id'];

?>



	<tr>
		<td width="272"><font face="Arial" size="2"><?echo $usuario?></font></td>
		<td><font face="Arial" size="2"><?echo $total_usuario?></font></td>
	</tr>
	<?
	}
	?>
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
		<p align="center"><b><font face="Arial" size="2">Operações Realizadas</font></b></td>
	</tr>
	<tr>
		<td width="629">
			<fieldset style="padding: 2">

<table border="0" width="100%" id="table2" cellspacing="0" cellpadding="0">
<?
$checa = pg_query("
SELECT
 '('||cn.codigo||'.'||at.codigo||'.'||ft.codigo||')' as conta
,'('||op.codigo||') - '||op.nome as op
,op.codigo
,count(nf.id) as total
,ct.id
,re.entradasaida
FROM
   tb_nota_fiscal_eletronica nfe
 , tb_nota_fiscal nf
 , tb_conta ct
 , tb_cnpj cn
 , tb_fonte ft
 , tb_atividade at
 , tb_regra re
 , tb_operacao op
 , tb_usuario us
WHERE nf.id = nfe.idnotafiscal
AND ct.id = nf.idconta
AND re.id = nf.idregra
AND ft.id = ct.idfonte
AND at.id = ct.idatividade
AND cn.id = ct.idcnpj
AND re.idoperacao = op.id
AND us.id = nf.idusuario
AND TO_CHAR(DATE_TRUNC('day',nf.datagravacao),'dd/mm/yyyy') = TO_CHAR(DATE_TRUNC('day',NOW()),'dd/mm/yyyy')
GROUP BY op,conta,ct.id,re.entradasaida,op.codigo
ORDER BY total DESC");
     while($dados=pg_fetch_array($checa)){
             $codigoop = $dados['codigo'];
             $op = $dados['op'];
             $total = $dados['total'];
             $conta = $dados['conta'];
             $contaid = $dados['id'];
             $entradasaida = $dados['entradasaida'];
?>
	<tr>
		<td width="272"><font face="Arial" size="2"><?echo "$conta - $op - $entradasaida" ?></font></td>
		<td><font face="Arial" size="2"><?echo $total?></font></td>
	</tr>
	<?
	}
?>
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
		<p align="center"><b><font face="Arial" size="2">UF´s Destino</font></b></td>
	</tr>
	<tr>
		<td width="629">
			<fieldset style="padding: 2">

<table border="0" width="100%" id="table2" cellspacing="0" cellpadding="0">
<?
$checa = pg_query("
SELECT

 (select uf from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=2) as ufdestinatario
,count(nf.id) as total

FROM
   tb_nota_fiscal_eletronica nfe
 , tb_nota_fiscal nf
 , tb_conta ct
 , tb_cnpj cn
 , tb_fonte ft
 , tb_atividade at
 , tb_regra re
 , tb_operacao op
 , tb_usuario us
WHERE nf.id = nfe.idnotafiscal
AND ct.id = nf.idconta
AND re.id = nf.idregra
AND ft.id = ct.idfonte
AND at.id = ct.idatividade
AND cn.id = ct.idcnpj
AND re.idoperacao = op.id
AND us.id = nf.idusuario
AND TO_CHAR(DATE_TRUNC('day',nf.datagravacao),'dd/mm/yyyy') = TO_CHAR(DATE_TRUNC('day',NOW()),'dd/mm/yyyy')
GROUP BY ufdestinatario
ORDER BY total DESC


 ");
       while($dados=pg_fetch_array($checa)){
             $ufdestinatario = $dados['ufdestinatario'];
             $total = $dados['total'];
?>
	<tr>
		<td width="272"><font face="Arial" size="2"><?echo "$ufdestinatario" ?></font></td>
		<td><font face="Arial" size="2"><?echo $total?></font></td>
	</tr>
	<?
	}
?>
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
		<p align="center"><b><font face="Arial" size="2">Municípios de Destino</font></b></td>
	</tr>
	<tr>
		<td width="629">
			<fieldset style="padding: 2">

<table border="0" width="100%" id="table2" cellspacing="0" cellpadding="0">
<?
$checa = pg_query("
SELECT

(select cidade from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=2) as cidade
,(select uf from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=2) as ufdest
,count(nf.id) as total

FROM
   tb_nota_fiscal_eletronica nfe
 , tb_nota_fiscal nf
 , tb_conta ct
 , tb_cnpj cn
 , tb_fonte ft
 , tb_atividade at
 , tb_regra re
 , tb_operacao op
 , tb_usuario us
WHERE nf.id = nfe.idnotafiscal
AND ct.id = nf.idconta
AND re.id = nf.idregra
AND ft.id = ct.idfonte
AND at.id = ct.idatividade
AND cn.id = ct.idcnpj
AND re.idoperacao = op.id
AND us.id = nf.idusuario
AND TO_CHAR(DATE_TRUNC('day',nf.datagravacao),'dd/mm/yyyy') = TO_CHAR(DATE_TRUNC('day',NOW()),'dd/mm/yyyy')
GROUP BY cidade,ufdest
ORDER BY total DESC


 ");
       while($dados=pg_fetch_array($checa)){
             $cidade = $dados['cidade'];
             $cidadeuf = $dados['ufdest'];
             $total = $dados['total'];
?>
	<tr>
		<td width="272"><font face="Arial" size="2"><?echo "$cidade - $cidadeuf" ?></font></td>
		<td><font face="Arial" size="2"><?echo $total?></font></td>
	</tr>
	<?
	}
?>
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
		<p align="center"><b><font face="Arial" size="2">Produtos Movimentados e Negociados Hoje</font></b></td>
	</tr>
	<tr>
		<td width="629">
			<fieldset style="padding: 2">

<table border="0" width="90%" id="table2" cellspacing="0" cellpadding="0">
<?
$checa = pg_query("
SELECT
 pr.codigo
,pr.nome
,to_char(sum(it.quantidade),'999G999G990D999')    as quantidade
,it.unidademedida
,count(pr.id) as total

FROM
   tb_nota_fiscal_eletronica nfe
 , tb_nota_fiscal nf
 , tb_nota_fiscal_item it
 , tb_produto pr
 , tb_conta ct
 , tb_cnpj cn
 , tb_fonte ft
 , tb_atividade at
 , tb_regra re
 , tb_operacao op
 , tb_usuario us
WHERE nf.id = nfe.idnotafiscal
AND ct.id = nf.idconta
AND it.idnotafiscal = nf.id
AND pr.id = it.idproduto
AND re.id = nf.idregra
AND ft.id = ct.idfonte
AND at.id = ct.idatividade
AND cn.id = ct.idcnpj
AND re.idoperacao = op.id
AND us.id = nf.idusuario
AND TO_CHAR(DATE_TRUNC('day',nf.datagravacao),'dd/mm/yyyy') = TO_CHAR(DATE_TRUNC('day',NOW()),'dd/mm/yyyy')
GROUP BY pr.codigo, pr.nome, it.unidademedida
ORDER BY pr.nome ,it.unidademedida DESC


 ");
       while($dados=pg_fetch_array($checa)){
             $codigo = $dados['codigo'];
             $nome = $dados['nome'];
             $quantidade = $dados['quantidade'];
             $unidademedida = $dados['unidademedida'];
             $total = $dados['total'];
?>
	<tr>
		<td width="272"><font face="Arial" size="2"><?echo "$codigo - $nome - $unidademedida" ?></font></td>
		<td><p align="right"><font face="Arial" size="2"><?echo $quantidade?></font></td>
	</tr>
	<?
	}
?>
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
		<p align="center"><b><font face="Arial" size="2">Notas Emitidas</font></b></td>
	</tr>
	<tr>
		<td width="629">
			<fieldset style="padding: 2">

<table border="0" width="100%" id="table2" cellspacing="0" cellpadding="0">
<?
$checa = pg_query("
SELECT
 '('||cn.codigo||'.'||at.codigo||'.'||ft.codigo||')' as conta
,nf.id
,nfe.chaveacessonfe
,nfe.protocoloautorizacao
,nf.numeronotafiscal
,TO_CHAR(nf.datanota,'dd/mm/yyyy HH24:MM:SS')  as datanota
,nfe.datahorarecibo
,(nfe.datahorarecibo-nf.datanota) as tempo_retorno
,nf.serienotafiscal
,nf.entradasaida
,nf.cfop
,to_char(nf.valortotalnotafiscal,'999G999G990D99') AS valortotalnotafiscal
,op.codigo as codigooperacao
,op.nome as nomeoperacao
,us.usuario as usuario
,nfe.textorecibo
,ct.uf as uforigem
,(select uf from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=2) as ufdestinatario
,trim(CASE WHEN nf.modelonotafiscal = '55' AND nf.emissaopropria THEN
     CASE WHEN nf.status = '1' THEN 'Gerada      '
          WHEN nf.status = '2' THEN 'Transmitida '
          WHEN nf.status = '3' THEN 'Autorizada  '
          WHEN nf.status = '4' THEN 'Cancelada   '
          WHEN nf.status = '5' THEN 'Contingencia'
          WHEN nf.status = '6' THEN 'Inutilizada '
          WHEN nf.status = '7' THEN 'Rejeitada   '
      END
ELSE
     CASE WHEN nf.status = '1' THEN 'Gravada    '
          WHEN nf.status = '2' THEN 'Impressa   '
          WHEN nf.status = '3' THEN 'Escriturada'
          WHEN nf.status = '4' THEN 'Cancelada  '
          WHEN nf.status = '5' THEN 'Estornada  '
          WHEN nf.status = '6' THEN 'Lanc.Indev.'
          WHEN nf.status = '7' THEN 'Rejeitada  '
    END
END) AS statusnota


FROM
   tb_nota_fiscal_eletronica nfe
 , tb_nota_fiscal nf
 , tb_conta ct
 , tb_cnpj cn
 , tb_fonte ft
 , tb_atividade at
 , tb_regra re
 , tb_operacao op
 , tb_usuario us
WHERE nf.id = nfe.idnotafiscal
AND ct.id = nf.idconta
AND re.id = nf.idregra
AND ft.id = ct.idfonte
AND at.id = ct.idatividade
AND cn.id = ct.idcnpj
AND re.idoperacao = op.id
AND us.id = nf.idusuario
AND TO_CHAR(DATE_TRUNC('day',nf.datagravacao),'dd/mm/yyyy') = TO_CHAR(DATE_TRUNC('day',NOW()),'dd/mm/yyyy')
ORDER BY nf.id DESC,conta, nf.datagravacao DESC


 ");
       while($dados=pg_fetch_array($checa)){
                               $conta = $dados['conta'];
                               $idnf = $dados['id'];
                               $numeronotafiscal = $dados['numeronotafiscal'];
                               $datanota = $dados['datanota'];
                               $tempo_retorno = $dados['tempo_retorno'];
                               $entradasaida = $dados['entradasaida'];
                               $valortotalnotafiscal = $dados['valortotalnotafiscal'];
                               $codigooperacao = $dados['codigooperacao'];
                               $nomeoperacao = $dados['nomeoperacao'];
                               $ufdestinatario = $dados['ufdestinatario'];
                               $statusnota = $dados['statusnota'];
                               $usuario = $dados['usuario'];

?>
	<tr>
		<td width="85%"><font face="Arial" size="2"><?echo "$conta - $numeronotafiscal - $codigooperacao $nomeoperacao - $entradasaida - $datanota" ?></font></td>
		<td><p align="right"><font face="Arial" size="2"><?echo "R$ $valortotalnotafiscal"?></font></td>
	</tr>
	<?
	}
?>
</table>
			</fieldset>
		</td>
	</tr>


</table>
<?
}

