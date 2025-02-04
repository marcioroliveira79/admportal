<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Cadastro Datas para Agendamento";
$titulo_listar="Datas Já Cadastrados";
$arquivo="sgc_datas_agenda.php";
$tabela="sgc_datas_agendamento";
$id_item=$_GET['id_item'];

if(!isset($acao_int)){




    include("conf/Pagina.class.php");

    $sql= mysql_query("SELECT count(id_data_agendamento) t FROM sgc_datas_agendamento");
    $dados=mysql_fetch_array($sql);
    $total=$dados['t'];

    $pagina = new Pagina();
    $pagina->setLimite(10);

 	$totalRegistros = $total;
	$linkPaginacao ="?action=$arquivo&id_item=$id_item";


?>
<script type="text/javascript">
function bloqueia_campos(){
    if(document.forms['meuFormulario'].data.value!=""){
        document.getElementById('hora_segunda').disabled = true;
        document.getElementById('hora_terca').disabled = true;
        document.getElementById('hora_quarta').disabled = true;
        document.getElementById('hora_quinta').disabled = true;
        document.getElementById('hora_sexta').disabled = true;
        document.getElementById('hora_sabado').disabled = true;
        document.getElementById('hora_domingo').disabled = true;
        document.getElementById('hora_segunda_f').disabled = true;
        document.getElementById('hora_terca_f').disabled = true;
        document.getElementById('hora_quarta_f').disabled = true;
        document.getElementById('hora_quinta_f').disabled = true;
        document.getElementById('hora_sexta_f').disabled = true;
        document.getElementById('hora_sabado_f').disabled = true;
        document.getElementById('hora_domingo_f').disabled = true;
    }else{
        document.getElementById('hora_segunda').disabled = false;
        document.getElementById('hora_terca').disabled = false;
        document.getElementById('hora_quarta').disabled = false;
        document.getElementById('hora_quinta').disabled = false;
        document.getElementById('hora_sexta').disabled = false;
        document.getElementById('hora_sabado').disabled = false;
        document.getElementById('hora_domingo').disabled = false;
        document.getElementById('hora_segunda_f').disabled = false;
        document.getElementById('hora_terca_f').disabled = false;
        document.getElementById('hora_quarta_f').disabled = false;
        document.getElementById('hora_quinta_f').disabled = false;
        document.getElementById('hora_sexta_f').disabled = false;
        document.getElementById('hora_sabado_f').disabled = false;
        document.getElementById('hora_domingo_f').disabled = false;
    }

    if(
       document.forms['meuFormulario'].hora_segunda.value!="" ||
       document.forms['meuFormulario'].hora_terca.value!="" ||
       document.forms['meuFormulario'].hora_quarta.value!="" ||
       document.forms['meuFormulario'].hora_quinta.value!="" ||
       document.forms['meuFormulario'].hora_sexta.value!="" ||
       document.forms['meuFormulario'].hora_sabado.value!="" ||
       document.forms['meuFormulario'].hora_domingo.value!=""  ||
       document.forms['meuFormulario'].hora_segunda_f.value!="" ||
       document.forms['meuFormulario'].hora_terca_f.value!="" ||
       document.forms['meuFormulario'].hora_quarta_f.value!="" ||
       document.forms['meuFormulario'].hora_quinta_f.value!="" ||
       document.forms['meuFormulario'].hora_sexta_f.value!="" ||
       document.forms['meuFormulario'].hora_sabado_f.value!="" ||
       document.forms['meuFormulario'].hora_domingo_f.value!="" ){
        
        document.getElementById('data').disabled = true;
        document.getElementById('hora_inicio').disabled = true;
       document.getElementById('hora_hora_f').disabled = true;
        
    }else{
        document.getElementById('data').disabled = false;
        document.getElementById('hora_inicio').disabled = false;
        document.getElementById('hora_hora_f').disabled = false;
    }




}
</script>




<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_objeto.value=="")
    {
        alert ("\nDigite a descricao para o Centro de Custo.");

        document.form1.desc_objeto.style.borderColor="#FF0000";
        document.form1.desc_objeto.style.borderWidth="1px solid";

        nomeform.desc_objeto.focus();
        return false;
    }
     if (nomeform.codigo.value=="")
    {
        alert ("\nDigite o código para o Centro de Custo.");

        document.form1.codigo.style.borderColor="#FF0000";
        document.form1.codigo.style.borderWidth="1px solid";

        nomeform.codigo.focus();
        return false;
    }
    if (nomeform.ajuda.value=="")
    {
        alert ("\nDigite a ajuda deste Centro de Custo ");

        document.form1.ajuda.style.borderColor="#FF0000";
        document.form1.ajuda.style.borderWidth="1px solid";

        nomeform.ajuda.focus();
        return false;
    }


