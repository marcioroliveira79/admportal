<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Postar Produto Para Homologação";
$id_item=$_GET['id_item'];
$arquivo="cad_homologacao.php";
$tabela="sgc_servidores";
$id_chave="id_servidor";





if(!isset($acao_int)){
echo $recomendacoes = $_POST['recomendacoes'];
?>
<script language='javascript'>
function valida_dados(nomeform)
{
    if (nomeform.versao.value=="")
    {
      alert ("\nPor favor descreva a versão.");
               return false;
    }

    if (nomeform.arquivo.value=="")
    {
        alert ("\nSelecione um arquivo para atualização.");
               return false;
    }

     if (nomeform.inovacoes.value=="")
    {
        alert ("\nAs inovações são obrigatórias.");
               return false;
    }
     if (nomeform.sureg.value=="")
    {
        alert ("\nVocê precisa selecionar o servidor de homologação.");
               return false;
    }
return true;
}

function validaCheckbox(v)
{
    todos = document.getElementsByTagName('sureg');
    for(x = 0; x < todos.length; x++)
    {
        if (todos[x].checked)
        {
            return true;
        }
    }
    alert("Selecione pelo menos um item!");
    return false;
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

<form method="POST" name="atualizar" action="sgc.php?action=<?echo $arquivo?>&acao_int=atualizar" onSubmit="return valida_dados(this);return validaCheckbox(this)" enctype="multipart/form-data">
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
					<p align="center">&nbsp;</p>
					<table border="1" width="578" cellspacing="0" cellpadding="0" style="border-collapse: collapse" bordercolor="#000000">
						<tr>
							<td>
					<table border="0" width="576" cellspacing="0" cellpadding="0">
						<tr>
							<td>
							<table border="0" width="100%" cellspacing="0" bgcolor="#FFFFFF">
								<tr>
									<td width="97%" colspan="2">
									<p align="center"><font color="#FF0000"><b><?echo $msg ?></b></font></td>
								</tr>
								<tr>
									<td width="15%">
									&nbsp;</td>
									<td width="82%">&nbsp;</td>
								</tr>
								<tr>
									<td width="15%">
									<p align="right">Versão:</td>
									<td width="82%">&nbsp;<!--webbot bot="Validation" b-value-required="TRUE" i-minimum-length="30" i-maximum-length="30" --><input type="text" name="versao" size="30" style="background-color: #FFFFFF" maxlength="30"></td>
								</tr>
								<tr>
									<td width="15%">
									<p align="right">Arquivo:</td>
									<td width="82%">&nbsp;<input type="file" name="file" size="45" style="background-color: #FFFFFF; color:#FFFFFF"></td>
								</tr>
								<tr>
									<td colspan="2" align="center">Recomendações
									para teste:</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
									<textarea rows="6" name="recomendacoes" value="<?echo $recomendacoes?>" cols="68" style="background-color: #FFFFFF"></textarea></td>
								</tr>
								<tr>
									<td colspan="2" align="center">Inovações da
									versão:</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
									<textarea rows="6" name="inovacoes" cols="68" style="background-color: #FFFFFF"></textarea></td>
								</tr>
								<tr>
									<td colspan="2" align="center">Selecione o servidor de homologação</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
							<select size="1" name="sureg">
							    <?
                          $checa = mysql_query("select * from $tabela where homologacao='ON' order by descricao_servidor desc ") or print(mysql_error());
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
							<option value="<?echo $id_objeto?>"><?Echo $ler_descricao_objeto?></option>
							<?
							}
							?>
							</select></td>
								</tr>
       	<tr>
									<td colspan="2" align="center">

								<tr>
									<td colspan="2" align="center">&nbsp;</td>
								</tr>
							</table>
							</td>
						</tr>
						</table>
							</td>
						</tr>
					</table>
					<p>
							<input type="submit" value="Atualizar"   style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: center; background: #C0C0C0"></p>
					<table border="1" width="576" cellspacing="0" cellpadding="0" style="border-collapse: collapse" bordercolor="#000000" bgcolor="#FFFFFF">
						<tr>
							<td>
							<table border="0" width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td width="18">&nbsp;</td>
									<td>&nbsp;</td>
									<td width="15">&nbsp;</td>
								</tr>
								<tr>
									<td width="18">&nbsp;</td>
									<td>
									<p align="center">Homologadores atualmente
									cadastrados</td>
									<td width="15">&nbsp;</td>
								</tr>
								<tr>
									<td width="18">&nbsp;</td>
									<td>&nbsp;</td>
									<td width="15">&nbsp;</td>
								</tr>
									<?
                    	$checa = mysql_query("SELECT concat(primeiro_nome,' ',ultimo_nome,' - ',u.sigla)nome
FROM
  sgc_gestor_homologacao gh
,  sgc_usuario su
, sgc_unidade u
, sgc_departamento dp

where
    su.id_usuario = gh.id_usuario
and u.codigo = su.id_unidade
and dp.id_departamento = su.id_departamento
order by nome  ") or print(mysql_error());
                         while($dados=mysql_fetch_array($checa)){
                            $nome = $dados['nome'];


                         ?>
								<tr>
									<td width="18">&nbsp;</td>
									<td><?echo $nome?></td>
									<td width="15">&nbsp;</td>
								</tr>
								<?
								}
								?>
								<tr>
									<td width="18">&nbsp;</td>
									<td>&nbsp;</td>
									<td width="15">&nbsp;</td>
								</tr>
							</table>
							</td>
						</tr>
					</table>
					<p>&nbsp;</td>
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

           $id_item = $_POST['id_item'];
             $sureg = $_POST['sureg'];
              $file = $_FILE['file'];

     $recomendacoes = $_POST['recomendacoes'];
         $inovacoes = $_POST['inovacoes'];
            $versao = $_POST['versao'];

//-----------Propriedades do arquivo ------------------------------//

      $nome =  $_FILES["file"]["name"];
      $tipo =  $_FILES["file"]["type"];
      $size = ($_FILES["file"]["size"] / 1024) ;
  $tmp_name =  $_FILES["file"]["tmp_name"];

//-----------------------------------------------------------------//

$checa = mysql_query("select count(*) t from sgc_produto_homologacao where versao='$versao'") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
         $versao_count = $dados['t'];
}

if($versao_count > 0 ){
$msg="Já existe um produto com essa verão por favor corrija o nome da versão!";
header("Location: ?action=$arquivo&id_item=$id_item&msg=$msg");
exit;

}


$checa = mysql_query("select count(*) t from sgc_gestor_homologacao") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
         $qtd_homologadores = $dados['t'];
}


