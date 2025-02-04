<?php
OB_START();
session_start();


if($permissao=='ok'){

$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

?>
<script src="prototype.js" type="text/javascript"></script>
<?
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


<form method="POST" name="form1" action="sgc.php?action=sgc_monitor_nfe.php&acao_int=notas" onSubmit="return valida_dados(this)">
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Monitor NF-e :: </b></td>
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
}elseif($acao_int=="visualizar_nota"){

$id_nota=$_GET['id_nota'];

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
                          ,to_char(com.data_comunicacao,'dd/mm/yyyy HH24:MI:SS') as DATA_COMUNICACAO
                          ,docc.cnpj_emitente
						  ,docc.cnpj_destinatario
						  ,doc.numero_eletronico as N_ELETRONICO
                            ,CASE WHEN st.descricao !='Autorizado' Then
					      	  (SELECT xmotivo FROM infretconsrecinfe WHERE chnfe = doc.numero_eletronico ORDER BY dhrecbto desc limit 1)
						     ELSE ''
						     END as MOTIVO
                         FROM
                           documento_nfe_campos docc
                           , status_doc st
                           , documento doc
                           , comunicacao com

                           where doc.id = $id_nota
                           and doc.id_status_doc = st.id
                           and docc.numero_eletronico = doc.numero_eletronico
                           and com.id_lote = doc.id_lote
                           order by doc.data_geracao desc, com.data_comunicacao desc");
                           while($dados=pg_fetch_array($checa)){
                               $id_nota = $dados['id_documento'];
                               $nnf = $dados['numero_nota'];
                               $data_g = $dados['data_geracao'];
                               $data_c = $dados['data_comunicacao'];
                               $status = $dados['descricao_status'];
                               $n_eletronico_busca = $dados['n_eletronico'];
                               $uf = $dados['uf_emissor'];
                               $cnpj_emissor = $dados['cnpj_emitente'];
                               $cnpj_destinatario = $dados['cnpj_destinatario'];
                               $cfop = $dados['cfop'];
                               $motivo = $dados['motivo'];

                               if($status=="Autorizado"){
                                 $cor_status="#00FF00";
                               }elseif($status=="Rejeitado"){
                                 $cor_status="#FF0000";
                               }else{
                                 $cor_status="#FFFF00";
                               }
                          }




if(strlen($cnpj_emissor)<14){
  $func_e="cpf";
  $campo_e="CPF";
}else{
  $func_e="cnpj";
  $campo_e="CNPJ";
}

if(strlen($cnpj_destinatario)<14){
  $func_d="cpf";
  $campo_d="CPF";
}else{
  $func_d="cnpj";
  $campo_d="CNPJ";
}


?>
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
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Monitor NF-e :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<form method="POST" action="?action=sgc_monitor_nfe.php&acao_int=notas">
                       <input type='hidden' name='id_item' value='<?echo $id_item?>'>
                       <input type='hidden' name='uf_nfe' value='<?echo $uf?>'>
                        <table border="1" width="600" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
						<tr>
							<td width="90" height="23">
							<table border="0" width="596" cellspacing="0" cellpadding="0">
								<tr>
									<td colspan="2">
									<p align="center">Número NF: <?echo $nnf?></td>
								</tr>
								<tr>
									<td width="130">
									<p align="right">UF Emissor:</td>
									<td width="466">
									&nbsp;<input type="text" name="uf" value="<?echo $uf?>" readonly size="2"></td>
								</tr>
								<tr>
									<td width="130">
									<p align="right"><?echo $campo_e?> Emissor:</td>
									<td width="466">
									&nbsp;<input type="text" name="cnpj_emissor" value="<?echo $cnpj_emissor?>" readonly size="15">
                                    <?if($validate->$func_e($cnpj_emissor))
                                    print "";
                                    else
                                    print "<font color='#FF0000'> $func_e Inválido!</font>";
                                    ?>
                                    </td>
								</tr>
								<tr>
									<td width="130">
									<p align="right"><?echo $campo_d?> Destino:</td>
									<td width="466">
									&nbsp;<input type="text" name="cnpj_destinatario"  value="<?echo $cnpj_destinatario?>" readonly size="15">
                                    <?if($validate->$func_d($cnpj_destinatario))
                                    print "";
                                    else
                                    print "<font color='#FF0000'> $func_d Inválido!</font>";
                                    ?>
                                    </td>
								</tr>
								<tr>
									<td width="130">
									<p align="right">CFOP:</td>
									<td width="466">
									&nbsp;<input type="text" name="cfpo" value="<?echo $cfop?>"  readonly size="4"></td>
								</tr>
								<tr>
									<td width="130">
									<p align="right">Nº Eletrônico:</td>
									<td width="466">
									&nbsp;<input type="text" name="n_eletronico" value="<?echo $n_eletronico_busca?>" readonly size="50"></td>
								</tr>
								<tr>
									<td width="130">
									<p align="right">Data Geração:</td>
									<td width="466">&nbsp;<input type="text" name="data_geracao" value="<?echo $data_g?>" readonly size="19"></td>
								</tr>
								<tr>
									<td width="130">
									<p align="right">Data Envio Sefaz:</td>
									<td width="466">&nbsp;<input type="text" name="data_envio_sefaz" value="<?echo $data_c?>" readonly size="19"></td>
								</tr>
								<tr>
									<td width="130">
									<p align="right">Status:</td>
									<td width="466">&nbsp;<input type="text" name="status" value="<?echo $status?>" readonly size="14" style="background-color: <?echo $cor_status?>"></td>
								</tr>
								<?
								if($motivo!=null){
								?>
								<tr>
									<td width="130">
									<p align="right">Motivo:</td>
									<td width="466">&nbsp;<?echo $motivo?></td>
								</tr>
								<?
								}
								?>
								<tr>
									<td width="596" colspan="2">
									<p align="center">
									<input type="button" value="Espelho NF" name="B1"  ></td>
								</tr>
								<tr>
									<td width="130">&nbsp;</td>
									<td width="466">&nbsp;</td>
								</tr>
							</table>

							</td>
						</tr>
					</table>
					</form>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>
</form>
<?
}elseif($acao_int=="notas"){


if($_POST['uf_nfe']==null){
   $uf=$_GET['uf_nfe'];
   $id_item=$_GET['id_item'];
}else{
   $uf=$_POST['uf_nfe'];
   $id_item=$_POST['id_item'];
}

$sureg=tabelainfo($uf,"sgc_servidores","descricao_servidor","nuf","");
$sureg=str_replace("-","",$sureg);

$pg=new sgc_nfe;
      $pg->conectar_nfe($sureg);



if($_POST['data_inicio']==null){
  $data_inicio=$_GET['data_inicio'];
}else{
  $data_inicio=$_POST['data_inicio'];
}
if($_POST['data_fim']==null){
  $data_fim=$_GET['data_fim'];
}else{
  $data_fim=$_POST['data_fim'];
}
if($_POST['status_nfe']==null){
  $status_nfe=$_GET['status_nfe'];
}else{
  $status_nfe=$_POST['status_nfe'];
}

if($_POST['nf']==null){
  $nf=$_GET['nf'];
}else{
  $nf=$_POST['nf'];
}

if($_POST['n_eletronico']==null){
  $n_eletronico=$_GET['n_eletronico'];
}else{
  $n_eletronico=$_POST['n_eletronico'];
}

if($_POST['conta']==null){
  $contaid=$_GET['conta'];
}else{
  $contaid=$_POST['conta'];
}

if($_POST['operacao']==null){
  $codop=$_GET['operacao'];
}else{
  $codop=$_POST['operacao'];
}




if($data_inicio!=null and $data_fim !=null){
  $sql_lexical=" And TO_CHAR(DATE_TRUNC('day',nf.datanota),'dd/mm/yyyy') BETWEEN '$data_inicio' AND '$data_fim' ";
}elseif($data_inicio!=null and $data_fim ==null){
  $sql_lexical=" And TO_CHAR(DATE_TRUNC('day',nf.datanota),'dd/mm/yyyy') >= '$data_inicio' ";
}elseif($data_inicio==null and $data_fim !=null){
  $sql_lexical=" And TO_CHAR(DATE_TRUNC('day',nf.datanota),'dd/mm/yyyy') = '$data_fim' ";
}

if($status_nfe!="NOT" and $status_nfe!=null){
   $sql_lexical.=" And nf.status='$status_nfe' ";
}

if($nf!=null){
  $sql_lexical.=" And nf.numeronotafiscal='$nf' ";
}

if($n_eletronico!=null){
  $sql_lexical.=" And nfe.chaveacessonfe = '$n_eletronico' ";
}

if($codop!=null){
  $sql_lexical.=" And op.codigo = '$codop' ";
}

if($contaid!=null){
  $sql_lexical.=" And ct.id = $contaid ";
}

    $desc_sureg=tabelainfo($uf,'sgc_servidores','descricao_servidor','nuf','');
    include("conf/Pagina.class.php");


    $checa = pg_query("SELECT
count(nf.id) as total
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
AND nf.modelonotafiscal='55'
 $sql_lexical
AND re.idoperacao = op.id
AND us.id = nf.idusuario

");
                     while($dados=pg_fetch_array($checa)){
                            $total = $dados['total'];
                            }




    $pagina = new Pagina();
    $pagina->setLimite(15);

 	$totalRegistros = $total;
	$linkPaginacao ="?action=sgc_monitor_nfe.php&acao_int=notas&id_item=$id_item&uf_nfe=$uf&data_inicio=$data_inicio&data_fim=$data_fim&status_nfe=$status_nfe";
 
 
?>
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


<script>
  new Ajax.PeriodicalUpdater('ultimas_notas', 'nfe_ultimas_notas.php',
  {
   method: 'post',
   parameters: {idus: '<?echo $idusuario?>', uf: '<?echo $uf?>', bloco: 'MONITOR'},
   frequency: <?echo $tempo=atributo('atributo3')?>
   });
</script>


<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Monitor NF-e :: </b></td>


				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<form method="POST" action="?action=sgc_monitor_nfe.php&acao_int=notas">
                       <input type='hidden' name='id_item' value='<?echo $id_item?>'>
                       <input type='hidden' name='uf_nfe' value='<?echo $uf?>'>

<p align="center"><font size="3">Você esta na <?Echo $desc_sureg?></font></p>

<div id="ultimas_notas">
</div>
<BR>

                        <table border="1" width="595" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" height="59">
						<tr>
							<td width="90" height="23">
							<table border="0" width="685" cellspacing="0" cellpadding="0">
								<tr>
									<td colspan="6" align="center" height="31"></td>
								</tr>
								<tr>
									<td width="8">
									<p align="right">&nbsp;&nbsp;</td>
									<td width="126">
									<p align="right">Status:</td>
									<td width="135">&nbsp;<select size="1" name="status_nfe">

                                    <option value="NOT">--</option>
                                    <?
                                    $checa = pg_query("SELECT
 DISTINCT
 CASE WHEN nf.modelonotafiscal = '55' AND nf.emissaopropria THEN
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
END AS statusnota_desc

,CASE WHEN nf.modelonotafiscal = '55' AND nf.emissaopropria THEN
     CASE WHEN nf.status = '1' THEN  1
          WHEN nf.status = '2' THEN  2
          WHEN nf.status = '3' THEN  3
          WHEN nf.status = '4' THEN  4
          WHEN nf.status = '5' THEN  5
          WHEN nf.status = '6' THEN  6
          WHEN nf.status = '7' THEN  7
      END
ELSE
     CASE WHEN nf.status = '1' THEN 1
          WHEN nf.status = '2' THEN 2
          WHEN nf.status = '3' THEN 3
          WHEN nf.status = '4' THEN 4
          WHEN nf.status = '5' THEN 5
          WHEN nf.status = '6' THEN 6
          WHEN nf.status = '7' THEN 7
    END
END AS statusnota

FROM
   tb_nota_fiscal_eletronica nfe
 , tb_nota_fiscal nf
WHERE nf.id = nfe.idnotafiscal  and nf.modelonotafiscal='55'


");
                                    while($dados=pg_fetch_array($checa)){
                                        $descricao = $dados['statusnota_desc'];
                                        $statusnota = $dados['statusnota'];

                                    ?>
                                    <option value="<?echo $statusnota?>"><?Echo $descricao?></option>
                                    <?
                                    }
                                    ?>

                                    </select></td>


                                    <td width="41">
									<p align="right">Data:</td>
									<td width="313">&nbsp;<!--webbot bot="Validation" b-value-required="TRUE" i-minimum-length="10" i-maximum-length="10" --><input type="text" name="data_inicio" onKeyUp="mascaraTexto(event,'99/99/9999')" size="10" maxlength="10">
									à&nbsp;<!--webbot bot="Validation" b-value-required="TRUE" i-minimum-length="10" i-maximum-length="10" --><input type="text" name="data_fim"  onKeyUp="mascaraTexto(event,'99/99/9999')" size="10" maxlength="10"></td>
									<td width="62">
									<p align="center">
							&nbsp;</td>
								</tr>
								<tr>
									<td width="8">
									&nbsp;</td>
									<td width="126">
									<p align="right">Conta:</td>
									<td width="489" colspan="3">&nbsp;<select size="1" name="conta">

                                    <option value="NOT">--</option>
                                    <?
                                    $checa = pg_query("SELECT DISTINCT
'('||cn.codigo||'.'||at.codigo||'.'||ft.codigo||') - '||cn.nome||'/'||at.nome||'/'||ft.nome as conta
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
AND nf.modelonotafiscal='55'


");
                                    while($dados=pg_fetch_array($checa)){
                                        $descricao_ct = $dados['conta'];
                                        $codigo_ct = $dados['id'];

                                    ?>
                                    <option value="<?echo $codigo_ct?>"><?Echo $descricao_ct?></option>
                                    <?
                                    }
                                    ?>

                                    </select></td>


									<td width="62">
							&nbsp;</td>
								</tr>
								<tr>
									<td width="8">
									&nbsp;</td>
									<td width="126">
									<p align="right">Nº nf:</td>
									<td width="489" colspan="3">&nbsp;<!--webbot bot="Validation" b-value-required="TRUE" i-minimum-length="10" i-maximum-length="10" --><input type="text" name="nf"  size="10" maxlength="10">
									</td>


									<td width="62">
							&nbsp;</td>
								</tr>
								<tr>
									<td width="8">
									&nbsp;</td>
									<td width="126">
									<p align="right">Nº Eletronico:</td>
									<td width="489" colspan="3">&nbsp;<!--webbot bot="Validation" b-value-required="TRUE" i-minimum-length="50" i-maximum-length="50" --><input type="text" name="n_eletronico"  size="50" maxlength="50"></td>


									<td width="62">
						    	<p align="center">&nbsp;</td>
								</tr>
                                      <td width="8">
									&nbsp;</td>
									<td width="126">
									<p align="right">Operação:</td>
									<td width="489" colspan="3">&nbsp;<select size="1" name="operacao">

                                    <option value="NOT">--</option>
                                    <?
                                    $checa = pg_query("SELECT
 DISTINCT
 '('||op.codigo||') '||op.nome as nomeoperacao
 ,op.codigo



FROM
   tb_nota_fiscal_eletronica nfe
 , tb_nota_fiscal nf
 , tb_regra re
 , tb_operacao op

WHERE nf.id = nfe.idnotafiscal  and nf.modelonotafiscal='55' and nf.idregra = re.id and re.idoperacao = op.id order by op.codigo


");
                                    while($dados=pg_fetch_array($checa)){
                                        $descricao_op = $dados['nomeoperacao'];
                                        $codigo_op = $dados['codigo'];


                                    ?>
                                    <option value="<?echo $codigo_op?>"><?Echo $descricao_op?></option>
                                    <?
                                    }
                                    ?>

                                    </select></td>


									<td width="62">
						    	&nbsp;</td>
								<tr>
                                      <td width="8">
									&nbsp;</td>
									<td width="126">
									&nbsp;</td>
									<td width="489" colspan="3">&nbsp;</td>


									<td width="62">
						    	<input type="submit" value="Buscar" name="B2" ></td>
								</tr>
								<tr>
                                      <td width="8">
									&nbsp;</td>
									<td width="126">
									&nbsp;</td>
									<td width="489" colspan="3">&nbsp;</td>


									<td width="62">
						    	&nbsp;</td>
								</tr>
							</table>

							</td>
						</tr>
					</table>
					</form>


                    <p><font color="#FF0000"><?echo $msg?></font></p>



<?




?>
</p>
</p>
<BR><BR>
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
$sql_lexical
AND cn.id = ct.idcnpj
AND re.idoperacao = op.id
AND us.id = nf.idusuario
ORDER BY nf.datanota asc
limit ".$pagina->getLimite()." OFFSET ".$pagina->getPagina($_GET['pagina']));

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


					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div><BR>

<?
 //----------------Paginador-------------------//

Pagina::configuraPaginacao($_GET['cj'],$_GET['pagina'],$totalRegistros,$linkPaginacao, $pagina->getLimite(), $_GET['direcao']);

//--------------------------------------------//

}

}else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>

