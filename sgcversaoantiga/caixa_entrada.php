<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Mensagens";
$titulo_listar="Últimos 10 Chamados Abertos por Você";
$id_item=$_GET['id_item'];
$arquivo="caixa_entrada.php";
$tabela="sgc_chamado";
$id_chave="id_mensagem";





if(!isset($acao_int)){



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

                   $checa_item = mysql_query("SELECT count(*)cont FROM sgc_usuarios_mensagens um, sgc_mensagem sm, sgc_usuario us
                         WHERE um.id_usuario=$idusuario
                         and sm.id_mensagem = um.id_mensagem
                         and us.id_usuario = sm.quem_criou order by um.data_criacao desc") or print mysql_error();
                        while($dados_item=mysql_fetch_array($checa_item)){
                                    $cont= $dados_item["cont"];
                                    }
                                    
                   if($cont==0){
                   ?>

                    <table border="1" width="344" cellspacing="0" cellpadding="0" style="border-collapse: collapse">
						<tr>
							<td>
							<p align="center"><br>
							Você não possui mensagens em sua caixa de entrada<br>
                         &nbsp;</td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
                <?
                 }else{
                ?>


                    <table border="1" width="95%" cellspacing="0" cellpadding="0" bordercolor="#808080" style="border-collapse: collapse">

                        <tr>
							<td width="20" bgcolor="#808080" height="23">&nbsp;</td>
							<td width="160" bgcolor="#808080" height="23">
							<font color="#FFFFFF">&nbsp;De</font></td>
							<td bgcolor="#808080" height="23">
							<font color="#FFFFFF">&nbsp;Assunto</font></td>
							<td width="101" bgcolor="#808080" height="23">
							<font color="#FFFFFF">&nbsp;Recebido</font></td>
							<td width="24" bgcolor="#808080" height="23">&nbsp;</td>
						</tr>
						<?

                        $checa_item = mysql_query("SELECT
                         concat(us.primeiro_nome,' ',us.ultimo_nome)nome
                         ,um.id_mensagem
                         ,date_format(um.data_criacao,'%d/%m/%Y %H:%i')data
                         ,sm.titulo
                         ,if(um.visto is null,'FECHADO','ABERTO')status
                         FROM sgc_usuarios_mensagens um, sgc_mensagem sm, sgc_usuario us
                         WHERE um.id_usuario=$idusuario
                         and sm.id_mensagem = um.id_mensagem
                         and us.id_usuario = sm.quem_criou order by um.data_criacao desc") or print mysql_error();
                        while($dados_item=mysql_fetch_array($checa_item)){
                                    $id_mensagem= $dados_item["id_mensagem"];
                                    $nome= $dados_item["nome"];
                                    $data= $dados_item["data"];
                                    $titulo= $dados_item["titulo"];
                                    $status= $dados_item["status"];
                                    
                        if($status=="FECHADO"){
                           $status="closedmail.gif";
                        }else{
                           $status="openmail.gif";
                        }

                       $permissao_item=acesso($idusuario,$id_item);

                        if($permissao_item=="OK"){

                        ?>
						<tr>
							<td width="20" height="23" style="border-right-style: none; border-right-width: medium">
							<p align="center">
							<img border="0" src="imgs/<?echo $status?>" width="13" height="12"></td>
							<td width="160" height="23" style="border-left-style: none; border-left-width: medium; border-right-style: none; border-right-width: medium">&nbsp;<?echo $nome?></td>
							<td height="23" style="border-left-style: none; border-left-width: medium; border-right-style: none; border-right-width: medium">&nbsp;<a href="?action=caixa_entrada.php&acao_int=ver_mensagem&id_mensagem=<?echo $id_mensagem?>&id_item=<?echo $id_item?>"><font color="#000000"><?echo $titulo?></a></font></td>
							<td width="101" height="23" style="border-left-style: none; border-left-width: medium">&nbsp;<?echo $data?></td>
							<td width="24" height="23">
							<p align="center"><a href="?action=caixa_entrada.php&acao_int=excluir&id_mensagem=<?Echo $id_mensagem?>&id_item=<?echo $id_item?>">
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
}elseif($acao_int=="nao_lida"){
$id_mensagem=$_GET['id_mensagem'];
$iditem=$_GET['id_item'];

$cadas = mysql_query("UPDATE sgc_usuarios_mensagens SET visto=null where id_mensagem=$id_mensagem") or print(mysql_error());
header("Location: ?action=$arquivo&id_item=$id_item");

}elseif($acao_int=="ver_mensagem"){

$id_mensagem=$_GET['id_mensagem'];

$cadas = mysql_query("UPDATE sgc_usuarios_mensagens SET visto=sysdate() where id_mensagem=$id_mensagem and id_usuario=$idusuario") or print(mysql_error());
$cadas = mysql_query("UPDATE sgc_mensagem_enviada SET visto=sysdate() where id_mensagem=$id_mensagem and destino=$idusuario") or print(mysql_error());

                        $checa_item = mysql_query("           SELECT
                         concat(us.primeiro_nome,' ',us.ultimo_nome)nome
                         ,um.id_mensagem
                         ,date_format(um.data_criacao,'%d/%m/%Y %H:%i')data
                         ,sm.titulo
                         ,sm.mensagem
                         ,um.visto
                         FROM
                           sgc_usuarios_mensagens um
                         , sgc_mensagem sm
                         , sgc_usuario us

                         WHERE sm.id_mensagem=$id_mensagem

                         and sm.id_mensagem = um.id_mensagem
                         and us.id_usuario = sm.quem_criou
                         and um.id_usuario = $idusuario
                         ") or print mysql_error();
                        while($dados_item=mysql_fetch_array($checa_item)){
                                    $id_mensagem= $dados_item["id_mensagem"];
                                    $nome= $dados_item["nome"];
                                    $data= $dados_item["data"];
                                    $titulo= $dados_item["titulo"];
                                    $mensagem= $dados_item["mensagem"];

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
									<font color="#FFFFFF">De:</font></td>
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
								<table border="0" width="350" cellspacing="0" cellpadding="0">
									<tr>

										<td width="20">
										<p align="center">
										<img border="0" src="imgs/lixo.gif" width="18" height="18"></td>
										<td width="58"><b><a href="?action=caixa_entrada.php&acao_int=excluir&id_mensagem=<?echo $id_mensagem?>&id_item=<?echo $id_item?>">
										<font color="#000000">Excluir</font></a></b></td>
										<td width="21">
										<p align="center">
										<img border="0" src="imgs/closedmail.gif" width="13" height="12"></td>
										<td width="134"><b><a href="?action=caixa_entrada.php&acao_int=nao_lida&id_mensagem=<?echo $id_mensagem?>&id_item=<?echo $id_item?>">
										<font color="#000000">Marcar como não
										lida</font></a></b></td>
										<td width="17">
										<img border="0" src="imgs/voltar.gif" width="15" height="18"></td>
										<td width="37"><a href="?action=caixa_entrada.php&id_item=<?echo $id_item?>"><b>
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

 $permissao_item=acesso($idusuario,$id_item);

  if($permissao_item=="OK"){

      $deleta = mysql_query("DELETE FROM sgc_usuarios_mensagens where id_mensagem=$id_mensagem and id_usuario=$idusuario") or print(mysql_error());

       $checa_msg= mysql_query("select
       count(*)CONTADOR
       FROM
        sgc_usuarios_mensagens
        where id_mensagem=$id_mensagem ") or print mysql_error();
                                while($dados_menu=mysql_fetch_array($checa_msg)){
                                $count_msg= $dados_menu["CONTADOR"];
                                }

      if($count_msg<1){
      $deleta = mysql_query("DELETE FROM sgc_mensagem where id_mensagem=$id_mensagem") or print(mysql_error());
      }

     header("Location: ?action=$arquivo&id_item=$id_item");
   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=$arquivo&id_mensagem=$id_mensagem&id_item=$id_item&msg=$msg");
   }


}elseif($acao_int=="sucesso"){
?>
    <div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: SUCESSO :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td>
							<p align="center">&nbsp;</p>
							<p align="center">ID Chamado #&nbsp;<?echo $id_ultimo_chamado=ultimo_registro('id_chamado','sgc_chamado','id_chamado');?> <br>
							Chamado concluído com sucesso!</p>
							<p align="center">&nbsp;</td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>
<p>&nbsp;</p>
<?

}else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=$arquivo&id_item=$id_item&msg=$msg");
   }


  }


else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>

