<?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include("conf/conecta.php");
include("conf/funcs.php");


$id_item=$_POST['id_item'];
$ip=$_POST['ip'];
$tipo_user=$_POST['tipo_user'];
$tipo_user="xfac";


?>

<table border="1" width="400" cellpadding="0" style="border-collapse: collapse" bordercolor="#C0C0C0" bgcolor="#FFFFFF">
						<tr>
							<td>
							<table border="0" width="400" cellspacing="0" cellpadding="0">
								<tr>
									<td width="400" colspan="4">
									<p align="center"><b>Usuários Logados</b></td>
								</tr>
								<tr>
									<td width="74">
									<p align="center"><b>Usuário</b></td>
									<td width="230">
									<p align="center"><b>Origem</b></td>
									<td width="78">
									<p align="center"><b>Login</b></td>
									<td width="16">
									&nbsp;</td>
								</tr>
                               <?

                                exec("sudo /var/www/xfac/sgc/executor.sh $ip 'w -huo' ",$resultado);
                                $resultado = str_replace("$tipo_user","|| $tipo_user",$resultado[0]);
                                $resultado = preg_replace('/ttyp[{0-9}]/i','CONECCAO',$resultado);
                                $resultado = explode("||", $resultado);
                                $x=0;
                                foreach($resultado as $valor){
                                 $linha[$x]=explode(" ", $valor);
                                 $x++;
                                }
                                foreach($linha as $valor){

                                if($valor[1]!=null && $valor[1]=="$tipo_user" ){
                                $data_hora=$valor[4]." ".$valor[5];
                                ?>
								<tr>
									<td width="74">
									<p align="center"><?echo $valor[1];?></td>
									<td width="230">
									<p align="center"><?echo $valor[3];?></td>
									<td width="78">
									<p align="left"><?echo $data_hora;?></td>
									<td width="16">
									&nbsp;</td>
								</tr>
                                <?
                                }
                                }
                                ?>

								<tr>
									<td width="390" colspan="4">
									<p align="center">&nbsp;</td>
								</tr>

                                <tr>
                                    <td width="390" colspan="4">

                                <form method="POST" name="killform" action="?action=monitor_ajax_tabela.php&acao_int=kill">
                                    <input type='hidden' name='tipo_user' value='<?echo $tipo_user?>'>
                                    <input type='hidden' name='ip' value='<?echo $ip?>'>
                                    <input type='hidden' name='id_item' value='<?echo $id_item?>'>

                                    <p align="center">
                                	<input type="button" value="Derrubar Usuários" onclick="pergunta()"></td>
								</form>
								</tr>

								<tr>
									<td width="390" colspan="4">
									&nbsp;</td>
								</tr>

								</table>
							</td>
						</tr>
					</table>





