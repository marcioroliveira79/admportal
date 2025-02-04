<?php
OB_START();
session_start();


if($permissao=='ok'){


$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="NF-e CONAB";

$id_item=$_GET['id_item'];
$arquivo="nfe_confere.php";



$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];


if(!isset($acao_int)){

if($_POST['uf_nfe']==null){

   $id_item=$_GET['id_item'];
}else{

   $id_item=$_POST['id_item'];

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


<form method="POST" action="?action=nfe_confere.php&acao_int=sureg" >
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
									<p align="center">Selecione o Estado Emissor</td>
								</tr>
								<tr>
									<td width="300" height="29">
									<p align="center">
									<select size="1" name="uf_nfe"  onchange="this.form.submit();" style="background-color: #FFFFFF">
									<option value="NOT">--</option>
                                    <?
                                    $checa = mysql_query("SELECT * FROM sgc_servidores WHERE nfe='ON' ORDER BY descricao_servidor") or print(mysql_error());
                                          while($dados=mysql_fetch_array($checa)){
                                          $descricao_servidor = $dados['descricao_servidor'];
                                          $uf = $dados['nuf'];
                                    ?>
                                    <option value="<?Echo $uf?>"><?Echo $descricao_servidor?></option>
                                    <?
                                    }
                                    ?>
                                    </select></td>
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
}elseif($acao_int=="sureg"){

if($_POST['uf_nfe']==null){
   $uf=$_GET['uf_nfe'];
   $id_item=$_GET['id_item'];
   $msg=$_GET['msg'];
}else{
   $uf=$_POST['uf_nfe'];
   $id_item=$_POST['id_item'];
   $msg=$_POST['msg'];
}


$sureg=tabelainfo($uf,"sgc_servidores","descricao_servidor","nuf","");
$nf_uf=tabelainfo($uf,"sgc_servidores","uf","nuf","");
$sureg=str_replace("-","",$sureg);

$pg=new sgc_nfe;
$pg->conectar_nfe($sureg)








?>

<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.numero_nf.value=="" && nomeform.chave_nf.value=="")
    {
        alert ("\nO Número da nota fiscal ou chave de acesso é obrigatório.");

        document.form1.numero_nf.style.borderColor="#FF0000";
        document.form1.numero_nf.style.borderWidth="1px solid";

        nomeform.numero_nf.focus();
        return false;
    }
return true;
}
</script>

<script src="prototype.js" type="text/javascript"></script>

<script type='text/javascript'>
function ChamaComboConta(){
   var valor = $F('nfnumero');
   var myAjax = new Ajax.Updater( 'combo_conta' , 'combo_conta_nfe_confere.php' ,
   {
      method : 'post' ,
      parameters: {uf: '<?echo $nf_uf?>', nnf: valor, sureg: '<?echo $sureg?>'  }
   }) ;
}
</script>



