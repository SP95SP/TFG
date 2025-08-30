#!/bin/bash
# Inicia el watcher de logs y el SSHD
/watcher.sh &
/usr/sbin/sshd -D
