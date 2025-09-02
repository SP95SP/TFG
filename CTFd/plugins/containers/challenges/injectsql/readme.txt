reto-sqlinjection (Inyección SQL en aplicación web PHP)

Árbol de archivos
reto-sqlinjection/
├── app/
│   └── index.php        # Código PHP vulnerable con formulario de login
├── _flag.txt            # Flag estática del reto
├── db-init.sql          # Script SQL que inicializa base de datos y usuarios
├── prepare.bash         # Script que prepara el entorno y lanza Apache
├── start.bash           # Levanta el contenedor en segundo plano
├── stop.bash            # Detiene y elimina el contenedor
├── docker-compose.yml   # Define el servicio único con Apache, PHP y MariaDB
└── Dockerfile           # Construcción de la imagen con Debian, Apache, PHP y MariaDB

Descripción general
Este reto consiste en explotar una vulnerabilidad de inyección SQL en una aplicación web simulada de una empresa farmacéutica. El entorno funciona en un único contenedor Docker que integra Apache, PHP y MariaDB. El objetivo es conseguir la flag manipulando la consulta SQL del login.

Escenario simulado
La aplicación muestra un formulario de inicio de sesión en index.php. El código PHP conecta a MariaDB y consulta la tabla users. Los datos del formulario se insertan directamente en la consulta SQL sin validación, lo que permite inyección SQL. Si la consulta devuelve éxito, se muestra la flag almacenada en _flag.txt.

Flujo de funcionamiento
- docker-compose.yml levanta un contenedor único que integra Apache, PHP y MariaDB.
- prepare.bash arranca el servicio de MariaDB, inicializa la base de datos challenge con db-init.sql y luego lanza Apache en foreground.
- db-init.sql crea la base de datos challenge, la tabla users, el usuario admin con contraseña securepassword y el usuario de acceso ctfuser.
- index.php recibe los datos del formulario de login, construye la consulta vulnerable y, si tiene éxito, muestra la flag.
- _flag.txt contiene la flag estática que se revela al explotar la inyección.

Resolución del reto
Acceder en el navegador a la URL del contenedor, por ejemplo:
http://localhost:5050

En el formulario de inicio de sesión, en el campo “Usuario”, introducir la inyección:
' OR 1=1 --
(incluyendo el espacio inicial y el doble guion al final, sin comillas exteriores)

Dejar el campo “Contraseña” vacío y enviar el formulario. Si la inyección es correcta, la aplicación mostrará la flag en pantalla.

El ataque funciona porque la consulta SQL se construye concatenando directamente los valores del formulario. No es necesario conocer las credenciales reales para resolver el reto, aunque los usuarios válidos de la base son admin / securepassword.

Workflow
1. Ejecutar bash prepare.bash para inicializar la base de datos y lanzar Apache.
2. Ejecutar bash start.bash para levantar el contenedor.
3. Acceder a http://localhost:5050 y usar el formulario de login.
4. Aplicar inyección SQL en el campo “Usuario” para saltarse la autenticación y mostrar la flag.
5. Ejecutar bash stop.bash para detener y eliminar el contenedor.

Tecnologías utilizadas
- Docker y Docker Compose
- Debian con Apache2
- PHP 7.x
- MariaDB (compatible con MySQL)
- Bash para scripts de preparación y control

Puerto de acceso
El servicio web está disponible en el puerto 5050 del host:
http://localhost:5050

Objetivos de aprendizaje
- Comprender la vulnerabilidad clásica de inyección SQL.
- Aprender a construir un payload sencillo para saltar autenticación (' OR 1=1 --).
- Observar cómo la falta de validación en consultas SQL permite acceder a información sensible.
- Extraer la flag como prueba de explotación exitosa.

