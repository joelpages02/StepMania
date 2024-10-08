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

     // Obtener el próximo número de música
     if (!empty($songs)) {
        $lastSong = end($songs);
        $count = $lastSong['id'] + 1; // Incrementa el ID basado en la última canción
    } else {
        $count = 1; // Si no hay canciones, comienza desde 1
    }

    $newMusicName = "musica_$count".".$fileMusicExt";
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

    if (!in_array($fileCoverExt, $allowedCover)) {
        $_SESSION['error'] = "Solo se permiten archivos de carátula en formato JPG, JPEG, PNG o GIF.";
        header('Location: llistacanc.php');
        exit();
    }

    $newCoverName = "imatge_$count".".$fileCoverExt";
    $coverDestination = $coversDir . $newCoverName;

    if (!move_uploaded_file($fileCoverTmpName, $coverDestination)) {
        $_SESSION['error'] = "Error al mover el archivo de carátula.";
        header('Location: llistacanc.php');
        exit();
    }
}

if (!empty($songs)) {
    $lastsong = end($songs);
    $count = $lastsong['id'] + 1;
} else {
    $count = 1;
}

// Almacenar los datos de la canción
$songs[] = [
    'id' => $count,
    'title' => $title,
    'artist' => $artist,
    'music' => $newMusicName,
    'cover' => $coverDestination ?? null,
    'description' => $description
];

// Guardar los datos en el archivo JSON
file_put_contents($jsonFile, json_encode($songs));

// Redirigir a la lista
header('Location: llistacanc.php');
exit();
