<?
//Header para evitar cahe
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("conf/conecta.php");
$mysql=new sgc;
       $mysql->conectar();

?><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr align="right">
									<td width="14">&nbsp;</td>
									<td width="209" valign="top">&nbsp;</td>
									<td>&nbsp;</td>
									<td width="207" valign="top">
                                    </td>
									<td width="13">&nbsp;</td>
						    		</tr>
<tr>
<td width="14">&nbsp;</td>
<td width="209" valign="top">
<span style="background-color: #FFFFFF">
<select style="border-style:solid; border-width:1px; font-size: 9px; width: 207; font-family: Verdana; height: 258; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px " multiple size="21" name="todos" >
<?
$post_area = $_GET['ID'];



     $checa = mysql_query("select
                          us.id_usuario
                          ,concat(us.primeiro_nome,' ',us.ultimo_nome)nome
                          from
                          sgc_usuario us
                          where us.id_usuario
                          and (us.id_usuario not in (select id_analista from sgc_associacao_area_analista where id_area=$post_area and desligamento !=null))
                          order by us.primeiro_nome, us.ultimo_nome
                            ") or print(mysql_error());
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
						   <td width="207" valign="top">
						   <p align="right">
						   <span style="background-color: #FFFFFF">
						   <select style="border-style:solid; border-width:1px; font-size: 9px; width: 205; font-family: Verdana; height: 258; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" id='selSeaShellsAS' multiple size="21" name="selecionados">
                            <?
                          $checa = mysql_query("select
                                       ass.id_analista
                                       ,ass.id_area
                                       ,concat(us.primeiro_nome,' ',us.ultimo_nome)nome
                                       from
                                       sgc_associacao_area_analista ass
                                       ,sgc_usuario us
                                       where
                                       ass.id_area =$post_area
                                       and ass.desligamento is null
                                       and us.id_usuario = ass.id_analista ") or print(mysql_error());
                           while($dados=mysql_fetch_array($checa)){
                           $id_analista = $dados['id_analista'];
                           $id_area = $dados['id_area'];
                           $descricao = $dados['nome'];

                           ?>

                           <option value="<?echo $id_analista?>"><?echo $descricao?></option>

                           <?
                           }
                           ?>
                          <input type='hidden' name='conjunto_selecionado' id='txtSelectedValuesAS'/>
                          <input type='hidden' name='menu_select' value='<?echo $men?>' />
                          <input type='hidden' name='usuario_select' value='<?echo $usuario?>' />
                          </select>

                          </span></td>
									<td width="13">&nbsp;</td>
								</tr>
								<tr>
									<td width="14">&nbsp;</td>
									<td width="209" valign="top">&nbsp;</td>
									<td>&nbsp;</td>
									<td width="207" valign="top">
                                  <input type="submit" value="Salvar" name="B1" style="float: right"></td>
									<td width="13">&nbsp;</td>
								</tr>
                                 </table>
