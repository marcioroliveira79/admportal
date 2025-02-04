<?
//Header para evitar cahe
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("conf/conecta.php");
$mysql=new sgc;
       $mysql->conectar();
$idarea = $_GET['ID'];
if($_GET[ID]!="#"){
echo"<select size='1' name='analista_change'  style='font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;'>";
echo "<option value='Todos'>Todos</option>";
$checa = mysql_query("select
                      ass.id_analista
                      ,ass.id_area
                      ,concat(us.primeiro_nome,' ',us.ultimo_nome)nome
                      from
                      sgc_associacao_area_analista ass
                      ,sgc_usuario us
                      where
                      ass.id_area = $idarea
                      and us.id_usuario = ass.id_analista

                      ") or print(mysql_error());
                                    while($dados=mysql_fetch_array($checa)){
                                    $id_objeto = $dados['id_analista'];
                                    $ler_descricao_objeto = $dados['nome'];


if($id_objeto==null){

 echo "<option value='#'>Nenhum Analista Cadastrado</option>";

}else{

  echo "<option value='$id_objeto'>$ler_descricao_objeto</option>";

 }
}

}else{
echo"<select size='1' name='analista_change' style=$borda font-family: Verdana, arial, helvetica, sans-serif; font-size: 11px; color: #000000;  background: #FFFFFF;'>";
echo "<option >Selecione a Área de Atuação</option>";


}



