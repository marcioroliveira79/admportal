<?php
OB_START();
session_start();
include("conf/conecta.php");
include("conf/funcs.php");
require_once("conf/class_VALIDATE.php");

$validate = new VALIDATE;

$mysql=new sgc;
       $mysql->conectar();
/*
$pg=new sgc_nfe;
      $pg->conectar_nfe();
*/

$acao_int=$_GET['acao_int'];


//------------------------------------------------------------//


          $mensage = $_SESSION['mensage'];
$permissao         = $_SESSION['permissao_global'];
$responsabilidade  = $_SESSION['responsabilidade_global'];
$idusuario         = $_SESSION['id_usuario_global'];
$iplogon           = get_real_ip();
$ldap_departamento = $_SESSION['ldap_departamento'];
$analista_suporte  = analista($idusuario);
          $perfil  = verperfil($idusuario);
     $perfil_desc  = verperfil($idusuario);
       $perfil_id  = verperfil_id($idusuario);
$id_unidade_usuario= unidade_usuario($idusuario);
       
/*
if($perfil=="CUSTOMIZADO"){
echo "  $mensage";
}
*/
//------------------------------------------------------------//
 $chamadoparafechar=chpfechar($idusuario);
// $aguardando_resposta=aguardando();


   $tempo3=atributo('atributo3');
   $ip=get_real_ip();

   /*--------------- Captura a url ----------------*/

   $url = (isset($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

  /*----------------------------------------------*/



?>
<script type="text/javascript" src="jsgraficos/jscharts.js"></script>
<?



//----------------------Fechamento automatico-----------------//
$checa = mysql_query("select sc.id_chamado,time_to_sec(TIMEDIFF(sysdate(),hc.data_criacao))DATA
                      from
                        sgc_chamado sc
                      , sgc_historico_chamado hc
                      where sc.status='Concluido'
                      and sc.id_linha_historico = hc.id_historico
                      and TIME_TO_SEC(TIMEDIFF(sysdate(),hc.data_criacao))>=(select atributo14 from sgc_parametros_sistema)
") or print(mysql_error());
                  while($dados=mysql_fetch_array($checa)){
                  $id_chamado_fechar = $dados['id_chamado'];

if($id_chamado_fechar!=null){
                       copiar_linha_historico($id_chamado_fechar,'Fechado','Fechado automaticamente por tempo excedido','','','','42','');
         $ultima_linha=tabelainfo($id_chamado_fechar,"sgc_historico_chamado","id_historico","id_chamado"," order by id_historico desc limit 1");
         $cadas = mysql_query("UPDATE sgc_chamado set id_linha_historico=$ultima_linha where id_chamado = $id_chamado_fechar") or print('52'.mysql_error());
  }
}
//------------------------------------------------------------//

 if(atributo('atributo22')=="ON"){
echo  $novos = definir_novo_status();
 }


$titulo=$_GET['titulo'];
$msg=$_GET['msg'];

if($titulo==null){
$titulo="Bem vindo ao Sistema Gerencial de Chamados";
}

$acao=$_GET['acao'];
$action=$_GET['action'];

//--------PARA N�O EXECU��O DE SCRIPTS N�O CADASTRADOS---------//
  $url_tela=current_url();
  list ($a, $b) = split ('[=]',$url_tela);
  list ($a, $b) = split ('[&]',$b);
  $tela_chamada=$a;

  if($tela_chamada!=null or $tela_chamada!=""){

  if(verefica_tela($tela_chamada)<1){
     $acesso_tela=tela_cadastrada($tela_chamada,$idusuario);
  }

  }else{

    $acesso_tela=null;

  }




 if($acesso_tela=="NEGADO"){


  if(atributo('atributo10')=="ON"){

  $nome=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
  $email_nome_adm=atributo("atributo6");
  list ($email_adm, $nome_adm) = split ('[;]',$email_nome_adm);
  $ip_real=get_real_ip();


     $email=send_mail_smtp("SGC - Tentativa de viloa��o de Sistema"
                         ,"<p align='center'>ATEN��O</p><p align='center'>O usu�rio: <b>$nome</b>, tentou executar uma tela que n�o esta cadastrada para seu perfil, o usu�rio foi desativado!<BR>Tela: $tela_chamada <BR> IP: $ip_real </p>"
                         ,"<p align='center'>ATEN��O</p><p align='center'>O usu�rio: <b>$nome</b>, tentou executar uma tela que n�o esta cadastrada para seu perfil, o usu�rio foi desativado!<BR>Tela: $tela_chamada <BR> IP: $ip_real </p>"
                         ,$email_adm
                         ,$nome_adm);

   }






   bloqueio_usuario($idusuario,"Tentativa de execu��o de tela n�o autorizada");
   header("Location: logout.php");
 }


//------------------------------------------------------------//


if($permissao=='ok'){
    if(!isset($acao)){

           if($_SESSION['url_finalizar']!=null and $_GET['acao_int']!="finalizar"){
                 $url_finalizar=$_SESSION['url_finalizar'];
                 header("Location: $url_finalizar&msg_fin=Voc� tem chamado(s) para ser avaliado(s)!");
                 session_unregister('url_finalizar');
            }

            if($_SESSION['url_questionar_analista']!=null and $_GET['acao_int']!="questionar_analista"){
                 $url_questionar_analista=$_SESSION['url_questionar_analista'];

                 $url_questionar_analista;
            }

            if($_SESSION['url_questionar_usuario']!=null and $_GET['acao_int']!="questionar_usuario"){
                 $url_questionar_usuario=$_SESSION['url_questionar_usuario'];

                 $url_questionar_usuario;
            }



    ?>
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title><?
       $checa = mysql_query("SELECT org.descricao,org.link FROM sgc_parametros_sistema at, sgc_organizacao org
                                    where org.id_organizacao = at.atributo9") or print(mysql_error());
                  while($dados=mysql_fetch_array($checa)){
                  echo $descricao = $dados['descricao'];
                       $link_organizacao = $dados['link'];
                  }



    ?></title>

    <style type="text/css">
<!--
  .formata { /* esta classe � somente
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
    <STYLE type="text/css">

	BODY {background: #FFFFFF ; color: black;}

	a:link {text-decoration: none; color: #363636;}
	a:visited {text-decoration: none; color: #363636;}
	a:active {text-decoration: none; color: #363636;}
	a:hover {text-decoration: underline; color: #363636;}

	a.kbase:link {text-decoration: underline; font-weight: bold; color: #000000;}
	a.kbase:visited {text-decoration: underline; font-weight: bold; color: #000000;}
	a.kbase:active {text-decoration: underline; font-weight: bold; color: #000000;}
	a.kbase:hover {text-decoration: underline; font-weight: bold; color: #000000;}


	table.border {background: #8C8984; color: black;}
	td {color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	tr {color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	td.back {background: #FFFFFF;}
	td.back2 {background: #EEEEEE;}

	td.date {background: #EEEEEE; font-family: "Arial"; font-size: 12px; color: #000000;}

	td.hf {background: #D0D0D0; font-family: "Arial"; font-size: 12px; color: #000000;}

	a.hf:link {text-decoration: none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #000000;}

	a.hf:visited {text-decoration:none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #000000;}

	a.hf:active {text-decoration: none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #000000;}

	a.hf:hover {text-decoration: underline; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #000000;}

	td.info {background: <?echo $cor_class_info=atributo('atributo23');?> ; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #FFFFFF;}

	a.info:link {text-decoration: none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #FFFFFF;}

	a.info:visited {text-decoration:none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #FFFFFF;}

	a.info:active {text-decoration: none; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #FFFFFF;}

	a.info:hover {text-decoration: underline; font-weight: normal; font-family: "Arial"; font-size: 12px; color: #FFFFFF;}

	select, option, textarea, input {font-family: Verdana, arial, helvetica, sans-serif; font-size:	11px; background: #EEEEEE; color: #000000;}

	td.cat {background: #EEEEEE; font-family: "Arial"; font-size: 12px; color: #000000;}

	td.stats {background: #EEEEEE; font-family: "Arial"; font-size: 10px; color: #000000;}

	td.error {background: #EEEEEE; color: #ff0000; font-family: "Arial"; font-size: 12px;}

	td.subcat {background: #EEEEEE; color: #000000; font-family: "Arial"; font-size: 12px;}



	input.box {border: 0px;}

	table.border2 {background: #6974b5;}
	td.install {background:#dddddd; color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	table.install {background: #000099;}
	td.head	{background:#6974b5; color: #ffffff; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	a.install:link {text-decoration: none; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #6974b5;}
	a.install:visited {text-decoration:none; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #6974b5;}
	a.install:active {text-decoration: none; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000099;}
	a.install:hover {text-decoration: underline; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000099;}

</STYLE>
</head>

<script src="prototype.js" type="text/javascript"></script>

<script language='javascript'>
function valida_dados_busca (nomeform)
{
    if (nomeform.id_chamado.value=="")
    {
        alert ("\nDigite o n�mero do chamado para busca.");
        return false;
    }

return true;
}
</script>


<script>
   new Ajax.PeriodicalUpdater('flutuante', 'flutuante.php',
   {
   method: 'post',
   parameters: {idus: '<?echo $idusuario?>', acao_int: 'banner', permissao: '<?echo $permissao?>', url: '<?echo $url?>'},
   frequency: 800
   });
</script>

<div id="flutuante" ></div>


</table>

<body topmargin="0" leftmargin="0" >
<table border="0" width="100%" cellspacing="0" cellpadding="0" background="imgs/f_cabecalho.jpg">
	<tr>
		<td width="1001" >
		<a target="_self" href="<?echo $link_organizacao?>" >
		<img style="border: 0px;" src="imgs/logo_sistema.jpg" /></a></td>
		<td width="132">
		<img src="imgs/logo_conab.jpg" width="126" height="59" align="right"></td>
	</tr>
</table>





<body>

<table class="border" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
		<table border="0" cellpadding="5" cellspacing="1" width="100%">
			<tr>
				<td class="hf" align="right">
                        <div align="right">		<a class="hf" href="index.php">
					home</a> |&nbsp;Voc� est� logado como <b><?echo $nome=strtolower(tabelainfo($idusuario,"sgc_usuario","email","id_usuario",""))?></b> (
					<a class="hf" href="logout.php">
					logout</a> )</div>
				</td>
			</tr>
			<tr>
				<td class="back" align="left"> <table width="100%">
					<tr>
						<td class="back" align="right" valign="top">
                  <?

                  $checa = mysql_query("SELECT atributo1 FROM sgc_parametros_sistema ") or print(mysql_error());
                  while($dados=mysql_fetch_array($checa)){
                  $iditematributo = $dados['atributo1'];
                  }



            $acesso=acesso($idusuario,$iditematributo);

                  if($acesso=="OK"){

                  $nvs_chamados=novos_chamados($var);
                  if($nvs_chamados>0){
                   $aviso="acionado";
                   $cor_tabela="#FF0000";
                   $mensagem="Existe: $nvs_chamados chamado(s) aguardando parametriza��o e seu n�vel de espera � cr�tico";
                  }

                 }

            ?>
            <p align="center">
            <table border="0" width="100%">
        	<tr>
			<td width="200">
			<?
			$meu_chamado_novo=0;
            $meu_chamado_novo=chamados_suporte($idusuario);
            if($meu_chamado_novo==1){
            ?>
            <table border="1" width="100%" cellspacing="0" style="border-collapse: collapse" bordercolor="#000000" height="55">
		    <tr>
			<td>
			<table border="0" width="100%" cellspacing="0" cellpadding="0" height="51">
			<tr>
			<td bgcolor="#FFFFFF">
			<p align="center">
			<font size="1" face="Verdana" color="#FFFFFF">
            <font color="#FFFFFF"></font></a></font>
			<font face="Verdana" size="1">
			<img border="0" src="imgs/alert.gif" width="50" height="43" align="left"><a href="?action=listar_meus_chamados.php"><b><font color="#000000"><b>ATEN��O</b><br>
			</b>Voc� tem novo(s)<br>Chamado(s)</font></a></font></td>
			</tr>
			</table>
			</td>
            </tr>
            </table>
            <?
            }
            
            //$nfe_status=tabelainfo($id_unidade_usuario,'sgc_servidores','nfe','nuf','');

            //$id_unidade_usuario=51;
            //$nfe_status="ON";
            /*
            if($nfe_status=="ON" && $acao_int!='notas'){

            <script>
             new Ajax.PeriodicalUpdater('ultimas_notas_barra', 'nfe_ultimas_notas.php',
               {
                  method: 'post',
                     parameters: {idus: '<?echo $idusuario?>', uf: '<?echo $id_unidade_usuario?>', bloco: 'BARRA'},
                        frequency: <?echo $tempo=atributo('atributo3')?>
                           });
            </script>
            <div id="ultimas_notas_barra">
            </div>


            }
            */
            
            ?>

            </td>
			<td>
            <?

            if($aviso=="acionado"){

              ?>

            <table border="1" width="100%" cellspacing="0" style="border-collapse: collapse" bordercolor="#000000" height="55">
		    <tr>
			<td>
        	<table border="0" width="100%" cellspacing="0" cellpadding="0" height="51">
			<tr>
			<td bgcolor="#FF0000">
			<p align="center">
			<font size="2" face="Verdana" color="#FFFFFF">
			<a href="?action=console_servicedesk.php&id_item=<?echo $iditematributo?>"><font color="#FFFFFF"><?echo $mensagem?></font></a></font></td>
			</tr>
			</table>
			</td>
	    	</tr>
           	</table>
            </td>
			</tr>
			</table>

			<?
			}
      ?>

   			
   			
<script>
   new Ajax.PeriodicalUpdater('popup', 'popup.php',
   {
   method: 'post',
   parameters: {idus: '<?echo $idusuario?>'},
   frequency: <?echo $tempo=atributo('atributo3')?>
   });
</script>


<div id="popup" align="left">
</div>
   			
   			
   			
   			
   			
			</td>
			</tr>
			</table>







            <table align="center" border="0" width="100%">
					<tr>
						<td valign="top" width="200">
						<table class="border" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
								<table border="0" cellpadding="5" cellspacing="1" width="100%">
									<tr>
										<td class="info" align="center"><b>
										Suporte Op��es</b></td>
									</tr>
                                    <?

                                     $nova_mensagem=tabelainfo($idusuario,"sgc_usuarios_mensagens","count(*)","id_usuario"," and visto is null");

                                     if($nova_mensagem > 0){
                                     
                                     $id_mensagem=tabelainfo($idusuario,"sgc_usuarios_mensagens","id_mensagem","id_usuario"," and visto is null");

                                    ?>

                                    <tr>
										<td class="cat"><table border="0" width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td width="24">
												<p align="center">
												<img border="0" src="imgs/emailalert.gif" width="16" height="11"></td>
												<td><a href="?action=caixa_entrada.php&id_item=35"><font color="#000000"><?echo $nova_mensagem?> Nova(s) Mensagen(s)</font></a></td>
											</tr>
										</table></td>
									</tr>
                                    <?
                                     }
                                    ?>
                                    <tr>
										<td class="cat"><b>Chamado Op��es</b></td>
									</tr>
									<tr>
										<td class="subcat">
										<li>
										<a href="?action=abertura_chamado.php">	Criar Chamado </a></li>
										<li>
										<a href="?action=meus_abertos.php">Meus Chamados Abertos </a>(<b><?echo $abertos=contador($idusuario,'sgc_chamado','id_usuario',"and status  not in ('FECHADO','LIMBO','SUSPENSO') "); ?></b>)
										<br>
                                        &nbsp;<p>&nbsp;</p>
										<form  method="post" action="?action=vis_chamado.php"  onSubmit="return valida_dados_busca(this)">Chamado # :
											<input name="id_chamado" size="5" type="text" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #FFFFFF;" >
											<input name="qtask" value="Ir!" type="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #C0C0C0;" >
										</form>
										</li>
										</td>
									</tr>
                                    <?
                                    $nvs_ch=novos_chamados($var);
                                    $checa_menu = mysql_query("SELECT perfil FROM sgc_usuario WHERE id_usuario = $idusuario") or print mysql_error();
                                    while($dados_menu=mysql_fetch_array($checa_menu)){
                                    $perfil= $dados_menu["perfil"];
                                    }

                                    if($perfil=="CUSTOMIZADO"){

                                     $sql="select distinct m.descricao MENU, m.id_menu ID_MENU
                                           from sgc_item_menu im, sgc_regra_menu rm, sgc_menu m
                                           where rm.id_usuario = $idusuario
                                           and m.id_menu = rm.id_menu order by menu asc";



                                    }else{

                                     $sql="select distinct m.descricao MENU, m.id_menu ID_MENU
                                           from sgc_item_menu im, sgc_template_regra rm, sgc_menu m
                                           where rm.id_template = $perfil
                                           and m.id_menu = rm.id_menu order by menu asc";



                                     }


                                    $checa_menu = mysql_query("$sql") or print mysql_error();
                                    while($dados_menu=mysql_fetch_array($checa_menu)){
                                    $id_menu= $dados_menu["ID_MENU"];
                                    $menu= $dados_menu["MENU"];
                                    $id_item= $dados_menu["ID_ITEM"];


                                   if($perfil=="CUSTOMIZADO"){

                                       $sql_1="select distinct  m.descricao MENU, m.id_menu ID_MENU,im.id_item_menu ID_ITEM, im.descricao ITEM ,im.link_item LINK, im.contador CONTADOR
                                             from sgc_item_menu im, sgc_regra_menu rm, sgc_menu m
                                             where m.id_menu = $id_menu
                                             and rm.id_usuario = $idusuario
                                             and rm.id_menu = m.id_menu
                                             and im.id_item_menu = rm.id_item_menu order by item asc";
                                   }else{

                                     $sql_1="select distinct m.descricao MENU, m.id_menu ID_MENU,im.id_item_menu ID_ITEM, im.descricao ITEM ,im.link_item LINK, im.contador CONTADOR
                                             from sgc_item_menu im, sgc_template_regra rm, sgc_menu m
                                             where m.id_menu = $id_menu
                                             and rm.id_template = $perfil
                                             and rm.id_menu = m.id_menu
                                             and im.id_item_menu = rm.id_item order by item asc";
                                   }






                                    ?>
                                    <tr>
										<td class="cat"><b><?echo $menu?></b></td>
									</tr>
									<tr>
                                        <td class="subcat">
									<?
								    $checa_item = mysql_query("$sql_1") or print mysql_error();
                                    while($dados_item=mysql_fetch_array($checa_item)){
                                    $item= $dados_item["ITEM"];
                                    $iditem= $dados_item["ID_ITEM"];
                                    $link= $dados_item["LINK"];
                                    $contador= $dados_item["CONTADOR"];



                                    if($contador!=null){

                                         list ($contador, $parametro) = split ('[|]',$contador);



                                         if($parametro=="idusuario"){
                                             $parametro=$idusuario;
                                         }else{
                                             $parametro=null;
                                         }


                                        $checa_cont = mysql_query("$contador $parametro") or print mysql_error();
                                             while($dados_cont=mysql_fetch_array($checa_cont)){
                                             $conta= $dados_cont["CONTADOR"];
                                        }


                                       $conta =  "(".$conta.")";
                                    }else{
                                       $conta =null;
                                       $contador=null;
                                    }

                                         list ($arquivo_link, $extencao_link) = split ('[.]',$link);
                                         
                                 if($extencao_link!="php"){

                                   echo"<li>
										<a href='$link'>$item <b>$conta</b></a>
                                        </li>";


                                         
                                         }else{

                                   echo"<li>
										<a href='$link&id_item=$iditem'>$item <b>$conta</b></a>
                                        </li>";

                                         }
                                         
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
						</table>
						</td>
						<td valign="top">
                        <?
                        if($chamadoparafechar>0 and $action!="vis_chamado.php" ){

                        ?>
                          <table class="border" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
								<table border="0" cellpadding="5" cellspacing="1" width="100%">
									<tr>
										<td class="info" colspan="1" align="center">
										<b>ATEN��O </b></td>
									</tr>
									<tr>
										<td class="cat" align="right">
										<font size="1">
										<table border="0" width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td width="28">&nbsp;</td>
												<td>
												<p align="center">Existem chamados seu conclu�do(s)<br>	Por favor defina seu &quot;status&quot;.</p>
												<table border="0" width="100%" cellspacing="0" cellpadding="0">
													<tr>
														<td width="31">&nbsp;</td>
														<td width="80">
														<p align="center">
														Chamado #</td>
														<td width="80%" colspan="2">&nbsp;T�tulo</td>
														<td width="41">&nbsp;</td>
													</tr>
												<?
												$checa = mysql_query("select id_chamado,titulo from sgc_chamado where quem_criou=$idusuario
                                                       and status='Concluido' ") or print(mysql_error());
                                                       while($dados=mysql_fetch_array($checa)){
                                                                     $id_ch = $dados['id_chamado'];
                                                                     $tit = $dados['titulo'];

												?>
													<tr>
														<td height="26" width="31">&nbsp;</td>
														<td height="26" width="80">
														<p align="center">
														<a href="?action=vis_chamado.php&id_chamado=<?echo $id_ch?>">
														<font color="#000000"><?echo $id_ch?></font></a></td>
														<td height="26" width="720">&nbsp;<a href="?action=vis_chamado.php&id_chamado=<?echo $id_ch?>"><font color="#000000"><?echo $tit?></font></a></td>
														<td height="26" width="137">
														<a href="?action=sgc.php&acao=define_status&id_chamado=<?echo $id_ch?>">
														<font color="#000000">Definir mais tarde</font></a></td>
														<td height="26" width="41">&nbsp;</td>
													</tr>
												<?
												}
												?>

												</table>
												</td>
												<td width="20">&nbsp;</td>
											</tr>
											<tr>
												<td width="28">&nbsp;</td>
												<td>&nbsp;</td>
												<td width="20">&nbsp;</td>
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

                        if(!isset($action)){




                           if( strtolower(verperfil($idusuario))==strtolower(atributo("atributo15")) and novos_cadastros()>0 ){

                           ?>
                            <table class="border" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
								<table border="0" cellpadding="5" cellspacing="1" width="100%">
									<tr>
										<td class="info" colspan="1" align="center">
										<b>Novo(s) Usu�rio(s) Para Confirma��o </b></td>
									</tr>
									<tr>
										<td class="cat" align="right">
										<font size="1">
										<div align="center">
										<table border="0" width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td width="43">&nbsp;</td>
												<td align="center">
												<table border="0" width="64%" cellspacing="0" cellpadding="0">
													<tr>
														<td><b>&nbsp;Nome:</b></td>
														<td width="231"><b>&nbsp;e-mail</b></td>
														<td width="148"><b>Data
														Autocadastro</b></td>
													</tr>
                                                    <?
                                                    $checa = mysql_query("select id_usuario, concat(primeiro_nome,' ',ultimo_nome)nome, lower(email)email,DATE_FORMAT(data_criacao,'%d/%m/%Y %H:%i')data from sgc_usuario where quem_alterou='0'") or print(mysql_error());
                                                       while($dados=mysql_fetch_array($checa)){
                                                                     $id_usuario_auto = $dados['id_usuario'];
                                                                     $nome_auto = $dados['nome'];
                                                                     $email_auto = $dados['email'];
                                                                     $data_auto = $dados['data'];

                                                    ?>
                                                    <tr>
														<td>&nbsp;<a href="?action=editar_usuario.php&acao_int=confirma_objeto&id_usuario=<?echo $id_usuario_auto?>&id_item=<?echo atributo("atributo16")?>"><font color="#000000"><?echo $nome_auto?></font></a></td>
														<td width="231">&nbsp;<a href="?action=editar_usuario.php&acao_int=confirma_objeto&id_usuario=<?echo $id_usuario_auto?>&id_item=<?echo atributo("atributo16")?>"><font color="#000000"><?echo $email_auto?></font></a></td>
														<td width="148"><?echo $data_auto?></td>
													</tr>
													<?
													 }
													?>
												</table>
										</table>
										</div>
										</td>
									</tr>
        						</table>
		    	    			</td>
			    			</tr>
						</table>
						<?





                           }else{

                        ?>
                       <!--
                     	<table class="border" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
								<table border="0" cellpadding="5" cellspacing="1" width="100%">
									<tr>
										<td class="info" colspan="1" align="center">
										<b><?echo $titulo?></b></td>
									</tr>
									<tr>
										<td class="cat" align="right">
										<font size="1">
										<p>&nbsp;</p>
										<table border="0" width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td width="43">&nbsp;</td>
												<td>
												<p align="center">
												<font color="#FF0000"><?echo $msg?></font></td>
												<td width="40">&nbsp;</td>
											</tr>
										</table>
										<p>&nbsp;</td>
									</tr>
        						</table>
		    	    			</td>
			    			</tr>
						</table>
                        <BR>
                        <BR>
                        -->

<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="100%">
						<tr>
							<td width="33">&nbsp;</td>
							<td>
							<p align="center"><b>Relat�rio de Status dos Chamados</b></td>
							<td width="34">&nbsp;</td>
						</tr>

						<tr>
							<td width="33">&nbsp;</td>
							<td>
							<p align="center">&nbsp;</td>
							<td width="34">&nbsp;</td>
						</tr>
						<tr>
							<td width="33">&nbsp;</td>
							<td>
							<table border="1" width="100%" cellspacing="0" style="border-collapse: collapse">
								<tr>
									<td class="info" width="90%" colspan="3" height="23">
									<p align="center"><b>Todos Chamados </b> </td>
								</tr>
                                <?
                                $checa = mysql_query("
                                SELECT
                                  Status,count(status)Total
                                  , round(count(status)*100/(SELECT count(id_chamado) FROM sgc_chamado where status != 'LIMBO'),2) Porcent
                                  FROM sgc_chamado ch
                                  where 1=1
                                  and status != 'LIMBO'
                                  group by status
                                  order by total desc
                                ")or print(mysql_error());
                                  while($dados=mysql_fetch_array($checa)){
                                    $status= $dados['Status'];
                                    $total= $dados['Total'];
                                    $porcent= $dados['Porcent']
                                        
                                
                                ?>
                                <tr>
									<td width="70%" height="23" bgcolor="#FFFFFF">&nbsp;<a href="?action=vis_chamados.php&id_item=31&acao_int=visualizar&situacao=<?echo $status?>"><font color="#000000"><?Echo $status?></font></a></td>
									<td width="10%" bgcolor="#FFFFFF">
									<p align="center"><?echo $total ?></td>
									<td width="10%" bgcolor="#FFFFFF">
									<p align="center"><?echo $porcent?> %</td>
								</tr>
								<?
								}
								?>
								
								
							</table>
							</td>
							<td width="34">&nbsp;</td>
						</tr>
     	<tr>
							<td width="33">&nbsp;</td>
							<td>
							&nbsp;</td>
							<td width="34">&nbsp;</td>
						</tr>
						<tr>
							<td width="33">&nbsp;</td>
							<td>
							<table border="1" width="100%" cellspacing="0" style="border-collapse: collapse">
								<tr>
									<td class="info" width="90%" colspan="3" height="23">
									<p align="center"><b>Todos Chamados da Sua Unidade</b></td>
								</tr>
                                <?
                                $checa = mysql_query("
                                SELECT
                                  Status,count(status)Total
                                  , round(count(status)*100/(SELECT count(id_chamado) FROM sgc_chamado where id_unidade = $id_unidade_usuario),2) Porcent
                                  FROM sgc_chamado ch
                                  where 1=1 and ch.id_unidade = $id_unidade_usuario
                                  group by status
                                  order by total desc
                                ")or print(mysql_error());
                                  while($dados=mysql_fetch_array($checa)){
                                    $status= $dados['Status'];
                                    $total= $dados['Total'];
                                    $porcent= $dados['Porcent']
                                    
                                  ?>
								<tr>
									<td width="70%" height="23" bgcolor="#FFFFFF">&nbsp;<a href="?action=vis_chamados.php&id_item=31&acao_int=visualizar&situacao=<?echo $status?>&unidade=<?echo $id_unidade_usuario?>"><font color="#000000"><?echo $status?></font></a></td>
									<td width="10%" bgcolor="#FFFFFF">
									<p align="center"><?echo $total?></td>
									<td width="10%" bgcolor="#FFFFFF">
									<p align="center"><?echo $porcent?> %</td>
								</tr>
								<?
								}
								?>
        					</table>
        					<BR>
                            <table border="1" width="100%" cellspacing="0" style="border-collapse: collapse">
								<tr>
									<td class="info" width="90%" colspan="3" height="23">
									<p align="center"><b>Distribui��o de Recursos</b></td>
								</tr>
                                <?
                                $checa = mysql_query("
                               SELECT
                                un.sigla
                                 ,count(ch.id_unidade) Total
                                 , round(count(ch.id_unidade)*100/(SELECT count(id_chamado) FROM sgc_chamado),2) Porcent
                                 FROM sgc_chamado ch, sgc_unidade un
                                 where ch.id_unidade = un.codigo
                                 group by ch.id_unidade
                                 order by total desc
                                ")or print(mysql_error());
                                  while($dados=mysql_fetch_array($checa)){
                                    $status= $dados['sigla'];
                                    $total= $dados['Total'];
                                    $porcent= $dados['Porcent']

                                  ?>
								<tr>
									<td width="70%" height="23" bgcolor="#FFFFFF">&nbsp;<font color="#000000"><?echo $status?></font></td>
									<td width="10%" bgcolor="#FFFFFF">
									<p align="center"><?echo $total?></td>
									<td width="10%" bgcolor="#FFFFFF">
									<p align="center"><?echo $porcent?> %</td>
								</tr>
								<?
								}
								?>
        					</table>
                            <BR>
                            <table border="1" width="100%" cellspacing="0" style="border-collapse: collapse">
								<tr>
									<td class="info" width="90%" colspan="3" height="23">
									<p align="center"><b>Distribui��o de Recursos Por Suporte (Concluidos ou Fechados)</b></td>
								</tr>
                                <?
                                $checa = mysql_query("
                                  SELECT
                                       concat(us.primeiro_nome,' ',us.ultimo_nome) Nome
                                           ,count(ch.id_chamado) Total
                                               ,round(count(ch.id_chamado)*100/(SELECT count(id_chamado) FROM sgc_chamado  where status in ('Concluido','Fechado') ),2) Porcent
                                                   FROM sgc_chamado ch, sgc_historico_chamado hist, sgc_usuario us
                                                       where hist.id_historico = ch.id_linha_historico
                                                           and  us.id_usuario = hist.id_suporte
                                                               and ch.status in ('Concluido','Fechado')
                                                                   group by hist.id_suporte
                                                                       order by total desc
                                ")or print(mysql_error());
                                  while($dados=mysql_fetch_array($checa)){
                                    $status= $dados['Nome'];
                                    $total= $dados['Total'];
                                    $porcent= $dados['Porcent']

                                  ?>
								<tr>
									<td width="70%" height="23" bgcolor="#FFFFFF">&nbsp;<?echo $status?></font></td>
									<td width="10%" bgcolor="#FFFFFF">
									<p align="center"><?echo $total?></td>
									<td width="10%" bgcolor="#FFFFFF">
									<p align="center"><?echo $porcent?> %</td>
								</tr>
								<?
								}
								?>
        					</table>
							</td>
							<td width="34">&nbsp;</td>
						</tr>
						<BR>
						<BR>
      					</table>
      					<BR>
						<BR>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>

						
                       <?
                         }
                       }else{

                           @include("$action");

                       }
                      }
                       ?>

                        <br>
					&nbsp;</td>
					</tr>
				</table>
				<br>
				&nbsp;</td>
			</tr>
			<tr>
				<td class="cat"><br>
				&nbsp;</td>
			</tr>
			<tr>
				<td class="hf" align="center">
				<div align="center">
					<a class="hf" href="index.php">
					home</a> |&nbsp;<a class="hf" href="logout.php">logout</a>
				</div>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<center>
</center>

</body>

</html>
</div>

<?
if($nova_mensagem > 0){
?>
<script>
   new Ajax.PeriodicalUpdater('msng', 'msg_automatico.php',
   {
   method: 'post',
   parameters: {idus: '<?echo $idusuario?>', acao_int: 'banner', permissao: '<?echo $permissao?>',id_mensagem: '<?echo $id_mensagem?>', url: '<?echo $url?>'},
   frequency: 10
   });
</script>
<?
 }
?>
<div id="msng" ></div>





<script>
  new Ajax.PeriodicalUpdater('useronline', 'user_online.php',
  {
   method: 'post',
   parameters: {idus: '<?echo $idusuario?>', ip: '<?echo $ip?>',  session: '<?echo $sesion_id = session_id()?>'},
   frequency: <?echo $tempo3?>
   });
</script>


<div id="useronline" align="left">
</div>







<?

}elseif($acao=="define_status"){
$id_chamado=$_GET['id_chamado'];
$cadas = mysql_query("UPDATE sgc_chamado SET definir_status=sysdate() where id_chamado = $id_chamado") or print(mysql_error());
$titulo='ATEN��O';
$tempo=atributo('atributo13')/3600;
$msg="Esse chamado ser� cobrando em $tempo Hora(s)";
header("Location: sgc.php?tela=sgc.php&titulo=$titulo&msg=$msg");
}
}else{

    $msg="Por favor confirme seus dados";


    $url_atual = (isset($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    session_register("url_atual");



    header("Location: index.php?result=$msg");

}




?>










