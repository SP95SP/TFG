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

$msg = "";
// Verificar si se envió un formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Capturar los datos del formulario sin escapar
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Consulta SQL vulnerable a inyección
    $query = "SELECT * FROM users WHERE username = '$user' AND password = '$pass'";
    $msg .= "<div class='query-box'><strong>Consulta generada:</strong> <code>$query</code></div>";

    $result = $conn->query($query);

    // Verificar el resultado
    if ($result && $result->num_rows > 0) {
        $msg .= "<div class='success-msg'>Acceso concedido. Aquí está tu flag: <span class='flag'>" . file_get_contents('/flag.txt') . "</span></div>";
    } else {
        $msg .= "<div class='error-msg'>Acceso denegado.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tinfa | Área Privada Distribución Farmacéutica</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html, body {
            margin: 0; padding: 0;
  
            background: linear-gradient(120deg, #d4fbe8 0%, #7de5c1 40%, #a777e3 100%);
            font-family: Arial, Helvetica, sans-serif;
            color: #2b2342;
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(90deg, #7938c5 80%, #62e8b7 120%);
            padding: 1.3rem 2rem 1.2rem 2rem;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 1.15rem;
            box-shadow: 0 2px 10px #c2bef42c;
        }
        .logo {
            font-size: 2.1rem;
            font-weight: bold;
            letter-spacing: 2.5px;
            color: #fff;
        }
        .logo span {
            color: #62e8b7;
        }
        .logo-slogan {
            font-size: 1rem;
            font-weight: 400;
            color: #e2ffe7;
            margin-left: 10px;
            letter-spacing: 1px;
        }
        .nav {
            display: flex;
            gap: 2rem;
        }
        .nav a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
            padding-bottom: 2px;
            border-bottom: 2px solid transparent;
        }
        .nav a:hover, .nav a.active {
            color: #62e8b7;
            border-bottom: 2px solid #62e8b7;
        }
        .main-container {
            display: flex;
            max-width: 1150px;
            margin: 2.5rem auto;
            background: #fff;
            border-radius: 19px;
            box-shadow: 0 4px 36px #6c3fa722;
            overflow: hidden;
        }
        .banner {
            position: relative;
            background: #7938c5;
            color: #fff;
            padding: 2.7rem 2rem 2.3rem 2.2rem;
            flex: 1.3;
            min-height: 430px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: flex-start;
            overflow: hidden;
        }
        .banner::before {
         
            content: "";
            position: absolute;
            top: -100px; right: -90px;
            width: 350px; height: 350px;
            background: radial-gradient(circle at 40% 40%, #62e8b7 0%, #7938c5 60%);
            opacity: 0.33;
            border-radius: 50%;
            z-index: 0;
        }
        .banner::after {
            content: "";
            position: absolute;
            bottom: -50px; left: -60px;
            width: 160px; height: 160px;
            background: radial-gradient(circle at 30% 30%, #fff 0%, #7938c5 70%);
            opacity: 0.15;
            border-radius: 50%;
            z-index: 0;
        }
        .banner-content {
            position: relative;
            z-index: 1;
        }
        .banner h1 {
            font-size: 2.3rem;
            font-weight: bold;
            margin-bottom: 1.6rem;
            margin-top: 0.2em;
            line-height: 1.12;
        }
        .banner p {
            font-size: 1.09rem;
            margin-bottom: 2rem;
            margin-top: 0;
            line-height: 1.45;
        }

        .login-panel {
            flex: 1;
            padding: 2.7rem 2rem 2.2rem 2rem;
            background: #fafaff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: stretch;
        }
        .login-title {
            font-size: 1.45rem;
            color: #7938c5;
            margin-bottom: 1.2rem;
            font-weight: bold;
        }
        .query-box {
            background: #e8e4fa;
            color: #52338c;
            border-left: 4px solid #62e8b7;
            padding: 0.8rem 1rem;
            margin-bottom: 0.7rem;
            border-radius: 7px;
            font-size: 0.95rem;
            word-break: break-all;
        }
        .success-msg {
            background: #62e8b7;
            color: #124b32;
            padding: 1rem;
            border-radius: 7px;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .flag {
            background: #fff;
            color: #7938c5;
            padding: 2px 7px;
            border-radius: 6px;
            margin-left: 0.5em;
            font-family: 'Courier New', Courier, monospace;
            font-size: 1.07em;
        }
        .error-msg {
            background: #fde9ef;
            color: #ae0852;
            padding: 0.8rem 1rem;
            border-radius: 7px;
            margin-bottom: 0.5rem;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1.05rem;
            margin-top: 0.5rem;
        }
        label {
            font-weight: 600;
            color: #44406c;
            margin-bottom: 0.3rem;
        }
        input[type="text"],
        input[type="password"] {
            border: 1.6px solid #ded6f6;
            border-radius: 8px;
            padding: 0.67rem;
            font-size: 1rem;
            outline: none;
            background: #fff;
            transition: border-color 0.2s;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #7938c5;
        }
        input[type="submit"] {
            background: linear-gradient(90deg, #7938c5 60%, #62e8b7 120%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.78rem;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 0.4rem;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 10px #6c3fa71b;
        }
        input[type="submit"]:hover {
            background: linear-gradient(90deg, #522b87 60%, #19ca83 120%);
        }
        .side-info {
            margin-top: 2rem;
            font-size: 0.98rem;
            background: #e9e8fc;
            border-left: 5px solid #7938c5;
            padding: 1rem 1.3rem;
            border-radius: 12px;
            color: #483b6d;
        }
        .result-area {
            min-height: 2.7em;
            margin-bottom: 0.7em;
        }
        .footer {
            margin-top: 3rem;
            background: #f3f0fc;
            color: #7938c5;
            text-align: center;
            padding: 1.1rem 2rem 1.5rem;
            font-size: 0.98rem;
            letter-spacing: 0.03em;
        }
        .footer a {
            color: #19ca83;
            text-decoration: none;
            margin: 0 0.7em;
        }
        @media (max-width: 900px) {
            .main-container { flex-direction: column; }
            .banner, .login-panel { min-width: 240px; width: 100%; }
            .banner .svg-icon { display: none; }
            .banner { min-height: 210px;}
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Tin<span>fa</span>
            <span class="logo-slogan">| Distribución Farmacéutica</span>
        </div>
        <nav class="nav">
            <a href="#">Inicio</a>
            <a href="#">Productos</a>
            <a href="#">Distribución</a>
            <a href="#">Contacto</a>
            <a href="#" class="active">Área Privada</a>
        </nav>
    </div>
    <div class="main-container">
        <div class="banner">
            <div class="banner-content">
                <h1>Acceso Seguro<br>a la Red de Distribución</h1>
                <p>
                    Bienvenido a la plataforma de acceso exclusivo para clientes y distribuidores de Tinfa.<br>
                    Gestiona tus pedidos, consulta el stock y recibe atención personalizada.<br><br>
                    <b>Tu salud y confianza, nuestra prioridad.</b>
                </p>
           
            </div>
        </div>
        <div class="login-panel">
            <div class="login-title">Área Privada Tinfa</div>
            <div class="result-area">
                <?php
                    // La consulta y el resultado solo aparecen tras enviar el formulario
                    if ($msg) echo $msg;
                ?>
            </div>
            <form method="POST" autocomplete="off">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required autocomplete="off">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required autocomplete="off">
                <input type="submit" value="Iniciar sesión">
            </form>
            <div class="side-info">
                <b>¿Olvidaste tus datos?</b> Contacta con tu responsable de cuentas.<br><br>
                <b>¡Aviso!</b> Este sistema registra los intentos de acceso.<br>
                <b>Recomendación:</b> Nunca compartas tus credenciales.
            </div>
        </div>
    </div>
    <div class="footer">
        Tinfa S.A. &copy; 2025 |
        <a href="#">Aviso legal</a> |
        <a href="#">Política de privacidad</a> |
        <a href="#">Contacto</a>
        <br>
        <span style="font-size:0.94em; color:#8577a9;">Distribución exclusiva de medicamentos y productos sanitarios.