<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: <?echo $titulo?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<form method="POST"  action="sgc.php?action=nfe_confere.php&acao_int=sureg" >
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
                            <? if($msg!=null){
                             ?>
                             <font color="#FF0000"><b><?echo $msg ?></b></font></p>
                             <?
                            }
                            ?>

						<table border="1" width="53%" id="table7" bgcolor="#FFFFFF">
							<tr>
								<td width="245">
								<p align="center">Você está na sureg <?Echo $sureg?></td>
								<td>
								<p align="center">&nbsp;Alterar sureg</td>
							</tr>
							<tr>
								<td width="245">&nbsp;</td>
								<td>
								<p align="center">&nbsp;
									<select size="1" name="uf_nfe"  onchange="this.form.submit();" style="background-color: #FFFFFF">
									<option value="NOT">--</option>
                                    <?
                                    $checa = mysql_query("SELECT * FROM sgc_servidores WHERE nfe='ON' ORDER BY descricao_servidor") or print(mysql_error());
                                          while($dados=mysql_fetch_array($checa)){
                                          $descricao_servidor = $dados['descricao_servidor'];
                                          $uf = $dados['nuf'];
                                    ?>
                                    <option value="<?Echo $uf?>"><?Echo $descricao_servidor?></option>
                                    <?
                                    }
                                    ?>
                                    </select>
                                    </form>

								</td>
							</tr>
						</table>
					<p align="center">

                    </p>

						<table border="1" width="41%" id="table8" bgcolor="#FFFFFF">
							<tr>
								<td width="412">
								<p align="left">&nbsp;Procure a nota desejada</td>
							</tr>
							<form method="POST" action="?action=nfe_confere.php&acao_int=result" name="form1" onSubmit="return valida_dados(this)">
							<tr>
								<td width="412">&nbsp;&nbsp;Nº NF:&nbsp<input type="text" name="numero_nf" id='nfnumero' size="9" maxlength="9" onkeyup="ChamaComboConta()">
                                <select size="1" name="combo_conta" id="combo_conta" ><option value="ALL">Todas</option></select>


                                </td>
								<INPUT TYPE="hidden" NAME="sureg" VALUE="<?echo $sureg?>">
							</tr>
							<tr>
								<td width="412">&nbsp;Chave NF:&nbsp;&nbsp;
								<!--webbot bot="Validation" s-data-type="Integer" s-number-separators="." b-value-required="TRUE" i-maximum-length="45" s-validation-constraint="Greater than or equal to" s-validation-value="45" -->
								<input type="text" name="chave_nf" size="45" maxlength="45"></td>
							</tr>
							<tr>
								<td width="412">
								<input type="submit" value="Procurar" name="B1"></td>
							</tr>
							</form>
						</table>
						</td>
				</tr>
			</table></td>
		</tr>
	</table>


<?



}elseif($acao_int=="download_xml"){

   $arquivo=$_POST['chave'];
   
   $pg=new sgc_obj;
      $pg->conectar_obj();

$checa = pg_query("
SELECT
  msg.xml_normal
, count(1) as contador
FROM  eng_mensagem_eletronica msg
WHERE msg.chave_acesso = '$arquivo'
GROUP BY msg.xml_normal
");

while($dados=pg_fetch_array($checa)){
    $xml_normal = $dados['xml_normal'];
      $contador = $dados['contador'];
}



    $aquivoNome = "nfe$arquivo.xml";
    $fp = fopen("nfexml/$aquivoNome","w");
    fwrite($fp,"$xml_normal");
    fclose($fp);



	$novoNome = $aquivoNome;
/*
	// Configuramos os headers que serão enviados para o browser
	header('Content-Description: File Transfer');
	header('Content-Disposition: attachment; filename="'.$novoNome.'"');
	header('Content-Type: application/octet-stream');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize($aquivoNome));
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Expires: 0');

	// Envia o arquivo para o cliente
	readfile($aquivoNome);
*/

}elseif($acao_int=="result"){
  $sql=null;
  $sql_obj=null;
  $sureg=$_POST['sureg'];
  $nf=$_POST['numero_nf'];
  $chave_nf=$_POST['chave_nf'];
  $conta=$_POST['combo_conta'];

  if($nf != null){
   $sql=" AND nf.numeronotafiscal=lpad('$nf',9,'0')";
    if($conta!="ALL"){
      $sql.=" AND (select cpfcnpj from tb_nota_fiscal_agente ag where ag.idnotafiscal=nf.id and ag.tipoagente=1) = '$conta'";
    }
  }elseif($chave_nf != null){
   $sql.=" AND nfe.chaveacessonfe ='$chave_nf' ";
  }


?>

<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.numero_nf.value=="" && nomeform.chave_nf.value=="")
    {
        alert ("\nO Número da nota fiscal ou chave de acesso é obrigatório.");

        document.form1.numero_nf.style.borderColor="#FF0000";
        document.form1.numero_nf.style.borderWidth="1px solid";

        nomeform.numero_nf.focus();
        return false;
    }
return true;
}
</script>

