FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    openssh-server \
    sudo \
    passwd \
    python3 \
    python3-pip \
    vim \
    && rm -rf /var/lib/apt/lists/*

# Usuarios
RUN useradd -m -s /bin/bash manolo && echo 'manolo:manolo' | chpasswd
RUN useradd -m -s /bin/bash maria && echo 'maria:maria' | chpasswd
RUN useradd -m -s /bin/bash carlos && echo 'carlos:carlos' | chpasswd
RUN useradd -m -s /bin/bash paco && echo 'paco:paco' | chpasswd
RUN useradd -m -s /bin/bash admin && echo 'admin:password123' | chpasswd

RUN echo "AllowUsers manolo maria carlos paco" >> /etc/ssh/sshd_config

# Copia archivos de reto
COPY flag.txt /tmp/flag.txt
COPY users.hashes /tmp/users.hashes
COPY watcher.sh /watcher.sh
COPY entrypoint.sh /entrypoint.sh
COPY add_log_line.sh /add_log_line.sh

# Flag en home de admin
RUN mv /tmp/flag.txt /home/admin/flag.txt && chmod 600 /home/admin/flag.txt && chown admin:admin /home/admin/flag.txt

# Prepara los hashes en el home de manolo
RUN mkdir -p /home/manolo/secretos && \
    cp /tmp/users.hashes /home/manolo/secretos/hashes.txt && \
    chown -R manolo:manolo /home/manolo/secretos

# Log de acceso
RUN touch /var/log/access.log && chmod 666 /var/log/access.log

# SSH service
RUN mkdir /var/run/sshd

# Añade línea de log a .bashrc de todos los usuarios
RUN for user in manolo maria carlos paco admin; do \
      bash /add_log_line.sh /home/$user/.bashrc; \
    done

RUN chmod +x /entrypoint.sh /watcher.sh /add_log_line.sh

ENTRYPOINT ["/entrypoint.sh"]

EXPOSE 22
