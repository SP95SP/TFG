reto_integridad/
├── prepare.bash
├── start.bash
├── stop.bash
├── Dockerfile
├── docker-compose.yml
├── entrypoint.sh
├── flag.txt          (contenido copiado a /root/flag.txt al arrancar)
├── users.hashes      (hashes de los usuarios, para copiar al home de manolo)
└── watcher.sh        (el script que comprueba los logs)


INSTRUCCIONES ACCESO RETO STANDALONE

1- Ejecutar el script de preparación:
./prepare.bash

2- Ejecutar el script para arrancar el reto:
./start.bash

3- Conectar por SSH al reto usando el puerto que está en el archivo .env
ssh manolo@localhost -p 52122

4- Buscar el archivo de hashes

5- Copia el hash de admin a tu máquina y crakéar con la herramienta de diccionario

6- Hacer su admin y poner la contraseña crackeada: password123.
su - admin
su - "ususario"  (el guión es necesario para cargar las variables de entorno de admin y accede a su home como si se hubiera echo login in el mismo)

7- Antes de que pasen 10 segundos, borrar cualquier referencia a admin en el archivo de logs (/var/log/access.log)

8- Si se es admin y no se es detectado, navegar al archivo de la flag.
cat /root/flag.txt

9- Parar el reto con
./stop.bash




DESCRIPCIÓN RETO
Simula una máquina Linux multiusuario. El objetivo es escalar privilegios desde un usuario básico, obtener la flag del usuario admin, y evitar que el sistema te detecte. Si se detecta acceso a admin, el entorno se reinicia y pierdes el progreso.





ESTRUCTURA ARCHIVOS

Dockerfile
Construye la imagen de la máquina vulnerable. Crea los usuarios, define los permisos y copia los scripts y archivos de configuración necesarios.

docker-compose.yml
Define el servicio y expone el puerto SSH (por defecto, 52122).

prepare.bash
Prepara el entorno: genera variables, instancia, flag y otros archivos necesarios.

start.bash
Lanza el contenedor Docker usando docker-compose.

stop.bash
Detiene y elimina el contenedor asociado.

flag.txt
Flag del reto. Se copia como /root/flag.txt (sólo admin puede leerla).

users.hashes
Hashes de los usuarios para escalar privilegios (almacenados en /home/manolo/secretos/hashes.txt).

entrypoint.sh
Script de inicio que arranca el servicio SSH y el watcher en segundo plano.

watcher.sh
Script que monitoriza el archivo de logs. Si detecta acceso a admin, muestra una alarma y apaga el contenedor.

add_log_line.sh
Añade la lógica de registro de cambios de usuario en el archivo /var/log/access.log.

_instance
Archivo generado automáticamente con el nombre de la instancia (no editar manualmente).