return true;
}
</script>

<script language='javascript'>
function confirmaExclusao(aURL) {
if(confirm('Você esta prestes a apagar este registro,deseja continuar?')) {
location.href = aURL;
}
}
</script>


<script language='javascript'>
function mascaraTexto(evento, mascara){

   var campo, valor, i, tam, caracter;

   if (document.all) // Internet Explorer
      campo = evento.srcElement;
   else // Nestcape, Mozzila
       campo= evento.target;

   valor = campo.value;
   tam = valor.length;

   for(i=0;i<mascara.length;i++){
      caracter = mascara.charAt(i);
      if(caracter!="9")
         if(i<tam & caracter!=valor.charAt(i))
            campo.value = valor.substring(0,i) + caracter + valor.substring(i,tam);
   }




}
</script>


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

<form method="POST" id="form1" name='meuFormulario'  action="sgc.php?action=<?echo $arquivo?>&acao_int=cad_objeto" onsubmit="return bloquear_campos()">
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
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="3" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
						</tr>
						<tr>
							<td width="308">
							<p align="right">Descrição Data:&nbsp; </td>
							<td width="711" height="23" colspan="2">
							<input size="68" name="desc_objeto"  style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="308">
							<p align="right">Data Especifica:&nbsp; </td>
							<td width="711" height="23" colspan="2">
							<input size="10" id="data" name="data" onKeyUp="mascaraTexto(event,'99/99/9999')"   onchange="bloqueia_campos(this.value)" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="10">
                            Hora Inicio:
							<input size="8" id="hora_inicio" name="data_hora_inicio" onKeyUp="mascaraTexto(event,'99:99:99')"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8">&nbsp;
							Hora Termino:
							<input size="8" id="hora_hora_f" name="data_hora_f" onKeyUp="mascaraTexto(event,'99:99:99')"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
						</tr>
							<tr>
							<td width="308">
							&nbsp;</td>
							<td width="711" height="23" colspan="2">
							<table border="1" width="68%" cellspacing="0">
								<tr>
									<td width="73" align="center">Segunda</td>
									<td align="center" width="50">Terça</td>
									<td align="center" width="57">Quarta</td>
									<td align="center" width="67">Quinta</td>
									<td align="center" width="66">Sexta</td>
									<td align="center">Sábado</td>
									<td align="center" width="68">Domingo</td>
								</tr>
								<tr>
									<td width="73" align="center">
									<p align="center">
							<input size="8" id="hora_segunda" name="hora_segunda" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)"   style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="50">
							<input size="8" id="hora_terca" name="hora_terca" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="57">
							<input size="8" id="hora_quarta" name="hora_quarta" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="67">
							<input size="8" id="hora_quinta" name="hora_quinta" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)"   style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="66">
							<input size="8" id="hora_sexta" name="hora_sexta" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center">
							<input size="8" id="hora_sabado" name="hora_sabado" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)"   style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="68">
							<input size="8" id="hora_domingo" name="hora_domingo" onKeyUp="mascaraTexto(event,'99:99:99')"  onchange="bloqueia_campos(this.value)"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
								</tr>
								<tr>
									<td width="73" align="center">
							<input size="8" id="hora_segunda_f" name="hora_segunda_f" onKeyUp="mascaraTexto(event,'99:99:99')"  onchange="bloqueia_campos(this.value)"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="50">
							<input size="8" id="hora_terca_f" name="hora_terca_f" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)"   style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="57">
							<input size="8" id="hora_quarta_f" name="hora_quarta_f" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)"   style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="67">
							<input size="8" id="hora_quinta_f" name="hora_quinta_f" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="66">
							<input size="8" id="hora_sexta_f" name="hora_sexta_f" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center">
							<input size="8" id="hora_sabado_f" name="hora_sabado_f" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)"   style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="68">
							<input size="8" id="hora_domingo_f" name="hora_domingo_f" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
								</tr>
							</table>
							</td>
						</tr>
                     	<tr>
							<td width="308">
							<p align="right">Executar em:&nbsp;&nbsp; </td>
							<td width="431" height="23">
							<input size="5" onKeyUp="mascaraTexto(event,'99:99')" name="execucao_minutos"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="5">
							minutos *caso não preenchido o robo controlará</td>
							<td width="280" height="23">
							&nbsp;</td>
						</tr>
						<tr>
							<td width="308">
							<p align="right">Número de execuções:&nbsp;&nbsp; </td>
							<td width="431" height="23">
							<input size="2" onKeyUp="mascaraTexto(event,'99')" name="qtde_execs"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="2">Número de execuções no periodo</td>
							<td width="280" height="23">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" width="739">
							&nbsp;</td>
							<td width="280">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" width="739">
							<p align="center">
							<input type="submit" value="Adicionar" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
							<td width="280">
							&nbsp;</td>
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
<p>&nbsp;</p>


