import os
import random
import string
import hashlib
import base64
import subprocess
import shutil
from Crypto.Util import number

USUARIOS = ['carlos', 'maria', 'manolo', 'paco']
NIVELES = 5

def letras_a_numeros(texto):
    return ' '.join(str(ord(c.upper()) - 64) for c in texto if c.isalpha())

def random_pwd(long=12):
    chars = string.ascii_letters + string.digits
    return ''.join(random.SystemRandom().choice(chars) for _ in range(long))

def hash_md5(txt):
    return hashlib.md5(txt.encode()).hexdigest()

def hash_sha1(txt):
    return hashlib.sha1(txt.encode()).hexdigest()

def base64_encode(txt):
    return base64.b64encode(txt.encode()).decode()

def rot13(txt):
    abecedario = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'
    desplazado = 'NOPQRSTUVWXYZABCDEFGHIJKLMnopqrstuvwxyzabcdefghijklm'
    return txt.translate(str.maketrans(abecedario, desplazado))

def xor_str_hex(txt, key):
    return ''.join('{:02x}'.format(ord(c) ^ key) for c in txt)

def gen_dir(path):
    if os.path.exists(path):
        shutil.rmtree(path)
    os.makedirs(path)

def gen_flag_rsa(flag, path):
    p = number.getPrime(32)
    q = p + random.randint(1, 10)
    n = p * q
    e = 65537
    m = int.from_bytes(flag.encode(), 'big')
    c = pow(m, e, n)
    with open(path, 'w') as f:
        f.write(f'n: {n}\n')
        f.write(f'e: {e}\n')
        f.write(f'c: {c}\n')
    return n, e, c

def pista_algoritmo(nombre_alg):
    return letras_a_numeros(nombre_alg)

def pista_rsa():
    return letras_a_numeros('RSA') + " (números primos p y q cercanos, público n, e, y el texto cifrado en decimal)"

def gen_zip(zipfile, files, pwd):
    cmd = ['zip', '-j', '-P', pwd, zipfile]
    for (name, localpath) in files:
        cmd += [localpath]
    subprocess.check_call(cmd, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)

def crea_carpeta_user(user, zipfile=True):
    diru = f'assets/{user}'
    gen_dir(diru)
    with open(f'{diru}/flag.txt', 'w') as f:
        f.write('flag{test}.\n')
    if zipfile:
        pwd1 = random_pwd()
        pista = pista_algoritmo('MD5')
        passfile = f'{diru}/pass1.txt'
        with open(passfile, 'w') as f:
            f.write(f'Contraseña cifrada (nivel 1) con MD5 ({pista}): {hash_md5(pwd1)}\n')
        tmpdummy = f'{diru}/flag.txt'
        with open(tmpdummy, 'w') as f:
            f.write('flag{jNSAn2042}.\n')
        gen_zip(f'{diru}/reto1.zip', [('flag.txt', tmpdummy)], pwd1)
        os.remove(tmpdummy)
        return pwd1
    return None

