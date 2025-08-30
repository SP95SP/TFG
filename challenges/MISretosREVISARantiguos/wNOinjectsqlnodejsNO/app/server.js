const express = require('express');
const sqlite3 = require('sqlite3').verbose();
const fs = require('fs');

const app = express();
const port = process.env.PORT;

// Conexión a la base de datos SQLite
const db = new sqlite3.Database(':memory:');

// Leer y ejecutar el script de inicialización
const initSQL = fs.readFileSync('db-init.sql', 'utf8');
db.exec(initSQL);

app.use(express.urlencoded({ extended: true }));

app.get('/', (req, res) => {
    res.send(`
        <h1>Inicia sesión</h1>
        <form method="POST" action="/login">
            Usuario: <input type="text" name="username"><br>
            Contraseña: <input type="password" name="password"><br>
            <input type="submit" value="Iniciar sesión">
        </form>
    `);
});

app.post('/login', (req, res) => {
    const { username, password } = req.body;

    // Consulta SQL vulnerable a inyección
    const query = `SELECT * FROM users WHERE username = '${username}' AND password = '${password}'`;
    console.log('Consulta generada:', query);

    db.all(query, (err, rows) => {
        if (err) {
            return res.status(500).send('Error en el servidor.');
        }

        if (rows.length > 0) {
            const flag = fs.readFileSync(process.env.FLAG_FILE, 'utf8');
            res.send(`Acceso concedido. Aquí está tu flag: ${flag}`);
        } else {
            res.send('Acceso denegado.');
        }
    });
});

app.listen(port, () => {
    console.log(`Servidor ejecutándose en http://localhost:${port}`);
});
