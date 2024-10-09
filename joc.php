<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StepMania</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <video autoplay muted loop class="bg-video">
        <source src="img/fondo.mp4" type="video/mp4">
    </video>

    <nav class="nav2">
        <a href="index.html">
            <span class="tabler--home-filled"></span>
        </a>
    </nav>

    <?php
    $jsonFile = 'basecanc.json';
    $title = '';

    if (file_exists($jsonFile)) {
        // Leer el contenido del archivo JSON
        $jsonData = file_get_contents($jsonFile);
        // Decodificar el JSON a un array asociativo
        $songs = json_decode($jsonData, true);

        if ($songs !== null && !empty($songs)) {
            // Verifica si hay un parámetro 'id' en la URL
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Obtiene el 'id' de la URL o usa 0 como predeterminado

            // Verifica que el ID esté dentro del rango de canciones
            foreach($songs as $song) {
                if ($song['id'] === $id) {
                    $title = htmlspecialchars($song['title']); // Escoge el título según el ID seleccionado
                    $cover = htmlspecialchars($song['cover']);
                    break;
                }
            }

            // if (isset($songs[$id])) {
            //     $title = htmlspecialchars($songs[$id]['title']); // Escoge el título según el ID seleccionado
            // } else {
            //     $title = 'Música no disponible'; // Si el ID no existe en el JSON
            // }
        } else {
            $title = 'No hi ha música disponible.';
        }
    }
    ?>

    <div class="cantual">
        <a class="sonant">Està sonant:<br></a>
        <a class="titulsonant"><?= $title ?></a>
        <img class="imgsonant" src="<?= $cover ?>">
    </div>

</body>

</html>
