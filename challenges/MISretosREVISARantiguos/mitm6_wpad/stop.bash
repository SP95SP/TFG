#!/bin/bash

mydir="$(dirname "$(readlink -f "$0")")"
cd "$mydir"

echo "[*] Deteniendo entorno mitm6 + WPAD"
docker compose down
