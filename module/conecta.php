<?Php
class portal {
      var $conexao_obj;
      function conectar_obj() {
          $this->conexao_obj = pg_connect("host=localhost
                                           port=5432
                                           dbname=portal
                                           user=postgres
                                           password=admin");
          if (!$this->conexao_obj) {
              die("Não foi possível conectar ao banco de dados.");
          }
          return $this->conexao_obj;
      }
  }
?>