<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: <?echo $titulo_listar?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="488" cellspacing="0" cellpadding="0">
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="369" height="23"><b>Descrição</b></td>
							<td width="38" height="23">&nbsp;</td>
							<td width="44" height="23">&nbsp;</td>
							<td width="18" height="23">&nbsp;</td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
                        <?

                          $checa = mysql_query("SELECT * FROM sgc_datas_agendamento order by id_data_agendamento asc limit ".$pagina->getPagina($_GET['pagina']).", ".$pagina->getLimite());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_objeto = $dados['id_data_agendamento'];
                                    $ler_descricao_objeto = $dados['descricao_data'];

                        ?>

                        <tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="369" height="23"><?echo $ler_descricao_objeto?></td>
							<td width="38" height="23">
							<p align="center"><a href="?action=<?echo $arquivo?>&acao_int=editar&id_objeto=<?echo $id_objeto?>&id_item=<?echo $id_item?>"">
							<font color="#000000">Editar</font></a></td>
							<td width="44" height="23">
							<p align="center">
                            <a href="javascript:confirmaExclusao('?action=<?echo $arquivo?>&acao_int=excluir&id_objeto=<?echo $id_objeto?>&id_item=<?echo $id_item?>')">
                            <font color="#000000"><?echo $acao?>Excluir</font></a></td>
							<td width="18" height="23">
							<p align="center"><a href="#" class="dcontexto">
							<font color="#000000">?</font>
                            <span><strong><?echo $ler_descricao_objeto?></strong>
                            </strong><p class="formata"></a>
                            </td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
                        <?
                        }
                        ?>

					</table></td>
				</tr>
			</table></td>
		</tr>
	</table>
</div>
<br>
<p align="center">


<?
//----------------Paginador-------------------//

Pagina::configuraPaginacao($_GET['cj'],$_GET['pagina'],$totalRegistros,$linkPaginacao, $pagina->getLimite(), $_GET['direcao']);

