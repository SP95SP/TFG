#!/bin/bash

mydir="$(dirname "$(readlink -f "$0")")"
cd "$mydir"

# Variables por defecto
defport=58080
defpub="flag"
defpriv="seeded"

PORT=${1:-$defport}
PUBLICINIT=${2:-$defpub}
PRIVATEINIT=${3:-$defpriv}

# Crear archivo .env con variables para Docker Compose
echo "PORT=$PORT" > .env

# Generar flag reproducible (misma para todos los usuarios si no se cambia semilla manualmente)
SEED=$(echo -n "$PRIVATEINIT" | md5sum | cut -d ' ' -f1)
FLAG_NUM=$(echo $SEED | cut -c1-8)
echo "$PUBLICINIT{$FLAG_NUM}" > _flag.txt

# Crear carpeta de flag para contenedor web
mkdir -p http_flag
cp _flag.txt http_flag/flag.html

# Crear carpeta de WPAD y archivo básico
mkdir -p atacante
cat > atacante/wpad.dat <<EOF
function FindProxyForURL(url, host) {
    return "PROXY attacker:8080";
}
EOF

# Crear estructura vacía
mkdir -p victima_win

echo "[*] Flag generada: $PUBLICINIT{$FLAG_NUM}"
echo "[*] Entorno listo para ejecutar con start.bash"
