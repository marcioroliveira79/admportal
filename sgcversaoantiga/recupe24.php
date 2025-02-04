<?php
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
       $conexao_nfe = pg_connect("host=$sureg port=5432 dbname=bd_xfac user=postgres password=postmy") or die ("Não foi possível conectar com o $sureg!");
      }
}

class sgc_obj {
      var $conexao_obj;
      function conectar_obj()
      {
       $conexao_nfe = pg_connect("host=10.1.0.109 port=5432 dbname=oobj_nfe_central user=marcioroliveira password=123456") or die ("Não foi possível conectar com o PostGre!");
      }
}


$mysql=new sgc;
       $mysql->conectar();
       
//  UF in ('MT','PR','SC','MG','PB','CE','RN','RO','AM','RR','RS','MS','AL','DF','BA','ES','MA','PA','PE','PI','RJ','SP','TO','GO','SE','AC')
$checa = mysql_query("SELECT ip_host,uf, LOWER(REPLACE(descricao_servidor,'-','')) as descricao_servidor FROM sgc_servidores WHERE UF in ('MT','PR','SC','MG','PB','CE','RN','RO','AM','RR','RS','MS','AL','DF','BA','ES','MA','PA','PE','PI','RJ','SP','TO','GO','SE','AC')   ") or print(mysql_error());
         while($dados=mysql_fetch_array($checa)){
        $ip_host = $dados['ip_host'];
        $uf = $dados['uf'];
        $descricao_servidor = $dados['descricao_servidor'];

echo "$uf<BR>";

         $pg=new sgc_nfe;
            $pg->conectar_nfe($descricao_servidor);
            

        $checa_pg = pg_query("
        SELECT
 '('||cn.codigo||'.'||at.codigo||'.'||ft.codigo||')' as conta
,nf.id
,nfe.chaveacessonfe
,(select cpfcnpj from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=1) as cnpj
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
,nfe.chaveacessonfe
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
AND nf.datanota BETWEEN '2011-01-22' AND '2011-03-10'
ORDER BY nf.id   DESC

        ");
               while($dados_pg=pg_fetch_array($checa_pg)){
               $conta = $dados_pg['conta'];
               $datanota = $dados_pg['datanota'];
               $chave = $dados_pg['chaveacessonfe'];
               $status_nfe = $dados_pg['statusnota'];
               $numeronf = $dados_pg['numeronotafiscal'];

                  $contador=null;
         $pg=new sgc_obj;
            $pg->conectar_obj();

                    $checa_pg1 = pg_query("SELECT
                         msg.xml_normal
                       , count(1) as contador
                       FROM  eng_mensagem_eletronica msg
                       WHERE msg.chave_acesso = '$chave'
                       GROUP BY msg.xml_normal");
                       while($dados_pg1=pg_fetch_array($checa_pg1)){
                         $contador = $dados_pg1['contador'];
                     }
          if($contador > 0 ){
               echo " $conta - $chave - $status_nfe - $datanota - $numeronf<BR>";
          }else{
                  if($status_nfe=="Cancelada"){
                    $chave_nome="$chave-ped-can.xml";
                  }else{
                    $chave_nome="nfe$chave.xml";
                  }
                   exec("sudo /var/www/xfac/sgc/copia_xml_livre.sh $descricao_servidor $chave_nome",$resultado);
                   echo " $conta - $chave - $status_nfe - $datanota - $numeronf - Não Existe Na Base OOBJ <BR>";
          }
                     

        }

}
