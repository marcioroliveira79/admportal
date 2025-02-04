<?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


$conexao = mysql_connect('localhost','root','') or die ("Não foi possível conectar com o MySQL!");
            mysql_select_db('sgc') or die ("Banco de dados inexistente");

$usuario_menu=$_POST['usuario_menu'];
echo"<select size='1' name='cidade'>";
$checa = mysql_query("select * from menu ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_objeto = $dados['id_menu'];
                                    $ler_nome = $dados['descricao'];

echo" <option>$ler_nome</option>";

}
echo"</select>";

?>

