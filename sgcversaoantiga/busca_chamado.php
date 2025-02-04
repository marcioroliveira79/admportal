<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Busca avançada de chamado(s)";

$id_item=$_GET['id_item'];
$arquivo="busca_chamado.php";


if(!isset($acao_int)){
if(chamado_fechado_falta_enquete($idusuario)!=null){
  $id_chamado_enquete=chamado_fechado_falta_enquete($idusuario);
  header("Location: ?action=vis_chamado.php&acao_int=enquete&id_chamado=$id_chamado_enquete");
}

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
						<tr>                               <input type='hidden' name='id_item' value='<?echo $id_item?>'>
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
                              <option value="NAOFECHADO">Todos Não Fechados e Concluido</option>
    						  <option value="Enviado Para Analista">Enviado Analista</option>
                              <option value="Não Verificado">Não Verificado</option>
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
								<td height="23" align="right">

										Unidade Solicitante:&nbsp;</td>
								<td height="23">
										<font size="1">
												<select size="1" name="unidade" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;">&nbsp;
                              <option value="Todos">Todos</option>
                               <?
                        $checa = mysql_query("SELECT distinct un.codigo, un.sigla FROM sgc_chamado ch, sgc_unidade un WHERE un.codigo = ch.id_unidade order by sigla") or print(mysql_error());
                        while($dados=mysql_fetch_array($checa)){
                        $codigo = $dados['codigo'];
                        $sigla = $dados['sigla'];
                       ?>
                         <option value="<?echo $codigo?>"><?echo $sigla?></option>
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

       $id_item=$_POST['id_item'];

       $data_inicial=$_POST['data_inicial'];
         $data_final=$_POST['data_final'];
               $area=$_POST['area'];
    $analista_change=$_POST['analista_change'];
         $prioridade=$_POST['prioridade'];
           $situacao=$_POST['situacao'];
          $categoria=$_POST['categoria'];
            $usuario=$_POST['usuario'];
            $unidade=$_POST['unidade'];
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
if($situacao!="Todos" and $situacao!="NAOFECHADO"){

   $sql_adendo.= " and hc.situacao='$situacao'";
   $situacao_rel="$situacao";
}else{
  $situacao_rel="Todos";
}

if($situacao=="NAOFECHADO"){

$sql_adendo.= " and hc.situacao not in ('Fechado','Concluido')";
$situacao_rel="$situacao";

}
If($situacao=="ABERTOS"){
 $sql_adendo.= " and hc.situacao not in ('Fechado','Concluido') ";
 $situacao_rel="$situacao";
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
//-----------------Unidade------------------//
if($unidade!="Todos"){

   $sql_adendo.= " and un1.codigo=$unidade";
   $unidade_rel=tabelainfo($unidade,'sgc_unidade',"concat(descricao,' - ',sigla)",'id_unidade','');
}elseif($unidade=="Todos"){
   $unidade_rel="Todos";
}
//--------------------------------------//
//-----------------Palavra Chave------------------//

if($palavra_chave!=null){

   $sql_p_chave_tb_chamado_descricao=0;
   $cadas = mysql_query("SELECT
   ch.id_chamado
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
  and ch.descricao like '%$palavra_chave%'
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
  order by ch.data_criacao, hc.situacao desc")or print(mysql_error());
         while($dados=mysql_fetch_array($cadas )){
          $id_chamados_chan_desc[$sql_p_chave_tb_chamado_descricao] = $dados['id_chamado'];
          $sql_p_chave_tb_chamado_descricao++;
          }


   $sql_p_chave_tb_chamado_titulo=0;
   $cadas = mysql_query("SELECT
   ch.id_chamado
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
  and ch.titulo like '%$palavra_chave%'
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
  order by ch.data_criacao, hc.situacao desc")or print(mysql_error());
         while($dados=mysql_fetch_array($cadas )){
          $id_chamados_chan_tit[$sql_p_chave_tb_chamado_titulo] = $dados['id_chamado'];
          $sql_p_chave_tb_chamado_titulo++;
          }
   
$sql_p_chave_tb_hist_chamado=0;
   $cadas = mysql_query("SELECT
   hc.id_chamado
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
  and hc.atualizacao like '%$palavra_chave%'
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
  order by ch.data_criacao, hc.situacao desc")or print(mysql_error());
         while($dados=mysql_fetch_array($cadas )){
          $id_chamados_hist[$sql_p_chave_tb_hist_chamado] = $dados['id_chamado'];
          $sql_p_chave_tb_hist_chamado++;
          }

$count=0;
foreach ($id_chamados_chan_tit as $v) {
   $resultado[$count]=$v;
   $count++;
}
foreach ($id_chamados_chan_desc as $v) {
   $resultado[$count]=$v;
   $count++;
}
foreach ($id_chamados_hist as $v) {
   $resultado[$count]=$v;
   $count++;
}

$resultado= array_unique($resultado);
$in=" and ch.id_chamado in (";

$count_res=0;
foreach ($resultado as $v){
   $ids.="$v,";
   $count_res++;
}

$ids = substr("$ids", 0, -1);
$in.=$ids.") ";

   if($count_res<1){
       $sql_adendo.=" and ch.id_chamado in ('X') ";
   }else{
     $sql_adendo.="$in";
   }
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
					<td class="info" align="middle"><b>:: Chamado(s)
					Localizado(s) :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center" style="background-color: #FFFFFF">
					<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#DFDFDF" style="border-collapse: collapse">
						<tr>
							<td width="48" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>ID</b></td>
							<td width="74" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Suporte</b></td>
							<td width="545" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Descrição Resumida</b></td>
							<td width="81" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Usuário</b></td>
							<td width="62" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Prioridade</b></td>
							<td width="200" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
							<p align="center"><b>Situação</b></td>
							<td width="150" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" bgcolor="#8C8984">
                          	<p align="center"><b>Data Chamado</b></td>

						</tr>
<?



$count=0;

 $sql="SELECT
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
, us1.primeiro_nome suporte
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
 order by ch.data_criacao desc, hc.situacao desc";
$checa = mysql_query("$sql") or print("ERRO AQUI 4");
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
 $descricao_cha_ver  = strtolower($dados['descricao']);
 $titulo_ver         = strtolower($dados['titulo']);
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
 $data_criacao       = $dados['data_criacao'];
 $count++;

if($palavra_chave!=null){
$count_desc=substr_count($descricao_cha_ver, strtolower("$palavra_chave"));

if($count_desc==0){
    $count_tit=substr_count($titulo_ver, strtolower("$palavra_chave"));

  if($count_tit>0){
      $descricao_cha=$titulo;
  }else{
  
      $checa_hist = mysql_query("
      SELECT atualizacao FROM sgc_historico_chamado WHERE
      id_chamado = $id_chamado
      and atualizacao like '%$palavra_chave%'
      order BY id_historico desc limit 1

      ") or print(mysql_error());
                    while($dados_hist=mysql_fetch_array($checa_hist)){
                     $descricao_cha         = $dados_hist['atualizacao'];
      }
  

  }
}


$troca = "<b>".strtoupper(strtolower($palavra_chave))."</b>";
$string = "$descricao_cha";
$show = eregi_replace($palavra_chave, $troca, $string);
}else{
   $show = $descricao_cha;
}


 
 ?>

						<tr>
							<td width="48" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center" bgcolor="#EEEEEE"><?echo $id_chamado?></td>
							<td width="74" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo $suporte?></td>
							<td width="545" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" align="center" bgcolor="#EEEEEE"><p align="left">&nbsp;<a href="?action=vis_chamado.php&id_chamado=<? echo $id_chamado?>"><font color="#000000"><?echo $show?></a></font></td>
							<td width="81" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo $nome_usuario?></td>
							<td width="62" height="23" bgcolor="<?echo $cor?>" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center"><?echo  $prioridade ?></td>
							<td width="200" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px" align="center"><?echo $situacao=tabelainfo($id_chamado,"sgc_historico_chamado","situacao","id_chamado","")?></td>
							<td width="160" height="23" style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px"  align="center" bgcolor="#EEEEEE"><?echo $data_criacao?></td>

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
</div><p align="left">Resultado(s): <? if($count==null){ echo 0; }else{ echo $count;}?><br>
<a href="sgc.php?action=<?echo $arquivo?>&id_item=<?echo $id_item?>"><font color="#000000">Para nova busca click aqui</font></a></p>
<p align="left">&nbsp;</p>

<?

}
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
