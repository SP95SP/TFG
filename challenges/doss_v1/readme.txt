doss_v1/
├── prepare.bash   # Configura variables, crea .env, _instance, _flag.txt y el contador (counter.txt).
├── start.bash        # Inicia el contenedor Docker con Docker Compose.
├── stop.bash        # Detiene el contenedor y reinicia el contador (counter.txt a 0).
├── docker-compose.yml    # Define el servicio web basado en PHP y mapea el puerto.
├── Dockerfile            # Instrucciones para construir la imagen Docker a partir de php:8.0-apache.
├── index.php             # Código principal formulario para introducir la edad, actualiza el contador |     ├ 				# al superar un umbral da un error 500 con la flag.
├── counter.txt            # Archivo donde se almacena el número de peticiones recibidas.
├── _flag.txt               # Archivo que contiene la flag generada durante la preparación del entorno.
└── advice/                 # Carpeta que contiene los archivos con consejos de salud.
    ├── advice1.txt      # Consejos para edades menores de 18.
    ├── advice2.txt      # Consejos para edades entre 18 y 34.
    ├── advice3.txt      # Consejos para edades entre 35 y 59.
    └── advice4.txt      # Consejos para edades de 60 en adelante.

Reto
El reto consiste en provocar un ataque DoS controlado sobre un endpoint PHP vulnerable. El endpoint muestra inicialmente un formulario para obtener consejos de salud según la edad. Sin embargo, se lleva un contador de peticiones. Al alcanzar un umbral (por ejemplo, 100 peticiones), el sistema responde con un error 500 en vez de la respuesta normal, mostrando la flag (contenido en _flag.txt).

Explotar la flag

- Simular un ataque de denegación de servicio (DoS) y alcanzar el umbral que active el error con la flag:
ab -n 100 -c 1 http://localhost:31530/

-n 100: Envía un total de 200 peticiones
-c 1: Mantiene 1 peticiones concurrentes. IMPORTANTE!!! Sino el contador no se incrementa correctamente.

-Otra forma de hacer mediante bulcle for y curl: 
for i in {1..150}; do curl -i http://127.0.0.1:50080/; echo; done

Cando se alcanza el humbral se devuelve un error 500 que contiene la flag como si fuera un mensaje de error.








Workflow
- El entorno se prepara con prepare.bash, que configura el puerto, la instancia, la flag y el contador.
- Con start.bash se levanta el contenedor Docker que ejecuta la aplicación en PHP.
- El usuario (o atacante) accede a la URL y envía peticiones mediante herramientas de carga (como Apache Benchmark o cURL).
- Cada petición incrementa el contador en counter.txt.
- Al alcanzar el umbral, la aplicación responde con un error HTTP 500 que incluye la flag en el mensaje de error.
- El reto se puede detener con stop.bash, que además reinicia el contador a 0 para poder repetir la prueba.


RELLENAR

