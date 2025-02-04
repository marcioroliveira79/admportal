#!/bin/bash

HOST=$1
CHAVE=$2
scp root@${HOST}:/home/nfe/oobj-nfe/integracao/processados/nfe${CHAVE}.xml /var/www/xfac/sgc/nfexml/reprocessadas/nfe${CHAVE}.xml
