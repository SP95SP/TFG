#!/bin/bash
LOGFILE="/var/log/access.log"
INTERVAL=10

while true; do
    sleep $INTERVAL
    if grep -q "admin" "$LOGFILE"; then
        echo "⚠️  Alarma: Se ha detectado acceso o cambio a admin. Reiniciando entorno..." | tee -a "$LOGFILE"
        wall "ALERTA: Uso no autorizado de admin detectado. El entorno se reiniciará."

    fi
done
