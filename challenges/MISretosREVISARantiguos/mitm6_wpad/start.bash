#!/bin/bash

mydir="$(dirname "$(readlink -f "$0")")"
cd "$mydir"

echo "[*] Iniciando entorno mitm6 + WPAD"
docker compose up -d
