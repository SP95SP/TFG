reto_metadatos (Flag oculta en metadatos de imágenes)

Árbol de archivos
reto_metadatos/
├── assets/
│   ├── im1.jpg
│   ├── im2.jpg
│   ├── im3.jpg
│   ├── im4.jpg
│   ├── im5.jpg
│   ├── im6.jpg
│   ├── im7.jpg
│   ├── im8.jpg
│   ├── im9.jpg
│   ├── im10.jpg
│   ├── tex1.txt
│   ├── tex2.txt
│   ├── tex3.txt   # pista: habla de metadatos
│   ├── tex4.txt
│   ├── tex5.txt
│   ├── tex6.txt
│   ├── tex7.txt
│   ├── tex8.txt
│   ├── tex9.txt
│   └── tex10.txt
├── Dockerfile          # Construcción de la máquina Linux con usuarios y escritorios
├── docker-compose.yml  # Define el servicio y expone el puerto SSH
├── prepare.bash        # Prepara el entorno, genera .env, _instance y flag.txt
├── start.bash          # Levanta el contenedor
├── stop.bash           # Detiene y elimina el contenedor
├── flag.txt            # Flag fija embebida en los metadatos de la imagen
├── readme.txt
└── readmeAlumno.txt

Descripción del reto
El escenario simula los escritorios de cuatro usuarios (manolo, maria, carlos, paco).  
Todos tienen 10 imágenes y 10 ficheros de texto con definiciones de ataques comunes. Solo uno de los textos, tex3.txt, contiene una definición más extensa que menciona los metadatos de imágenes.  
Esto sirve como pista para revisar la imagen im3.jpg.  
La flag se encuentra incrustada como comentario en los metadatos EXIF de la imagen 3 del usuario maría.  

La flag es estática y tiene este valor:  
flag{svetlaPLAMENOVA195tfd}

Recursos disponibles en el entorno
- Usuarios: manolo, maria, carlos, paco (contraseña = nombre de usuario).
- Herramienta instalada: exiftool (para examinar metadatos).
- Otros binarios básicos de Linux (vim, nano, ssh, etc).

Reto standalone
1. Ejecutar ./prepare.bash para preparar el entorno.
2. Ejecutar ./start.bash para levantar el contenedor.
3. Consultar el puerto SSH en el archivo .env (por defecto 52222).
4. Conectar por SSH con cualquier usuario:
   ssh manolo@localhost -p 52222
   (o maria, carlos, paco según se prefiera).
5. Cambiar de usuario si es necesario con:
   su - maria
6. Detener el entorno con ./stop.bash

Cómo resolver el reto
1. Acceder por SSH con cualquier usuario.
2. Navegar a los escritorios y revisar los ficheros de texto:
   cat tex3.txt
   Se observará que este archivo contiene información distinta, habla de metadatos de imágenes.
3. Relacionar la pista con la imagen im3.jpg.
4. Probar cada usuario. Solo en el escritorio de maria está la flag embebida en im3.jpg.
5. Usar exiftool para revisar los metadatos de la imagen sospechosa:
   exiftool im3.jpg
6. Localizar en el campo de comentarios la flag:
   flag{svetlaPLAMENOVA195tfd}

Workflow
- Los textos guían al jugador a sospechar de los metadatos.
- El número 3 aparece tanto en el archivo de texto (tex3.txt) como en la imagen (im3.jpg).
- Solo la carpeta del usuario maría contiene la imagen 3 con la flag oculta.
- El uso de exiftool confirma la presencia de la flag.

Objetivos de aprendizaje
- Reconocer pistas escondidas en ficheros de texto.
- Relacionar información con archivos multimedia.
- Aprender a usar exiftool para analizar metadatos de imágenes.
- Extraer información sensible embebida en metadatos.

