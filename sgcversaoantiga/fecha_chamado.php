<?php
OB_START();
session_start();


if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Fechar chamados em lote";
$titulo_listar="";
$id_item=$_GET['id_item'];





if(!isset($acao_int)){
?>
<script language='javascript'>
function valida_dados (nomeform)
{
    if (nomeform.id.value=="")
    {
        alert ("\nDigite o ID do usuário.");

        document.form1.id.style.borderColor="#FF0000";
        document.form1.id.style.borderWidth="1px solid";

        nomeform.id.focus();
        return false;
    }
     if (nomeform.msg.value=="")
    {
        alert ("\nDigite a mensagem de fechamento do chamado");

        document.form1.msg.style.borderColor="#FF0000";
        document.form1.msg.style.borderWidth="1px solid";

        nomeform.msg.focus();
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

<form method="POST" name="form1" action="sgc.php?action=fecha_chamado.php&acao_int=fecha_chamado" onSubmit="return valida_dados(this)">
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
					<table border="0" width="587" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" height="23">
							<p align="center"><font color="#FF0000"><?echo $msg?></font></td>
                            <input type='hidden' name='id_item' value='<?echo $id_item?>'></td>
						</tr>
                        <tr>
							<td width="145">
							<p align="right">ID Usuário:&nbsp;</td>
							<td width="442" height="23">
							<input size="5" name="id" value="<?echo $_SESSION['id']?>" style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="5">
                            </td>
						</tr>

						<tr>
							<td width="587" colspan="2" height="23">
							<p align="center">Mensagem Fechamento</td>
						</tr>
						<tr>
							<td height="23" width="145">
							<p align="right">&nbsp; </td>
							<td height="23" width="442">
							<textarea rows="6" name="msg" cols="67" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; background: #FFFFFF;"><?echo $_SESSION['ajuda']?></textarea></td>
						</tr>
						<tr>
							<td colspan="2">
							&nbsp;</td>
						</tr>
						  <tr>
							<td width="145">
							<p align="right">Data Inicial:&nbsp;</td>
							<td width="442" height="23">
							<input size="10" name="datainicio"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="10">
                            </td>
						</tr>
						  <tr>
							<td width="145">
							<p align="right">Data Final:&nbsp;</td>
							<td width="442" height="23">
							<input size="10" name="datafinal"  style=" <?echo $borda?> font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;" maxlength="10">
                            </td>
						</tr>

       						<tr>
							<td width="587" colspan="2" height="23">
							<p align="center">Se as datas estivem em branco serão fechados todos os chamados</td>
						</tr>
						<tr>
							<td colspan="2">
							<input type="submit" value="Fechar Chamado" name="submit" style="font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000; float: right; background: #C0C0C0"></td>
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
}elseif($acao_int=="fecha_chamado"){

       $id_item=$_POST['id_item'];
       $permissao_item=acesso($idusuario,$id_item);


if($permissao_item=="OK"){
      echo "aqui - ID : ";

      echo $id=$_POST['id'];
       session_register('id');

       $msg=$_POST['msg'];
       session_register('msg');

       $datainicio=$_POST['datainicio'];
       session_register('datainicio');
       $datafinal=$_POST['datafinal'];
       session_register('datafinal');

       If( (trim($datainicio)) !=null   && (trim($datafinal))==null  ){
       
       $sql=" AND DATE_FORMAT(c.data_criacao ,'%d/%m/%Y') >= '$datainicio' ";

       }elseif( (trim($datainicio)) ==null  && (trim($datafinal))  ){
       
       $sql=" AND DATE_FORMAT(c.data_criacao ,'%d/%m/%Y') <= '$datafinal' ";
       
       }elseif( (trim($datainicio)) !=null  && (trim($datafinal)) !=null  ){
       
        $sql=" AND DATE_FORMAT(c.data_criacao ,'%d/%m/%Y') BETWEEN '$datainicio' AND '$datafinal' ";
       
       }else{
        $sql="";
       }
       

        echo "<BR>$sql<BR>";


        $select = mysql_query("SELECT
         c.id_chamado
         ,us.id_usuario
         ,us.primeiro_nome
         ,c.titulo
         ,c.data_criacao
         ,ch.data_criacao
         ,al.descricao
         ,c.status
         ,c.id_linha_historico
         FROM
         sgc_historico_chamado ch
         ,sgc_chamado c
         ,sgc_area_locacao al
         ,sgc_usuario us
         WHERE
                 ch.id_chamado = c.id_chamado
             AND ch.id_historico = c.id_linha_historico
             AND c.id_area_locacao = al.id_area_locacao
             AND ch.id_suporte = us.id_usuario
             AND us.id_usuario = $id
             $sql
             AND al.descricao IN ('Desenvolvimento XFac','Plantão XFac','Suporte NF-e')
             AND c.status NOT IN ('Fechado','Concluido')") or print(mysql_error());
             $count=0;
             while($dados=mysql_fetch_array($select)){
              echo $id_objeto = $dados['id_chamado'];
              $id_linha_historico = $dados['id_linha_historico'];
              $primeiro_nome = $dados['primeiro_nome'];
              $count++;
              
              $insert = mysql_query("INSERT INTO sgc_historico_chamado
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

                             SELECT
                                              id_chamado
                                             , situacao
                                             , acao
                                             ,'$msg'
                                             ,visto_service_desk
                                             ,id_service_desk
                                             ,visto_suporte
                                             ,prioridade
                                             ,id_suporte
                                             ,$id
                                             ,quem_criou
                                             ,sysdate()
                                             ,id_categoria
                                             FROM sgc_historico_chamado WHERE id_historico = $id_linha_historico and id_chamado =$id_objeto

                              ")or print(mysql_error());
                       $ultimo_registro=ultimo_registro("id_historico","sgc_historico_chamado","id_historico");
                       $cadas = mysql_query("UPDATE sgc_chamado set id_linha_historico = $ultimo_registro where id_chamado = $id_objeto") or print(mysql_error());

                       $insert = mysql_query("INSERT INTO sgc_historico_chamado
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

                               SELECT
                                              id_chamado
                                             ,'Fechado'
                                             ,'Fechado'
                                             ,'Fechado Pelo usuário: $primeiro_nome'
                                             ,visto_service_desk
                                             ,id_service_desk
                                             ,visto_suporte
                                             ,prioridade
                                             ,id_suporte
                                             ,$id
                                             ,quem_criou
                                             ,sysdate()
                                             ,id_categoria FROM sgc_historico_chamado WHERE id_historico = $id_linha_historico and id_chamado =$id_objeto

                              ")or print(mysql_error());
                       $ultimo_registro=ultimo_registro("id_historico","sgc_historico_chamado","id_historico");
                       $cadas = mysql_query("UPDATE sgc_chamado set status = 'Fechado', id_linha_historico=$ultimo_registro where id_chamado = $id_objeto") or print(mysql_error());


             }


        session_unregister('id');
        session_unregister('msg');
        session_register('datainicio');
        session_register('datafinal');
        
        
        $texto="Fechado(s): $count";
        
        header("Location: ?action=fecha_chamado.php&msg=$texto&id_item=$id_item");



   }else{
     $msg="Você não tem permissão para esta operação";
     header("Location: ?action=fecha_chamado.php&id_item=$id_item&msg=$msg");
    }
  }
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
