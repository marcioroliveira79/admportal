ip=$1
comando=$2

execucao=$(ssh root@$ip "$2")
echo $execucao