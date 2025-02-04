<?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("conf/conecta.php");
include("conf/funcs.php");

$sureg=$_POST['sureg'];
$uf=$_POST['uf'];
$nf=$_POST['nnf'];



$pg=new sgc_nfe;
      $pg->conectar_nfe($sureg);




?>


                                    <?
                                    $checa = pg_query("
                                    SELECT
                                     '('||cn.codigo||'.'||at.codigo||'.'||ft.codigo||')' as conta
                                     ,nf.numeronotafiscal
                                     ,TO_CHAR(nf.datanota,'dd/mm/yyyy HH24:MM:SS')  as datanota
                                     ,(select cpfcnpj from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=1) as cnpj
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
                                                AND nf.numeronotafiscal = lpad('$nf',9,'0')
                                                ORDER BY nf.id   DESC
                                    ");
                                          while($dados=pg_fetch_array($checa)){
                                          $conta = $dados['conta'];
                                          $datanota = $dados['datanota'];
                                          $cnpj = $dados['cnpj'];
                                    ?><option value="<?php Echo $cnpj?>"><?php Echo "$conta - $datanota"?></option><?
                                    }
                                    ?>
                                    <option value="ALL">Todas</option>
                                    <?

