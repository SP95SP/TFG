FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    openssh-server \
    exiftool \
    sudo \
    vim

# Crear usuarios
RUN for user in manolo maria carlos paco; do \
    useradd -m -s /bin/bash $user && echo "$user:$user" | chpasswd; \
  done

RUN echo "AllowUsers manolo maria carlos paco" >> /etc/ssh/sshd_config

# Copiar archivos assets a Desktop de cada usuario
COPY assets/ /assets/

RUN for user in manolo maria carlos paco; do \
      mkdir -p /home/$user/Desktop; \
      cp /assets/im*.jpg /home/$user/Desktop/; \
      cp /assets/tex*.txt /home/$user/Desktop/; \
      echo "flag{test}" > /home/$user/flag.txt; \
      chown -R $user:$user /home/$user; \
   done

# Poner la flag en el metadato de la imagen 3 de maria
RUN exiftool -overwrite_original -Comment="flag{svetlaPLAMENOVA195tfd}" /home/maria/Desktop/im3.jpg && \
    chown maria:maria /home/maria/Desktop/im3.jpg

RUN mkdir /var/run/sshd

EXPOSE 22
CMD ["/usr/sbin/sshd", "-D"]

