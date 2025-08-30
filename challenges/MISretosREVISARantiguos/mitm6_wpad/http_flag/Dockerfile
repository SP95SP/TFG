FROM httpd:2.4

RUN apt-get update && \
    apt-get install -y apache2-utils

COPY flag.html /usr/local/apache2/htdocs/flag.html

# Crear archivo .htpasswd con usuario: admin / password
RUN htpasswd -cb /usr/local/apache2/.htpasswd admin password

# Agregar directiva de autenticación en un archivo separado
RUN echo '\
<Location /flag.html>\n\
  AuthType Basic\n\
  AuthName "Restricted"\n\
  AuthUserFile /usr/local/apache2/.htpasswd\n\
  Require valid-user\n\
</Location>' >> /usr/local/apache2/conf/httpd.conf
