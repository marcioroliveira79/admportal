<?php
OB_START();
       $conexao = mysql_connect('localhost','sgc','senha_sgc') or die ("FUNC - N�o foi poss�vel conectar com o MySQL!");
                   mysql_select_db('db_sgc') or die ("Banco de dados inexistente - Conecta");

require("class.phpmailer.php");



function RemoveAcentos($string) {
	$palavra = strtr($string, "���������������������������������������������������������������������", "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
	$palavranova = str_replace("_", " ", $palavra);
	return $palavranova;
}




function chamado_redundante($titulo,$descricao,$idusuario){
$checa = mysql_query ("SELECT
id_chamado
FROM sgc_chamado
WHERE datediff(sysdate(),data_criacao) = 0
AND quem_criou = $idusuario
AND titulo = '$titulo'
AND descricao ='$descricao'
")or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
   $id_chamado = $dados['id_chamado'];
}
if($id_chamado==null || $id_chamado==""){
 return "OK";
}else{
 return $id_chamado;
}



}

function chamados_em_atrazo($idusuario){
$count=0;
$checa = mysql_query ("SELECT * FROM sgc_chamado ch, sgc_historico_chamado hc
WHERE ch.id_chamado = hc.id_chamado
AND hc.id_historico = ch.id_linha_historico
AND ch.id_suporte = $idusuario
AND ch.status not in ('Fechado','Concluido')
AND time_to_sec(timediff(sysdate(),ch.data_criacao)) > 259200")or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
   $id_chamado = $dados['ch.id_chamado'];
       $status = $dados['ch.status'];
      $criacao = $dados['ch.criacao'];
      $suporte = $dados['ch.id_suporte'];
$criacao_linha = $dados['hc.quem_criou_linha'];

     $count++;

}

return $count;
}



function hora_agora(){
return date("H:i:s");
}

function status_atual($idchamdo){
$checa = mysql_query
("SELECT status FROM sgc_chamado ch, sgc_historico_chamado hc
WHERE ch.id_chamado = $idchamdo
AND hc.id_historico = ch.id_linha_historico")or print(mysql_error());
 while($dados=mysql_fetch_array($checa)){
  $status   = $dados['status'];
 }
return $status;
}


function verefica_cartao_registro_hoje($idusuario){
$checa = mysql_query
("
SELECT if(entrada_manha is not null,'entrada_manha',if(entrada_tarde is not null,'entrada_tarde',''))PERIODO FROM sgc_cartao_ponto WHERE id_usuario = $idusuario
AND date_format(entrada_manha,'%Y-%m-%d') =  date_format(sysdate(),'%Y-%m-%d')
OR date_format(saida_almoco,'%Y-%m-%d') =  date_format(sysdate(),'%Y-%m-%d')
OR date_format(entrada_tarde,'%Y-%m-%d') =  date_format(sysdate(),'%Y-%m-%d')
or date_format(saida_tarde,'%Y-%m-%d') =  date_format(sysdate(),'%Y-%m-%d')
")or print(mysql_error());
 while($dados=mysql_fetch_array($checa)){
  $periodo   = $dados['PERIODO'];
 }
return $periodo;
}

function verefica_cartao($idusuario,$periodo){

  if($periodo == 1){
    $periodo="entrada_manha";
  }elseif($periodo == 2){
    $periodo="saida_almoco";
  }elseif($periodo == 3){
    $periodo="entrada_tarde";
  }elseif($periodo == 4){
     $periodo="saida_tarde";
  }

$checa = mysql_query("SELECT if(count(*) is null,0,1)REGISTRO,date_format($periodo,'%d/%m/%Y %H:%i:%s')HORA FROM sgc_cartao_ponto WHERE id_usuario =$idusuario AND date_format($periodo,'%Y-%m-%d') =  date_format(sysdate(),'%Y-%m-%d') group by id_usuario")or print(mysql_error());
  while($dados=mysql_fetch_array($checa)){
  $registro   = $dados['REGISTRO'];
  $h_registro = $dados['HORA'];
 }
 if($registro==1){
  return $h_registro;
 }else{
  return 0;
 }
}


function removeAccentuation($str)
{
   $from = '��������������������������';
   $to   = 'AAAAEEIOOOUUCaaaaeeiooouuc';

   return strtr($str, $from, $to);
}


function unidade_usuario($idusuario){
$checa = mysql_query("SELECT * FROM sgc_usuario us WHERE id_usuario =$idusuario")or print(mysql_error());
  while($dados=mysql_fetch_array($checa)){
  $id_unidade= $dados['id_unidade'];
 }
return $id_unidade;
}

function questionamento_analista($idusuario){
$checa = mysql_query("SELECT
*,
if((SELECT 1 FROM sgc_replica_questao_analista WHERE id_questao = qa.id_questao) = 1
,'SIM'
,'NAO')REPLICA
FROM
 sgc_questionario_analista qa
WHERE qa.analista = $idusuario ")or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
   $replica = $dados['REPLICA'];
   $id_questao = $dados['id_questao'];

   if($replica=="NAO"){
      $id_questao_env=$id_questao;
   }
}
return $id_questao_env;
}

function questionamento_usuario($idusuario){
$checa = mysql_query("SELECT
*,
if((SELECT 1 FROM sgc_replica_questao_usuario WHERE id_questao = qa.id_questao) = 1
,'SIM'
,'NAO')REPLICA
FROM
 sgc_questionario_usuario qa
WHERE qa.id_usuario_dono = $idusuario ")or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
   $replica = $dados['REPLICA'];
   $id_questao = $dados['id_questao'];

   if($replica=="NAO"){
      $id_questao_env=$id_questao;
   }
}
return $id_questao_env;
}



function aguardando(){
$count=0;
$checa = mysql_query("
SELECT
 if(hc.quem_criou_linha != ch.id_usuario,'EMAIL DONO','EMAIL SUPORTE') nome
,(SELECT concat(primeiro_nome,' ',ultimo_nome) FROM sgc_usuario WHERE id_usuario = if(hc.quem_criou_linha != ch.id_usuario,ch.id_usuario,hc.id_suporte))nome_dono
,(SELECT concat(primeiro_nome,' ',ultimo_nome) FROM sgc_usuario WHERE id_usuario = if(hc.quem_criou_linha  = ch.id_usuario,hc.id_suporte,ch.id_usuario))nome_destinatario
,(SELECT concat(primeiro_nome,' ',ultimo_nome) FROM sgc_usuario WHERE id_usuario = if(hc.quem_criou_linha != ch.id_usuario,hc.quem_criou_linha,ch.id_usuario))quem_criou_linha
,(SELECT id_usuario FROM sgc_usuario WHERE id_usuario = if(hc.quem_criou_linha != ch.id_usuario,hc.quem_criou_linha,ch.id_usuario))id_quem_criou_linha
,hc.data_criacao data_status
,if(hc.quem_criou_linha != ch.id_usuario,ch.id_usuario,hc.id_suporte) id
,(SELECT email FROM sgc_usuario WHERE id_usuario = if(hc.quem_criou_linha != ch.id_usuario,ch.id_usuario,hc.id_suporte))email
,hc.id_historico
,ch.id_chamado
,concat('Aguardando Resposta',' - CH: ',ch.id_chamado,' - Hist: ',hc.id_historico,' - Us: ',if(hc.quem_criou_linha != ch.id_usuario,ch.id_usuario,hc.id_suporte))hash

,if(
time_to_sec(TIMEDIFF(sysdate('%Y-%m-%d %H:%i:%s'),(SELECT data FROM sgc_log_emails WHERE
titulo=concat('Aguardando Resposta',' - CH: ',ch.id_chamado,' - Hist: ',hc.id_historico,' - Us: ',if(hc.quem_criou_linha != ch.id_usuario,ch.id_usuario,hc.id_suporte))
ORDER BY data DESC limit 1 )))

> (SELECT round(atributo14/4,0) FROM sgc_parametros_sistema),'ENVIAR'

,if(time_to_sec(TIMEDIFF(sysdate('%Y-%m-%d %H:%i:%s'),(SELECT data FROM sgc_log_emails WHERE
titulo=concat('Aguardando Resposta',' - CH: ',ch.id_chamado,' - Hist: ',hc.id_historico,' - Us: ',if(hc.quem_criou_linha != ch.id_usuario,ch.id_usuario,hc.id_suporte))
ORDER BY data DESC limit 1 ))) is null,'ENVIAR','NAO')



)DECISION

,time_to_sec(TIMEDIFF(sysdate('%Y-%m-%d %H:%i:%s'),(SELECT data FROM sgc_log_emails WHERE
titulo=concat('Aguardando Resposta',' - CH: ',ch.id_chamado,' - Hist: ',hc.id_historico,' - Us: ',if(hc.quem_criou_linha != ch.id_usuario,ch.id_usuario,hc.id_suporte))
ORDER BY data DESC limit 1 )))


FROM sgc_chamado ch,sgc_historico_chamado hc
WHERE
    ch.status='Aguardando Resposta - Usu�rio'
and hc.id_historico = ch.id_linha_historico
and hc.situacao='Aguardando Resposta - Usu�rio'") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
   $id_chamado_a            = $dados['id_chamado'];
   $titulo_a                = $dados['hash'];
   $email_a                 = $dados['email'];
   $decisao_a               = $dados['DECISION'];
   $data_a                  = $dados['data_status'];
   $quem_perguntou          = $dados['quem_criou_linha'];
   $nome_dono_chamado       = $dados['nome_dono'];
   $id_quem_perguntou       = $dados['id_quem_criou_linha'];

$url_chamado=organizacao('link')."/sgc.php?action=vis_chamado.php&id_chamado=$id_chamado_a";
if(atributo('atributo10')=="ON"){



$mensagem_a="<p><font face='Courier New'  size='2'>
********************  CHAMADO AGUARDANDO RESPOSTA *************************<BR>
Usuario.............: $quem_perguntou - Aguarda Sua Resposta <BR>
ID Chamado .........: $id_chamado_a<BR>
Data de Status......: $data_a<BR>
---------------------------------------------------------------------------<BR>
        POR FAVOR RESPONDA A ESTE CHAMADO O MAIS BREVE POSS�VEL<BR>
---------------------------------------------------------------------------<BR>
<BR><a href='$url_chamado'><font color='#000000'>$url_chamado</font></a><BR>
---------------------------------------------------------------------------<BR>
</font></p>";
if($decisao_a=="ENVIAR"){
 $email=send_mail_smtp("$titulo_a",$mensagem_a,$mensagem_a,$email_a,$nome_dono_chamado);
 $datahoje=data_with_hour(datahoje('datahora'));
 copiar_linha_historico($id_chamado_a,"Aguardando Resposta - Usu�rio","E-mail automatico, cobrando resposta enviado em: $datahoje ","","","","$id_quem_perguntou","");
 $count++;
}

 }

}
return $count;


}


function ver_fone($idusuario){

  $checa = mysql_query("SELECT

if(ddd is null || ddd = 0 || LENGTH(ddd)<2 || telefone is null || telefone = 0 || LENGTH(telefone)<9 ,0,1)CADASTRO_FONE

,ddd,telefone,ramal

FROM sgc_usuario WHERE id_usuario = $idusuario") or print(mysql_error());
  while($dados=mysql_fetch_array($checa)){
   $cadastro                = $dados['CADASTRO_FONE'];
}
return $cadastro;
}

function definir_novo_status(){

 $checa = mysql_query("select acao,id_historico,id_chamado,id_prioridade_atual,ultimo_status,id_prioridade_superior from view_definir_acao_status_usuario_analista") or print(mysql_error());
 while($dados=mysql_fetch_array($checa)){
   $acao_ac                = $dados['acao'];
   $id_chamado_ac          = $dados['id_chamado'];
   $id_historico_ac        = $dados['id_historico'];
   $ultimo_status          = $dados['ultimo_status'];
   $id_prioridade_atual    = $dados['id_prioridade_atual'];
   $id_prioridade_superior = $dados['id_prioridade_superior'];
   $count++;

   if($id_prioridade_superior!="Prioridade do Chamado em Nivel Maximo" and $acao!="NENHUMA"){

    $criador_status   =tabelainfo($id_historico_ac,"sgc_historico_chamado","quem_criou_linha","id_historico","");
    $email_criador_st =tabelainfo($criador_status,"sgc_usuario","email","id_usuario","");
    $nome_criador_st  =tabelainfo($criador_status,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");

    $criador_chamado  =tabelainfo($id_chamado_ac,"sgc_chamado","quem_criou","id_chamado","");
    $email_criador    =tabelainfo($criador_chamado,"sgc_usuario","email","id_usuario","");
    $nome_criador     =tabelainfo($criador_chamado,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");

    $analista_chamado =tabelainfo($id_historico_ac,"sgc_historico_chamado","id_suporte","id_historico","");
    $email_analista   =tabelainfo($analista_chamado,"sgc_usuario","email","id_usuario","");
    $nome_analista    =tabelainfo($analista_chamado,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");

    $prioridade_ant   =tabelainfo($id_prioridade_atual,"sgc_sla_analista_usuario","descricao","id_sla_analista","");
    $prioridade_ac    =tabelainfo($id_prioridade_superior,"sgc_sla_analista_usuario","descricao","id_sla_analista","");
    $usuario_bot      =atributo('atributo11');
    $nome_bot         =tabelainfo($usuario_bot,"sgc_usuario","concat(primeiro_nome,' ',ultimo_nome)","id_usuario","");
    $data_g           =data_with_hour(datahoje("datahora"));
    $titulo_chamado   =tabelainfo($id_chamado_ac,"sgc_chamado","titulo","id_chamado","");


     if($criador_status != $analista_chamado and $ultimo_status =="Aguardando Resposta - Usu�rio"){

        copiar_linha_historico($id_chamado_ac,$ultimo_status,"Prioridade alterado autom�ticamente para: $prioridade_ac<BR>Motivo: Tempo excedido","","","","$usuario_bot","");
        $ultima_linha=tabelainfo($id_chamado_ac,"sgc_historico_chamado","id_historico","id_chamado"," order by id_historico desc limit 1");
        $cadas = mysql_query("UPDATE sgc_chamado set id_linha_historico=$ultima_linha where id_chamado = $id_chamado_ac") or print(mysql_error());

        $mensagem_g="<p><font face='Courier New'  size='2'>
        *************************** STATUS ALTERADO ********************************<BR>
        Usuario.............: $nome_bot<BR>
        ID Chamado .........: $id_chamado_ac<BR>
        Data de mudan�a.....: $data_g<BR>
        ---------------------------------------------------------------------------<BR>
        Titulo Chamado......: $titulo_chamado<BR>
        Prioridade Atual....: $prioridade_ant<BR>
        Prioridade Nova.....: $prioridade_ac<BR>
        Motivo mudan�a......: Tempo Excedido<BR>

        ---------------------------------------------------------------------------<BR>
        </font></p>";

        if(atributo('atributo11')=="ON"){
              $email_analista=send_mail_smtp("O chamado de N� $id_chamado_ac, Teve seu status alterado automaticamente ",$mensagem_g,$mensagem_g,$email_analista,$nome_analista);

            if($email_criador_st!=$email_criador){

              $email_criador_st=send_mail_smtp("O chamado de N� $id_chamado_ac, Teve seu status alterado automaticamente ",$mensagem_g,$mensagem_g,$email_criador_st,$nome_criador_st);
              $email_criador=send_mail_smtp("O chamado de N� $id_chamado_ac, Teve seu status alterado automaticamente ",$mensagem_g,$mensagem_g,$email_criador,$nome_criador);
            }else{
              $email_criador=send_mail_smtp("O chamado de N� $id_chamado_ac, Teve seu status alterado automaticamente ",$mensagem_g,$mensagem_g,$email_criador,$nome_criador);
            }
         }


     }
   }
 }
 return $marcador;
}



function send_mail_smtp($subject,$body,$body_txt,$conta_de_envio,$nome_envio){

$host=atributo('atributo21');
$atributo20=atributo('atributo20');
list ($username, $password) = split ('[;]',$atributo20);
list ($username, $dns) = split ('[@]',$username);

$sufixo = explode("noreply",$conta_de_envio);

If($conta_de_envio =="" || $subject=="" || $body=="" || $sufixo=="noreply" ){

  $erro="E-mail, titulo e mensagem s�o orgrigat�rios";

}else{

$mail = new PHPMailer();
$mail->IsSMTP(); //ENVIAR VIA SMTP
$mail->Host = "$host"; //SERVIDOR DE SMTP, USE smtp.SeuDominio.com OU smtp.hostsys.com.br
$mail->SMTPAuth = FALSE;
$mail->Username = "$username"; //EMAIL PARA SMTP AUTENTICADO (pode ser qualquer conta de email do seu dom�nio)
$mail->Password = "$password"; //SENHA DO EMAIL PARA SMTP AUTENTICADO
$mail->From = "$username@$dns"; //E-MAIL DO REMETENTE
$mail->FromName = "$username"; //NOME DO REMETENTE
$mail->AddAddress("$conta_de_envio","$nome_envio"); //E-MAIL DO DESINAT�RIO, NOME DO DESINAT�RIO

//$mail->AddReplyTo("suporte@hostsys.com.br"," Suporte Hostsys "); //UTILIZE PARA DEFINIR OUTRO EMAIL DE RESPOSTA (opcional)

$mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
$mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML
$mail->Subject = "$subject"; //ASSUNTO DA MENSAGEM
$mail->Body = "$body"; //MENSAGEM NO FORMATO HTML
$mail->AltBody = "$body_txt"; //MENSAGEM NO FORMATO TXT





$email="$conta_de_envio";


if(!$mail->Send()){
$erro="Erro ao enviar mensagen: " . $mail->ErrorInfo;
$cadas = mysql_query("INSERT INTO sgc_log_emails (titulo, destino, data, error) VALUES ('$subject','$email',sysdate(),'$erro')") or print(mysql_error());

$erro_a = explode("SMTP Error: ",$mail->ErrorInfo);
$erro_a = explode("[",$erro_a[1]);
$compare = explode("[",$mail->ErrorInfo);
$compare = ltrim(rtrim($compare));
 if ( $compare == "The following recipients failed" || $compare== "SMTP Error: Data not accepted" ){
      send_mail_smtp($subject,$body,$body_txt,$conta_de_envio,$nome_envio);
 }
}

$erro= "Mensagem enviada com sucesso!";
$cadas = mysql_query("INSERT INTO sgc_log_emails (titulo,  destino, data, error) VALUES ('$subject','$email',sysdate(),'$erro')") or print(mysql_error());
}
return $erro;
}





function contador_item($contador){

$checa = mysql_query("$contador") or print(mysql_error());
 while($dados=mysql_fetch_array($checa)){
   $contador= $dados['CONTADOR'];
 }
  return $contador;
}

function id_dono_chamado($idchamado){

$checa = mysql_query("
SELECT
id_usuario
FROM
sgc_chamado
where
id_chamado=$idchamado") or print(mysql_error());
 while($dados=mysql_fetch_array($checa)){
   $id_usuario= $dados['id_usuario'];
 }
  return  $id_usuario;
}

function dono_chamado($idusuario,$idchamado){

$checa = mysql_query("
SELECT
if(id_usuario=$idusuario,'SIM','NAO')DONO
FROM
sgc_chamado
where
id_chamado=$idchamado") or print(mysql_error());
 while($dados=mysql_fetch_array($checa)){
   $decisao= $dados['DONO'];
 }
  return $decisao;
}


function chamado_fechado_falta_enquete($idusuario){
$checa = mysql_query("SELECT

if((SELECT nota_enquete FROM sgc_historico_chamado
WHERE id_chamado = ch.id_chamado
and situacao='Fechado'
and nota_enquete is not null) is null,
(SELECT nota_enquete FROM sgc_historico_chamado
WHERE id_chamado = ch.id_chamado
and situacao='Fechado'
and nota_enquete is not null),'N') NOTA_ENQUETE
,ch.id_chamado
FROM sgc_chamado ch
where ch.id_usuario = $idusuario
and ch.status='Fechado'") or print(mysql_error());
 while($dados=mysql_fetch_array($checa)){
  if($dados['NOTA_ENQUETE'] == "" ){
    $id_chamado= $dados['id_chamado'];
  }
 }
 return $id_chamado;
}




function ultimo_historico($id_chamado){
$checa = mysql_query(" SELECT hc.id_historico FROM sgc_historico_chamado hc
                       where hc.id_chamado =$id_chamado
                       order by hc.id_historico desc limit 1") or print(mysql_error());
 while($dados=mysql_fetch_array($checa)){
   $id_historico= $dados['id_historico'];
 }
 return $id_historico;
}


function ultimo_historico_chamado($id_chamado){

 $checa = mysql_query("SELECT hc.id_historico FROM sgc_chamado ch, sgc_historico_chamado hc
 where ch.id_chamado ='$id_chamado'
 and ch.id_chamado = hc.id_chamado
 and ch.id_linha_historico=hc.id_historico
 and hc.nota_enquete is null") or print(mysql_error());
 while($dados=mysql_fetch_array($checa)){
   $id_historico= $dados['id_historico'];
 }
 return $id_historico;
}

function novos_cadastros(){
$count=0;
$checa = mysql_query("select * from sgc_usuario where quem_alterou='0'") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
   $count++;
 }
 return $count;
}

function chamados_fechados_sem_nota($idusuario){

$checa = mysql_query("SELECT * FROM sgc_chamado ch, sgc_historico_chamado hc
where ch.id_chamado = hc.id_chamado
and ch.id_usuario = $idusuario
and ch.id_linha_historico=hc.id_historico
and ch.status='Fechado'
and hc.nota_enquete is null") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
   $id_chamado= $dados['id_chamado'];
 }
 return $id_chamado;
}

function verperfil_id($idusuario){
$checa = mysql_query("
SELECT if(DESCRICAO='CUSTOMIZADO','0',DESCRICAO)DESCRICAO

,IDUSUARIO,NOME

FROM (

SELECT if(DESCRICAO='CUSTOMIZADO','0',sm.id_template) DESCRICAO,su.id_usuario IDUSUARIO ,concat(su.primeiro_nome,' ',su.ultimo_nome)NOME
FROM sgc_template_menu sm, sgc_usuario su
where sm.id_template = su.perfil
and su.id_usuario = $idusuario
union
SELECT su.perfil DESCRICAO ,su.id_usuario IDUSUARIO ,concat(su.primeiro_nome,' ',su.ultimo_nome)NOME
FROM  sgc_usuario su
where su.id_usuario = $idusuario ) RESULT

LIMIT 1
 ") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
   $desc = $dados['DESCRICAO'];
 }
 return $desc;
}


function verperfil($idusuario){
$checa = mysql_query("
SELECT DESCRICAO,IDUSUARIO,NOME

FROM (

SELECT sm.descricao DESCRICAO,su.id_usuario IDUSUARIO ,concat(su.primeiro_nome,' ',su.ultimo_nome)NOME
FROM sgc_template_menu sm, sgc_usuario su
where sm.id_template = su.perfil
and su.id_usuario = $idusuario
union
SELECT su.perfil DESCRICAO ,su.id_usuario IDUSUARIO ,concat(su.primeiro_nome,' ',su.ultimo_nome)NOME
FROM  sgc_usuario su
where su.id_usuario = $idusuario ) RESULT

LIMIT 1
 ") or print('376'.mysql_error());
while($dados=mysql_fetch_array($checa)){
   $desc = $dados['DESCRICAO'];
 }
 return $desc;
}


function copiar_linha_historico($idch,$situacao,$atualizacao,$id_service_desk,$prioridade,$id_suporte,$quem_criou_linha,$id_categoria){

$return='True';

if($situacao != null or $atualizacao != null  or  $id_service_desk != null or  $prioridade   != null
                   or $id_suporte    != null  or $quem_criou_linha != null or  $id_categoria !=null){


if($situacao==null){
  $situacao="situacao";
  $acao="acao";
}else{
  $situacao="'$situacao'";
  $acao="$situacao";

  $update = mysql_query("UPDATE sgc_chamado SET status=$situacao WHERE id_chamado=$idch") or print('403'.mysql_error());
}

if($atualizacao==null){
  $atualizacao="atualizacao";
}else{
  $atualizacao="'$atualizacao'";
}

if($id_service_desk==null){
  $id_service_desk="id_service_desk";
}

if($prioridade==null){
  $prioridade="prioridade";
}

if($id_suporte==null){
  $id_suporte="id_suporte";
}

if($quem_criou_linha==null){
  $quem_criou_linha="quem_criou_linha";
}

if($id_categoria==null){
  $id_categoria="id_categoria";
}
$SQL="insert into sgc_historico_chamado
(id_chamado
,situacao
,acao
,atualizacao
,visto_service_desk
,id_service_desk
,visto_suporte
,prioridade
,id_suporte
,quem_criou_linha
,quem_criou
,data_criacao
,id_categoria)

(SELECT
 id_chamado
,$situacao
,$acao
,$atualizacao
,visto_service_desk
,$id_service_desk
,visto_suporte
,$prioridade
,$id_suporte
,$quem_criou_linha
,quem_criou
,sysdate()
,$id_categoria
FROM sgc_historico_chamado where id_chamado=$idch order by id_chamado desc limit 1)";


$copia = mysql_query("$SQL") or $return="Erro na sql de copia em Funcoes 427";
$id_historico=ultimo_registro('id_historico','sgc_historico_chamado','id_historico');
$update = mysql_query("UPDATE sgc_chamado SET id_linha_historico=$id_historico WHERE id_chamado=$idch") or print('465'.mysql_error());

 return $return;

}else{

 return false;

}
}




function tempo_p_fechamento_chamado($idch){
$tempo=atributo('atributo14');
$count=0;
$checa = mysql_query("
SELECT
if(timediff(sysdate('%Y-%m-%d %H:%i:%s'),data_criacao)>='$tempo','FECHAR','NAOFECHAR')TEMPO
FROM sgc_historico_chamado where id_chamado=57
and situacao='Concluido'
order by id_chamado desc limit 1                ") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
   $id_historico_2 = $dados['TEMPO'];
 }
return $count;
}


function chpfechar($idusu){
$tempo=atributo('atributo13');
$count=0;
$checa = mysql_query("select id_chamado,sysdate(),definir_status,definir_status + INTERVAL 7200 SECOND from sgc_chamado where quem_criou=$idusu
and status='Concluido'
and definir_status + INTERVAL $tempo SECOND <= sysdate() ") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
   $count++;
 }
return $count;
}


function verefica_tela($tela){
$count=0;
$checa = mysql_query("SELECT * FROM sgc_tela WHERE descricao='$tela'") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
   $count++;
}
return $count;
}


function get_real_ip()
{
     $ip = false;
     if(!empty($_SERVER['HTTP_CLIENT_IP']))
     {
          $ip = $_SERVER['HTTP_CLIENT_IP'];
     }
     if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
     {
          $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
          if($ip)
          {
               array_unshift($ips, $ip);
               $ip = false;
          }
          for($i = 0; $i < count($ips); $i++)
          {
               if(!preg_match("/^(10|172\.16|192\.168)\./i", $ips[$i]))
               {
                    if(version_compare(phpversion(), "5.0.0", ">="))
                    {
                         if(ip2long($ips[$i]) != false)
                         {
                              $ip = $ips[$i];
                              break;
                         }
                    }
                    else
                    {
                         if(ip2long($ips[$i]) != - 1)
                         {
                              $ip = $ips[$i];
                              break;
                         }
                    }
               }
          }
     }
     return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}


function tempo_status($id_chamado){

$checa = mysql_query("SELECT id_historico FROM sgc_historico_chamado WHERE id_chamado = $id_chamado order by data_criacao desc limit 1") or print(mysql_error());
        while($dados=mysql_fetch_array($checa)){
           $id_historico_1 = $dados['id_historico'];
        }
$checa = mysql_query("SELECT id_historico FROM sgc_historico_chamado
                      WHERE id_chamado = $id_chamado
                      and id_historico <  $id_historico_1
                      order by data_criacao desc limit 1") or print(mysql_error());
        while($dados=mysql_fetch_array($checa)){
           $id_historico_2 = $dados['id_historico'];
        }

if($id_historico_2==null){

$checa = mysql_query("SELECT id_historico FROM sgc_historico_chamado
                      WHERE id_chamado = $id_chamado
                      and id_historico <=  $id_historico_1
                      order by data_criacao desc limit 1") or print(mysql_error());
        while($dados=mysql_fetch_array($checa)){
           $id_historico_2 = $dados['id_historico'];
        }
}

$checa = mysql_query("SELECT
                      timediff(sc.data_criacao,sc1.data_criacao)tempo_criacao
                      FROM
                       sgc_historico_chamado sc
                       ,sgc_historico_chamado sc1
                       WHERE sc.id_historico = $id_historico_1
                       and sc1.id_historico = $id_historico_2") or print(mysql_error());
        while($dados=mysql_fetch_array($checa)){
           $tempo_status = $dados['tempo_criacao'];
        }



return $tempo_status;
}


function tela_cadastrada($tela,$idusuario){
$checa = mysql_query("select * from sgc_item_menu  where link_item like '%$tela'") or print(mysql_error());
        while($dados=mysql_fetch_array($checa)){
           $id_item = $dados['id_item_menu'];
        }

if($id_item==null){
  $id_item=0;
}

$acesso_tela=acesso($idusuario,"$id_item");
if($acesso_tela==null){
 $acesso_tela="NEGADO";
}
return $acesso_tela;
}


function id_sla(){

$checa_sla_service= mysql_query
("
 SELECT
 if(tipo_tempo='Minutos',round(tempo*60,0),if(tipo_tempo='Horas',round(tempo*3600,0),round(tempo*86400,0)))tem_segundos_sla
,time_to_sec(timediff(hora_final,curtime()))tem_rest_final_sla_sec
,if(if(tipo_tempo='Minutos',round(tempo*60,0),if(tipo_tempo='Horas',round(tempo*3600,0),round(tempo*86400,0)))<time_to_sec(timediff(hora_final,curtime()))
,id_sla_service
,
(SELECT id_sla_service FROM sgc_sla_servicedesk  WHERE
(ADDTIME(Curtime(),SEC_TO_TIME(if(tipo_tempo='Minutos',round(tempo*60,0),if(tipo_tempo='Horas',round(tempo*3600,0),round(tempo*86400,0))))

+ sec_to_time(tem_segundos_sla)

) not between hora_final and hora_inicio and hora_final<hora_inicio)
 or
(ADDTIME(Curtime(),SEC_TO_TIME(if(tipo_tempo='Minutos',round(tempo*60,0),if(tipo_tempo='Horas',round(tempo*3600,0),round(tempo*86400,0))))

+ sec_to_time(tem_segundos_sla)

)between hora_inicio and hora_final and hora_final>hora_inicio))
)condicao_regra

FROM sgc_sla_servicedesk  WHERE
(curtime() not between hora_final and hora_inicio and hora_final<hora_inicio)
 or
(curtime() between hora_inicio and hora_final and hora_final>hora_inicio)

") or print(mysql_error());
                         while($dados_sla=mysql_fetch_array($checa_sla_service)){
                         $id_sla = $dados_sla['condicao_regra'];
}

return $id_sla;

}

function diratual(){
    $arfdn = explode('/', dirname($_SERVER['PHP_SELF']));
    return end($arfdn);
}


function current_url() {
 if (sizeof($_GET) != 0) {
   $pos_url = "?";
   while ($i < sizeof($_GET)) {
     $a = each($_GET);
     if ($i+1 == sizeof($_GET))
       $pos_url = $pos_url.$a[0]."=".$a[1];
     else
       $pos_url = $pos_url.$a[0]."=".$a[1]."&";
     $i++;
   }
 }
 $current_url = $_SERVER["REDIRECT_URL"].$pos_url;
 return $current_url;
}

function tamanho_arquivo($tamanhoarquivo) {

//       echo tamanho_arquivo(filesize("/caminho/para/arquivo/arquivo.extensao"));


	$bytes = array('KB', 'MB', 'GB', 'TB');

	if($tamanhoarquivo <= 999) {
		$tamanhoarquivo = 1;
	}

	for($i = 0; $tamanhoarquivo > 999; $i++) {
		$tamanhoarquivo /= 1024;
	}

	return round($tamanhoarquivo).$bytes[$i];
}


//--------------------------------------------------------------------------------------------------//
function email($nome_adm,$email_adm,$email_dest,$nome,$txtAssunto,$mensagem){

	$mail = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host = "192.9.1.252"; // SMTP server
	$mail->From = $email_adm;
	$mail->FromName = $nome_adm;

    $mail->Body    = $mensagem;
    $mail->AltBody = $mensagem;
	$mail->Subject = $txtAssunto;
    $mail->AddAddress($email_dest, $nome);

    if(!$mail->Send()){

       return "ERRO";

     }else{

       return "OK";

     }
     $mail->ClearAddresses();
}


function expectadores($id_chamado){
$count=0;
 $checa = mysql_query("select id_usuario_contatar from sgc_contatos_por_chamado where id_chamado=$id_chamado") or $myerr = mysql_error();
     while($dados=mysql_fetch_array($checa)){
      $id_usuario[$count]= $dados["id_usuario_contatar"];
     $count++;
  }
 return  $id_usuario;
}


function email_sgc($id_adm,$id_usuario_dest,$txtAssunto,$mensagem){

  $checa = mysql_query("select concat(primeiro_nome,' ',ultimo_nome)nome,email from sgc_usuario where id_usuario=$id_adm") or $myerr = mysql_error();
     while($dados=mysql_fetch_array($checa)){
      $nome_adm= $dados["nome"];
      $email_adm= $dados["email"];
  }

  $checa = mysql_query("select concat(primeiro_nome,' ',ultimo_nome)nome,email from sgc_usuario where id_usuario=$id_usuario_dest") or $myerr = mysql_error();
     while($dados=mysql_fetch_array($checa)){
      $nome_user= $dados["nome"];
      $email_user= $dados["email"];
  }




	$mail = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host = "192.9.1.252"; // SMTP server
	$mail->From = $email_adm;
	$mail->FromName = $nome_adm;

    $mail->Body    = $mensagem;
    $mail->AltBody = $mensagem;
	$mail->Subject = $txtAssunto;
    $mail->AddAddress($email_user, $nome_user);

    if(!$mail->Send()){

       return "ERRO";

     }else{

       return "OK";

     }
     $mail->ClearAddresses();
}


function atributo($var){
       $checa = mysql_query("SELECT $var FROM sgc_parametros_sistema ") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
         $valor = $dados["$var"];
 }

return $valor;
}

function bloqueio_usuario($var,$var1){
  $existe=tabelainfo($var,"sgc_usuario","id_usuario","id_usuario","");

if($var!=null and $existe==$var){

       $checa = mysql_query("UPDATE sgc_usuario set desativacao=sysdate(), data_alteracao=sysdate(), quem_alterou=0, oque_alterou='$var1' where id_usuario=$var") or print(mysql_error());
       $valor="Bloqueado";
}else{

       $valor="N�o Bloqueado";

}
       return $valor;

}


function ultimo_registro($campo,$tabela,$ordenador){
       $checa = mysql_query("SELECT $campo FROM $tabela order by $ordenador desc limit 1 ") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
         $valor = $dados["$campo"];
 }
return $valor;
}


function novos_chamados($var){
$checa = mysql_query("select count(*)CONTADOR
from
  sgc_chamado ch
, sgc_sla_analista_usuario sla
, sgc_usuario us
where  ch.id_chamado   not in ( SELECT hch.id_chamado FROM sgc_historico_chamado hch WHERE hch.id_chamado = ch.id_chamado )
  and ch.id_urgencia_usuario = sla.id_sla_analista
  and us.id_usuario = ch.id_usuario


  and time_to_sec(TIMEDIFF(sysdate('%Y-%m-%d %H:%i:%s'),ch.data_criacao))>
  (SELECT  round(if(tipo_tempo='Minutos',round(tempo*60,0),if(tipo_tempo='Horas',round(tempo*3600,0),round(tempo*86400,0)))/'1.2',0) tempo

FROM sgc_sla_servicedesk  WHERE
           (curtime() not between hora_final and hora_inicio and hora_final<hora_inicio)
            or
            (curtime() between hora_inicio and hora_final and hora_final>hora_inicio))
  order by ch.data_criacao desc") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
         $count = $dados['CONTADOR'];
 }
  return $count;
}

function chamado_status($var){
$checa = mysql_query("SELECT if((SELECT 1 FROM sgc_chamado WHERE id_chamado =$var),'1','0' )VALOR ") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
    $valor = $dados['VALOR'];
}
return $valor;
}

function chamados_suporte($var){
$checa = mysql_query("

SELECT

 if(hc.visto_suporte='0000-00-00 00:00:00' or hc.visto_suporte is null,'NOVO','' ) Teste


,hc.visto_suporte
,ch.id_chamado
FROM sgc_chamado ch, sgc_historico_chamado hc
where hc.id_chamado = ch.id_chamado
and ch.id_suporte =$var
order by hc.id_historico  desc limit 1") or print(mysql_error());
while($dados=mysql_fetch_array($checa)){
    $visto = $dados['Teste'];
 }
if($visto=="NOVO"){
  return 1;
}else{
  return 0;
}


}









function datahoje($var){
if($var=="data"){

         $d = date("d");
         $s = date("D");
         $m = date("m");
         $hora = date("G:i");
         $ano = date("Y");

         $data_hoje.=$ano."-".$m."-".$d;


}elseif($var=="hora"){

         $d = date("d");
         $s = date("D");
         $m = date("m");
         $hora = date("G:i");
         $ano = date("Y");

         $data_hoje.=$hora;


}elseif($var=="datahora"){

         $d = date("d");
         $s = date("D");
         $m = date("m");
         $hora = date("G:i");
         $ano = date("Y");

         $data_hoje.=$ano."-".$m."-".$d." ".$hora;



}



return $data_hoje;
}

function data_with_hour($var){
if($var != null || $var != ""){
   list ($data, $horas) = split ('[T]',$var);
   $var=$data." ".$horas;
   list ($ano, $mes, $dia) = split ('[-]',$var);
   list ($dia, $hora) = split ('[ ]',$dia);
   list ($hora, $minuto, $segundo) = split ('[:]',$hora);
   $data_hoje.=$dia."/".$mes."/".$ano." ".$hora.":".$minuto;
}else{
  $data_hoje="";
}

return $data_hoje;
}




function organizacao($campo){

$checa = mysql_query("select $campo from sgc_organizacao ") or print(mysql_error());
         while($dados=mysql_fetch_array($checa)){
         $ler_campo = $dados["$campo"];
}
return $ler_campo;
}





function invertedata($var){
if($var!=null){
 list ($ano, $mes, $dia) = split ('[-]',$var);
 $data_hoje.=$dia."/".$mes."/".$ano;
 return $data_hoje;
}else{
 return null;
}

}

function databd($var){
list ($dia, $mes, $ano) = split ('[/]',$var);
$data_bd.=$ano."-".$mes."-".$dia;
if($dia==null && $mes==null){
return null;
}else{
return $data_bd;
}
}

function databd_ext($var,$tipo){
if($tipo=="data"){

list ($dia, $mes, $ano) = split ('[/]',$var);
$data_bd.=$ano."-".$mes."-".$dia;
if($dia==null && $mes==null){
return null;
}else{
return $data_bd;
}




}elseif($tipo=="data_hora_minuto"){

list ($dia, $mes, $ano) = split ('[/]',$var);
list ($ano, $hora) = split ('[ ]',$ano);
$data_bd.=$ano."-".$mes."-".$dia." ".$hora.":00";
if($dia==null && $mes==null){
return null;
}else{
return $data_bd;
}




}elseif($tipo=="data_hora_minuto_segundo"){

list ($dia, $mes, $ano) = split ('[/]',$var);
list ($ano, $hora) = split ('[ ]',$ano);
$data_bd.=$ano."-".$mes."-".$dia." ".$hora;
if($dia==null && $mes==null){
return null;
}else{
return $data_bd;
}



}else{
   return "1979-04-02 23:59:29";
}


}




function gerasenha(){
 $CaracteresAceitos = 'abcdefghijmnlopqrstuvxzy0123456789';
 $max = strlen($CaracteresAceitos)-1;

  $password = null;

  for($i=0; $i < 8; $i++) {

   $password .= $CaracteresAceitos{mt_rand(0, $max)};

  }

  return $password;

}

function contador($id,$tabela,$where,$and){

  $checa = mysql_query("select count(*)CONTADOR from $tabela where $where='$id' $and") or $myerr = mysql_error();
  while($dados=mysql_fetch_array($checa)){
  $info= $dados['CONTADOR'];
  }
  $row = mysql_num_rows($checa);

if(isset($myerr)){
  return mysql_error();
}else{
 if($row<1){
   return "<font face='Verdana' size='1' color='#FFFFFF'><span style='background-color: #FF0000'>&nbsp;Campo n�o encontrado! </span></font>";
 }else{
    return $info;
    }
  }
}



function tabelainfo($id,$tabela,$campo,$where,$and){

  $checa = mysql_query("select $campo from $tabela where $where='$id' $and ") or $myerr = mysql_error();
  while($dados=mysql_fetch_array($checa)){
  $info= $dados["$campo"];
  }
  $row = mysql_num_rows($checa);

if(isset($myerr)){
  return mysql_error();
}else{
 if($row<1){
   return "<font face='Verdana' size='1' color='#FFFFFF'><span style='background-color: #FF0000'>&nbsp;Refer�ncia Inv�lida! </span></font>";
 }else{
    return $info;
    }
  }
}

function integridade($desc,$tabela,$campo,$where,$and){

  $checa = mysql_query("select $campo from $tabela where $where='$desc' $and") or $myerr = mysql_error();
  while($dados=mysql_fetch_array($checa)){
  $info= $dados["$campo"];
  }
  $row = mysql_num_rows($checa);

if(isset($myerr)){
  return mysql_error();
}else{
 if($row>0){
   return "Existe";
 }else{
    return $info;
    }
  }
}

function integridade_relacional($desc,$tabela,$campo,$where,$and){

  $checa = mysql_query("select $campo from $tabela where $where='$desc' $and") or $myerr = mysql_error();
  while($dados=mysql_fetch_array($checa)){
  $info= $dados["$campo"];
  }
  $row = mysql_num_rows($checa);

if(isset($myerr)){
  return mysql_error();
}else{
 if($row>0){
   return "Existe";
 }else{
    return $info;
    }
  }
}

function alter_resp($idusuario){


$template_perfil=tabelainfo($idusuario,"sgc_usuario","perfil","id_usuario","");

if($template_perfil=="CUSTOMIZADO"){
  return "SIM";

}else{

  $template_perfil=tabelainfo($template_perfil,"sgc_template_menu","id_template","id_template","");

  $checa = mysql_query("SELECT
  if(ps.atributo8=tm.descricao,'SIM','NAO')resp
  FROM sgc_parametros_sistema ps, sgc_template_menu tm
  where
  tm.id_template=$template_perfil
  and ps.atributo8=tm.descricao") or $myerr = mysql_error();
  while($dados=mysql_fetch_array($checa)){
  $info= $dados["resp"];
  }
  return $info;
}


}

function areasuporte($idusuario){
$checa = mysql_query("SELECT id_area FROM sgc_associacao_area_analista WHERE id_analista = $idusuario ORDER BY id_area LIMIT 1") or $myerr = mysql_error();
  while($dados=mysql_fetch_array($checa)){
  $decision= $dados["id_area"];
  }
return $decision;
}


function analista($idusuario){

$checa = mysql_query("select distinct
                     if(an.id_analista is not null,'ANALISTA','') ANALISTA
                     from sgc_associacao_area_analista an where an.id_analista = $idusuario") or $myerr = mysql_error();
  while($dados=mysql_fetch_array($checa)){
  $decision= $dados["ANALISTA"];
  }
  if($decision=="ANALISTA"){

    $decision="SIM";

  }else{

    $decision="NAO";

  }


return $decision;
}


function acesso($idusuario,$iditem){
$template=tabelainfo($idusuario,"sgc_usuario","perfil","id_usuario","");

if($template=="CUSTOMIZADO"){

  $sql="select 1 from sgc_regra_menu rm where rm.id_usuario='$idusuario' and rm.id_item_menu='$iditem'";

}else{

  $sql="select 1 from sgc_regra_menu rm
        where rm.id_usuario='$idusuario' and rm.id_item_menu='$iditem'
        union all
        select 1 from sgc_template_regra tr where tr.id_template = $template and tr.id_item = $iditem";

}


  $checa = mysql_query("$sql") or $myerr = mysql_error();
  $row = mysql_num_rows($checa);

  $checa = mysql_query("SELECT if(desativacao is null,'ATIVADO','DESATIVADO')FLAG FROM sgc_usuario WHERE id_usuario=$idusuario") or $myerr = mysql_error();
  while($dados=mysql_fetch_array($checa)){
    $desativacao= $dados["FLAG"];
  }


if(isset($myerr)){
  return mysql_error();
}else{
 if($row>0){
  if($desativacao=="DESATIVADO"){
    return $info;
  }else{
    return "OK";
  }
 }else{
    return $info;
    }
  }
}




?>
