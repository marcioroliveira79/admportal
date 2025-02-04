<?php
OB_START();

include("conf/conecta.php");
include("conf/funcs.php");


$mysql=new sgc;
       $mysql->conectar();

$nome_adm="SGC";
$email_adm="sgc@frimesa.com.br";
$email_dest="rodrigues@frimesa.com.br";
$nome="$objeto1";
$txtAssunto="Cadastro SGC Concluído";
$mensagem="<p><font face='Verdana' size='1'>Seu cadastro foi realizado com sucesso no SGC
'Sistema Gerencial de Chamados'<br>
login:$objeto3<br>
senha:$objeto12<br>
click aqui para acessar --&gt; </font></p>";

echo $email=email($nome_adm,$email_adm,$email_dest,$nome,$txtAssunto,$mensagem);

?>




