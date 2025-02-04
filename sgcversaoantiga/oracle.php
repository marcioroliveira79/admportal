<?php
if ($conexao=ora_logon("APPS@fripd0","beds489")) {
echo "Conexao efetuada com sucesso.\n";
ora_commitoff($conexao);
ora_logoff($conexao);
} else {
echo "Erro na conexao com o Oracle" . ora_error();
}
?>
