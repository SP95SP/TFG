ia_pickle (Fase 2: Acceso lateral – robo de modelos ajenos)

Árbol de archivos
ia_pickle/
├── app.py
├── crear_modelos_con_flag.py
├── docker-compose.yml
├── Dockerfile
├── prepare.bash
├── requirements.txt
├── start.bash
├── stop.bash
├── readme.txt
├── readmeAlumno.txt
├── templates/
│   └── form.html
├── models/
│   ├── admin_model.pkl
│   ├── ana_model.pkl
│   ├── carla_model.pkl
│   ├── juan_model.pkl
│   ├── pepe_model.pkl
│   └── model.pkl
└── crack/
    ├── exec_whoami.pkl
    ├── gen_excec_command.py
    ├── gen_list_models.py
    ├── gen_read_flag1.py
    ├── gen_read_flag2_model.py
    ├── list_models.pkl
    ├── not_evil.pkl
    ├── read_flag1.pkl
    └── read_flag2_from_model.pkl

Además se generan:
- _flag.txt: flag de la fase 1 (no relevante aquí)
- _flag2.txt: flag de la fase 2 (la que se debe robar)
- .env: puerto
- _instance: nombre de la instancia

Descripción del reto
Esta es la Fase 2. El sistema contiene múltiples modelos en la carpeta models que simulan pertenecer a otros usuarios. El atacante debe moverse lateralmente dentro del contenedor, listar esos modelos y cargar el modelo ajeno admin_model.pkl. Dicho modelo contiene serializada la Flag2, que también se encuentra en _flag2.txt.

Objetivo
Obtener la Flag2 cargando y leyendo el modelo ajeno admin_model.pkl con pickle.load, demostrando movimiento lateral dentro del mismo contenedor.

Explotación 

Usando cargas preparadas en crack/
El flujo de la aplicación permite subir un archivo .pkl y luego predecirlo con pickle.load. Esto puede usarse para listar models y extraer la Flag2.
Subir crack/list_models.pkl en /upload y luego visitar /predict para ver los modelos.
Subir crack/read_flag2_from_model.pkl en /upload y luego visitar /predict para ver la Flag2.

prepare.bash
Genera .env con el puerto, _instance con el nombre de la instancia, _flag.txt y _flag2.txt con las flags. Crea carpetas y llama a crear_modelos_con_flag.py que genera modelos de ejemplo y admin_model.pkl con la Flag2 serializada.

start.bash
Levanta el contenedor con docker compose en segundo plano.

stop.bash
Detiene y elimina el contenedor de la instancia.

docker-compose.yml
Define el servicio ia_pickle, construye desde el Dockerfile y mapea ${PORT}:5000. Monta el directorio en /app.

Dockerfile
Basado en python:3.10-slim. Copia el proyecto, instala dependencias de requirements.txt y ejecuta app.py con Flask en el puerto 5000.

requirements.txt
Incluye Flask y dependencias necesarias.

app.py
Aplicación Flask que permite subir un archivo .pkl a /upload y luego cargarlo en /predict con pickle.load. Esta deserialización es insegura y permite ejecutar código o acceder a ficheros locales como modelos ajenos.

templates/form.html
Formulario para subir un archivo .pkl y ejecutar la predicción.

crack/
Payloads preparados para demostrar la explotación. Incluyen list_models.pkl para listar modelos y read_flag2_from_model.pkl para leer admin_model.pkl y revelar la Flag2.

Workflow
1. Ejecutar prepare.bash para preparar entorno, flags y modelos.
2. Ejecutar start.bash para levantar el contenedor.
3. Acceder a http://127.0.0.1:58080 y usar el formulario:
   - Subir list_models.pkl y consultar /predict para ver los modelos en /models.
   - Subir read_flag2_from_model.pkl y consultar /predict para extraer la Flag2.
4. Ejecutar stop.bash para detener el contenedor.

Tecnologías utilizadas
Docker y Docker Compose, Python 3.10, Flask, pickle para deserialización, bash scripts para la gestión del entorno.

Objetivos de aprendizaje
Comprender la explotación de deserialización insegura en pickle. Practicar movimiento lateral en un mismo contenedor. Aprender a extraer información sensible (Flag2) desde recursos ajenos en la carpeta models.

