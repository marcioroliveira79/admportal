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

<div align="center">
	<table border="1" width="86%" id="table1" cellspacing="1" cellpadding="0" style="border-collapse: collapse">
		<tr>
			<td width="61" align="center"><b><font size="1" face="Arial">Conta</font></b></td>
			<td width="67" align="center"><b><font size="1" face="Arial">Nº Nota</font></b></td>
			<td width="121" align="center"><b><font size="1" face="Arial">&nbsp;Data
			Nota</font></b></td>
			<td width="47" align="center"><b><font face="Arial" size="1">T.
			Sefaz</font></b></td>
			<td align="center" width="27"><b><font size="1" face="Arial">E/S</font></b></td>
			<td align="center" width="74"><b><font size="1" face="Arial">Valor
			Total</font></b></td>
			<td align="center" width="41"><b><font size="1" face="Arial">Cod. Op</font></b></td>
			<td align="center" width="262"><b><font size="1" face="Arial">
			Operação</font></b></td>
			<td align="center" width="82"><b><font size="1" face="Arial">Usuário</font></b></td>
			<td align="center" width="24"><b><font size="1" face="Arial">UF D</font></b></td>
			<td align="center"><b><font size="1" face="Arial">Status</font></b></td>
		</tr>
		  <?
     	$checa = pg_query(" SELECT
 '('||cn.codigo||'.'||at.codigo||'.'||ft.codigo||')' as conta
,nfe.chaveacessonfe
,nfe.protocoloautorizacao
,nf.numeronotafiscal
,nf.datanota
,nfe.datahorarecibo
,(nfe.datahorarecibo-nf.datanota) as tempo_retorno
,nf.serienotafiscal
,nf.entradasaida
,nf.cfop
,nf.valortotalnotafiscal
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
ORDER BY nf.datanota DESC
LIMIT 10
                       ");
                           while($dados=pg_fetch_array($checa)){
                               $conta = $dados['conta'];
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
			<td width="61" align="center" bgcolor="#000000">
			<font face="Arial" size="1" color="#FFFFFF"><?echo $conta?></font></td>
			<td width="67" align="center" bgcolor="#000000">
			<font face="Arial" size="1" color="#FFFFFF"><?echo $numeronotafiscal?></font></td>
			<td width="121" align="center" bgcolor="#000000">
			<font face="Arial" size="1" color="#FFFFFF"><?echo $datanota?></font></td>
			<td width="47" align="center" bgcolor="#000000">
			<font face="Arial" size="1" color="#FFFFFF"><?echo $tempo_retorno?></font></td>
			<td align="center" width="27" bgcolor="#000000">
			<font face="Arial" size="1" color="#FFFFFF"><?echo $entradasaida?></font></td>
			<td align="right" width="74" bgcolor="#000000">
			<font face="Arial" size="1" color="#FFFFFF"><?echo $valortotalnotafiscal?></font></td>
			<td align="center" width="41" bgcolor="#000000">
			<font face="Arial" size="1" color="#FFFFFF"><?echo $codigooperacao?></font></td>
			<td align="center" width="262" bgcolor="#000000">
			<font face="Arial" size="1" color="#FFFFFF"><?echo $nomeoperacao?></font></td>
			<td align="center" width="82" bgcolor="#000000">
			<font face="Arial" size="1" color="#FFFFFF"><?echo $usuario?></font></td>
			<td align="center" width="24" bgcolor="#000000">
			<font face="Arial" size="1" color="#FFFFFF"><?echo $ufdestinatario?></font></td>
			<td align="center" bgcolor="#000000"><b>
			<font face="Arial" size="1" color="<?Echo $cor_fonte?>"><?echo $statusnota?></font></b>
			</td>
		</tr>
		<?
		}
		?>
	</table>
</div>
<p align="center">&nbsp;</p>



<?
}elseif($bloco=="BARRA"){
?>
<table border="1" width="100%" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="3">
				<p align="center">Últimas&nbsp; 4&nbsp; NF-E</td>
			</tr>

			<tr>
				<td width="1%"></td>
				<td width="98%">
				<table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#000000">
                      <?
    $checa = pg_query("
    		  SELECT
                           doc.id as ID_DOCUMENTO
                          ,docc.numero_nota_cliente as NUMERO_NOTA
                          ,docc.cnpj_emitente as CNPJ_EMITENTE
                          ,docc.cnpj_destinatario as CNPJ_DESTINATARIO
                          ,docc.estado_emissor as UF_EMISSOR
                          ,docc.cfop as CFOP
                          ,to_char(docc.data_emissao,'dd/mm/yyyy HH24:MI:SS') as DATA_EMISSAO
                          ,to_char(doc.data_geracao,'dd/mm/yyyy HH24:MI:SS') as DATA_GERACAO
                          ,st.id as ID_STATUS
                          ,st.descricao as DESCRICAO_STATUS
                          ,doc.numero_eletronico as NUMERO_ELETRONICO
                          ,to_char(com.data_comunicacao,'dd/mm HH24:MI') as DATA_COMUNICACAO
                          ,docc.cnpj_emitente
						  ,docc.cnpj_destinatario
						  ,doc.numero_eletronico as N_ELETRONICO
						  ,(SELECT xmotivo FROM infretconsrecinfe WHERE chnfe = doc.numero_eletronico ORDER BY dhrecbto desc limit 1) as MOTIVO
                         FROM
                           documento_nfe_campos docc
                           , status_doc st
                           , documento doc
                           , comunicacao com
                           where doc.id_status_doc = st.id

                           and docc.numero_eletronico = doc.numero_eletronico
                           and com.id_lote = doc.id_lote
                           and docc.estado_emissor = $uf
                           order by doc.data_geracao desc, com.data_comunicacao desc LIMIT 4");
                     while($dados=pg_fetch_array($checa)){
                               $id_nota = $dados['id_documento'];
                               $nf = $dados['numero_nota'];
                               $data_g = $dados['data_geracao'];
                               $data_c = $dados['data_comunicacao'];
                               $status_1 = $dados['descricao_status'];
                               $status = $dados['motivo'];

                                $status=$status_1;


                               if($status_1=="Autorizado"){
                                 $cor_status="#00FF00";
                               }elseif($status=="Rejeitado"){
                                 $cor_status="#FF0000";
                               }else{
                                 $cor_status="#FFFF00";
                               }


                   ?>

                    <tr>
						<td width="61">
						<p align="center"><a href="?action=sgc_monitor_nfe.php&acao_int=visualizar_nota&id_nota=<?echo $id_nota?>"><font color="#FFFFFF"><?Echo $nf?></a></font></td>
						<td width="121">
						<p align="center"><font color="#FFFFFF"><?echo $data_c?></font></td>
						<td>
						<p align="center"><font color="#00FF00"><?echo $status?></font></td>
					</tr>
					<?
                    }
     ?>
				</table>
				</td>
				<td width="2%">&nbsp;</td>
			</tr>

		</table>
		</td>
	</tr>
</table>
<?


}








