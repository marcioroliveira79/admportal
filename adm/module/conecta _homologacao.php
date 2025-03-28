<?php
class Portal {
    public $conexao_obj;

    public function conectar_obj() {
        $this->conexao_obj = pg_connect("host=postgres_db 
                                         port=5432 
                                         dbname=portal_homologacao 
                                         user=usr_homologacao 
                                         password=Portal2025");
        if (!$this->conexao_obj) {
            die("Não foi possível conectar ao banco de dados.");
        }
        return $this->conexao_obj;
    }
}
?>
