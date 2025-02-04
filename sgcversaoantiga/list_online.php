<?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include("conf/conecta.php");
include("conf/funcs.php");


?>
<div align="center">
<div id="online" align="center">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Usuário(s) On-line <?echo $titulo?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
				<table border="0" width="750" cellpadding="0">
					<tr>
						<td width="388">
                         <b>Usuário</b></td>
						<td width="179">
                    	<b>IP</b></td>
						<td width="61">
                    	&nbsp;<b>Status</b></td>
                     	</tr>

<? $tempo3=atributo('atributo3');?>

<?


$checa = mysql_query("
SELECT
concat(us.primeiro_nome,' ',us.ultimo_nome,' - ',un.sigla,' - ',dp.descricao)usuario
,us.id_usuario
,if(time_to_sec(TIMEDIFF(sysdate('%Y-%m-%d %H:%i:%s'),onl.ultimo_registro))>$tempo3,'OFF-LINE','ON-LINE')status
,onl.ip
,time_to_sec(TIMEDIFF(sysdate('%Y-%m-%d %H:%i:%s'),onl.ultimo_registro))sec
FROM sgc_usuario us, sgc_usuario_online onl, sgc_unidade un, sgc_departamento dp
where onl.id_usuario = us.id_usuario
and us.id_departamento = dp.id_departamento
and us.id_unidade = un.codigo
and if(time_to_sec(TIMEDIFF(sysdate('%Y-%m-%d %H:%i:%s'),onl.ultimo_registro))>$tempo3,'OFF-LINE','ON-LINE') = 'ON-LINE'
order by usuario
") or print(mysql_error());
                                 while($dados=mysql_fetch_array($checa)){
                                 if($dados['status']=="ON-LINE"){
                                  $status = $dados['status'];
                                  $usuario = $dados['usuario'];
                                  $id_usuario = $dados['id_usuario'];
                                  $ip = $dados['ip'];

  $count_offline++;
  $cor="#008000";




?>
					<tr>
						<td width="388">
                          <?
                            if($id_usuario!=$_POST['idus']){
                          ?>
                          <a href="javaScript: void(window.open('open_chat.php?id_usuario=<?echo $_POST['idus']?>&id_destino=<?echo $id_usuario?>','Visualizar','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1,width=625,height=530'));">
                          <?
                          }
                          ?>
                          <?echo $usuario;?>

                          </td><td width="179"><?echo $ip?></td>

                        <td width="61"><font color="<?echo $cor?>"><?echo $status?></font></td>
					</tr>
<?
}
  $status = null;
  $usuario = null;
  $id_usuario = null;
  $ip = null;



}


?>

             		<tr>
						<td width="388"></td>
						<td width="179"><b>ON-LINE <?echo $count_offline?></b></td>
						<td width="61"></font></td>
					</tr>
					</table>

					</td>
				</tr>
			</table>

</div>
</div>
<?
