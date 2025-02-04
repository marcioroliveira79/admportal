<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Relatório Geral - 001";

$id_item=$_GET['id_item'];
$arquivo="rel_001.php";


if(!isset($acao_int)){
?>
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

<script type="text/javascript" src="conf\prototype.js"></script>
<script language="javascript"  src="ajax-area-analista-relatorio.js" type="text/javascript"></script>


<div align="center">
	<form method="POST" id="form1" name='meuFormulario' enctype="multipart/form-data"  action="?action=<?echo $arquivo?>&acao_int=buscar">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: <?echo $titulo?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="80%" cellspacing="0" cellpadding="0">
						<tr>
							<td width="15">&nbsp;</td>
							<td height="23" width="559" colspan="2">
							<p align="center">
												<font color="#FF0000" size="1"><?echo $msg?></font></td>
							<td width="31">&nbsp;</td>
						</tr>
						<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

													Data Inicial:&nbsp; </td>
								<td height="23">
											<font size="1"><input type="text" name="data_inicial" onKeyUp="mascaraTexto(event,'99/99/9999')"  size="10" style="background-color: #FFFFFF" maxlength="60"></td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

													Data Final:&nbsp;</td>
								<td height="23">
											<font size="1"><input type="text" name="data_final" onKeyUp="mascaraTexto(event,'99/99/9999')"  size="10" style="background-color: #FFFFFF" maxlength="60"></td>
								<td width="31">&nbsp;</td>
							</tr>
						<tr>
							<td width="15">&nbsp;</td>
							<td height="23" align="right">

												Área de Atuação Suporte:&nbsp;</td>
							<td height="23">
										<font size="1">
												<select size="1" name="area" Onchange="atualiza(this.value);" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >
                              <option value="Todos">Todos</option>
                            	  <?
                            $checa_menu = mysql_query("SELECT id_area_locacao,descricao FROM sgc_area_locacao order by id_area_locacao") or print mysql_error();
                                    while($dados_menu=mysql_fetch_array($checa_menu)){
                                    $id_area= $dados_menu["id_area_locacao"];
                                    $descricao= $dados_menu["descricao"];

                                ?>
     							<option value="<?echo $id_area?>"><?echo $descricao?></option>
                                <?
                           }
                        ?>
						</select></td>
							<td width="31">&nbsp;</td>
						</tr>
						<tr>
							<td width="15">&nbsp;</td>
							<td height="23" align="right">Analista:&nbsp;</td>
							<td height="23"><font size="1">
                            <div id="atualiza" >
                            </div>
         				</td>
							<td width="31"></td>
		     			</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

												Prioridade:&nbsp;</td>
								<td height="23">
										<font size="1">
												<select size="1" name="prioridade" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" >&nbsp;

                              <option value="Todos">Todos</option>
              <?
                        $checa = mysql_query("SELECT * FROM sgc_sla_analista_usuario order by descricao='$prioridade' desc") or print(mysql_error());
                        while($dados=mysql_fetch_array($checa)){
                        $id_sla_analista = $dados['id_sla_analista'];
                        $descricao  = $dados['descricao'];
                       ?>
                         <option value="<?echo $id_sla_analista?>"><?echo $descricao?></option>
                       <?
                          }
                       ?>
						</select></td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

												Situação Chamado:&nbsp;</td>
								<td height="23">
										<font size="1">
												<select size="1" name="situacao" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >&nbsp;
                              <option value="Todos">Todos</option>
    						  <option value="Enviado Para Analista">Enviado Analista</option>
                              <option value="Não Verificado">Não Vereficado</option>
                              <option value="Concluido">Concluido</option>
                              <option value="Fechado">Fechado</option>
     						  <option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>
						</select></td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

												Categoria:&nbsp;</td>
								<td height="23">
										<font size="1">
												<select size="1" name="categoria" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;"  >&nbsp;
                              <option value="Todos">Todos</option>
                       <?
                        $checa = mysql_query("SELECT * FROM sgc_categoria order by id_categoria='$id_categoria' desc") or print(mysql_error());
                        while($dados=mysql_fetch_array($checa)){
                        $id_categoria = $dados['id_categoria'];
                        $descricao_cate  = $dados['descricao'];
                       ?>
                         <option value="<?echo $id_categoria?>"><?echo $descricao_cate?></option>
                       <?
                          }
                       ?>
						</select></td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

										Usuário:&nbsp;</td>
								<td height="23">
										<font size="1">
												<select size="1" name="usuario" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;">&nbsp;
                              <option value="Todos">Todos</option>
                               <?
                        $checa = mysql_query("select id_usuario,concat(us.primeiro_nome,' ',us.ultimo_nome)nome  from sgc_usuario us  order by 2") or print(mysql_error());
                        while($dados=mysql_fetch_array($checa)){
                        $id_usuario = $dados['id_usuario'];
                        $nome_us  = $dados['nome'];
                       ?>
                         <option value="<?echo $id_usuario?>"><?echo $nome_us?></option>
                       <?
                          }
                       ?>

         					</select></td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">&nbsp;</td>
								<td height="23">&nbsp;</td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23" align="right">

										Palavra Chave:&nbsp;</td>
								<td height="23">
										<font size="1"><input type="text" name="palavra_chave" size="49" style="background-color: #FFFFFF" maxlength="60"></td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23">&nbsp;</td>
								<td height="23">&nbsp;</td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23">&nbsp;</td>
								<td height="23">
										<font size="1">
												<input type="submit" value="Buscar" name="B1"></td>
								<td width="31">&nbsp;</td>
							</tr>
							<tr>
								<td width="15">&nbsp;</td>
								<td height="23">&nbsp;</td>
								<td height="23">&nbsp;</td>
								<td width="31">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table></td>
		</tr>
	</table>
</form>
</div>


<?



}elseif($acao_int=="buscar"){

       $data_inicial=$_POST['data_inicial'];
         $data_final=$_POST['data_final'];
               $area=$_POST['area'];
    $analista_change=$_POST['analista_change'];
         $prioridade=$_POST['prioridade'];
           $situacao=$_POST['situacao'];
          $categoria=$_POST['categoria'];
            $usuario=$_POST['usuario'];
      $palavra_chave=$_POST['palavra_chave'];

       $data_inicial=databd($data_inicial);
         $data_final=databd($data_final);



//-----------------Datas------------------//
if($data_inicial!=null and $data_final!=null){

   $sql_adendo= " and date_format(ch.data_criacao,'%Y-%m-%d') BETWEEN '$data_inicial' AND '$data_final'";

   $data_inicial=invertedata($data_inicial);
   $data_final=invertedata($data_final);

   $data_busca=" $data_inicial à $data_final ";
}elseif($data_inicial!=null and $data_final==null){

  $sql_adendo= " and date_format(ch.data_criacao,'%Y-%m-%d') ='$data_inicial'";

   $data_inicial=invertedata($data_inicial);
   $data_busca=" $data_inicial";

}elseif($data_inicial==null and $data_final!=null){

  $sql_adendo= " and date_format(ch.data_criacao,'%Y-%m-%d') >='$data_final'";
  $data_final=invertedata($data_final);
  $data_busca=" $data_final";

}
//--------------------------------------//

//-----------------Area------------------//
if($area!="Todos"){

   $sql_adendo.= " and al.id_area_locacao=$area";
   $area_atuacao=tabelainfo($area,'sgc_area_locacao','descricao','id_area_locacao','');

}else{
  $area_atuacao="Todos";
}
//--------------------------------------//
//-----------------Analista(suporte)------------------//
if($analista_change!="Todos" and $analista_change!=null ){
   $sql_adendo.= " and ch.id_suporte=$analista_change";
   $analista=tabelainfo($analista_change,'sgc_usuario',"concat(primeiro_nome,' ',ultimo_nome)",'id_usuario','');
}else{
   $analista="Todos";
}
//--------------------------------------//

//-----------------Prioridade------------------//
if($prioridade!="Todos"){

   $sql_adendo.= " and slaa.id_sla_analista=$prioridade";
   $prioridade_rel=tabelainfo($prioridade,'sgc_sla_analista_usuario',"descricao",'id_sla_analista','');
}else{
  $prioridade_rel="Todos";
}
//--------------------------------------//
//-----------------Situacao------------------//
if($situacao!="Todos"){

   $sql_adendo.= " and hc.situacao='$situacao'";
   $situacao_rel="$situacao";
}else{
  $situacao_rel="Todos";
}
//--------------------------------------//
//-----------------Categoria------------------//
if($categoria!="Todos"){

   $sql_adendo.= " and ch.id_categoria='$categoria'";
   $categoria_rel=tabelainfo($categoria,'sgc_categoria',"descricao",'id_categoria','');
}else{
 $categoria_rel="Todos";
}
//--------------------------------------//
//-----------------Usuário------------------//
if($usuario!="Todos"){

   $sql_adendo.= " and us.id_usuario=$usuario";
   $usuario_rel=tabelainfo($usuario,'sgc_usuario',"concat(primeiro_nome,' ',ultimo_nome)",'id_usuario','');
}elseif($usuario=="Todos"){
   $usuario_rel="Todos";
}
//--------------------------------------//
//-----------------Palavra Chave------------------//
if($palavra_chave!="Todos"){

   $sql_adendo.=" and ch.titulo like '%$palavra_chave%'";
   $palavrachave="$palavra_chave";

}
//--------------------------------------//

?>
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
						<fieldset style="padding: 2">
						<legend><b>Parâmetros</b></legend>
     	<table border="0" width="100%" cellspacing="0" cellpadding="0" height="23">
							<tr>
								<td width="30" height="23">&nbsp;</td>
								<td width="98" height="23">
								<p align="right">Data:&nbsp;&nbsp; </td>
								<td height="23" width="243"><?echo $data_busca?></td>
								<td height="23" width="169">
								<p align="right">Prioridade:&nbsp; </td>
								<td height="23" width="361"><?echo $prioridade_rel?></td>
								<td height="23" width="45">&nbsp;</td>
								<td width="34" height="23">&nbsp;</td>
							</tr>
							<tr>
								<td width="30" height="23">&nbsp;</td>
								<td width="98" height="23">
								<p align="right">Área atuação:&nbsp;&nbsp; </td>
								<td height="23" width="243"><?echo $area_atuacao?></td>
								<td height="23" width="169">
								<p align="right">Situação Chamado:&nbsp;&nbsp;</td>
								<td height="23" width="361"><?echo $situacao_rel?></td>
								<td height="23" width="45">&nbsp;</td>
								<td width="34" height="23">&nbsp;</td>
							</tr>
							<tr>
								<td width="30" height="23">&nbsp;</td>
								<td width="98" height="23">
								<p align="right">Analista:&nbsp;&nbsp; </td>
								<td height="23" width="243"><?Echo $analista?></td>
								<td height="23" width="169">
								<p align="right">Categoria:&nbsp;&nbsp;</td>
								<td height="23" width="361"><?Echo $categoria_rel?></td>
								<td height="23" width="45">&nbsp;</td>
								<td width="34" height="23">&nbsp;</td>
							</tr>
							<tr>
								<td width="30" height="23">&nbsp;</td>
								<td width="98" height="23">&nbsp;</td>
								<td height="23" width="243">&nbsp;</td>
								<td height="23" width="169">
								<p align="right">Usuário:&nbsp;&nbsp; </td>
								<td height="23" width="361"><?echo $usuario_rel?></td>
								<td height="23" width="45">&nbsp;</td>
								<td width="34" height="23">&nbsp;</td>
							</tr>
							<tr>
								<td width="30" height="23">&nbsp;</td>
								<td width="120" height="23">
								<p align="right">Palavra Chave:&nbsp;&nbsp;</td>
								<td height="23" width="489" colspan="2"><?echo $palavrachave?></td>
								<td height="23" width="361">&nbsp;</td>
								<td height="23" width="45">&nbsp;</td>
								<td width="34" height="23">&nbsp;</td>
							</tr>
							<tr>
								<td width="30" height="23">&nbsp;</td>
								<td width="98" height="23">&nbsp;</td>
								<td height="23" width="489" colspan="2">&nbsp;</td>
								<td height="23" width="361">&nbsp;</td>
								<td height="23" width="45">&nbsp;</td>
								<td width="34" height="23">&nbsp;</td>
							</tr>
						</table>
						</fieldset></form>
					</td>
				</tr>
			</table></td>
		</tr>
	</table>
</div>
<BR>
<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
	<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
		<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
		<table cellSpacing="1" cellPadding="5" width="100%" border="0" style="border-bottom-width: 0px">
			<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
				<td class="cat" align="center" style="border-bottom-style: none; border-bottom-width: medium">
				<fieldset style="padding: 2">
				<legend><b>Resultado(s)</b></legend>
				<table border="0" width="100%" cellspacing="0" cellpadding="0" height="23" style="border-top-width: 0px; border-bottom-width: 0px">
					<tr>
						<td width="29" height="23" style="border-right-style: none; border-right-width: medium">&nbsp;</td>
						<td width="50" height="23" bgcolor="#8C8984" style="border-style:none; border-width:medium; ">
						<p align="center"><font color="#FFFFFF"><b>ID</b></font></td>
						<td height="23" width="95" align="center" bgcolor="#8C8984" style="border-style:none; border-width:medium; ">
						<font color="#FFFFFF"><b>ANALISTA</b></font></td>
						<td height="23" width="339" bgcolor="#8C8984" style="border-style:none; border-width:medium; ">
						<p align="center"><font color="#FFFFFF"><b>TITULO</b></font></td>
						<td height="23" width="95" align="center" bgcolor="#8C8984" style="border-style:none; border-width:medium; ">
						<font color="#FFFFFF"><b>USUÁRIO</b></font></td>
						<td height="23" width="95" align="center" bgcolor="#8C8984" style="border-style:none; border-width:medium; ">
						<font color="#FFFFFF"><b>PRIORIDADE</b></font></td>
						<td height="23" width="95" align="center" bgcolor="#8C8984" style="border-style:none; border-width:medium; ">
						<font color="#FFFFFF"><b>CRIAÇÃO</b></font></td>
						<td height="23" width="200" align="center" bgcolor="#8C8984" style="border-style:none; border-width:medium; ">
						<font color="#FFFFFF"><b>SITUAÇÃO</b></font></td>
						<td height="23" width="50" align="center" bgcolor="#8C8984" style="border-style:none; border-width:medium; ">
						<font color="#FFFFFF"><b>TEMPO</b></font></td>
						<td width="34" height="23" style="border-left-style: none; border-left-width: medium">&nbsp;</td>
					</tr>



<?


$checa = mysql_query("SELECT
  ch.id_chamado
, if(ch.tempo_gasto is null,'00:00:00',sec_to_time(tempo_gasto))  tempo_gasto
, ch.titulo
, ch.id_suporte
, ch.id_categoria
, ch.id_categoria id_cat_imut
, ch.id_usuario dono
, ch.descricao
, hc.visto_suporte
, al.id_area_locacao
, al.descricao desc_area
, ch.obs
, hc.id_categoria
, us1.id_usuario id_analista
, hc.situacao
, us.primeiro_nome
, slaa.descricao prioridade
, hc.atualizacao
, date_format(hc.data_criacao,'%d/%m/%y %H:%i')ultima_atualizacao
, date_format(ch.data_criacao,'%d/%m/%y %H:%i')data_criacao
, us1.primeiro_nome  primeiro_nome_suporte
, concat(us1.primeiro_nome,' ',us1.ultimo_nome,' - ',dp.descricao,' - ',un.descricao)suporte
, concat(us.primeiro_nome,' ',us.ultimo_nome,' - ',dp1.descricao,' - ',un1.descricao)usuario
, us.email email_usuario , concat('(',us.ddd,')',' ',us.telefone,' Ramal: ',us.ramal)telefone
FROM
sgc_chamado ch
, sgc_historico_chamado hc
, sgc_usuario us
, sgc_usuario us1
, sgc_sla_analista_usuario slaa
, sgc_unidade un

, sgc_departamento dp
, sgc_unidade un1

, sgc_departamento dp1
, sgc_area_locacao al
, sgc_associacao_area_analista aa

where 1=1
$sql_adendo
 and hc.id_chamado = ch.id_chamado
 and us.id_usuario = ch.quem_criou
 and slaa.id_sla_analista = hc.prioridade
 and us1.id_usuario = hc.id_suporte
 and us1.id_unidade = un.codigo
 and us1.id_departamento = dp.id_departamento
 and us.id_unidade = un1.codigo
 and us.id_departamento = dp1.id_departamento
 and aa.id_analista=us1.id_usuario
 and al.id_area_locacao= ch.id_area_locacao
 and aa.id_area = al.id_area_locacao
 and hc.id_historico=ch.id_linha_historico
 order by ch.data_criacao, hc.situacao desc

 ") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
 $id_chamado         = $dados['id_chamado'];
 $data_chamado       = $dados['data_criacao'];
 $ultima_atualizacao = $dados['ultima_atualizacao'];
 $suporte            = $dados['suporte'];
 $prioridade         = $dados['prioridade'];
 $situacao           = $dados['situacao'];
 $usuario            = $dados['usuario'];
 $email              = $dados['email_usuario'];
 $telefone           = $dados['telefone'];
 $titulo             = $dados['titulo'];
 $obs                = $dados['obs'];
 $descricao_cha      = $dados['descricao'];
 $id_area_locacao    = $dados['id_area_locacao'];
 $desc_area          = $dados['desc_area'];
 $dono               = $dados['dono'];
 $analista_ch        = $dados['id_suporte'];
 $id_analista_ch     = $dados['id_analista'];
 $id_categoria       = $dados['id_categoria'];
 $id_cat_imut        = $dados['id_cat_imut'];
 $visto_suporte      = $dados['visto_suporte'];
 $nome_usuario       = $dados['primeiro_nome'];
 $tempo_gasto        = $dados['tempo_gasto'];
 $suporte_nome       = $dados['primeiro_nome_suporte'];
 $atualizacao        = $dados['atualizacao'];
 $count++;
?>
	<tr>
						<td width="29" height="23" style="border-right-style: none; border-right-width: medium">&nbsp;</td>
						<td width="50" height="23" style="border-style:none; border-width:medium; color: #808080; ">
						<p align="center"><font color="#000000"><?echo $id_chamado?></font></td>
						<td height="23" width="95" align="center" style="border-style:none; border-width:medium; color: #808080; ">
						<font color="#000000"><?echo $suporte_nome?></font></td>
						<td height="23" width="339" style="border-style:none; border-width:medium; color: #808080; ">
						<font color="#000000"><?echo $titulo?></font></td>
						<td height="23" width="95" align="center" style="border-style:none; border-width:medium; color: #808080; ">
						<font color="#000000"><?echo $nome_usuario?></font></td>
						<td height="23" width="95" align="center" style="border-style:none; border-width:medium; color: #808080; ">
						<font color="#000000"><?echo $prioridade?></font></td>
						<td height="23" width="95" align="center" style="border-style:none; border-width:medium; color: #808080; ">
						<font color="#000000"><?echo $data_chamado?></font></td>
						<td height="23" width="200" align="center" style="border-style:none; border-width:medium; color: #808080; ">
						<font color="#000000"><?echo $situacao?></font></td>
						<td height="23" width="50" align="center" style="border-style:none; border-width:medium; color: #808080; ">
						<font color="#000000"><?echo $tempo_gasto?></font></td>
						<td width="34" height="23" style="border-left-style: none; border-left-width: medium">&nbsp;</td>
					</tr>
					<tr>
						<td width="29" height="23" style="border-right-style: none; border-right-width: medium">&nbsp;</td>
						<td width="1019" height="23" colspan="8" style="color: #808080; border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" bgcolor="#FFFFFF">
						<font color="#000000"><b>&nbsp;Última atualização:</b>&nbsp;<?echo  $atualizacao?></font></td>
						<td width="34" height="23" style="border-left-style: none; border-left-width: medium">&nbsp;</td>
					</tr>
<?

}
?>

				</table></fieldset></form>
					</td>
			</tr>
		</table></td>
	</tr>
</table>
<BR>
<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
	<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
		<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
  <table cellSpacing="1" cellPadding="5" width="100%" border="0" style="border-bottom-width: 0px">
			<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
				<td class="cat" align="center" style="border-bottom-style: none; border-bottom-width: medium">
				<fieldset style="padding: 2">
				<legend><b>Estatísticas</b></legend>
				<table border="0" width="100%" cellspacing="0" cellpadding="0" height="23">
					<tr>
						<td width="28">&nbsp;</td>
						<td>
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td width="156" height="23" align="right">Total
								Chamados:&nbsp;&nbsp; </td>
								<td width="164" height="23"><?echo $count?></td>
								<td width="192" height="23" align="right">Tempo
								Médio Resolução:&nbsp;&nbsp; </td>
								<td height="23" width="162">$medio_resolucao</td>
								<td height="23" width="31">&nbsp;</td>
								<td height="23" width="36">&nbsp;</td>
								<td height="23" width="92">&nbsp;</td>
							</tr>
							<tr>
								<td width="156" height="23" align="right">Média
								Diária:&nbsp;&nbsp; </td>
								<td width="164" height="23">$media_diaria</td>
								<td width="192" height="23" align="right">
								Satisfação:&nbsp;&nbsp; </td>
								<td height="23" width="162">$satisfacao</td>
								<td height="23" width="31">&nbsp;</td>
								<td height="23" width="36">&nbsp;</td>
								<td height="23" width="92">&nbsp;</td>
							</tr>
							<tr>
								<td width="156" height="23">&nbsp;</td>
								<td width="164" height="23">&nbsp;</td>
								<td width="192" height="23">&nbsp;</td>
								<td height="23" width="162">&nbsp;</td>
								<td height="23" width="31">&nbsp;</td>
								<td height="23" width="36">&nbsp;</td>
								<td height="23" width="92">&nbsp;</td>
							</tr>
						</table>
						</td>
						<td width="28">&nbsp;</td>
					</tr>
				</table>
				</fieldset></form>
					</td>
			</tr>
		</table></td>
	</tr>
</table>
<?

 }
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
