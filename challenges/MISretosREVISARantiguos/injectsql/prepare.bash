#!/bin/bash

mydir=$( cd ${0%/*} ; pwd )
cd $mydir

definstance=${mydir##*/}
defport=5050

INSTANCENAME=${1:-$definstance}
PORT=${2:-$defport}
PUBLICINIT=${3:-flag}
PRIVATEINIT=${4:-$RANDOM}

echo "instancename: $INSTANCENAME"
echo "port: $PORT"
echo "publicinit: $PUBLICINIT"
echo "privateinit: $PRIVATEINIT"

printf "PORT=$PORT\n" > .env
echo $INSTANCENAME > _instance

# Generar una flag única para el reto
echo "flag{test}" > _flag.txt
