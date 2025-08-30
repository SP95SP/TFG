<?php
// index.php

// Actualiza el contador de peticiones
$counterFile = "counter.txt";
$counter = (int) file_get_contents($counterFile);
$counter++;
file_put_contents($counterFile, $counter);

// Umbral para activar el error (flag)
$threshold = 100;
$flag = file_get_contents("_flag.txt");

if ($counter >= $threshold) {
    header("HTTP/1.1 500 Internal Server Error");
    echo "Error crítico: " . $flag;
    exit();
}

function getAdvice($age) {
    if ($age < 18) {
        return file_get_contents("advice/advice1.txt");
    } elseif ($age < 35) {
        return file_get_contents("advice/advice2.txt");
    } elseif ($age < 60) {
        return file_get_contents("advice/advice3.txt");
    } else {
        return file_get_contents("advice/advice4.txt");
    }
}

$age = "";
$advice = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $age = intval($_POST["age"]);
    $advice = getAdvice($age);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consejos de Salud</title>
    <style>
        body {
            background: linear-gradient(135deg, #ffe9ec 0%, #d2f6fc 100%);
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.20);
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 480px;
            width: 95vw;
            margin: 2rem auto;
            text-align: center;
            position: relative;
        }
        .card::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 22px;
            padding: 2px;
            background: linear-gradient(120deg, #fcb6c5, #e6e6fa, #d2f6fc);
            z-index: -1;
        }
        .main-title {
            font-size: 2.4rem;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 0.8rem;
            color: #d81b60;
            text-shadow: 1px 2px 8px #ffcad4cc;
        }
        .intro {
            font-size: 1.1rem;
            margin-bottom: 1.7rem;
            color: #495057;
        }
        form {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin-bottom: 1.3rem;
        }
        input[type="number"] {
            padding: 0.5rem;
            border: none;
            border-radius: 10px;
            background: #ffe9ec;
            box-shadow: 0 1px 4px #d2f6fc80;
            width: 7rem;
            font-size: 1rem;
        }
        input[type="submit"] {
            padding: 0.5rem 1.2rem;
            border: none;
            border-radius: 10px;
            background: linear-gradient(90deg, #fcb6c5 0%, #d81b60 80%);
            color: #fff;
            font-weight: bold;
            font-size: 1rem;
            box-shadow: 0 2px 8px #d81b6080;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }
        input[type="submit"]:hover {
            background: linear-gradient(90deg, #d81b60 0%, #fcb6c5 90%);
            transform: translateY(-2px) scale(1.03);
        }
        h2 {
            color: #ad1457;
            margin-top: 1.6rem;
            font-size: 1.4rem;
        }
        .advice-content {
            text-align: left;
            background: #f8f9fa;
            border-radius: 12px;
            box-shadow: 0 1px 4px #ffd6e080;
            padding: 1rem 1.2rem;
            margin-top: 0.6rem;
            color: #374151;
        }
        @media (max-width: 650px) {
            .card { padding: 1.5rem 0.5rem; }
            .main-title { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="main-title">CONSEJOS DE SALUD</div>
        <div class="intro">
            Bienvenido a la página de <b>consejos de salud</b>.<br>
            Introduce tu edad y te mostraremos los mejores consejos adaptados para ti.<br>
            Este servicio es solo educativo. Consulta siempre con un profesional médico para asesoramiento personalizado.
        </div>
        <form method="post">
            <input type="number" name="age" placeholder="Tu edad" min="0" max="120" required>
            <input type="submit" value="Enviar">
        </form>
        <?php if ($advice): ?>
            <h2>Consejos:</h2>
            <div class="advice-content"><?php echo nl2br(htmlspecialchars($advice)); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
