<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Relatório Por Analista";
$titulo_listar="Analistas já associados";
$id_item=$_GET['id_item'];
$arquivo="rel_analista.php";




if(!isset($acao_int)){

if(!isset($_POST['id_item'])){

  $id_item=$_GET['id_item'];

}else{
  $id_item=$_POST['id_item'];
}


?>





<div align="center">
	<form method="POST" id="form1" name='meuFormulario' enctype="multipart/form-data"  action="?action=<?echo $arquivo?>&acao_int=buscar">
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
											<font size="1"><input type="text" name="data_inicial" onKeyUp="mascaraTexto(event,'99/99/9999')"  size="10" style="background-color: #FFFFFF" maxlength="60"></td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

													Data Final:&nbsp;</td>
								<td height="23">
											<font size="1"><input type="text" name="data_final" onKeyUp="mascaraTexto(event,'99/99/9999')"  size="10" style="background-color: #FFFFFF" maxlength="60"></td>
								<td width="31">&nbsp;</td>
							</tr>
						<tr>
							<td width="15">&nbsp;</td>
							<td height="23" align="right">

												Área de Atuação Suporte:&nbsp;</td>
							<td height="23">
										<font size="1">
												<select size="1" name="area" Onchange="atualiza(this.value);" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                              <option value="Todos">Todos</option>
                            	  <?
                            $checa_menu = mysql_query("SELECT id_area_locacao,descricao FROM sgc_area_locacao order by id_area_locacao") or print mysql_error();
                                    while($dados_menu=mysql_fetch_array($checa_menu)){
                                    $id_area= $dados_menu["id_area_locacao"];
                                    $descricao= $dados_menu["descricao"];

                                ?>
     							<option value="<?echo $id_area?>"><?echo $descricao?></option>
                                <?
                           }
                        ?>
						</select></td>
							<td width="31">&nbsp;</td>
						</tr>
						<tr>
							<td width="15">&nbsp;</td>
							<td height="23" align="right">Analista:&nbsp;</td>
							<td height="23"><font size="1">
                            <div id="atualiza" >
                            </div>
         				</td>
							<td width="31"></td>
		     			</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

												Prioridade:&nbsp;</td>
								<td height="23">
										<font size="1">
												<select size="1" name="prioridade" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" >&nbsp;

                              <option value="Todos">Todos</option>
              <?
                        $checa = mysql_query("SELECT * FROM sgc_sla_analista_usuario order by descricao='$prioridade' desc") or print(mysql_error());
                        while($dados=mysql_fetch_array($checa)){
                        $id_sla_analista = $dados['id_sla_analista'];
                        $descricao  = $dados['descricao'];
                       ?>
                         <option value="<?echo $id_sla_analista?>"><?echo $descricao?></option>
                       <?
                          }
                       ?>
						</select></td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

												Situação Chamado:&nbsp;</td>
								<td height="23">
										<font size="1">
												<select size="1" name="situacao" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >&nbsp;
                              <option value="Todos">Todos</option>
      						  <option value="NAOFECHADO">Todos Não Fechados e Concluido</option>
    						  <option value="Enviado Para Analista">Enviado Analista</option>
                              <option value="Não Verificado">Não Verificado</option>
                              <option value="Concluido">Concluido</option>
                              <option value="Fechado">Fechado</option>
     						  <option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>
						</select></td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

												Categoria:&nbsp;</td>
								<td height="23">
										<font size="1">
												<select size="1" name="categoria" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >&nbsp;
                              <option value="Todos">Todos</option>
                       <?
                        $checa = mysql_query("SELECT * FROM sgc_categoria order by id_categoria='$id_categoria' desc") or print(mysql_error());
                        while($dados=mysql_fetch_array($checa)){
                        $id_categoria = $dados['id_categoria'];
                        $descricao_cate  = $dados['descricao'];
                       ?>
                         <option value="<?echo $id_categoria?>"><?echo $descricao_cate?></option>
                       <?
                          }
                       ?>
						</select></td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

										Usuário:&nbsp;</td>
								<td height="23">
										<font size="1">
												<select size="1" name="usuario" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;">&nbsp;
                              <option value="Todos">Todos</option>
                               <?
                        $checa = mysql_query("select id_usuario,concat(us.primeiro_nome,' ',us.ultimo_nome)nome  from sgc_usuario us  order by 2") or print(mysql_error());
                        while($dados=mysql_fetch_array($checa)){
                        $id_usuario = $dados['id_usuario'];
                        $nome_us  = $dados['nome'];
                       ?>
                         <option value="<?echo $id_usuario?>"><?echo $nome_us?></option>
                       <?
                          }
                       ?>

         					</select></td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">&nbsp;</td>
								<td height="23">&nbsp;</td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

										Palavra Chave:&nbsp;</td>
								<td height="23">
										<font size="1"><input type="text" name="palavra_chave" size="49" style="background-color: #FFFFFF" maxlength="60"></td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23">&nbsp;</td>
								<td height="23">&nbsp;</td>
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
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23">&nbsp;</td>
								<td height="23">&nbsp;</td>
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
 $idusuario = $_SESSION['id_usuario_global'];
 $id_item=$_POST['id_item'];
 $id_analista=$_POST['suporte'];









}
}else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
