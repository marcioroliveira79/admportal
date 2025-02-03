<?Php
OB_START();
session_start();
//error_reporting(0);
include("conf/conecta.php");
include("conf/functions.php");

echo LogAcesso(1, '2', '');

?>