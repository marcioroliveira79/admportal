#!/bin/sh
TABLE_NAME='sgc_servidores'
DATABASE='db_sgc'
USER_NAME='xfac'
IP_ADDR='mysql.conab.gov.br'
PASSWORD='xfacsalvador'

i=$1
arquivo=$2

COUNT_PING=0
achou=0

while [ $COUNT_PING -lt 3 -a $achou -ne 1 ] ;  do

  achou=$(ping -c 1 $i| grep packet | cut -d , -f2 | awk '{ print $1 }')
  COUNT_PING=$((COUNT_PING+1))

done

if [ "$achou" == "1" ]; then

     conect=$(ssh -q -o "BatchMode=yes" root@$i "	echo 2>&1" && echo "OK" || echo "NOK")
     echo $conect

     if [ $conect == "OK" ];then
        
        if [ $arquivo == "manut.ini" ];then
           SQL_MANUT="select arquivo_manutencao from sgc_servidores where ip_host='$i'"
           PATH_MANUT="select path_manutencao from sgc_servidores where ip_host='$i'"

           mensagem=$(mysql --user=${USER_NAME} --password=${PASSWORD} ${DATABASE} -h ${IP_ADDR} -e "$SQL_MANUT")
           caminho_manut=$(mysql --user=${USER_NAME} --password=${PASSWORD} ${DATABASE} -h ${IP_ADDR} -e "$PATH_MANUT")

           caminho_manut=(`echo $caminho_manut | tr ' ' ' '`)
         
      
           mensagem="${mensagem//arquivo_manutencao/}"
                    
           existe_manut=$(ssh root@$i "if [ -e ${caminho_manut[1]} ];then echo "ok"; else echo "not"; fi")
           if [ $existe_manut == "ok" ]; then	
             apaga=$(ssh root@$i "rm ${caminho_manut[1]}")
           fi
           grava=$(ssh root@$i "echo "$mensagem" >> ${caminho_manut[1]}")
           echo "Arquivo Manutencao Gravado Com Sucesso"
        else
          echo "Arquivo Desconhecido"
        fi  
     else
      echo "Sem Permissao de Acesso"
     fi

else

echo "Servidor OFF-Line"

fi
./monitor_maquina.sh $i