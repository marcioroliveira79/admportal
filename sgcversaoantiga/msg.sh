#!/bin/bash
ip=$1
execucao=$(ssh root@$ip "echo 'CARO USUARIO - POR FAVOR CONCLUA SUA OPERACAO E SAI DO SISTEMA PARA MANUTENCAO! ' | wall -n")
echo $execucao
