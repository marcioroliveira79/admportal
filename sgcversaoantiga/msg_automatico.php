<?php
OB_START();
session_start();

$permissao=$_POST['permissao'];
$idusuario=$_POST['idus'];
$idmensagem=$_POST['id_mensagem'];
$url=$_POST['url'];

$conexao = mysql_connect('mysql.conab.gov.br','xfac','xfacsalvador') or die ("Não foi possível conectar com o MySQL!");
            mysql_select_db('db_sgc') or die ("Banco de dados inexistente");

if($permissao=='ok'){
$acao_int=$_POST['acao_int'];

if(!isset($acao_int)){
	$checa = mysql_query ("SELECT
                          concat(us.primeiro_nome,' ',us.ultimo_nome)nome
                         ,um.id_mensagem
                         ,date_format(um.data_criacao,'%d/%m/%Y %H:%i')data
                         ,sm.titulo
                         ,sm.mensagem
                         ,um.visto
                         ,um.id_lista
                         FROM
                           sgc_usuarios_mensagens um
                         , sgc_mensagem sm
                         , sgc_usuario us

                         WHERE sm.id_mensagem=$idmensagem

                         and sm.id_mensagem = um.id_mensagem
                         and us.id_usuario = sm.quem_criou
                         and um.id_usuario = $idusuario
                         and um.visto is null order by um.id_mensagem DESC")or print(mysql_error());
                          while($dados=mysql_fetch_array($checa)){
                               $id_lista = $dados['id_lista'];
                               $nome = $dados['nome'];
                               $data = $dados['data'];
                               $titulo = $dados['titulo'];
                               $mensagem = $dados['mensagem'];
                           }



                           

?>
<script type="text/javascript">
function fecharId(div)
{
	document.getElementById(div).style.display = "none";
}
</script>

<style type="text/css">
.style2 {
	color: #FFFFFF;
	font-size: 8px;
}
</style>
</head>
</head>

<div id="showimage"  style="width:937px;position:absolute;z-index:3;border:1px solid #800000;left:200px;top:123px;">
	<div style="background:#FF0000;color:#ffffff;padding:5px">
	<div style="float:LEFT;font-size:10px;"><font face="Arial"><b>A T E N Ç Ã O</b></font></div>
	<div style="clear:both"></div>
</div>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
			<table border="0" width="100%" style="border-collapse: collapse" bgcolor="#FFFFFF">
				<tr>
    				<input type="hidden" id="DOB" name="idlista" value="<?echo $id_lista?>" />
         			<td width="163">
					<p align="center">
					<img border="0" src="imgs/logo_horizontal.jpg" width="120" height="61"></td>
					<td width="596">
					<p align="center">
					<font face="Verdana" size="4" color="#FF0000"><?echo $titulo?></font></td>
					<td width="169">
					&nbsp;</td>
				</tr>
				<tr>
					<td width="163">
					&nbsp;</td>
					<td width="596">
					<p align="center"><font face="Arial">De: <?echo $nome?> Data: <?echo $data?></font></td>
					<td width="169">
					&nbsp;</td>
				</tr>
				<tr>
					<td width="930" colspan="3">
					<table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0">
						<tr>
							<td width="19">&nbsp;</td>
							<td>
							<p style 0cm;\ class="\&quot;western\&quot;" align="\&quot;JUSTIFY\&quot;">
							<font face="Arial"><?echo $mensagem?></font><p style 0cm;\ class="\&quot;western\&quot;" align="\&quot;JUSTIFY\&quot;">
							<input type = "submit" value = "OK! Li está informação" onclick="document.getElementById('showimage').style.visibility='hidden'" ></td>

							<td width="22">&nbsp;</td>
						</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td width="930" colspan="3">
					<p align="center">&nbsp;</td>
				</tr>
			</table>
			</td>
		</tr>
</table>
</div>


<?

//$insert = mysql_query ("UPDATE sgc_usuarios_mensagens SET visto=sysdate() WHERE id_lista=$id_lista")or print(mysql_error());


}
}else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
