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
<select style="border-style:solid; border-width:1px; font-size: 9px; width: 207; font-family: Verdana; height: 258; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px  " multiple size="21" name="todos" >
<?
$post_menu = $_GET['ID'];
list($item_men,$usuario) = split('[/]', $post_menu);


     $checa = mysql_query("SELECT us.id_usuario,concat(us.primeiro_nome,' ',us.ultimo_nome)nome FROM sgc_usuario us
                           where us.id_usuario not in (SELECT id_controlado FROM sgc_assc_relatorios where id_item = $item_men  and id_controlador = $usuario )") or print(mysql_error());
                           while($dados=mysql_fetch_array($checa)){
                           $id_usuario_busca = $dados['id_usuario'];
                           $descricao_p = $dados["nome"];

                           ?>

                           <option value="<?echo $id_usuario_busca?>"><?echo $descricao_p?></option>

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
                          $checa = mysql_query("
                           SELECT us.id_usuario,concat(us.primeiro_nome,' ',us.ultimo_nome)nome FROM sgc_usuario us
                           where us.id_usuario in (SELECT id_controlado FROM sgc_assc_relatorios where id_item = $item_men and id_controlador = $usuario)
                          ") or print(mysql_error());
                           while($dados=mysql_fetch_array($checa)){
                           $id_usuario_assoc = $dados['id_usuario'];
                           $descricao_p1 = $dados["nome"];

                           ?>

                           <option value="<?echo $id_usuario_assoc?>"><?echo $descricao_p1?></option>

                           <?
                           }
                           ?>
                          <input type='hidden' name='conjunto_selecionado' id='txtSelectedValuesAS'/>
                          <input type='hidden' name='rel_select' value='<?echo $item_men?>' />
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
