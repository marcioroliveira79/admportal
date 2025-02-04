#!/bin/bash
TABLE_NAME='sgc_servidores'
DATABASE='db_sgc'
USER_NAME='xfac'
IP_ADDR='mysql.conab.gov.br'
PASSWORD='xfacsalvador'

i=$1



COUNT_PING=0
achou=0

while [ $COUNT_PING -lt 3 -a $achou -ne 1 ] ;  do

  achou=$(ping -c 1 $i| grep packet | cut -d , -f2 | awk '{ print $1 }')
  COUNT_PING=$((COUNT_PING+1))
  echo " $COUNT_PING - Tentativa "

done



  if [ "$achou" == "1" ]; then
     echo "Servidor: $i ON-LINE"    
     
     comando=$(mysql --user=${USER_NAME} --password=${PASSWORD} ${DATABASE} -h ${IP_ADDR} -e "UPDATE sgc_servidores SET status='ON', ultimo_ping=sysdate(), falha_conexao_data=null WHERE ip_host = '$i'")

     
     conect=$(ssh -q -o "BatchMode=yes" root@$i "	echo 2>&1" && echo "OK" || echo "NOK")

     echo $conect
     
if [ $conect == "OK" ];then 


     SQL_MANUT="select path_manutencao from sgc_servidores where ip_host='$i'"
     resul_manut=$(mysql --user=${USER_NAME} --password=${PASSWORD} ${DATABASE} -h ${IP_ADDR} -e "$SQL_MANUT")
     
     SQL_ERRO="select path_erro from sgc_servidores where ip_host='$i'"
     resul_erro=$(mysql --user=${USER_NAME} --password=${PASSWORD} ${DATABASE} -h ${IP_ADDR} -e "$SQL_ERRO")

     SQL_EXECUTAVEL="select executavel from sgc_servidores where ip_host='$i'"
     resul_executavel=$(mysql --user=${USER_NAME} --password=${PASSWORD} ${DATABASE} -h ${IP_ADDR} -e "$SQL_EXECUTAVEL")



resul_executavel=(`echo $resul_executavel | tr ' ' ' '`)
echo ${resul_executavel[1]}

resul_manut=(`echo $resul_manut | tr ' ' ' '`)
echo ${resul_manut[1]}

resul_erro=(`echo $resul_erro | tr ' ' ' '`)      
echo ${resul_erro[1]}




     existe_executavel=$(ssh root@$i "if [ -e ${resul_executavel[1]} ];then echo "ok"; else echo "not"; fi")

     if [ $existe_executavel == "ok" ]; then
	   echo "Executavel Encontrado"
 	   data_arquivo_executavel=$(ssh root@$i "date +"%Y-%m-%d/%H:%m:%S" -r ${resul_executavel[1]}")
 	   data_arquivo_executavel=(`echo $data_arquivo_executavel | tr '/' ' '`)
       tamanho_arquivo_executavel=$(ssh root@$i "ls -sh ${resul_executavel[1]}")
       tamanho_arquivo_executavel=(`echo $tamanho_arquivo_executavel | tr ' ' ' '`)
       comando=$(mysql --user=${USER_NAME} --password=${PASSWORD} ${DATABASE} -h ${IP_ADDR} -e "UPDATE sgc_servidores SET data_executavel='${data_arquivo_executavel[0]} ${data_arquivo_executavel[1]}', tamanho_executavel='$tamanho_arquivo_executavel' WHERE ip_host = '$i'")
     else
	   echo "Executavel Nao Encontrado"
	   comando=$(mysql --user=${USER_NAME} --password=${PASSWORD} ${DATABASE} -h ${IP_ADDR} -e "UPDATE sgc_servidores SET data_executavel=null, tamanho_executavel=null WHERE ip_host = '$i'")
     fi

   existe_manut=$(ssh root@$i "if [ -e ${resul_manut[1]} ];then echo "ok"; else echo "not"; fi")


  if [ $existe_manut == "ok" ]; then
	  echo "Manut.ini Encontrado"
 	  data_arquivo_manu=$(ssh root@$i "date +"%Y-%m-%d/%H:%m:%S" -r ${resul_manut[1]}")
 	  data_arquivo_manu=(`echo $data_arquivo_manu | tr '/' ' '`) 
 	
	  comando=$(mysql --user=${USER_NAME} --password=${PASSWORD} ${DATABASE} -h ${IP_ADDR} -e "UPDATE sgc_servidores SET manutencao_status='ON', manutencao_data='${data_arquivo_manu[0]} ${data_arquivo_manu[1]}' WHERE ip_host = '$i'")
  else
	  echo "Manut.ini Nao Encontrado"
	  comando=$(mysql --user=${USER_NAME} --password=${PASSWORD} ${DATABASE} -h ${IP_ADDR} -e "UPDATE sgc_servidores SET manutencao_status='OFF', manutencao_data = null, usuario_criador_manutencao = null  WHERE ip_host = '$i'")
  fi

existe_erro=$(ssh root@$i "if [ -e ${resul_erro[1]} ];then echo "ok"; else echo "not"; fi")
 
  if [ $existe_erro == "ok" ];then
	  echo "Error.log Encontrado"
	  data_arquivo_erro=$(ssh root@$i "date +"%Y-%m-%d/%H:%m:%S" -r ${resul_erro[1]}")
 	  data_arquivo_erro=(`echo $data_arquivo_erro | tr '/' ' '`) 
	  
	  arquivo_erro=$(ssh root@$i cat ${resul_erro[1]} | sed "s/'//g")

  
	  comando=$(mysql --user=${USER_NAME} --password=${PASSWORD} ${DATABASE} -h ${IP_ADDR} -e "UPDATE sgc_servidores SET erro_status='ON', erro_data='${data_arquivo_erro[0]} ${data_arquivo_erro[1]}', arquivo_erro='$arquivo_erro'  WHERE ip_host = '$i'")
  else
  	  echo "Error.log Nao Encontrado"
	  comando=$(mysql --user=${USER_NAME} --password=${PASSWORD} ${DATABASE} -h ${IP_ADDR} -e "UPDATE sgc_servidores SET erro_status='OFF', erro_data=null, arquivo_erro=null WHERE ip_host = '$i'")
  fi
      echo "------------------------"   

  comando=$(mysql --user=${USER_NAME} --password=${PASSWORD} ${DATABASE} -h ${IP_ADDR} -e "UPDATE sgc_servidores SET autorizacao='ON', ultimo_ping=sysdate() WHERE ip_host = '$i'")


else
  comando=$(mysql --user=${USER_NAME} --password=${PASSWORD} ${DATABASE} -h ${IP_ADDR} -e "UPDATE sgc_servidores SET autorizacao='OFF', ultimo_ping=sysdate() WHERE ip_host = '$i'")
fi

  else
     echo $i    
     echo "Servidor: $i OFF-LINE"
     comando=$(mysql --user=${USER_NAME} --password=${PASSWORD} ${DATABASE} -h ${IP_ADDR} -e "UPDATE sgc_servidores SET status='OFF', ultimo_ping=sysdate(), falha_conexao_data=sysdate() WHERE ip_host = '$i'")
     echo "----------------------"
  fi 

 



  
