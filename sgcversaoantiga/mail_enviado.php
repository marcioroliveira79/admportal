<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];


$titulo="Caixa de Saída";
$titulo_listar="Últimos 10 Chamados Abertos por Você";
$id_item=$_GET['id_item'];
$arquivo="email.php";
$tabela="sgc_chamado";
$id_chave="id_mensagem";

include("conf/Pagina.class.php");



if(!isset($acao_int)){

if($_POST['mensagem_mail'] != null ){
   $mensagem_mail=$_POST['mensagem_mail'];
}else{
   $mensagem_mail=$_GET['mensagem_mail'];
}

if($_POST['visualizacao'] != null ){
   $visualizacao=$_POST['visualizacao'];
}else{
   $visualizacao=$_GET['visualizacao'];
}

echo $mensagem_mail;


?>


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

                   <?
                   
                   if($mensagem_mail!=null){
                    $sqlmsn="  AND um.id_mensagem=$mensagem_mail ";
                    $sqlmsn1=" AND saida.id_mensagem=$mensagem_mail ";
                   }

                   $checa_item = mysql_query("SELECT count(*)cont FROM sgc_mensagem_enviada um
                   WHERE um.origem=$idusuario
                   $sqlmsn
                  ") or print mysql_error();
                        while($dados_item=mysql_fetch_array($checa_item)){
                                    $cont= $dados_item["cont"];
                                    }

                   if($cont==0){
                   ?>

                    <table border="1" width="344" cellspacing="0" cellpadding="0" style="border-collapse: collapse">
						<tr>
							<td>
							<p align="center"><br>
							Você não possui mensagens em sua caixa de saída<br>
                         &nbsp;</td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
                <?
                 }else{
                 
                 
                 
                  $pagina = new Pagina();
                  $pagina->setLimite(20);

                  $totalRegistros = $cont;
	              $linkPaginacao ="?action=mail_enviado.php&id_item=$id_item";

                 
                 
                ?>   	<form method="POST" action="?action=mail_enviado.php&id_item=<?echo $id_item?>">
                        <p align="left">
                      		<select size="1" name="mensagem_mail" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                              <option value="Todos"></option>
												<?
												 $checa = mysql_query("SELECT distinct assunto, id_mensagem FROM sgc_mensagem_enviada um WHERE um.origem=$idusuario $sqlmsn ORDER BY id_mensagem desc
                                                 ") or print(mysql_error());
                                                 while($dados=mysql_fetch_array($checa)){
                                                 $id_mensagem = $dados['id_mensagem'];
                                                 $assunto = $dados['assunto'];
                                    ?>

                              <option value="<?echo $id_mensagem?>"><?echo $assunto?></option>
                              <?
                              }
                              ?>

						</select></p>
						<p align="left">
                      		<select size="1" name="visualizacao_mail" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                              <option value="Todos"></option>
                                         <option value="visto">Vísto(s)</option>
                                         <option value="nvisto">Não Vísto(s)</option>

						</select> <font size="1" face="Verdana">
							<input type="submit" value="Buscar" name="B1"></font></p>
                 	</form>
						
                    <table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#808080" style="border-collapse: collapse">

                        <tr>
							<td width="20" bgcolor="#808080" height="23">&nbsp;</td>
							<td width="160" bgcolor="#808080" height="23">
							<font color="#FFFFFF">&nbsp;Para</font></td>
							<td bgcolor="#808080" height="23" width="613">
							<font color="#FFFFFF">&nbsp;Assunto</font></td>
							<td bgcolor="#808080" height="23" width="130">
							<font color="#FFFFFF">Enviado</font></td>
							<td width="110" bgcolor="#808080" height="23">
							<font color="#FFFFFF">&nbsp;Vísto</font></td>
							<td width="24" bgcolor="#808080" height="23">&nbsp;</td>
						</tr>
						<?

                        $checa_item = mysql_query("SELECT
                        saida.id_mensagem
                        ,concat(us.primeiro_nome,' ',us.ultimo_nome)nome
                        ,saida.assunto
                        ,saida.texto
                        ,if(saida.visto is null,'FECHADO','ABERTO')status

                        ,date_format(saida.visto,'%d/%m/%Y %H:%i')data_visto
                        ,date_format(saida.data_envio,'%d/%m/%Y %H:%i')data_envio
                        ,saida.destino
                        FROM sgc_mensagem_enviada saida, sgc_usuario us
                        where saida.origem=$idusuario
                        and us.id_usuario = saida.destino
                        $sqlmsn1
                        order by saida.data_envio desc
                        limit ".$pagina->getPagina($_GET['pagina']).", ".$pagina->getLimite());
                        while($dados_item=mysql_fetch_array($checa_item)){
                                    $id_mensagem= $dados_item["id_mensagem"];
                                    $nome= $dados_item["nome"];
                                    $data_envio= $dados_item["data_envio"];
                                    $data_visto= $dados_item["data_visto"];
                                    $titulo= $dados_item["assunto"];
                                    $status= $dados_item["status"];
                                    $id_destino= $dados_item["destino"];

                        if($status=="FECHADO"){
                           $status="closedmail.gif";
                        }else{
                           $status="openmail.gif";
                        }

                       $permissao_item=acesso($idusuario,$id_item);

                        if($permissao_item=="OK"){

                        ?>
						<tr>
							<td width="21" height="23" style="border-right-style: none; border-right-width: medium">
							<p align="center">
							<img border="0" src="imgs/<?echo $status?>" width="13" height="12"></td>
							<td width="161" height="23" style="border-left-style: none; border-left-width: medium; border-right-style: none; border-right-width: medium">&nbsp;<?echo $nome?></td>
							<td height="23" style="border-left-style: none; border-left-width: medium; border-right-style: none; border-right-width: medium" width="613">&nbsp;<a href="?action=mail_enviado.php&acao_int=ver_mensagem&id_mensagem=<?echo $id_mensagem?>&id_item=<?echo $id_item?>&id_destino=<?echo $id_destino?>"><font color="#000000"><?echo $titulo?></a></font></td>
							<td height="23" style="border-left-style: none; border-left-width: medium; border-right-style: none; border-right-width: medium" width="130">&nbsp;<?echo $data_envio?></td>
							<td width="101" height="23" style="border-left-style: none; border-left-width: medium">&nbsp;<?echo $data_visto?></td>
							<td width="24" height="23">
							<p align="center"><a href="?action=mail_enviado.php&acao_int=excluir&id_mensagem=<?Echo $id_mensagem?>&id_item=<?echo $id_item?>&id_destino=<?echo $id_destino?>">
							<img border="0" src="imgs/lixo.gif" width="18" height="18"></a></td>
						</tr>
						<?
                        }else{
                        $msg="Você não tem permissão para visualizar essa caixa de mensagens!";
                        }
                        }
                        if($msg!=null){

                        ?>

						<tr>
							<td width="20" height="23" style="border-right-style: none; border-right-width: medium">
							&nbsp;</td>
							<td height="23" style="border-left-style: none; border-left-width: medium; border-right-style: none; border-right-width: medium" colspan="3">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
							<td width="24" height="23">
							&nbsp;</td>
						</tr>
						<?
						 }
						?>
					</table>

					  <?
					  }
					  ?>


					</td>
				</tr>
			</table></td>
		</tr>
	</table>
</div>
<?
//----------------Paginador-------------------//

Pagina::configuraPaginacao($_GET['cj'],$_GET['pagina'],$totalRegistros,$linkPaginacao, $pagina->getLimite(), $_GET['direcao']);

//--------------------------------------------//


}elseif($acao_int=="ver_mensagem"){

$id_mensagem=$_GET['id_mensagem'];
$id_destino=$_GET['id_destino'];


                        $checa_item = mysql_query("SELECT
                        saida.id_mensagem
                        ,concat(us.primeiro_nome,' ',us.ultimo_nome)nome
                        ,saida.assunto
                        ,saida.texto
                        ,saida.destino
                        ,if(saida.visto is null,'FECHADO','ABERTO')status

                        ,date_format(saida.visto,'%d/%m/%Y %H:%i')data_visto
                        ,date_format(saida.data_envio,'%d/%m/%Y %H:%i')data_envio

                        FROM sgc_mensagem_enviada saida, sgc_usuario us
                        where saida.id_mensagem = $id_mensagem
                        and saida.destino = $id_destino
                        and us.id_usuario = saida.destino
                        order by saida.data_envio desc
                         ") or print mysql_error();
                        while($dados_item=mysql_fetch_array($checa_item)){
                                    $id_mensagem= $dados_item["id_mensagem"];
                                    $nome= $dados_item["nome"];
                                    $data= $dados_item["data_envio"];
                                    $titulo= $dados_item["assunto"];
                                    $mensagem= $dados_item["texto"];
                                    $id_destino= $dados_item["destino"];

                        }

?>
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle">&nbsp;</td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="1" width="95%" cellspacing="0" cellpadding="0" bordercolor="#808080" style="border-collapse: collapse">
						<tr>
							<td width="100%" bgcolor="#808080" height="23">
							<table border="0" width="100%" cellspacing="0" cellpadding="0" height="23">
                                <?
                                  if($msg!=null){
                                ?>
                                <tr>
									<td width="12" height="23">&nbsp;</td>
									<td width="914" align="right" height="23" colspan="2">
									<p align="center"><font color="#FFFF00"><?echo $msg?></font></td>
									<td width="12" height="23">&nbsp;</td>
								</tr>
                                <?
                                }
                                ?>
                                <tr>
									<td width="12" height="23">&nbsp;</td>
									<td width="54" align="right" height="23">
									<font color="#FFFFFF">Assunto:</font></td>
									<td width="860" height="23">
									<font color="#FFFFFF">&nbsp;<?echo $titulo?></font></td>
									<td width="12" height="23">&nbsp;</td>
								</tr>
								<tr>
									<td width="12" height="23">&nbsp;</td>
									<td width="54" align="right" height="23">
									<font color="#FFFFFF">Para:</font></td>
									<td width="860" height="23">
									<font color="#FFFFFF">&nbsp;<?echo $nome?></font></td>
									<td width="12" height="23">&nbsp;</td>
								</tr>
								<tr>
									<td width="12" height="23">&nbsp;</td>
									<td width="54" align="right" height="23">
									<font color="#FFFFFF">Data:</font></td>
									<td width="860" height="23">
									<font color="#FFFFFF">&nbsp;<?echo $data?></font></td>
									<td width="12" height="23">&nbsp;</td>
								</tr>
							</table>
							</td>
						</tr>
						<tr>
							<td height="23" style="border-right-style: none; border-right-width: medium">
							<table border="0" width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td width="13">&nbsp;</td>
									<td><BR><?echo $mensagem?><BR><BR><BR><BR></td>
									<td width="13">&nbsp;</td>
								</tr>
							</table>
							</td>
						</tr>
						<tr>
							<td height="23" style="border-right-style: none; border-right-width: medium">
							<div align="center">
								<table border="0" width="150" cellspacing="0" cellpadding="0">
									<tr>

										<td width="20">
										<p align="center">
										<img border="0" src="imgs/lixo.gif" width="18" height="18"></td>
										<td width="58"><b><a href="?action=mail_enviado.php&acao_int=excluir&id_mensagem=<?echo $id_mensagem?>&id_destino=<?echo $id_destino?>&id_item=<?echo $id_item?>">
										<font color="#000000">Excluir</font></a></b></td>


										<td width="17">
										<img border="0" src="imgs/voltar.gif" width="15" height="18"></td>
										<td width="37"><a href="?action=mail_enviado.php&id_item=<?echo $id_item?>"><b>
										<font color="#000000">Voltar</font></b></a></td>

									</tr>
								</table>
							</div>
							</td>
						</tr>
					</table>
					</td>
				</tr>
			</table></td>
		</tr>
	</table>
</div>

<?
}elseif($acao_int=="excluir"){

 $idusuario = $_SESSION['id_usuario_global'];
 $id_item=$_GET['id_item'];
 $id_mensagem=$_GET['id_mensagem'];
 $id_destino=$_GET['id_destino'];

 $permissao_item=acesso($idusuario,$id_item);

  if($permissao_item=="OK"){

      $deleta = mysql_query("DELETE FROM sgc_mensagem_enviada where id_mensagem=$id_mensagem and destino=$id_destino") or print(mysql_error());

      header("Location: ?action=mail_enviado.php&id_item=$id_item");
   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=mail_enviado.php&msg=$msg");
   }
}elseif($acao_int=="sucesso"){

}else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=mail_enviado.php&id_item=$id_item&msg=$msg");
   }


  }


else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>

