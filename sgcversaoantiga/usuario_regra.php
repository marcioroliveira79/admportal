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
$post_menu = $_GET['ID'];
list($men,$usuario) = split('[/]', $post_menu);


     $checa = mysql_query("select
                           im.id_item_menu
                         , im.descricao
                           from
                           sgc_item_menu im
                           where im.id_item_menu
                           and im.id_item_menu not in
                           (select id_item_menu from sgc_regra_menu where  id_usuario=$usuario)
                            ") or print(mysql_error());
                           while($dados=mysql_fetch_array($checa)){
                           $id_menu = $dados['id_menu'];
                           $id_item_menu = $dados['id_item_menu'];
                           $descricao = $dados['descricao'];

                           ?>

                           <option value="<?echo $id_item_menu?>"><?echo $descricao?></option>

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
                                  rm.id_menu
                                 ,im.id_item_menu
                                 ,im.descricao
                           from
                           sgc_regra_menu rm
                          ,sgc_item_menu im
                           where
                           rm.id_usuario = $usuario
                           and rm.id_menu = $men
                           and im.id_item_menu = rm.id_item_menu ") or print(mysql_error());
                           while($dados=mysql_fetch_array($checa)){
                           $id_menu = $dados['id_menu'];
                           $id_item_menu = $dados['id_item_menu'];
                           $descricao = $dados['descricao'];

                           ?>

                           <option value="<?echo $id_item_menu?>"><?echo $descricao?></option>

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
