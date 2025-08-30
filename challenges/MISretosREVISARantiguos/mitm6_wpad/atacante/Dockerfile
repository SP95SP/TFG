FROM kalilinux/kali-rolling

# Instalamos herramientas necesarias
RUN apt-get update && \
    apt-get install -y git python3 python3-pip python3-venv responder mitm6 curl && \
    python3 -m venv /opt/venv && \
    /opt/venv/bin/pip install --upgrade pip && \
    /opt/venv/bin/pip install impacket

# Variables de entorno para usar el venv por defecto
ENV PATH="/opt/venv/bin:$PATH"

COPY entrypoint.sh /entrypoint.sh
COPY wpad.dat /var/www/html/wpad.dat

RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