def crea_carpeta_carlos():
    diru = 'assets/carlos'
    gen_dir(diru)
    with open(f'{diru}/flag.txt', 'w') as f:
        f.write('flag{tjhsF532}.\n')
    contras = [random_pwd() for _ in range(NIVELES)]
    cifradores = [
        ('MD5', hash_md5, 'MD5'),
        ('SHA1', hash_sha1, 'SHA1'),
        ('BASE64', base64_encode, 'BASE64'),
        ('ROT13', rot13, 'ROT13'),
        ('XOR', lambda s: xor_str_hex(s, 42), 'XOR')
    ]
    flag_real = 'flag{svetla2025FLAG}'
    flagtxt = f'{diru}/flag.txt.final'
    n, e, c = gen_flag_rsa(flag_real, flagtxt)
    pass5cif = cifradores[4][1](contras[4])
    pista5 = pista_algoritmo(cifradores[4][2])
    with open(f'{diru}/pass5.txt', 'w') as f:
        f.write(f'Contraseña cifrada (nivel 5) con XOR ({pista5}): {pass5cif}\n')
        f.write(f'Key: 42\n')
    with open(flagtxt, 'a') as f:
        f.write('\n')
        f.write('Pista: ' + pista_rsa() + '\n')
    gen_zip(f'{diru}/reto5.zip', [('flag.txt', flagtxt), ('pass5.txt', f'{diru}/pass5.txt')], contras[4])
    os.remove(flagtxt)
    os.remove(f'{diru}/pass5.txt')
    pass4cif = cifradores[3][1](contras[3])
    pista4 = pista_algoritmo(cifradores[3][2])
    with open(f'{diru}/pass4.txt', 'w') as f:
        f.write(f'Contraseña cifrada (nivel 4) con ROT13 ({pista4}): {pass4cif}\n')
    gen_zip(f'{diru}/reto4.zip', [('reto5.zip', f'{diru}/reto5.zip'), ('pass4.txt', f'{diru}/pass4.txt')], contras[3])
    os.remove(f'{diru}/reto5.zip')
    os.remove(f'{diru}/pass4.txt')
    pass3cif = cifradores[2][1](contras[2])
    pista3 = pista_algoritmo(cifradores[2][2])
    with open(f'{diru}/pass3.txt', 'w') as f:
        f.write(f'Contraseña cifrada (nivel 3) con BASE64 ({pista3}): {pass3cif}\n')
    gen_zip(f'{diru}/reto3.zip', [('reto4.zip', f'{diru}/reto4.zip'), ('pass3.txt', f'{diru}/pass3.txt')], contras[2])
    os.remove(f'{diru}/reto4.zip')
    os.remove(f'{diru}/pass3.txt')
    pass2cif = cifradores[1][1](contras[1])
    pista2 = pista_algoritmo(cifradores[1][2])
    with open(f'{diru}/pass2.txt', 'w') as f:
        f.write(f'Contraseña cifrada (nivel 2) con SHA1 ({pista2}): {pass2cif}\n')
    gen_zip(f'{diru}/reto2.zip', [('reto3.zip', f'{diru}/reto3.zip'), ('pass2.txt', f'{diru}/pass2.txt')], contras[1])
    os.remove(f'{diru}/reto3.zip')
    os.remove(f'{diru}/pass2.txt')
    pass1cif = cifradores[0][1](contras[0])
    pista1 = pista_algoritmo(cifradores[0][2])
    with open(f'{diru}/pass1.txt', 'w') as f:
        f.write(f'Contraseña cifrada (nivel 1) con MD5 ({pista1}): {pass1cif}\n')
    gen_zip(f'{diru}/reto1.zip', [('reto2.zip', f'{diru}/reto2.zip'), ('pass1.txt', f'{diru}/pass1.txt')], contras[0])
    os.remove(f'{diru}/reto2.zip')
    os.remove(f'{diru}/pass1.txt')
    return contras

def main():
    print('[+] Generando carpeta y archivos de carlos...')
    contras_carlos = crea_carpeta_carlos()
    contras_otros = {}
    for user in USUARIOS:
        if user != 'carlos':
            print(f'[+] Generando carpeta de señuelo para {user}...')
            contras_otros[user] = crea_carpeta_user(user)
    print('[+] ¡Listo! Estructura assets creada.')
    print('\nSe recomienda copiar el siguiente mensaje en un lugar seguro para testeo y depuración')
    print('\n====== CONTRASEÑAS DE TEST ======')
    print('\n[Carlos]')
    for idx, pwd in enumerate(contras_carlos, 1):
        print(f'  Nivel {idx}: {pwd}')
    for user in contras_otros:
        print(f'\n[{user.capitalize()}]')
        print(f'  Zip señuelo nivel 1: {contras_otros[user]}')
    print('====== FIN ======\n')

if __name__ == '__main__':
    main()
