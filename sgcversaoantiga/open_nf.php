<?php
OB_START();
session_start();

header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");



$sureg=$_GET['sureg'];



include("conf/conecta.php");
include("conf/funcs.php");


$pg=new sgc_nfe;
      $pg->conectar_nfe($sureg);







$action=$_GET['action'];
$msg=$_GET['msg'];

if(!isset($action)){



}elseif($action=="OPERACAO"){
?>
<p>EM CONSTRUÇÃO</p>

<?
}elseif($action=="CONTA"){

$idconta=$_GET['id_conta'];

  $checa = pg_query("
SELECT

'('||cn.codigo||'.'||at.codigo||'.'||ft.codigo||')' as conta
,count(ct.id) as total
,ct.id
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
AND ct.id = $idconta
AND TO_CHAR(DATE_TRUNC('day',nf.datagravacao),'dd/mm/yyyy') = TO_CHAR(DATE_TRUNC('day',NOW()),'dd/mm/yyyy')
GROUP BY conta,ct.id
ORDER BY total DESC


 ");
       while($dados=pg_fetch_array($checa)){
             $total_conta = $dados['total'];
             $conta = $dados['conta'];
             $conta_id = $dados['id'];
}
?>

<table border="1" width="625" id="table8">


									<tr>
										<td width="585" colspan="6" ><b>
										<font size="2" face="Arial">CONTA/INSTURMENTO: <?echo $conta?></font></b></td>
									</tr>


									<tr>
										<td width="59" ><b>
										<font face="Arial" size="2">USUÁRIO</font></b></td>
										<td width="66" ><b>
										<font face="Arial" size="2">Nº NF</font></b></td>
										<td width="101" ><b>
										<font face="Arial" size="2">DATA </font>
										</b></td>
										<td width="25" >
										<p align="center"><b>
										<font face="Arial" size="2">E/S</font></b></td>
										<td width="103" >
										<p align="right"><b>
										<font face="Arial" size="2">VALOR TOTAL</font></b></td>
										<td width="231" ><b>
										<font face="Arial" size="2">OPERAÇÃO</font></b></td>
									</tr>
<?

  $checa = pg_query("
  SELECT
 '('||cn.codigo||'.'||at.codigo||'.'||ft.codigo||')' as conta
,nf.numeronotafiscal
,TO_CHAR(nf.datanota,'dd/mm/yyyy HH24:MM')  as datanota
,nf.entradasaida
,to_char(nf.valortotalnotafiscal,'999G999G990D99') AS valortotalnotafiscal
,op.codigo as codigooperacao
,op.nome as nomeoperacao
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
,nf.id
,initcap(TRANSLATE(us.usuario,'.',' ')) as usuario

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
AND ct.id = $conta_id
ORDER BY nf.id DESC,nf.datagravacao DESC




 ");
       while($dados=pg_fetch_array($checa)){
             $idnf = $dados['id'];
             $conta = $dados['conta'];
             $numeronotafiscal = $dados['numeronotafiscal'];
             $datanota = $dados['datanota'];
             $entradasaida = $dados['entradasaida'];
             $valortotalnotafiscal = $dados['valortotalnotafiscal'];
             $codigooperacao = $dados['codigooperacao'];
             $nomeoperacao = $dados['nomeoperacao'];
             $statusnota = $dados['statusnota'];
             $usuario = $dados['usuario'];

?>





									<tr>
										<td width="65" >
										<font face="Arial" size="2"><?echo $usuario?></font></td>
										<td width="66" >
										<font face="Arial" size="2">
										<a href="open_nf.php?&action=NOTA&id_nf=<?echo $idnf?>&sureg=<?echo $sureg?>"><font color="#000000"><span style="text-decoration: none"><?echo $numeronotafiscal?></a></font></td>
										<td width="101" >
										<font face="Arial" size="2"><?echo $datanota?></font></td>
										<td width="25" >
										<p align="center">
										<font face="Arial" size="2"><?echo $entradasaida?></font></td>
										<td width="103" >
										<p align="right">
										<font face="Arial" size="2">&nbsp;<?echo $valortotalnotafiscal?></font></td>
										<td width="231" >
										<font face="Arial" size="2"><?echo $codigooperacao?> - <?echo $nomeoperacao?></font></td>
									</tr>
<?
}
?>

									<tr>
										<td width="378" colspan="5" >
										<p align="right"><b>
										<font face="Arial" size="2">TOTAL DE NOTA EMITIDAS:</font></b></td>
										<td width="231" >
										<font face="Arial" size="2">
										<span style="font-size: arial; font-weight: 700">
										<?echo $total_conta ?></span></font></td>
									</tr>


								</table>
<?
}elseif($action=="USUARIO"){

$idusuario_nf=$_GET['id_usuario'];

  $checa = pg_query("

SELECT
 initcap(TRANSLATE(us.usuario,'.',' ')) as usuario
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
AND us.id = $idusuario_nf
AND TO_CHAR(DATE_TRUNC('day',nf.datagravacao),'dd/mm/yyyy') = TO_CHAR(DATE_TRUNC('day',NOW()),'dd/mm/yyyy')
GROUP BY usuario ,us.id
ORDER BY total DESC


 ");
       while($dados=pg_fetch_array($checa)){
             $total_usuario = $dados['total'];
             $usuario = $dados['usuario'];
             $usuario_id = $dados['id'];
}
?>

<table border="1" width="625" id="table8">


									<tr>
										<td width="585" colspan="6" ><b>
										<font size="2" face="Arial">USUÁRIO	EMISSOR: <?echo $usuario?></font></b></td>
									</tr>


									<tr>
										<td width="59" ><b>
										<font face="Arial" size="2">CONTA</font></b></td>
										<td width="66" ><b>
										<font face="Arial" size="2">Nº NF</font></b></td>
										<td width="101" ><b>
										<font face="Arial" size="2">DATA </font>
										</b></td>
										<td width="25" >
										<p align="center"><b>
										<font face="Arial" size="2">E/S</font></b></td>
										<td width="103" >
										<p align="right"><b>
										<font face="Arial" size="2">VALOR TOTAL</font></b></td>
										<td width="231" ><b>
										<font face="Arial" size="2">OPERAÇÃO</font></b></td>
									</tr>
<?

  $checa = pg_query("
  SELECT
 '('||cn.codigo||'.'||at.codigo||'.'||ft.codigo||')' as conta
,nf.numeronotafiscal
,TO_CHAR(nf.datanota,'dd/mm/yyyy HH24:MM')  as datanota
,nf.entradasaida
,to_char(nf.valortotalnotafiscal,'999G999G990D99') AS valortotalnotafiscal
,op.codigo as codigooperacao
,op.nome as nomeoperacao
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
,nf.id

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
AND us.id = $usuario_id
ORDER BY nf.id DESC,nf.datagravacao DESC




 ");
       while($dados=pg_fetch_array($checa)){
             $idnf = $dados['id'];
             $conta = $dados['conta'];
             $numeronotafiscal = $dados['numeronotafiscal'];
             $datanota = $dados['datanota'];
             $entradasaida = $dados['entradasaida'];
             $valortotalnotafiscal = $dados['valortotalnotafiscal'];
             $codigooperacao = $dados['codigooperacao'];
             $nomeoperacao = $dados['nomeoperacao'];
             $statusnota = $dados['statusnota'];

?>





									<tr>
										<td width="59" >
										<font face="Arial" size="2"><?echo $conta?></font></td>
										<td width="66" >
										<font face="Arial" size="2">
										<a href="open_nf.php?&action=NOTA&id_nf=<?echo $idnf?>&sureg=<?echo $sureg?>"><font color="#000000"><span style="text-decoration: none"><?echo $numeronotafiscal?></a></font></td>
										<td width="101" >
										<font face="Arial" size="2"><?echo $datanota?></font></td>
										<td width="25" >
										<p align="center">
										<font face="Arial" size="2"><?echo $entradasaida?></font></td>
										<td width="103" >
										<p align="right">
										<font face="Arial" size="2">&nbsp;<?echo $valortotalnotafiscal?></font></td>
										<td width="231" >
										<font face="Arial" size="2"><?echo $codigooperacao?> - <?echo $nomeoperacao?></font></td>
									</tr>
<?
}
?>

									<tr>
										<td width="378" colspan="5" >
										<p align="right"><b>
										<font face="Arial" size="2">TOTAL DE NOTA EMITIDAS:</font></b></td>
										<td width="231" >
										<font face="Arial" size="2">
										<span style="font-size: arial; font-weight: 700">
										<?echo $total_usuario ?></span></font></td>
									</tr>


								</table>
<?




}elseif($action=="NOTA"){
$idnf=$_GET['id_nf'];
$checa = pg_query("
 SELECT
 '('||cn.codigo||'.'||at.codigo||'.'||ft.codigo||')' as conta
,nf.id
,nfe.chaveacessonfe
,nfe.protocoloautorizacao
,nf.numeronotafiscal
,TO_CHAR(nf.datanota,'dd/mm/yyyy')  as datanota
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

,(select nome from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=1) as nome_origem
,(select nome from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=2) as nome_destinatario
,(select uf from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=2) as uf_destinatario
,(select cidade from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=2) as cidade_destinatario
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
AND nf.id=$idnf
");
                           while($dados=pg_fetch_array($checa)){
                               $idnota = $dados['id'];
                               $conta = $dados['conta'];
                               $nome_origem = $dados['nome_origem'];
                               $nome_destinatario = $dados['nome_destinatario'];
                               $cidade_destinatario = $dados['cidade_destinatario'];
                               $uforigem = $dados['uforigem'];
                               $idnf = $dados['idnf'];
                               $cfop = $dados['cfop'];
                               $numeronotafiscal = $dados['numeronotafiscal'];
                               $datanota = $dados['datanota'];
                               $tempo_retorno = $dados['tempo_retorno'];
                               $entradasaida = $dados['entradasaida'];
                               $valortotalnotafiscal = $dados['valortotalnotafiscal'];
                               $codigooperacao = $dados['codigooperacao'];
                               $nomeoperacao = $dados['nomeoperacao'];
                               $ufdestinatario = $dados['uf_destinatario'];
                               $statusnota = $dados['statusnota'];
                               $usuario = $dados['usuario'];
}


?>                                              <font face="Arial" size="2">
                             <a href="javascript:history.back()">
<font color="#000000"><span style="text-decoration: none; font-weight: 700">Voltar</span></font></a>
								<table border="1" width="625" id="table9">
									<tr>
										<td width="111">
										<font face="Arial" size="2">&nbsp;<b>NF Nº:</b>
										<?echo $numeronotafiscal?></font></td>
										<td width="373">
										<p align="center">&nbsp;<font face="Arial" size="2"><b>CFOP:</b>
										<?echo $cfop?> - <b>CONTA:</b></font>
										<font face="Arial" size="2"><?echo $conta?>
										</font></td>
										<td width="119">
										<p align="center">&nbsp;<font face="Arial" size="2"><?echo $datanota?></font></td>
									</tr>
									<tr>
										<td colspan="2" width="490">
										&nbsp;<font face="Arial" size="2"><?echo $nome_origem?> - <?echo $uforigem?></font></td>
										<td width="119">
										<p align="center">
										<font face="Arial" size="2"><? if($entradasaida=="S"){
                                                                              echo "SAÍDA";
                                                                              }else{
                                                                               echo "ENTRADA";
                                                                              }; ?></font></td>
									</tr>


						     		<tr>
										<td colspan="3" width="616">
										<font face="Arial" size="2">&nbsp;<?echo $codigooperacao?> - <?echo $nomeoperacao?> </font></td>
									</tr>
									<tr>
										<td colspan="3" width="616">
										&nbsp;<font face="Arial" size="2"><b>REMETENTE:</b> <?echo $nome_destinatario?>  </font></td>
									</tr>
									<tr>
										<td colspan="3" width="616">
										<font face="Arial" size="2">&nbsp;<?echo $cidade_destinatario?> - <?echo $ufdestinatario?> </font></td>
									</tr>
									<tr>
										<td colspan="3" width="616">
										<table border="1" width="100%" id="table10">
											<tr>
												<td width="70"><b>&nbsp;<font face="Arial" size="2">COD</font></b></td>
												<td><b>&nbsp;<font face="Arial" size="2">PRODUTO</font></b></td>
												<td width="22" align="center">
												<b>&nbsp;<font face="Arial" size="2">AC</font></b></td>
												<td width="27" align="center">
												<b>&nbsp;<font face="Arial" size="2">UM</font></b></td>
												<td width="25" align="center">
												<b>&nbsp;<font face="Arial" size="2">SIT</font></b></td>
												<td width="79" align="center">
												<p align="center"><b>&nbsp;<font face="Arial" size="2">QTDE</font></b></td>
												<td width="89" align="center">
												<p align="center">
												<b>
												<font face="Arial" size="2">
												VALOR R$</font></b></td>
											</tr> <?

$checa = pg_query("SELECT
 it.nome
,it.situacaotributaria
,it.unidademedida
,it.acondicionamento
,it.quantidade
,it.valortotal
,pr.codigo
,nf.valortotalnotafiscal
FROM tb_nota_fiscal_item it, tb_produto pr, tb_nota_fiscal nf
WHERE it.idnotafiscal =$idnota
AND pr.id = it.idproduto
AND nf.id = it.idnotafiscal
ORDER BY it.id
");
                           while($dados=pg_fetch_array($checa)){
                               $nome = $dados['nome'];
                               $situacaotributaria = $dados['situacaotributaria'];
                               $unidademedida = $dados['unidademedida'];
                               $acondicionamento = $dados['acondicionamento'];
                               $quantidade = $dados['quantidade'];
                               $valortotal = $dados['valortotal'];
                               $codigo = $dados['codigo'];
                               $valortotalnotafiscal = $dados['valortotalnotafiscal'];
                                ?>

                                        	<tr>
												<td width="70">
										<font face="Arial" size="2">&nbsp;<?Echo $codigo?></font></td>
												<td>&nbsp;<font face="Arial" size="2"><?Echo $nome?></font></td>
												<td width="22" align="center">&nbsp;<font face="Arial" size="2"><?Echo $acondicionamento?></font></td>
												<td width="27" align="center">&nbsp;<font face="Arial" size="2"><?Echo $unidademedida?></font></td>
												<td width="25" align="center">
												<p align="center">
												<font face="Arial" size="2"><?Echo $situacaotributaria?></font></td>
												<td width="79">
												<p align="right">
												<font face="Arial" size="2"><?Echo $quantidade?></font></td>
												<td width="89">
												<p align="right">
												<font face="Arial" size="2"><?Echo $valortotal?></font></td>
											</tr>
											<?
											}
											?>
											<tr>
												<td colspan="6">
												<p align="right"><b>
												<font face="Arial" size="2">VALOR TOTAL NOTA FISCAL:</font></b></td>
												<td width="89" align="right"><b><font face="Arial" size="2"><?echo $valortotalnotafiscal?></font></b></td>
											</tr>
										</table>
										</td>
									</tr>
 						     </table>

<?


}elseif($action=="send"){



}

?>
