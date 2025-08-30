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
</head>
<body>
    <h1>Consejos de Salud</h1>
    <p>Introduce tu edad y te daremos los consejos de salud más apropiados para ti.</p>
    <form method="post">
        <input type="number" name="age" placeholder="Tu edad" required>
        <input type="submit" value="Enviar">
    </form>
    <?php if ($advice): ?>
        <h2>Consejos:</h2>
        <p><?php echo nl2br(htmlspecialchars($advice)); ?></p>
    <?php endif; ?>
</body>
</html>
