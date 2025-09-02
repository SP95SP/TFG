reto_zipchain (Cadena de zips con cifrados sucesivos y flag RSA vulnerable)

Árbol de archivos
reto_zipchain/
├── assets/
│   ├── carlos/
│   │   ├── reto1.zip      # Cadena real de 5 niveles
│   │   ├── pass1.txt      # Pista cifrada para el primer nivel
│   │   ├── flag.txt       # Señuelo
│   ├── maria/
│   │   ├── reto1.zip      # Zip señuelo (vacío)
│   │   ├── pass1.txt
│   │   ├── flag.txt
│   ├── manolo/
│   │   ├── reto1.zip      # Zip señuelo (vacío)
│   │   ├── pass1.txt
│   │   ├── flag.txt
│   ├── paco/
│   │   ├── reto1.zip      # Zip señuelo (vacío)
│   │   ├── pass1.txt
│   │   ├── flag.txt
├── generar_zipchain.py    # Script que crea automáticamente la cadena de zips y pistas
├── Dockerfile             # Imagen base Debian + utilidades
├── docker-compose.yml     # Define servicio SSH y mapea puerto
├── prepare.bash           # Prepara entorno, .env, _instance y flag de test
├── start.bash             # Levanta el contenedor
├── stop.bash              # Detiene el contenedor
├── readme.txt
└── readmeAlumno.txt

Descripción del reto
Este reto combina análisis forense de archivos comprimidos con un paso final de criptografía básica (RSA vulnerable).  
En la carpeta de Carlos hay una cadena de 5 zips anidados, cada uno protegido por una contraseña distinta. Cada nivel incluye un archivo passX.txt que contiene la contraseña del siguiente nivel, pero cifrada con un algoritmo diferente y acompañada de una pista numérica.  
Los demás usuarios (maria, manolo, paco) solo tienen un zip señuelo con un flag falso.  
La única flag real se encuentra en el quinto nivel de Carlos, dentro de reto5.zip, cifrada con un esquema RSA de primos muy pequeños y fáciles de factorizar.  

Flag real:  
flag{svetla2025FLAG}

Acceso al reto
- Igual que los retos anteriores basados en SSH.
1. Ejecutar ./prepare.bash
2. Ejecutar ./start.bash
3. Consultar puerto SSH en el archivo .env (por defecto 52245)
4. Conectar al contenedor con cualquier usuario:
   ssh manolo@localhost -p 52245
   (o carlos/maria/paco, contraseña = nombre de usuario)
5. Navegar a la carpeta del usuario objetivo (assets/carlos).
6. Resolver la cadena de zips.
7. ./stop.bash para detener el contenedor.

Cadena de niveles (usuario carlos)

Nivel 1 (reto1.zip)
- Contraseña cifrada con MD5.
- pass1.txt contiene el hash MD5 y la pista “13 4 5” (MD5 en números).
- Resolver crackeando el hash con John/Hashcat y wordlist.

Nivel 2 (reto2.zip)
- Contraseña cifrada con SHA1.
- pass2.txt contiene el hash SHA1 y la pista “19 8 1 1”.
- Resolver crackeando SHA1.

Nivel 3 (reto3.zip)
- Contraseña codificada en Base64.
- pass3.txt contiene la cadena y la pista “2 1 19 5 6 4” (BASE64).
- Resolver decodificando base64.

Nivel 4 (reto4.zip)
- Contraseña cifrada con ROT13.
- pass4.txt contiene la contraseña en rot13 y la pista “18 15 20 13”.
- Resolver decodificando rot13.

Nivel 5 (reto5.zip)
- Contraseña cifrada con XOR 42 (cada byte XOR 42, en hex).
- pass5.txt contiene la clave y la pista “24 15 18” (XOR).
- Resolver decodificando hex y aplicando XOR con 42.

Flag final (flag.txt dentro de reto5.zip)
- Contiene valores de un RSA vulnerable:
  n = p * q con primos cercanos
  e = 65537
  c = mensaje cifrado
- pass5.txt añade la pista “RSA” en números (“18 19 1”) y explica que p y q son cercanos.
- Factorizar n (ejemplo: con RsaCtfTool o factordb).
- Calcular d, descifrar c y obtener flag{svetla2025FLAG}.

Herramientas recomendadas
- john the ripper / hashcat para crackear MD5 y SHA1
- base64 para decodificar nivel 3
- tr/rot13 o CyberChef para decodificar nivel 4
- Python3 (instalado en la máquina) para aplicar XOR y resolver RSA
- RsaCtfTool, factordb u otros scripts de factorización

Workflow
1. Preparar y arrancar el contenedor.
2. Acceder a la carpeta assets/carlos y comenzar con reto1.zip.
3. Usar pass1.txt para deducir el algoritmo y crackear la contraseña.
4. Extraer reto2.zip y repetir hasta reto5.zip.
5. Al abrir reto5.zip, descifrar el esquema RSA con Python/factorización.
6. Recuperar la flag real flag{svetla2025FLAG}.

Objetivos de aprendizaje
- Manejar múltiples formatos de cifrado/codificación (MD5, SHA1, Base64, ROT13, XOR).
- Comprender pistas numéricas como guías de algoritmos.
- Practicar crackeo de hashes con herramientas de CTF.
- Aprender el flujo básico de un RSA vulnerable y cómo explotarlo.
- Integrar técnicas forenses, de reversing de archivos y de criptografía en un reto encadenado.

