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
echo "publicinit: $PUBLICINIT"
echo "privateinit: $PRIVATEINIT"

printf "PORT=$PORT\n" > .env
echo $INSTANCENAME > _instance

R=$RANDOM$RANDOM
printf "%s{%s}\n" "$PUBLICINIT" "$R" > _flag.txt

echo "Flag generated in _flag.txt"
