<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Atualizador xFac";
$id_item=$_GET['id_item'];
$arquivo="atualizador_xfac.php";
$tabela="sgc_servidores";
$id_chave="id_servidor";





if(!isset($acao_int)){
?>
<script language='javascript'>
function valida_dados(nomeform)
{
    if (nomeform.arquivo.value=="")
    {
        alert ("\nSelecione um arquivo para atualização.");

    }
return true;
}
</script>

<script language="JavaScript">
function pergunta(){
   if (confirm('Se tem certeza que deseja atualizar essas unidades?')){
      document.atualizar.submit()
   }
}
</script>

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

<form method="POST" name="atualizar" action="sgc.php?action=<?echo $arquivo?>&acao_int=atualizar" onSubmit="return valida_dados(this)" enctype="multipart/form-data">
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
					<table border="0" width="576" cellspacing="0" cellpadding="0">
						<tr>
							<td height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
						</tr>
						<tr>
							<td>
							<p align="center">Selecione a unidade para
							atualização</td>
						</tr>
						<tr>
							<td align="center">
							<table border="1" width="300" cellspacing="0" bgcolor="#FFFFFF">
                                <?
                          $checa = mysql_query("select * from $tabela order by descricao_servidor desc ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_objeto = $dados["$id_chave"];
                                    $ler_descricao_objeto = $dados['descricao_servidor'];
                                    $ler_ajuda = $dados['ajuda'];
                                    $ler_ip_host = $dados['ip_host'];
                                    $ler_manut = $dados['path_manutencao'];
                                    $ler_erro = $dados['path_erro'];
                                    $ler_executavel = $dados['executavel'];
                                    $ler_nuf = $dados['nuf'];
                                    $ler_versao = $dados['versao'];


                        ?>

                                <tr>
									<td width="22">
									<p align="center">
									<input type="checkbox" name="sureg[]" value="<?echo $id_objeto?>"></td>
									<td>&nbsp;<?Echo $ler_descricao_objeto?></td>
									<td width="140">&nbsp;<?Echo $ler_executavel?></td>
								</tr>
								<?
								}
								?>
							</table>
							</td>
						</tr>
						<tr>
							<td>
							<p align="center">Selecione o arquivo para
							atualização</td>
						</tr>
						<tr>
							<td>
							<p align="center">
							<input type="file" name="arquivo" size="45" style="background-color: #FFFFFF"></td>
						</tr>
						<tr>
							<td>
							<p align="center">Versão</td>
						</tr>
						<tr>
							<td>
							<p align="center">
							<!--webbot bot="Validation" B-Value-Required="TRUE" I-Minimum-Length="30" I-Maximum-Length="30" -->
							<input type="text" name="versao" size="30" style="background-color: #FFFFFF" maxlength="30"></td>
						</tr>
						<tr>
							<td>
							&nbsp;</td>
						</tr>
						<tr>
							<td>
							<p align="center">
							<input type="button" value="Atualizar"  onclick="pergunta()" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: center; background: #C0C0C0"></td>
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
<p>&nbsp;</p>
<?



  }
elseif($acao_int=="atualizar"){

     $idusuario = $_SESSION['id_usuario_global'];
     $id_item=$_POST['id_item'];
     $sureg=$_POST['sureg'];
     $arquivo = $_FILE['arquivo'];
     
foreach($sureg as $valor){



$checa = mysql_query("select * from sgc_servidores where id_servidor = $valor ") or print(mysql_error());
                      while($dados=mysql_fetch_array($checa)){
                                      $nuf = $dados['nuf'];
                                  $ip_host = $dados['ip_host'];
                               $executavel = $dados['executavel'];
                            $desc_servidor = $dados['descricao_servidor'];
                      }

   $d = date("d");
   $s = date("D");
   $m = date("m");
   $hora = date("Gis");
   $ano = date("Y");
   $data_hoje=$ano.$m.$d.$hora;

$dir="$valor-$data_hoje";
//mkdir("atualizacoes/$dir",0777);
echo "$valor <BR>";
echo "$desc_servidor<BR>";

}





}elseif($acao_int=="excluir"){

}elseif($acao_int=="cad_objeto"){

}

}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
