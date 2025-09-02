reto_integridad_simple (Escalada de privilegios en Linux multiusuario con alarma)

Árbol de archivos
reto_integridad_simple/
├── prepare.bash        # Prepara el entorno, genera .env, _instance y flag.txt
├── start.bash          # Levanta el contenedor con docker-compose
├── stop.bash           # Detiene y elimina el contenedor
├── Dockerfile          # Construye la máquina Linux vulnerable
├── docker-compose.yml  # Define el servicio y expone el puerto SSH
├── entrypoint.sh       # Script de arranque que lanza SSH y watcher
├── watcher.sh          # Monitoriza accesos y genera alarma en los logs
├── add_log_line.sh     # Añade entradas en /var/log/access.log
├── flag.txt            # Flag del reto (se copia a /root/flag.txt)
├── users.hashes        # Hashes de los usuarios para crackear
└── _instance           # Nombre de instancia generado automáticamente

Descripción del reto
Este reto simula una máquina Linux multiusuario. El jugador accede como manolo mediante SSH y debe escalar privilegios hasta convertirse en admin. El objetivo es crackear la contraseña de admin y leer /root/flag.txt.  

Diferencia respecto a la versión avanzada: aquí watcher.sh solo registra y muestra una alarma en los logs si se detecta acceso a admin, pero no apaga el contenedor.

Flujo de funcionamiento
- prepare.bash genera los archivos necesarios (.env, _instance, flag.txt).
- start.bash levanta el contenedor en segundo plano.
- docker-compose.yml expone el puerto SSH (por defecto 52122).
- entrypoint.sh arranca el servidor SSH y lanza watcher.sh.
- users.hashes se copia en el home de manolo (/home/manolo/secretos/hashes.txt).
- watcher.sh escribe una alarma en los logs cuando detecta uso de admin.

Explotación de la vulnerabilidad
1. Conexión inicial por SSH:
   ssh manolo@localhost -p 52122

2. Buscar hashes:
   cd /home/manolo/secretos
   cat hashes.txt
   Copiar el hash correspondiente al usuario admin.

3. Crackeo del hash:
   En la máquina atacante usar herramientas de diccionario:
   john --wordlist=/usr/share/wordlists/rockyou.txt hashes.txt
   o
   hashcat -m 1800 hashes.txt /usr/share/wordlists/rockyou.txt
   El crackeo revela la contraseña de admin: password123.

4. Escalada a admin:
   su - admin
   Introducir la contraseña crackeada (password123).
   El guion en su - es necesario para cargar el entorno y acceder al home de admin.

5. Lectura de la flag:
   sudo cat /root/flag.txt
   La flag se encuentra únicamente accesible para admin.

En esta versión, aunque watcher.sh detecte el acceso y registre la alarma en /var/log/access.log, el contenedor sigue activo y permite continuar hasta obtener la flag.

Explotación detallada: crackear la contraseña de admin y leer la flag

Conéctate por SSH como manolo usando el puerto del .env (por defecto 52122).
ssh manolo@localhost -p 52122

Localiza y visualiza los hashes.
cd /home/manolo/secretos
ls -l
cat hashes.txt

Copia el fichero de hashes a tu máquina atacante.
scp -P 52122 manolo@localhost:/home/manolo/secretos/hashes.txt ./hashes.txt

Identifica el tipo de hash para elegir el modo correcto de cracking.
Observa el prefijo del hash de admin:

$6$ → sha512crypt (hashcat -m 1800, John autodetecta)

$5$ → sha256crypt (hashcat -m 7400)

$1$ → md5crypt (hashcat -m 500)

$2y$/$2a$ → bcrypt (hashcat -m 3200)
Si tienes la herramienta hashid puedes comprobarlo:
hashid -m hashes.txt

Crackea con John the Ripper (recomendado si el fichero está en formato estilo shadow con “usuario:hash:…”).
john --wordlist=/usr/share/wordlists/rockyou.txt --format=crypt hashes.txt
Mira la contraseña encontrada:
john --show hashes.txt
Deberías ver admin:password123

Alternativa con Hashcat (si el fichero trae “usuario:hash”, usa --username o extrae solo el hash).
Opción A (con --username):
hashcat -a 0 -m 1800 hashes.txt /usr/share/wordlists/rockyou.txt --username
Opción B (extrayendo solo el campo hash si está separado por “:”):
cut -d: -f2 hashes.txt > admin.hash
hashcat -a 0 -m 1800 admin.hash /usr/share/wordlists/rockyou.txt
Muestra la contraseña cuando finalice:
hashcat -m 1800 admin.hash --show

Valida y escala a admin dentro del contenedor.
ssh manolo@localhost -p 52122
su - admin
Introduce la contraseña crackeada (por ejemplo, password123)

Lee la flag.
sudo cat /root/flag.txt

Notas prácticas

Si no tienes wordlists, instala rockyou.txt (en Debian/Ubuntu suele estar en /usr/share/wordlists/rockyou.txt o en el paquete wordlists).

Si John no detecta el formato, puedes forzar: --format=sha512crypt, --format=md5crypt, etc.

En Hashcat ajusta -m según el prefijo del hash: 1800 (sha512crypt), 7400 (sha256crypt), 500 (md5crypt), 3200 (bcrypt).

Instrucciones de acceso (standalone)
1. ./prepare.bash
2. ./start.bash
3. ssh manolo@localhost -p 52122
4. Crackear el hash de admin desde /home/manolo/secretos/hashes.txt
5. Escalar a admin con su - admin
6. sudo cat /root/flag.txt
7. ./stop.bash

Tecnologías utilizadas
- Docker y Docker Compose
- Debian Linux
- Servidor SSH
- Bash scripting
- users.hashes con contraseñas almacenadas como hashes
- watcher.sh como alarma de detección

Puerto de acceso
El puerto SSH expuesto por defecto es 52122:
ssh manolo@localhost -p 52122

Herramientas recomendadas para explotación
- John the Ripper (john)
- Hashcat
- Wordlists comunes como rockyou.txt

Objetivos de aprendizaje
- Practicar crackeo de hashes para escalada de privilegios en Linux.
- Usar herramientas como John the Ripper o hashcat con diccionarios.
- Comprender el funcionamiento de un sistema de detección por logs (aunque aquí solo registre).
- Obtener la flag al convertirse en admin.

