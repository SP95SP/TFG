from flask import Flask, request, render_template
import sqlite3

app = Flask(__name__)

def get_db_connection():
    conn = sqlite3.connect('users.db')
    conn.row_factory = sqlite3.Row
    return conn

@app.route('/', methods=['GET'])
def index():
    return render_template('login.html')

@app.route('/login', methods=['POST'])
def login():
    username = request.form.get('username', '')
    password = request.form.get('password', '')

    if not username or not password:
        return "Error: Credenciales faltantes", 400

    conn = get_db_connection()
    # Consulta vulnerable: se concatenan directamente los parámetros
    query = "SELECT * FROM users WHERE username = '{}' AND password = '{}'".format(username, password)
    try:
        cur = conn.execute(query)
        user = cur.fetchone()
    except Exception as e:
        conn.close()
        return "Error en la consulta", 500
    conn.close()

    if user:
        # Si las credenciales son exactamente las definidas, se da la bienvenida
        if username == "admin" and password == "securepassword":
            return "Bienvenido, admin"
        else:
            # Si se logra el bypass (inyección SQL), se muestra la flag
            try:
                with open("_flag.txt", "r") as f:
                    flag = f.read().strip()
            except Exception as e:
                flag = "Flag no encontrada"
            return "Flag: " + flag
    else:
        return "Error: Credenciales inválidas", 401

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=80)
