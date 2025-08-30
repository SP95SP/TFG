#!/bin/bash
mydir=$( cd ${0%/*} ; pwd )
cd $mydir

INSTANCENAME=${1:-ia_pickle}
PORT=${2:-58080}



printf "PORT=$PORT\n" > .env
echo $INSTANCENAME > _instance

echo "flag{test}" > _flag.txt

mkdir -p models templates
cp templates/form.html templates/form.html 2>/dev/null || true

echo "Instancia preparada: $INSTANCENAME en puerto $PORT"
