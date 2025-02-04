<?

       $conexao = mysql_connect('localhost','sgc','senha_sgc') or die ("LDAP - N�o foi poss�vel conectar com o MySQL!");
                   mysql_select_db('db_sgc') or die ("Banco de dados inexistente - Conecta");


function cadastro_ldap($st,$codigost,$idusuario,$centro_custo,$departamento,$login,$senha){

if($st!="DF"){

   //----------------------Cadastro de Unidade Filial------------------------//
  $checa = mysql_query("select
                       count(*)CONTADOR
                       ,if(count(*)>0,id_unidade,0) id_unidade
                       from sgc_unidade
                       where
                       descricao='Superintend�ncia Regional'
                       and sigla ='$st'

                       ") or print(mysql_error());
  while($dados=mysql_fetch_array($checa)){
  $contador = $dados['CONTADOR'];
  $id_unidade = $dados['id_unidade'];
  }

  if($contador<1){

    $cadas = mysql_query("INSERT INTO sgc_unidade (codigo,descricao,sigla,ajuda,data_criacao,quem_criou)
                          VALUES ($codigost,'Superintend�ncia Regional','$st','SUREG $st',sysdate(),$idusuario)") or print(mysql_error());




    $checa = mysql_query("SELECT id_unidade FROM sgc_unidade order by id_unidade desc limit 1 ") or print(mysql_error());
    while($dados=mysql_fetch_array($checa)){
         $id_unidade = $dados["id_unidade"];
     }
  }
}else{

  //----------------------Cadastro de Unidade Filial DF------------------------//
  $checa = mysql_query("select
                        count(*)CONTADOR
                        ,if(count(*)>0,id_unidade,0) id_unidade
                        FROM sgc_unidade
                       where
                       descricao='MATRIZ'
                       and sigla ='$st'
                       ") or print(mysql_error());
  while($dados=mysql_fetch_array($checa)){
  $contador = $dados['CONTADOR'];
  $id_unidade = $dados['id_unidade'];
  }

  if($contador<1){

    $cadas = mysql_query("INSERT INTO sgc_unidade (codigo,descricao,sigla,ajuda,data_criacao,quem_criou)
                          VALUES ($codigost,'MATRIZ','$st','CONAB MATRIZ DF',sysdate(),$idusuario)") or print(mysql_error());



    $checa = mysql_query("SELECT id_unidade FROM sgc_unidade order by id_unidade desc limit 1 ") or print(mysql_error());
    while($dados=mysql_fetch_array($checa)){
         $id_unidade = $dados["id_unidade"];
     }




  }
  }
  //----------------------Cadastro de Centro de Custo------------------//
  $checa = mysql_query("select
                       count(*)CONTADOR
                       ,if(count(*)>0,id_centro,0) id_centro
                       from sgc_centro_custo
                       where
                       descricao='$centro_custo $st'
                       ") or print(mysql_error());
  while($dados=mysql_fetch_array($checa)){
    $contador = $dados['CONTADOR'];
    $id_centro = $dados['id_centro'];
  }
  if($contador<1){

    $cadas = mysql_query("INSERT INTO sgc_centro_custo (id_gasto,id_area,codigo,descricao,ajuda,data_criacao,quem_criou)
                          VALUES (4,5,1,'$centro_custo $st','Centro de Custo $centro_custo $st',sysdate(),$idusuario)") or print(mysql_error());


    $checa = mysql_query("SELECT id_centro FROM sgc_centro_custo order by id_centro desc limit 1 ") or print(mysql_error());
    while($dados=mysql_fetch_array($checa)){
         $id_centro = $dados["id_centro"];
     }
  }

  //----------------------Cadastro de Departamento------------------------//
  $checa = mysql_query("select
                         count(*)CONTADOR
                        ,if(count(*)>0,id_departamento,0) id_departamento
                         FROM sgc_departamento
                         where
                         descricao='$departamento'
                       ") or print(mysql_error());
  while($dados=mysql_fetch_array($checa)){
    $contador = $dados['CONTADOR'];
    $id_departamento = $dados['id_departamento'];
  }

  if($contador<1){

    $cadas = mysql_query("INSERT INTO sgc_departamento (descricao,ajuda,data_criacao,quem_criou)
                          VALUES ('$departamento','$centro_custo - $st',sysdate(),$idusuario)") or print(mysql_error());




    $checa = mysql_query("SELECT id_departamento FROM sgc_departamento order by id_departamento desc limit 1 ") or print(mysql_error());
    while($dados=mysql_fetch_array($checa)){
         $id_departamento = $dados["id_departamento"];
     }




  }

  //----------------------Cadastro de usuario------------------//
   $count=0;
   $checa = mysql_query("select count(*)CONTADOR,
                         if(count(*)>0,id_usuario,0) id_usuario
                         ,primeiro_nome
                         ,ultimo_nome
                         ,email
                          from sgc_usuario   where
                         id_usuario=$idusuario
                         group by id_usuario") or print(mysql_error());
  while($dados=mysql_fetch_array($checa)){
    $contador_us = $dados['CONTADOR'];
    $id_usuario = $dados['id_usuario'];
    $primeiro_nome = $dados['primeiro_nome'];
    $ultimo_nome = $dados['ultimo_nome'];
    $email = $dados['email'];
    $count++;

  }
$marcador=$contador_us;

  if($contador_us < 1 or $contador_us == null){

    if(ldap_query($login,$senha,'')!=false){

     list ($primeiro_nome, $ultimo_nome) = split ('[ ]',ldap_query($login,$senha,'displayname'));
           $email=ldap_query($login,$senha,'mail');

    $checa_perfil = mysql_query("SELECT if(count(*)>0,'CUSTOMIZADO',null) PERFIL FROM sgc_regra_menu WHERE id_usuario = $idusuario") or print(mysql_error());
    while($dados=mysql_fetch_array($checa_perfil)){
      $perfil = $dados['PERFIL'];

      if($perfil!="CUSTOMIZADO"){
         $perfil="1";
      }

    }
    $primeiro_nome=strtoupper($primeiro_nome);
    $ultimo_nome=strtoupper($ultimo_nome);

    echo "$id_centro centro<BR>";
    echo "$id_unidade unidade<BR>";
    echo "$id_departamento dep<BR>";

    $cadas = mysql_query("INSERT INTO sgc_usuario
                         (id_usuario
                         ,id_departamento
                         ,id_unidade
                         ,id_centro
                         ,primeiro_nome
                         ,ultimo_nome
                         ,email
                         ,externo
                         ,perfil
                         ,data_criacao
                         ,quem_criou)
                          VALUES
                          ($idusuario
                           ,$id_departamento
                           ,$codigost
                           ,$id_centro
                           ,'$primeiro_nome'
                           ,'$ultimo_nome'
                           ,'$email'
                           ,'SIM'
                           ,'$perfil'
                           ,sysdate()
                           ,$idusuario)") or print(mysql_error());

   $marcador = "AQUI";
   return $marcador;


  }else{
   $marcador = "X USUARIO NAO ENCONTRADO LDAP";
   return $marcador;
  }
}

 if(ldap_query($login,$senha,'')!=false){

     $nomes=null;
     $nomes= explode(" ",ldap_query($login,$senha,'displayname'));
     $primeiro_nome=$nomes[0];

     $count=0;
     $ultimo_nome=null;
     foreach ($nomes as $valor) {
     if($count!=0){
       $ultimo_nome.="$valor ";
      }
     $count++;
     }









      $email=ldap_query($login,$senha,'mail');

     }

     $primeiro_nome=strtoupper($primeiro_nome);
     $ultimo_nome=strtoupper($ultimo_nome);




 $checa = mysql_query("SELECT count(*)CONTADOR
                       FROM  sgc_usuario us
                       WHERE us.id_usuario = $idusuario
                       and us.primeiro_nome='$primeiro_nome'
                       and us.ultimo_nome='$ultimo_nome'
                       and us.email='$email'
                       and us.id_departamento = $id_departamento
                       and us.id_centro = $id_centro
                       and us.id_unidade = $codigost") or print(mysql_error());
  while($dados=mysql_fetch_array($checa)){
    $contador_atualizador = $dados['CONTADOR'];
  }

if($contador_atualizador<1){




   $cadas = mysql_query("UPDATE sgc_usuario set  id_departamento=$id_departamento
                                                ,id_unidade=$codigost
                                                ,id_centro=$id_centro
                                                ,primeiro_nome='$primeiro_nome'
                                                ,ultimo_nome='$ultimo_nome'
                                                ,email='$email'
                                                ,data_alteracao=sysdate()
                                                ,quem_alterou=$idusuario
                                                ,oque_alterou='Automatico Cadastro LDAP'
                                                 where id_usuario = $idusuario") or print(mysql_error());


}

//$marcador =" ID DEP: $id_departamento ID UNI: $codigost ID CENTRO: $id_centro ID USU: $idusuario";

return  "$marcador";

}


function ldap_query($uid,$ldappass,$campo){

$ldaprdn  = "uid=$uid, ou=usuarios, dc=matriz, dc=conab, dc=gov, dc=br";
$ldapconn = ldap_connect("ldap.conab.gov.br");

if ($ldapconn) {
   ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
	if ($ldapbind) {
		$query        = ldap_search($ldapconn, $ldaprdn, 'uid='. $uid);
		if ($query) {
			$query_result = ldap_get_entries($ldapconn, $query);

            if ($query_result["count"] != 1) {
                return FALSE;
			} else {
                $username     = str_replace("\'", "''", $query_result[0]["displayname"][0]);
				$email 	      = str_replace("\'", "''", $query_result[0]["mail"][0]);
				$userid       = str_replace("\'", "''", $query_result[0]["uidnumber"][0]);
				$descricao    = str_replace("\'", "''", $query_result[0]["description"][0]);
			    $stat         = str_replace("\'", "''", $query_result[0]["st"][0]);
                $l            = str_replace("\'", "''", $query_result[0]["l"][0]);

                $ldap_resultado[0] = str_replace("\'", "''", $query_result[0]["displayname"][0]);
                $ldap_resultado[1] = str_replace("\'", "''", $query_result[0]["mail"][0]);
                $ldap_resultado[2] = str_replace("\'", "''", $query_result[0]["uidnumber"][0]);
                $ldap_resultado[3] = str_replace("\'", "''", $query_result[0]["description"][0]);
                $ldap_resultado[4] = str_replace("\'", "''", $query_result[0]["st"][0]);
                $ldap_resultado[5] = str_replace("\'", "''", $query_result[0]["l"][0]);
                list ($ldap_resultado[7], $ldap_resultado[8]) = split ('[;]',$ldap_resultado[3]);

                if($ldap_resultado[4]=="RO"){
                   $ldap_resultado[6] ="11";
                }elseif($ldap_resultado[4]=="AC"){
                        $ldap_resultado[6] ="12";

                }elseif($ldap_resultado[4]=="AM"){
                        $ldap_resultado[6] ="13";

                }elseif($ldap_resultado[4]=="RR"){
                        $ldap_resultado[6] ="14";

                }elseif($ldap_resultado[4]=="PA"){
                        $ldap_resultado[6] ="15";

                }elseif($ldap_resultado[4]=="AP"){
                        $ldap_resultado[6] ="16";

                }elseif($ldap_resultado[4]=="TO"){
                        $ldap_resultado[6] ="17";

                }elseif($ldap_resultado[4]=="MA"){
                        $ldap_resultado[6] ="21";

                }elseif($ldap_resultado[4]=="PI"){
                        $ldap_resultado[6] ="22";

                }elseif($ldap_resultado[4]=="CE"){
                        $ldap_resultado[6]=23;

                }elseif($ldap_resultado[4]=="RN"){
                        $ldap_resultado[6]=24;

                }elseif($ldap_resultado[4]=="PB"){
                        $ldap_resultado[6]=25;

                }elseif($ldap_resultado[4]=="PE"){
                        $ldap_resultado[6]=26;

                }elseif($ldap_resultado[4]=="AL"){
                        $ldap_resultado[6]=27;

                }elseif($ldap_resultado[4]=="SE"){
                        $ldap_resultado[6]=28;

                }elseif($ldap_resultado[4]=="BA"){
                        $ldap_resultado[6]=29;

                }elseif($ldap_resultado[4]=="MG"){
                        $ldap_resultado[6]=31;

                }elseif($ldap_resultado[4]=="ES"){
                        $ldap_resultado[6]=32;

                }elseif($ldap_resultado[4]=="RJ"){
                        $ldap_resultado[6]=33;

                }elseif($ldap_resultado[4]=="SP"){
                        $ldap_resultado[6]=35;

                }elseif($ldap_resultado[4]=="PR"){
                        $ldap_resultado[6]=41;

                }elseif($ldap_resultado[4]=="SC"){
                        $ldap_resultado[6]=42;

                }elseif($ldap_resultado[4]=="RS"){
                        $ldap_resultado[6]=43;

                }elseif($ldap_resultado[4]=="MS"){
                        $ldap_resultado[6]=50;

                }elseif($ldap_resultado[4]=="MT"){
                        $ldap_resultado[6]=51;

                }elseif($ldap_resultado[4]=="GO"){
                        $ldap_resultado[6]=52;

                }elseif($ldap_resultado[4]=="DF"){
                        $ldap_resultado[6]=53;
                }

                if($campo==null){
                  return $ldap_resultado;
                }else{
                  return str_replace("\'", "''", $query_result[0]["$campo"][0]);
               }
            }
         }
      }
   }
}


?>
