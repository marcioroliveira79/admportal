#!/bin/bash
TABLE_NAME='sgc_servidores'
DATABASE='db_sgc'
USER_NAME='xfac'
IP_ADDR='mysql.conab.gov.br'
PASSWORD='xfacsalvador'

ip=$1
tipo_user=$2

conect=$(ssh -q -o "BatchMode=yes" root@$ip "	echo 2>&1" && echo "OK" || echo "NOK")
echo $conect
 if [ $conect == "OK" ];then 
    usuarios=$(ssh root@$ip "w -hus $2")
    usuarios=(`echo $usuarios | tr ' ' ' '`)       
    echo $usuarios
    
 else
   echo "Sem Permissao de Acesso"
 fi