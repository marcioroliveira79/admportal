<?php
OB_START();
session_start();
$acao_int=$_GET['acao_int'];

if(!isset($acao_int)){
$id_chamado=$_GET['id_chamado'];
$id_usuario=$_GET['id_usuario'];

?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.titulo.value=="")
    {
        alert ("\nDigite o título.");
        nomeform.titulo.focus();
        return false;
    }
    if (nomeform.nota.value=="")
    {
        alert ("\nDigite a nota ou código");
        nomeform.nota.focus();
        return false;
    }
return true;
}
</script>


<BR>
<html>

<head>
<meta http-equiv="Content-Language" content="pt-br">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Notas e Códigos</title>
</head>
<form method="POST" name='meuFormulario' action="?acao_int=gravar" onsubmit='return valida_dados(this)'>

  <input type='hidden' name='id_chamado' value='<?echo $id_chamado?>'>
  <input type='hidden' name='id_usuario' value='<?echo $id_usuario?>'>

<body topmargin="0" leftmargin="0">
<table border="0" width="650" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td width="16">&nbsp;</td>
				<td>
				<table border="1" width="100%" cellpadding="0" style="border-collapse: collapse">
					<tr>
						<td bgcolor="#C0C0C0" height="23">
						<p align="center"><b><font face="Arial" size="2">::
						Notas e Códigos ::</font></b></td>
					</tr>
					<tr>
						<td height="23" style="border-bottom-style: none; border-bottom-width: medium">
						<p align="center"><b><font size="2" face="Arial">Título</font></b></td>
					</tr>
					<tr>
						<td height="23" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
						<p align="center">
						<input type="text" name="titulo" size="96" style="border: 1px solid #C0C0C0; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></td>
					</tr>
					<tr>
						<td height="23" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
						<p align="center"><b><font face="Arial" size="2">Nota ou
						Código</font></b></td>
					</tr>
					<tr>
						<td height="23" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
						<p align="center">
						<textarea rows="15" name="nota" cols="72"></textarea></td>
					</tr>
					<tr>
						<td height="23" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
					</tr>
    	<tr>
						<td height="23" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
						<p align="center"><font face="Arial" size="2">Acesso:
						</font>
						<select size="1" name="acesso" style="font-family: Arial; font-size: 10pt">
                        <option value="grupo">Somente meu(s) grupo(s) de suporte</option>
    					<option>Todos</option>
						<option value="criador">Somente EU</option>
      						</select></td>
					</tr>
					<tr>
						<td height="23" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
					</tr>
					<tr>
						<td height="23" style="border-top-style: none; border-top-width: medium">
						<p align="center">
						<input type="submit" value="Inserir" name="B1"></td>
					</tr>
				</table>
				</td>
				<td width="18">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
</table>

</form>

<?
}elseif($acao_int=="gravar"){

include("conf/conecta.php");
$mysql=new sgc;
$mysql->conectar();

echo "Gravando...";
echo $id_chamado=$_POST['id_chamado']; echo "<BR>";
echo $id_usuario=$_POST['id_usuario']; echo "<BR>";
echo $titulo=$_POST['titulo']; echo "<BR>";
echo $nota=$_POST['nota']; echo "<BR>";
echo $acesso=$_POST['acesso'];echo "<BR>";

if($acesso=="Todos"){
 $criador=null;
 $grupo_criador=null;
}elseif($acesso="Criador"){
 $criador="X";
 $grupo_criador=null;
}elseif($acesso="Grupo"){
 $criador=null;
 $grupo_criador="X";
}

$cadas = mysql_query("insert into sgc_notas_codigos
 (id_chamado
 ,titulo_nota
 ,nota
 ,data_criacao
 ,quem_criou
 ,somente_criador
 ,grupo_criador

 )

values

 ($id_chamado
 ,'$titulo'
 ,'$nota'
 ,sysdate()
 ,$id_usuario
 ,'$criador'
 ,'$grupo_criador'
 )") or print(mysql_error());


echo "<script language='JavaScript'>
        function carregar(){
          window.opener = window
          window.close()
     }
     </script>"
?>
<body onLoad="carregar()">
</body>
</html>
<?

}
?>

