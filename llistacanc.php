<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$jsonFile = 'basecanc.json';
$songs = [];

// Cargar el archivo JSON si existe
if (file_exists($jsonFile)) {
    $jsonContent = file_get_contents($jsonFile);
    $songs = json_decode($jsonContent, true);  // Decodificar como array asociativo

    if (json_last_error() !== JSON_ERROR_NONE) {
        $_SESSION['error'] = "Error al decodificar el JSON: " . json_last_error_msg();
    }
} else {
    $_SESSION['error'] = "El archivo JSON no existe.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llistat de Cançons</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="llista-cancions">
    <video autoplay muted loop class="bg-video">
        <source src="img/fondo.mp4" type="video/mp4">
    </video>

    <nav class="nav2">
        <a href="index.html">
            <span class="tabler--home-filled"></span>
        </a>

        <a href="formacanco.html">
            <span class="streamline--song-recommendation-solid"></span>
        </a>
    </nav>

    <div class="llistac">
    <ul>
        <?php
        // Cargar el archivo JSON
        $jsonFile = 'basecanc.json';

        if (file_exists($jsonFile)) {
            // Leer el contenido del archivo JSON
            $jsonData = file_get_contents($jsonFile);
            if ($jsonData === false) {
                // Mostrar un error si no se puede leer el archivo
                echo "<li>Error: No se pudo leer el archivo JSON.</li>";
            } else {
                // Decodificar el JSON a un array asociativo
                $songs = json_decode($jsonData, true);
                if ($songs === null) {
                    // Mostrar un error si el JSON está mal formateado
                    echo "<li>Error: El archivo JSON está mal formado.</li>";
                }
            }
        } else {
            // Mostrar un mensaje si el archivo no existe
            echo "<li>Error: El archivo JSON no existe.</li>";
            $songs = [];
        }

        // Mostrar canciones desde el archivo JSON
        if (empty($songs)): ?>
            <li>No hi ha cançons disponibles.</li>
        <?php else:
            // Inicializar el contador
            $count = 1;

            foreach ($songs as $index => $song):
                // Asegurarse de que cada campo esté presente
                $title = isset($song['title']) ? htmlspecialchars($song['title']) : 'Título no disponible';
                $artist = isset($song['artist']) ? htmlspecialchars($song['artist']) : 'Artista no disponible';
                $cover = isset($song['cover']) ? htmlspecialchars($song['cover']) : '';
                $id = isset($song['id']) ? htmlspecialchars($song['id']) : '';
        ?>
                <li>Cançó <?= $count ?></li>
                <li>Títul: <?= $title ?></li>
                <li>Artista: <?= $artist ?></li>
                <li>    
                    <?php if ($cover): ?>
                        <img src="<?= $cover ?>" alt="Carátula" style="width:100px; height:auto;">
                    <?php else: ?>
                        No disponible
                    <?php endif; ?>
                </li>

                <div class="botonsllista">

                 <!-- Formulario para jugar -->
                 <form action="joc.php?id=<?= urlencode($id) ?>" method="post">
                    <button class='bllista' type="submit">Jugar</button>
                </form>

                <!-- Formulario para editar la canción -->
                <form action="editcan.php?title=<?= urlencode($title) ?>&artist=<?= urlencode($artist) ?>" method="post">
                    <input type="hidden" name="song_index" value="<?= $index ?>">
                    <button class='bllista' type="submit">Editar</button>
                </form>

                <!-- Formulario para eliminar la canción -->
                <form action="eliminarcanco.php" method="post">
                    <input type="hidden" name="song_index" value="<?= $index ?>">
                    <button class='bllista' type="submit">Eliminar Cançó</button>
                </form>
                    </div>

               

                <?php $count++; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Mostrar mensajes de éxito -->
        <?php if (isset($_SESSION['success'])): ?>
            <?php if (is_array($_SESSION['success'])): ?>
                <?php foreach ($_SESSION['success'] as $message): ?>
                    <li><?= $message ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li><?= $_SESSION['success'] ?></li>
            <?php endif; ?>
            <?php unset($_SESSION['success']); // Limpiar mensajes después de mostrarlos ?>
        <?php endif; ?>

        <!-- Mostrar mensajes de error -->
        <?php if (isset($_SESSION['error'])): ?>
            <li style="color:red;"><?= $_SESSION['error'] ?></li>
            <?php unset($_SESSION['error']); // Limpiar mensaje de error después de mostrarlo ?>
        <?php endif; ?>
    </ul>
</div>


</body>

</html>