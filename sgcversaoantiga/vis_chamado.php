<?php
OB_START();
session_start();




if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

if($_GET['id_chamado']==null and $acao_int!="finalizar" and $acao_int!="questionamento_analista" and $acao_int!="rec_reg_quest_analista" and $acao_int!="questionamento_usuario" and $acao_int!="rec_reg_quest_usuario"){



  $id_chamado=$_POST['id_chamado'];
  header("Location: ?action=vis_chamado.php&id_chamado=$id_chamado");
  

}else{

  $id_chamado=$_GET['id_chamado'];

}


if(chamado_status($id_chamado)==0){
  $msg="Esse chamado não existe!";
}else{
  $msg="";
}

if($id_chamado==null){
 $id_chamado = 0;
 $msg = "O ID_CHAMADO é nulo!";
}


$count=0;
if(!isset($acao_int)){



$checa = mysql_query("SELECT
  ch.id_chamado
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
, date_format(hc.data_criacao,'%d/%m/%y %H:%i')ultima_atualizacao
, date_format(ch.data_criacao,'%d/%m/%y %H:%i')data_criacao
, time_to_sec(TIMEDIFF(sysdate('%Y-%m-%d %H:%i:%s'),ch.data_criacao))segundo
, TIMEDIFF(sysdate('%Y-%m-%d %H:%i:%s'),ch.data_criacao)Espera
, concat(us1.primeiro_nome,' ',us1.ultimo_nome,' - ',dp.descricao,' - ',un.descricao)suporte
, concat(us.primeiro_nome,' ',us.ultimo_nome,' - ',dp1.descricao,' - ',un1.descricao,' - ', un1.sigla)usuario
, us.email email_usuario
, concat('(',us.ddd,')',' ',us.telefone,' Ramal: ',us.ramal)telefone
, DATE_FORMAT(ch.previsao,'%d/%m/%Y %H:%i') previsao
FROM
  sgc_chamado ch
, sgc_historico_chamado hc
, sgc_usuario us
, sgc_usuario us1
, sgc_sla_analista_usuario slaa
, sgc_unidade un
, sgc_centro_custo cc
, sgc_departamento dp
, sgc_unidade un1
, sgc_centro_custo cc1
, sgc_departamento dp1
, sgc_area_locacao al
, sgc_associacao_area_analista aa

where hc.id_chamado=$id_chamado
and hc.id_chamado = ch.id_chamado
and us.id_usuario = ch.quem_criou
and slaa.id_sla_analista = hc.prioridade
and us1.id_usuario = hc.id_suporte

and us1.id_unidade = un.codigo
and us1.id_departamento = dp.id_departamento
and us1.id_centro = cc.id_centro

and us.id_unidade = un1.codigo
and us.id_departamento = dp1.id_departamento
and us.id_centro = cc1.id_centro

and aa.id_analista=ch.id_suporte
and al.id_area_locacao= ch.id_area_locacao
and aa.id_area = al.id_area_locacao
and hc.id_historico=ch.id_linha_historico


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
$previsao           = $dados['previsao'];
$count++;
}


if($idusuario==$analista_ch AND $visto_suporte==null OR $visto_suporte=="0000-00-00 00:00:00"){

  $checa = mysql_query("SELECT * FROM sgc_historico_chamado
                         WHERE id_chamado = $id_chamado
                         order by data_criacao desc limit 1");
   while($dados=mysql_fetch_array($checa)){
         $id_historico = $dados['id_historico'];
         }

   $cadas = mysql_query("UPDATE sgc_historico_chamado SET visto_suporte=sysdate() WHERE id_historico = $id_historico") or print(mysql_error());

}

?>
<script type="text/javascript" src="conf\prototype.js"></script>
<script language="javascript"  src="ajax-area-analista.js" type="text/javascript"></script>



<script LANGUAGE="JavaScript">
function DoClick(obj)
{  //alert(obj.name);//
   //alert ("x" + obj.style.display + "x");//
   if (obj.style.display == "") {
	//alert("Vai esconder");//
	obj.style.display = "none";
   }
   else {
	//alert("Vai mostrar");//
	obj.style.display = "";
   }
}
</script>


<script language='javascript'>
function confirmaExclusao(aURL) {
if(confirm('Tem certeza que deseja excluir este anexo?')) {
location.href = aURL;
}
}
</script>

<script>
function moveElementoDaLista(objFrom, objTo) {
try {
for (i = 0; i < objFrom.options.length; i++) {
if (objFrom.options[i].selected == true) {
no = new Option();
no.value = objFrom.options[i].value;
no.text = objFrom.options[i].text;
objTo.options[objTo.options.length] = no;
for (j = i + 1; j < objFrom.options.length; j++) {
objFrom.options[j - 1].value = objFrom.options[j].value;
objFrom.options[j - 1].text = objFrom.options[j].text;
objFrom.options[j - 1].selected = objFrom.options[j].selected;
}
objFrom.options[objFrom.options.length - 1] = null;
i--;
}
}
} catch(e) {
alert("Ocorreu um erro executando o método 'moveElementoDaLista(objFrom, objTo)'." +
"\nCausa:\n" + e);
}
}
</script>

<script language="JavaScript" type="text/javascript">
function loopSelectedAS()
{
  var txtSelectedValuesObj = document.getElementById('txtSelectedValuesAS');
  var selectedArray = new Array();
  var selObj = document.getElementById('selSeaShellsAS');
  var i;
  var count = 0;
  for (i=0; i<selObj.options.length; i++) {
    if (selObj.options[i].selected) {
      selectedArray[count] = selObj.options[i].value;
      count++;
    }
  }
  txtSelectedValuesObj.value = selectedArray;
}
</script>


<script type="text/javascript">
function seleciona(){
    document.forms['meuFormulario'].selecionados.options
	for(i=0;i<document.forms['meuFormulario'].selecionados.options.length;i++){
		document.forms['meuFormulario'].selecionados.options[i].selected=true;
	}
}
</script>

<script language="JavaScript" type="text/javascript">
function loopSelectedAS()
{
  var txtSelectedValuesObj = document.getElementById('txtSelectedValuesAS');
  var selectedArray = new Array();
  var selObj = document.getElementById('selSeaShellsAS');
  var i;
  var count = 0;
  for (i=0; i<selObj.options.length; i++) {
    if (selObj.options[i].selected) {
      selectedArray[count] = selObj.options[i].value;
      count++;
    }
  }
  txtSelectedValuesObj.value = selectedArray;
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



<script language="JavaScript">
function VerificaData(digData)
{
    var bissexto = 0;
    var data = digData;
    var tam = data.length;
    if (tam == 10)
    {
        var dia = data.substr(0,2)
        var mes = data.substr(3,2)
        var ano = data.substr(6,4)
        if ((ano > 1900)||(ano < 2100))
        {
            switch (mes)
            {
                case '01':
                case '03':
                case '05':
                case '07':
                case '08':
                case '10':
                case '12':
                    if  (dia <= 31)
                    {
                        return true;
                    }
                    break

                case '04':
                case '06':
                case '09':
                case '11':
                    if  (dia <= 30)
                    {
                        return true;
                    }
                    break
                case '02':
                    /* Validando ano Bissexto / fevereiro / dia */
                    if ((ano % 4 == 0) || (ano % 100 == 0) || (ano % 400 == 0))
                    {
                        bissexto = 1;
                    }
                    if ((bissexto == 1) && (dia <= 29))
                    {
                        return true;
                    }
                    if ((bissexto != 1) && (dia <= 28))
                    {
                        return true;
                    }
                    break
            }
        }
    }
    alert("A Data "+data+" é inválida!");
    return false;
}


function valida_dados (nomeform){




if( nomeform.data_inicio.value=="" && nomeform.hora_inicio.value=="" && nomeform.data_final.value=="" && nomeform.hora_termino.value=="" && nomeform.situacao.value!="Concluido"  ){

    return true;

}else if(nomeform.atualizar.value==""){
	if (   nomeform.data_inicio.value!="" && nomeform.hora_inicio.value=="" || nomeform.data_final.value=="" || nomeform.hora_termino.value=="" ){

	    alert ("\nPara incluir dados sobre tempo de trabalho você precisa preencher dos os campos no quadro: Apontamento de Horas Trabalhadas");
        return false;
	}

	if (   nomeform.hora_inicio.value!="" && nomeform.data_inicio.value=="" || nomeform.data_final.value=="" || nomeform.hora_termino.value=="" ){

	    alert ("\nPara incluir dados sobre tempo de trabalho você precisa preencher dos os campos no quadro: Apontamento de Horas Trabalhadas");
        return false;
	}

	if (   nomeform.data_final.value!="" && nomeform.hora_termino.value=="" || nomeform.data_inicio.value=="" || nomeform.hora_inicio.value=="" ){

	    alert ("\nPara incluir dados sobre tempo de trabalho você precisa preencher dos os campos no quadro: Apontamento de Horas Trabalhadas");
        return false;
	}

	if (   nomeform.hora_termino.value!="" && nomeform.data_final.value=="" || nomeform.data_inicio.value=="" || nomeform.hora_inicio.value=="" ){

	    alert ("\nPara incluir dados sobre tempo de trabalho você precisa preencher dos os campos no quadro: Apontamento de Horas Trabalhadas");
        return false;
	}
}
   return true;
}





</script>

<script language="JavaScript" type="text/javascript">
function compara_datas()
{
  var data_inicio_value  = document.getElementById('data_inicio');
  var hora_inicio_value  = document.getElementById('hora_inicio');
  var data_final_value   = document.getElementById('data_final');
  var hora_termino_value = document.getElementById('hora_termino');

  var vDia_inicio    = data_inicio_value.substr(0,2);
  var vMes_inicio    = data_inicio_value.substr(3,2);
  var vAno_inicio    = data_inicio_value.substr(6,5);
  var vHora_inicioc  = hora_inicio_value.substr(0,2);
  var vMinuto_inicio = hora_inicio_value.substr(3,2);

  var data_inicial = vDia_inicio.concat("/").concat(vMes_inicio).concat("/").concat(vAno_inicio);
  alert(data_inicial);
}
</script>




<?
if($count==0){

  $checa_asso = mysql_query("SELECT * FROM sgc_chamado WHERE id_chamado=$id_chamado") or print mysql_error();
  while($dados_asso=mysql_fetch_array($checa_asso)){
  $count++;
  }

  if($count>0){
   $msg="Chamado ainda não parametrizado, pelo Service-desk";
  }

?>

	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
             <table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: ERRO <?echo $id_chamado?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">

				<table border="1" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse" bordercolor="#DFDFDF">
					<tr>
						<td width="100%" align="right" height="23">
						<p align="center"><?echo $msg?></td>
					</tr>
                      			</table>

					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>


<?
}else{
?>

<body>
	<form method="POST" id="form1" name='meuFormulario' enctype="multipart/form-data"  action="?action=vis_chamado.php&acao_int=atualizar" onsubmit='seleciona();loopSelectedAS();return valida_dados(this)'>
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
   <table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Chamado # <?echo $id_chamado?> :: </b> </td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">

				<table border="1" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse" bordercolor="#DFDFDF">
                    <tr>
						<td width="108%" align="right" height="23" colspan="2">
						<p align="center"><b>

						<a href="javaScript: void(window.open('impressao.php?&id_chamado=<?echo $id_chamado?>&id_usuario=<?echo $idusuario?>&permissao=OK','Visualizar','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=650,height=600'));">
						
						<img border="0" src="imgs/impressora.gif" width="25" height="23"></a></b></td>


					</tr>

                    <tr>
						<td width="8%" align="right" height="23">Data Chamado:&nbsp;</td>
						<td width="100%" height="23">&nbsp;<?echo $data_chamado?></td>
                         <input type='hidden' name='id_chamado' value='<?echo $id_chamado?>'>
                         
                         <input type='hidden' name='url_chamado' value='<?echo $url=(isset($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>'>


					</tr>
					<tr>
						<td width="8%" align="right" height="23">Última atualização:&nbsp;</td>
						<td width="100%" height="23">&nbsp;<?echo $ultima_atualizacao?></td>
					</tr>
					<tr>
						<td  width="8%" align="right" height="23">Anexo(s):&nbsp;</td>
						<td width="100%" height="23">

                        <table border="0" width="100%" cellspacing="2" cellpadding="0">
                        	<tr>
                         		<td width="1%">&nbsp;</td>
                           		<td width="2%">&nbsp;</td>
                             		<td width="1%">&nbsp;</td>
                             		<td width="65%"><b>Nome Arquivo</b></td>
                             		<td width="2%">&nbsp;</td>
                               		<td width="11%"><b>Tamanho</b></td>
                                 		<td width="22%"><b>Data Inclusão</b></td>
                                   		<td width="36%">&nbsp;</td>
                                     	</tr>

                                     	   <?
                         $checa = mysql_query("select *,concat('v',versao,'-',nome_arquivo)nome_ver,concat(round((tamanho/1024),0),' ','KB')FILESIZE,date_format(data_cadastro,'%d/%m/%y %H:%i:%s')DATA_POST from sgc_anexo where id_chamado=$id_chamado order by id_anexo desc") or print(mysql_error());
                         while($dados=mysql_fetch_array($checa)){
                            $id_anexo = $dados['id_anexo'];
                            $nome_ver = $dados['nome_ver'];
                            $nome_arquivo = $dados['nome_arquivo'];
                            $versao = $dados['versao'];
                            $tamanho = $dados['FILESIZE'];
                            $data = $dados['DATA_POST'];
                            $count++;
                         ?>

                          	<tr>
		<td width="1%">&nbsp;</td>
		<td width="2%">
		<p align="center"><a target="_blank" href="arquivos/<?echo $nome_ver?>">
		<img border="0" src="imgs/icone_download.gif" width="17" height="17"></a></td>
		<td width="1%"></td>
		<td width="65%"><?echo $nome_arquivo?></td>
		<td width="2%">&nbsp;</td>
		<td width="11%"><?echo $tamanho?></td>
		<td width="22%"><?echo $data?> </td>
		<td width="36%">
        <?

               //----------------Fornece autorização para manipulação do chamado-------------------//

                    $checa1 = mysql_query("SELECT atributo1 FROM sgc_parametros_sistema ") or print(mysql_error());
                    while($dados1=mysql_fetch_array($checa1)){
                             $iditematributo = $dados1['atributo1'];
                    }

                    $acesso=acesso($idusuario,$iditematributo);

                    if($acesso=="OK" or $analista_ch==$idusuario){
                    $url_chamado=(isset($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                    ?>
                    <a href="javascript:confirmaExclusao('?action=vis_chamado.php&acao_int=excluir_anexo&id_anexo=<?echo $id_anexo?>&id_chamado=<?echo $id_chamado?>&url_chamado=<?echo $url_chamado?>')">
                    <img border="0" src="imgs/lixo.gif" width="18" height="18"></a>
                    <?

                    }
               ?>

        </td>
	</tr>
	<?
	}
	?>
</table>




                        </td>
					</tr>
							<tr>
						<td  width="8%" align="right" height="23"></td>
						<td width="100%" height="23">
						 <input type="file" name="arquivo" size="68" style="background-color: #FFFFFF"></td>
					</tr>
					<tr>
						<td width="8%" align="right" height="23">Expectadores:&nbsp;</td>
						<td width="100%" height="23">

                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                       	<tr>
                 		<td width="5%">&nbsp;</td>
                		<td width="70%"><font face="Verdana" size="1"><b>Nome</b></font></td>
                		<td width="30%"><font face="Verdana" size="1"><b>e-mail</b></font></td>
                    	</tr>
                    	<?
                    	$checa = mysql_query("SELECT concat(sigla,' - ',dp.descricao,' - ',primeiro_nome,' ',ultimo_nome)nome, email
                        FROM
                        sgc_contatos_por_chamado cc
                        , sgc_usuario su
                        , sgc_unidade u
                        , sgc_departamento dp

                        where cc.ID_CHAMADO = $id_chamado
                        and cc.id_usuario_contatar = su.id_usuario
                        and u.codigo = su.id_unidade
                        and dp.id_departamento = su.id_departamento
                        order by nome  ") or print(mysql_error());
                         while($dados=mysql_fetch_array($checa)){
                            $nome = $dados['nome'];
                            $email_expcs = $dados['email'];

                         ?>

                    	<tr>
                 		<td width="5%">&nbsp;</td>
                		<td width="70%"><?echo $nome?></td>
                		<td width="30%"><?echo $email_expcs?></td>
                       	</tr>
                        <?
                        }
                        ?>

                        </table>


                        </td>
					</tr>
						<tr>
							<td>
       						</td>
						<td width="100%" height="23">


						
                        <p align="left">&nbsp;
                                   <?

               //----------------Fornece autorização para manipulação do chamado-------------------//

                    $checa = mysql_query("SELECT atributo1 FROM sgc_parametros_sistema ") or print(mysql_error());
                    while($dados=mysql_fetch_array($checa)){
                          $iditematributo = $dados['atributo1'];
                    }

                    $acesso=acesso($idusuario,$iditematributo);



                    if($acesso=="OK" or $analista_ch==$idusuario or $dono==$idusuario){

                         /*
                         echo "Acesso: "; echo $acesso; echo "<BR>";
                         echo "Dono: "; echo $dono; echo "<BR>";
                         echo "Analista: "; echo $analista_ch; echo "<BR>";
                         */
                               $flag_aberto="";
                               
                    }else{
                          $flag_aberto="Disabled";
                    }




                    //-----------------------Fornece autorizacao para mudar status do chamado------------------//
                    //Somente o dono ou analista//

                    if($acesso=="OK" or $analista_ch==$idusuario or $dono==$idusuario){

                      $flag_aberto_situacao="";
                    
                    }else{

                      $flag_aberto_situacao="Disabled";
                    
                    }
                    




                    ?>


                        <?
                        if($flag_aberto!='Disabled'){

                        ?>
                        <a href="#" OnClick="DoClick(contato)"><font color="#000000">Inserir e Remover Expectadores</font></a>
                        <?
                        }
                        ?>

                        <br>





                          <table  align="left" id='contato' name='contato' style='DISPLAY: none' border="0" width="500" cellspacing="0" cellpadding="0">
                          <tr align="left">
									<td width="5%">&nbsp;</td>
									<td width="8%" valign="top">&nbsp;</td>
									<td>&nbsp;</td>
									<td width="40%" valign="top">
                                    </td>
									<td width="5%">&nbsp;</td>
						    		</tr>
                            <tr>
                            <td width="5%">&nbsp;</td>
                            <td width="8%" valign="top">
                            <p align="right">
                            <span style="background-color: #FFFFFF">
                            <select style="border-style:solid; border-width:1px; font-size: 9px; width: 207; font-family: Verdana; height: 150; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px " multiple size="21" name="todos" >
                            <?

                          $checa = mysql_query("select
                          us.id_usuario
                          ,concat(us.primeiro_nome,' ',us.ultimo_nome)nome
                          from
                          sgc_usuario us
                          where us.id_usuario not in (SELECT id_usuario_contatar FROM sgc_contatos_por_chamado WHERE id_chamado=$id_chamado)  order by nome") or print(mysql_error());
                           while($dados=mysql_fetch_array($checa)){
                           $id_usuario = $dados['id_usuario'];
                           $nome = $dados['nome'];


                           ?>

                           <option value="<?echo $id_usuario?>"><?echo $nome?></option>

                           <?
                           }
                           ?>
                           </select></span></td>
						   <td>

                           <p align="center">
                           <input type='button' name='botaoET' onClick='moveElementoDaLista(this.form.todos,this.form.selecionados)' value='>>'><br>
                           <input type='button' name='botaoEY' onClick='moveElementoDaLista(this.form.selecionados,this.form.todos)' value='<<'>
						   </td>
						   <td width="30%" valign="top">
						   <p align="left">
						   <span style="background-color: #FFFFFF">
						   <select style="border-style:solid; border-width:1px; font-size: 9px; width: 210; font-family: Verdana; height: 150; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" id="selSeaShellsAS" multiple size="21" name="selecionados">
					       <?
                           $checa = mysql_query("select
                           us.id_usuario
                           ,concat(us.primeiro_nome,' ',us.ultimo_nome)nome
                            from
                           sgc_usuario us
                           where us.id_usuario in (SELECT id_usuario_contatar FROM sgc_contatos_por_chamado WHERE id_chamado=$id_chamado) order by nome") or print(mysql_error());
                            while($dados=mysql_fetch_array($checa)){
                           $id_usuario = $dados['id_usuario'];
                           $nome = $dados['nome'];


                           ?>

                           <option value="<?echo $id_usuario?>"><?echo $nome?></option>

                           <?
                           }
                           ?>
                           <input type="hidden" name="conjunto_selecionado" id="txtSelectedValuesAS"/>
                           </select>



                           </span>
                            </td>
									<td width="5%">&nbsp;</td>
								</tr>
								<tr>
									<td width="5%">&nbsp;</td>
									<td width="30%" valign="top">&nbsp;</td>
									<td>&nbsp;</td>
									<td width="35%" valign="top"></td>
									<td width="5%">&nbsp;</td>
								</tr>
                            </table>






                        </td>
						</tr>
                    	<tr>
						<td class="info" align="right" width="100%" colspan="2" height="23">
						<p align="center"><b>Informações do Usuário</b></td>
					</tr>
					<tr>
						<td align="right" width="158" height="23">Usuário:&nbsp;</td>
						<td align="right" width="100%" height="23">
						<p align="left">&nbsp;<?echo $usuario?></td>
					</tr>
					<tr>
						<td align="right" width="8%" height="23">E-mail:&nbsp;</td>
						<td align="right" width="100%" height="23">
						<p align="left">&nbsp;<?echo $email?></td>
					</tr>
					<tr>
						<td align="right" width="8%" height="23">Telefone:&nbsp;</td>
						<td align="right" width="100%" height="23">
						<p align="left">&nbsp;<?echo $telefone?></td>
					</tr>

					<tr>
					<tr>
						<td class="info" align="right" colspan="2" height="23">
						<p align="center"><b>Informações do Suporte</b></td>
					</tr>



                	<tr>
						<td align="right" width="8%" height="23">Área Atuação:&nbsp; </td>
						<td align="right" width="100%" height="23">
						<p align="left"><select size="1" name="area" Onchange="atualiza(this.value);" <?Echo $flag_aberto?> >




						  <?
                           //-----------------Se não for suporte xfac o grupo GERENCIA SUTIN é excluido da seleção---------//
                           If ( areasuporte($idusuario) != 11 ){
                               $sql1=" AND id_area_locacao != 15 ";
                           };


                            $checa_menu = mysql_query("SELECT id_area_locacao,descricao FROM sgc_area_locacao WHERE 1=1 $sql1 order by id_area_locacao=$id_area_locacao desc") or print mysql_error();
                                    while($dados_menu=mysql_fetch_array($checa_menu)){
                                    $id_area= $dados_menu["id_area_locacao"];
                                    $descricao= $dados_menu["descricao"];

                                ?>
     							<option value="<?echo $id_area?>"><?echo $descricao?></option>
                                <?
                           }
                        ?>
						</select></td>
					</tr>


                    <tr>
						<td align="right" width="8%" height="23">Analísta:&nbsp; </td>
						<td align="left" width="100%" height="23">
                        <div id="atualiza" >
                        <select size="1" name="analista_change"  <?Echo $flag_aberto?>>
                        <?
                            $checa_menu = mysql_query("
                                                        SELECT distinct
                              al.descricao desc_area
                              , us1.id_usuario id_analista
                              , concat(us1.primeiro_nome,' ',us1.ultimo_nome,' - ',dp.descricao,' - ',un.descricao)suporte

                              FROM


                                  sgc_usuario us1
                                , sgc_unidade un
                                , sgc_departamento dp
                                , sgc_area_locacao al
                                , sgc_associacao_area_analista aa

                                where


                                        us1.id_unidade = un.codigo
                                    and us1.id_departamento = dp.id_departamento
                                    and aa.id_analista=us1.id_usuario
                                    and al.id_area_locacao= aa.id_area


                                    and al.id_area_locacao =  $id_area_locacao
                                    and aa.id_area = al.id_area_locacao
                                    order by us1.id_usuario = $analista_ch  desc ") or print mysql_error();
                                    while($dados_menu=mysql_fetch_array($checa_menu)){
                                    $id_obj= $dados_menu["id_analista"];
                                    $descr_sup= $dados_menu["suporte"];

                                ?>
     							<option value="<?echo $id_obj?>"><?echo $descr_sup?></option>
                                <?
                         }
                           
                        ?>
                        </select>

                        </div>
                         </td>
					</tr>
					<tr>
						<td align="right" width="30%" height="23">Prioridade:&nbsp;</td>
						<td align="right" width="70%" height="23">
						<p align="left">
                        <?

                        if($acesso=="OK" or $analista_ch==$idusuario or $perfil_desc=="Suporte" or $perfil_desc==atributo('atributo8')){
                               $flag_aberto_prioridade="";
                        }else{
                               if(atributo('atributo26')=="N"){
                                     $flag_aberto_prioridade="Disabled";
                               }else{
                                     $flag_aberto_prioridade="";
                               }
                        }



                        ?>

                        <select size="1" name="prioridade" <?echo  $flag_aberto_prioridade?> >
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
                       </select>


                        </td>
					</tr>
					<tr>
						<td align="right" width="30%" height="23">Situação Chamado:&nbsp;</td>
						<td align="right" width="70%" height="23">
        				<p align="left"><select size="1" name="situacao" <?echo  $flag_aberto_situacao?>>
                        <?
                        




                        //------------------------Permissão para usuários service desk , adm etc -----------------//
                        $permitidos = explode(";", atributo("atributo19"));
                        foreach($permitidos as $valor){
                           if($valor==$perfil_id){
                             $permissao_situacao="OK";
                           }
                        }


                        
                        if($permissao_situacao=="OK"){
                        
                        if($situacao=="Fechado"){
                        ?>
                        <option value="Fechado">Fechado</option>
                        <option value="Aceito - Em Andamento">Aceito - Em Andamento</option>
                        <option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>
                        <option value="Concluido">Concluido</option>
                        <option value="Suspenso">Suspenso</option>
                        <option value="Não Verificado">Não Verificado</option>
                        <option value="Enviado Para Analista">Enviado Para Analista</option>
                        <?

                        }elseif($situacao=="Aceito - Em Andamento"){


                         ?>
                        <option value="Aceito - Em Andamento">Aceito - Em Andamento</option>
                        <option value="Fechado">Fechado</option>
                        <option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>
                        <option value="Concluido">Concluido</option>
                        <option value="Suspenso">Suspenso</option>
                        <option value="Não Verificado">Não Verificado</option>
                        <option value="Enviado Para Analista">Enviado Para Analista</option>


                        <?

                        }elseif($situacao=="Suspenso"){

                        ?>
                        <option value="Suspenso">Suspenso</option>
                        <option value="Aceito - Em Andamento">Aceito - Em Andamento</option>
                        <option value="Fechado">Fechado</option>
                        <option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>
                        <option value="Concluido">Concluido</option>
                        <option value="Não Verificado">Não Verificado</option>
                        <option value="Enviado Para Analista">Enviado Para Analista</option>


                        <?

                        }elseif($situacao=="Concluido"){

                        ?>
                        <option value="Concluido">Concluido</option>
                        <option value="Suspenso">Suspenso</option>
                        <option value="Aceito - Em Andamento">Aceito - Em Andamento</option>
                        <option value="Fechado">Fechado</option>
                        <option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>
                        <option value="Não Verificado">Não Verificado</option>
                        <option value="Enviado Para Analista">Enviado Para Analista</option>
                        <?

                        }elseif($situacao=="Não Verificado"){

                        ?>
                        <option value="Não Verificado">Não Verificado</option>
                        <option value="Concluido">Concluido</option>
                        <option value="Suspenso">Suspenso</option>
                        <option value="Aceito - Em Andamento">Aceito - Reabrir</option>
                        <option value="Fechado">Fechado</option>
                        <option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>
                        <option value="Enviado Para Analista">Enviado Para Analista</option>
                        <?

                        }elseif($situacao=="Enviado Para Analista"){

                        ?>
                        <option value="Enviado Para Analista">Enviado Para Analista</option>
                        <option value="Não Verificado">Não Verificado</option>
                        <option value="Concluido">Concluido</option>
                        <option value="Suspenso">Suspenso</option>
                        <option value="Aceito - Em Andamento">Aceito - Em Andamento</option>
                        <option value="Fechado">Fechado</option>
                        <option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>
                        <?

                        }elseif($situacao=="Aguardando Resposta - Usuário"){

                        ?>
                        <option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>
                        <option value="Enviado Para Analista">Enviado Para Analista</option>
                        <option value="Não Verificado">Não Verificado</option>
                        <option value="Concluido">Concluido</option>
                        <option value="Suspenso">Suspenso</option>
                        <option value="Aceito - Em Andamento">Aceito - Em Andamento</option>
                        <option value="Fechado">Fechado</option>

                        <?

                          }
                        
                        
                        
                        }else{


                        

                        if($situacao=="Fechado"){
                        ?>
                        <option value="Fechado">Fechado</option>
                        <option value="Enviado Para Analista">Reenviar Para Analista - Reabrir</option>
                        <option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>
                        <!--<option>Suspenso</option>-->
                        <!--<option>Concluido</option>-->
                        <!--<option>Não Verificado</option>-->
                        <!--<option>Enviado Para Analista</option>-->
                        <!--<option>Aguardando Resposta - Usuário</option>-->
                        <?

                        }elseif($situacao=="Aceito - Em Andamento"){


                         ?>
                        <option value="Aceito - Em Andamento">Aceito - Em Andamento</option>
                        <option value="Fechado">Fechado</option>
                        <!--<option value="Suspenso">Suspenso</option>-->
                        <!--<option value="Concluido">Concluido</option>-->
                        <!--<option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>-->
                        <!--<option>Não Verificado</option>-->
                        <!--<option>Enviado Para Analista</option>-->

                        <?

                        }elseif($situacao=="Suspenso"){

                        ?>
                        <option value="Suspenso">Suspenso</option>
                        <option value="Fechado">Fechado</option>
                        <!--<option value="Aceito - Em Andamento">Aceito - Reabrir</option>-->
                        <option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>
                        <!--<option>Não Verificado</option>-->
                        <!--<option>Enviado Para Analista</option>-->
                        <?

                        }elseif($situacao=="Concluido"){

                        ?>
                        <option value="Concluido">Concluido</option>
                        <!--<option>Suspenso</option>-->
                        <option value="Enviado Para Analista">Reenviar Para Analista - Reabrir</option>
                        <option value="Fechado">Fechado</option>
                        <!--<option>Não Verificado</option>-->
                        <!--<option>Enviado Para Analista</option>-->
                        <option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>
                        <?

                        }elseif($situacao=="Não Verificado"){

                        ?>
                        <option value="Não Verificado">Não Verificado</option>
                        <option value="Fechado">Fechado</option>
                        <!--<option>Concluido</option>-->
                        <!--<option>Suspenso</option>-->
                        <!--<option value="Aceito - Em Andamento">Aceito - Em Andamento</option>-->
                        <!--<option>Fechado</option>-->
                        <!--<option>Enviado Analísta</option>-->
                        <!--<option>Aguardando Resposta - Usuário</option>-->
                        <?

                        }elseif($situacao=="Enviado Para Analista"){

                        ?>
                        <option value="Enviado Para Analista">Enviado Para Analista</option>
                        <!--<option>Não Verificado</option>-->
                        <!--<option>Concluido</option>-->
                        <!--<option>Suspenso</option>-->
                        <!--<<option value="Aceito - Em Andamento">Aceito - Em Andamento</option>-->
                        <!--<option>Fechado</option>-->
                        <!--<option>Aguardando Resposta - Usuário</option>-->
                        <?

                        }elseif($situacao=="Aguardando Resposta - Usuário"){

                        ?>
                        <option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>
                        <!--<option>Enviado Para Analista</option>-->
                        <!--<option>Não Verificado</option>-->
                        <!--<option value="Concluido">Concluido</option>-->
                        <!--<option value="Suspenso">Suspenso</option>-->
                        <!--<option value="Aceito - Em Andamento">Aceito - Em Andamento</option>-->
                        <option value="Fechado">Fechado</option>
                        <?

                          }
                        }
                        ?>
                        </select>

                        </td>
					</tr>
					<?
					
					if($idusuario=='17303' and $analista_ch==$idusuario){
                      if($situacao=="Fechado" or $situacao=="Concluido"){
                         $previsao_flag="disabled";
                      }else{
                         $previsao_flag=null;
                      }

                    ?>
					<tr>
						<td align="right" width="8%" height="23">Previsão para conlusão:&nbsp;</td>
						<td align="right" width="100%" height="23">
						<p align="left">
                        <input <?echo $previsao_flag ?> type="text" id="previsao"   name="previsao" value="<?echo $previsao?>" style="background-color: #FFFFFF"onKeyUp="mascaraTexto(event,'99/99/9999 99:99')"  size="16" maxlength="16">

                        </td>
					</tr>
                    <?
                    }
                    ?>
						<td class="info" align="right" width="100%" height="23" colspan="2">
						<p align="center"><b>Informações do Chamado</b></td>
					</tr>
					<tr>
						<td align="right" width="8%" height="23">Categoria:&nbsp;</td>
						<td align="right" width="100%" height="23">
						<p align="left"><select size="1" name="categoria" <?echo  $flag_aberto?> >
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
                       </select>

                        </td>
					</tr>
					<tr>
						<td  align="right" width="100%" height="23" colspan="2">
						<fieldset style="padding: 2,  width:100%">
						<legend align="center"><b>Título</b></legend><p align="center"><?echo nl2br($titulo)?></fieldset><br>
						<fieldset style="padding: 2"><legend align="center"><b>Descrição</b></legend>
						<p align="center"><?echo nl2br($descricao_cha)?></fieldset></td>
					</tr>
					<?
					if($obs!=null){
					?>
					<tr>
						<td align="right" width="8%" height="23"><b>Obs:</b>&nbsp;</td>
						<td align="right" width="100%" height="23">
						<p align="left">&nbsp;<?echo $obs?></td>
					</tr>
					<?
					}
					?>
					<tr>
                     	<tr>
						<td align="right" width="8%" height="23">Atualizar:&nbsp;</td>
						<td align="right" width="100%" height="23">
						<p align="left">&nbsp;<textarea rows="8" name="atualizar" cols="100" style="background-color: #FFFFFF"></textarea></td>
					</tr>
                    <?
                    if($analista_suporte=="SIM"){
                    ?>

                    <tr>
						<td  align="right" width="100%" height="23" colspan="2">
						<fieldset style="padding: 2">
						<legend align="center"><b>Apontamento de Horas Trabalhadas</b></legend>
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td width="26">&nbsp;</td>
								<td width="105">
								<p align="right">Data de inicio:&nbsp;</td>
								<td width="89">
								<!--webbot bot="Validation" B-Value-Required="TRUE" I-Maximum-Length="11" -->
								<input type="text" id="data_inicio"  name="data_inicio" style="background-color: #FFFFFF"onKeyUp="mascaraTexto(event,'99/99/9999')"  size="10" maxlength="10"></td>
								<td width="107" align="right">Hora de inicio:&nbsp;</td>
								<td width="475">
								<!--webbot bot="Validation" B-Value-Required="TRUE" I-Maximum-Length="5" -->
								<input type="text" id="hora_inicio" name="hora_inicio" style="background-color: #FFFFFF"onKeyUp="mascaraTexto(event,'99:99')"   size="5" maxlength="5"></td>
								<td width="13">&nbsp;</td>
							</tr>
							<tr>
								<td width="26">&nbsp;</td>
								<td width="105">
								<p align="right">Data de termino:&nbsp;</td>
								<td width="89">
								<!--webbot bot="Validation" B-Value-Required="TRUE" I-Maximum-Length="11" -->
								<input type="text" id="data_final" name="data_final"style="background-color: #FFFFFF" onKeyUp="mascaraTexto(event,'99/99/9999')"   size="10" maxlength="10"></td>
								<td width="107" align="right">Hora de termino:&nbsp;</td>
								<td width="475">
								<!--webbot bot="Validation" B-Value-Required="TRUE" I-Maximum-Length="5" -->
								<input type="text" id="hora_termino" name="hora_termino"  style="background-color: #FFFFFF"onKeyUp="mascaraTexto(event,'99:99');compara_datas()" size="5" maxlength="5"></td>
								<td width="13">&nbsp;</td>
							</tr>
							<tr>
								<td width="26">&nbsp;</td>
								<td width="105">
								&nbsp;</td>
								<td width="89">
								&nbsp;</td>
								<td width="107" align="right">&nbsp;</td>
								<td width="475">
								&nbsp;</td>
								<td width="13">&nbsp;</td>
                           <?
                           $checa = mysql_query
                           ("
                           SELECT
                           if(ch.id_usuario=$idusuario,'OK',if(hc.id_suporte=$idusuario,'OK'
                           ,
                           (SELECT
                           if(us.perfil='CUSTOMIZADO','OK',if(us.perfil=0 or us.perfil=2 or us.perfil=3 or us.perfil=4,'OK','NAO'))
                           FROM sgc_usuario us WHERE us.id_usuario=$idusuario)
                           ))DECISAO
                           FROM
                           sgc_chamado ch, sgc_historico_chamado hc
                           where ch.id_chamado = $id_chamado
                           and hc.id_historico = ch.id_linha_historico
                            ") or print(mysql_error());
                           while($dados=mysql_fetch_array($checa)){
                           $decisao_codigo = $dados['DECISAO'];
                           }

                           if($decisao_codigo=="OK"){

                           ?>
							</tr>
                       			<tr>
								<td width="26">&nbsp;</td>
								<td width="776" colspan="4">
								<p align="center"><b>
                                <a href="javaScript: void(window.open('notas.php?&id_chamado=<?echo $id_chamado?>&id_usuario=<?echo $idusuario?>','Visualizar','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=650,height=500'));">
								<font color="#000000">Inserir Notas Técnicas e Códigos</font></a></b></td>
								<td width="13">&nbsp;</td>
							</tr>
							<?
							}
							?>
							
							
							<tr>
								<td width="26">&nbsp;</td>
								<td width="100%" colspan="4" align="center">
								<table border="0" width="100%" cellspacing="0" cellpadding="0">
                                    <?
                                     $checa = mysql_query("
                                     SELECT
                                     nt.id_chamado
                                     , nt.id_nota
                                     , nt.titulo_nota
                                     , date_format(nt.data_criacao,'%d/%m/%Y %h:%i') data_criacao
                                     , us.primeiro_nome
                                     , nt.somente_criador
                                     , nt.grupo_criador
                                     , nt.quem_criou
                                     ,if(nt.grupo_criador = 'X'
                                        ,(SELECT 1 FROM sgc_associacao_area_analista sa WHERE sa.id_analista = $idusuario and sa.id_area = ch.id_area_locacao),'')grupo
                                        FROM
                                          sgc_notas_codigos nt
                                          , sgc_usuario us
                                          , sgc_chamado ch
                                          where nt.quem_criou = us.id_usuario
                                          and nt.id_chamado = $id_chamado
                                          and nt.id_chamado = ch.id_chamado
                                          order by nt.data_criacao desc
                                      ") or print(mysql_error());
                                            while($dados=mysql_fetch_array($checa)){
                                            $nt_id_nota = $dados['id_nota'];
                                            $nt_titulo = $dados['titulo_nota'];
                                            $nt_data = $dados['data_criacao'];
                                            $nt_usuario = $dados['primeiro_nome'];
                                            $nt_grupo_ana = $dados['grupo'];
                                            $nt_criador = $dados['somente_criador'];
                                            $nt_grupo = $dados['grupo_criador'];
                                            $nt_criou = $dados['quem_criou'];
                                            
                                             $t++;
                                             if ($t % 2 == 0) {$cor_linha="";}
                                             else             {
                                             $cor_linha="#FFFFFF";
                                             }
                                            

                                    ?>
                                    <tr>
										<td bgcolor="<?echo $cor_linha?>"  width="473">
										<p align="center">

                                        <?
                                        if($nt_grupo_ana=="1" or $nt_criou==$idusuario or $nt_grupo==null and $nt_criador == null ){
                                        ?>
                                           <a href="?action=ver_nota.php&id_chamado=<?echo $id_chamado?>&id_nota=<?echo $nt_id_nota?>"><font color="#000000"><?echo $nt_titulo?></font></a>
                                        <?
                                        }else{
                                        ?>
                                           <font color="#000000"><?echo $nt_titulo?></font>
                                        <?
                                        }
                                        ?>

                                        </td>
										<td bgcolor="<?echo $cor_linha?>" width="137">
										<p align="center"><?echo $nt_data?></td>
										<td bgcolor="<?echo $cor_linha?>" width="137">
										<p align="center"><?echo $nt_usuario?></td>
									</tr>
									<?

                                    }
									
									?>
									
									
								</table>
								</td>
								<td width="13">&nbsp;</td>
							</tr>
						</table>
						</fieldset>
					</tr>
					<?
					}
					?>
					
					</tr>
					<tr>
						<td class="info" align="right" width="100%" height="23" colspan="2">
						<p align="center"><b>Atualizações</b></td>
					</tr>

            		<?

                    $checa = mysql_query("
                      select hc.acao
                    , concat(us.primeiro_nome,' ',us.ultimo_nome)usuario
                    , us1.id_usuario id_usuario_linha
                    , concat(us1.primeiro_nome,' ',us1.ultimo_nome)usuario_linha
                    , date_format(hc.data_criacao,'%d/%m/%y %H:%i')data_criacao
                    , hc.atualizacao
                  from
                     sgc_historico_chamado hc
                    ,sgc_usuario us
                    ,sgc_usuario us1
                  where hc.id_chamado=$id_chamado
                     and  us.id_usuario = hc.quem_criou
                     and  us1.id_usuario = hc.quem_criou_linha
                     
                     
                      order by hc.data_criacao desc
                    ") or print(mysql_error());
                    while($dados=mysql_fetch_array($checa)){
                       $acao = $dados['acao'];
                       $usuario = $dados['usuario'];
                       $id_usuario_linha = $dados['id_usuario_linha'];
                       $usuario_linha = $dados['usuario_linha'];
                       $data = $dados['data_criacao'];
                       $atualizacao= $dados['atualizacao'];
                       
                        $t++;
                        if ($t % 2 == 0) {$cor="";}
                        else             {

                        $cor="#FFFFFF";
                        $estilo="bordercolor='#C0C0C0' style='border-bottom-style: solid; border-bottom-width: 1px'";
                        }
                       

                    ?>
        			<tr>
                      	<td bgcolor="<?echo $cor?>" align="right" width="100%" height="23" colspan="2">
						<p align="left"><font size="1"><b>&nbsp;&nbsp;</b><?echo $data?> por <?echo $usuario_linha?></font>
                        </td>
					</tr>
                   	<?
                        if ($atualizacao!=null){
                        

                        
                        if ($id_usuario_linha != $idusuario){
                        
                        $talk="<img border='0' src='imgs/icon_talk.gif' width='27' height='22'>";

                        }else{
                        
                        $talk=null;
                        
                        }
                        
                        ?>
             			<tr <?echo $estilo  ?> >
                      	<td  bgcolor="<?echo $cor?>" align="right" width="100%" height="23" colspan="2">
                      	<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td width="16" >&nbsp;</td>
								<td><p align="left"><font size="2"><?echo $talk?>&nbsp;&nbsp;<i><?echo nl2br($atualizacao)?></i></font></td>
							</tr>
						</table>
                        </td>
     					</tr>
                        <?
                        }
                        ?>

                    <?
                    }
                    ?>

        			</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>

    <?

    if($flag_aberto_situacao=="Disabled"){
                      ?>
                      <input type='hidden' name='situacao' value='<?echo $situacao?>'>
                      <?
    }
    
    if($flag_aberto=="Disabled"){
                       $id_prioridade=tabelainfo($prioridade,"sgc_sla_analista_usuario","id_sla_analista","descricao","");
                       ?>
                       <input type='hidden' name='prioridade' value='<?echo $id_prioridade?>'>
                       <input type='hidden' name='analista_change' value='<?echo $analista_ch?>'>
                       <input type='hidden' name='categoria' value='<?echo $id_cat_imut?>'>
                       

                       <?
    }
    ?>



    <BR><p align="center"><input type="submit" value="Atualizar Chamado" name="B1"></p>
	</form>
	<p>&nbsp;</body></html>
<?
}
}elseif($acao_int=="questionamento_usuario"){

   session_unregister('url_questionar_usuario');
                    $url_questionar_usuario = (isset($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
   session_register("url_questionar_usuario");

$id_questao=$_GET['id_quest'];
$id_chamado=tabelainfo($id_questao,'sgc_questionario_usuario','id_chamado','id_questao','');
$questao=tabelainfo($id_questao,'sgc_questionario_usuario','questao','id_questao','');
$titulo=tabelainfo($id_chamado,'sgc_chamado','titulo','id_chamado','');
$nota=tabelainfo($id_chamado,"sgc_historico_chamado","nota_enquete","id_chamado"," and nota_enquete is not null order by id_historico desc limit 1");


?>

<script language='javascript'>
function valida_dados(nomeform)
{
    if (nomeform.replica.value=="")
    {
        alert ("\nDigite sua resposta sobre a questão.");
        nomeform.replica.focus();
        return false;
    }
return true;
}
</script>

<div align="center">
<form method="POST" id="form1" action="?action=vis_chamado.php&acao_int=rec_reg_quest_usuario" onsubmit="return valida_dados(this)">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
  	<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Usuário! O seu chamado foi questionado por favor responda abaixo :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">


                            <table border="0" width="100%">
								<tr>
									<td>Chamado #: <b><a href="?action=vis_chamado.php&id_chamado=<?echo $id_chamado?>"><?echo $id_chamado?></a></b></td>
								</tr>
								<tr>
									<td>Titulo: <b><a href="?action=vis_chamado.php&id_chamado=<?echo $id_chamado?>"><?echo $titulo?></a></b></td>
								</tr>
								<tr>
									<td>
									<p align="center">Questionamento:</td>
								</tr>
								<tr>
									<td>
									<p align="center"><b><?Echo $questao?> </b></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>

                                <tr>
									<td>
									<p align="center">Use o campo abaixo para
									responder ao questionamento!</td>
								</tr>
								<tr>
									<td>

        								    <p align="center">
                                            <input type='hidden' name='id_chamado' value='<?echo $id_chamado?>'>
                                            <input type='hidden' name='id_questao' value='<?echo $id_questao?>'>

                            			<textarea rows="9" name="replica" cols="92" style="background-color: #FFFFFF"></textarea></p>
										<p align="center">
										&nbsp;</p>

									<p align="center">Deseja manter a mesma
									nota?</td>
								</tr>
								<tr>
									<td>
									<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			</form>
			</td>
		</tr>
	</table>
</div>
									<div align="center">


<table border="0" width="11%" cellspacing="0" cellpadding="0">
                                                    <?

                                                    if($nota=="100"){
                                                       $checked_1 = "checked";
                                                    }elseif($nota=="75"){
                                                        $checked_2 = "checked";
                                                    }elseif($nota=="50"){
                                                        $checked_3 = "checked";
                                                    }elseif($nota=="0"){
                                                        $checked_4 = "checked";
                                                    }

                                                    ?>


                                                    	<tr>
															<td width="23">
															<input type="radio" value="100" <?echo $checked_1?> name="enquete" ></td>
															<td>Ótimo</td>
														</tr>
                                                        <tr>
															<td width="23">
															<input type="radio" value="75" <?echo $checked_2?> name="enquete" ></td>
															<td>Bom</td>
														</tr>
														<tr>
															<td width="23">
															<input type="radio" value="50" <?echo $checked_3?> name="enquete"></td>
															<td>Regular</td>
														</tr>
														<tr>
															<td width="23">
															<input type="radio" value="0" <?echo $checked_4?> name="enquete" ></td>
															<td>Ruim</td>
														</tr>
														</table></div>
									</td>
								</tr>
								<tr>
									<td>
									<p align="center">
										&nbsp;</td>
								</tr>
								<tr>
									<td>
									<p align="center">
										<input type="submit" value="Enviar" name="B1"></td>
										
											</form>
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


}elseif($acao_int=="questionamento_analista"){

               session_unregister('url_questionar_analista');
                    $url_questionar_analista = (isset($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
               session_register("url_questionar_analista");


$id_questao=$_GET['id_quest'];
$id_chamado=tabelainfo($id_questao,'sgc_questionario_analista','id_chamado','id_questao','');
$questao=tabelainfo($id_questao,'sgc_questionario_analista','questao','id_questao','');
$titulo=tabelainfo($id_chamado,'sgc_chamado','titulo','id_chamado','');
?>

<script language='javascript'>
function valida_dados(nomeform)
{
    if (nomeform.replica.value=="")
    {
        alert ("\nDigite sua resposta sobre a questão.");
        nomeform.replica.focus();
        return false;
    }
return true;
}
</script>

<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
  	<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Analista! O seu chamado foi questionado por favor responda abaixo :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">


                            <table border="0" width="100%">
								<tr>
									<td>Chamado #: <b><a href="?action=vis_chamado.php&id_chamado=<?echo $id_chamado?>"><?echo $id_chamado?></a></b></td>
								</tr>
								<tr>
									<td>Titulo: <b><a href="?action=vis_chamado.php&id_chamado=<?echo $id_chamado?>"><?echo $titulo?></a></b></td>
								</tr>
								<tr>
									<td>
									<p align="center">Questionamento:</td>
								</tr>
								<tr>
									<td>
									<p align="center"><b><?Echo $questao?> </b></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>

                                <tr>
									<td>
									<p align="center">Use o campo abaixo para
									responder ao questionamento!</td>
								</tr>
								<tr>
									<td>
									<form method="POST" id="form1" action="?action=vis_chamado.php&acao_int=rec_reg_quest_analista" onsubmit="return valida_dados(this)">
        								    <p align="center">
                                            <input type='hidden' name='id_chamado' value='<?echo $id_chamado?>'>
                                            <input type='hidden' name='id_questao' value='<?echo $id_questao?>'>

                            			<textarea rows="9" name="replica" cols="92" style="background-color: #FFFFFF"></textarea></p>
										<p align="center">
										<input type="submit" value="Enviar" name="B1"></p>
									</form>
									<p>&nbsp;</td>
								</tr>
							</table>
					</td>
				</tr>
				<form method="POST" action="?action=valida_enquete.php&acao_int=quest_analista_post" onsubmit='return valida_dados(this)'>
				</table>
			</form>
			</td>
		</tr>
	</table>
</div>
<?
}elseif($acao_int=="rec_reg_quest_usuario"){

$id_chamado=$_POST['id_chamado'];
$id_questao=$_POST['id_questao'];
$replica=$_POST['replica'];
$enquete=$_POST['enquete'];
$id_historico=tabelainfo($id_chamado,"sgc_historico_chamado","id_historico","id_chamado"," order by id_historico desc limit 1");
$nota_anterior=tabelainfo($id_chamado,"sgc_historico_chamado","nota_enquete","id_chamado"," and nota_enquete is not null order by id_historico desc limit 1");
$questao=tabelainfo($id_questao,"sgc_questionario_usuario","questao","id_questao"," ");
$autor_questao_email=tabelainfo(tabelainfo($id_questao,"sgc_questionario_usuario","autor_questao","id_questao"," "),"sgc_usuario","email","id_usuario","");
$autor_questao_nome=tabelainfo(tabelainfo($id_questao,"sgc_questionario_usuario","autor_questao","id_questao"," "),"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
$usuario_dono=tabelainfo(tabelainfo($id_chamado,"sgc_chamado","quem_criou","id_chamado"," "),"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");


if(tabelainfo($id_questao,"sgc_replica_questao_usuario","id_questao","id_questao","")!=$id_questao){


$cadas = mysql_query  ("INSERT INTO sgc_replica_questao_usuario
                             (id_usuario
                             ,id_questao
                             ,replica
                             ,nota
                             ,data)

                             VALUES

                             ($idusuario
                             ,$id_questao
                             ,'$replica'
                             ,$enquete
                             ,sysdate())
                             ");

                             print(mysql_error());

$cadas = mysql_query        ("UPDATE sgc_historico_chamado SET nota_enquete = $enquete WHERE id_historico = $id_historico");
         print(mysql_error());

if(atributo('atributo10')=="ON"){


$mensagem_g="<p><font face='Courier New'  size='2'>


************************** QUESTÃO RESPONDIDA *****************************<BR>
ID Chamado .........: $id_chamado<BR>
---------------------------------------------------------------------------<BR>
Nota anterior: $nota_anterior<BR>
Nova nota....: $enquete<BR>
Questão: <BR>$questao<BR>
---------------------------------------------------------------------------<BR>
O usuário....: $usuario_dono<BR>
Repondeu sua pergunta com a seguinte resposta:<BR>
<BR>$replica<BR>
---------------------------------------------------------------------------<BR>
</font></p>";

$email=send_mail_smtp("SISGAT - ANALISTA! Sua questão sobre o chamado #$id_chamado foi respondida",$mensagem_g,$mensagem_g,$autor_questao_email,$autor_questao_nome);



if(atributo('atributo10')=="ON"){

  $emails_gerencia = atributo('atributo24');
  $amails_gerencia = split ('[;]',$emails_gerencia);

  foreach($amails_gerencia as $valor){
  $nome_envio=tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","email","");
  $email=send_mail_smtp("SISGAT - A questão sobre o chamado #$id_chamado foi respondida",$mensagem_g,$mensagem_g,$valor,$nome_envio);
  }
 }
}

}

?>
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
  	<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Obrigado por ter respondido a questão :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">


                            <table border="0" width="100%">
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>
									<p align="center">Sua resposta foi enviada
									com sucesso !</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								</table>
					</td>
				</tr>

				</table>
			</form>
			</td>
		</tr>
	</table>
</div>
<?
session_unregister('url_questionar_usuario');


}elseif($acao_int=="rec_reg_quest_analista"){

$id_chamado=$_POST['id_chamado'];
$id_questao=$_POST['id_questao'];
$replica=$_POST['replica'];
$questao=tabelainfo($id_questao,"sgc_questionario_analista","questao","id_questao"," ");
$autor_questao_email=tabelainfo(tabelainfo($id_questao,"sgc_questionario_analista","id_analista","id_questao"," "),"sgc_usuario","email","id_usuario","");
$autor_questao_nome=tabelainfo(tabelainfo($id_questao,"sgc_questionario_analista","id_analitas","id_questao"," "),"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
$usuario_dono=tabelainfo(tabelainfo($id_chamado,"sgc_historico_chamado","id_suporte","id_chamado"," order by id_historico desc limit 1"),"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");

if(tabelainfo($id_questao,"sgc_replica_questao_analista","id_questao","id_questao"," ")!=$id_questao){

$cadas = mysql_query
                            ("INSERT INTO sgc_replica_questao_analista
                             (id_analista
                             ,id_questao
                             ,replica
                             ,data)

                             VALUES

                             ($idusuario
                             ,$id_questao
                             ,'$replica'
                             ,sysdate())
                             ");

                             print(mysql_error());
                             
if(atributo('atributo10')=="ON"){


$mensagem_g="<p><font face='Courier New'  size='2'>


************************** QUESTÃO RESPONDIDA *****************************<BR>
ID Chamado .........: $id_chamado<BR>
---------------------------------------------------------------------------<BR>
Questão: <BR>$questao<BR>
---------------------------------------------------------------------------<BR>
O Analista....: $usuario_dono<BR>
Repondeu sua pergunta com a seguinte resposta:<BR>
<BR>$replica<BR>
---------------------------------------------------------------------------<BR>
</font></p>";

$email=send_mail_smtp("SISGAT - USUÁRIO! Sua questão sobre o chamado #$id_chamado foi respondida",$mensagem_g,$mensagem_g,$autor_questao_email,$autor_questao_nome);



if(atributo('atributo10')=="ON"){

  $emails_gerencia = atributo('atributo24');
  $amails_gerencia = split ('[;]',$emails_gerencia);

  foreach($amails_gerencia as $valor){
  $nome_envio=tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","email","");
  $email=send_mail_smtp("SISGAT - A questão sobre o chamado #$id_chamado foi respondida",$mensagem_g,$mensagem_g,$valor,$nome_envio);
  }
 }
}

                             
                             
}
?>
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
  	<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Obrigado por ter respondido a questão :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">


                            <table border="0" width="100%">
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>
									<p align="center">Sua resposta foi enviada
									com sucesso !</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								</table>
					</td>
				</tr>

				</table>
			</form>
			</td>
		</tr>
	</table>
</div>
<?








session_unregister('url_questionar_analista');


}elseif($acao_int=="enquete"){


               session_unregister("url_finalizar");
               $_SESSION['url_finalizar'] = 1;
                    $url_finalizar = (isset($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
               session_register("url_finalizar") ;





$id_chamado=$_GET['id_chamado'];



?>
<form method="POST" id="form1" name='meuFormulario' enctype="multipart/form-data"  action="sgc.php?action=vis_chamado.php&acao_int=finalizar" >
	<table class="border" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
								<table border="0" cellpadding="5" cellspacing="1" width="100%">
        <tr>
										<td class="info" colspan="1" align="center">
										<b>Por favor dê sua opinião sobre o
										atendimento deste chamado - Chamado # <?Echo $id_chamado?><br>
										<font color="#FFFF00">Obs: O chamado
										somente será fechando quando respondido
										o questionário!<BR>ATENÇÃO<BR>Seu julgamento e opinião não será exposto para o analísta somente para sua gerência!</font></b></td>
									</tr>
									<tr>
										<td class="cat" align="right">
										<font size="1">
										<table border="0" width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												<p align="center"><?echo $titulo_chamado=tabelainfo($id_chamado,'sgc_chamado','titulo','id_chamado','');?></td>
												<td width="40">&nbsp;</td>
											</tr>
											<tr>
												<td width="43">&nbsp;</td>
												<td>

                                        				<table border="0" width="100%" cellspacing="0" cellpadding="0">
														<tr>
															<td width="23">
															<input type="radio" value="100" name="enquete"></td>
															<td>Ótimo</td>
														</tr>
                                                        <input type='hidden' name='id_chamado' value='<?echo $id_chamado?>'>
                                                        <tr>
															<td width="23">
															<input type="radio" value="75" name="enquete"></td>
															<td>Bom</td>
														</tr>
														<tr>
															<td width="23">
															<input type="radio" value="50" checked name="enquete"></td>
															<td>Regular</td>
														</tr>
														<tr>
															<td width="23">
															<input type="radio" value="0"  name="enquete"></td>
															<td>Ruim</td>
														</tr>
														<tr>
															<td width="23">&nbsp;</td>
															<td><b>Caso você tenha alguma opinião por favor descreva abaixo! (Não Obrigatório)</b></td>
														</tr>
														<tr>
															<td width="23">&nbsp;</td>
															<td>
															<textarea rows="7" name="obs" cols="100" style="background-color: #FFFFFF"></textarea></td>
														</tr>
														<tr>
															<td width="23">&nbsp;</td>
															<td>
															<input type="submit" value="OK" name="B1"></td>
														</tr>
													</table>

												</td>
												<td width="40">&nbsp;</td>
											</tr>
												<tr>
												<td width="43">&nbsp;</td>
												<td>
												<p align="center">
												<font color="#FF0000"><?echo $msg_fin?></font></td>
												<td width="40">&nbsp;</td>
											</tr>
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												&nbsp;</td>
												<td width="40">&nbsp;</td>
											</tr>
										</table>
										</td>
									</tr>
        						</table>
		    	    			</td>
			    			</tr>
						</table>
</form>
<?

}elseif($acao_int=="finalizar"){

session_unregister('url_finalizar');

$id_chamado=$_POST['id_chamado'];
$enquete=$_POST['enquete'];
$obs=$_POST['obs'];

$id_historico=ultimo_historico_chamado($id_chamado);
If($id_historico==null){

Echo "-------------------------------------------------------------------------------------------------<BR>";
Echo "Erro: Resultado da função 'ultimo_historico_chamado' é Nulo Verefique a linha do hitorico de chamado!";
Echo "<BR>-------------------------------------------------------------------------------------------------<BR>";
};

$enquete=$_POST['enquete'];
$obs=$_POST['obs'];

$cadas = mysql_query("UPDATE sgc_historico_chamado set nota_enquete=$enquete,obs_enquete = '$obs'   where id_historico = $id_historico") or print(mysql_error());

?>
	<table class="border" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
								<table border="0" cellpadding="5" cellspacing="1" width="100%">
									<tr>
										<td class="info" colspan="1" align="center">
										SUCESSO</td>
									</tr>
									<tr>
										<td class="cat" align="right">
										<font size="1">
										<table border="0" width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												&nbsp;</td>
												<td width="40">&nbsp;</td>
											</tr>
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												<p align="center">Obrigado!<br>
												Chamado FINALIZADO com sucesso.</td>
												<td width="40">&nbsp;</td>
											</tr>
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												&nbsp;</td>
												<td width="40">&nbsp;</td>
											</tr>
										</table>
										</td>
									</tr>
        						</table>
		    	    			</td>
			    			</tr>
						</table>
<?


}elseif($acao_int=="excluir_anexo"){

$id_anexo=$_GET['id_anexo'];
$id_chamado=$_GET['id_chamado'];
$url_chamado = $_GET['url_chamado'];

$param_9=tabelainfo($id_chamado,"sgc_chamado","id_usuario","id_chamado","");
$analista_ch=tabelainfo($id_chamado,"sgc_chamado","id_suporte","id_chamado","");
$id_chamado_anexo=tabelainfo($id_anexo,"sgc_anexo","id_chamado","id_anexo","");

$checa1 = mysql_query("SELECT atributo1 FROM sgc_parametros_sistema ") or print(mysql_error());
          while($dados1=mysql_fetch_array($checa1)){
            $iditematributo = $dados1['atributo1'];
          }
echo $acesso=acesso($idusuario,$iditematributo);

if($id_chamado_anexo==$id_chamado and $acesso=="OK" or $analista_ch==$idusuario){


 $checa = mysql_query("select * from sgc_anexo where id_anexo=$id_anexo") or print(mysql_error());
                                while($dados=mysql_fetch_array($checa)){
                                $nome_arquivo_or = $dados['nome_arquivo'];
                                $caminho = $dados['caminho'];
                                $versao = $dados['versao'];
 }
 
 $caminho="arquivos";
 $nome_arquivo="v$versao-$nome_arquivo_or";
 unlink("$caminho/$nome_arquivo");
 $deleta = mysql_query("DELETE FROM sgc_anexo where id_anexo=$id_anexo") or print(mysql_error());


 /*-----------------------Inserindo mensagem no chamado-----------------------*/
 $categoria1=tabelainfo($id_chamado,'sgc_historico_chamado','id_categoria','id_chamado',' order by id_historico desc limit 1 ');
          $situacao=tabelainfo($id_chamado,'sgc_historico_chamado','situacao','id_chamado',' order by id_historico desc limit 1 ');
 $visto_service_desk1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_service_desk','id_chamado',' order by id_historico desc limit 1 ');
 $id_service_desk1=tabelainfo($id_chamado,"sgc_historico_chamado","id_service_desk","id_chamado"," order by id_historico desc limit 1 ");
 $visto_suporte1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_suporte','id_chamado',' order by id_historico desc limit 1 ');
 $prioridade1=tabelainfo($id_chamado,"sgc_historico_chamado","prioridade","id_chamado", "order by id_historico desc limit 1");
 $analista1=tabelainfo($id_chamado,"sgc_historico_chamado","id_suporte","id_chamado"," order by id_historico desc limit 1");

 $texto="Arquivo Removido: $nome_arquivo_or";

 $cadas = mysql_query("INSERT INTO sgc_historico_chamado
                           (  id_chamado
                             ,situacao
                             ,acao
                             ,atualizacao
                             ,visto_service_desk
                             ,id_service_desk
                             ,visto_suporte
                             ,prioridade
                             ,id_suporte
                             ,quem_criou_linha
                             ,quem_criou
                             ,data_criacao
                             ,id_categoria
                             )

                              values

                             ( $id_chamado
                              ,'$situacao'
                              ,'$situacao'
                              ,'$texto'
                              ,'$visto_service_desk1'
                              , $id_service_desk1
                              , '$visto_suporte1'
                              , $prioridade1
                              , $analista1
                              , $idusuario
                              , $idusuario
                              , sysdate()
                              , $categoria1
                              )")or print(mysql_error());
/*---------------------------------------------------------------------------------*/





if(atributo('atributo10')=="ON"){
  $id_emails=expectadores($id_chamado);
  $nome_pro=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
  foreach($id_emails as $valor){

   $email=send_mail_smtp("SISGAT - Arquivo removido - # $id_chamado"
                         ,"O Arquivo: '$nome_arquivo_or', foi removido do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado&id_chamado=$id_chamado</font></a>"
                         ,"O Arquivo: '$nome_arquivo_or', foi removido do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado&id_chamado=$id_chamado</font></a>"
                         ,tabelainfo($valor,"sgc_usuario","email","id_usuario","")
                         ,tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );

  }
   $email=send_mail_smtp("SISGAT - Arquivo removido - # $id_chamado"
                         ,"O Arquivo: '$nome_arquivo_or', foi removido do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado&id_chamado=$id_chamado</font></a>"
                         ,"O Arquivo: '$nome_arquivo_or', foi removido do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado&id_chamado=$id_chamado</font></a>"
                         ,tabelainfo($idusuario,"sgc_usuario","email","id_usuario","")
                         ,tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );

  if($idusuario!=$param_9){

   $email=send_mail_smtp("SISGAT - Arquivo removido - # $id_chamado"
                         ,"O Arquivo: '$nome_arquivo_or', foi removido do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado&id_chamado=$id_chamado</font></a>"
                         ,"O Arquivo: '$nome_arquivo_or', foi removido do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado&id_chamado=$id_chamado</font></a>"
                         ,tabelainfo($param_9,"sgc_usuario","email","id_usuario","")
                         ,tabelainfo($param_9,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );
 }
 if($idusuario!=$analista){
   $email=send_mail_smtp("SISGAT - Arquivo removido - # $id_chamado"
                         ,"O Arquivo: '$nome_arquivo_or', foi removido do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado&id_chamado=$id_chamado</font></a>"
                         ,"O Arquivo: '$nome_arquivo_or', foi removido do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado&id_chamado=$id_chamado</font></a>"
                         ,tabelainfo($analista_ch,"sgc_usuario","email","id_usuario","")
                         ,tabelainfo($analista_ch,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );
  }
}

}else{

$nome=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
$email_nome_adm=atributo("atributo6");
list ($email_adm, $nome_adm) = split ('[;]',$email_nome_adm);

if(atributo('atributo10')=="ON"){





$email=send_mail_smtp("SISGAT - Tentativa de viloação de Sistema"
                     ,"<p align='center'>ATENÇÃO</p>
                       <p align='center'>O usuário: <b>$nome</b>, tentou excluir um arquivo que não faz parte
                       de sua coleção, devido a isso seu login foi bloqueado.</p>"
                     ,"<p align='center'>ATENÇÃO</p>
                       <p align='center'>O usuário: <b>$nome</b>, tentou excluir um arquivo que não faz parte
                       de sua coleção, devido a isso seu login foi bloqueado.</p>"
                     ,$email_adm,$nome_adm);

 }
}
$emails=$_POST['emails'];
$desc_objeto=$_POST['desc_objeto'];
$desc_objeto=ltrim("$desc_objeto");
$ajuda=$_POST['ajuda'];

$cadas = mysql_query("INSERT INTO sgc_mensagem
        (titulo, mensagem, data_criacao, quem_criou)
         VALUES
        ('$desc_objeto','$ajuda',sysdate(),$idusuario)") or print(mysql_error());

$id_mensagem=ultimo_registro('id_mensagem','sgc_mensagem','id_mensagem');
$id_emails = explode(",", $emails);

foreach($id_emails as $valor){
        $cadas = mysql_query("INSERT INTO sgc_usuarios_mensagens  (id_mensagem, id_usuario, data_criacao, quem_criou)
        VALUES
        ('$id_mensagem','$valor',sysdate(),$idusuario)") or print(mysql_error());
}



header("Location: ?action=vis_chamado.php&id_chamado=$id_chamado");

}


elseif($acao_int=="atualizar"){

            $id_chamado = $_POST['id_chamado'];
             $atualizar = $_POST['atualizar'];
    $atualizar_original = $_POST['atualizar'];
            $prioridade = $_POST['prioridade'];
              $situacao = $_POST['situacao'];
               $arquivo = $_FILE['arquivo'];
             $categoria = $_POST['categoria'];
           $url_chamado = $_POST['url_chamado'];
  $conjunto_selecionado = $_POST['conjunto_selecionado'];
              $previsao = $_POST['previsao'];
              $analista = $_POST['analista_change'];
                  $area = $_POST['area'];



  if($data_inicio_marc!=1){
         $data_inicio  = $_POST['data_inicio'];
         $data_final   = $_POST['data_final'];
         $hora_inicio  = $_POST['hora_inicio'];
         $hora_termino = $_POST['hora_termino'];
   }

//--------Caso o usuário que aceitar o chamado for diferente do atual dono do chamado automaticamente ele se tornará dono do chamado--------//

$analista_atual_chamado=tabelainfo($id_chamado,"sgc_chamado","id_suporte","id_chamado","");
$status_atual_chamado=tabelainfo($id_chamado,"sgc_chamado","status","id_chamado","");

//-----Corrigir bug de combom sem valor quando usuário não tem permissão para mudar o mesmo----//
if($prioridade==null){
    $prioridade=tabelainfo($id_chamado,'sgc_historico_chamado','prioridade','id_chamado',' order by id_historico desc limit 1 ');
}


//--------------//

if($analista_atual_chamado!=$analista and $analista!=$idusuario and $status_atual_chamado!=$situacao and $situacao=="Aceito - Em Andamento"){
   if(analista($idusuario)=="ANALISTA"){
       $analista=$idusuario;

   }
}
//$id_area_locacao_analista=tabelainfo($analista,"sgc_associacao_area_analista","id_area","id_analista"," AND desligamento is null");
  $id_area_locacao_analista=$area;
//-----------------------------------------------//


   $vetor_conjunto = explode(",", $conjunto_selecionado);
   count($conjunto_selecionado);


  /*-----------------------REMOVE USUARIO-------------------*/
   $countO=0;
   $checa = mysql_query("select * from sgc_contatos_por_chamado where id_chamado=$id_chamado ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                     $objeto[$countO] = $dados['id_usuario_contatar'];
                                     $countO++;
   }

   $deletar = array_diff($objeto, $vetor_conjunto);
   foreach( $deletar as $value){
      $cadas = mysql_query("DELETE FROM sgc_contatos_por_chamado WHERE id_chamado=$id_chamado and id_usuario_contatar=$value") or print(mysql_error());
   }
   /*------------------------------------------------------*/





 foreach($vetor_conjunto as $value){

      if($value!=null or $value>0){



      $email=tabelainfo($value,'sgc_usuario','email','id_usuario');
      $nome=tabelainfo($value,'sgc_usuario','primeiro_nome','id_usuario');



      $txtAssunto="SGC - Você é expectador do chamado: $id_chamado";
      $link=organizacao('link');

      $titulo=tabelainfo($id_chamado,"sgc_chamado","titulo","id_chamado","");
      $descricao=tabelainfo($id_chamado,"sgc_chamado","descricao","id_chamado","");


      $mensagem="<table border='0' width='100%' cellspacing='0' cellpadding='0'>
	             <tr>
           		 <td>
                 <p align='center'><b>O Chamado #&nbsp;$id_chamado  tem
            		você como expectador todas a atualizações serão enviadas para seu
        		e-mail.</b></td>
            	</tr>
             	<tr>
              	<td align='center'>Título:</td>
               	</tr>
                <tr>
                <td align='center'>".nl2br($titulo)."<BR></td>
                </tr>
                <tr>
                <td align='center'>Descrição:</td>
                </tr>
                <tr>
                <td align='center'>".nl2br($descricao)."</td>
                </tr>
                </table>
                <p align='center'><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>
                ";


                 $existe=integridade($id_chamado,"sgc_contatos_por_chamado","id_chamado","id_chamado"," and id_usuario_contatar=$value");

                 if($existe!="Existe"){

                   if(atributo('atributo10')=="ON"){

                       $env=send_mail_smtp($txtAssunto,$mensagem,$mensagem,$email,$nome);
                       
                   }
                   $cadas = mysql_query("INSERT INTO sgc_contatos_por_chamado
                                       ( id_chamado
                                       , id_usuario_contatar
                                       , id_usuario_chamado
                                       , data_criacao)
                                         VALUES ($id_chamado,$value,$idusuario,sysdate())") or print(mysql_error());
                  }
                  
                  
                  
                }
              }
 

 
 
 
 
 
 

  $param_1="IGUAL";     /*Situacao*/
  $param_2="IGUAL";     /*Atualicao*/
  $param_3="IGUAL";     /*Visto Service Desk*/
  $param_4="IGUAL";     /*ID Service Desk*/
  $param_5="IGUAL";     /*Visto Suporte*/
  $param_6="IGUAL";     /*Prioridade*/
  $param_7="IGUAL";     /*ID Analista*/
  $param_8="IGUAL";     /*ID Categoria*/
  $param_10="IGUAL";     /*Previsao*/



/*--------------------Situacao--------------------*/
if(tabelainfo($id_chamado,"sgc_historico_chamado","situacao","id_chamado"," order by id_historico desc limit 1")!=$situacao){
  $sql_campos = ",situacao";
  $sql_valor  = ",'$situacao'";
  $param_1    = "DIFE";

}else{

 $situacao=tabelainfo($situacao,"sgc_historico_chamado","situacao","situacao","and id_chamado=$id_chamado order by id_historico desc limit 1");
 $sql_campos = ",situacao";
 $sql_valor  = ",'$situacao'";
}

/*-----------------------------------------------*/
/*--------------------Categoria------------------*/
if(tabelainfo($id_chamado,"sgc_historico_chamado","id_categoria","id_chamado"," order by id_historico desc limit 1")!=$categoria){
  $sql_campos = $sql_campos. ",id_categoria";
  $sql_valor  = $sql_valor. ",$categoria";
  $param_8    = "DIFE";

}else{

 $categoria=tabelainfo($categoria,"sgc_historico_chamado","id_categoria","id_categoria","and id_chamado=$id_chamado order by id_historico desc limit 1");
 $sql_campos = ",id_categoria";
 $sql_valor  = ",$categoria";

}
/*-----------------------------------------------*/

/*--------------------Atualizacao----------------*/
if(strlen($atualizar)>0){
 $sql_campos= $sql_campos.",atualizacao";
 $sql_valor= $sql_valor.",'$atualizar'";
 $param_2="DIFE";
}
/*----------------------------------------------*/
/*-----------------Visto Service Desk-----------*/
if($visto_service_desk!=null){

  $visto_service_desk=tabelainfo($id_chamado,'sgc_historico_chamado','visto_service_desk','id_chamado','order by id_historico desc limit 1');

  $sql_campos= $sql_campos.",visto_service_desk";
  $sql_valor= $sql_valor.",'$visto_service_desk'";
  $param_3="DIFE";

}else{

 $visto_service_desk=tabelainfo($id_chamado,"sgc_historico_chamado","visto_service_desk","id_chamado"," order by id_historico desc limit 1");
 $sql_campos=$sql_campos.",visto_service_desk";
 $sql_valor=$sql_valor.",'$visto_service_desk'";

}
/*-----------------------------------------------*/

/*-------------------ID Service Desk-------------*/
if($id_service_desk!=null){

   $id_service_desk=tabelainfo($id_chamado,'sgc_historico_chamado','id_service_desk','id_chamado','order by id_historico desc limit 1');
   $sql_campos= $sql_campos.",id_service_desk";
   $sql_valor= $sql_valor.",$id_service_desk";
   $param_4="DIFE";

}else{

 $id_service_desk=tabelainfo($id_chamado,"sgc_historico_chamado","id_service_desk","id_chamado","order by id_historico desc limit 1");
 $sql_campos=$sql_campos.",id_service_desk";
 $sql_valor=$sql_valor.",$id_service_desk";
}
/*-----------------------------------------------*/

/*---------------Visto Suporte-------------*/
if($visto_suporte!=null){

  $visto_suporte=tabelainfo($id_chamado,'sgc_historico_chamado','visto_suporte','id_chamado','order by id_historico desc limit 1');
  $sql_campos= $sql_campos.",visto_suporte";
  $sql_valor= $sql_valor.",'$visto_suporte'";
  $param_5="DIFE";
}else{

 $visto_suporte=tabelainfo($id_chamado,"sgc_historico_chamado","visto_suporte","id_chamado","order by id_historico desc limit 1");
 $sql_campos=$sql_campos.",visto_suporte";
 $sql_valor=$sql_valor.",''";
}
/*-----------------------------------------------*/

/*--------------------Prioridade-----------------*/
if(tabelainfo($id_chamado,"sgc_historico_chamado","prioridade","id_chamado","order by id_historico desc limit 1")!=$prioridade){

   $sql_campos= $sql_campos.",prioridade";
   $sql_valor= $sql_valor.",$prioridade";
   $param_6="DIFE";

}else{

 $prioridade=tabelainfo($prioridade,"sgc_historico_chamado","prioridade","prioridade","and id_chamado=$id_chamado order by id_historico desc limit 1");
 $sql_campos=$sql_campos.",prioridade";
 $sql_valor=$sql_valor.",$prioridade";

}
/*-----------------------------------------------*/

/*--------------------ID Analista----------------*/
if(tabelainfo($id_chamado,"sgc_historico_chamado","id_suporte","id_chamado","order by id_historico desc limit 1")!=$analista){

   $sql_campos = $sql_campos.",id_suporte";
   $sql_valor= $sql_valor.",$analista";
   $param_7="DIFE";

}else{

 $analista=tabelainfo($analista,"sgc_historico_chamado","id_suporte","id_suporte","and id_chamado=$id_chamado order by id_historico desc limit 1");
 $sql_campos=$sql_campos.",id_suporte";
 $sql_valor=$sql_valor.",$analista";

}

/*-----------------------------------------------*/


  



$param_9=tabelainfo($id_chamado,"sgc_chamado","id_usuario","id_chamado","");

echo "   1 ...............Situação - "; echo $param_1; echo "<BR>";
echo "   2 ............Atualização - "; echo $param_2; echo "<BR>";
echo " * 3 .....Visto Service Desk - "; echo $param_3; echo "<BR>";
echo " * 4 ........ID Service Desk - "; echo $param_4; echo "<BR>";
echo " * 5 ..........Visto Suporte - "; echo $param_5; echo "<BR>";
echo "   6 .............Prioridade - "; echo $param_6; echo "<BR>";
echo "   7 ............ID Analista - "; echo $param_7; echo "<BR>";
echo "   8 ...........ID Categoria - "; echo $param_8; echo "<BR>";
echo "   9 ID Proprietario chamado - "; echo $param_9; echo "<BR>";
echo "   10 ...............Previão - "; echo $param_10; echo "<BR>";



     $count=0;
     if($_FILES['arquivo']['size'] > 0)
     {

     $fileName_original = $_FILES['arquivo']['name'];
     $tmpName_original  = $_FILES['arquivo']['tmp_name'];
     $fileSize_original = $_FILES['arquivo']['size'];
     $fileType_original = $_FILES['arquivo']['type'];

     $checa = mysql_query("select * from sgc_anexo where nome_arquivo='$fileName_original'") or print(mysql_error());
     while($dados=mysql_fetch_array($checa)){
     $nome_arquivo = $dados['nome_arquivo'];
     $versao = $dados['versao'];
     $count++;
     }

     if($count==0){

        $versao=1;
        $nome_com_versao="v$versao-$fileName_original";
     }else{

        $versao=$versao+1;
        $nome_com_versao="v$versao-$fileName_original";
     }
     $cadas = mysql_query("INSERT INTO sgc_anexo
                          ( id_chamado
                          , caminho
                          , nome_arquivo
                          , tipo_arquivo
                          , data_cadastro
                          , versao
                          , tamanho
                          )
                           VALUES ($id_chamado,'atributo5','$fileName_original','$fileType_original',sysdate(),'$versao',$fileSize_original)") or print(mysql_error());

      copy($tmpName_original, "arquivos/$nome_com_versao");
      
             $categoria1=tabelainfo($id_chamado,'sgc_historico_chamado','id_categoria','id_chamado',' order by id_historico desc limit 1 ');
             $situacao1=tabelainfo($id_chamado,'sgc_historico_chamado','situacao','id_chamado',' order by id_historico desc limit 1 ');
 $visto_service_desk1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_service_desk','id_chamado',' order by id_historico desc limit 1 ');
 $id_service_desk1=tabelainfo($id_chamado,"sgc_historico_chamado","id_service_desk","id_chamado"," order by id_historico desc limit 1 ");
 $visto_suporte1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_suporte','id_chamado',' order by id_historico desc limit 1 ');
 $prioridade1=tabelainfo($id_chamado,"sgc_historico_chamado","prioridade","id_chamado", "order by id_historico desc limit 1");
 $analista1=tabelainfo($id_chamado,"sgc_historico_chamado","id_suporte","id_chamado"," order by id_historico desc limit 1");

 $texto="Arquivo anexado: $fileName_original";
 
 $cadas = mysql_query("INSERT INTO sgc_historico_chamado
                           (  id_chamado
                             ,situacao
                             ,acao
                             ,atualizacao
                             ,visto_service_desk
                             ,id_service_desk
                             ,visto_suporte
                             ,prioridade
                             ,id_suporte
                             ,quem_criou_linha
                             ,quem_criou
                             ,data_criacao
                             ,id_categoria
                             )

                              values

                             ( $id_chamado
                              ,'$situacao1'
                              ,'$situacao1'
                              ,'$texto'
                              ,'$visto_service_desk1'
                              , $id_service_desk1
                              , '$visto_suporte1'
                              , $prioridade1
                              , $analista1
                              , $idusuario
                              , $idusuario
                              , sysdate()
                              , $categoria1
                              )")or print(mysql_error());
      

     if(atributo('atributo10')=="ON"){
      $id_emails=expectadores($id_chamado);
       $nome_pro=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
     foreach($id_emails as $valor){


      $email=send_mail_smtp("SISGAT - Arquivo Anexado - # $id_chamado"
                         ,"O Arquivo: $fileName_original, foi anexado do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O Arquivo: $fileName_original, foi anexado do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($valor,"sgc_usuario","email","id_usuario","")
                         ,tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );
     }



       $email=send_mail_smtp("SISGAT - Arquivo Anexado - # $id_chamado"
                         ,"O Arquivo: $fileName_original, foi anexado do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O Arquivo: $fileName_original, foi anexado do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($idusuario,"sgc_usuario","email","id_usuario","")
                         ,tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );


       if($idusuario!=$param_9){
       
          $email=send_mail_smtp("SISGAT - Arquivo Anexado - # $id_chamado"
                         ,"O Arquivo: $fileName_original, foi anexado do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O Arquivo: $fileName_original, foi anexado do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($param_9,"sgc_usuario","email","id_usuario","")
                         ,tabelainfo($param_9,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );
       }
       if($idusuario!=$analista){
          $email=send_mail_smtp("SISGAT - Arquivo Anexado - # $id_chamado"
                         ,"O Arquivo: $fileName_original, foi anexado do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O Arquivo: $fileName_original, foi anexado do chamado #$id_chamado, pelo usuário: $nome_pro <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($analista,"sgc_usuario","email","id_usuario","")
                         ,tabelainfo($analista,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
          );
       
       

       }
     }








     }







if($data_inicio != null and $data_final != null and $data_inicio_marc != 1 ){

 $categoria1=tabelainfo($id_chamado,'sgc_historico_chamado','id_categoria','id_chamado',' order by id_historico desc limit 1 ');
 $visto_service_desk1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_service_desk','id_chamado',' order by id_historico desc limit 1 ');
 $id_service_desk1=tabelainfo($id_chamado,"sgc_historico_chamado","id_service_desk","id_chamado"," order by id_historico desc limit 1 ");
 $visto_suporte1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_suporte','id_chamado',' order by id_historico desc limit 1 ');
 $prioridade1=tabelainfo($id_chamado,"sgc_historico_chamado","prioridade","id_chamado", "order by id_historico desc limit 1");
 $analista1=tabelainfo($id_chamado,"sgc_historico_chamado","id_suporte","id_chamado"," order by id_historico desc limit 1");

 $nome=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");

 $data_inicio =databd($data_inicio);
 $data_final =databd($data_final); echo "<BR>";

 $inicio_trabalho = $data_inicio." ".$hora_inicio.":00"; echo "<BR>";
 $final_trabalho = $data_final." ".$hora_termino.":00"; echo "<BR>";


$cadas = mysql_query("select TIMEDIFF('$final_trabalho','$inicio_trabalho') DIFE , TIME_TO_SEC(TIMEDIFF('$final_trabalho','$inicio_trabalho')) DIFE_SEC from dual")or print(mysql_error());
         while($dados=mysql_fetch_array($cadas )){
          $total_horas = $dados['DIFE'];
          $total_horas_sec = $dados['DIFE_SEC'];
          }


 $atualizar="Inserido horas de trabalho: $inicio_trabalho - $final_trabalho - Total de: $total_horas";

 $cadas = mysql_query("INSERT INTO sgc_historico_chamado
                           (  id_chamado
                             ,situacao
                             ,acao
                             ,atualizacao
                             ,visto_service_desk
                             ,id_service_desk
                             ,visto_suporte
                             ,prioridade
                             ,id_suporte
                             ,quem_criou_linha
                             ,quem_criou
                             ,data_criacao
                             ,id_categoria
                             ,inicio_trabalho
                             ,final_trabalho
                             )

                              values

                             ( $id_chamado
                              ,'$situacao'
                              ,'$situacao'
                              ,'$atualizar'
                              ,'$visto_service_desk1'
                              , $id_service_desk1
                              , '$visto_suporte1'
                              , $prioridade1
                              , $analista1
                              , $idusuario
                              , $idusuario
                              , sysdate()
                              , $categoria1
                              , '$inicio_trabalho'
                              , '$final_trabalho'
                              )")or print(mysql_error());


 $tempo_gasto=tabelainfo($id_chamado,'sgc_chamado','tempo_gasto','id_chamado','');

 $tempo_gasto=$tempo_gasto+$total_horas_sec;

 $ultimo_hist=ultimo_historico($id_chamado);

 $cadas = mysql_query("UPDATE sgc_chamado set status = '$situacao', tempo_gasto='$tempo_gasto', id_linha_historico=$ultimo_hist where id_chamado = $id_chamado") or print(mysql_error());


 if(atributo('atributo10')=="ON"){
      $id_emails=expectadores($id_chamado);




$checa_local = mysql_query("
SELECT concat(dp.descricao,' - ', un.descricao,' - ',un.sigla )DESC_LOCAL,SYSDATE() DATE FROM
  sgc_usuario us
, sgc_unidade un
, sgc_departamento dp
WHERE  us.id_usuario = $idusuario
and us.id_departamento = dp.id_departamento
and un.codigo = us.id_unidade
") or print(mysql_error());
                  while($dados_local=mysql_fetch_array($checa_local)){
                  $desc_local= $dados_local['DESC_LOCAL'];

}
$data_g = data_with_hour(datahoje("datahora"));
$prioridade_g=tabelainfo($prioridade1,"sgc_sla_analista_usuario","descricao","id_sla_analista","");
$nome_usuario_g=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
$mensagem_g="<p><font face='Courier New'  size='2'>
*************************** CHAMADO ATUALIZADO ****************************<BR>
Atualizado por......: $nome_usuario_g <BR>
Local...............: $desc_local <BR>
ID Chamado .........: $id_chamado<BR>
Data de Atualização.: $data_g<BR>
---------------------------------------------------------------------------<BR>
Prioridade..........: $prioridade_g<BR>
---------------------------------------------------------------------------<BR>

$atualizar<BR>
<BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>
---------------------------------------------------------------------------<BR>
</font></p>";


foreach($id_emails as $valor){





       $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"$mensagem_g"
                         ,"$mensagem_g"
                         ,tabelainfo($valor,"sgc_usuario","email","id_usuario","")
                         ,tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );

     }

         $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"$mensagem_g"
                         ,"$mensagem_g"
                         ,tabelainfo($idusuario,"sgc_usuario","email","id_usuario","")
                         ,tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
 );




       if($idusuario!=$param_9){

          $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"O chamado #$id_chamado, foi atualizado pelo usuário: $nome <BR> '$atualizar' <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, foi atualizado pelo usuário: $nome <BR> '$atualizar' <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($param_9,"sgc_usuario","email","id_usuario","")
                         ,tabelainfo($param_9,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")

                         );
      }
      if($idusuario!=$analista){
          $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"O chamado #$id_chamado, foi atualizado pelo usuário: $nome <BR> '$atualizar' <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, foi atualizado pelo usuário: $nome <BR> '$atualizar' <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($analista,"sgc_usuario","email","id_usuario","")
                         ,tabelainfo($analista,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")

                         );

       }
   }
  $data_inicio_marc=1;
}


if($param_1=="DIFE"){

 $categoria1=tabelainfo($id_chamado,'sgc_historico_chamado','id_categoria','id_chamado',' order by id_historico desc limit 1 ');
 $visto_service_desk1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_service_desk','id_chamado',' order by id_historico desc limit 1 ');
 $id_service_desk1=tabelainfo($id_chamado,"sgc_historico_chamado","id_service_desk","id_chamado"," order by id_historico desc limit 1 ");
 $visto_suporte1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_suporte','id_chamado',' order by id_historico desc limit 1 ');
 $prioridade1=tabelainfo($id_chamado,"sgc_historico_chamado","prioridade","id_chamado", "order by id_historico desc limit 1");
 $analista1=tabelainfo($id_chamado,"sgc_historico_chamado","id_suporte","id_chamado"," order by id_historico desc limit 1");

 $situacao_anterior=tabelainfo($id_chamado,"sgc_historico_chamado","situacao","id_chamado"," order by id_historico desc limit 1");

 if($situacao_anterior=="Fechado" and $situacao=="Aceito - Em Andamento"){
    $atualiza = mysql_query("UPDATE sgc_historico_chamado SET nota_enquete = null, obs_enquete = null WHERE id_chamado =$id_chamado ")or print(mysql_error());
 }



 $texto="Situacao alterada para: $situacao";

 $cadas = mysql_query("INSERT INTO sgc_historico_chamado
                           (  id_chamado
                             ,situacao
                             ,acao
                             ,atualizacao
                             ,visto_service_desk
                             ,id_service_desk
                             ,visto_suporte
                             ,prioridade
                             ,id_suporte
                             ,quem_criou_linha
                             ,quem_criou
                             ,data_criacao
                             ,id_categoria
                             )

                              values

                             ( $id_chamado
                              ,'$situacao'
                              ,'$situacao'
                              ,'$texto'
                              ,'$visto_service_desk1'
                              , $id_service_desk1
                              , '$visto_suporte1'
                              , $prioridade1
                              , $analista1
                              , $idusuario
                              , $idusuario
                              , sysdate()
                              , $categoria1
                              )")or print(mysql_error());

 $ultimo_registro=ultimo_registro("id_historico","sgc_historico_chamado","id_historico");
 $cadas = mysql_query("UPDATE sgc_chamado set status = '$situacao' where id_chamado = $id_chamado") or print(mysql_error());
 $cadas = mysql_query("UPDATE sgc_chamado set id_linha_historico = $ultimo_registro where id_chamado = $id_chamado") or print(mysql_error());


 $cadas = mysql_query("UPDATE sgc_chamado set definir_status = sysdate() where id_chamado = $id_chamado") or print(mysql_error());




if(atributo('atributo10')=="ON"){
      $id_emails=expectadores($id_chamado);
      $nome=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");


     $checa_local = mysql_query("
     SELECT concat(dp.descricao,' - ', un.descricao,' - ',un.sigla )DESC_LOCAL,SYSDATE() DATE FROM
     sgc_usuario us
     , sgc_unidade un
     , sgc_departamento dp
     WHERE  us.id_usuario = $idusuario
     and us.id_departamento = dp.id_departamento
     and un.codigo = us.id_unidade
     ") or print(mysql_error());
                  while($dados_local=mysql_fetch_array($checa_local)){
                  $desc_local= $dados_local['DESC_LOCAL'];

     }
     $data_g = data_with_hour(datahoje("datahora"));
     $prioridade_g=tabelainfo($prioridade1,"sgc_sla_analista_usuario","descricao","id_sla_analista","");
     $titulo=tabelainfo($id_chamado,"sgc_chamado","titulo","id_chamado","");


$mensagem_g="<p><font face='Courier New'  size='2'>
*************************** STATUS ALTERADO ****************************<BR>
Aalterado por.......: $nome <BR>
Local...............: $desc_local <BR>
Titulo:.............: $titulo <BR>
ID Chamado .........: $id_chamado<BR>
Data de Atualização.: $data_g<BR>
------------------------------------------------------------------------<BR>
Foi alterado para...: $situacao<BR>
------------------------------------------------------------------------<BR>
$atualizar<BR>
<BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>
------------------------------------------------------------------------<BR>
</font></p>";








      foreach($id_emails as $valor){


       $email=send_mail_smtp("SISGAT - Status alterado - # $id_chamado"
                         ,"$mensagem_g"
                         ,"$mensagem_g"
                         ,tabelainfo($valor,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );

              }




              $email=send_mail_smtp("SISGAT - Status alterado - # $id_chamado"
                         ,"$mensagem_g"
                         ,"$memsagem_g"
                         ,tabelainfo($idusuario,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );






       if($idusuario!=$param_9){
              $email=send_mail_smtp("SISGAT - Status alterado - # $id_chamado"
                         ,"$mensagem_g"
                         ,"$mensagem_g"
                         ,tabelainfo($param_9,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($param_9,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );
       }
       if($idusuario!=$analista){
        $email=send_mail_smtp("SISGAT - Status alterado - # $id_chamado"
                         ,"$mensagem_g"
                         ,"$mensagem_g"
                         ,tabelainfo($analista,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($analista,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );

       }
    }












}


if($param_2=="DIFE"){



            $categoria1=tabelainfo($id_chamado,'sgc_historico_chamado','id_categoria','id_chamado',' order by id_historico desc limit 1 ');
             $situacao1=tabelainfo($id_chamado,'sgc_historico_chamado','situacao','id_chamado',' order by id_historico desc limit 1 ');
 $visto_service_desk1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_service_desk','id_chamado',' order by id_historico desc limit 1 ');
 $id_service_desk1=tabelainfo($id_chamado,"sgc_historico_chamado","id_service_desk","id_chamado"," order by id_historico desc limit 1 ");
 $visto_suporte1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_suporte','id_chamado',' order by id_historico desc limit 1 ');
 $prioridade1=tabelainfo($id_chamado,"sgc_historico_chamado","prioridade","id_chamado", "order by id_historico desc limit 1");
 $analista1=tabelainfo($id_chamado,"sgc_historico_chamado","id_suporte","id_chamado"," order by id_historico desc limit 1");

 $id_dono_chamado=id_dono_chamado($id_chamado);

 if($situacao1=="Aguardando Resposta - Usuário" && $id_dono_chamado==$idusuario){
    $situacao1="Aceito - Em Andamento";
 }
 
 $cadas = mysql_query("INSERT INTO sgc_historico_chamado
                           (  id_chamado
                             ,situacao
                             ,acao
                             ,atualizacao
                             ,visto_service_desk
                             ,id_service_desk
                             ,visto_suporte
                             ,prioridade
                             ,id_suporte
                             ,quem_criou_linha
                             ,quem_criou
                             ,data_criacao
                             ,id_categoria
                             )

                              values

                             ( $id_chamado
                              ,'$situacao1'
                              ,'$situacao1'
                              ,'$atualizar_original'
                              ,'$visto_service_desk1'
                              , $id_service_desk1
                              , '$visto_suporte1'
                              , $prioridade1
                              , $analista1
                              , $idusuario
                              , $idusuario
                              , sysdate()
                              , $categoria1
                              )")or print(mysql_error());



 if(atributo('atributo10')=="ON"){
      $id_emails=expectadores($id_chamado);
      
      
$checa_local = mysql_query("
SELECT concat(dp.descricao,' - ', un.descricao,' - ',un.sigla )DESC_LOCAL,SYSDATE() DATE FROM
  sgc_usuario us
, sgc_unidade un
, sgc_departamento dp
WHERE  us.id_usuario = $idusuario
and us.id_departamento = dp.id_departamento
and un.codigo = us.id_unidade
") or print(mysql_error());
                  while($dados_local=mysql_fetch_array($checa_local)){
                  $desc_local= $dados_local['DESC_LOCAL'];

}
$data_g = data_with_hour(datahoje("datahora"));
$prioridade_g=tabelainfo($prioridade1,"sgc_sla_analista_usuario","descricao","id_sla_analista","");
$nome_usuario_g=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
$mensagem_g="<p><font face='Courier New'  size='2'>
*************************** CHAMADO ATUALIZADO ****************************<BR>
Atualizado por......: $nome_usuario_g <BR>
Local...............: $desc_local <BR>
ID Chamado .........: $id_chamado<BR>
Data de Atualização.: $data_g<BR>
---------------------------------------------------------------------------<BR>
Prioridade..........: $prioridade_g<BR>
---------------------------------------------------------------------------<BR>

$atualizar<BR>
<BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>
---------------------------------------------------------------------------<BR>
</font></p>";
      
      
      

     foreach($id_emails as $valor){
       $nome=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");


       $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"$mensagem_g"
                         ,"$mensagem_g"
                         ,tabelainfo($valor,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );

              }




       $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"$mensagem_g"
                         ,"$mensagem_g"
                         ,tabelainfo($idusuario,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );





if($idusuario!=$param_9){

                $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"O chamado #$id_chamado, foi atualizado pelo usuário: $nome <BR> '$atualizar' <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, foi atualizado pelo usuário: $nome <BR> '$atualizar' <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($param_9,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($param_9,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );

        }
        if($idusuario!=$analista){
                $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"O chamado #$id_chamado, foi atualizado pelo usuário: $nome <BR> '$atualizar' <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, foi atualizado pelo usuário: $nome <BR> '$atualizar' <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($analista,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($analista,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );

       }

    }
  }
if($param_6=="DIFE"){

             $categoria1=tabelainfo($id_chamado,'sgc_historico_chamado','id_categoria','id_chamado',' order by id_historico desc limit 1 ');
             $situacao1=tabelainfo($id_chamado,'sgc_historico_chamado','situacao','id_chamado',' order by id_historico desc limit 1 ');
 $visto_service_desk1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_service_desk','id_chamado',' order by id_historico desc limit 1 ');
 $id_service_desk1=tabelainfo($id_chamado,"sgc_historico_chamado","id_service_desk","id_chamado"," order by id_historico desc limit 1 ");
 $visto_suporte1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_suporte','id_chamado',' order by id_historico desc limit 1 ');
 $analista1=tabelainfo($id_chamado,"sgc_historico_chamado","id_suporte","id_chamado"," order by id_historico desc limit 1");

 $prioridade_desc=tabelainfo($prioridade,"sgc_sla_analista_usuario","descricao","id_sla_analista","");
 
 $texto="Prioridade alterada para: $prioridade_desc";

 $cadas = mysql_query("INSERT INTO sgc_historico_chamado
                           (  id_chamado
                             ,situacao
                             ,acao
                             ,atualizacao
                             ,visto_service_desk
                             ,id_service_desk
                             ,visto_suporte
                             ,prioridade
                             ,id_suporte
                             ,quem_criou_linha
                             ,quem_criou
                             ,data_criacao
                             ,id_categoria
                             )

                              values

                             ( $id_chamado
                              ,'$situacao'
                              ,'$situacao1'
                              ,'$texto'
                              ,'$visto_service_desk1'
                              , $id_service_desk1
                              , '$visto_suporte1'
                              , $prioridade
                              , $analista1
                              , $idusuario
                              , $idusuario
                              , sysdate()
                              , $categoria1
                              )")or print(mysql_error());
                              
                              
if(atributo('atributo10')=="ON"){
      $id_emails=expectadores($id_chamado);
      $prioridade_descricao=tabelainfo($prioridade,"sgc_sla_analista_usuario","descricao","id_sla_analista","");
      $nome=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
      
     foreach($id_emails as $valor){


       $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"O chamado #$id_chamado, teve sua prioridade modificada para: $prioridade_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, teve sua prioridade modificada para: $prioridade_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($valor,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );

              }




              $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"O chamado #$id_chamado, teve sua prioridade modificada para: $prioridade_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, teve sua prioridade modificada para: $prioridade_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($idusuario,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );






       if($idusuario!=$param_9){
              $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"O chamado #$id_chamado, teve sua prioridade modificada para: $prioridade_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, teve sua prioridade modificada para: $prioridade_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($param_9,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($param_9,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );
       }
       if($idusuario!=$analista){
              $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"O chamado #$id_chamado, teve sua prioridade modificada para: $prioridade_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, teve sua prioridade modificada para: $prioridade_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($analista,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($analista,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );

       }
    }
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              


}
if($param_7=="DIFE"){

             $categoria1=tabelainfo($id_chamado,'sgc_historico_chamado','id_categoria','id_chamado',' order by id_historico desc limit 1 ');
             $situacao1=tabelainfo($id_chamado,'sgc_historico_chamado','situacao','id_chamado',' order by id_historico desc limit 1 ');
 $visto_service_desk1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_service_desk','id_chamado',' order by id_historico desc limit 1 ');
 $id_service_desk1=tabelainfo($id_chamado,"sgc_historico_chamado","id_service_desk","id_chamado"," order by id_historico desc limit 1 ");
 $visto_suporte1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_suporte','id_chamado',' order by id_historico desc limit 1 ');
 $prioridade1=tabelainfo($id_chamado,"sgc_historico_chamado","prioridade","id_chamado", "order by id_historico desc limit 1");


 $analista_nome=tabelainfo($analista,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");

 $texto="Chamado transferido para: $analista_nome";

 if($situacao1!="Concluido" or $situacao1!="Fechado"){
    $situacao1="Enviado Para Analista";
 }
 
 

 $cadas = mysql_query("INSERT INTO sgc_historico_chamado
                           (  id_chamado
                             ,situacao
                             ,acao
                             ,atualizacao
                             ,visto_service_desk
                             ,id_service_desk
                             ,visto_suporte
                             ,prioridade
                             ,id_suporte
                             ,quem_criou_linha
                             ,quem_criou
                             ,data_criacao
                             ,id_categoria
                             ,id_area_locacao
                             )

                              values

                             ( $id_chamado
                              ,'$situacao1'
                              ,'$situacao1'
                              ,'$texto'
                              ,'$visto_service_desk1'
                              , $id_service_desk1
                              , '$visto_suporte1'
                              , $prioridade1
                              , $analista
                              , $idusuario
                              , $idusuario
                              , sysdate()
                              , $categoria1
                              , $area
                              )")or print(mysql_error());

 $ultimo_registro=ultimo_registro("id_historico","sgc_historico_chamado","id_historico");
 $cadas = mysql_query("UPDATE sgc_chamado set id_linha_historico = $ultimo_registro, status='$situacao1' where id_chamado = $id_chamado") or print(mysql_error());
 $cadas = mysql_query("UPDATE sgc_chamado set id_suporte = $analista , id_area_locacao= $id_area_locacao_analista where id_chamado = $id_chamado") or print(mysql_error());



if(atributo('atributo10')=="ON"){
      $id_emails=expectadores($id_chamado);
      $prioridade_descricao=tabelainfo($prioridade,"sgc_sla_analista_usuario","descricao","id_sla_analista","");
      $nome=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");

     foreach($id_emails as $valor){


       $email=send_mail_smtp("SISGAT - Chamado Transferido - # $id_chamado "
                         ,"O chamado #$id_chamado, foi transferido para: $analista_nome<BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, foi transferido para: $analista_nome<BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($valor,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );

              }






             $email=send_mail_smtp("SISGAT - Chamado Transferido para você - # $id_chamado "
                         ,"O chamado #$id_chamado, foi transferido para: $analista_nome<BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, foi transferido para: $analista_nome<BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($analista,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($analista,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );






       if($idusuario!=$param_9){
                    $email=send_mail_smtp("SISGAT - Chamado Transferido - # $id_chamado "
                         ,"O chamado #$id_chamado, foi transferido para: $analista_nome<BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, foi transferido para: $analista_nome<BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($param_9,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($param_9,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );

         $email=send_mail_smtp("SISGAT - Chamado Transferido - # $id_chamado "
                         ,"O chamado #$id_chamado, foi transferido para: $analista_nome<BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, foi transferido para: $analista_nome<BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($idusuario,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );



       }

}








}
if($param_8=="DIFE"){

             $situacao1=tabelainfo($id_chamado,'sgc_historico_chamado','situacao','id_chamado',' order by id_historico desc limit 1 ');
 $visto_service_desk1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_service_desk','id_chamado',' order by id_historico desc limit 1 ');
 $id_service_desk1=tabelainfo($id_chamado,"sgc_historico_chamado","id_service_desk","id_chamado"," order by id_historico desc limit 1 ");
 $visto_suporte1=tabelainfo($id_chamado,'sgc_historico_chamado','visto_suporte','id_chamado',' order by id_historico desc limit 1 ');
 $prioridade1=tabelainfo($id_chamado,"sgc_historico_chamado","prioridade","id_chamado", "order by id_historico desc limit 1");
 $analista1=tabelainfo($id_chamado,"sgc_historico_chamado","id_suporte","id_chamado"," order by id_historico desc limit 1");

 $cate_desc=tabelainfo($categoria,"sgc_categoria","descricao","id_categoria","");

 $texto="Categoria alterada para: $cate_desc";


 $cadas = mysql_query("INSERT INTO sgc_historico_chamado
                           (  id_chamado
                             ,situacao
                             ,acao
                             ,atualizacao
                             ,visto_service_desk
                             ,id_service_desk
                             ,visto_suporte
                             ,prioridade
                             ,id_suporte
                             ,quem_criou_linha
                             ,quem_criou
                             ,data_criacao
                             ,id_categoria
                             )

                              values

                             ( $id_chamado
                              ,'$situacao1'
                              ,'$situacao1'
                              ,'$texto'
                              ,'$visto_service_desk1'
                              , $id_service_desk1
                              , '$visto_suporte1'
                              , $prioridade1
                              , $analista1
                              , $idusuario
                              , $idusuario
                              , sysdate()
                              , $categoria
                              )")or print(mysql_error());

 $ultimo_registro=ultimo_registro("id_historico","sgc_historico_chamado","id_historico");
 $cadas = mysql_query("UPDATE sgc_chamado set id_linha_historico = $ultimo_registro where id_chamado = $id_chamado") or print(mysql_error());
 $cadas = mysql_query("UPDATE sgc_chamado set id_suporte = $analista where id_chamado = $id_chamado") or print(mysql_error());



 if(atributo('atributo10')=="ON"){
      $id_emails=expectadores($id_chamado);
      $categoria_descricao=tabelainfo($categoria,"sgc_categoria","descricao","id_categoria","");
      $nome=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");

     foreach($id_emails as $valor){


       $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"O chamado #$id_chamado, teve sua categoria modificada para: $categoria_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, teve sua categoria modificada para: $categoria_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($valor,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );

              }




              $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"O chamado #$id_chamado, teve sua categoria modificada para: $categoria_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, teve sua categoria modificada para: $categoria_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($idusuario,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );






       if($idusuario!=$param_9){
              $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"O chamado #$id_chamado, teve sua categoria modificada para: $categoria_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, teve sua categoria modificada para: $categoria_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($param_9,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($param_9,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );
        }
        if($idusuario!=$analista){
               $email=send_mail_smtp("SISGAT - Chamado atualizado - # $id_chamado"
                         ,"O chamado #$id_chamado, teve sua categoria modificada para: $categoria_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,"O chamado #$id_chamado, teve sua categoria modificada para: $categoria_descricao, usuário: $nome <BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a>"
                         ,tabelainfo($analista,'sgc_usuario','email','id_usuario','')
                         ,tabelainfo($analista,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
               );

       }
    }






}

if($situacao=="Fechado" and $param_1=="DIFE"){
 header("Location: ?action=vis_chamado.php&acao_int=enquete&id_chamado=$id_chamado&msg=$msg");
}




if($param_1=="IGUAL" and $param_2=="IGUAL" and $param_3=="IGUAL" and $param_4=="IGUAL" and $param_5=="IGUAL" and $param_6=="IGUAL" and $param_7=="IGUAL" and $param_8=="IGUAL"){

 header("Location: ?action=vis_chamado.php&id_chamado=$id_chamado&msg=$msg");

exit;

}else{

 $ultimo_registro=ultimo_registro("id_historico","sgc_historico_chamado","id_historico");
 $cadas = mysql_query("UPDATE sgc_chamado set id_categoria=$categoria, id_linha_historico= $ultimo_registro where id_chamado = $id_chamado") or print(mysql_error());
 header("Location: ?action=vis_chamado.php&id_chamado=$id_chamado&msg=$msg");

}




   }
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
