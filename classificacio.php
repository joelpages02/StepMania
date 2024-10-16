<?php
// Defineix la ubicació del fitxer JSON amb les dades de les cançons
$jsonFile = 'noms.json';

// Llegeix el contingut del fitxer JSON
if (file_exists($jsonFile)) {
    $jsonData = file_get_contents($jsonFile);
    $scores = json_decode($jsonData, true);
} else {
    $scores = [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/estils/style.css">
    <title>Classificació</title>
</head>

<body>

    <header class="header2">
        <h1>Classificació</h1>
    </header>

    <nav class="nav2">
        <a href="index.html">
            <span class="tabler--home-filled"></span>
        </a>
    </nav>

    <ul class="llistaclassif">
        <?php 
        // Itera sobre las puntuaciones y muestra solo si 'name' y 'score' existen
        foreach ($scores as $entry) {
            // Verifica si las claves 'name' y 'score' existen en el array
            $name = isset($entry['name']) ? htmlspecialchars($entry['name']) : 'Nombre no disponible';
            $score = isset($entry['score']) ? htmlspecialchars($entry['score']) : '0';

            echo "<li>$name ----- $score</li>";
        }
        ?>
    </ul>

</body>

</html>
