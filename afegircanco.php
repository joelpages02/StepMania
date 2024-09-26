<?php
session_start();

$jsonFile = 'basecanc.json';
$songs = [];

// Cargar el archivo JSON si existe
if (file_exists($jsonFile)) {
    $songs = json_decode(file_get_contents($jsonFile), true);
}

// Directorios para subir archivos
$musicDir = 'uploads/music/';
$coversDir = 'uploads/covers/';
$gameDir = 'uploads/games/';

// Crear directorios si no existen
if (!is_dir($musicDir)) {
    mkdir($musicDir, 0755, true);
}
if (!is_dir($coversDir)) {
    mkdir($coversDir, 0755, true);
}
if (!is_dir($gameDir)) {
    mkdir($gameDir, 0755, true);
}

// Procesar el formulario
$title = $_POST['titol'] ?? 'Título no disponible';
$artist = $_POST['artista'] ?? 'Artista no disponible';
$description = $_POST['descripcio'] ?? 'Descripción no disponible';

// Procesar la subida de archivos de música
if (isset($_FILES['fmusic'])) {
    $fileMusic = $_FILES['fmusic'];
    $fileMusicName = $fileMusic['name'];
    $fileMusicTmpName = $fileMusic['tmp_name'];
    $fileMusicError = $fileMusic['error'];
    $fileMusicExt = strtolower(pathinfo($fileMusicName, PATHINFO_EXTENSION));
    $allowedMusic = ['mp3', 'wav'];

    if ($fileMusicError !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "Error al subir el archivo de música: " . $fileMusicError;
        header('Location: llistacanc.php');
        exit();
    }

    if (!in_array($fileMusicExt, $allowedMusic)) {
        $_SESSION['error'] = "Solo se permiten archivos de música en formato MP3 o WAV.";
        header('Location: llistacanc.php');
        exit();
    }

    $newMusicName = uniqid('', true) . "." . $fileMusicExt;
    $musicDestination = $musicDir . $newMusicName;

    if (move_uploaded_file($fileMusicTmpName, $musicDestination)) {
        // Se ha subido correctamente
    } else {
        $_SESSION['error'] = "Error al mover el archivo de música.";
        header('Location: llistacanc.php');
        exit();
    }
}

// Procesar la subida de la carátula
if (isset($_FILES['fcarat'])) {
    $fileCover = $_FILES['fcarat'];
    $fileCoverName = $fileCover['name'];
    $fileCoverTmpName = $fileCover['tmp_name'];
    $fileCoverError = $fileCover['error'];
    $fileCoverExt = strtolower(pathinfo($fileCoverName, PATHINFO_EXTENSION));
    $allowedCover = ['jpg', 'jpeg', 'png', 'gif'];

    if ($fileCoverError !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "Error al subir el archivo de carátula: " . $fileCoverError;
        header('Location: llistacanc.php');
        exit();
    }

    if (!in_array($fileCoverExt, $allowedCover)) {
        $_SESSION['error'] = "Solo se permiten archivos de carátula en formato JPG, JPEG, PNG o GIF.";
        header('Location: llistacanc.php');
        exit();
    }

    $newCoverName = uniqid('', true) . "." . $fileCoverExt;
    $coverDestination = $coversDir . $newCoverName;

    if (!move_uploaded_file($fileCoverTmpName, $coverDestination)) {
        $_SESSION['error'] = "Error al mover el archivo de carátula.";
        header('Location: llistacanc.php');
        exit();
    }
}

// Almacenar los datos de la canción
$songs[] = [
    'title' => $title,
    'artist' => $artist,
    'music' => $newMusicName,
    'cover' => $coverDestination ?? null,
    'description' => $description
];

// Guardar los datos en el archivo JSON
file_put_contents($jsonFile, json_encode($songs));

// Guardar mensajes en la sesión
$_SESSION['success'] = "Canción añadida exitosamente.";

// Redirigir a la lista
header('Location: llistacanc.php');
exit();
?>