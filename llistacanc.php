<?php
session_start();
$jsonFile = 'basecanc.json';
$songs = [];

// Cargar el archivo JSON si existe
if (file_exists($jsonFile)) {
    $jsonContent = file_get_contents($jsonFile);
    
    if ($jsonContent === false) {
        $_SESSION['error'] = "No se pudo leer el archivo JSON.";
    } else {
        $songs = json_decode($jsonContent, true); // Decodificar como array
        
        // Verificar errores en la decodificación
        if (json_last_error() !== JSON_ERROR_NONE) {
            $_SESSION['error'] = "Error al decodificar el JSON: " . json_last_error_msg();
            $songs = []; // Reiniciar a array vacío si hay error
        }
    }
} else {
    $_SESSION['error'] = "El archivo JSON no existe.";
}

// Verificar que $songs sea un array antes de usar foreach
if (!is_array($songs)) {
    $songs = []; // Asegúrate de que $songs sea un array vacío si la carga falla
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

<body class="llista-cancions"> <!-- Cambiado a la clase correcta -->
    <video autoplay muted loop class="bg-video">
        <source src="img/fondo.mp4" type="video/mp4">
    </video>

    <header class="header2">
        <h1>Llistat Cançons</h1>
    </header>
    <nav class="nav2">
        <a href="index.html">
            <img src="img/home.png" alt="home">
        </a>
    </nav>

    <div class="llistac">
        <ul>
            <?php
            // Mostrar canciones desde el archivo JSON
            if (empty($songs)) {
                echo "<li>No hay canciones disponibles o ocurrió un error al cargar el archivo.</li>";
            } else {
                // Inicializar el contador
                $counter = 1; 
                
                foreach ($songs as $song) {
                    // Asegurarse de que cada campo esté presente
                    $title = isset($song['title']) ? htmlspecialchars($song['title']) : 'Título no disponible';
                    $music = isset($song['music']) ? htmlspecialchars($song['music']) : 'Nombre no disponible';
                    $artist = isset($song['artist']) ? htmlspecialchars($song['artist']) : 'Artista no disponible';
                    $cover = isset($song['cover']) ? htmlspecialchars($song['cover']) : '';

                    // Mostrar información de la canción con número
                    echo "<li>Cançó $counter:</li>"; // Mostrar el número de la canción
                    echo "<li>Títul: $title</li>";
                    echo "<li>Nom de la Cançó: $music</li>";
                    echo "<li>Artista: $artist</li>";
                    if ($cover) {
                        echo "<li>Caràtula: <img src='$cover' alt='Carátula' style='width:100px; height:auto;'></li>";
                    } else {
                        echo "<li>Caràtula: No disponible</li>";
                    }

                    // Incrementar el contador
                    $counter++;
                }
            }

            // Mostrar mensajes de éxito
            if (isset($_SESSION['success'])) {
                foreach ($_SESSION['success'] as $message) {
                    echo "<li>$message</li>";
                }
                unset($_SESSION['success']); // Limpiar mensajes después de mostrarlos
            }

            // Mostrar mensajes de error
            if (isset($_SESSION['error'])) {
                echo "<li style='color:red;'>".$_SESSION['error']."</li>";
                unset($_SESSION['error']); // Limpiar mensaje de error después de mostrarlo
            }
            ?>
        </ul>
    </div>
</body>

</html>
