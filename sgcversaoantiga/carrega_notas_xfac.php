<?php
OB_START();
session_start();

class sgc {
      var $conexao;
      function conectar()
      {
       $conexao = mysql_connect('mysql.conab.gov.br','xfac','xfacsalvador') or die ("Não foi possível conectar com o MySQL!");
                   mysql_select_db('db_sgc') or die ("Banco de dados inexistente - Conecta");
     }
}

class sgc_nfe {
      var $conexao_nfe;
      function conectar_nfe($sureg)
      {
       $conexao_nfe = pg_connect("host=$sureg port=5432 dbname=bd_xfac user=postgres password=postmy") or die (" Não foi possível conectar com o PostGres! ");
      }
}


$mysql=new sgc;
      $mysql->conectar();

$total_notas_aturizadas=0;
$total_notas_canceladas=0;
$checa = mysql_query("SELECT * FROM sgc_servidores WHERE nfe='ON' AND uf != '' AND status='ON' ORDER BY descricao_servidor") or print(mysql_error());
         while($dados=mysql_fetch_array($checa)){
         $descricao_servidor = $dados['descricao_servidor'];
         $nuf = $dados['nuf'];
         $uf = $dados['uf'];
         $ip_host = $dados['ip_host'];

$pg=new sgc_nfe;
      $pg->conectar_nfe($ip_host);

// TO_CHAR(DATE_TRUNC('day',NOW() - INTERVAL '4 DAYS'),'dd/mm/yyyy')
$checa_sureg = pg_query("
SELECT
 nf.id
,ct.uf
,cn.codigo as cn_codigo
,at.codigo as at_codigo
,ft.codigo as ft_codigo
,nfe.chaveacessonfe
,(select cpfcnpj from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=1) as cnpj_origem
,nfe.protocoloautorizacao
,nf.numeronotafiscal
,nf.datanota
,nfe.datahorarecibo
,nf.serienotafiscal
,nf.entradasaida
,nf.cfop
,nf.valortotalnotafiscal AS valortotalnotafiscal
,op.codigo as codigooperacao
,op.nome as nomeoperacao
,us.usuario as usuario
,nfe.textorecibo
,ct.uf as uforigem
,it.sequenciaitem

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
,(select cidade from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=2) as cidadedestinatario
,(select uf from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=2) as ufdestinatario
,(select cidade from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=1) as cidadeemitente
,(select uf from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=1) as ufemitente
,pr.codigo
,pr.nome
,it.unidademedida
,it.quantidade
,it.valortotal
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
 , tb_nota_fiscal_item it
 , tb_produto pr
WHERE nf.id = nfe.idnotafiscal
AND ct.id = nf.idconta
AND pr.id = it.idproduto
AND re.id = nf.idregra
AND ft.id = ct.idfonte
AND it.idnotafiscal = nf.id
AND at.id = ct.idatividade
AND cn.id = ct.idcnpj
AND re.idoperacao = op.id
AND us.id = nf.idusuario
AND TO_CHAR(DATE_TRUNC('day',nf.datagravacao),'dd/mm/yyyy') = TO_CHAR(DATE_TRUNC('day',NOW()),'dd/mm/yyyy')
ORDER BY nf.id DESC,it.sequenciaitem, nf.datagravacao DESC");
         while($dados_sureg=pg_fetch_array($checa_sureg)){
         $id = $dados_sureg['id'];
         $ufnota = $dados_sureg['uf'];
         $cn_codigo = $dados_sureg['cn_codigo'];
         $at_codigo = $dados_sureg['at_codigo'];
         $ft_codigo = $dados_sureg['ft_codigo'];
         $chaveacessonfe = $dados_sureg['chaveacessonfe'];
         $protocoloautorizacao = $dados_sureg['protocoloautorizacao'];
         $numeronotafiscal = $dados_sureg['numeronotafiscal'];
         $datanota = $dados_sureg['datanota'];
         $datahorarecibo = $dados_sureg['datahorarecibo'];
         $serienotafiscal = $dados_sureg['serienotafiscal'];
         $entradasaida = $dados_sureg['entradasaida'];
         $cfop = $dados_sureg['cfop'];
         $valortotalnotafiscal = $dados_sureg['valortotalnotafiscal'];
         $codigooperacao = $dados_sureg['codigooperacao'];
         $nomeoperacao = $dados_sureg['nomeoperacao'];
         $usuario = $dados_sureg['usuario'];
         $textorecibo = $dados_sureg['textorecibo'];
         $uforigem = $dados_sureg['uforigem'];
         $statusnota = trim($dados_sureg['statusnota']);
         $cidadedestinatario = $dados_sureg['cidadedestinatario'];
         $ufdestinatario = $dados_sureg['ufdestinatario'];
         $cidadeemitente = $dados_sureg['cidadeemitente'];
         $ufemitente = $dados_sureg['ufemitente'];
         $codigo = $dados_sureg['codigo'];
         $nome = $dados_sureg['nome'];
         $unidademedida = $dados_sureg['unidademedida'];
         $quantidade = $dados_sureg['quantidade'];
         $valortotal = $dados_sureg['valortotal'];
         $sequenciaitem = $dados_sureg['sequenciaitem'];
         $cnpj_origem = $dados_sureg['cnpj_origem'];



$contador_nota=0;
$checa_nota = mysql_query("SELECT
                           count(1) as contador
                           FROM sgc_nota_xfac nf
                           WHERE nf.idnota  = $id
                           AND nf.uf = '$ufnota'
                           AND nf.cod_programa='$cn_codigo'
                           AND nf.cod_atividade='$at_codigo'
                           AND nf.cod_fonte='$ft_codigo'
                           AND nf.status_nota='$statusnota'


                           ") or print(mysql_error());
         while($dados_nota=mysql_fetch_array($checa_nota)){
                       $contador_nota = $dados_nota['contador'];
         }

if($contador_nota<1){


//----------------------------------Checar se houve mudança no registro--------------------------//
$apaga_status = mysql_query("DELETE FROM sgc_nota_xfac  WHERE idnota  = $id AND uf = '$ufnota'");
//------------------------------------------------------------------------------------------------//

//----------------------------------Checar se houve mudança no registro--------------------------//
$apaga_status = mysql_query("DELETE FROM sgc_nota_xfac_item  WHERE idnota = $id AND uf = '$ufnota'");
//------------------------------------------------------------------------------------------------//


            $cad = mysql_query("INSERT INTO sgc_nota_xfac
                                           (idnota
                                           , uf
                                           , cnpj_origem
                                           , cod_programa
                                           , cod_atividade
                                           , cod_fonte
                                           , chave_acesso
                                           , protocolo_autorizacao
                                           , numero_nota_fiscal
                                           , data_nota
                                           , data_recibo
                                           , serie_nota_fiscal
                                           , entrada_saida
                                           , cfop
                                           , valor_total_nota_fiscal
                                           , cod_operacao
                                           , nome_operacao
                                           , usuario
                                           , texto_recibo
                                           , status_nota
                                           , cidade_emitente
                                           , uf_emitente
                                           , cidade_destinatario
                                           , uf_destinatario
                                           , data_inclusao


                                           )  VALUES
                                           ($id
                                           ,'$ufnota'
                                           ,'$cnpj_origem'
                                           ,'$cn_codigo'
                                           ,'$at_codigo'
                                           ,'$ft_codigo'
                                           ,'$chaveacessonfe'
                                           ,'$protocoloautorizacao'
                                           ,'$numeronotafiscal'
                                           ,'$datanota'
                                           ,'$datarecibo'
                                           ,'$serienotafiscal'
                                           ,'$entradasaida'
                                           ,'$cfop'
                                           ,'$valortotalnotafiscal'
                                           ,'$codigooperacao'
                                           ,'$nomeoperacao'
                                           ,'$usuario'
                                           ,'$textorecibo'
                                           ,'$statusnota'
                                           ,'$cidadeemitente'
                                           ,'$ufemitente'
                                           ,'$cidadedestinatario'
                                           ,'$ufdestinatario'

                                           ,sysdate())") or print(mysql_error());

                                           $ultimo_id = mysql_insert_id();
}

$contador_item=0;
$checa_nota_item = mysql_query("SELECT
                           count(1) as contador
                           FROM sgc_nota_xfac nf, sgc_nota_xfac_item nfi
                           WHERE nf.idnota  = $id
                           AND nfi.idnota = nf.idnota
                           AND nf.uf = '$ufnota'
                           AND nf.cod_programa='$cn_codigo'
                           AND nf.cod_atividade='$at_codigo'
                           AND nf.cod_fonte='$ft_codigo'
                           AND nfi.sequencia_item=$sequenciaitem

                           ") or print(mysql_error());
         while($dados_nota_item=mysql_fetch_array($checa_nota_item)){
                       $contador_item = $dados_nota_item['contador'];
         }

if($contador_item<1){

                                           $cad = mysql_query("INSERT INTO sgc_nota_xfac_item
                                           ( idnotasgc
                                           , idnota
                                           , uf
                                           , cod_produto
                                           , nome_produto
                                           , sequencia_item
                                           , unidade_medida
                                           , quantidade
                                           , valor_total
                                           , data_inclusao)
                                           VALUES
                                           ( $ultimo_id
                                           , $id
                                           ,'$ufnota'
                                           ,'$codigo'
                                           ,'$nome'
                                           , $sequenciaitem
                                           ,'$unidademedida'
                                           ,'$quantidade'
                                           ,'$valortotal'
                                           ,sysdate()

                                           )") or print(mysql_error());


}

   }


}

?>
