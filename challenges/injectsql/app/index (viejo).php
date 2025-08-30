<?php
// Configuración de la base de datos
$host = 'db';  // Nombre del servicio de la base de datos en docker-compose.yml
$dbname = 'challenge';
$username = 'root';
$password = 'example';

// Conexión a la base de datos
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se envió un formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Capturar los datos del formulario sin escapar
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Consulta SQL vulnerable a inyección
    $query = "SELECT * FROM users WHERE username = '$user' AND password = '$pass'";
    echo "Consulta generada: $query<br>";

    $result = $conn->query($query);

    // Verificar el resultado
    if ($result && $result->num_rows > 0) {
        echo "Acceso concedido. Aquí está tu flag: " . file_get_contents('/flag.txt');
    } else {
        echo "Acceso denegado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Desafío SQL Injection</title>
</head>
<body>
    <h1>Inicia sesión</h1>
    <form method="POST">
        Usuario: <input type="text" name="username"><br>
        Contraseña: <input type="password" name="password"><br>
        <input type="submit" value="Iniciar sesión">
    </form>
</body>
</html>
