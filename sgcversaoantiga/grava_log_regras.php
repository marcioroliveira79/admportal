<?php




function grava_arquivo($arquivo,$usuario,$data_geracao){
$arquivo="/var/www/xfac/sgc/xregras_pdf/$arquivo";
$fp = fopen($arquivo,'r');
$texto = fread($fp, filesize($arquivo));

$classificados = explode("/", $texto);

foreach($classificados as $valor){

list($num_operacao, $descricao, $direcao, $id_regra) = explode(";", $valor);

   $descricao=rtrim($descricao);
$num_operacao=rtrim($num_operacao);
     $direcao=rtrim($direcao);
    $id_regra=rtrim($id_regra);
    
If($descricao != null && $direcao != null && $num_operacao != null && $data_geracao != null && $usuario != null && $id_regra != null ){

$cadas = mysql_query  ("INSERT INTO sgc_log_regra_xfac
                             (id_regra
                             ,usuario
                             ,data_geracao_regra
                             ,data_gravacao_banco
                             ,operacao
                             ,descricao_operacao
                             ,es
                             )

                             VALUES

                             ($id_regra
                             ,'$usuario'
                             ,'$data_geracao'
                             ,sysdate()
                             ,'$num_operacao'
                             ,'$descricao'
                             ,'$direcao')
                             ");print(mysql_error());



}
}
fclose ($fp);

return $cadas;
}


//copia pdfs do 205 para 104
exec("sudo /var/www/xfac/sgc/copy_regra_pdf.sh $ip_host $arquivo",$resultado);


// pega o endereço do diretório
$diretorio = "/var/www/xfac/sgc/xregras_pdf/";




foreach(new DirectoryIterator($diretorio) as $file){


//-- Como a gravação do arquivo esta com erro pegamos o ID da regra que esta dentro do arquivo e não no nome do arquivo
//-- Este código abaixo faz exatamente isto
//------------------------------------------------------------------//
$arquivo="/var/www/xfac/sgc/xregras_pdf/$file";
$fp = fopen($arquivo,'r');
$texto = fread($fp, filesize($arquivo));
list($num_operacao_arquivo, $descricao_arquivo, $direcao_arquivo, $id_regra_arquivo) = explode(";", $texto);
//-----------------------------------------------------------------//

  list($id_regra, $nome_usuario, $data) = explode("-", $file);

  $nome_usuario;
  $nome_usuario=str_replace('_','.',$nome_usuario);

  list($data,$extensao) = explode(".", $data);
  if($extensao=="txt"){


  $dia     = substr($data, 0, 2);
  $mes     = substr($data, 2, 2);
  $ano     = substr($data, 4, 4);
  $hora    = substr($data, 8, 2);
  $minuto  = substr($data, 10, 2);
  $segundo = substr($data, 12, 2);

  $data_geracao="$ano-$mes-$dia $hora:$minuto:$segundo";

$count=0;
$checa = mysql_query("SELECT * FROM
                      sgc_log_regra_xfac
                      WHERE id_regra=$id_regra
                      AND usuario='$nome_usuario'
                      AND data_geracao_regra ='$data_geracao'
                      ") or print mysql_error();
while($dados=mysql_fetch_array($checa)){
   $id_regra_ver           = $dados['id_regra'];
   $usuario_ver            = $dados['usuario'];
   $data_geracao_regra_ver = $dados['data_geracao_regra'];
   $count++;
}


if($count==0){


 grava_arquivo($file,$nome_usuario,$data_geracao);
}

}

}

?>
