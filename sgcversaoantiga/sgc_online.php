<?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


$conexao = mysql_connect('localhost','root','') or die ("Não foi possível conectar com o MySQL!");
           mysql_select_db('sgc') or die ("Banco de dados inexistente");


include("conf/funcs.php");


?>
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title><?echo $nvs_ch=novos_chamados($var)?></title>

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

	td.info {background: #336666; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #FFFFFF;}

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

<body>

<table class="border" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
		<table border="0" cellpadding="5" cellspacing="1" width="100%">
			<tr>
				<td class="hf" align="right">
                        <div align="right">		<a class="hf" href="index.php">
					home</a> |&nbsp;Você está logado como <b><?echo $nome=strtolower(tabelainfo($idusuario,"sgc_usuario","email","id_usuario"))?></b> (
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
                   $mensagem="Existe: $nvs_chamados chamado(s) aguardando parametrização e seu nível de espera é crítico";
                  }

                 }

            ?>
            <p align="center">
            <table border="0" width="100%">
        	<tr>
			<td width="200">
			<?
            $meu_chamado_novo=chamados_suporte($idusuario);
            if($meu_chamado_novo>0){
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
			<img border="0" src="imgs/alert.gif" width="50" height="43" align="left"><a href="novos"><b><font color="#000000"><b>ATENÇÃO</b><br>
			</b>Você tem: <?echo $meu_chamado_novo?> novo(s)<br>Chamado(s)</font></a></font></td>
			</tr>
			</table>
			</td>
            </tr>
            </table>
            <?
            }
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
										Suporte Opções</b></td>
									</tr>
									<tr>
										<td class="cat"><b>Chamado Opções</b></td>
									</tr>
									<tr>
										<td class="subcat">
										<li>
										<a href="?action=abertura_chamado.php">
										Criar Chamado </a></li>
										<li>
										<a href="?action=listar_meus_chamados.php">
										Meus Chamados Abertos </a>(<b><?echo $abertos=contador($idusuario,'sgc_chamado','id_usuario',"and status!='FECHADO' "); ?></b>)
										<br>
                                        &nbsp;<p>&nbsp;</p>
										<form name="formTicketSearch" method="get" action="http://www.frimesa.com.br/intranet/cpd/oozv164/supporter/index.php?">
											<input name="t" value="tupd" type="hidden">Chamado # :
											<input name="id" size="5" type="text" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #FFFFFF;" >
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

                                    ?>
                                    <tr>
										<td class="cat"><b><?echo $menu?></b></td>
									</tr>
									<tr>
                                        <td class="subcat">
									<?
								    $checa_item = mysql_query("select m.descricao MENU, m.id_menu ID_MENU,im.id_item_menu ID_ITEM, im.descricao ITEM ,im.link_item LINK
                                    from sgc_item_menu im, sgc_regra_menu rm, sgc_menu m
                                    where m.id_menu = $id_menu
                                    and rm.id_menu = m.id_menu
                                    and im.id_item_menu = rm.id_item_menu order by item asc
                                    ") or print mysql_error();
                                    while($dados_item=mysql_fetch_array($checa_item)){
                                    $item= $dados_item["ITEM"];
                                    $iditem= $dados_item["ID_ITEM"];
                                    $link= $dados_item["LINK"];


                                   echo"<li>
										<a href='$link&id_item=$iditem'>$item</a>
                                        </li>";
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
                        if(!isset($action)){
                        ?>

                        <table class="border" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
								<table border="0" cellpadding="5" cellspacing="1" width="100%">
									<tr>
										<td class="info" colspan="1" align="center">
										<b>Anúncios </b></td>
									</tr>
									<tr>
										<td class="cat" align="right">
										<font size="1">
										<p>&nbsp;</p>
										<p>&nbsp;</p>
										<p>&nbsp;</td>
									</tr>
        						</table>
		    	    			</td>
			    			</tr>
						</table>
                       <?
                       }else{

                           @include("$action");

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
<center></center>

</body>

</html>
<?
