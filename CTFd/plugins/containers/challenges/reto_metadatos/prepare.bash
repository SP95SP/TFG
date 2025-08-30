#!/bin/bash
mydir=$( cd ${0%/*} ; pwd )
cd $mydir

definstance=${mydir##*/}
defport=52222

INSTANCENAME=${1:-$definstance}
PORT=${2:-$defport}

echo "instancename: $INSTANCENAME"
echo "port: $PORT"

printf "PORT=$PORT\n" > .env
echo $INSTANCENAME > _instance

echo "flag{test}" > _flag.txt

echo "Reto preparado. Flag oculta en metadatos."

