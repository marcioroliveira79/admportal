<?php
OB_START();
session_start();

header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include("conf/conecta.php");
include("conf/funcs.php");

$idus=$_GET['id_usuario'];
$session=$_POST['session'];
$id_mensagem=$_GET['id_mensagem'];

if($_GET['id_destino']==null){
 $id_destino=$_POST['id_destino'];
}else{
 $id_destino=$_GET['id_destino'];
}





$action=$_GET['action'];
$msg=$_GET['msg'];

if(!isset($action)){

$checa_chat = mysql_query("SELECT * FROM sgc_chat WHERE
id_destino=$idus
order by id_mensagem asc
") or print mysql_error();
              while($dados_chat=mysql_fetch_array($checa_chat)){
                    $id_mensagem= $dados_chat["id_mensagem"];
                    $mensagem= $dados_chat["mensagem"];
                    $origem= $dados_chat["id_origem"];

}
if($origem==null){

 $origem=$_GET['id_destino'];
}
?>
<script type="text/javascript">
function resizeWindow()
  {
  window.resizeTo(625,530)
  }
</script>

<script src="prototype.js" type="text/javascript"></script>
<script>
function submitForm(form) {
            new Ajax.Request('open_chat.php?action=send',
            {
            method:'post',
            parameters: $('camposajax').serialize(true),

            onSuccess:function(transport){
              var req = transport.responseText || 'NAO CARREGOU';
                return 1
            },
              onFailure:function(){
                return 0
            }
});

}

function stopEvent(event) {
    if (event.preventDefault) {
        event.preventDefault();
        event.stopPropagation();
    } else {
        event.returnValue = false;
        event.cancelBubble = true;
    }
}

function areaEnvia(obj, evt) {
    var e = evt || event;
    var k = e.keyCode;

    if(k == 13) { //verifica se teclou enter
        if(!e.shiftKey) {
            if(obj.form)
                obj.form.submit();

            stopEvent(e);
        }
    }
}

document.form1.texto.focus()

</script>

<script type="text/javascript">
function setFocus(){
document.getElementById("texto").focus();
}
</script>


	<head>
	<meta http-equiv='Content-Language' content='pt-br'>
	</head>





	<body topmargin="0" leftmargin="0" >
	

	
	
	<form name="form1" method="POST" id="camposajax" action="open_chat.php?action=send">
	<table border="1" width="624" style="border-collapse: collapse" cellpadding="0" bordercolor="#000000">
		<tr>
			<td height="23">
			<p align="left">
               	<iframe width="618" scrolling="auto" height="326" src="list_mensagens.php?id_mensagem=<?echo $id_mensagem?>&id_usuario=<?echo $idus?>&id_destino=<?echo $id_destino?>"></iframe></td>
           </tr>
		<tr>
			<td height="23">

            <textarea rows="5" id="texto" name="texto" tabindex="1" onkeydown="areaEnvia(this, event);" cols="75"></textarea>

            </td>
            <input type='hidden' name='id_usuario' value='<?echo $idus?>'>
            <input type='hidden' name='id_destino' value='<?echo $id_destino?>'>
            <input type='hidden' name='id_mensagem' value='<?echo $id_mensagem?>'>
		</tr>
		<tr>
			<td height="23">
			<p align="center">
			<br>
			<input type="submit"  value="Enviar" name="B1" ><br>
			<?
			//onclick="submitForm(); return false"
			?>
			<font face="Verdana" size="2" color="#FF0000"><br>
			<?echo $msg?><br>
	&nbsp;</font></td>
		</tr>
		<tr>
			<td height="23">
			&nbsp;</td>
		</tr>
	</table>
	</form>
	



<?
}elseif($action=="send"){

echo $texto_send=$_POST['texto']; echo "<BR>";
echo $usuario=$_POST['id_usuario'];echo "<BR>";
echo $destino=$_POST['id_destino'];echo "<BR>";
echo $mensagem=$_POST['id_mensagem'];echo "<BR>";


$session="$usuario$destino";

$cadas = mysql_query("insert into sgc_chat
 (id_origem
 ,id_destino
 ,mensagem
 ,envio
 ,session

 )

values

 ($usuario
 ,$destino
 ,'$texto_send'
 ,sysdate()
 ,'$session')") or print(mysql_error());

              header("Location: open_chat.php?id_usuario=$usuario&id_mensagem=$mensagem&id_destino=$destino");



}

?>
