<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Relatório Gerêncial";

$id_item=$_GET['id_item'];
$arquivo="rel_gerencial.php";




if(!isset($acao_int)){

if(!isset($_POST['id_item'])){

  $id_item=$_GET['id_item'];

}else{
  $id_item=$_POST['id_item'];
}


?>

<script type="text/javascript">
function checarGeral (nomeform)
{
     var validacao01 = checarDatas(nomeform);
     var validacao02 = valida_dados(nomeform);

     return ( validacao01 && validacao02);
}


function checarDatas (nomeform)
{
     var datainicio = nomeform.data_inicial.value;
     var datafinal = nomeform.data_final.value;
     var mespesquisa = nomeform.mes.value;
     
     if ((datainicio == "" || datafinal == "") && mespesquisa == "XX" ) {
        return false;
     }
     
     var Compara01 = parseInt(datainicio.split("/")[2].toString() + datainicio.split("/")[1].toString() + datainicio.split("/")[0].toString());
     var Compara02 = parseInt(datafinal.split("/")[2].toString() + datafinal.split("/")[1].toString() + datafinal.split("/")[0].toString());

     if (Compara01 > Compara02) {
       alert("Data inicial maior do que a data final.");
       return false;
     } else {
       return true;
     }
}
</script>



<script type="text/javascript">
function bloqueia_campos(){
    if(document.forms['meuFormulario'].mes.value!="XX"){
        document.getElementById('data_inicial').disabled = true;
        document.getElementById('data_final').disabled = true;
    }else{
        document.getElementById('data_inicial').disabled = false;
        document.getElementById('data_final').disabled = false;
    }
    if(document.forms['meuFormulario'].data_inicial.value!="" || document.forms['meuFormulario'].data_final.value!=""){
        document.getElementById('mes').disabled = true;
    }else{
        document.getElementById('mes').disabled = false;
    }

}
</script>

<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.data_inicial.value=="" && nomeform.mes.value=="XX")
    {
        alert ("\nInsira a data inicial ou seleciona um mês para pesquisa.");
        nomeform.data_inicial.focus();
        return false;
    }
    if (nomeform.data_final.value=="" && nomeform.mes.value=="XX")
    {
        alert ("\nInsira a data Final");
        nomeform.data_final.focus();
        return false;
    }
    return true;
}
</script>


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
	<form method="POST" id="form1" name='meuFormulario' action="?action=<?echo $arquivo?>&acao_int=gera_relatorio" onsubmit="return checarGeral(this)">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: <?echo $titulo?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="80%" cellspacing="0" cellpadding="0">
						<tr>
							<td width="15">&nbsp;</td>
							<td height="23" width="559" colspan="2">
							<p align="center">
												<font color="#FF0000" size="1"><?echo $msg?></font></td>
							<td width="31">&nbsp;</td>
						</tr>
						<tr>                               <input type='hidden' name='id_item' value='<?echo $id_item?>'>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

													Data Inicial:&nbsp; </td>
								<td height="23">
											<font size="1"><input type="text" name="data_inicial" id="data_inicial" onchange="bloqueia_campos(this.value)" maxlength="10" onKeyUp="mascaraTexto(event,'99/99/9999')"  size="10" style="background-color: #FFFFFF" maxlength="60"></td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

													Data Final:&nbsp;</td>
								<td height="23">
											<font size="1"><input type="text" name="data_final" id="data_final" onchange="bloqueia_campos(this.value)" maxlength="10" onKeyUp="mascaraTexto(event,'99/99/9999')"  size="10" style="background-color: #FFFFFF" maxlength="60"></td>
								<td width="31">&nbsp;</td>
							</tr>

						<tr>
							<td width="15">&nbsp;</td>
							<td height="23" align="right">

												Mês pesquisa:&nbsp;</td>
							<td height="23">
										<font size="1">
								<select size="1" name="mes" id="mes" onChange="bloqueia_campos(this.value)" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                                 <option value="XX" ></option>
                            	  <?
                            $checa_menu = mysql_query("SELECT DISTINCT date_format(data_criacao,'%m') MES,  date_format(data_criacao,'%m/%Y')MES_ANO FROM sgc_chamado ") or print mysql_error();
                                    while($dados_menu=mysql_fetch_array($checa_menu)){
                                    $mes = $dados_menu["MES"];
                                    $mes_ano = $dados_menu["MES_ANO"];

                                ?>
     							<option value="<?echo  $mes?>" > <?echo $mes_ano?>   </option>

                                <?
                           }
                        ?>
						</select></td>
							<td width="31">&nbsp;</td>
						</tr>
							<tr>
							<td width="15">&nbsp;</td>
							<td height="23" width="559" colspan="2">
							<p align="center">
											</td>
							<td width="31">&nbsp;</td>
						</tr>
                         	<tr>
								<td width="15">&nbsp;</td>
								<td height="23">&nbsp;</td>
								<td height="23">
										<font size="1">
												<input type="submit" value="Buscar" name="B1"></td>
								<td width="31">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table></td>
		</tr>
	</table>
