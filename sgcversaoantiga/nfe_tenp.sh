#!/bin/sh
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


    if [ $conect == "OK" ];then
        caminho_manut="/home/nfe/esales/nfe/sistema/$arquivo.temp"
        caminho_manut=(`echo $caminho_manut | tr ' ' ' '`)  
       
        #grava=$(ssh root@$i "echo "$arquivo" >> $caminho_manut")
        echo " - Gravado com sucesso"
   else
      echo "Sem Permissao de Acesso"
   fi
else
     echo "Servidor OFF-Line"
fi
