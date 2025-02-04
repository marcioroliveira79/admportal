<?
include("conf/conecta.php");
include("conf/funcs.php");
$mysql=new sgc;
       $mysql->conectar();
       


$checa = mysql_query("SELECT

 IF (dt.segunda_inicio <= date_format(sysdate(),'%H:%i:%s') && dt.segunda_fim  >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 1,'EXECUTAR'
,IF (dt.terca_inicio   <= date_format(sysdate(),'%H:%i:%s') && dt.terca_fim    >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 2,'EXECUTAR'
,IF (dt.quarta_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.quarta_fim   >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 3,'EXECUTAR'
,IF (dt.quinta_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.quinta_fim   >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 4,'EXECUTAR'
,IF (dt.sexta_inicio   <= date_format(sysdate(),'%H:%i:%s') && dt.sexta_fim    >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 5,'EXECUTAR'
,IF (dt.sabado_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.sabado_fim   >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 6,'EXECUTAR'
,IF (dt.domingo_inicio <= date_format(sysdate(),'%H:%i:%s') && dt.domingo_fim  >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 0,'EXECUTAR'
,IF (dt.data_especifica = date_format(sysdate(),'%Y-%m-%d') && dt.data_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.data_fim >= date_format(sysdate(),'%H:%i:%s'),'EXECUTAR'
,'')))))))) decisao

,IF (dt.segunda_inicio <= date_format(sysdate(),'%H:%i:%s') && dt.segunda_fim  >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 1,TIME_TO_SEC(TIMEDIFF(dt.segunda_fim,dt.segunda_inicio))
,IF (dt.terca_inicio   <= date_format(sysdate(),'%H:%i:%s') && dt.terca_fim    >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 2,TIME_TO_SEC(TIMEDIFF(dt.terca_fim,dt.terca_inicio))
,IF (dt.quarta_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.quarta_fim   >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 3,TIME_TO_SEC(TIMEDIFF(dt.quarta_fim,dt.quarta_inicio))
,IF (dt.quinta_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.quinta_fim   >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 4,TIME_TO_SEC(TIMEDIFF(dt.quinta_fim,dt.quinta_inicio))
,IF (dt.sexta_inicio   <= date_format(sysdate(),'%H:%i:%s') && dt.sexta_fim    >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 5,TIME_TO_SEC(TIMEDIFF(dt.sexta_fim,dt.sexta_inicio))
,IF (dt.sabado_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.sabado_fim   >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 6,TIME_TO_SEC(TIMEDIFF(dt.sabado_fim,dt.sabado_inicio))
,IF (dt.domingo_inicio <= date_format(sysdate(),'%H:%i:%s') && dt.domingo_fim  >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 0,TIME_TO_SEC(TIMEDIFF(dt.domingo_fim,dt.domingo_inicio))
,IF (dt.data_especifica = date_format(sysdate(),'%Y-%m-%d') && dt.data_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.data_fim >= date_format(sysdate(),'%H:%i:%s'),TIME_TO_SEC(TIMEDIFF(dt.data_fim,dt.data_inicio))
,'')))))))) tempo_para_execucao_periodo

,IF (dt.segunda_inicio <= date_format(sysdate(),'%H:%i:%s') && dt.segunda_fim  >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 1,concat('SE','/',dt.segunda_fim,'/',dt.segunda_inicio)
,IF (dt.terca_inicio   <= date_format(sysdate(),'%H:%i:%s') && dt.terca_fim    >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 2,concat('TE','/',dt.terca_fim,'/',dt.terca_inicio)
,IF (dt.quarta_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.quarta_fim   >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 3,concat('QA','/',dt.quarta_fim,'/',dt.quarta_inicio)
,IF (dt.quinta_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.quinta_fim   >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 4,concat('QI','/',dt.quinta_fim,'/',dt.quinta_inicio)
,IF (dt.sexta_inicio   <= date_format(sysdate(),'%H:%i:%s') && dt.sexta_fim    >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 5,concat('SE','/',dt.sexta_fim,'/',dt.sexta_inicio)
,IF (dt.sabado_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.sabado_fim   >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 6,concat('SA','/',dt.sabado_fim,'/',dt.sabado_inicio)
,IF (dt.domingo_inicio <= date_format(sysdate(),'%H:%i:%s') && dt.domingo_fim  >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 0,concat('DO','/',dt.domingo_fim,'/',dt.domingo_inicio)
,IF (dt.data_especifica = date_format(sysdate(),'%Y-%m-%d') && dt.data_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.data_fim >= date_format(sysdate(),'%H:%i:%s'),concat(dt.data_especifica,'/',dt.data_fim,'/',dt.data_inicio)
,'')))))))) data_execucao


,ROUND(IF (dt.segunda_inicio <= date_format(sysdate(),'%H:%i:%s') && dt.segunda_fim  >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 1,TIME_TO_SEC(TIMEDIFF(dt.segunda_fim,dt.segunda_inicio))
,IF (dt.terca_inicio   <= date_format(sysdate(),'%H:%i:%s') && dt.terca_fim    >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 2,TIME_TO_SEC(TIMEDIFF(dt.terca_fim,dt.terca_inicio))
,IF (dt.quarta_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.quarta_fim   >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 3,TIME_TO_SEC(TIMEDIFF(dt.quarta_fim,dt.quarta_inicio))
,IF (dt.quinta_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.quinta_fim   >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 4,TIME_TO_SEC(TIMEDIFF(dt.quinta_fim,dt.quinta_inicio))
,IF (dt.sexta_inicio   <= date_format(sysdate(),'%H:%i:%s') && dt.sexta_fim    >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 5,TIME_TO_SEC(TIMEDIFF(dt.sexta_fim,dt.sexta_inicio))
,IF (dt.sabado_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.sabado_fim   >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 6,TIME_TO_SEC(TIMEDIFF(dt.sabado_fim,dt.sabado_inicio))
,IF (dt.domingo_inicio <= date_format(sysdate(),'%H:%i:%s') && dt.domingo_fim  >= date_format(sysdate(),'%H:%i:%s') && date_format(sysdate(),'%w') = 0,TIME_TO_SEC(TIMEDIFF(dt.domingo_fim,dt.domingo_inicio))
,IF (dt.data_especifica = date_format(sysdate(),'%Y-%m-%d') && dt.data_inicio  <= date_format(sysdate(),'%H:%i:%s') && dt.data_fim >= date_format(sysdate(),'%H:%i:%s'),TIME_TO_SEC(TIMEDIFF(dt.data_fim,dt.data_inicio))
,'')))))))) / (dt.tempo_execucao_minutos * 60),0)numero_de_execucoes
,ag.id_agendamento
,ag.id_robo
,rb.nome nome_robo
,rb.parametros parametros
,(dt.tempo_execucao_minutos * 60) tempo_execucao_segundos
,n_execucoes
FROM

  sgc_agendamento ag
, sgc_datas_agendamento dt
, sgc_robos rb

where ag.id_data_agendamento = dt.id_data_agendamento
and rb.id_robo = ag.id_robo") or print(mysql_error());
         while($dados=mysql_fetch_array($checa)){
         $id_agendamento = $dados['id_agendamento'];
                $id_robo = $dados['id_robo'];
                  $tempo = $dados['tempo_execucao_em_minutos'];
                $decisao = $dados['decisao'];
              $nome_robo = $dados['nome_robo'];
             $parametros = $dados['parametros'];
          $data_execucao = $dados['data_execucao'];
           $numero_execs = $dados['numero_de_execucoes'];
              $intervalo = $dados['tempo_execucao_segundos'];
                $n_execs = $dados['n_execucoes'];

         list ($dia, $hora_fim, $hora_ini) = split ('[/]',$data_execucao);


   $checa_n_execs = mysql_query("
   SELECT count(*) N FROM sgc_log_execucao
   WHERE date_format(data_exec,'y%-m%-d%') = date_format(sysdate(),'y%-m%-d%')
   AND id_agendamento = $id_agendamento
   AND id_robo = $id_robo
   AND DATEDIFF(sysdate(),data_exec) < 1
   ") or print(mysql_error());
   while($dados_n_execs=mysql_fetch_array($checa_n_execs)){
         $numero_execs_date = $dados_n_execs['N'];
   }

   if($numero_execs_date >= $n_execs){
    $decisao = NULL;
   }



         

   $checa_ultima_decisao = mysql_query("SELECT TIME_TO_SEC(TIMEDIFF(sysdate(),data_exec))ultima ,data_exec data_inicio, data_termino_exec data_fim FROM sgc_log_execucao  where id_agendamento =  $id_agendamento   and date_format(data_exec,'%H:%i') between  '$hora_ini' and '$hora_fim' order by id_log_exec desc limit 1") or print(mysql_error());
   while($dados_ultima_decicao=mysql_fetch_array($checa_ultima_decisao)){
         $ultima = $dados_ultima_decicao['ultima'];
    $data_inicio = $dados_ultima_decicao['data_inicio'];
       $data_fim = $dados_ultima_decicao['data_fim'];
   }

   If($ultima > $intervalo or $ultima == null){
         
   $checa_decisao = mysql_query("SELECT count(*) N FROM sgc_log_execucao
   WHERE date_format(data_exec,'y%-m%-d%') = date_format(sysdate(),'y%-m%-d%')
   AND id_agendamento = $id_agendamento
   AND id_robo = $id_robo
   AND DATEDIFF(sysdate(),data_exec) <1
   ") or print(mysql_error());
   while($dados_decicao=mysql_fetch_array($checa_decisao)){
         $contador = $dados_decicao['N'];
   }


   echo "Id Ag. $id_agendamento Decisao: $decisao<BR> Numero Execs: $numero_execs Contador: $contador<BR>";
   if($decisao=="EXECUTAR" && $numero_execs > $contador || $numero_execs == null){

       if($data_inicio !=null && $data_fim == null ){
                   echo "O Processo anterior ainda não terminou! <BR>";

       }elseif($decisao=="EXECUTAR"){

       if($n_execs != null && $n_execs > 0  && $contador >= $n_execs){
          echo "Número de execuções já executadas";
       }else{

       $insert = mysql_query("INSERT INTO sgc_log_execucao (data_exec,id_agendamento,id_robo) values (sysdate(),$id_agendamento,$id_robo)") or print(mysql_error());
       $ultimo_id = ultimo_registro('id_log_exec','sgc_log_execucao','id_log_exec');


       echo "Executando Processo ...<BR>";
       echo " $nome_robo <BR>";
       exec("sudo /var/www/xfac/sgc/$nome_robo",$resultado);
       $update = mysql_query("UPDATE sgc_log_execucao SET data_termino_exec = sysdate() WHERE id_log_exec =$ultimo_id") or print(mysql_error());


       echo " Encerrado!";
       echo $ultimo_id; echo " <BR>";
       }
   }
  }else{
   echo "Id Ag. $id_agendamento | Não esta para esse periodo! ou já foi concluida<BR>";
  }

 }else{
  echo "Nenhum Programa no intervalor";
 }
 
 
}

?>

