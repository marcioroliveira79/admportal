<?php
OB_START();
session_start();


if($permissao=='ok'){


$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="NF-e CONAB";

$id_item=$_GET['id_item'];
$arquivo="nfe_pesquisa.php";



$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];


if(!isset($acao_int)){


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
<script language='javascript'>
function mascaraTexto(evento, mascara){

   var campo, valor, i, tam, caracter;

   if (document.all) // Internet Explorer
      campo = evento.srcElement;
   else // Nestcape, Mozzila
       campo= evento.target;

   valor = campo.value;
   tam = valor.length;

   for(i=0;i<mascara.length;i++){
      caracter = mascara.charAt(i);
      if(caracter!="9")
         if(i<tam & caracter!=valor.charAt(i))
            campo.value = valor.substring(0,i) + caracter + valor.substring(i,tam);

   }

}
</script>

<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.data_inicio.value=="" || nomeform.data_final.value=="")
    {
        alert ("\nDigite a data inicial e final para pesquisa.");

        document.form1.data_inicio.style.borderColor="#FF0000";
        document.form1.data_inicio.style.borderWidth="1px solid";

        nomeform.data_inicio.focus();
        return false;
    }
return true;
}
</script>

<form method="POST" name="form1" action="?action=nfe_pesquisa.php&acao_int=busca" onSubmit="return valida_dados(this)">
<input type='hidden' name='id_item' value='<?echo $id_item?>'>
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Confere NF-e :: </b></td>
				</tr>
                <input type='hidden' name='id_item' value='<?echo $id_item?>'>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="1" width="300" cellspacing="0" cellpadding="0" style="border-collapse: collapse" bordercolor="#C0C0C0" bgcolor="#FFFFFF">
						<tr>
							<td>
							<table border="0" width="300" cellspacing="0" cellpadding="0" height="58">
								<tr>
									<td>
									<p align="center">Selecione a data de
									pesquisa</td>
								</tr>
								<tr>
									<td width="300" height="29">
									<p align="center">
									&nbsp;<p align="center">
									<input type="text" name="data_inicio" size="10"  maxlength="10" onKeyUp="mascaraTexto(event,'99/99/9999')">
									á
									<input type="text" name="data_final" size="10"  maxlength="10" onKeyUp="mascaraTexto(event,'99/99/9999')"><p align="center">
									<button name="B1">Pesquisar</button>
									<p align="center">
									&nbsp;</td>
								</tr>
							</table>
							</td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>
</form>
<?
}elseif($acao_int=="busca"){

$data_inicio=databd($_POST['data_inicio']);
$data_final=databd($_POST['data_final']);
$count=0;
$count_divergencia=0;
/*
$pg=new sgc_obj;
      $pg->conectar_obj();
*/

$pg=new sgc_etl;
   $pg->conectar_etl();

?>
    <table border="1" width="100%" id="table1">
	<tr>
		<td width="10">&nbsp;Conta Op.</td>
		<td width="30">&nbsp;Nº Nota</td>
		<td width="120">&nbsp;Data</td>
		<td width="250">&nbsp;Status xFac</td>
		<td width="200">&nbsp;Status OBJ</td>
		<td>&nbsp;Status Sefaz</td>
	</tr>
<?


$checa = pg_query("
SELECT
   nf.datagravacao
  ,nf.numeronotafiscal
  ,ct.uf
  ,nfe.textorecibo as status
  ,nfe.chaveacessonfe
  ,xfacweb.fc_conta_operacional(nf.idconta,'conta') as conta
  ,nfe.statusrecibo
FROM
  xfacweb.tb_nota_fiscal nf
 ,xfacweb.tb_conta ct
 ,xfacweb.tb_nota_fiscal_eletronica nfe
WHERE 1=1
 AND nf.datagravacao BETWEEN '$data_inicio' AND '$data_final'
 AND ct.id = nf.idconta
 AND nf.id = nfe.idnotafiscal
ORDER BY nf.datagravacao ASC");
                           while($dados=pg_fetch_array($checa)){
                               $datagravacao = $dados['datagravacao'];
                               $numeronotafiscal = $dados['numeronotafiscal'];
                               $uf = $dados['uf'];
                               $conta = $dados['conta'];
                               $status = $dados['status'];
                               $statusrecibo = $dados['statusrecibo'];
                               $chaveacessonfe = $dados['chaveacessonfe'];
                               $count++;

/*-------------Busca no obj-----------*/
$statusobj=0;
$codigoobj=0;

$pg=new sgc_obj;
      $pg->conectar_obj();

$checa_obj = pg_query("SELECT
 res.descricao
,le.status_nfe
,lpad(msg.numero_doc_fiscal,9,0) as numero_nota
,msg.cnpj_emit
,msg.razao_social_emitente
,msg.data_emissao
,msg.uf_receptora
,msg.municipio
,res.codigo
FROM
  eng_mensagem_eletronica msg
, nf_nfe nf
, nf_lote_nfe nfe
, nf_lote nfl
, eng_lote_mensagem_eletronica le
, nfe_resultado_processamento res
WHERE  1=1
AND msg.chave_acesso='$chaveacessonfe'
AND nf.id_mensagem_eletronica = msg.id_mensagem_eletronica
AND nfe.id_lote_mensagem_eletronica = nf.id_lote_me_vigente
AND nfl.id_lote = nfe.lote_id
AND le.id_lote_mensagem_eletronica = nfe.id_lote_mensagem_eletronica
AND res.codigo = le.retorno_nfe
");
   while($dados_obj=pg_fetch_array($checa_obj)){
   $statusobj = $dados_obj['descricao'];
   $codigoobj = $dados_obj['codigo'];
}

if($statusobj==null){

$checa_obj = pg_query("SELECT count(1) as cont FROM   eng_mensagem_eletronica msg
                       WHERE  1=1
                       AND msg.chave_acesso='$chaveacessonfe'
                       ");
                       while($dados_obj=pg_fetch_array($checa_obj)){
                          $cont = $dados_obj['cont'];
                       }
                       if($cont==1){
                         $statusobj="Perdeu vinculo";
                       }else{
                         $statusobj="Não existe na base obj";
                         $count_divergencia++;
                       }
                       
}



if($codigoobj!=$statusrecibo){
 $corlinha="bgcolor='#FF0000'";

}else{
 $corlinha="";
}

 /*
$homepage = file_get_contents("http://10.1.0.105/nfe/exemplos/consulta.php?cUF=$uf&idNFe=$chaveacessonfe");

if ($homepage !="FALHA"){
preg_match('/<xMotivo>.*<\/xMotivo>/i', $homepage, $aut);
preg_match('/<cStat>.*<\/cStat>/i', $homepage, $aut_stat);

foreach ($aut_stat as $value) {
    $status_sefaz_cod = $value;
}
foreach ($aut as $value) {
    $status_sefaz = $value;
}

}ELSE{
 $status_sefaz="FALHA AO PESQUISAR";
}

 */


                               ?>
	<tr>
		<td width="110" <?echo $corlinha?> >&nbsp;<?echo $conta?></td>
		<td width="107" <?echo $corlinha?> >&nbsp;<?echo "$numeronotafiscal - $uf"?></td>
		<td width="107" <?echo $corlinha?> >&nbsp;<?echo $datagravacao?></td>
		<td width="132" <?echo $corlinha?> >&nbsp;<?echo $status?></td>
		<td width="100" <?echo $corlinha?> >&nbsp;<?echo $statusobj?></td>
		<td>&nbsp;<?echo $status_sefaz?></td>
	</tr>
<?
 }
?>
</table>
<?
echo $count_divergencia;




}
}else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
