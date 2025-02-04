<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Parametrização";
$titulo_listar="Últimos 10 Chamados Abertos por Você";
$id_item=$_GET['id_item'];
$arquivo="parametrizacao.php";
$tabela="sgc_chamado";
$id_chave="id_chamado";

$id_chamado=$_GET['id_chamado'];
$semelhantes=null;

$id_usuario_chamado=tabelainfo($id_chamado,$tabela,'id_usuario','id_chamado');

function compare($x, $y)
  {
    if($y[1] == $x[1])
      return 1;
    else if($y[1] < $x[1])
      return -1;
    else
      return 1;
  }

function semelhanca($var,$var1,$idchamado){
   $count=0;
   $contador=0;
   $media=0;

   $var=strtolower($var);
   $var1=strtolower($var1);

  $sql= mysql_query("select sc.descricao,sc.titulo,sc.id_chamado from sgc_chamado sc
WHERE sc.id_chamado !=$idchamado
and sc.id_chamado in (SELECT id_chamado FROM sgc_historico_chamado)
");
  while($dados=mysql_fetch_array($sql)){
    $descricao=$dados['descricao'];
    $id_chamado=$dados['id_chamado'];
    $titulo=$dados['titulo'];

    $titulo=strtolower($titulo);
    $descricao=strtolower($descricao);

    similar_text($titulo, $var, $porcent);
    similar_text($descricao, $var1, $porcent_desc);
    $count++;



  $porcent=number_format($porcent,0, '.', '');
  $porcent_desc=number_format($porcent_desc,0, '.', '');
  
  

  $qtde_var = explode(" ", $var);
  $qtde_var = count($qtde_var);
  
  

  if($porcent_desc>60 and $porcent<80){

      $if="D";
      $media=$porcent_desc;

  }elseif($porcent>70 and $porcent_desc<60){
      $if="T";
      $media=$porcent;

  }elseif($porcent<60 and $porcent_desc<60){
      $if="M";
      $media=($porcent+$porcent_desc)/2;

  }elseif($porcent==$porcent_desc){
      $if="M";
      $media=$porcent;

  }elseif($porcent>70  and  $porcent_desc>60){
      $if="T";
      $media=($porcent+$porcent_desc)/2;
  }

   if($media>=50){
      $contador++;
      $lista[$contador] = array("$media-$id_chamado",$media);
   }
}
if($contador>0){
usort($lista,'compare');
}


return $lista;
}

if(!isset($acao_int)){
?>
<script language='javascript'>
function valida_dados (nomeform)
{

    if (nomeform.analista.value=="")
    {
        alert ("\nSelecione o Analista.");

        nomeform.prioridade.focus();
        return false;
    }
    if (nomeform.analista.value=="" || nomeform.analista.value=="#")
    {
        alert ("\nSelecione o Analista.");

        nomeform.analista.focus();
        return false;
    }
      if (nomeform.situacao.value=="" || nomeform.situacao.value=="NAOVEREFICADO")
    {
        alert ("\nDetermine a Situação do Chamado.");

        nomeform.situacao.focus();
        return false;
    }


return true;
}
</script>




<script type="text/javascript" src="conf\prototype.js"></script>
<script language="javascript"  src="ajax-area-analista.js" type="text/javascript"></script>

<?



if(tabelainfo($id_chamado,"sgc_chamado","status","id_chamado"," ")!="LIMBO" and tabelainfo($id_chamado,"sgc_chamado","status","id_chamado"," ")!="<font face='Verdana' size='1' color='#FFFFFF'><span style='background-color: #FF0000'>&nbsp;Referência Inválida! </span></font>"){

$mens="Este chamado já foi parametrizado"

?><div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="500" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: ATENÇÃO :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="right">
					<table border="0" width="488" cellspacing="0" cellpadding="0">
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="302" height="23">&nbsp;</td>
							<td width="85" height="23">&nbsp;</td>
							<td width="40" height="23">&nbsp;</td>
							<td width="42" height="23">&nbsp;</td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="469" height="23" colspan="4">
							<p align="center"><?echo $mens?></td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="302" height="23">&nbsp;</td>
							<td width="85" height="23">&nbsp;</td>
							<td width="40" height="23">
							&nbsp;</td>
							<td width="42" height="23">
							&nbsp;</td>
							<td width="10" height="23">&nbsp;</td>
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
}elseif(tabelainfo($id_chamado,"sgc_chamado","status","id_chamado"," ")=="<font face='Verdana' size='1' color='#FFFFFF'><span style='background-color: #FF0000'>&nbsp;Referência Inválida! </span></font>"){

$mens="Este chamado não existe"

?><div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="500" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: ATENÇÃO :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="right">
					<table border="0" width="488" cellspacing="0" cellpadding="0">
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="302" height="23">&nbsp;</td>
							<td width="85" height="23">&nbsp;</td>
							<td width="40" height="23">&nbsp;</td>
							<td width="42" height="23">&nbsp;</td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="469" height="23" colspan="4">
							<p align="center"><?echo $mens?></td>
							<td width="10" height="23">&nbsp;</td>
						</tr>
						<tr>
							<td width="9" height="23">&nbsp;</td>
							<td width="302" height="23">&nbsp;</td>
							<td width="85" height="23">&nbsp;</td>
							<td width="40" height="23">
							&nbsp;</td>
							<td width="42" height="23">
							&nbsp;</td>
							<td width="10" height="23">&nbsp;</td>
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

}else{

?>


<div align="center">
<form method="POST" action="?action=parametrizacao.php&acao_int=registro" onSubmit="return valida_dados(this)">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Chamado #<?echo $id_chamado?> :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
				<table border="0" width="100%" cellpadding="0">
					<tr>
						<td colspan="4">

	<fieldset style="padding: 2">
	<legend align="center"><b>Dados Usuário</b></legend>
				<table border="0" width="100%" cellpadding="0">
					<tr>
						<td width="255">

	<p align="right">e-mail:&nbsp;&nbsp; </td>
						<td width="746"><?echo $email=tabelainfo(tabelainfo($id_chamado,$tabela,'id_usuario','id_chamado'),'sgc_usuario',"email",'id_usuario')?></td>
                          <input type='hidden' name='id_chamado' value='<?echo $id_chamado?>'>
                          <input type='hidden' name='id_usuario_chamado' value='<?echo $id_usuario_chamado?>'>
                    </tr>
                   <input type='hidden' name='url_chamado' value='<?echo $url=(isset($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>'>

                	<tr>
						<td width="255" height="19">

	<p align="right">Nome Completo:&nbsp;&nbsp; </td><td width="746" height="19"><?echo $nome=tabelainfo(tabelainfo($id_chamado,$tabela,'id_usuario','id_chamado'),'sgc_usuario',"concat(primeiro_nome,' ',ultimo_nome)",'id_usuario')?></td>
					</tr><tr><td width="255"><p align="right">Unidade:&nbsp;&nbsp; </td>
						<td width="746"><?echo $unidade=tabelainfo(tabelainfo($id_chamado,$tabela,'id_unidade','id_chamado'),'sgc_unidade',"sigla",'codigo')?>
						 <?echo $departamento=tabelainfo(tabelainfo($email,'sgc_usuario','id_departamento','email'),'sgc_departamento',"descricao",'id_departamento')?></td>
                    </tr>
					<tr>
						<td width="255">

	<p align="right">Telefone:&nbsp;&nbsp; </td>
						<td width="746"><?echo $telefone=tabelainfo(tabelainfo($id_chamado,$tabela,'id_usuario','id_chamado'),'sgc_usuario',"concat('(',ddd,')',' ',telefone,' Ramal: ',ramal)",'id_usuario')?></td>
					</tr>
				</table>

					</td>
					</tr>
					<tr>
						<td colspan="4" width="100%">


		<?

		$titulo=tabelainfo($id_chamado,$tabela,'titulo','id_chamado');
		$descricao=tabelainfo($id_chamado,$tabela,'descricao','id_chamado');

        //$semelhantes=semelhanca("$titulo",$descricao,$id_chamado);

        $result = count($semelhantes);
        if($result>0){

		?>
		<table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#DFDFDF">
		<tr>
			<td colspan="3">
			<p align="center"><b>Existe outros chamados com semelhança a este</b></td>
		</tr>

       <tr>
			<td width="57">&nbsp; <b>ID</b></td>
			<td width="92"><b>De</b></td>
			<td width="499"><b>Título</b></td>
			<td width="78"><b>Para</b></td>
			<td width="112"><b>Semelhança</b></td>
		</tr>
       <?
  	   for($linha =0;$linha < 5;$linha++)
          {
           while(list($key,$value) = each($semelhantes[$linha]))
              {

              list($porcent,$idchamadose) = split ('[-]',$value);



              if($idchamadose!=null){

        ?>
		<tr>
		<td width="57">&nbsp; <a href="?action=vis_chamado.php&id_chamado=<? echo $idchamadose?>"><?echo $idchamadose?></a></td>
			<td width="92"><?echo $usuario=tabelainfo(tabelainfo($idchamadose,'sgc_chamado','id_usuario','id_chamado'),'sgc_usuario','primeiro_nome','id_usuario')?></td>
			<td width="499">
            <a href="javaScript: void(window.open('view_chamado.php?&id_chamado=<?echo $idchamadose?>&id_usuario=<?echo $idusuario?>','Visualizar','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=650,height=500'));"><?echo $titulo=tabelainfo($idchamadose,'sgc_chamado','titulo','id_chamado')?></a>



            </td>

			<td width="78"><?


            $usuario=tabelainfo(tabelainfo($idchamadose,'sgc_chamado','id_suporte','id_chamado'),'sgc_usuario','primeiro_nome','id_usuario');

            if($usuario!="<font face='Verdana' size='1' color='#FFFFFF'><span style='background-color: #FF0000'>&nbsp;Referência Inválida! </span></font>"){
            echo $usuario;
            }

            ?></td>

			<td width="112"><?echo "$porcent"?>%</td>	</tr>
		<?
		   }
          }
         }
		?>
       <tr>
			<td width="57">&nbsp;</td>
			<td width="92">&nbsp;</td>
			<td width="499">&nbsp;</td>
			<td width="78">&nbsp;</td>
			<td width="112">&nbsp;</td>
		</tr>
    	</table>
        <?
        }
        ?>
	&nbsp;</td>
	</tr>

	<tr>
	<td colspan="4">

	<fieldset style="padding: 2">
	<legend align="center"><b>&nbsp;<?echo nl2br($titulo=tabelainfo($id_chamado,$tabela,'titulo','id_chamado'))?>&nbsp;</b></legend>
    <br><p align="center"><?echo nl2br($descricao=tabelainfo($id_chamado,$tabela,'descricao','id_chamado'))?><p>
    </td>
					</tr>
                    <?
                    $obs=tabelainfo($id_chamado,$tabela,'obs','id_chamado');
                    if($obs!=null){
                    ?>
                    <tr>
						<td width="830" colspan="4">
						<b>&nbsp;Obs:&nbsp;</b> <?echo $obs=tabelainfo($id_chamado,$tabela,'obs','id_chamado')?> </td>
					</tr>
                    <tr>
						<td width="830" colspan="4">&nbsp;</td>
					</tr>

                    <?
                    }
                    ?>

                    <tr>
						<td width="259">
						<p align="right">Data Chamado:&nbsp;&nbsp; </td>
						<td width="504"><?echo $data_chamado=data_with_hour(tabelainfo($id_chamado,$tabela,'data_criacao','id_chamado'))?></td>
						<td width="24">&nbsp;</td>
						<td width="225">&nbsp;</td>
					</tr>
     <tr>
						<td width="259">
						<p align="right">&nbsp;Categoria:&nbsp;&nbsp; </td>
						<td width="504"><select size="1" name="categoria">
						  <?
                            $checa_menu = mysql_query("SELECT id_categoria,descricao FROM sgc_categoria order by descricao") or print mysql_error();
                                    while($dados_menu=mysql_fetch_array($checa_menu)){
                                    $id_categoria= $dados_menu["id_categoria"];
                                    $descricao= $dados_menu["descricao"];
                                ?>
     							<option value="<?echo $id_categoria?>"><?echo $descricao?></option>
                                <?
                           }
                        ?>
						</select></td>
						<td width="24">&nbsp;</td>
						<td width="225">&nbsp;</td>
					</tr>
                  <?

                   $prio_user=tabelainfo(tabelainfo($id_chamado,$tabela,'id_urgencia_usuario','id_chamado',''),'sgc_sla_analista_usuario','descricao','id_sla_analista','');

                  if($prio_user=="Crítica"){

                    $prio_sis=tabelainfo(tabelainfo($id_chamado,$tabela,'id_impacto_sistema','id_chamado',''),'sgc_sla_analista_usuario','descricao','id_sla_analista','');

                  if($prio_sis=="Crítica"){
                    $cor_font="<font color='#FF0000'><b>";
                  }
                  $q1=tabelainfo($id_chamado,'sgc_chamado','q1','id_chamado','and q1 is not null and q2 is not null and q3 is not null');
                  $q2=tabelainfo($id_chamado,'sgc_chamado','q2','id_chamado','and q1 is not null and q2 is not null and q3 is not null');
                  $q3=tabelainfo($id_chamado,'sgc_chamado','q3','id_chamado','and q1 is not null and q2 is not null and q3 is not null');

                   ?>

                       	<tr>
						    <td width="259">
					    	<p align="right"> &nbsp;Prioridade definida pelo Sistema:&nbsp;&nbsp; </td>
				    		<td width="504"><?echo $cor_font?><?echo $prio_sis;?> - Quest 1: <?echo $q1?> - Quest 2: <?echo $q2?> - Quest 3: <?echo $q3?></td>
			      			<td width="24">&nbsp;</td>
		       				<td width="225">&nbsp;</td>
	    				</tr>
    				<?
                  }else{


                  if($test=tabelainfo($id_chamado,'sgc_chamado','count(*)','id_chamado','and q1 is not null and q2 is not null and q3 is not null')>0){
                    $prio_sis=tabelainfo(tabelainfo($id_chamado,$tabela,'id_impacto_sistema','id_chamado',''),'sgc_sla_analista_usuario','descricao','id_sla_analista','');

                  if($prio_sis=="Crítica"){
                    $cor_font="<font color='#FF0000'><b>";
                  }
                  $q1=tabelainfo($id_chamado,'sgc_chamado','q1','id_chamado','and q1 is not null and q2 is not null and q3 is not null');
                  $q2=tabelainfo($id_chamado,'sgc_chamado','q2','id_chamado','and q1 is not null and q2 is not null and q3 is not null');
                  $q3=tabelainfo($id_chamado,'sgc_chamado','q3','id_chamado','and q1 is not null and q2 is not null and q3 is not null');
                    ?>
                       	<tr>
						    <td width="259">
					    	<p align="right"> &nbsp;Usuário: Crítica, Sistema:&nbsp;&nbsp; </td>
				    		<td width="504"><?echo $prio_sis;?> - 1Quest 1: <?echo $q1?> - Quest 2: <?echo $q2?> - Quest 3: <?echo $q3?></td>
			      			<td width="24">&nbsp;</td>
		       				<td width="225">&nbsp;</td>
	    				</tr>
    				<?
                  }
                 }

                ?>
					<tr>
						<td width="259">
						<p align="right">&nbsp;Prioridade:&nbsp;&nbsp; </td>
						<td width="504"><select size="1" name="prioridade">
						   <?
                            $checa_menu = mysql_query("SELECT id_sla_analista,descricao FROM sgc_sla_analista_usuario order by descricao='$prio_user' desc , tempo, tipo_tempo asc") or print mysql_error();
                                    while($dados_menu=mysql_fetch_array($checa_menu)){
                                    $id_sla_analista= $dados_menu["id_sla_analista"];
                                    $descricao= $dados_menu["descricao"];
                                ?>
     							<option value="<?echo $id_sla_analista?>"><?echo $descricao?></option>
                                <?
                                }
                                ?>
						</select></td>
						<td width="24">&nbsp;</td>
						<td width="225">&nbsp;</td>
					</tr>



					<tr>
						<td width="259">
						<p align="right">&nbsp;Área Atuação:&nbsp;&nbsp; </td>
						<td width="504"><select size="1" name="area" Onchange="atualiza(this.value);">
                              <option value="#">Selecione a Àrea de Atuação</option>
						  <?
                            $checa_menu = mysql_query("SELECT id_area_locacao,descricao FROM sgc_area_locacao order by descricao") or print mysql_error();
                                    while($dados_menu=mysql_fetch_array($checa_menu)){
                                    $id_area= $dados_menu["id_area_locacao"];
                                    $descricao= $dados_menu["descricao"];

                                ?>
     							<option value="<?echo $id_area?>"><?echo $descricao?></option>
                                <?
                           }
                        ?>
						</select></td>
						<td width="24">&nbsp;</td>
						<td width="225">&nbsp;</td>
					</tr>

                    <tr>
						<td width="259">
						<p align="right">Analista:&nbsp;&nbsp; </td>
						<td width="504">
                        <div id="atualiza">
						<select size="1" name="analista" Onchange="atualiza(this.value);">
                        <option value="#">Selecione a Àrea de Atuação</option>
                        </select>
                        </div>
                        </td>
						<td width="24">&nbsp;</td>
						<td width="225">&nbsp;</td>
					</tr>
    				<tr>
						<td width="259">
						<p align="right">Situação Chamado:&nbsp;&nbsp; </td>
						<td width="504"><select size="1" name="situacao" >
    						  <option value="Enviado Para Analista">Enviado Para Analista</option>
                            <!--  <option value="Não Verificado">Não Vereficado</option>-->
                            <!--  <option value="Fechado">Fechado</option>-->
     						<!--  <option value="Aguardando Resposta - Usuário">Aguardando Resposta - Usuário</option>-->
						</select></td>
						<td width="24">&nbsp;</td>
						<td width="225">&nbsp;</td>
					</tr>
					<tr>
						<td width="259">&nbsp;</td>
						<td width="504">&nbsp;</td>
						<td width="24">&nbsp;</td>
						<td width="225">&nbsp;</td>
					</tr>



					<tr>
						<td width="238">
						<p align="right">Anexo(s):&nbsp;&nbsp; </td>
						<td width="702" colspan="3">
						
						
						
                        <table border="0" width="100%" cellspacing="2" cellpadding="0">
                        	<tr>
                         		<td width="1%">&nbsp;</td>
                           		<td width="2%">&nbsp;</td>
                             		<td width="1%">&nbsp;</td>
                             		<td width="65%"><b>Nome Arquivo</b></td>
                             		<td width="2%">&nbsp;</td>
                               		<td width="11%"><b>Tamanho</b></td>
                                 		<td width="35%"><b>Data Inclusão</b></td>
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
		<p align="center"><a href="arquivos/<?echo $nome_ver?>">
		<img border="0" src="imgs/icone_download.gif" width="17" height="17"></a></td>
		<td width="1%"></td>
		<td width="65%"><?echo $nome_arquivo?></td>
		<td width="2%">&nbsp;</td>
		<td width="11%"><?echo $tamanho?></td>
		<td width="35%"><?echo $data?> </td>
		<td width="36%">
        <?

               //----------------Fornece autorização para manipulação do chamado-------------------//

                    $checa1 = mysql_query("SELECT atributo1 FROM sgc_parametros_sistema ") or print(mysql_error());
                    while($dados1=mysql_fetch_array($checa1)){
                          $iditematributo = $dados1['atributo1'];
                    }

                    $acesso=acesso($idusuario,$iditematributo);


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
						<td width="830" colspan="4">
						<p align="center"><b>Atualização</b></td>
					</tr>
					<tr>
						<td width="830" colspan="4">
						<p align="center">
						<textarea rows="6" name="atualizacao" style="background-color: #FFFFFF" cols="70"></textarea></td>
					</tr>
					<tr>
   				<td colspan="4">
	<fieldset style="padding: 2">
	<legend align="center"><b>Estatística do Usuário</b></legend>
				<table border="0" width="100%" cellpadding="0">
					<tr>
						<td width="213">

	<p align="right">Chamados criados:</td>
						<td width="611"><?echo $criador=contador($id_usuario_chamado,'sgc_chamado','id_usuario'); ?></td>
					</tr>
					<tr>
						<td width="213">

	<p align="right">Chamados em aberto: </td><td width="611"><?echo $abertos=contador($id_usuario_chamado,'sgc_chamado','id_usuario',"and status not in ('LIMBO','FECHADO')"); ?></td><td width="611"><? ?></td>
					</tr>
						<tr>
						<td width="213">

      	<p align="right">P/ Categorizar:</td><td width="611"><?echo $abertos=contador($id_usuario_chamado,'sgc_chamado','id_usuario',"and status='LIMBO' "); ?></td>
					</tr>
				</table>

					</td>
					</tr>
					<tr>
						<td width="823" colspan="4">&nbsp;</td>
					</tr>
				</table>

					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
			<p align="right"><input type="submit" value="Enviar" name="B1">&nbsp;</div></div>
</form></div>
<?
}

}elseif($acao_int=="registro"){

echo "1 "; echo  $id_chamado=$_POST['id_chamado'];                     echo "<BR>";
echo "2 "; echo  $id_usuario_chamado=$_POST['id_usuario_chamado'];     echo "<BR>";
echo "3 "; echo  $categoria=$_POST['categoria'];                       echo "<BR>";
echo "4 "; echo  $prioridade=$_POST['prioridade'];                     echo "<BR>";
echo "5 "; echo  $analista=$_POST['analista_change'];                  echo "<BR>";
echo "6 "; echo  $atualizacao=$_POST['atualizacao'];                   echo "<BR>";
echo "7 "; echo  $situacao=$_POST['situacao'];                         echo "<BR>";
echo "8 "; echo  $area_locacao=$_POST['area'];                         echo "<BR>";
echo "9 "; echo  $id_sla=id_sla();                                     echo "<BR>";
echo "10 "; echo $url_chamado=$_POST['url_chamado'];                   echo "<BR>";

//--------------Previsao------------------//
/*
$checa_previsao = mysql_query("
SELECT
 DATE_FORMAT(ADDTIME(sysdate(), SEC_TO_TIME(if(tipo_tempo='Dias',tempo*24*60,if(tipo_tempo='Horas',tempo*60,tempo))*60)),'%d/%m/%Y %H:%i:%s') TEMPO
,ADDTIME(sysdate(), SEC_TO_TIME(if(tipo_tempo='Dias',tempo*24*60,if(tipo_tempo='Horas',tempo*60,tempo))*60)) TEMPO_BANCO
FROM sgc_sla_analista_usuario
where id_sla_analista = $prioridade
") or print(mysql_error());
while($dados_previsao=mysql_fetch_array($checa_previsao)){
      $previsao = $dados_previsao['TEMPO_BANCO'];
      $cadas = mysql_query("UPDATE sgc_chamado set previsao='$previsao' where id_chamado = $id_chamado") or print(mysql_error());
}
*/
//---------------------------------------//


if(atributo('atributo10')=="ON"){
       $desc=tabelainfo($id_chamado,'sgc_chamado','descricao','id_chamado',$and);
       $link=tabelainfo('1','sgc_organizacao','link','id_organizacao','');


$nome_usuario_g=tabelainfo($id_usuario_chamado,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
$data_g = data_with_hour(datahoje("datahora"));
$prioridade_g=tabelainfo($prioridade,"sgc_sla_analista_usuario","descricao","id_sla_analista","");

$checa_local = mysql_query("
SELECT concat(dp.descricao,' - ', un.descricao,' - ',un.sigla )DESC_LOCAL FROM
  sgc_usuario us
, sgc_unidade un
, sgc_departamento dp
WHERE  us.id_usuario = $id_usuario_chamado
and us.id_departamento = dp.id_departamento
and un.codigo = us.id_unidade
") or print(mysql_error());
                  while($dados_local=mysql_fetch_array($checa_local)){
                  $desc_local= $dados_local['DESC_LOCAL'];
}


$mensagem_g="<p><font face='Courier New'  size='2'>
************************* DESIGNADO PARA VOCÊ ******************************<BR>
Usuário.............: $nome_usuario_g <BR>
Local...............: $desc_local <BR>
ID Chamado .........: $id_chamado<BR>
Data de abertura....: $data_g<BR>
---------------------------------------------------------------------------<BR>
Prioridade..........: $prioridade_g<BR>
---------------------------------------------------------------------------<BR>
Descrição:<BR>
$desc<BR>
<BR><a href='$link/sgc.php?action=vis_chamado.php&id_chamado=$id_chamado'>$link/sgc.php?action=vis_chamado.php&id_chamado=$id_chamado</a>
<BR>---------------------------------------------------------------------------<BR>
</font></p>";

       $email=send_mail_smtp("SGC - Você foi designado paro o chamado - # $id_chamado "
                         ,"$mensagem_g"
                         ,"$mensagem_g"
                         ,tabelainfo($analista,"sgc_usuario","email","id_usuario","")
                         ,tabelainfo($analista,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
       );
}



echo "<BR>";


if($situacao=="Não Verificado" or $situacao=="Enviado Para Analista" or $situacao=="Aguardando Resposta - Usuário"){
  $acao="Parametrizado";
  $cadas = mysql_query("UPDATE sgc_chamado set id_categoria=$categoria,status = '$situacao', id_area_locacao=$area_locacao,id_horario=$id_sla, id_impacto_analista=$prioridade  where id_chamado = $id_chamado") or print(mysql_error());
}else{
  $acao="Fechado";
  $cadas = mysql_query("UPDATE sgc_chamado set id_categoria=$categoria,status = '$situacao', id_area_locacao=$area_locacao,id_horario=$id_sla, id_impacto_analista=$prioridade  where id_chamado = $id_chamado") or print(mysql_error());
}

if($atualizacao==null){

echo "    ID CHAMADO: "; echo $id_chamado; echo "<BR>";
echo "      SITUACAO: "; echo $situacao; echo "<BR>";
echo "          ACAO: "; echo $acao; echo "<BR>";
echo "   ATUALIZACAO: "; echo $texto_at; echo "<BR>";
echo "    ID USUARIO: "; echo $idusuario; echo "<BR>";
echo "    PRIORIDADE: "; echo $prioridade; echo "<BR>";
echo "      ANALISTA: "; echo $analista; echo "<BR>";
echo "    ID USUARIO: "; echo $idusuario; echo "<BR>";
echo " ID USUARIO CH: "; echo $id_usuario_chamado; echo "<BR>";

$parametrizador=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
$texto_at="Parametrizado por: $parametrizador";

$cadas = mysql_query("insert into sgc_historico_chamado
 (id_chamado
 ,situacao
 ,acao
 ,atualizacao
 ,visto_service_desk
 ,id_service_desk
 ,prioridade
 ,id_suporte
 ,quem_criou_linha
 ,quem_criou
 ,data_criacao
 ,id_categoria

 )

values

 ($id_chamado
 ,'$situacao'
 ,'$acao'
 ,'$texto_at'
 ,sysdate()
 ,$idusuario
 ,$prioridade
 ,$analista
 ,$idusuario
 ,$id_usuario_chamado
 ,sysdate()
 ,$categoria
 )") or print(mysql_error());



 $ultimo_registro=ultimo_registro("id_historico","sgc_historico_chamado","id_historico");
 
 $cadas = mysql_query("UPDATE sgc_chamado set id_suporte = $analista, id_linha_historico= $ultimo_registro where id_chamado = $id_chamado") or print(mysql_error());

 $url=tabelainfo('1','sgc_organizacao','link','id_organizacao','');
$mensagem_g="<p><font face='Courier New'  size='2'>
*************************** CHAMADO PARAMETRIZADO ****************************<BR>
Parametrizado por...: $parametrizador <BR>
ID Chamado .........: $id_chamado<BR>
------------------------------------------------------------------------------
<BR><a href='http://$url/sgc.php?action=vis_chamado.php&id_chamado=$id_chamado'><font color='#000000'>http://$url/sgc.php?action=vis_chamado.php&id_chamado=$id_chamado</font></a><BR>
******************************************************************************<BR>
</font></p>";

if(atributo('atributo10')=="ON"){
 $email=send_mail_smtp("SGC - Chamado parametrizado - # $id_chamado"
                         ,"$mensagem_g"
                         ,"$mensagem_g"
                         ,tabelainfo($id_usuario_chamado,"sgc_usuario","email","id_usuario","")
                         ,tabelainfo($id_usuario_chamado,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );
}



}else{

$cadas = mysql_query("insert into sgc_historico_chamado
 (id_chamado
 ,situacao
 ,acao
 ,atualizacao
 ,visto_service_desk
 ,id_service_desk
 ,prioridade
 ,id_suporte
 ,quem_criou_linha
 ,quem_criou
 ,data_criacao
 ,id_categoria
 )
values
 ($id_chamado
 ,'$situacao'
 ,'$acao'
 ,'$atualizacao'
 ,sysdate()
 ,$idusuario
 ,$prioridade
 ,$analista
 ,$idusuario
 ,$id_usuario_chamado
 ,sysdate()
 ,$categoria)") or print(mysql_error());

$parametrizador=tabelainfo($idusuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
echo $texto_at="Parametrizado por: $parametrizador";


 $cadas = mysql_query("insert into sgc_historico_chamado
 (id_chamado
 ,situacao
 ,acao
 ,atualizacao
 ,visto_service_desk
 ,id_service_desk
 ,prioridade
 ,id_suporte
 ,quem_criou_linha
 ,quem_criou
 ,data_criacao
 ,id_categoria)
values
 ($id_chamado
 ,'$situacao'
 ,'$acao'
 ,'$texto_at'
 ,sysdate()
 ,$idusuario
 ,$prioridade
 ,$analista
 ,$idusuario
 ,$id_usuario_chamado
 ,sysdate()
 ,$categoria
 )") or print(mysql_error());




  $cadas = mysql_query("UPDATE sgc_chamado set id_suporte = $analista where id_chamado = $id_chamado") or print(mysql_error());
  
  $ultimo_registro=ultimo_registro("id_historico","sgc_historico_chamado","id_historico");

  $cadas = mysql_query("UPDATE sgc_chamado set id_categoria=$categoria,id_area_locacao=$area_locacao,id_horario=$id_sla, id_impacto_analista=$prioridade, id_suporte = $analista, id_linha_historico= $ultimo_registro where id_chamado = $id_chamado") or print(mysql_error());

$url=tabelainfo('1','sgc_organizacao','link','id_organizacao','');
$mensagem_g="<p><font face='Courier New'  size='2'>
*************************** CHAMADO PARAMETRIZADO ****************************<BR>
Parametrizado por...: $parametrizador <BR>
ID Chamado .........: $id_chamado<BR>
------------------------------------------------------------------------------
<BR><a href='http://$url/sgc.php?action=vis_chamado.php&id_chamado=$id_chamado'><font color='#000000'>http://$url/sgc.php?action=vis_chamado.php&id_chamado=$id_chamado</font></a><BR>
******************************************************************************<BR>
</font></p>";

if(atributo('atributo10')=="ON"){
 $email=send_mail_smtp("SGC - Chamado parametrizado - # $id_chamado"
                         ,"$mensagem_g"
                         ,"$mensagem_g"
                         ,tabelainfo($id_usuario_chamado,"sgc_usuario","email","id_usuario","")
                         ,tabelainfo($id_usuario_chamado,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")
                         );
}

}



$checa = mysql_query("SELECT atributo1 FROM sgc_parametros_sistema ") or print(mysql_error());
                  while($dados=mysql_fetch_array($checa)){
                  $iditematributo = $dados['atributo1'];
                  }

header("Location: ?action=$arquivo&acao_int=sucesso");

}elseif($acao_int=="sucesso"){

$checa = mysql_query("SELECT atributo1 FROM sgc_parametros_sistema ") or print(mysql_error());
                  while($dados=mysql_fetch_array($checa)){
                  $iditematributo = $dados['atributo1'];
                  }
?>
<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Sucesso Categorização:: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
				<table border="0" width="636" cellpadding="0">
					<tr>
						<td width="630">
					<p align="center">&nbsp;</td>
					</tr>
					<tr>
						<td width="630">
						<p align="center">Chamado categorizado com sucesso!</td>
					</tr>
					<tr>
						<td width="630">
						<p align="center"><font size="2"><a href="?action=console_servicedesk.php&id_item=<?echo $iditematributo?>">
						<font color="#000000">Voltar para o console</font></a></font></td>
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

}else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
