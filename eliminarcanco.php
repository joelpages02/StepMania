<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Archivo JSON donde se almacenan las canciones
$jsonFile = 'basecanc.json';
$songs = [];

// Cargar el archivo JSON si existe
if (file_exists($jsonFile)) {
    $jsonContent = file_get_contents($jsonFile);
    $songs = json_decode($jsonContent, true);  // Decodificar como array asociativo

    if (json_last_error() !== JSON_ERROR_NONE) {
        $_SESSION['error'] = "Error al decodificar el JSON: " . json_last_error_msg();
        header('Location: llistacanc.php');
        exit;
    }
} else {
    $_SESSION['error'] = "El archivo JSON no existe.";
    header('Location: llistacanc.php');
    exit;
}

// Verificar que $songs sea un array antes de continuar
if (!is_array($songs)) {
    $songs = [];  // Si la decodificación falló, asegurarse de que sea un array vacío
}

// Eliminar canción del JSON y archivos del servidor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['song_index'])) {
    $songIndex = (int)$_POST['song_index'];  // Índice de la canción a eliminar

    if (isset($songs[$songIndex])) {
        $song = $songs[$songIndex];

        // Construir rutas completas para los archivos de la canción y la carátula
        $coverPath = __DIR__ . '/' . $song['cover'];
        $musicPath = __DIR__ . '/uploads/music/' . $song['music'];

        // Eliminar archivo de la carátula si existe
        if (file_exists($coverPath)) {
            unlink($coverPath);
        } else {
            $_SESSION['error'] = "No se encontró el archivo de la carátula: " . htmlspecialchars($song['cover']);
        }

        // Eliminar archivo de la música si existe
        if (file_exists($musicPath)) {
            unlink($musicPath);
        } else {
            $_SESSION['error'] = "No se encontró el archivo de la canción: " . htmlspecialchars($song['music']);
        }

        // Eliminar la canción del array
        unset($songs[$songIndex]);

        // Reindexar el array de canciones
        $songs = array_values($songs);

        // Guardar el nuevo contenido en el archivo JSON
        if (file_put_contents($jsonFile, json_encode($songs, JSON_PRETTY_PRINT)) === false) {
            $_SESSION['error'] = "No se pudo guardar el archivo JSON actualizado.";
        } else {
            $_SESSION['success'] = "La cancó s'ha eliminat correctament.";
        }
    } else {
        $_SESSION['error'] = "Índice de canción no válido.";
    }

    // Redireccionar de nuevo a la página de listado de canciones
    header('Location: llistacanc.php');
    exit;
} else {
    $_SESSION['error'] = "Solicitud no válida.";
    header('Location: llistacanc.php');
    exit;
}