//--------------------------------------------//
}elseif($acao_int=="editar_bd"){





       $id_item=$_POST['id_item'];
       $id_objeto=$_POST['id_objeto'];

       $permissao_item=acesso($idusuario,$id_item);


       if($permissao_item=="OK"){

       $desc_objeto=$_POST['desc_objeto'];
       $desc_objeto=ltrim("$desc_objeto");
       $data=$_POST['data'];
       $data_hora_inicio=$_POST['data_hora_inicio'];
       $data_hora_f=$_POST['data_hora_f'];
       $hora_segunda=$_POST['hora_segunda'];
       $hora_terca=$_POST['hora_terca'];
       $hora_quarta=$_POST['hora_quarta'];
       $hora_quinta=$_POST['hora_quinta'];
       $hora_sexta=$_POST['hora_sexta'];
       $hora_sabado=$_POST['hora_sabado'];
       $hora_domingo=$_POST['hora_domingo'];
       $execucao_minutos=$_POST['execucao_minutos'];
       $hora_segunda_f=$_POST['hora_segunda_f'];
       $hora_terca_f=$_POST['hora_terca_f'];
       $hora_quarta_f=$_POST['hora_quarta_f'];
       $hora_quinta_f=$_POST['hora_quinta_f'];
       $hora_sexta_f=$_POST['hora_sexta_f'];
       $hora_sabado_f=$_POST['hora_sabado_f'];
       $hora_domingo_f=$_POST['hora_domingo_f'];
       $execucao_minutos=$_POST['execucao_minutos'];
        $qtde_execs=$_POST['qtde_execs'];

      $data_format=databd($data);
      $cadas = mysql_query("UPDATE $tabela SET
              descricao_data = '$desc_objeto'
            ,data_especifica = '$data_format'
                ,data_inicio = '$data_hora_inicio'
                   ,data_fim = '$data_hora_f'
     ,tempo_execucao_minutos = '$execucao_minutos'
             ,segunda_inicio = '$hora_segunda'
               ,terca_inicio = '$hora_terca'
              ,quarta_inicio = '$hora_quarta'
              ,quinta_inicio = '$hora_quinta'
               ,sexta_inicio = '$hora_sexta'
              ,sabado_inicio = '$hora_sabado'
             ,domingo_inicio = '$hora_domingo'
                ,segunda_fim = '$hora_segunda_f'
                  ,terca_fim = '$hora_terca_f'
                 ,quarta_fim = '$hora_quarta_f'
                 ,quinta_fim = '$hora_quinta_f'
                  ,sexta_fim = '$hora_sexta_f'
                 ,sabado_fim = '$hora_sabado_f'
                ,domingo_fim = '$hora_domingo_f'
             ,quando_alterou = sysdate()
               ,quem_alterou = $idusuario
               ,n_execucoes =  '$qtde_execs'
               WHERE id_data_agendamento =$id_objeto

      ") or print(mysql_error());

      session_unregister('desc_objeto');
      session_unregister('data_especifica');
      session_unregister('data_inicio');
      session_unregister('data_fim');
      session_unregister('tempo_execucao_minutos');

      session_unregister('segunda_inicio');
      session_unregister('terca_inicio');
      session_unregister('quarta_inicio');
      session_unregister('quinta_inicio');
      session_unregister('sexta_inicio');
      session_unregister('sabado_inicio');
      session_unregister('domingo_inicio');
      
      session_unregister('segunda_fim');
      session_unregister('terca_fim');
      session_unregister('quarta_fim');
      session_unregister('quinta_fim');
      session_unregister('sexta_fim');
      session_unregister('sabado_fim');
      session_unregister('domingo_fim');
      session_unregister('qtde_execs');


     header("Location: ?action=$arquivo&id_item=$id_item");

 }

}elseif($acao_int=="editar"){
$id_objeto=$_GET['id_objeto'];
$id_item=$_GET['id_item'];

$checa = mysql_query("select * from $tabela where id_data_agendamento=$id_objeto ") or print(mysql_error());
                                while($dados=mysql_fetch_array($checa)){
                                $ler_descricao_objeto = $dados['descricao_data'];
                                     $data_especifica = $dados['data_especifica'];
                                         $data_inicio = $dados['data_inicio'];
                                            $data_fim = $dados['data_fim'];
                                    $execucao_minutos = $dados['tempo_execucao_minutos'];

                                      $segunda_inicio = $dados['segunda_inicio'];
                                        $terca_inicio = $dados['terca_inicio'];
                                       $quarta_inicio = $dados['quarta_inicio'];
                                       $quinta_inicio = $dados['quinta_inicio'];
                                        $sexta_inicio = $dados['sexta_inicio'];
                                       $sabado_inicio = $dados['sabado_inicio'];
                                      $domingo_inicio = $dados['domingo_inicio'];
                                         $segunda_fim = $dados['segunda_fim'];
                                           $terca_fim = $dados['terca_fim'];
                                          $quarta_fim = $dados['quarta_fim'];
                                          $quinta_fim = $dados['quinta_fim'];
                                           $sexta_fim = $dados['sexta_fim'];
                                          $sabado_fim = $dados['sabado_fim'];
                                         $domingo_fim = $dados['domingo_fim'];
                                          $qtde_execs = $dados['n_execucoes'];

                                     if($data_especifica=="0000-00-00"){
                                        $data_especifica=null;
                                     }

                                     if($data_inicio=="00:00:00"){
                                        $data_inicio=null;
                                     }

                                     if($data_fim=="00:00:00"){
                                        $data_fim=null;
                                     }
                                     
                                     if($segunda_inicio=="00:00:00"){
                                        $segunda_inicio=null;
                                     }
                                     if($terca_inicio=="00:00:00"){
                                        $terca_inicio=null;
                                     }
                                     if($quarta_inicio=="00:00:00"){
                                        $quarta_inicio=null;
                                     }
                                     if($quinta_inicio=="00:00:00"){
                                        $quinta_inicio=null;
                                     }
                                     if($sexta_inicio=="00:00:00"){
                                        $sexta_inicio=null;
                                     }
                                     if($sabado_inicio=="00:00:00"){
                                        $sabado_inicio=null;
                                     }
                                     if($domingo_inicio=="00:00:00"){
                                        $domingo_inicio=null;
                                     }


                                    if($segunda_fim=="00:00:00"){
                                        $segunda_fim=null;
                                     }
                                     if($terca_fim=="00:00:00"){
                                        $terca_fim=null;
                                     }
                                     if($quarta_fim=="00:00:00"){
                                        $quarta_fim=null;
                                     }
                                     if($quinta_fim=="00:00:00"){
                                        $quinta_fim=null;
                                     }
                                     if($sexta_fim=="00:00:00"){
                                        $sexta_fim=null;
                                     }
                                     if($sabado_fim=="00:00:00"){
                                        $sabado_fim=null;
                                     }
                                     if($domingo_fim=="00:00:00"){
                                        $domingo_fim=null;
                                     }
                                     if($execucao_minutos=="0"){
                                        $execucao_minutos=null;
                                     }





                        $data_especifica=invertedata($data_especifica);

                        if(!isset($_SESSION['desc_objeto'])){

                                   $desc_objeto=$ler_descricao_objeto;
                                          $data=$data_especifica;
                              $data_hora_inicio=$data_inicio;
                                   $data_hora_f=$data_fim;
                              $execucao_minutos=$execucao_minutos;
                                  $hora_segunda=$segunda_inicio;
                                    $hora_terca=$terca_inicio;
                                   $hora_quarta=$quarta_inicio;
                                   $hora_quinta=$quinta_inicio;
                                    $hora_sexta=$sexta_inicio;
                                   $hora_sabado=$sabado_inicio;
                                  $hora_domingo=$domingo_inicio;
                                  $hora_segunda=$segunda_inicio;
                                $hora_segunda_f=$segunda_fim;
                                  $hora_terca_f=$terca_fim;
                                 $hora_quarta_f=$quarta_fim;
                                 $hora_quinta_f=$quinta_fim;
                                  $hora_sexta_f=$sexta_fim;
                                 $hora_sabado_f=$sabado_fim;
                                $hora_domingo_f=$domingo_fim;
                                   $qtde_execs = $qtde_execs;





                            }else{
                                   $desc_objeto=$_SESSION['desc_objeto'];
                                          $data=$_SESSION['data_especifica'];
                              $data_hora_inicio=$_SESSION['data_inicio'];
                                   $data_hora_f=$_SESSION['data_fim'];
                              $execucao_minutos=$_SESSION['execucao_minutos'];
                                  $hora_segunda=$_SESSION['segunda_inicio'];
                                    $hora_terca=$_SESSION['terca_inicio'];
                                   $hora_quarta=$_SESSION['quarta_inicio'];
                                   $hora_quinta=$_SESSION['quinta_inicio'];
                                    $hora_sexta=$_SESSION['sexta_inicio'];
                                   $hora_sabado=$_SESSION['sabado_inicio'];
                                  $hora_domingo=$_SESSION['domingo_inicio'];
                                  $hora_segunda=$_SESSION['domingo_inicio'];
                                $hora_segunda_f=$_SESSION['segunda_fim'];
                                  $hora_terca_f=$_SESSION['terca_fim'];
                                 $hora_quarta_f=$_SESSION['quarta_fim'];
                                 $hora_quinta_f=$_SESSION['quinta_fim'];
                                  $hora_sexta_f=$_SESSION['sexta_fim'];
                                 $hora_sabado_f=$_SESSION['sabado_fim'];
                                $hora_domingo_f=$_SESSION['domingo_fim'];
                                    $qtde_execs=$_SESSION['qtde_execs'];


                            }




?>
<script type="text/javascript">
function bloqueia_campos(){
    if(document.forms['meuFormulario'].data.value!=""){
        document.getElementById('hora_segunda').disabled = true;
        document.getElementById('hora_terca').disabled = true;
        document.getElementById('hora_quarta').disabled = true;
        document.getElementById('hora_quinta').disabled = true;
        document.getElementById('hora_sexta').disabled = true;
        document.getElementById('hora_sabado').disabled = true;
        document.getElementById('hora_domingo').disabled = true;
        document.getElementById('hora_segunda_f').disabled = true;
        document.getElementById('hora_terca_f').disabled = true;
        document.getElementById('hora_quarta_f').disabled = true;
        document.getElementById('hora_quinta_f').disabled = true;
        document.getElementById('hora_sexta_f').disabled = true;
        document.getElementById('hora_sabado_f').disabled = true;
        document.getElementById('hora_domingo_f').disabled = true;
    }else{
        document.getElementById('hora_segunda').disabled = false;
        document.getElementById('hora_terca').disabled = false;
        document.getElementById('hora_quarta').disabled = false;
        document.getElementById('hora_quinta').disabled = false;
        document.getElementById('hora_sexta').disabled = false;
        document.getElementById('hora_sabado').disabled = false;
        document.getElementById('hora_domingo').disabled = false;
        document.getElementById('hora_segunda_f').disabled = false;
        document.getElementById('hora_terca_f').disabled = false;
        document.getElementById('hora_quarta_f').disabled = false;
        document.getElementById('hora_quinta_f').disabled = false;
        document.getElementById('hora_sexta_f').disabled = false;
        document.getElementById('hora_sabado_f').disabled = false;
        document.getElementById('hora_domingo_f').disabled = false;
    }

    if(
       document.forms['meuFormulario'].hora_segunda.value!="" ||
       document.forms['meuFormulario'].hora_terca.value!="" ||
       document.forms['meuFormulario'].hora_quarta.value!="" ||
       document.forms['meuFormulario'].hora_quinta.value!="" ||
       document.forms['meuFormulario'].hora_sexta.value!="" ||
       document.forms['meuFormulario'].hora_sabado.value!="" ||
       document.forms['meuFormulario'].hora_domingo.value!=""  ||
       document.forms['meuFormulario'].hora_segunda_f.value!="" ||
       document.forms['meuFormulario'].hora_terca_f.value!="" ||
       document.forms['meuFormulario'].hora_quarta_f.value!="" ||
       document.forms['meuFormulario'].hora_quinta_f.value!="" ||
       document.forms['meuFormulario'].hora_sexta_f.value!="" ||
       document.forms['meuFormulario'].hora_sabado_f.value!="" ||
       document.forms['meuFormulario'].hora_domingo_f.value!="" ){

        document.getElementById('data').disabled = true;
        document.getElementById('hora_inicio').disabled = true;
       document.getElementById('hora_hora_f').disabled = true;

    }else{
        document.getElementById('data').disabled = false;
        document.getElementById('hora_inicio').disabled = false;
        document.getElementById('hora_hora_f').disabled = false;
    }




}
</script>




<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.desc_objeto.value=="")
    {
        alert ("\nDigite a descricao para o Centro de Custo.");

        document.form1.desc_objeto.style.borderColor="#FF0000";
        document.form1.desc_objeto.style.borderWidth="1px solid";

        nomeform.desc_objeto.focus();
        return false;
    }
     if (nomeform.codigo.value=="")
    {
        alert ("\nDigite o código para o Centro de Custo.");

        document.form1.codigo.style.borderColor="#FF0000";
        document.form1.codigo.style.borderWidth="1px solid";

        nomeform.codigo.focus();
        return false;
    }
    if (nomeform.ajuda.value=="")
    {
        alert ("\nDigite a ajuda deste Centro de Custo ");

        document.form1.ajuda.style.borderColor="#FF0000";
        document.form1.ajuda.style.borderWidth="1px solid";

        nomeform.ajuda.focus();
        return false;
    }


return true;
}
</script>

<script language='javascript'>
function confirmaExclusao(aURL) {
if(confirm('Você esta prestes a apagar este registro,deseja continuar?')) {
location.href = aURL;
}
}
</script>


<script language='javascript'>
function mascaraTexto(evento, mascara){

   var campo, valor, i, tam, caracter;

   if (document.all) // Internet Explorer
      campo = evento.srcElement;
   else // Nestcape, Mozzila
       campo= evento.target;

   valor = campo.value;
   tam = valor.length;

   for(i=0;i<mascara.length;i++){
      caracter = mascara.charAt(i);
      if(caracter!="9")
         if(i<tam & caracter!=valor.charAt(i))
            campo.value = valor.substring(0,i) + caracter + valor.substring(i,tam);
   }




}
</script>


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

<form method="POST" id="form1" name='meuFormulario'  action="sgc.php?action=<?echo $arquivo?>&acao_int=editar_bd" onsubmit="return bloquear_campos()">
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
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="3" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
                            <input type='hidden' name='id_objeto' value='<?echo $id_objeto?>'>
                            </td>
						</tr>
						<tr>
							<td width="308">
							<p align="right">Descrição Data:&nbsp; </td>
							<td width="711" height="23" colspan="2">
							<input size="68" name="desc_objeto" value="<?echo $desc_objeto?>" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"></td>
						</tr>
							<tr>
							<td width="308">
							<p align="right">Data Especifica:&nbsp; </td>
							<td width="711" height="23" colspan="2">
							<input size="10" id="data" name="data" onKeyUp="mascaraTexto(event,'99/99/9999')" value="<?echo $data?>"  onchange="bloqueia_campos(this.value)" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="10">
                            Hora Inicio:
							<input size="8" id="hora_inicio" name="data_hora_inicio" onKeyUp="mascaraTexto(event,'99:99:99')" value="<?echo $data_hora_inicio?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8">&nbsp;
							Hora Termino:
							<input size="8" id="hora_hora_f" name="data_hora_f" onKeyUp="mascaraTexto(event,'99:99:99')" value="<?echo $data_hora_f?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
						</tr>
							<tr>
							<td width="308">
							&nbsp;</td>
							<td width="711" height="23" colspan="2">
							<table border="1" width="68%" cellspacing="0">
								<tr>
									<td width="73" align="center">Segunda</td>
									<td align="center" width="50">Terça</td>
									<td align="center" width="57">Quarta</td>
									<td align="center" width="67">Quinta</td>
									<td align="center" width="66">Sexta</td>
									<td align="center">Sábado</td>
									<td align="center" width="68">Domingo</td>
								</tr>
								<tr>
									<td width="73" align="center">
									<p align="center">
							<input size="8" id="hora_segunda" name="hora_segunda" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)" value="<?echo $hora_segunda ?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="50">
							<input size="8" id="hora_terca" name="hora_terca" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)" value="<?echo $hora_terca ?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="57">
							<input size="8" id="hora_quarta" name="hora_quarta" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)" value="<?echo $hora_quarta ?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="67">
							<input size="8" id="hora_quinta" name="hora_quinta" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)" value="<?echo $hora_quinta ?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="66">
							<input size="8" id="hora_sexta" name="hora_sexta" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)" value="<?echo $hora_sexta ?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center">
							<input size="8" id="hora_sabado" name="hora_sabado" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)" value="<?echo $hora_sabado ?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="68">
							<input size="8" id="hora_domingo" name="hora_domingo" onKeyUp="mascaraTexto(event,'99:99:99')"  onchange="bloqueia_campos(this.value)" value="<?echo $hora_domingo ?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
								</tr>
								<tr>
									<td width="73" align="center">
							<input size="8" id="hora_segunda_f" name="hora_segunda_f" onKeyUp="mascaraTexto(event,'99:99:99')"  onchange="bloqueia_campos(this.value)" value="<?echo $hora_segunda_f ?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="50">
							<input size="8" id="hora_terca_f" name="hora_terca_f" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)" value="<?echo $hora_terca_f ?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="57">
							<input size="8" id="hora_quarta_f" name="hora_quarta_f" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)" value="<?echo $hora_quarta_f ?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="67">
							<input size="8" id="hora_quinta_f" name="hora_quinta_f" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)" value="<?echo $hora_quinta_f ?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="66">
							<input size="8" id="hora_sexta_f" name="hora_sexta_f" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)" value="<?echo $hora_sexta_f ?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center">
							<input size="8" id="hora_sabado_f" name="hora_sabado_f" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)" value="<?echo $hora_sabado_f ?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
									<td align="center" width="68">
							<input size="8" id="hora_domingo_f" name="hora_domingo_f" onKeyUp="mascaraTexto(event,'99:99:99')" onchange="bloqueia_campos(this.value)" value="<?echo $hora_domingo_f ?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="8"></td>
								</tr>
							</table>
							</td>
						</tr>
       	<tr>
							<td width="308">
							<p align="right">Executar em:&nbsp;&nbsp; </td>
							<td width="431" height="23">
							<input size="5" onKeyUp="mascaraTexto(event,'99:99')" name="execucao_minutos" value="<?echo $execucao_minutos?>"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="5">
							minutos *caso não preenchido o robo controlará</td>
							<td width="280" height="23">
							&nbsp;</td>
						</tr>
												<tr>
							<td width="308">
							<p align="right">Número de execuções:&nbsp;&nbsp; </td>
							<td width="431" height="23">
							<input size="2" onKeyUp="mascaraTexto(event,'99')" name="qtde_execs"  value="<?echo $qtde_execs?>"   style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="2">Número de execuções no periodo</td>
							<td width="280" height="23">
							&nbsp;</td>
						</tr>

						<tr>
							<td colspan="2" width="739">
							&nbsp;</td>
							<td width="280">
							&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" width="739">
							<p align="center">
							<input type="submit" value="Alterar" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
							<td width="280">
							&nbsp;</td>
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
<p>&nbsp;</p>
<?

}
}elseif($acao_int=="excluir"){

       $id_item=$_GET['id_item'];
       $permissao_item=acesso($idusuario,$id_item);


       if($permissao_item=="OK"){
            $id_objeto=$_GET['id_objeto'];
            $delete = mysql_query("DELETE from $tabela where id_data_agendamento=$id_objeto") or print(mysql_error());
            header("Location: ?action=$arquivo&id_item=$id_item");
       }

}elseif($acao_int=="cad_objeto"){


       $id_item=$_POST['id_item'];

       $permissao_item=acesso($idusuario,$id_item);


       if($permissao_item=="OK"){

       $desc_objeto=$_POST['desc_objeto'];
       $desc_objeto=ltrim("$desc_objeto");
       session_register('desc_objeto');


       $qtde_execs=$_POST['qtde_execs'];
       session_register('qtde_execs');

       $data=$_POST['data'];
       session_register('data');

       $data_hora_inicio=$_POST['data_hora_inicio'];
       session_register('data_hora_inicio');

       $data_hora_f=$_POST['data_hora_f'];
       session_register('data_hora_f');



       $hora_segunda=$_POST['hora_segunda'];
       session_register('hora_segunda');

       $hora_terca=$_POST['hora_terca'];
       session_register('hora_terca');

       $hora_quarta=$_POST['hora_quarta'];
       session_register('hora_quarta');

       $hora_quinta=$_POST['hora_quinta'];
       session_register('hora_quinta');

       $hora_sexta=$_POST['hora_sexta'];
       session_register('hora_sexta');

       $hora_sabado=$_POST['hora_sabado'];
       session_register('hora_sabado');

       $hora_domingo=$_POST['hora_domingo'];
       session_register('hora_domingo');


       $execucao_minutos=$_POST['execucao_minutos'];
       session_register('execucao_minutos');




       $hora_segunda_f=$_POST['hora_segunda_f'];
       session_register('hora_segunda_f');

       $hora_terca_f=$_POST['hora_terca_f'];
       session_register('hora_terca_f');

       $hora_quarta_f=$_POST['hora_quarta_f'];
       session_register('hora_quarta_f');

       $hora_quinta_f=$_POST['hora_quinta_f'];
       session_register('hora_quinta_f');

       $hora_sexta_f=$_POST['hora_sexta_f'];
       session_register('hora_sexta_f');

       $hora_sabado_f=$_POST['hora_sabado_f'];
       session_register('hora_sabado_f');

       $hora_domingo_f=$_POST['hora_domingo_f'];
       session_register('hora_domingo_f');






       $execucao_minutos=$_POST['execucao_minutos'];
       session_register('execucao_minutos');



      $data_format=databd($data);
      $cadas = mysql_query("INSERT INTO $tabela
      (descricao_data
      ,data_especifica
      ,data_inicio
      ,data_fim
      ,tempo_execucao_minutos
      ,segunda_inicio
      ,terca_inicio
      ,quarta_inicio
      ,quinta_inicio
      ,sexta_inicio
      ,sabado_inicio
      ,domingo_inicio
      ,segunda_fim
      ,terca_fim
      ,quarta_fim
      ,quinta_fim
      ,sexta_fim
      ,sabado_fim
      ,domingo_fim
      ,quando_criou
      ,quem_criou
      ,n_execucoes)

      VALUES (
      '$desc_objeto'
      ,'$data_format'
      ,'$data_hora_inicio'
      ,'$data_hora_f'
      ,'$execucao_minutos'
      ,'$hora_segunda'
      ,'$hora_terca'
      ,'$hora_quarta'
      ,'$hora_quinta'
      ,'$hora_sexta'
      ,'$hora_sabado'
      ,'$hora_domingo'
      ,'$hora_segunda_f'
      ,'$hora_terca_f'
      ,'$hora_quarta_f'
      ,'$hora_quinta_f'
      ,'$hora_sexta_f'
      ,'$hora_sabado_f'
      ,'$hora_domingo_f'
      ,sysdate()
      ,$idusuario
      ,$qtde_execs

      )") or print(mysql_error());




      session_unregister('desc_objeto');
      session_unregister('data');
      session_unregister('data_hora_inicio');
      session_unregister('data_hora_f');
      session_unregister('hora_segunda');
      session_unregister('hora_segunda_f');
      session_unregister('hora_terca');
      session_unregister('hora_terca_f');
      session_unregister('hora_quarta');
      session_unregister('hora_quarta_f');
      session_unregister('hora_quinta');
      session_unregister('hora_quinta_f');
      session_unregister('hora_sexta');
      session_unregister('hora_sexta_f');
      session_unregister('hora_sabado');
      session_unregister('hora_sabado_f');
      session_unregister('hora_domingo');
      session_unregister('hora_domingo_f');
      session_unregister('execucao_minutos');
      session_unregister('qtde_execs');

      
      
      header("Location: ?action=$arquivo&id_item=$id_item");



    }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=$arquivo&id_item=$id_item&msg=$msg");
   }

 }
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
