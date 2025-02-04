<?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("conf/funcs.php");

$uf=$_POST['uf'];
$sureg=tabelainfo($uf,"sgc_servidores","descricao_servidor","nuf","");
$sureg=str_replace("-","",$sureg);

include("conf/conecta.php");


$pg=new sgc_nfe;
      $pg->conectar_nfe($sureg);


$id_usuario=$_POST['idus'];

$bloco=$_POST['bloco'];

if($bloco=="MONITOR"){
?>
   		<table border="1" width="93%" id="table6" bgcolor="#FFFFFF">
						    <tr>
								<td width="92%" colspan="9">
								<p align="center"><b>Últimas 10 Notas Fiscais Emitidas Hoje</b></td>
							</tr>
   							<tr>
								<td width="9%">
								<p align="center"><font size="2"><b>Conta</b></font></td>
								<td width="10%">
								<p align="center"><font size="2"><b>Nº NF</b></font></td>
								<td width="18%">
								<font align="center" size="2"><b>Data da NF</b> </font></td>
								<td width="6%">
								<font size="2"><b>Tempo</b></font></td>
								<td width="2%">
								<font size="2"><b>E/S</b></font></td>
								<td width="11%">
								<p align="center"><font size="2"><b>Valor NF R$</b></font></td>
								<td width="5%">
								<p align="center"><font size="2"><b>Operação</b></font></td>
								<td width="17%">
								<p align="center"><font size="2"><b>Usuário</b></font></td>
								<td width="14%"  bgcolor="#FFFFFF">
								<p align="center"><font size="2"><b>Status NF</b></font></td>
							</tr>
<?


$checa = pg_query(" SELECT
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
ORDER BY nf.id DESC,nf.datagravacao DESC
LIMIT 10
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
                               If($statusnota=="Autorizada"){
                                 $cor_fonte="#00FF00";
                               }elseIf($statusnota=="Cancelada" OR $statusnota=="Inutilizada"){
                                 $cor_fonte="#FFFF00";
                               }elseIf($statusnota=="Rejeitada"){
                                 $cor_fonte="#FF0000";
                               }elseIf($statusnota=="Transmitida" OR $statusnota=="Gerada"){
                                 $cor_fonte="#00FFFF";
                               }

        ?>









							<tr>
								<td width="9%">
								<p align="center"><?echo $conta?></td>
								<td width="10%">
								<p align="center">
                                <a href="javaScript: void(window.open('open_nf.php?&action=NOTA&id_nf=<?echo $idnf?>&sureg=<?echo $sureg?>','Visualizar','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=665,height=530'));">
                                <?Echo $numeronotafiscal?>
                                </a></td>
								<td width="18%">
								<p align="center"><?echo $datanota?></td>
								<td width="6%">
								<p align="center"><?Echo $tempo_retorno?></td>
								<td width="2%">
								<p align="center"><?Echo $entradasaida?></td>
								<td width="11%">
								<p align="right"><?echo $valortotalnotafiscal?></td>
								<td width="5%">
								<p align="center"><?Echo $codigooperacao?></td>
								<td width="17%">
								<p align="center"><?Echo $usuario?></td>
								<td width="14%"  bgcolor="#000000">
								<p align="center"><font color="<?echo $cor_fonte?>"><?Echo $statusnota?></font></td>
							</tr>
<?
}
?>
							<tr>
								<td width="45%" colspan="4">
								<div align="center">
									<table border="0" width="100%" id="table7" cellspacing="0" cellpadding="0">


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
											<td>
											<p align="right"><b><font size="5"><?echo $statusnota?>(s):</font></b></td>
											<td width="203"><b><font size="5">&nbsp;<?echo $total?></font></b></td>
										</tr>
										<?
										}
										?>



									</table>
								</div>
								</td>
								<td width="2%" rowspan="2">&nbsp;</td>
								<td colspan="4" valign="top">
<table border="1" width="100%" id="table8">
									<tr>
										<td colspan="2">
										<p align="center"><b>Notas Por Usuário Hoje</b></td>
									</tr>
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
										<td >
                                        <a href="javaScript: void(window.open('open_nf.php?&action=USUARIO&id_usuario=<?echo $usuario_id?>&sureg=<?echo $sureg?>','Visualizar','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=665,height=530'));">
                                        <?echo $usuario?></a></td>
										<td ><?echo $total_usuario?></td>
									</tr>
<?
}
?>

								</table>
								
								
								
								
                            <table border="1" width="100%" id="table8">
									<tr>
										<td colspan="4">
										<p align="center"><b>Operações	Realizadas Hoje</b></td>
									</tr>
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
ORDER BY conta ,re.entradasaida


 ");
       while($dados=pg_fetch_array($checa)){
             $codigoop = $dados['codigo'];
             $op = $dados['op'];
             $total = $dados['total'];
             $conta = $dados['conta'];
             $contaid = $dados['id'];
             $entradasaida = $dados['entradasaida'];

?>

									<tr>
										<td><a href="javaScript: void(window.open('open_nf.php?&action=CONTA&id_conta=<?echo $contaid?>&sureg=<?echo $sureg?>','Visualizar','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=665,height=530'));">

                                        <?echo $conta?>
                                        </a>
                                        </td>
										<td ><a href="javaScript: void(window.open('open_nf.php?&action=OPERACAO&entradasaida=<?echo $entradasaida?>&codigo=<?echo $codigoop?>&sureg=<?echo $sureg?>','Visualizar','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=665,height=530'));">
                                        <?echo $op?></a></td>
										<td ><?echo $entradasaida?></td>
										<td ><?echo $total?></td>
									</tr>
<?
}
?>

								</table>
								
								
								<table border="1" width="100%" id="table8">
									<tr>
										<td colspan="2" width="477">
										<p align="center"><b>UF´s Destino</b></td>
									</tr>
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
										<td width="350"><?Echo $ufdestinatario?></td>
										<td width="121"><?Echo $total?></td>
									</tr>
<?
}
?>
						     </table>

								<table border="1" width="100%" id="table9">
									<tr>
										<td colspan="2" width="477">
										<p align="center"><b>Municípios de
										Destino</b></td>
									</tr>
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
										<td width="350"><?Echo "$cidade - $cidadeuf"?></td>
										<td width="121"><?Echo $total?></td>
									</tr>
<?
}
?>
						     </table>


								</td>
							</tr>
							<tr>
								<td width="45%" colspan="4">
								<div align="center">
        	<table border="1" width="100%" id="table8">
            	<tr>
										<td colspan="4">
										<p align="center"><b>Produtos Movimentados e Negociados Hoje</b></td>
									</tr>
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
										<td><?echo $codigo?></td>
										<td ><?echo $nome?></td>
										<td ><?echo $unidademedida?></td>
                 						<td align="right"><?echo $quantidade?></td>
									</tr>
<?
}
?>

								</div>
								</td>
								<td colspan="4" valign="top">



                                </td>
							</tr>
						</table>

<?
}








