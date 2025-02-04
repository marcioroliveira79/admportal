<?
//Header para evitar cahe
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("conecta.php");
$mysql=new sgc;
       $mysql->conectar();

echo "<select name=\"filial\"  Onchange=\"atualiza(this.value)\" style='font-family: Verdana; font-size: 8pt;'>";
$result = mysql_query("select * from  usuario us, filial f where f.id_filial=us.filial and us.id_usuario='$_GET[ID]'");
while($row = mysql_fetch_array($result)){
echo "<option value=\"$row[ID_FILIAL]\">$row[DESC_FILIAL]</option>";
}
echo "</select>";
?>