<script src="prototype.js" type="text/javascript"></script>

<script type='text/javascript'>
function ChamaComboConta(){
   var valor = $F('nfnumero');
   var myAjax = new Ajax.Updater( 'combo_conta' , 'combo_conta_nfe_confere.php' ,
   {
      method : 'post' ,
      parameters: {uf: '<?echo $nf_uf?>', nnf: valor, sureg: '<?echo $sureg?>'  }
   }) ;
}
</script>



<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: <?echo $titulo?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<form method="POST"  action="sgc.php?action=nfe_confere.php&acao_int=sureg" >
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>

						<table border="1" width="53%" id="table7" bgcolor="#FFFFFF">
							<tr>
								<td width="245">
								<p align="center">Você está na sureg <?Echo $sureg?></td>
								<td>
								<p align="center">&nbsp;Alterar sureg</td>
							</tr>
							<tr>
								<td width="245">&nbsp;</td>
								<td>
								<p align="center">&nbsp;
									<select size="1" name="uf_nfe"  onchange="this.form.submit();" style="background-color: #FFFFFF">
									<option value="NOT">--</option>
                                    <?
                                    $checa = mysql_query("SELECT * FROM sgc_servidores WHERE nfe='ON' ORDER BY descricao_servidor") or print(mysql_error());
                                          while($dados=mysql_fetch_array($checa)){
                                          $descricao_servidor = $dados['descricao_servidor'];
                                          $uf = $dados['nuf'];
                                    ?>
                                    <option value="<?Echo $uf?>"><?Echo $descricao_servidor?></option>
                                    <?
                                    }
                                    ?>
                                    </select>
                                    </form>

								</td>
							</tr>
						</table>
					<p align="center">

                    </p>

						<table border="1" width="41%" id="table8" bgcolor="#FFFFFF">
							<tr>
								<td width="412">
								<p align="left">&nbsp;Procure a nota desejada</td>
							</tr>
							<form method="POST" action="?action=nfe_confere.php&acao_int=result" name="form1" onSubmit="return valida_dados(this)">
							<tr>
								<td width="412">&nbsp;&nbsp;Nº NF:&nbsp<input type="text" name="numero_nf" id='nfnumero' size="9" maxlength="9" onkeyup="ChamaComboConta()">
                                <select size="1" name="combo_conta" id="combo_conta" ><option value="ALL">Todas</option></select>
                                </td>
								<INPUT TYPE="hidden" NAME="sureg" VALUE="<?echo $sureg?>">
							</tr>
							<tr>
								<td width="412">&nbsp;Chave NF:&nbsp;&nbsp;
								<!--webbot bot="Validation" s-data-type="Integer" s-number-separators="." b-value-required="TRUE" i-maximum-length="45" s-validation-constraint="Greater than or equal to" s-validation-value="45" -->
								<input type="text" name="chave_nf" size="45" maxlength="45"></td>
							</tr>
							<tr>
								<td width="412">
								<input type="submit" value="Procurar" name="B1"></td>
							</tr>
							</form>
						</table>
						</td>
				</tr>
			</table></td>
		</tr>
	</table><BR><BR>
<?
$pg=new sgc_nfe;
      $pg->conectar_nfe($sureg);

