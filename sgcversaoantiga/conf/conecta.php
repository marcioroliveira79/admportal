<?
class sgc {
      var $conexao;
      function conectar()
      {
       $conexao = mysql_connect('localhost','sgc','senha_sgc') or die ("CONECTA - N�o foi poss�vel conectar com o MySQL!");
                   mysql_select_db('db_sgc') or die ("Banco de dados inexistente - Conecta");
     }
}

class sgc_nfe {
      var $conexao_nfe;
      function conectar_nfe($sureg)
      {
       $conexao_nfe = pg_connect("host=$sureg port=5432 dbname=bd_xfac user=postgres password=postmy") or die ("
<div align='center'>
	<table class='border' cellSpacing='0' cellPadding='0' width='100%' border='0'>
		<tr style='color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px'>
			<td style='color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px'>
			<table cellSpacing='1' cellPadding='5' width='100%' border='0'>
				<tr style='color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px'>
					<td class='info' align='middle'><b>:: Banco de dados indispon�vel :: </b></td>
				</tr>
				<tr style='color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px'>
					<td class='cat' align='center'>
					&nbsp;<p>N�o foi poss�vel se conectar a base de dados da $sureg, por
					favor tente mais tarde!</p>
					<p><br>&nbsp;</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>");
      }
}


class sgc_etl {
      var $conexao_etl;
      function conectar_etl($sureg)
      {
       $conexao_nfe = pg_connect("host=10.1.14.161 port=5432 dbname=bd_siscorp user=consultas_publicas password=!#@-consultaspublicas_132") or die ("
<div align='center'>
	<table class='border' cellSpacing='0' cellPadding='0' width='100%' border='0'>
		<tr style='color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px'>
			<td style='color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px'>
			<table cellSpacing='1' cellPadding='5' width='100%' border='0'>
				<tr style='color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px'>
					<td class='info' align='middle'><b>:: Banco de dados indispon�vel :: </b></td>
				</tr>
				<tr style='color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px'>
					<td class='cat' align='center'>
					&nbsp;<p>N�o foi poss�vel se conectar a base de dados da $sureg, por
					favor tente mais tarde!</p>
					<p><br>&nbsp;</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>");
      }
}



class sgc_obj {
      var $conexao_obj;
      function conectar_obj()
      {
       $conexao_obj = pg_connect("host=10.1.0.109 port=5432 dbname=oobj_nfe_central user=marcioroliveira password=123456") or die ("N�o foi poss�vel conectar com o PostGre!");
      }
}

class sgc_obj_backup {
      var $conexao_obj_bakcup;
      function conectar_obj_backup()
      {
       $conexao_nfe = pg_connect("host=10.1.0.109 port=5432 dbname=oobj_nfe_central_backup_2011_03_09 user=marcioroliveira password=123456") or die ("N�o foi poss�vel conectar com o PostGre!");
      }
}


?>
