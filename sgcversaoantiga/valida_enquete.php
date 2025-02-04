<?php
OB_START();
session_start();


if($permissao=='ok'){
$arquivo="teste_email.php";
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Teste de e-mail";
$id_item=$_GET['id_item'];



if(!isset($acao_int)){
include("conf/Pagina.class.php");


$sql= mysql_query("
SELECT
COUNT(*) t
FROM sgc_chamado sc, sgc_historico_chamado hc,sgc_sla_analista_usuario sla
WHERE sc.status='Fechado'
and hc.id_historico = sc.id_linha_historico
and hc.nota_enquete is not null
and hc.prioridade = sla.id_sla_analista
");
    $dados=mysql_fetch_array($sql);
    $total=$dados['t'];


    $pagina = new Pagina();
    $pagina->setLimite(3);

    $totalRegistros = $total;
	$linkPaginacao ="?action=valida_enquete.php&id_item=$id_item";


?><body style="text-align: center">

<div align="center">
	<table class="border" cellSpacing="0" cellPadding="0" width="100%" border="0">
		<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<td style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
			<table cellSpacing="1" cellPadding="5" width="100%" border="0">
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="info" align="middle"><b>:: Avaliação de Atendimento :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">
					<table border="0" width="100%">
						<tr>
							<td width="33">&nbsp;</td>
							<td>
							<? $id_questao=null;
                               $replica=null;
                               $data_replica=null;
                               $questao=null;
							  $checa = mysql_query("SELECT
                               hc.nota_enquete
                               ,sc.titulo
                               ,sc.id_chamado
                               ,hc.obs_enquete
                               ,concat(date_format(sc.data_criacao,'%d/%m/%y %H:%i'),' / ',date_format(hc.data_criacao,'%d/%m/%y %H:%i')) abfec
                               ,sla.descricao

                               ,if((SELECT 1 FROM sgc_questionario_analista WHERE id_chamado = sc.id_chamado)=1,
                               (SELECT questao FROM sgc_questionario_analista WHERE id_chamado = sc.id_chamado),
                               'NAO-HA')questao

                               ,if((SELECT 1 FROM sgc_questionario_analista WHERE id_chamado = sc.id_chamado)=1,
                               (SELECT autor_questao FROM sgc_questionario_analista WHERE id_chamado = sc.id_chamado),
                               'NAO-HA')autor_questao

                               ,if((SELECT 1 FROM sgc_questionario_analista WHERE id_chamado = sc.id_chamado)=1,
                               (SELECT analista FROM sgc_questionario_analista WHERE id_chamado = sc.id_chamado),
                               'NAO-HA')analista

                               ,if((SELECT 1 FROM sgc_questionario_analista WHERE id_chamado = sc.id_chamado)=1,
                               (SELECT date_format(data_questao,'%d/%m/%y %h:%i') FROM sgc_questionario_analista WHERE id_chamado = sc.id_chamado),
                               'NAO-HA')data_questao
                               
                               ,if((SELECT 1 FROM sgc_questionario_analista WHERE id_chamado = sc.id_chamado)=1,
                               (SELECT id_questao FROM sgc_questionario_analista WHERE id_chamado = sc.id_chamado),
                               'NAO-HA')id_questao

                                ,if((SELECT 1 FROM sgc_questionario_usuario WHERE id_chamado = sc.id_chamado)=1,
                               (SELECT questao FROM sgc_questionario_usuario WHERE id_chamado = sc.id_chamado),
                               'NAO-HA')questao_usuario

                               ,if((SELECT 1 FROM sgc_questionario_usuario WHERE id_chamado = sc.id_chamado)=1,
                               (SELECT autor_questao FROM sgc_questionario_usuario WHERE id_chamado = sc.id_chamado),
                               'NAO-HA')autor_questao_usuario

                               ,if((SELECT 1 FROM sgc_questionario_usuario WHERE id_chamado = sc.id_chamado)=1,
                               (SELECT analista FROM sgc_questionario_usuario WHERE id_chamado = sc.id_chamado),
                               'NAO-HA')analista_usuario

                               ,if((SELECT 1 FROM sgc_questionario_usuario WHERE id_chamado = sc.id_chamado)=1,
                               (SELECT date_format(data_questao,'%d/%m/%y %h:%i') FROM sgc_questionario_usuario WHERE id_chamado = sc.id_chamado),
                               'NAO-HA')data_questao_usuario

                               ,if((SELECT 1 FROM sgc_questionario_usuario WHERE id_chamado = sc.id_chamado)=1,
                               (SELECT id_questao FROM sgc_questionario_usuario WHERE id_chamado = sc.id_chamado),
                               'NAO-HA')id_questao_usuario



                               FROM sgc_chamado sc, sgc_historico_chamado hc,sgc_sla_analista_usuario sla
                               WHERE sc.status='Fechado'
                               and hc.id_historico = sc.id_linha_historico
                               and hc.nota_enquete is not null
                               and hc.prioridade = sla.id_sla_analista order by sc.data_criacao desc
                           limit ".$pagina->getPagina($_GET['pagina']).", ".$pagina->getLimite());
                           while($dados=mysql_fetch_array($checa)){
                            $nota = $dados['nota_enquete'];
                            $titulo_ch = $dados['titulo'];
                            $id = $dados['id_chamado'];
                            $obs_enquete = $dados['obs_enquete'];
                            $abfec = $dados['abfec'];
                            $descricao_sla = $dados['descricao'];
                            $questao = $dados['questao'];
                            $idusuariox = $dados['analista'];
                            $data_questao = $dados['data_questao'];
                            $autor_questao = $dados['autor_questao'];
                            $id_questao = $dados['id_questao'];
                            
                            $questao_usuario = $dados['questao_usuario'];
                            $autor_questao_usuario = $dados['autor_questao_usuario'];
                            $data_questao_usuario = $dados['data_questao_usuario'];
                            $id_questao_usuario = $dados['id_questao_usuario'];

                            if($nota<70){
                            $cor_nota="#FF0000";
                            }else{
                            $cor_nota="#0000FF";
                            }
                            
                            ?>

							<table border="1" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse" bordercolor="#C0C0C0">
								<tr>
									<td>
									<table border="0" width="863" cellspacing="0" cellpadding="0">
										<tr>
											<td width="48">
											<p align="center"><b>ID</b></td>
											<td width="451">
											<p align="center"><b>&nbsp;Titulo</b></td>
											<td width="117">
											<p align="center"><b>Prioridade</b></td>
											<td width="187">
											<p align="center"><b>&nbsp;Abertura/Fechamento</b></td>
											<td width="59">
											<p align="center"><b>&nbsp;Nota</b></td>
										</tr>
										<tr>
											<td width="48">
											<p align="center">&nbsp;<?echo $id?></td>
											<td width="451">
											<p align="center"><a href="?action=vis_chamado.php&id_chamado=<?echo $id?>">
											<font color="#000000"><?echo $titulo_ch?></font></a></td>
											<td width="117">
											<p align="center"><?Echo $descricao_sla?></td>
											<td width="187">
											<p align="center"><?Echo $abfec?></td>
											<td width="59">
											<p align="center">
											<font color="<?echo $cor_nota?>"><?echo $nota?></font></td>
										</tr>
										<tr>
											<td width="48">&nbsp;</td>
											<td width="451">&nbsp;</td>
											<td width="117">&nbsp;</td>
											<td width="187">&nbsp;</td>
											<td width="59">&nbsp;</td>
										</tr>
										<tr>
											<td width="852" colspan="5">
											<p align="center"><b><?echo $obs_enquete?></b></td>
										</tr>
										<tr>
											<td width="852" colspan="5">
											<table border="0" width="100%" cellspacing="0" cellpadding="0" height="23">
												<tr>
													<td>&nbsp;</td>
													<td width="167">
													<p align="center">
                                                    <?
                                                    if($questao=="NAO-HA"){
                                                    ?>
                                                    <a href="?action=valida_enquete.php&acao_int=quest_analista&id_chamado=<?echo $id?>&id_item=<?echo $id_item?>">
                                                    <font color="#000000">Questionar Analista</font></a>
                                                    <?
                                                    }else{
                                                    ?>
                                                    <font color="#000000">Questionar Analista</font>
                                                    <?
                                                    }
                                                    ?>
                                                    </td>
													<td width="164">
													<p align="center">

                                                    <?
                                                    if($questao_usuario=="NAO-HA"){
                                                    ?>
                                                    &nbsp;<a href="?action=valida_enquete.php&acao_int=quest_usuario&id_chamado=<?echo $id?>&id_item=<?echo $id_item?>">
                                                    <font color="#000000">Responder	Usuário</font></a>
                                                    <?
                                                    }else{
                                                     ?>
                                                    &nbsp;<font color="#000000">Responder Usuário</font></a>
                                                    <?
                                                    }
                                                    ?>

                                                    </td>
													<td>&nbsp;</td>
												</tr>
												<tr>
											    <td width="852" colspan="5">


                                                </td>
										        </tr>
											</table>
											</td>
										</tr>
									    <tr>
											<td width="852" colspan="5">
											&nbsp;</td>
										</tr>
                                        <?
                                        if($questao!="NAO-HA"){
                                        ?>
                                        <tr>
										<td width="852" colspan="5" bgcolor="#FFFFFF">&nbsp;<b>
		    							<?

                                        echo $usuariox=ucwords(strtolower(tabelainfo($autor_questao,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")));

                                        ?>
                                        </b>
                                        Em
                                        <b><?echo $data_questao?></b> Questiona Analista...<p>&nbsp;<i><?echo$questao?></i><br></td>
										</tr>

                                        <tr>
											<td width="852" colspan="5" bgcolor="#FFFFFF">
											&nbsp;</td>
										</tr>
                                        <?
                                        }
                                        if($questao!="NAO-HA"){
                                        $data_replica=null;
                                        $checa_re = mysql_query("SELECT id_analista ,replica ,date_format(data,'%d/%m/%y %H:%i')data FROM sgc_replica_questao_analista WHERE id_questao = $id_questao");
                                        while($dados_re=mysql_fetch_array($checa_re)){
                                        $id_analista = $dados_re['id_analista'];
                                        $replica = $dados_re['replica'];
                                        $data_replica = $dados_re['data'];
                                        }
                                        if($data_replica!=null){
                                        
                                        ?>
                                    	<tr>
											<td width="852" colspan="5" bgcolor="#E8FFE8">
											&nbsp; Analista<b>
                                            <?
                                             echo $usuarioy=ucwords(strtolower(tabelainfo($id_analista,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")));

                                            ?>
                                            </b>
                                            Em <b><?echo $data_replica?></b> Responde...<p>&nbsp;<i>
											<?echo nl2br($replica)?></i><br>
                                            &nbsp;</td>
										</tr>
										<?
                                         }
                                        }
										?>
										

         								<?
                                        if($questao_usuario!="NAO-HA"){
                                        ?>
                                        <tr>
										<td width="852" colspan="5" bgcolor="#FFFF00">&nbsp;<b>
		    							<?

                                        echo $usuariox=ucwords(strtolower(tabelainfo($autor_questao_usuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")));

                                        ?>
                                        </b>
                                        Em
                                        <b><?echo $data_questao_usuario?></b> Questiona Usuário...<p>&nbsp;<i><?echo$questao_usuario?></i><br></td>
										</tr>

                                        <tr>
											<td width="852" colspan="5" bgcolor="#FFFF00">
											&nbsp;</td>
										</tr>
                                        <?
                                        $questao_usuario="NAO-HA";

                                        }
                                        
                                        
                                        
                                        if($questao_usuario!="NAO-HA"){
                                        $data_replica_usuario=null;
                                        $checa_re = mysql_query("SELECT id_usuario ,replica ,date_format(data,'%d/%m/%y %H:%i')data FROM sgc_replica_questao_usuario WHERE id_questao = $id_questao_usuario");
                                        while($dados_re=mysql_fetch_array($checa_re)){
                                        $id_usuario = $dados_re['id_usuario'];
                                        $replica = $dados_re['replica'];
                                        $data_replica = $dados_re['data'];
                                        }
                                        if($data_replica!=null){

                                        ?>
                                    	<tr>
											<td width="852" colspan="5" bgcolor="#FFCC00">
											&nbsp; Usuário <b>
                                            <?
                                             echo $usuarioy=ucwords(strtolower(tabelainfo($id_usuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","")));

                                            ?>
                                            </b>
                                            Em <b><?echo $data_replica?></b> Responde...<p>&nbsp;<i>
											<?echo nl2br($replica)?></i><br>
                                            &nbsp;</td>
										</tr>
										<?
                                         }
                                        $replica=null;
                                        $data_replica=null;
                                        }
										?>
										
										
										
										
									</table>
									</td>
								</tr>
								
							</table>
							                         <BR>
                          <?
                          }
                          ?>



							</td>
     		<td width="34">&nbsp;</td>
						</tr>
							<tr>
							<td width="33">&nbsp;</td>
							<td>
							&nbsp;</td>
							<td width="34">&nbsp;</td>
						</tr>



					</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>
<br>
<p align="center">
<?
Pagina::configuraPaginacao($_GET['cj'],$_GET['pagina'],$totalRegistros,$linkPaginacao, $pagina->getLimite(), $_GET['direcao']);


}elseif($acao_int=="msg_analista_post"){
 $id_item=$_GET['id_item'];
 $titulo=$_GET['titulo'];
 $msg=$_GET['msg'];
 
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
					<td class="cat" align="center"><?echo $msg?><br>
					<br>
					<a href="sgc.php?action=valida_enquete.php&id_item=<?echo $id_item?>"><font color="#000000">Voltar para avaliador</font></a><br>
                 &nbsp;</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
 </div>
<?


}elseif($acao_int=="msg_usuario_post"){
 $id_item=$_GET['id_item'];
 $titulo=$_GET['titulo'];
 $msg=$_GET['msg'];

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
					<td class="cat" align="center"><?echo $msg?><br>
					<br>
					<a href="sgc.php?action=valida_enquete.php&id_item=<?echo $id_item?>"><font color="#000000">Voltar para avaliador</font></a><br>
                 &nbsp;</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
 </div>
<?
}elseif($acao_int=="quest_analista_post"){
 $id_item=$_POST['id_item'];
 $id_chamado=$_POST['id_chamado'];
 $questao=$_POST['questao'];
 
 
 $id_suporte=tabelainfo($id_chamado,"sgc_historico_chamado","id_suporte","id_chamado"," order by id_historico desc limit 1");
 $id_usuario=tabelainfo($id_chamado,"sgc_chamado","quem_criou","id_chamado","");


 $nome_g=tabelainfo($id_suporte,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
 $email_g=tabelainfo($id_suporte,"sgc_usuario","email","id_usuario","");


if(tabelainfo($id_chamado,"sgc_questionario_analista","id_chamado","id_chamado","")!=$id_chamado){

 $cadas = mysql_query("INSERT INTO sgc_questionario_analista (id_chamado, data_questao, questao, autor_questao, analista) VALUES ($id_chamado,sysdate(),'$questao',$idusuario,$id_suporte)") or print(mysql_error());


 
if(atributo('atributo10')=="ON"){

 $id_autor=tabelainfo($id_chamado,"sgc_questionario_analista","autor_questao","id_chamado","");
 $nome_autor=tabelainfo($id_usuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");

 $titulo=tabelainfo($id_chamado,"sgc_chamado","titulo","id_chamado","");


$mensagem_g="<p><font face='Courier New'  size='2'>
*************************** CHAMADO FOI QUESTIONADO ***********************<BR>
Autor ..............: $nome_autor<BR>
ID Chamado .........: $id_chamado<BR>
---------------------------------------------------------------------------<BR>
Titulo Chamado .....: $titulo<BR>
---------------------------------------------------------------------------<BR>
Questão:
$questao<BR>
---------------------------------------------------------------------------<BR>
</font></p>";

$email=send_mail_smtp("SGC - Seu chamado #$id_chamado Foi questionado! Responda Urgente!",$mensagem_g,$mensagem_g,$email_g,$nome_g);



if(atributo('atributo10')=="ON"){

  $emails_gerencia = atributo('atributo24');
  $amails_gerencia = split ('[;]',$emails_gerencia);

  foreach($amails_gerencia as $valor){
  $nome_envio=tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","email","");
  $email=send_mail_smtp("SGC - O chamado de Nº $id_chamado Foi questionado!",$mensagem_g,$mensagem_g,$valor,$nome_envio);
   }
  }
 }
header("Location: ?action=valida_enquete.php&acao_int=msg_analista_post&id_item=$id_item&titulo=Sucesso&msg=Sua questão foi enviada para o analista com sucesso!");
}else{
header("Location: ?action=valida_enquete.php&acao_int=msg_analista_post&id_item=$id_item&titulo=Atenção&msg=Esse chamado já foi questionado!");
}
}elseif($acao_int=="quest_usuario"){

$id_chamado_quest=$_GET['id_chamado'];
$id_item=$_GET['id_item'];


$permissao_item=acesso($idusuario,$id_item);

if($permissao_item=="OK"){


							  $checa = mysql_query("SELECT
                               hc.nota_enquete
                               ,sc.titulo
                               ,sc.id_chamado
                               ,hc.obs_enquete
                               ,concat(date_format(sc.data_criacao,'%d/%m/%y %H:%i'),' / ',date_format(hc.data_criacao,'%d/%m/%y %H:%i')) abfec
                               ,sla.descricao
                               FROM sgc_chamado sc, sgc_historico_chamado hc,sgc_sla_analista_usuario sla
                               WHERE sc.id_chamado = $id_chamado_quest
                               and sc.status='Fechado'
                               and hc.id_historico = sc.id_linha_historico
                               and hc.nota_enquete is not null
                               and hc.prioridade = sla.id_sla_analista order by sc.data_criacao
                           ");
                           while($dados=mysql_fetch_array($checa)){
                            $nota = $dados['nota_enquete'];
                            $titulo_ch = $dados['titulo'];
                            $id = $dados['id_chamado'];
                            $obs_enquete = $dados['obs_enquete'];
                            $abfec = $dados['abfec'];
                            $descricao_sla = $dados['descricao'];

                            if($nota<70){
                            $cor_nota="#FF0000";
                            }else{
                            $cor_nota="#0000FF";
                            }
                            }
?>

<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.questao.value=="")
    {
        alert ("\nVocê precisa digitar sua pergunta.");
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
					<td class="info" align="middle"><b>:: Responder Usuário :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">


                            <table border="1" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse" bordercolor="#C0C0C0">
								<tr>
									<td align="center">
									<table border="0" width="863" cellspacing="0" cellpadding="0">
										<tr>
											<td width="48">
											<p align="center"><b>ID</b></td>
											<td width="451">
											<p align="center"><b>&nbsp;Titulo</b></td>
											<td width="117">
											<p align="center"><b>Prioridade</b></td>
											<td width="187">
											<p align="center"><b>
											&nbsp;Abertura/Fechamento</b></td>
											<td width="59">
											<p align="center"><b>&nbsp;Nota</b></td>
										</tr>
										<tr>
											<td width="48">
											<p align="center">&nbsp;<?echo $id?></td>
											<td width="451">
											<p align="center"><a href="?action=vis_chamado.php&id_chamado=<?echo $id?>">
											<font color="#000000"><?echo $titulo_ch?></font></a></td>
											<td width="117">
											<p align="center"><?Echo $descricao_sla?></td>
											<td width="187">
											<p align="center"><?Echo $abfec?></td>
											<td width="59">
											<p align="center">
											<font color="<?echo $cor_nota?>"><?echo $nota?></font></td>
										</tr>
         	<tr>
											<td width="48">&nbsp;</td>
											<td width="451">&nbsp;</td>
											<td width="117">&nbsp;</td>
											<td width="187">&nbsp;</td>
											<td width="59">&nbsp;</td>
										</tr>
										<tr>
											<td width="852" colspan="5">
											<p align="center"><b><?echo $obs_enquete?></b></td>
										</tr>
										<tr>
											<td width="852" colspan="5">

											</td>
										</tr>
									</table>
									</td>
								</tr>

							</table>
					</td>
				</tr>
				<form method="POST" action="?action=valida_enquete.php&acao_int=quest_usuario_post" onsubmit='return valida_dados(this)'>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
       								 <input type='hidden' name='id_item' value='<?echo $id_item?>'>
                                     <input type='hidden' name='id_chamado' value='<?echo $id?>'>

                    <td class="cat" align="center">
									<table border="0" width="100%" cellspacing="0" cellpadding="0">
										<tr>
											<td>
											<p align="center">Você pode responder ou questionar a nota e comentário feito pelo usuário</td>
										</tr>
										<tr>
											<td>
											<p align="center">
											<textarea rows="8" name="questao" cols="104" style="background-color: #FFFFFF"></textarea></td>
										</tr>
    		                            <input type='hidden' name='id_item' value='<?echo $id_item?>'>
    		                            <input type='hidden' name='id_chamado' value='<?echo $id_chamado_quest?>'>
										<tr>
											<td>
											<p align="center">Questionar nota?
											<select size="1" name="quest_nota">
                                           	<option value="NAO">Não</option>
        									<option value="SIM">Sim</option>
											</select></td>
										</tr>
										<tr>
											<td>
											<p align="center"><br>
											<input type="submit" value="Enviar" name="B1"><br>
                                        &nbsp;</td>
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


}
}elseif($acao_int=="quest_usuario_post"){

 $id_item=$_POST['id_item'];
 $id_chamado=$_POST['id_chamado'];
 $questao=$_POST['questao'];
 $quest_nota=$_POST['quest_nota'];

 $id_suporte=tabelainfo($id_chamado,"sgc_historico_chamado","id_suporte","id_chamado"," order by id_historico desc limit 1");
 $id_usuario=tabelainfo($id_chamado,"sgc_chamado","quem_criou","id_chamado","");
 $nome_g=tabelainfo($id_usuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
 $email_g=tabelainfo($id_usuario,"sgc_usuario","email","id_usuario","");


if(tabelainfo($id_chamado,"sgc_questionario_usuario","id_chamado","id_chamado","")!=$id_chamado){

 $cadas = mysql_query("INSERT INTO sgc_questionario_usuario
                      (id_chamado
                      , data_questao
                      , questao
                      , questionar_nota
                      , autor_questao
                      , analista
                      , id_usuario_dono)
                      VALUES
                      ($id_chamado
                      ,sysdate()
                      ,'$questao'
                      ,'$quest_nota'
                      ,$idusuario
                      ,$id_suporte
                      ,$id_usuario)") or print(mysql_error());



if(atributo('atributo10')=="ON"){

 $id_autor=tabelainfo($id_chamado,"sgc_questionario_analista","autor_questao","id_chamado","");
 $nome_autor=tabelainfo($id_usuario,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");

 $titulo=tabelainfo($id_chamado,"sgc_chamado","titulo","id_chamado","");

 if($quest_nota=="SIM"){
     $titulo_email="*************************** SUA NOTA FOI QUESTIONADA **************************";
 }else{
     $titulo_email="*************************** INFORMAÇÃO SOBRE CHAMADO **************************";
 }


$mensagem_g="<p><font face='Courier New'  size='2'>


$titulo_email<BR>
Autor ..............: $nome_autor<BR>
ID Chamado .........: $id_chamado<BR>
---------------------------------------------------------------------------<BR>
Titulo Chamado .....: $titulo<BR>
---------------------------------------------------------------------------<BR>
Questão:
$questao<BR>
---------------------------------------------------------------------------<BR>
</font></p>";

$email=send_mail_smtp("SGC - Seu chamado #$id_chamado Foi questionado! Responda Urgente!",$mensagem_g,$mensagem_g,$email_g,$nome_g);



if(atributo('atributo10')=="ON"){

  $emails_gerencia = atributo('atributo24');
  $amails_gerencia = split ('[;]',$emails_gerencia);

  foreach($amails_gerencia as $valor){
  $nome_envio=tabelainfo($valor,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","email","");
  $email=send_mail_smtp("SGC - O chamado de Nº $id_chamado Foi questionado!",$mensagem_g,$mensagem_g,$valor,$nome_envio);
   }
  }
 }
header("Location: ?action=valida_enquete.php&acao_int=msg_usuario_post&id_item=$id_item&titulo=Sucesso&msg=Sua questão foi enviada para o usuário com sucesso!");
}else{
header("Location: ?action=valida_enquete.php&acao_int=msg_usuario_post&id_item=$id_item&titulo=Atenção&msg=Esse chamado já foi questionado!");
}


}elseif($acao_int=="quest_analista"){

$id_chamado_quest=$_GET['id_chamado'];
$id_item=$_GET['id_item'];


$permissao_item=acesso($idusuario,$id_item);

if($permissao_item=="OK"){


							  $checa = mysql_query("SELECT
                               hc.nota_enquete
                               ,sc.titulo
                               ,sc.id_chamado
                               ,hc.obs_enquete
                               ,concat(date_format(sc.data_criacao,'%d/%m/%y %H:%i'),' / ',date_format(hc.data_criacao,'%d/%m/%y %H:%i')) abfec
                               ,sla.descricao
                               FROM sgc_chamado sc, sgc_historico_chamado hc,sgc_sla_analista_usuario sla
                               WHERE sc.id_chamado = $id_chamado_quest
                               and sc.status='Fechado'
                               and hc.id_historico = sc.id_linha_historico
                               and hc.nota_enquete is not null
                               and hc.prioridade = sla.id_sla_analista order by sc.data_criacao
                           ");
                           while($dados=mysql_fetch_array($checa)){
                            $nota = $dados['nota_enquete'];
                            $titulo_ch = $dados['titulo'];
                            $id = $dados['id_chamado'];
                            $obs_enquete = $dados['obs_enquete'];
                            $abfec = $dados['abfec'];
                            $descricao_sla = $dados['descricao'];

                            if($nota<70){
                            $cor_nota="#FF0000";
                            }else{
                            $cor_nota="#0000FF";
                            }
                            }
?>

<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.questao.value=="")
    {
        alert ("\nVocê precisa digitar sua pergunta.");
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
					<td class="info" align="middle"><b>:: Questionar Analista :: </b></td>
				</tr>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
					<td class="cat" align="center">


                            <table border="1" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse" bordercolor="#C0C0C0">
								<tr>
									<td align="center">
									<table border="0" width="863" cellspacing="0" cellpadding="0">
										<tr>
											<td width="48">
											<p align="center"><b>ID</b></td>
											<td width="451">
											<p align="center"><b>&nbsp;Titulo</b></td>
											<td width="117">
											<p align="center"><b>Prioridade</b></td>
											<td width="187">
											<p align="center"><b>
											&nbsp;Abertura/Fechamento</b></td>
											<td width="59">
											<p align="center"><b>&nbsp;Nota</b></td>
										</tr>
										<tr>
											<td width="48">
											<p align="center">&nbsp;<?echo $id?></td>
											<td width="451">
											<p align="center"><a href="?action=vis_chamado.php&id_chamado=<?echo $id?>">
											<font color="#000000"><?echo $titulo_ch?></font></a></td>
											<td width="117">
											<p align="center"><?Echo $descricao_sla?></td>
											<td width="187">
											<p align="center"><?Echo $abfec?></td>
											<td width="59">
											<p align="center">
											<font color="<?echo $cor_nota?>"><?echo $nota?></font></td>
										</tr>
         	<tr>
											<td width="48">&nbsp;</td>
											<td width="451">&nbsp;</td>
											<td width="117">&nbsp;</td>
											<td width="187">&nbsp;</td>
											<td width="59">&nbsp;</td>
										</tr>
										<tr>
											<td width="852" colspan="5">
											<p align="center"><b><?echo $obs_enquete?></b></td>
										</tr>
										<tr>
											<td width="852" colspan="5">

											</td>
										</tr>
									</table>
									</td>
								</tr>

							</table>
					</td>
				</tr>
				<form method="POST" action="?action=valida_enquete.php&acao_int=quest_analista_post" onsubmit='return valida_dados(this)'>
				<tr style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px">
       								 <input type='hidden' name='id_item' value='<?echo $id_item?>'>
                                     <input type='hidden' name='id_chamado' value='<?echo $id?>'>

                    <td class="cat" align="center">
									<table border="0" width="100%" cellspacing="0" cellpadding="0">
										<tr>
											<td>
											<p align="center">Faça sua pergunta,
											sugestão ou crítica para o analista</td>
										</tr>
										<tr>
											<td>
											<p align="center">
											<textarea rows="8" name="questao" cols="104" style="background-color: #FFFFFF"></textarea></td>
										</tr>
										<tr>
											<td>
											<p align="center"><br>
											<input type="submit" value="Enviar" name="B1"><br>
                                        &nbsp;</td>
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


}
}
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
