#!/bin/bash
mydir=$( cd "$(dirname "$0")" && pwd )
cd $mydir

INSTANCENAME=${1:-ia_pickle}
PORT=${2:-58080}
PUBLICINIT=${3:-flag}
PRIVATEINIT=${4:-$RANDOM}

printf "PORT=$PORT\n" > .env
echo $INSTANCENAME > _instance

echo "Esta flag no es, pero vas por el buen camino" > _flag.txt

echo "flag{test}" > _flag2.txt

mkdir -p models templates
cp templates/form.html templates/form.html 2>/dev/null || true

# Generar modelos de relleno
python3 crear_modelos_con_flag.py

echo "Instancia preparada: $INSTANCENAME en puerto $PORT"
