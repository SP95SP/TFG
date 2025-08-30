#!/bin/bash

# Ubicación del directorio actual
mydir=$( cd ${0%/*} ; pwd )
cd $mydir

# Variables por defecto
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

# Se crea el archivo .env con el puerto
printf "PORT=$PORT\n" > .env

# Se guarda el nombre de la instancia
echo $INSTANCENAME > _instance

# Se genera la flag (similar al ejemplo)
R=$RANDOM$RANDOM
printf "%s{%s}\n" "$PUBLICINIT" "$R" > _flag.txt

# Creación de la base de datos SQLite y la tabla con el usuario inicial
DBFILE="users.db"
if [ -f $DBFILE ]; then
  rm $DBFILE
fi

sqlite3 $DBFILE "CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT, password TEXT);"
sqlite3 $DBFILE "INSERT INTO users (username, password) VALUES ('admin', 'securepassword');"

echo "Base de datos creada y usuario 'admin' insertado."
