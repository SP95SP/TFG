#!/bin/bash

echo "[*] Resolviendo WPAD..."
host wpad.testsegment.local || true

sleep 2

echo "[*] Accediendo a http://http_flag/ desde proxy"
curl -x http://attacker:8080 http://http_flag/flag.html || true

sleep infinity
