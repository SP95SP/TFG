#!/bin/bash
# Lanza sshd en background
/usr/sbin/sshd -D &

# Ejecuta el watcher en primer plano, así cualquier kill 1 apaga todo el contenedor
exec /watcher.sh

