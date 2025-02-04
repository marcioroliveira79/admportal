<?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");



include("conf/funcs.php");
include("conf/conecta.php");

$id_usuario=$_POST['id_usuario'];
$id_destino=$_POST['id_destino'];
$id_mensagem=$_POST['id_mensagem'];



?>
<script language='javascript'>
function maximize() {
    var winX = (screen.availWidth - window.outerWidth) / 2
    var winY = 50
    window.moveTo(winX, winY)
    window.resizeTo(625,530)
}
</script>




<body onLoad="scroller()">

<table border="0" width="570" cellspacing="0" cellpadding="0">
<?
$checa = mysql_query("SELECT
 concat(if(date_format(sysdate(),'%d/%m/%Y')>date_format(ch.envio,'%d/%m/%Y'),date_format(ch.envio,'%d/%m/%Y %h:%i'),date_format(ch.envio,'%h:%i:%s')),' - '
,if(ud.id_usuario=ch.id_origem,'EU',concat(uo.primeiro_nome,' ',uo.ultimo_nome)))origem

,ch.mensagem
,ch.id_mensagem id_mensagem_vista
,ud.primeiro_nome
,ch.id_origem
,ch.id_destino

FROM sgc_chat ch, sgc_usuario ud,sgc_usuario uo
WHERE    ch.id_origem in ($id_usuario,$id_destino) and ch.id_destino in ($id_usuario,$id_destino)
and ud.id_usuario = ch.id_destino
and uo.id_usuario = ch.id_origem

order by ch.id_mensagem asc") or print(mysql_error());
                             while($dados=mysql_fetch_array($checa)){
                              $id_origem = $dados['id_origem'];
                              $origem = $dados['origem'];
                              $mensagem = $dados['mensagem'];
                              $id_mensagem_vista = $dados['id_mensagem_vista'];
                              $id_destino_atual = $dados['id_destino'];

if($id_usuario==$id_destino_atual){
              $cadas = mysql_query("UPDATE sgc_chat set recebimento=sysdate() where id_mensagem = $id_mensagem_vista") or print(mysql_error());
}

                              if($id_origem==$id_usuario){
                                          $color="#C0C0C0";
                                          $color1="#008000";
                                 }else{
                                          $color="#0000FF";
                                          $color1="#FF0000";
                                          ?>
                                          <script language='javascript'>
                                             var winX = (screen.availWidth - window.outerWidth) / 2
                                             var winY = 50
                                             window.moveTo(winX, winY)
                                             window.resizeTo(625,530)
                                          </script>
                                          
                                          <?
                                  }
                              

?>
    <tr>
		<td height="23"><b><font face="Verdana" size="2" color="<?echo $color?>"><?echo $origem?>:</font></b></td>
	</tr>
	<tr>
		<td height="23"><font face="Verdana" size="2" color="<?echo $color1?>"><?echo nl2br($mensagem); ?></font></td>
	</tr>
<?


}
?>

</table>


<?



?>
