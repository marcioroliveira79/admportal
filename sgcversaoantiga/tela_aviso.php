<?php
OB_START();
session_start();


if($permissao=='ok'){

$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

?>
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: ATENÇÃO :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="488" cellspacing="0" cellpadding="0">
                      		<tr>
							<td width="488" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
						</tr>
						<tr>
					<td height="23" width="488">
                    <div id="atualiza">
         			</div>
                        	</td>
						</tr>
						</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>
<?
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>

