<?
//Header para evitar cahe
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("conf/conecta.php");
$mysql=new sgc;
       $mysql->conectar();
       
if($_GET[ID]!=null){
echo"<select size='1' name='menu'  Onchange=\"atualiza_item(this.value);\" style=$borda font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;'>";
$checa = mysql_query("select distinct mn.id_menu, mn.descricao from sgc_regra_menu rm, sgc_menu mn
                      where rm.id_usuario = $_GET[ID]
                      and mn.id_menu = rm.id_menu order by mn.descricao") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_objeto = $dados['id_menu'];
                                    $ler_descricao_objeto = $dados['descricao'];

echo "<option value='$id_objeto'>$ler_descricao_objeto</option>";
}

}else{
echo"<select size='1' name='menu'  Onchange='atualiza_item(this.value);' style=$borda font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;'>";
echo "<option >Selecione o Menu</option>";


}
                                    


