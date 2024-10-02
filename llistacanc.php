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

    <header class="header2">
        <h1>Llistat Cançons</h1>
    </header>
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
            // Mostrar canciones desde el archivo JSON
            if (empty($songs)) {
                echo "<li>No hi ha cançons disponibles.</li>";
            } else {
                // Inicializar el contador
                $count = 1;

                foreach ($songs as $index => $song) {
                    // Asegurarse de que cada campo esté presente
                    $title = isset($song['title']) ? htmlspecialchars($song['title']) : 'Título no disponible';
                    $music = isset($song['music']) ? htmlspecialchars($song['music']) : 'Nombre no disponible';
                    $artist = isset($song['artist']) ? htmlspecialchars($song['artist']) : 'Artista no disponible';
                    $cover = isset($song['cover']) ? htmlspecialchars($song['cover']) : '';

                    // Mostrar información de la canción con número
                    echo "<li>Cançó $count:</li>"; // Mostrar el número de la canción
                    echo "<li>Títul: $title</li>";
                    echo "<li>Artista: $artist</li>";
                    if ($cover) {
                        echo "<li>Caràtula: <img src='$cover' alt='Carátula' style='width:100px; height:auto;'></li>";
                    } else {
                        echo "<li>Caràtula: No disponible</li>";
                    }

                    // Formulario para eliminar la canción
                    echo "
                    <form action='eliminarcanco.php' method='post'>
                        <input type='hidden' name='song_index' value='$index'>
                        <button type='submit'>Eliminar Cançó</button>
                    </form>
                    ";

                    echo "
                    <form action='editcan.php?title={$title}&&artist={$artist}' method='post'>
                        <input type='hidden' name='son_index' value='$index'>
                        <button type='submit'>Editar</button>
                    ";

                    // Incrementar el contador
                    $count++;
                }
            }

            // Mostrar mensajes de éxito
            if (isset($_SESSION['success'])) {
                if (is_array($_SESSION['success'])) {
                    foreach ($_SESSION['success'] as $message) {
                        echo "<li>$message</li>";
                    }
                } else {
                    echo "<li>" . $_SESSION['success'] . "</li>";
                }
                unset($_SESSION['success']); // Limpiar mensajes después de mostrarlos
            }

            // Mostrar mensajes de error
            if (isset($_SESSION['error'])) {
                echo "<li style='color:red;'>" . $_SESSION['error'] . "</li>";
                unset($_SESSION['error']); // Limpiar mensaje de error después de mostrarlo
            }
            ?>
        </ul>
    </div>
</body>
</html>
