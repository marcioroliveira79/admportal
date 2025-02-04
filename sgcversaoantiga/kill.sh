#!/bin/bash
ip=$1
tipo_user=$2
execucao=$(ssh root@$ip "killall -u $tipo_user")

