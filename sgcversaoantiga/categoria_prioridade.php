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

<tr>
<td width="209" valign="top">


<?
$categoria = $_GET['ID'];
if($categoria!=null){

     $checa = mysql_query("select sla.id_sla_analista
                          ,concat(sla.descricao,' - ',sla.tempo,' ',sla.tipo_tempo)descricao
                          from  sgc_sla_analista_usuario sla") or print(mysql_error());
                           while($dados=mysql_fetch_array($checa)){
                           $id_sla_analista = $dados['id_sla_analista'];
                           $id_item_menu = $dados['id_item_menu'];
                           $descricao = $dados['descricao'];


     $checa_marc = mysql_query("SELECT COUNT(*)CONTADOR FROM sgc_ass_cat_prio WHERE id_prioridade = $id_sla_analista and id_categoria=$categoria ") or print(mysql_error());
                           while($dados_marc=mysql_fetch_array($checa_marc)){
                           $contador = $dados_marc['CONTADOR'];

     if($contador>0){
       $checked="checked";
     }else{
       $checked=null;
     }
}

?>
        <p><input type="radio" <?echo $checked?> value="<?echo $id_sla_analista?>" name="prioridade"><?echo $descricao?></p>
<?


}
?>
                                <input type="submit" value="Salvar" name="B4">
								</tr>
<?
}
?>
                                 </table>
