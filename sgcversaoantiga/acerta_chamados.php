<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Acerta Chamados";

$id_item=$_GET['id_item'];
$arquivo="acerta_chamados.php";




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

    if(document.forms['meuFormulario'].idchamado.value!=""){
        alert("aqui");
        document.getElementById('data_inicial').disabled = true;
        document.getElementById('data_final').disabled = true;
        document.getElementById('mes').disabled = true;
    }else{
        alert("aqui");
        document.getElementById('data_inicial').disabled = false;
        document.getElementById('data_final').disabled = false;
        document.getElementById('mes').disabled = false;
    }

    if(document.forms['meuFormulario'].mes.value!="XX"){
        document.getElementById('idchamado').disabled = true;
        document.getElementById('data_inicial').disabled = true;
        document.getElementById('data_final').disabled = true;
    }else{
        document.getElementById('idchamado').disabled = false;
        document.getElementById('data_inicial').disabled = false;
        document.getElementById('data_final').disabled = false;
    }
    if(document.forms['meuFormulario'].data_inicial.value!="" || document.forms['meuFormulario'].data_final.value!=""){
        document.getElementById('idchamado').disabled = true;
        document.getElementById('mes').disabled = true;
    }else{
        document.getElementById('idchamado').disabled = false;
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
	<form method="POST" id="form1" name='meuFormulario' action="?action=<?echo $arquivo?>&acao_int=acerta_chamado" onsubmit="return checarGeral(this)">
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
						                    <input type='hidden' name='id_item' value='<?echo $id_item?>'>
						<tr>                               <input type='hidden' name='id_item' value='<?echo $id_item?>'>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

													ID Chamado:&nbsp; </td>
								<td height="23">
											<font size="1"><input type="text" name="idchamado" id="idchamado" onchange="bloqueia_campos(this.value)" maxlength="10"   size="10" style="background-color: #FFFFFF" maxlength="60"></td>
								<td width="31">&nbsp;</td>
							</tr>
						<tr>
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
												<input type="submit" value="Acertar" name="B1"></td>
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



}elseif($acao_int=="acerta_chamado"){

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


   $lexical="and DATE_FORMAT(ch.data_criacao,'%Y-%m-%d') BETWEEN '$data_inicial' and '$data_final'";
}

$sql="SELECT id_chamado,
(SELECT id_historico FROM sgc_historico_chamado WHERE id_chamado = ch.id_chamado order by id_historico desc limit 1) linha_certa
FROM sgc_chamado ch
where 1=1
$lexical
ORDER BY ID_CHAMADO
";



                                    $checa = mysql_query("$sql") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                       echo $id_chamado = $dados['id_chamado']; echo " ";
                                       echo $linha_certa = $dados['linha_certa']; echo "<BR>";
                                    }



?>



<?



}
}else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
