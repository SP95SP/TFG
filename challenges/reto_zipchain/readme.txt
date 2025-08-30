reto_zipchain/
├── assets/
│   ├── carlos/
│   │   ├── reto1.zip        # Zip principal anidado (nivel 1)
│   │   ├── pass1.txt        # Pista y contraseña cifrada para reto1.zip
│   │   ├── flag.txt         # Señuelo
│   ├── maria/
│   │   ├── reto1.zip        # Zip señuelo (vacío)
│   │   ├── pass1.txt        # Contraseña cifrada
│   │   ├── flag.txt         # Señuelo
│   ├── manolo/
│   │   ├── reto1.zip        # Zip señuelo (vacío)
│   │   ├── pass1.txt
│   │   ├── flag.txt
│   ├── paco/
│   │   ├── reto1.zip        # Zip señuelo (vacío)
│   │   ├── pass1.txt
│   │   ├── flag.txt
├── Dockerfile
├── docker-compose.yml
├── prepare.bash
├── start.bash
├── stop.bash
├── README.md



Estructura interna del zip principal de Carlos

carlos/reto1.zip
├── reto2.zip
├── pass2.txt
carlos/reto2.zip
├── reto3.zip
├── pass3.txt
carlos/reto3.zip
├── reto4.zip
├── pass4.txt
carlos/reto4.zip
├── reto5.zip
├── pass5.txt
carlos/reto5.zip
├── flag.txt  # Aquí está la flag cifrada (esquema de números primos, tipo RSA vulnerable)


UNICA FLAG REAL: flag{svetla2025FLAG}

Para el archivo final de la flag mencionar en la descripción del reto que es otro reto de cifrado RSA con números primos vulnerables y que tienen python3 instalado en la máquina virtual


ACCESO AL RETO 
Igual que retos SSH de metadatos e integridad


GENERACÓN AUTOMÁTICA DEL ENTORNO DEL JUEGO (CARPETAS ZIP)

Ejecutar script: python3 generar_zipchain.py

El script generar_zipchain.py automatiza la creación de la estructura de archivos y zips anidados necesarios para un reto CTF orientado a análisis forense y criptografía básica, con varios niveles de cifrado de contraseñas y una flag final cifrada con un esquema RSA vulnerable.

Genera todos los directorios y archivos necesarios en la carpeta assets/ para ser usados directamente en un entorno Docker/CTFd, incluyendo los retos principales y los señuelos de los demás usuarios.


Estructura automatizada de carpetas y archivos en assets/ para cuatro usuarios (carlos, maria, manolo, paco).

Para el usuario objetivo (carlos):

Una cadena de 5 archivos zip anidados, cada uno protegido por una contraseña random diferente.

En cada nivel, la contraseña del siguiente zip está cifrada con un algoritmo diferente y acompañada de una pista codificada.

El archivo final es una flag cifrada con RSA vulnerable (factible de resolver con herramientas de CTF).

Para los otros usuarios:

Un zip señuelo protegido por una contraseña random cifrada, pero que contiene solo un texto “no hay nada aquí”.

Un archivo flag señuelo, igual que en carlos.

Todas las pistas y contraseñas están cifradas con los métodos seleccionados y presentadas con pistas codificadas por posición en el abecedario.

Automatización completa: genera y elimina archivos temporales, anida los zips en el orden correcto, y documenta la información necesaria para la resolución. 

Cuando acaba de ejecutarse el archivo py imprime por pantalla las contrasenas generadas en texto plano para poder hace testeo (CADA VEZ QUE SE EJECUTE GENERA DIFERENTES). 

APUNTAR LAS CONTRASEÑAS DE LA ÚLTIMA ITERACIÓN USADA DEL CÓDIGO GENERADOR DE LOS ZIP AQUÍ:
====== CONTRASEÑAS DE TEST ======

[Carlos]
  Nivel 1: 3AoUdbrcjm1h
  Nivel 2: JQhTNbjaggXB
  Nivel 3: uVCxpu0SgKZE
  Nivel 4: TcSmB2SLgHwg
  Nivel 5: qSjAO8Us3jXl

[Maria]
  Zip señuelo nivel 1: kw9quPXblGZc

[Manolo]
  Zip señuelo nivel 1: HoTtjIA2vHCR

[Paco]
  Zip señuelo nivel 1: 9NPc0QvjdISF
====== FIN ======



EN CARLOS:

Nivel 1
Archivo: reto1.zip

Contenido:

reto2.zip (nivel 2)

pass1.txt (pista para abrir este zip)

Contraseña:

Generada aleatoriamente (no es palabra común)

Cifrada con: MD5

Pista:

El hash MD5 de la contraseña y la palabra "MD5" codificada por posición en el abecedario (“13 4 5”).

Para avanzar:

Averigua el algoritmo usando la pista y crackea la contraseña MD5 para abrir el zip.

Nivel 2
Archivo: reto2.zip (dentro de reto1.zip)

Contenido:

reto3.zip (nivel 3)

pass2.txt

Contraseña:

Generada aleatoriamente

Cifrada con: SHA1

Pista:

El hash SHA1 de la contraseña y la palabra "SHA1" codificada (“19 8 1 1”).

Para avanzar:

Igual que antes, pero crackea un SHA1.

Nivel 3
Archivo: reto3.zip

Contenido:

reto4.zip

pass3.txt

Contraseña:

Generada aleatoriamente

Cifrada con: Base64

Pista:

La contraseña codificada en base64 y “BASE64” en números (“2 1 19 5 6 4”).

Para avanzar:

Decodifica base64.

Nivel 4
Archivo: reto4.zip

Contenido:

reto5.zip

pass4.txt

Contraseña:

Generada aleatoriamente

Cifrada con: ROT13

Pista:

Contraseña en rot13 y “ROT13” como pista (“18 15 20 13”).

Para avanzar:

Decodifica rot13 para obtener la contraseña.

Nivel 5
Archivo: reto5.zip

Contenido:

flag.txt (flag real cifrada)

pass5.txt

Contraseña:

Generada aleatoriamente

Cifrada con:

XOR key 42 (cada byte xor 42, resultado en hex).

En pass5.txt, la clave "Key: 42" está indicada y “XOR” codificado (“24 15 18”).

Para avanzar:

Decodifica el texto hex con XOR 42.

Flag final (flag.txt dentro de reto5.zip)
La flag es flag{CTF_RSA_ZIPMASTER_2024}

Cifrada con:

RSA vulnerable (dos primos cercanos p y q, e=65537)

El archivo contiene:
n: <modulo>
e: 65537
c: <cifrado en decimal>

Pista codificada:

“RSA” en números (“18 19 1”) y la frase “números primos p y q cercanos, público n, e, y el texto cifrado en decimal” (en pass5.txt).


Factorizar n, descifrar RSA con los datos y obtener la flag real.


IMPORTANTE: Si ejecutas el script varias veces, cada vez generará nuevas contraseñas y hashes diferentes.

