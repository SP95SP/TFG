reto_metadatos/
├── assets/
│   ├── im1.jpg
│   ├── im2.jpg
│   ├── ...
│   ├── im10.jpg
│   ├── tex1.txt
│   ├── tex2.txt
│   ├── ...
│   └── tex10.txt
├── Dockerfile
├── docker-compose.yml
├── prepare.bash
├── start.bash
├── stop.bash

DESCRIPCIÓN RETO:
Todos los desktops de los 4 usuarios (manolo maria carlos paco) tienen los mismos archivos. 10 imágenes y 10 archivos de texto con las definiciones de ataques a vulnerabilidades más comunes, solo un archivo tiene una definición más extensa, el archivo tex3.txt que habla sobre los metadatos de las imágenes, el número que puede dar una pista de que la imagen 3 de uno de los usuarios contiene la flag en los metadatos, esta flag solo se encuentra en el usuario de maría. El dockerfile modifica la imagen 3 del usuario María 

RECURSOS:
- Contrasenas de los usuarios mismas que los nombres de usuario
- flag fija embebida en el campo de comentarios de la imagen 3 del usuario maría en sus escritorio: flag{svetlaPLAMENOVA195tfd}
- Herramienta instalada en el SO para capturar los metadatos: exiftool
- Otras herramientas disponibles: vim, nano, SSH, comandos linux


RETO STANDALONE: 
1. Ejecutar ./prepare.bash para preparar el entorno.

2. Ejecutar ./start.bash para levantar el contenedor.

3. Buscar el puerto SSH en el archivo .env.

4. Conectar por SSH usando cualquier usuario:

ssh "usuario"@localhost -p "puerto"

5. Cambiar de usuario con su - "nuevoUsuarios"

6. Detener entorno con ./stop.bash


RESOLVER RETO
Acceder por ssh
Navegar al escritorio y comprobar el contenido de los archivos, leer los textos de los archivos.
Darse cuenta que el texto 3 es diferente.
Flag en texto plano como senuelo.
Asociar que la flag está en los metadatos de la imagen 3 de uno de los usuarios.
Revisar cada una de ellas mediante: 
exiftool im3.jpg