</form>
</div>
<?



}elseif($acao_int=="gera_relatorio"){

  $id_item=$_POST['id_item'];
  $mes=$_POST['mes'];
  $data_inicial=$_POST['data_inicial'];
  $data_final=$_POST['data_final'];

if($mes!=null){
   $lexical="and date_format(ch.data_criacao,'%m') = $mes";
}
if($data_inicial!=null and $data_final!=null){
   $data_inicial=explode("/", $data_inicial);
   list($dia, $mes, $ano) = $data_inicial;
   $data_inicial = $ano."-".$mes."-".$dia;

   $data_final=explode("/", $data_final);
   list($dia, $mes, $ano) = $data_final;
   $data_final = $ano."-".$mes."-".$dia;


   $lexical="and DATE_FORMAT(ch.data_criacao,'%Y-%m-%d')BETWEEN '$data_inicial' and '$data_final'";
}

$sql="
SELECT
  date_format(ch.data_criacao,'%d/%m/%Y') DATA
, date_format(ch.data_criacao,'%Y-%m-%d')  data_criacao
, COUNT(ch.id_chamado) TOTAL
, round(sum(hc.prioridade)/COUNT(ch.id_chamado),1) MEDIA_PRIORIDADE
, CASE WHEN round(sum(hc.prioridade)/COUNT(ch.id_chamado),1) = 2 OR round(sum(hc.prioridade)/COUNT(ch.id_chamado),1) < 3 THEN 'CRÍTICA'
  WHEN round(sum(hc.prioridade)/COUNT(ch.id_chamado),1) = 3 OR round(sum(hc.prioridade)/COUNT(ch.id_chamado),1) < 4 THEN 'ALTA'
  WHEN round(sum(hc.prioridade)/COUNT(ch.id_chamado),1) = 4 OR round(sum(hc.prioridade)/COUNT(ch.id_chamado),1) < 5 THEN 'BAIXA'
  ELSE 'BAIXA'
  END MEDIA_PRIORIDADE_DESC
FROM sgc_chamado ch,  sgc_historico_chamado hc
where ch.id_chamado = hc.id_chamado
and ch.id_linha_historico = hc.id_historico
$lexical
group by date_format(ch.data_criacao,'%d')
order by ch.data_criacao, COUNT(ch.id_chamado)
";

$sql_resumo_uf="
SELECT
  date_format(ch.data_criacao,'%d/%m/%Y') DATA
, COUNT(ch.id_chamado) TOTAL
, round(sum(hc.prioridade)/COUNT(ch.id_chamado),1) MEDIA_PRIORIDADE
, CASE WHEN round(sum(hc.prioridade)/COUNT(ch.id_chamado),1) = 2 OR round(sum(hc.prioridade)/COUNT(ch.id_chamado),1) < 3 THEN 'CRÍTICA'
  WHEN round(sum(hc.prioridade)/COUNT(ch.id_chamado),1) = 3 OR round(sum(hc.prioridade)/COUNT(ch.id_chamado),1) < 4 THEN 'ALTA'
  WHEN round(sum(hc.prioridade)/COUNT(ch.id_chamado),1) = 4 OR round(sum(hc.prioridade)/COUNT(ch.id_chamado),1) < 5 THEN 'BAIXA'
  ELSE 'BAIXA'
  END MEDIA_PRIORIDADE_DESC
, un.sigla
FROM sgc_chamado ch,  sgc_historico_chamado hc, sgc_unidade un
where ch.id_chamado = hc.id_chamado
and un.codigo = ch.id_unidade
and ch.id_linha_historico = hc.id_historico
$lexical
group by ch.id_unidade
order by COUNT(ch.id_chamado) desc";

?>

<head>
<meta http-equiv="Content-Language" content="pt-br">
</head>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="29">&nbsp;</td>
		<td>
		<p align="center">RELATÓRIO</td>
		<td width="25">&nbsp;</td>
	</tr>
	<tr>
		<td width="29">&nbsp;</td>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
				<td width="85">&nbsp;Data</td>
				<td width="380">&nbsp;Total</td>
				<td width="237">&nbsp;Média Prioridade</td>
				<td>&nbsp;</td>
			</tr>
			    <?
            $checa = mysql_query("$sql") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $ler_data_criacao = $dados['data_criacao'];
                                    $ler_data = $dados['DATA'];
                                    $ler_total = $dados['TOTAL'];
                                    $ler_media_prioridade = $dados['MEDIA_PRIORIDADE'];
                                    $ler_descricao_prioridade = $dados['MEDIA_PRIORIDADE_DESC'];

            ?>
			<tr>
				<td width="85" bgcolor="#F7F7F7"><b>&nbsp;<?echo $ler_data?></b></td>
				<td width="380" bgcolor="#F7F7F7"><b>&nbsp;<?echo $total?></b></td>
				<td width="237" bgcolor="#F7F7F7"><b>&nbsp;<?echo $ler_media_prioridade?></b></td>
				<td bgcolor="#F7F7F7"><b>&nbsp;<?echo $ler_descricao_prioridade?></b></td>
			</tr>
			<tr>
				<td width="85">&nbsp;</td>
				<td width="617" colspan="2">
				<table border="0" width="100%" cellspacing="0" cellpadding="0">

                    <tr>
						<td width="44%">&nbsp;UF</td>
						<td width="54%">&nbsp;Total</td>
					</tr>
					 <?
                       $checa_un = mysql_query("SELECT
                        ch.id_unidade
                        ,un.sigla
                        ,count(ch.id_unidade) total_un
                        FROM sgc_chamado ch,sgc_unidade un WHERE ch.data_criacao like '$ler_data_criacao%'
                        and ch.id_unidade = un.codigo
                        group by ch.id_unidade") or print(mysql_error());
                                    while($dados_un=mysql_fetch_array($checa_un)){
                                    $ler_id_unidade = $dados_un['id_unidade'];
                                    $ler_sigla = $dados_un['sigla'];
                                    $ler_total_unidade = $dados_un['total_un'];


                    ?>
					<tr>
						<td width="44%" bgcolor="#F7F7F7"><b>&nbsp;<?echo $ler_sigla?></b></td>
						<td width="54%" bgcolor="#F7F7F7"><b>&nbsp;<?echo $ler_total_unidade?></b></td>
					</tr>

					<tr>
						<td colspan="2">
						<table border="0" width="617" cellspacing="0" cellpadding="0">
							<tr>
								<td width="23">&nbsp;</td>
								<td width="253" bgcolor="#FFFFFF">&nbsp;Data Chamado</td>
								<td bgcolor="#FFFFFF" width="321" colspan="2">&nbsp;ID Chamado</td>
								<td width="20">&nbsp;</td>
							</tr>
							 <?
                             $checa_ch = mysql_query("SELECT
                             date_format(data_criacao,'%d/%m/%Y %H:%i') data_criacao, id_chamado
                             FROM sgc_chamado ch WHERE data_criacao like '$ler_data_criacao%'
                             and id_unidade = $ler_id_unidade") or print(mysql_error());
                                    while($dados_ch=mysql_fetch_array($checa_ch)){
                                    $ler_data_criacao_ch = $dados_ch['data_criacao'];
                                    $ler_id_chamado_ch = $dados_ch['id_chamado'];



                            ?>
							<tr>
								<td width="23">&nbsp;</td>
								<td width="253" bgcolor="#FFFFFF">&nbsp;<?echo  $ler_data_criacao_ch?></td>
								<td bgcolor="#FFFFFF" width="120">&nbsp;<?echo $ler_id_chamado_ch?></td>
								<td bgcolor="#FFFFFF" width="201"><?echo status_atual($ler_id_chamado_ch)?></td>
								<td width="20">&nbsp;</td>
							</tr>
                          <?
                          }
                          ?>

						</table>
						</td>
					</tr>
					 <?
                     }
                     ?>
				</table>
				</td>
				<td>&nbsp;</td>
			</tr>
			<?

			}
			?>
		</table>
		</td>
		<td width="25">&nbsp;</td>
	</tr>
	<tr>
		<td width="29">&nbsp;</td>
		<td>&nbsp;</td>
		<td width="25">&nbsp;</td>
	</tr>
</table>



<head>
<meta http-equiv="Content-Language" content="pt-br">
</head>

<table border="0" width="600" cellspacing="0" cellpadding="0">
	<tr>
		<td>&nbsp;</td>
		<td width="152" align="center">&nbsp;</td>
		<td width="112" align="center">&nbsp;</td>
		<td width="115" align="center">&nbsp;</td>
		<td width="90" align="center">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td width="152" align="center"><b>Total</b></td>
		<td width="112" align="center"><b>Média Prioridade</b></td>
		<td width="115" align="center"><b>Desc. Prioridade </b></td>
		<td width="90" align="center"><b>UF</b></td>
		<td>&nbsp;</td>
	</tr>
	 <?     $count_total=0;
            $count_res=0;
            $sum_media=0;
            $checa = mysql_query("$sql_resumo_uf") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){

                                    $ler_sigla = $dados['sigla'];
                                    $ler_total = $dados['TOTAL'];
                                    $ler_media_prioridade = $dados['MEDIA_PRIORIDADE'];
                                    $ler_descricao_prioridade = $dados['MEDIA_PRIORIDADE_DESC'];
                                    $count_total=$count_total+$ler_total;
                                    $count_res++;
                                    $sum_media=$sum_media+$ler_media_prioridade;
                                    
            ?>
	<tr>
		<td>&nbsp;</td>
		<td width="152" align="center" bgcolor="#F7F7F7"><?echo $ler_total?></td>
		<td width="112" align="center" bgcolor="#F7F7F7"><?echo $ler_media_prioridade?></td>
		<td width="115" align="center" bgcolor="#F7F7F7"><?echo $ler_descricao_prioridade?></td>
		<td width="90" align="center" bgcolor="#F7F7F7"><?echo $ler_sigla?></td>
		<td>&nbsp;</td>
	</tr>
	<?
	}
	?>
	<tr>
		<td>&nbsp;</td><b>
		<td width="152" align="center">&nbsp;<b><?echo $count_total?></b></td>
		<td width="112" align="center"><b><?echo round($sum_media/$count_res,1)?></b>
        </td>
		<td width="115" align="center"><b>
        <?
        if(round($sum_media/$count_res,1) < 3 ){
          echo "CRÍTICA";
        }elseif(round($sum_media/$count_res,1) < 4 ){
          echo "ALTA";
        }elseif(round($sum_media/$count_res,1) < 5 ){
          echo "BAIXA";
        }
        ?>
        </b>
        </td>

        
		<td width="90" align="center">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>


<?



}
}else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
