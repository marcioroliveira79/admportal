<?Php

class sgc_obj {
      var $conexao_obj;
      function conectar_obj()
      {
       $conexao_obj = pg_connect("host=localhost
                                 port=5432
                                 dbname=sgc
                                 user=sgc
                                 password=12345678")
                                 or die ("Não foi possível conectar com o PostGreS!");
      }
}
?>
