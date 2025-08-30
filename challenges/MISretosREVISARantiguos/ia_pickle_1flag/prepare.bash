#!/bin/bash
mydir=$( cd ${0%/*} ; pwd )
cd $mydir

INSTANCENAME=${1:-ia_pickle}
PORT=${2:-58080}
PUBLICINIT=${3:-flag}
PRIVATEINIT=${4:-$RANDOM}

printf "PORT=$PORT\n" > .env
echo $INSTANCENAME > _instance

R=$RANDOM$RANDOM
printf "%s{%s}\n" "$PUBLICINIT" "$R" > _flag.txt

mkdir -p models templates
cp templates/form.html templates/form.html 2>/dev/null || true

echo "Instancia preparada: $INSTANCENAME en puerto $PORT"
