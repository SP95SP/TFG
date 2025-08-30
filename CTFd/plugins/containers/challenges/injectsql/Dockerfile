FROM php:8.0-apache

RUN apt-get update && \
    apt-get install -y mariadb-server mariadb-client && \
    docker-php-ext-install mysqli pdo pdo_mysql

COPY app/ /var/www/html/
COPY _flag.txt /flag.txt
COPY db-init.sql /db-init.sql
COPY prepare.bash /prepare.bash

RUN chmod +x /prepare.bash

EXPOSE 80

CMD ["/prepare.bash"]