$checa = pg_query(" SELECT
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
$sql
ORDER BY nf.id   DESC

                       ");
                           while($dados=pg_fetch_array($checa)){
                               $conta = $dados['conta'];
                               $cnpj = $dados['cnpj'];
                               $idnf = $dados['id'];
                               $numeronotafiscal = $dados['numeronotafiscal'];
                               $datanota = $dados['datanota'];
                               $tempo_retorno = $dados['tempo_retorno'];
                               $entradasaida = $dados['entradasaida'];
                               $valortotalnotafiscal = $dados['valortotalnotafiscal'];
                               $codigooperacao = $dados['codigooperacao'];
                               $nomeoperacao = $dados['nomeoperacao'];
                               $ufdestinatario = $dados['ufdestinatario'];
                               $uforigem = $dados['uforigem'];
                               $statusnota = $dados['statusnota'];
                               $usuario = $dados['usuario'];
                               $chave = $dados['chaveacessonfe'];
                               If($statusnota=="Autorizada"){
                                 $cor_fonte="#008000";
                               }elseIf($statusnota=="Cancelada" OR $statusnota=="Inutilizada"){
                                 $cor_fonte="#FFFF00";
                               }elseIf($statusnota=="Rejeitada"){
                                 $cor_fonte="#FF0000";
                               }elseIf($statusnota=="Transmitida" OR $statusnota=="Gerada"){
                                 $cor_fonte="#00FFFF";
                               }

if($chave!=null || $chave !=""){
  $sql_obj=" AND msg.chave_acesso='$chave' ";
}else{
  $sql_obj=" AND lpad(msg.numero_doc_fiscal,9,0)='$numeronotafiscal' ";
}

 $status_sefaz=null;
 $status_nfe=null;
 $data_emissao_obj=null;
 $data_sefaz_rec=null;





if($chave!=null || $chave !=""){

  $sureg_acesso=strtolower($sureg);

  //shell_exec("sudo /var/www/xfac/sgc/copia_xml.sh $sureg_acesso $chave");

$arquivo=$_POST['chave'];

   $pg=new sgc_obj;
      $pg->conectar_obj();

$checa = pg_query("
SELECT
  msg.xml_normal
, count(1) as contador
FROM  eng_mensagem_eletronica msg
WHERE msg.chave_acesso = '$chave'
GROUP BY msg.xml_normal
");

while($dados=pg_fetch_array($checa)){
    $xml_normal = $dados['xml_normal'];
    $contador = $dados['contador'];
}



    $diretorio = getcwd();
    $aquivoNome = "nfe$chave.xml";
    echo "$diretorio/nfexml/$aquivoNome";
    if($contador > 0 ){
      $fp = fopen("$diretorio/nfexml/$aquivoNome","w");
      fwrite($fp,"$xml_normal");
      fclose($fp);
    }






  $homepage = file_get_contents("http://10.1.0.105/nfe/exemplos/consulta.php?cUF=$uforigem&idNFe=$chave");

if ($homepage !="FALHA"){
preg_match('/<xMotivo>.*<\/xMotivo>/i', $homepage, $aut);
preg_match('/<cStat>.*<\/cStat>/i', $homepage, $aut_stat);
preg_match('/<dhRecbto>.*<\/dhRecbto>/i', $homepage, $data_sefaz);

foreach ($data_sefaz as $value) {
    $data_sefaz_rec = $value;
}
foreach ($aut_stat as $value) {
    $status_sefaz_cod = $value;
}
foreach ($aut as $value) {
    $status_sefaz = $value;
}

$status_sefaz_cod=trim($status_sefaz_cod);

  if($status_sefaz_cod=="100"){
     $cor_fonte_sefaz="#008000";
     $status_sefaz="Autorizada";
  }else{

     $cor_fonte_sefaz="#FF0000";
  }

 }else{
     $status_sefaz="Falhou na Pesquisa";
     $cor_fonte_sefaz="#FFCC00";
}

}

$pg=new sgc_obj;
      $pg->conectar_obj();




$checa1 = pg_query(" SELECT
 res.descricao
,le.status_nfe
,lpad(msg.numero_doc_fiscal,9,0) as numero_nota
,msg.cnpj_emit
,msg.razao_social_emitente
,msg.data_emissao
,msg.uf_receptora
,msg.municipio
FROM
  eng_mensagem_eletronica msg
, nf_nfe nf
, nf_lote_nfe nfe
, nf_lote nfl
, eng_lote_mensagem_eletronica le
, nfe_resultado_processamento res
WHERE  1=1
$sql_obj
AND nf.id_mensagem_eletronica = msg.id_mensagem_eletronica
AND nfe.id_lote_mensagem_eletronica = nf.id_lote_me_vigente
AND nfl.id_lote = nfe.lote_id
AND le.id_lote_mensagem_eletronica = nfe.id_lote_mensagem_eletronica
AND res.codigo = le.retorno_nfe
AND msg.cnpj_emit ='$cnpj'

                       ");
                           while($dados1=pg_fetch_array($checa1)){
                               $status_nfe = $dados1['descricao'];
                               $data_emissao_obj = $dados1['data_emissao'];



                          }



?>

     <table border="1" width="501" id="table9" bgcolor="#FFFFFF">
							<tr>
								<td width="491" colspan="3">
								<p align="center">Resultado</td>
							</tr>
							<tr>
								<td width="491" colspan="3">
                                Nota valor R$: <?echo $valortotalnotafiscal?><BR>
								Data: <?echo $datanota?><BR>
                                Conta: <?echo $conta?><BR>
                                Nº NF: <a href="javascript:%20void(window.open('open_nf.php?&action=NOTA&id_nf=<?echo $idnf?>&sureg=<?echo $sureg?>','Visualizar','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=665,height=530'));"><?echo $numeronotafiscal?></a></td>
								<INPUT TYPE="hidden" NAME="sureg0" VALUE="<?echo $sureg?>">
							</tr>
							<tr>
								<td width="491" colspan="3">&nbsp;Chave de acesso: <?echo $chave?></td>
							</tr>
							<tr>
								<td width="169">
								<p align="center">Status xFac</td>
								<td width="158">
								<p align="center">Status OOBJ</td>
								<td width="152">
								<p align="center">Status Sefaz</td>
							</tr>
							<tr>
								<td width="169">
								<p align="center"><font color="<?echo $cor_fonte?>"><b><?echo $statusnota?></b></font></td>
								<td width="158">
								<p align="center"><font color="<?echo $cor_fonte_obj?>"><b><?echo $status_nfe?></b></td>
								<td width="152">
								<p align="center"><font color="<?echo $cor_fonte_sefaz?>"><b><?echo $status_sefaz?></td>
							</tr>
							<tr align="center">
								<td width="169"><?echo $datanota?></td>
								<td width="158"><?echo data_with_hour($data_emissao_obj)?></td>
								<td width="152"><?echo data_with_hour($data_sefaz_rec)?></td>
							</tr>
							<tr align="center">
								<td width="169">
								<form method="POST" action="nfePHP/printDANFE.php" target="_blank"><p>
								<input type='hidden' name='chave' value='<?echo $chave?>'>
								<input type="submit" value="DANFE" name="danfe" <? if($statusnota!="Autorizada" || $contador == null || $contador < 1){ echo "Disabled"; } ?> ></td>
								</form>
								<td width="158">
								<form method="POST" action="?action=nfe_confere.php&acao_int=download_xml" target="_blank"><p>
								<p align="center">
    							<input type='hidden' name='chave' value='<?echo $chave?>'>
								<input type="submit" value="E-mail Xml" name="danfe0"  <? if($statusnota!="Autorizada" || $contador == null || $contador < 1){ echo "Disabled"; } ?> ></p>
							</td>
                                </form>
								<td width="152">&nbsp;</td>
							</tr>

						</table><p>

<?
}
?>
						

<?

if(trim($status_nfe) == null || trim($status_nfe)=="" && $perfil!="CUSTOMIZADO" || $idusuario=="16182"){
?>
<form method="POST" action="?action=nfe_confere.php&acao_int=enviarxml">
<div align="center">
<table border="1" width="501" id="table9" bgcolor="#FFFFFF">
							<tr>
								<td width="491">
								<p align="center"><font color="#FF0000">ERRO</font></td>
							</tr>
							<input type='hidden' name='chave' value='<?echo $chave?>'>
							<input type='hidden' name='nfe_uf' value='<?echo $sureg?>'>
							<tr>
								<td width="491">
                                <p align="center">Essa nota esta com problema no oobj deseja reprocessar a mesma?</td>
							</tr>
							<tr>
								<td width="491">
								<p align="center">
								<input type="submit" value="Reprocessar" name="B1"></td>
							</tr>

						</table>
</div>
</form>
<?
}




$pg=new sgc_obj;
      $pg->conectar_obj();
      
      



}elseif($acao_int=="enviarxml"){
 $chave=$_POST['chave'];
 $uf=$_POST['nfe_uf'];

$sureg=$uf;
$uf=substr($uf,-2);
$nuf=tabelainfo($uf,"sgc_servidores","nuf","uf","");


$pg=new sgc_obj_backup;
   $pg->conectar_obj_backup();
 
$checa = pg_query("
SELECT
  msg.xml_normal
, count(1) as contador
FROM  eng_mensagem_eletronica msg
WHERE msg.chave_acesso = '$chave'
GROUP BY msg.xml_normal
");

while($dados=pg_fetch_array($checa)){
    $xml_normal = $dados['xml_normal'];
      $contador1 = $dados['contador'];
}

if($contador1 < 1 ||  $contador1 == null ){
       $msg_0="Recuperado direto da $sureg";
       exec("sudo /var/www/xfac/sgc/copia_xml.sh $sureg $chave",$resultado);

}elseif($contador1 > 0 ){
       $msg_0="Recuperado atravéz da base de backup";
      /* Monta o nome de arquivo */
	   $nome_arquivo="nfe$chave.xml";
       /* Monta nome de diretorio para o arquivo */
       $diretorio = getcwd()."/nfexml/reprocessadas";
       /* Retira assinatura digital do xml */
	   $xml_normal_sem_assinatura=preg_replace("/<Signature(.*?)\<\/Signature\>/s","",$xml_normal);


        $arquivo = "$diretorio/$nome_arquivo";
        /* Abre arquivo insere conteudo e grava */
        $fp = fopen("$diretorio/$nome_arquivo","w");
        fwrite($fp,"$xml_normal_sem_assinatura");
        fclose($fp);

        /* Redireciona para tela de informações sobre recuperação */
        $destino="root@$sureg:/marcior/";
        $msg="Foi recuperado o xml da base de backup";
        exec("sudo /var/www/xfac/sgc/nfexml/reprocessadas/copia_xml.sh $arquivo $destino",$resultado);


}

 if(!file_exists("/var/www/xfac/sgc/nfexml/reprocessadas/nfe$chave.xml")){
       $msg="Não foi possível localizar o xml dessa nota";
       header("Location: ?action=nfe_confere.php&acao_int=sureg&msg=$msg&uf_nfe=$nuf");
 }
       $msg="$msg_0";

	   $nome_arquivo="nfe$chave.xml";
       $diretorio = getcwd()."/nfexml/reprocessadas";
	   $xml_normal_sem_assinatura=preg_replace("/<Signature(.*?)\<\/Signature\>/s","",$xml_normal);

       $arquivo = "$diretorio/$nome_arquivo";

        $fp = fopen("$diretorio/$nome_arquivo","w");
        fwrite($fp,"$xml_normal_sem_assinatura");
        fclose($fp);


$destino="root@$sureg:/home/nfe/saida/";
exec("sudo /var/www/xfac/sgc/nfexml/reprocessadas/copia_xml.sh $arquivo $destino",$resultado);
header("Location: ?action=nfe_confere.php&acao_int=sureg&msg=XML Enviado, Aguarde! $msg&uf_nfe=$nuf");


}
}else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
