<?Php



//-----------------CONECTA PG------------------//
class sgc_obj_fc {
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
//-----------------FIM CONECTA PG-------------//

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


function LoginUsuario($login,$senha){
    $pg=new sgc_obj_fc;
    $pg->conectar_obj();

    if(isset($login)){
        if(isset($senha)){
            $check = pg_query("
			
			                   SELECT us.id, us.nome, us.sobre_nome, us.email, us.telefone, us.login , pl.id as id_perfil, pl.nome as desc_perfil
							   FROM administracao.usuario us
							   left join administracao.usuario_perfil uf on us.id = uf.fk_usuario 
							   left join administracao.perfil pl on uf.fk_perfil = pl.id
			
                               WHERE login='$login'
                               AND senha='$senha'");
            while($dados=pg_fetch_array($check)){
                $id = $dados['id'];
                $nome = $dados['nome'];
                $sobre_nome = $dados['sobre_nome'];
                $email = $dados['email'];
                $telefone = $dados['telefone'];
                $login_bd = $dados['login'];
				$id_perfil = $dados['id_perfil'];
				$desc_perfil = $dados['desc_perfil'];
            }

            if(isset($id) && isset($id_perfil)){

                return array ('aut' => true,'mensagem'=> null,'id' => $id,'nome' => $nome,'sobre_nome' => $sobre_nome,'email' => $email,'telefone' => $telefone,'login' => $login_bd, 'id_perfil' => $id_perfil, 'desc_perfil' => $desc_perfil );

            }elseif(isset($id) && !isset($id_perfil)){

                return array ('aut' => false,'mensagem'=> 'Usuário sem atribuições');

            }elseif(isset($id)){
				
				return array ('aut' => false,'mensagem'=> 'Usuário ou senha inválido');
			}	

 
        }
    }else{
       return array (false,'Infome o usuário',null,null,null,null,null,null);
      //Sucesso login, mensagem, id, nome, sobre_nome, email, telefone, login//
    }            

}

function LogAcesso($id_usuario, $id_session, $act){
    //-----ACT-----//
    //--in quando vc quer registrar a entrada
    //--out quando vc quer registrar a saida
    //--id quando vc quer receber o id do ultimo acesso baseado no usuario e sessao
    //--dump mostra parametros recebidos

    if(isset($act) OR isset($id_usuario) OR isset($id_session)   ){

        $pg=new sgc_obj_fc;
        $pg->conectar_obj();

        if($act=='in'){

            $check = pg_query("INSERT INTO administracao.log_acesso (fk_usuario,data_acesso,ip_acesso,session_id) 
                     values ($id_usuario,now(),null,'$id_session')");
            
            return "in";

        }elseif($act=='out'){

            $check = pg_query("UPDATE  administracao.log_acesso  SET data_saida=now()
                       WHERE fk_usuario=$id_usuario AND session_id = '$id_session' AND data_saida IS NULL");
            
            return "out";

        }elseif($act=='id' or !isset($act)){

            $check = pg_query("
            select id 
            from administracao.log_acesso
            where fk_usuario = $id_usuario
            and session_id=  '$id_session'
            and data_saida is null            
            ");
            while($dados=pg_fetch_array($check)){
                               $valor = $dados['id'];
            }

            return $valor;

        }elseif($act=='dump'){
           
            return "  ACT: ".$act." ID USU: ".$id_usuario." ID SESSION: ".$id_session;

        }

    }else{

      return "ERRO - Verifique os parametros";

    }

}  

function AtributoSistema($nome_tabela, $nome_item, $id_item){

    $pg=new sgc_obj_fc;
    $pg->conectar_obj();

    if(isset($nome_item)){
        if(isset($nome_tabela)){


            $checa = pg_query("SELECT tba.valor_item FROM administracao.pseudo_tabela tb
                                        INNER JOIN administracao.pseudo_tabela_atributos tba ON tb.id = tba.fk_atributo
                                        WHERE tb.nome_tabela='$nome_tabela'
                                        AND tba.nome_item='$nome_item'");
                           while($dados=pg_fetch_array($checa)){
                               $valor = $dados['valor_item'];
                            }
            return $valor;

        }else{
            return "Você deve informar o nome da tabela";
        }


    }elseif(isset($id_item)){

        $checa = pg_query("SELECT tba.id FROM administracao.pseudo_tabela tb
        INNER JOIN administracao.pseudo_tabela_atributos tba ON tb.id = tba.fk_atributo
        WHERE tba.id='$id_item'
        ");
        while($dados=pg_fetch_array($checa)){
        $valor = $dados['id'];
        }
        return $valor;

    }else{
        return "Você deve passar nome ou id do item!";
    }
}

function anti_injection($sql)
{
    // remove palavras que contenham sintaxe sql
        $sql = preg_replace(preg_quote("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"),"",$sql);
        $sql = trim($sql);//limpa espa�os vazio
        $sql = strip_tags($sql);//tira tags html e php
        $sql = addslashes($sql);//Adiciona barras invertidas a uma string
    return $sql;
}


function getMenuItem($perfil,$idusuario){
    $pg=new sgc_obj_fc;
    $pg->conectar_obj();
	
	if(isset($perfil) && isset($idusuario)){
		
		
		             
						  				  
                           					
							$getMenu = pg_query("
							SELECT DISTINCT
								  us.id
								, us.nome
								, uf.id as id_perfil
								, pl.nome
								, mn.id as id_menu
								, mn.descricao as menu
								, mn.ajuda as ajuda_menu
								FROM administracao.usuario us
								 join administracao.usuario_perfil uf on us.id = uf.fk_usuario 
								 join administracao.perfil pl on uf.fk_perfil = pl.id
								 join administracao.menus_e_itens mi on mi.fk_perfil = pl.id
								 join administracao.menu mn on mi.fk_menu = mn.id								
							WHERE 1=1
							and uf.id = $perfil
							and us.id = $idusuario
							order by mn.id asc
							");
							while($menu=pg_fetch_array($getMenu)){
							$id_menu= $menu['id_menu'];
							$menuHtml.='
													
						              
						<tr>
                          <td class="cat"><b>'.$menu['menu'].'</b></td>
                        </tr>
                        <tr>';
						
						$getItem = pg_query("
							select 
							  mn.id
							, it.id as id_item
							, it.descricao as item
							, it.ajuda as ajuda_item
							, it.link as link_item
							from administracao.menu mn
							join administracao.menus_e_itens im on im.fk_menu = mn.id
							join administracao.item_menu it on im.fk_item = it.id
							where 1=1
							and mn.id = $id_menu
							order by it.id asc
							");
							
						while($item=pg_fetch_array($getItem)){						
                         $menuHtml.='<td class="subcat">
                           <li><a href="?xItem='.$item['id_item'].'">'.$item['item'].'</a></li>
						   </td>
                        </tr>';
								}
						}						 
		return $menuHtml;
		
	}else{
		
		    return null;
	};	
};	

function getTelaAut($idusuario,$idperfil,$xItem){
	$pg=new sgc_obj_fc;
    $pg->conectar_obj();
	
	if(isset($idusuario) && isset($idperfil) && isset($xItem)){
		$autorizacao = false;
		$getAut = pg_query("select 
							case when count(*) > 0 then true else false end aut_item
							,im.link
							,im.arquivo_php
							from administracao.usuario us
							inner join administracao.usuario_perfil uf on uf.fk_usuario = us.id  
							inner join administracao.perfil pe on pe.id = uf.fk_perfil
							inner join administracao.menus_e_itens mi on mi.fk_perfil = pe.id
							inner join administracao.menu mn on mn.id = mi.fk_menu
							inner join  administracao.item_menu im on im.id = mi.fk_item
							where 1=1

							and us.id = $idusuario
							and pe.id = $idperfil
							and im.id = $xItem
							
							group by 
							 im.link
							,im.arquivo_php
							
							");
							
						while($Aut=pg_fetch_array($getAut)){
							$autorizacao =  $Aut['aut_item'];
							$arquivo =  $Aut['arquivo_php'];
							 
						}
						if($autorizacao == true){
							
								return array ('aut' => true,'arquivo'=> $arquivo );
						
						}else{
	
							return array ('aut' => false,'arquivo'=> null );
							
						}						
						
	}else{

		return array ('aut' => false,'arquivo'=> null );
		
	} 		

}	

?>