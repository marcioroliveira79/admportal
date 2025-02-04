<?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("conf/conecta.php");
include("conf/funcs.php");


$idus=$_POST['idus'];
$ip=$_POST['ip'];
$session=$_POST['session'];


$checa_chat_1 = mysql_query("SELECT count(*)contador FROM sgc_chat WHERE
                           recebimento is null
                           and id_destino=$idus") or print mysql_error();
              while($dados_chat1=mysql_fetch_array($checa_chat_1)){
                    $contador= $dados_chat1["contador"];
if($contador>0){


?>
<table border=1 width=100% cellpadding=0 style='border-collapse: collapse' bordercolor='#000000'>
	<tr>
		<td bordercolor='#000000'>
		<table border='0' width='100%' cellspacing='0' cellpadding='0'>
			<tr>
				<td width='34'>&nbsp;</td>
				<td>&nbsp;</td>
				<td width='37'>&nbsp;</td>
			</tr>
			<?
			$checa_chat = mysql_query("SELECT

                            id_origem
                           ,id_mensagem
                            FROM sgc_chat
                            WHERE
                           recebimento is null
                           and id_destino=$idus
") or print mysql_error();
              while($dados_chat=mysql_fetch_array($checa_chat)){
                    $id_mensagem= $dados_chat["id_mensagem"];
                    $flag= $dados_chat["FLAG"];
                    $origem= $dados_chat["id_origem"];


$nome=tabelainfo($origem,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
?>

			<tr>
				<td width='34'>&nbsp;</td>
				<td>
				<p align='center'><b><font face='Verdana' size='2'>
                <a href="javaScript: void(window.open('open_chat.php?&id_mensagem=<?echo $id_mensagem?>&id_usuario=<?echo $idus?>&id_destino=<?echo $origem?>','Visualizar','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1,width=625,height=530'));">
                <font color='#000000'>ATENÇÃO! <?echo $nome?>, quer falar
				com você, click aqui para iniciar o chat!</font></a></font></b></td>
				<td width='37'>&nbsp;</td>
			</tr>
            <?
            }
            ?>
            <tr>
				<td width='34'>&nbsp;</td>
				<td>&nbsp;</td>
				<td width='37'>&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<?

  }

}

?>
