<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];


$id_item=$_GET['id_item'];


$id_unidade_usuario=unidade_usuario($idusuario);
$sigla_unidade=tabelainfo($id_unidade_usuario,"sgc_unidade","sigla","codigo","");




if(!isset($acao_int)){
if(chamado_fechado_falta_enquete($idusuario)!=null){
  $id_chamado_enquete=chamado_fechado_falta_enquete($idusuario);
  header("Location: ?action=vis_chamado.php&acao_int=enquete&id_chamado=$id_chamado_enquete");
}


?>

<table class="border" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
<form method="POST" id="form1" action="sgc.php?action=rel_regras.php&acao_int=visualizar" ">
							<tr>
								<td>
								<table border="0" cellpadding="5" cellspacing="1" width="100%">
									<tr>
										<td class="info" colspan="1" align="center">
										<b>Relatório de ALteração de Regras</b></td>
									</tr>
									<tr>
										<td class="cat" align="right">
										<font size="1">
										<table border="0" width="887" cellspacing="0" cellpadding="0">
											<tr>
												<td width="43">&nbsp;</td>
												<td width="803" colspan="2">
												<p align="center">
												&nbsp;</td>
												<td width="41">&nbsp;</td>
											</tr>
											<tr>
												<td width="43">&nbsp;</td>
												<td width="119" align="right">
												Id Regra:&nbsp;&nbsp; </td>
												<td width="685">
										<font size="1">
												<select size="1" name="id_regra" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                                                <option ></option>
                                                <?
												$checa = mysql_query("SELECT distinct id_regra FROM sgc_log_regra_xfac order by id_regra");
                                                while($dados=mysql_fetch_array($checa)){
                                                $id_regra = $dados['id_regra'];
                                                ?>
                                                <option ><?echo $id_regra?></option>
                                                <?
                                                }
                                                ?>
                        						</select></td>
												<td width="41">&nbsp;</td>
											</tr>
											<tr>
												<td width="43">&nbsp;</td>
												<td width="119" align="right">
												&nbsp;Desc. Operação:&nbsp;&nbsp; </td>
												<td width="685">
										<font size="1">
												<select size="1" name="desc_op" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                                                <option ></option>
                                                <?
												$checa = mysql_query("SELECT distinct descricao_operacao FROM sgc_log_regra_xfac order by descricao_operacao");
                                                while($dados=mysql_fetch_array($checa)){
                                                $descricao_operacao = $dados['descricao_operacao'];
                                                ?>
                                                <option><?echo $descricao_operacao?></option>
                                                <?
                                                }
                                                ?>
						</select></td>
												<td width="41">&nbsp;</td>
											</tr>
												<tr>
												<td width="43">&nbsp;</td>
												<td width="119" align="right">
												&nbsp;Data Geração:&nbsp;&nbsp; </td>
												<td width="685">
										<font size="1">
												<select size="1" name="data_geracao" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                                                <option value=""></option>
                                                <?
    											$checa = mysql_query("SELECT
                                                distinct date_format(data_geracao_regra,'%d/%m/%Y') data_vis
                                                , date_format(data_geracao_regra,'%Y-%m-%d') data_busca
                                                FROM sgc_log_regra_xfac order by data_geracao_regra");
                                                while($dados=mysql_fetch_array($checa)){
                                                $data_vis = $dados['data_vis'];
                                                $data_busca = $dados['data_busca'];
                                                ?>
                                                <option value='<? echo $data_busca ?>' ><?echo $data_vis?></option>
                                                <?
                                                }
                                                ?>
						                        </select></td>
												<td width="41">&nbsp;</td>
											</tr>
                                            <tr>
												<td width="43">&nbsp;</td>
												<td width="119" align="right">
												Entrada/Saida:&nbsp;&nbsp; </td>
												<td width="685">
										<font size="1">
												<select size="1" name="entrada_saida" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                                <option></option>
                                <option>E</option>
								<option>S</option>
						</select></td>
												<td width="41">&nbsp;</td>
											</tr>

          <tr>
												<td width="43">&nbsp;</td>
												<td width="119">
												<p align="right">Usuário:&nbsp;&nbsp; </td>
												<td width="685">
										<font size="1">
												<select size="1" name="usuario" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                                                <option ></option>
                                                <?
    											$checa = mysql_query("SELECT distinct usuario FROM sgc_log_regra_xfac order by usuario");
                                                while($dados=mysql_fetch_array($checa)){
                                                $usuario = $dados['usuario'];
                                                ?>
                                                <option ><?echo $usuario?></option>
                                                <?
                                                }
                                                ?>
						</select></td>
												<td width="41">&nbsp;</td>
											</tr>

          <tr>
												<td width="43">&nbsp;</td>
												<td width="119">
												<p align="right">Operação:&nbsp;&nbsp; </td>
												<td width="685">
										<font size="1">
												<select size="1" name="operacao" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                                                 <option ></option>
                                                <?
    											$checa = mysql_query("SELECT distinct operacao FROM sgc_log_regra_xfac order by operacao");
                                                while($dados=mysql_fetch_array($checa)){
                                                $operacao = $dados['operacao'];
                                                ?>
                                                <option ><?echo $operacao?></option>
                                                <?
                                                }
                                                ?>
						</select></td>
												<td width="41">&nbsp;</td>
											</tr>

          <tr>
												<td width="43">&nbsp;</td>
												<td width="804" colspan="2">
										<font size="1">
												<p align="center">
												<input type="submit" value="Buscar" name="B1"></td>
												<td width="41">&nbsp;</td>
											</tr>

										</table>
										</td>
									</tr>
        						</table>
		    	    			</td>
			    			</tr>

                       </form>
						</table>
						<BR>
</head>
<body>
<?



}elseif($acao_int=="visualizar"){
include('grava_log_regras.php');

       $usuario=$_POST['usuario'];
      $operacao=$_POST['operacao'];
 $entrada_saida=$_POST['entrada_saida'];
  $data_geracao=$_POST['data_geracao'];
 $desc_operacao=$_POST['desc_op'];
      $id_regra=$_POST['id_regra'];

If($usuario!=null){
   $sql_as=" AND usuario='$usuario' ";
}
If($operacao!=null){
   $sql_as.=" AND operacao=$operacao ";
}
If($entrada_saida!=null){
   $sql_as.=" AND es='$entrada_saida' ";
}
If($desc_operacao!=null){
   $sql_as.=" AND descricao_operacao='$desc_operacao' ";
}
If($id_regra!=null){
   $sql_as.=" AND id_regra=$id_regra ";
}
If($data_geracao!=null){
   $sql_as.=" AND data_geracao like '$data_geracao%' ";
}

 $sql_as;



?>
<table class="border" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">

							<tr>
								<td>
								<table border="0" cellpadding="5" cellspacing="1" width="100%">
									<tr>
										<td class="cat" align="right">
										<table border="0" width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td>&nbsp;</td>
												<td align="center" width="62">Id
												Regra</td>
												<td align="center" width="269">
												Desc. Operação</td>
												<td align="center" width="105">&nbsp;Nº
												Operação</td>
												<td align="center" width="132">
												Data Geração</td>
												<td align="center" width="24">
												E/S</td>
												<td align="center">Quem Alterou</td>
												<td>&nbsp;</td>
											</tr>
                                            <? $count=0;
											$checa = mysql_query("SELECT *,date_format(data_geracao_regra,'%d/%m/%Y') data_vis
                                                                  FROM sgc_log_regra_xfac WHERE 1=1
                                                                   $sql_as
                                                                  order by data_geracao_regra");
                                                while($dados=mysql_fetch_array($checa)){
                                                $id_regra = $dados['id_regra'];
                                                $operacao = $dados['operacao'];
                                                $data_vis = $dados['data_vis'];
                                                      $es = $dados['es'];
                                                 $usuario = $dados['usuario'];
                                                $descricao_operacao = $dados['descricao_operacao'];
                                                $count++;
                                            ?>
                                            <tr>
												<td>&nbsp;</td>
												<td align="center" width="62"><?Echo $id_regra?></td>
												<td align="center" width="269">
												<?Echo $descricao_operacao?></td>
												<td align="center" width="105"><?Echo$operacao?></td>
												<td align="center" width="132">
												<?Echo$data_vis?></td>
												<td align="center" width="24">
												<?Echo$es?></td>
												<td align="center"><?Echo$usuario?></td>
												<td>&nbsp;</td>
											</tr>
                                            <?
                                            }
                                            ?>
										</table>
										</td>
									</tr>
									<tr>
										<td class="cat" align="right">
										<p align="center">Resultado Encontrados:<?Echo $count?></td>
									</tr>
        						</table>
		    	    			</td>
			    			</tr>

						</table>
						<BR>
</head>
<body>
<?




}

}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