$checa = mysql_query("select * from sgc_servidores where id_servidor = $sureg ") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
         $id_servidor = $dados['id_servidor'];
         $nuf = $dados['nuf'];
         $ip_host = $dados['ip_host'];
         $executavel = $dados['executavel'];
         $desc_servidor = $dados['descricao_servidor'];
         }





$cadas = mysql_query  ("INSERT INTO sgc_produto_homologacao
                             (versao
                             ,arquivo
                             ,tamanho
                             ,recomendacoes_teste
                             ,inovacoes
                             ,data_criacao
                             ,quem_criou
                             ,qtd_homologadores
                             ,status
                             )

                             VALUES

                             ('$versao'
                             ,'$file'
                             ,'$size'
                             ,'$recomendacoes'
                             ,'$inovacoes'
                             ,sysdate()
                             ,$idusuario
                             ,$qtd_homologadores
                             ,'EM APROVACAO')
                             ");

                             print(mysql_error());

$id_processo = ultimo_registro('id_processo','sgc_produto_homologacao','id_processo');




$checa = mysql_query("select * from sgc_gestor_homologacao") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
         $id_usuario = $dados['id_usuario'];

 $cadas = mysql_query  ("INSERT INTO sgc_propriedade_homologacao
                             (id_processo
                             ,id_usuario_homologador
                             ,servidor
                             ,data_criacao)

                             VALUES

                             ($id_processo
                             ,$id_usuario
                             ,'$ip_host'
                             ,sysdate())
                             ");

                             print(mysql_error());



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







}elseif($acao_int=="excluir"){

}elseif($acao_int=="cad_objeto"){

}

}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
