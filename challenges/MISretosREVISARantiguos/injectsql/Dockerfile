FROM php:8.0-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli

# Copiar archivos al contenedor
COPY app/ /var/www/html/
COPY _flag.txt /flag.txt

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
