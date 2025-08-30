FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    openssh-server \
    zip unzip \
    openssl \
    python3 \
    vim \
    # Herramientas opcionales:
    hashcat john

# Crear usuarios
RUN for user in manolo maria carlos paco; do \
    useradd -m -s /bin/bash $user && echo "$user:$user" | chpasswd; \
  done

RUN echo "AllowUsers manolo maria carlos paco" >> /etc/ssh/sshd_config

# Copiar los zips, pass y señuelo a cada Desktop
COPY assets/carlos/ /home/carlos/Desktop/
COPY assets/maria/  /home/maria/Desktop/
COPY assets/paco/   /home/paco/Desktop/
COPY assets/manolo/ /home/manolo/Desktop/

RUN for user in manolo maria carlos paco; do \
    chown -R $user:$user /home/$user/Desktop; \
done

RUN mkdir /var/run/sshd

EXPOSE 22
CMD ["/usr/sbin/sshd", "-D"]

