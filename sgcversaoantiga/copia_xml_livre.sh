#!/bin/bash

HOST=$1
CHAVE=$2
scp root@${HOST}:/home/nfe/oobj-nfe/integracao/processados/${CHAVE} /var/www/xfac/sgc/nfexml/recuperadas/${CHAVE}