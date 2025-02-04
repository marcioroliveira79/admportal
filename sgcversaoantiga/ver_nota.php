<?php
OB_START();
session_start();



if($permissao=='ok'){
$acao_int=$_GET['acao_int'];
$msg=$_GET['msg'];

$titulo="Nota Técnica";
$id_item=$_GET['id_item'];
$id_nota=$_GET['id_nota'];
$id_chamado=$_GET['id_chamado'];



if(!isset($acao_int)){

                                     $checa = mysql_query("
                                     SELECT
                                     nt.id_chamado
                                     , nt.id_nota
                                     , nt.nota
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
                                          and nt.id_nota = $id_nota
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
                                            $nt_nota = $dados['nota'];

                                          }
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

<script language='javascript'>
function confirmaExclusao(aURL) {
if(confirm('Tem certeza que deseja apagar esta nota técnica?')) {
location.href = aURL;
}
}
</script>


<form method="POST" name="form1" action="sgc.php?action=cad_area_negocio.php&acao_int=cad_objeto" onSubmit="return valida_dados(this)">
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
					<table border="0" width="500" cellspacing="0" cellpadding="0">
						<tr>
							<td>
							<table border="0" width="500" cellspacing="0" cellpadding="0">
								<tr>
									<td>
									<p align="center"><BR><b><?echo $nt_titulo?></b></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>
                                    <?

                                       echo '<div style="border: solid 1px orange;  background-color: #FFFFFF; padding: 20px; margin: 20px">';
                                       highlight_string($nt_nota);
                                       echo '</div>';

                                    ?></td>
								</tr>
								<tr>
									<td>
									<table border="0" width="100%" cellspacing="0" cellpadding="0">
										<tr>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td>
											                            <p align="center"><a href="?action=vis_chamado.php&id_chamado=<?echo $id_chamado?>"><font color="#000000">Voltar para o chamado</font></a></p>
											<p align="center">


                                            <b>

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

                                 <a href="javascript:confirmaExclusao('?action=ver_nota.php&acao_int=excluir&id_chamado=<?echo $id_chamado?>&id_nota=<?echo $id_nota?>')">Excluir</a>
                                 
                            <?
                            }
                            ?>            </b>


                            </td>
                            			</tr>
										</table>
									</td>
								</tr>
							</table>
							</td>
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
elseif($acao_int=="editar_bd"){

}elseif($acao_int=="excluir"){
$id_nota=$_GET['id_nota'];
$id_chamado=$_GET['id_chamado'];


     $deleta = mysql_query("DELETE FROM sgc_notas_codigos where id_nota=$id_nota") or print(mysql_error());
     header("Location: ?action=vis_chamado.php&id_chamado=$id_chamado");


}elseif($acao_int=="cad_objeto"){

 }
}
else{
    echo "Você não tem permissão de acesso";
    exit;
}
?>
