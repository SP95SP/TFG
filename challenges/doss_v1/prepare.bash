#!/bin/bash

mydir=$( cd ${0%/*} ; pwd )
cd $mydir

definstance=${mydir##*/}
defport=50080

INSTANCENAME=${1:-$definstance}
PORT=${2:-$defport}
PUBLICINIT=${3:-flag}
PRIVATEINIT=${4:-$RANDOM}

echo "instancename: $INSTANCENAME"
echo "port: $PORT"

printf "PORT=$PORT\n" > .env
echo $INSTANCENAME > _instance

echo "flag{test}" > _flag.txt

# Inicializa el contador de peticiones
echo "0" > counter.txt

echo "Environment prepared. Flag generated in _flag.txt"

# Dar permisos de escritura sobre el contador
touch counter.txt
touch _flag.txt
chmod 666 counter.txt _flag.txt
