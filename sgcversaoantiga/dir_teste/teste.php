
<?


$ds=ldap_connect("ldap.conab.gov.br",389);
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

if ($ds)
{
	$dn="uid=marcio.r.oliveira,ou=usuarios,dc=matriz,dc=conab,dc=gov,dc=br"; //colocar nome de usuario aqui
	$r=ldap_bind($ds,$dn,"03002721206"); //colocar senha aqui

	//$filter="(*)"; //colocar nome de usuario aqui de novo
	//$sr=ldap_search($ds, "dc=conab,dc=gov,dc=br", $filter);
	$sr= ldap_search($ds,"dc=conab,dc=gov,dc=br", 'uid=*');
   echo	$info = ldap_get_entries($ds, $sr);


	ldap_close($ds); 	//nao como passar o valor de $ds para dentro de TableLogin, se conseguir passar, não sera necessario desconectar do servidor e depois reconectar dentro da funcao userval
}






?>

