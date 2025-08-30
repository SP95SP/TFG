prepare.bash
Función:
    • Genera la flag (almacenada en _flag.txt) que se mostrará cuando se explote la vulnerabilidad.
    • Crea el archivo .env con el puerto asignado.
    • Registra el nombre de la instancia en el fichero _instance.
    • Crea la base de datos SQLite (users.db) y la tabla "users" con un usuario predefinido:
        ◦ Usuario: admin
        ◦ Contraseña: securepassword
Uso: Ejecutar ./prepare.bash para inicializar el reto.
start.bash y stop.bash
start.bash:
    • Levanta el contenedor Docker mediante Docker Compose usando el nombre de la instancia definido en _instance.
stop.bash:
    • Detiene y remueve el contenedor Docker asociado al reto.
Uso:
    • Iniciar el reto: ./start.bash
    • Detener el reto: ./stop.bash
docker-compose.yml y Dockerfile
docker-compose.yml:
    • Define el servicio "webser" que se construye a partir del Dockerfile y mapea el puerto configurado en el archivo .env al puerto 80 del contenedor.
Dockerfile:
    • Usa la imagen base python:3.9-slim.
    • Establece el directorio de trabajo en /app.
    • Copia e instala las dependencias definidas en requirements.txt.
    • Copia el resto del código del proyecto.
    • Expone el puerto 80 y ejecuta app.py al iniciar el contenedor.
requirements.txt
Contenido: Se especifican las versiones necesarias para evitar incompatibilidades: Flask==2.0.3 Werkzeug==2.0.3
Se usa Flask 2.0.3 y Werkzeug 2.0.3 para asegurar la disponibilidad de la función url_quote, porque has sido eliminada en siguientes versiones de Werkzeug.
app.py
Función:
    • Implementa una aplicación web con Flask.
    • Muestra un formulario de login (usando la plantilla login.html).
    • Procesa la autenticación en la ruta /login:
        ◦ Si no se envían ambos campos, devuelve un error.
        ◦ Si se envían las credenciales correctas (admin / securepassword), muestra un mensaje de bienvenida.
        ◦ Si la consulta a la base de datos retorna algún resultado pero las credenciales no coinciden exactamente (por ejemplo, al explotar la vulnerabilidad), se lee y muestra la flag almacenada en _flag.txt.
Vulnerabilidad: La consulta SQL se construye de forma vulnerable, concatenando directamente los valores del formulario, lo que permite inyectar código SQL malicioso.
Código Vulnerable: query = "SELECT * FROM users WHERE username = '{}' AND password = '{}'".format(username, password)
templates/login.html
Función: Contiene el formulario HTML para que el usuario ingrese sus credenciales.
Versiones Usadas
    • Base del Contenedor: python:3.9-slim
    • Framework Web: Flask 2.0.3
    • Librería de Utilidades Web: Werkzeug 2.0.3
    • Base de Datos: SQLite (archivo users.db)
    • Contenerización: Docker y Docker Compose
Explotación de la Vulnerabilidad
Payload 1:
    • Usuario: ' OR 1=1; --
    • Contraseña: cualquier valor
      Esto hace que la consulta se transforme en algo similar a: SELECT * FROM users WHERE username = '' OR 1=1; --' AND password = '' El operador -- comenta el resto de la consulta, haciendo que la condición siempre se evalúe como verdadera.
Payload 2:
    • Usuario: admin' --
    • Contraseña: cualquier valor 
      Esto convierte la consulta en: SELECT * FROM users WHERE username = 'admin' --' AND password = '...' De esta manera, se ignora la condición de contraseña y se selecciona el usuario admin.
Al explotar la vulnerabilidad, la aplicación detecta que los datos no coinciden exactamente con el usuario y contraseña originales, por lo que en lugar de dar la bienvenida, lee el contenido de _flag.txt y lo muestra en la respuesta.


Requisitos previos:
    • Tener instalado Docker y Docker Compose.
    • Tener instalado sqlite3 en el sistema para ejecutar el script de preparación (prepare.bash).
Acceso al reto :
http://127.0.0.1:50080 Se mostrará el formulario de login.
