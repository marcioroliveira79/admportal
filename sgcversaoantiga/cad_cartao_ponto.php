<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Registro de Ponto";
$id_item=$_GET['id_item'];
$arquivo="cad_cartao_ponto.php";
$tabela="sgc_cartao_ponto";
$id_chave="id_menu_item";





if(!isset($acao_int)){
?>
<style type="text/css">
<!--
  .formata { /* esta classe é somente
               para formatar a fonte */
  font: 12px arial, verdana, helvetica, sans-serif;
  }
  a.dcontexto{
  position:relative;
  font:12px arial, verdana, helvetica, sans-serif;
  padding:0;
  color:#039;
  text-decoration:none;
  border-bottom:2px dotted #039;
  cursor:help;
  z-index:24;
  }
  a.dcontexto:hover{
  background:transparent;
  z-index:25;
  }
  a.dcontexto span{display: none}
  a.dcontexto:hover span{
  display:block;
  position:absolute;
  width:230px;
  top:3em;
  text-align:justify;
  left:0;
  font: 12px arial, verdana, helvetica, sans-serif;
  padding:5px 10px;
  border:1px solid #999;
  background:#e0ffff;
  color:#000;
  }
  -->
</style>
<script>
function sendForm( btn )
{
if( btn.name == "chegada_manha" )
{
document.registro.action = "sgc.php?action=cad_cartao_ponto.php&acao_int=registro&periodo=1";
document.registro.target = "_self";
}
else if( btn.name == "saida_almoco" )
{
document.registro.action = "sgc.php?action=cad_cartao_ponto.php&acao_int=registro&periodo=2";
document.registro.target = "_self";
}
else if( btn.name == "entrada_tarde" )
{
document.registro.action = "sgc.php?action=cad_cartao_ponto.php&acao_int=registro&periodo=3";
document.registro.target = "_self";
}
else if( btn.name == "saida_tarde" )
{
document.registro.action = "sgc.php?action=cad_cartao_ponto.php&acao_int=registro&periodo=4";
document.registro.target = "_self";
}
document.registro.submit();
}
</script>

<form method="POST" name="registro" style="font-family: Verdana; font-size: 8pt">
<input type='hidden' name='id_item' value='<?echo $id_item?>'>
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
					<table border="0" width="488" cellspacing="0" cellpadding="0">
						<tr>
							<td width="133">
							<p align="right">&nbsp;&nbsp; </td>
							<td width="355" height="23">
                            <div id="atualiza">
                            &nbsp;</div>
                            </td>
						</tr>
						<tr>
							<td width="133">
							<p align="right">Entrada Manhã:&nbsp;&nbsp; </td>
							<td width="355" height="23">
							<?
                            if(hora_agora()<"12:30:00"){
                            if(verefica_cartao($idusuario,1)==0){
							?>
                            <input type="submit" value="Registrar" name="chegada_manha" onclick="sendForm(this);"></td>
                            <?
                            }else{
                            ?>
                            <font color="#FF0000"><?echo verefica_cartao($idusuario,"1")?></font>
                            <?
                             }
                            }else{
                            ?>
                            <font color="#FF0000"><?echo verefica_cartao($idusuario,"1")?></font>
                            <?
                            }
                            ?>

						</tr>
						<tr>
							<td width="133">
							<p align="right">Saída Manhã:&nbsp;&nbsp; </td>
							<td width="355" height="23">
                            <?
                         if(hora_agora()<="12:30:00"){
							if(verefica_cartao($idusuario,2)==0){
                              if(verefica_cartao($idusuario,1)==0){

							?>

                            <?
                              }else{
                              ?>
                              <input type="submit" value="Registrar" name="saida_almoco" onclick="sendForm(this);"></td>
                              <?
                              }
                            }else{
                            ?>
                            <font color="#FF0000"><?echo verefica_cartao($idusuario,"2")?></font>
                            <?
                            }
                            }else{
                            ?>
                            <font color="#FF0000"><?echo verefica_cartao($idusuario,"2")?></font>
                            <?

                            }
                            ?>
						</tr>
						<tr>
							<td width="133">
							<p align="right">Entrada Tarde:&nbsp;&nbsp; </td>
							<td width="355" height="23">
                            	<?
                            if(hora_agora()>"12:30:00"){
                            if(verefica_cartao($idusuario,3)==0){
							?>
                            <input type="submit" value="Registrar" name="entrada_tarde" onclick="sendForm(this);"></td>
                            <?
                            }else{
                            ?>
                            <font color="#FF0000"><?echo verefica_cartao($idusuario,"3")?></font>
                            <?
                            }
                            }else{
                            ?>
                            <font color="#FF0000"><?echo verefica_cartao($idusuario,"3")?></font>
                            <?
                            }
                            ?>
						</tr>
						<tr>
							<td width="133">
							<p align="right">Saída Tarde:&nbsp;&nbsp; </td>
							<td width="355" height="23">
                            	<?
							if(verefica_cartao($idusuario,4)==0){
                             if(verefica_cartao($idusuario,3)==0){
                             ?>
                             
                             <?
                             }else{
							?>
                            <input type="submit" value="Registrar" name="saida_tarde" onclick="sendForm(this);"></td>
                            <?
                             }
                            }else{
                            ?>
                            <font color="#FF0000"><?echo verefica_cartao($idusuario,"4")?></font>
                            <?
                            }

                            ?>
						</tr>
						</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>
</form>
<?
$checa = mysql_query("SELECT
*,
  if(entrada_manha is not null and saida_almoco is not null,timediff(saida_almoco,entrada_manha),'')MANHA
, if(entrada_tarde is not null and saida_tarde is not null,timediff(saida_tarde,entrada_tarde),'')TARDE
, if(entrada_manha is not null
     and saida_almoco is not null
     and entrada_tarde is not null
     and saida_tarde is not null,addtime(timediff(saida_almoco,entrada_manha),timediff(saida_tarde,entrada_tarde)),'')TOTAL_DIA
FROM sgc_cartao_ponto
WHERE id_usuario =$idusuario
AND date_format(entrada_manha,'%Y-%m-%d') =  date_format(sysdate(),'%Y-%m-%d')
OR date_format(saida_almoco,'%Y-%m-%d') =  date_format(sysdate(),'%Y-%m-%d')
OR date_format(entrada_tarde,'%Y-%m-%d') =  date_format(sysdate(),'%Y-%m-%d')
or date_format(saida_tarde,'%Y-%m-%d') =  date_format(sysdate(),'%Y-%m-%d')


")or print(mysql_error());
  while($dados=mysql_fetch_array($checa)){
  $manha   = $dados['MANHA'];
  $tarde = $dados['TARDE'];
  $total = $dados['TOTAL_DIA'];
 }
?>

<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Tempo Decorrido Hoje :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">

					<table border="0" width="40%" cellspacing="0" cellpadding="0">
						<tr>
							<td>
							<p align="center">Manhã</td>
							<td width="50%">
							<p align="center">Tarde</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td width="50%">&nbsp;</td>
						</tr>
						<tr>
							<td>
							<p align="center"><?echo $manha?></td>
							<td width="50%">
							<p align="center"><?echo $tarde?></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td width="50%">&nbsp;</td>
						</tr>
						<tr>
							<td>
							<p align="right">Total:&nbsp;&nbsp; </td>
							<td width="50%">
                            <?
                            if($total!=null){
                             echo $total;
                            }else{
                              if($manha==null){
                                echo "$tarde";
                              }else{
                                echo $manha;
                              }
                             }
                            ?></td>
						</tr>
					</table>

					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>
<BR>

<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Semana :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">

					<table border="1" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse" bordercolor="#C0C0C0">
						<tr>
							<td width="15%">&nbsp;</td>
							<td style="width: 17%">Entrada</td>
							<td style="width: 17%">Saída</td>
							<td style="width: 17%">Entrada</td>
							<td style="width: 17%">Saída</td>
							<td width="15%">Total</td>
						</tr>
						<?
						$checa = mysql_query("
                        SELECT
                         date_format(entrada_manha,'%d/%m/%Y %H:%i')CHEGADA
                         ,date_format(saida_almoco,'%d/%m/%Y %H:%i')SAIDA_ALMOCO
                         ,date_format(entrada_tarde,'%d/%m/%Y %H:%i')CHEGADA_TARDE
                         ,date_format(saida_tarde,'%d/%m/%Y %H:%i')SAIDA_TARDE
                         ,date_format(data_gravacao,'%W')DIA_SEMANA
                         ,if(entrada_manha is not null
                              and saida_almoco is not null
                                   and entrada_tarde is not null
                                        and saida_tarde is not null,addtime(timediff(saida_almoco,entrada_manha),timediff(saida_tarde,entrada_tarde)),'')TOTAL_DIA
                                        FROM sgc_cartao_ponto
                                        WHERE id_usuario =$idusuario
                                        AND WEEKOFYEAR(data_gravacao) =  WEEKOFYEAR(sysdate())
                                        AND date_format(data_gravacao,'%W') = 'Monday'
                        ") or print(mysql_error());
                        while($dados=mysql_fetch_array($checa)){
                        $s_em = $dados['CHEGADA'];
                        $s_sm = $dados['SAIDA_ALMOCO'];
                        $s_et = $dados['CHEGADA_TARDE'];
                        $s_st = $dados['SAIDA_TARDE'];
                        $s_total = $dados['TOTAL_DIA'];
                        }

						?>
						<tr>
							<td width="15%">Segunda</td>
							<td style="width: 17%"><font size="2"><?echo $s_em?> </font>
							</td>
							<td style="width: 17%"><font size="2"><?echo $s_sm?> </font>
							</td>
							<td style="width: 17%"><font size="2"><?echo $s_et?></font></td>
							<td style="width: 17%"><font size="2"><?echo $s_st ?></font>
							</td>
							<td width="15%"><font size="2"><?echo $s_total ?></font></td>
						</tr>
                       <?
                       $checa = mysql_query("
                        SELECT
                         date_format(entrada_manha,'%d/%m/%Y %H:%i')CHEGADA
                         ,date_format(saida_almoco,'%d/%m/%Y %H:%i')SAIDA_ALMOCO
                         ,date_format(entrada_tarde,'%d/%m/%Y %H:%i')CHEGADA_TARDE
                         ,date_format(saida_tarde,'%d/%m/%Y %H:%i')SAIDA_TARDE
                         ,date_format(data_gravacao,'%W')DIA_SEMANA
                         ,if(entrada_manha is not null
                              and saida_almoco is not null
                                   and entrada_tarde is not null
                                        and saida_tarde is not null,addtime(timediff(saida_almoco,entrada_manha),timediff(saida_tarde,entrada_tarde)),'')TOTAL_DIA
                                        FROM sgc_cartao_ponto
                                        WHERE id_usuario =$idusuario
                                        AND WEEKOFYEAR(data_gravacao) =  WEEKOFYEAR(sysdate())
                                        AND date_format(data_gravacao,'%W') = 'Tuesday'
                        ") or print(mysql_error());
                        while($dados=mysql_fetch_array($checa)){
                        $t_em = $dados['CHEGADA'];
                        $t_sm = $dados['SAIDA_ALMOCO'];
                        $t_et = $dados['CHEGADA_TARDE'];
                        $t_st = $dados['SAIDA_TARDE'];
                        $t_total = $dados['TOTAL_DIA'];
                        }
                        ?>
						<tr>
							<td width="15%">Terça</td>
							<td style="width: 17%"><font size="2"><?echo $t_em ?></font>
							</td>
							<td style="width: 17%"><font size="2"><?echo $t_sm ?></font>
							</td>
							<td style="width: 17%"><font size="2"><?echo $t_et ?></font></td>
							<td style="width: 17%"><font size="2"><?echo $t_st ?> </font>
							</td>
							<td width="15%"><font size="2"><?echo $t_total ?></font></td>
						</tr>
						<tr>
							<td width="15%">Quarta</td>
							<td style="width: 17%"><font size="2">$q_em </font>
							</td>
							<td style="width: 17%"><font size="2">$q_sm </font>
							</td>
							<td style="width: 17%"><font size="2">$q_et</font></td>
							<td style="width: 17%"><font size="2">$q_st </font>
							</td>
							<td width="15%"><font size="2">$q_total </font></td>
						</tr>
						<tr>
							<td width="15%">Quinta</td>
							<td style="width: 17%"><font size="2">$qi_em </font>
							</td>
							<td style="width: 17%"><font size="2">$qi_sm </font>
							</td>
							<td style="width: 17%"><font size="2">$qi_et</font></td>
							<td style="width: 17%"><font size="2">$qi_st </font>
							</td>
							<td width="15%"><font size="2">$qi_total </font>
							</td>
						</tr>
						<tr>
							<td width="15%">Sexta</td>
							<td style="width: 17%"><font size="2">$sx_em </font>
							</td>
							<td style="width: 17%"><font size="2">$sx_sm </font>
							</td>
							<td style="width: 17%"><font size="2">$sx_et</font></td>
							<td style="width: 17%"><font size="2">$sx_st </font>
							</td>
							<td width="15%"><font size="2">$sx_total </font>
							</td>
						</tr>
						<tr>
							<td width="15%">Sábado</td>
							<td style="width: 17%"><font size="2">$sa_em </font>
							</td>
							<td style="width: 17%"><font size="2">$sa_sm </font>
							</td>
							<td style="width: 17%"><font size="2">$sa_et</font></td>
							<td style="width: 17%"><font size="2">$sa_st </font>
							</td>
							<td width="15%"><font size="2">$sa_total </font>
							</td>
						</tr>
						<tr>
							<td width="15%">Domingo</td>
							<td style="width: 17%"><font size="2">$d_em </font>
							</td>
							<td style="width: 17%"><font size="2">$d_sm </font>
							</td>
							<td style="width: 17%"><font size="2">$d_et</font></td>
							<td style="width: 17%"><font size="2">$d_st </font>
							</td>
							<td width="15%"><font size="2">$d_total </font></td>
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
}elseif($acao_int=="registro"){
$periodo=$_GET['periodo'];

if($periodo==1){
  $periodo="entrada_manha";
  $periodo_ip="ip_entrada_manha";
}elseif($periodo==2){
  $periodo="saida_almoco";
  $periodo_ip="ip_saida_almoco";
}elseif($periodo==3){
  $periodo="entrada_tarde";
  $periodo_ip="ip_entrada_tarde";
}elseif($periodo==4){
  $periodo="saida_tarde";
  $periodo_ip="ip_saida_tarde";
}
  if($idusuario!=null){
  
        $checa = mysql_query("SELECT id_ponto FROM sgc_cartao_ponto
                              WHERE id_usuario = $idusuario
                              AND date_format(entrada_manha,'%Y-%m-%d') =  date_format(sysdate(),'%Y-%m-%d')
                              OR date_format(saida_almoco,'%Y-%m-%d') =  date_format(sysdate(),'%Y-%m-%d')
                              OR date_format(entrada_tarde,'%Y-%m-%d') =  date_format(sysdate(),'%Y-%m-%d')
                              OR date_format(saida_tarde,'%Y-%m-%d') =  date_format(sysdate(),'%Y-%m-%d')

                              ")or print(mysql_error());
        while($dados=mysql_fetch_array($checa)){
        $id_ponto   = $dados['id_ponto'];
        }

        if($id_ponto!=null){

         $cadas = mysql_query("UPDATE sgc_cartao_ponto
                               SET
                                    $periodo=sysdate()
                                   ,$periodo_ip='$iplogon'
                               WHERE id_usuario = $idusuario
                               AND id_ponto = $id_ponto
                                   ") or print(mysql_error());
        }else{
        
        $cadas = mysql_query("INSERT INTO sgc_cartao_ponto (id_usuario,$periodo,$periodo_ip,data_gravacao)
                          VALUES ($idusuario,sysdate(),'$iplogon',sysdate())") or print(mysql_error());

        }
      

                          
  }
 }
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
