<?php
OB_START();
session_start();



if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Seus Chamados";
$titulo_listar="Horários Já Cadastrados";
$id_item=$_GET['id_item'];


if(!isset($acao_int)){

?>
<table class="border" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
<form method="POST" id="form1" action="sgc.php?action=vis_logs.php&acao_int=visualizar" ">
							<tr>
								<td>
								<table border="0" cellpadding="5" cellspacing="1" width="100%">
									<tr>
										<td class="info" colspan="1" align="center">
										<b>Log´s de Acessos</b></td>
									</tr>
									<tr>
										<td class="cat" align="right">
										<font size="1">
										<table border="0" width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												&nbsp;</td>
												<td width="40">&nbsp;</td>
											</tr>
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												<p align="center">Selecione o
												usuário</td>
												<td width="40">&nbsp;</td>
											</tr>
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												<p align="center">
										<font size="1">
												<select size="1" name="usuario_log" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  onchange="this.form.submit();">
                              <option value="Todos"></option>
												<?
												 $checa = mysql_query("select
                                                 us.id_usuario
                                                 ,concat(us.primeiro_nome,' ',us.ultimo_nome)nome_usuario
                                                 from sgc_acesso ac, sgc_usuario us
                                                 where us.id_usuario = ac.id_usuario
                                                 group by us.id_usuario
                                                 order by us.primeiro_nome
                                                 ") or print(mysql_error());
                                                 while($dados=mysql_fetch_array($checa)){
                                                 $id_usuario_log = $dados['id_usuario'];
                                                 $nome = $dados['nome_usuario'];
                                    ?>

                              <option value="<?echo $id_usuario_log?>"><?echo $nome?></option>
                              <?
                              }
                              ?>

						</select></td>
												<td width="40">&nbsp;</td>
											</tr>
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												<p align="center">
												Selecione a data</td>
												<td width="40">&nbsp;</td>
											</tr>
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												<p align="center">
										<font size="1">
												<select size="1" name="data_acesso" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  onchange="this.form.submit();">
                              <option value="Todos">ALL</option>
												<?
												 $checa = mysql_query("select
                                                 date_format(data_acesso,'%d/%m/%Y')data_formatada
                                                 ,date_format(data_acesso,'%Y-%m-%d')data_banco
                                                 from sgc_acesso
                                                 group by date_format(data_acesso,'%d/%m/%Y')
                                                 order by data_acesso desc ") or print(mysql_error());
                                                 while($dados=mysql_fetch_array($checa)){
                                                 $data_formatada = $dados['data_formatada'];
                                                 $data_banco = $dados['data_banco'];
                                    ?>

                              <option value="<?echo$data_banco?>"><?echo $data_formatada?></option>
                              <?
                              }
                              ?>

						</select></td>
												<td width="40">&nbsp;</td>
											</tr>
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												<p align="center">
												&nbsp;</td>
												<td width="40">&nbsp;</td>
											</tr>
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												&nbsp;</td>
												<td width="40">&nbsp;</td>
											</tr>
										</table>
										</td>
									</tr>
        						</table>
		    	    			</td>
			    			</tr>
                       </form>
						</table>

<?


}elseif($acao_int=="visualizar"){


    include("conf/Pagina.class.php");

    if($_POST['data_acesso']==null){
      $data_acesso=$_GET['data_acesso'];
    }else{
      $data_acesso=$_POST['data_acesso'];
    }

    if($_POST['usuario_log']==null){
      $usuario_log=$_GET['usuario_log'];
    }else{
      $usuario_log=$_POST['usuario_log'];
    }


    if($usuario_log != "Todos"){
     $adendo=" and ac.id_usuario=$usuario_log";
    }else{
     $adendo=null;
    }


    if($data_acesso != "Todos"){
     $adendo_data=" and date_format(ac.data_acesso,'%Y-%m-%d') = '$data_acesso' ";
    }else{
     $adendo_data=null;
    }



$sql= mysql_query("
SELECT count(*) t FROM  sgc_acesso ac where 1=1 $adendo_data $adendo");
    $dados=mysql_fetch_array($sql);
    $total=$dados['t'];


    $pagina = new Pagina();
    $pagina->setLimite(20);

    $totalRegistros = $total;
	$linkPaginacao ="?action=vis_logs.php&acao_int=visualizar&id_item=$id_item&data_acesso=$data_acesso&usuario_log=$usuario_log";


?>
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Acessos do dia <?echo $data_acesso=invertedata($data_acesso)?>:: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center" style="background-color: #FFFFFF">
					<table border="1" width="773" cellspacing="0" cellpadding="0" bordercolor="#DFDFDF" style="border-collapse: collapse">
   			<tr>
							<td width="123" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Entrada</b></td>
							<td width="123" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Saída</b></td>
							<td width="390" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Usuário</b></td>
							<td width="132" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>IP de Acesso</b></td>
						</tr>
						   <?
                         $checa = mysql_query("select

                          date_format(ac.data_acesso,'%d/%m/%Y %H:%i:%s')data_acesso
                         ,date_format(ac.data_saida,'%d/%m/%Y %H:%i:%s')data_saida
                         ,concat(us.primeiro_nome,' ',us.ultimo_nome)nome_usuario
                         ,ac.ip_acesso ip_acesso_log

                         from sgc_acesso ac, sgc_usuario us
                         where 1=1
                         $adendo_data $adendo
                         and us.id_usuario = ac.id_usuario
                         order by ac.data_acesso desc  limit ".$pagina->getPagina($_GET['pagina']).", ".$pagina->getLimite());
                                    while($dados=mysql_fetch_array($checa)){
                                    $data_acesso = $dados['data_acesso'];
                                    $data_saida = $dados['data_saida'];
                                    $nome_log = $dados['nome_usuario'];
                                    $ip_acesso_log= $dados['ip_acesso_log'];

                        ?>
                        <tr>
							<td width="123" height="23"  <?echo $cor_linha?>    style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo $data_acesso?></td>
							<td width="123" height="23"  <?echo $cor_linha?>    style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo $data_saida?></td>
							<td width="390" height="23"  <?echo $cor_linha?>    style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo $nome_log?></td>
							<td width="132" height="23"  <?echo $cor_linha?>    style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo $ip_acesso_log?></td>
						</tr>
                     <?
                        }
                        ?>


					</table></td>
				</tr>
			</table></td>
		</tr>
	</table>


</div>
<br>
<p align="center">

<p align="center">


</div>
<br>
<p align="center">

<p align="center">
<?
//----------------Paginador-------------------//

Pagina::configuraPaginacao($_GET['cj'],$_GET['pagina'],$totalRegistros,$linkPaginacao, $pagina->getLimite(), $_GET['direcao']);

//--------------------------------------------//



}
elseif($acao_int=="editar_bd"){


}


}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
