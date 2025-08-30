#!/bin/bash
set -e

# Inicializa el datadir si no existe
if [ ! -d "/var/lib/mysql/mysql" ]; then
    mysqld --initialize-insecure --user=mysql
fi

# Arranca el server MySQL en background
mysqld_safe --datadir="/var/lib/mysql" &

# Espera a que MySQL esté disponible (máximo 30s)
for i in {1..30}; do
    if mysqladmin ping -uroot --silent; then
        break
    fi
    sleep 1
done

# Inicializa la base solo si no existe
if ! mysql -uroot -e 'USE challenge' 2>/dev/null; then
    mysql -uroot < /db-init.sql
fi

# Lanza apache en foreground
apache2-foreground

