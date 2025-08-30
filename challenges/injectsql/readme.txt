Descripción general
Este reto consiste en explotar una vulnerabilidad de inyección SQL en una aplicación web simulada de una empresa farmacéutica. El entorno funciona en un único contenedor Docker, integrando Apache, PHP y MariaDB. El objetivo es conseguir la flag manipulando la consulta SQL del login.

Árbol de archivos
reto-sqlinjection/
│
├── app/
│   └── index.php
├── _flag.txt
├── db-init.sql
├── prepare.bash
├── start.bash
├── stop.bash
└── docker-compose.yml


Explicación de archivos

app/index.php: Código PHP de la web vulnerable al ataque. Realiza la conexión y consulta a la base de datos.

_flag.txt: Archivo que contiene la flag que debe obtener el participante.

db-init.sql: Script SQL que crea la base de datos challenge, la tabla users, el usuario admin y el usuario de base de datos ctfuser.

prepare.bash: Script que inicializa la base de datos y lanza Apache dentro del contenedor.

start.bash: Lanza el contenedor en segundo plano.

stop.bash: Detiene y elimina el contenedor.

docker-compose.yml: Define el contenedor único (web) que integra Apache, PHP y MariaDB.


Tipo de base de datos
Se utiliza MariaDB (totalmente compatible con MySQL), versión instalada por el sistema de paquetes de Debian dentro del contenedor.

Interacción entre archivos y servicios

docker-compose.yml crea el contenedor y monta todos los archivos requeridos.

Al arrancar el contenedor, prepare.bash inicializa la base de datos mediante db-init.sql y luego lanza Apache.

db-init.sql crea la base de datos, la tabla users, el usuario de ejemplo y el usuario de acceso desde la web.

index.php recibe los datos del formulario de login, realiza la consulta SQL vulnerable y muestra el resultado y la flag en pantalla si la autenticación tiene éxito.

_flag.txt almacena la flag, que se muestra únicamente al explotar correctamente la vulnerabilidad.




Resolución del reto

Accede en el navegador a la URL del contenedor (por ejemplo, http://localhost:5050).

En el formulario de inicio de sesión, en el campo "Usuario", introduce una cadena de inyección SQL, como:
' OR 1=1 --
(incluye el espacio inicial y el doble guion al final, sin comillas exteriores)

Deja el campo "Contraseña" vacío y envía el formulario.

Si la inyección es correcta, la aplicación mostrará la flag en pantalla.

El ataque funciona porque los datos del formulario se insertan directamente en la consulta SQL sin validación ni escape.


El usuario y contraseña reales de la base son admin / securepassword, pero no es necesario conocerlos para resolver el reto.

La flag es estática, está en _flag.txt y solo se muestra si el login SQL tiene éxito.

