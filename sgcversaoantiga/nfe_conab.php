<?php
OB_START();
session_start();


if($permissao=='ok'){


$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="NF-e CONAB";

$id_item=$_GET['id_item'];
$arquivo="nfe_conab.php";



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


<form method="POST" name="form1" action="sgc.php?action=nfe_conab.php&acao_int=sureg" onSubmit="return valida_dados(this)">
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
}elseif($acao_int=="sureg"){

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


//$homepage = file_get_contents("http://10.1.0.105/nfe/exemplos/consultaServico.php?cUF=$uf&UF=$n_uf");

if ($homepage !="FALHA"){


}else{



}






?>

<script src="prototype.js" type="text/javascript"></script>

<script>
  new Ajax.PeriodicalUpdater('ultimas_notas', 'nfe_conab_ajax.php',
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
					<td class="info" align="middle"><b>:: <?echo $titulo?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<form method="POST" name="form1" action="sgc.php?action=nfe_conab.php&acao_int=sureg" onSubmit="return valida_dados(this)">
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
						<p align="center"><b>

						<a href="javaScript: void(window.open('nfe_impressao.php?&sureg=<?echo $sureg?>&id_usuario=<?echo $idusuario?>&permissao=OK','Visualizar','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=650,height=600'));">

						<img border="0" src="imgs/impressora.gif" width="25" height="23"></a></b></p>
					<p align="center">




                    <div id="ultimas_notas">

                    </div>

                    </p>
					<p align="center">&nbsp;</td>
				</tr>
			</table></td>
		</tr>
	</table>
</div>	<p align="center">&nbsp;
<?







}
}else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
