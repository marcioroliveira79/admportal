#!/bin/bash
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
         existe_arquivo=$(ssh root@$i "if [ -e $2 ];then echo "ok"; else echo "not"; fi") 
         if [ $existe_arquivo == "ok" ];then 
           data_arquivo=$(ssh root@$i "date +"%Y-%m-%d%H:%m:%S" -r $2")
           echo $data_arquivo
         else
           echo "Arquivo nao existe"
         fi
     else
     echo "Sem Permissao de Acesso"
     fi
else

echo "Servidor OFF-Line"

fi
