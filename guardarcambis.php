<?php
// Mostrar errores de PHP para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Archivo JSON donde se guardan los datos
$jsonFile = 'basecanc.json';

// Verifica si el archivo JSON existe
if (!file_exists($jsonFile)) {
    die("El archivo JSON no existe.");
}

// Lector del archivo JSON
$jsonData = file_get_contents($jsonFile);

// Verifica si el JSON fue leído correctamente
if ($jsonData === false) {
    die("No se puede leer el archivo JSON.");
}

// Decodificar JSON a un array asociativo
$songs = json_decode($jsonData, true);

// Verificar si la decodificación fue exitosa
if ($songs === null) {
    die("Error al decodificar el archivo JSON.");
}

// Obtén los datos del formulario
$originalTitle = isset($_POST['original_title']) ? trim($_POST['original_title']) : '';
$newTitle = isset($_POST['titol']) ? trim($_POST['titol']) : $originalTitle;
$newArtist = isset($_POST['artista']) ? trim($_POST['artista']) : '';
$newDescription = isset($_POST['descripcio']) ? trim($_POST['descripcio']) : '';

// Buscar la canción original en el array de canciones
$foundIndex = -1;
foreach ($songs as $index => $song) {
    if ($song['title'] === $originalTitle) {
        $foundIndex = $index;
        break;
    }
}

// Verificar si se encontró la canción
if ($foundIndex === -1) {
    die("La canción original no existe.");
}

// Actualizar los campos de la canción
$songs[$foundIndex]['title'] = $newTitle;
$songs[$foundIndex]['artist'] = $newArtist;
$songs[$foundIndex]['description'] = $newDescription;

// Verificar si se subió una nueva música
if (isset($_FILES['musica']) && $_FILES['musica']['error'] === UPLOAD_ERR_OK) {
    $musicPath = 'path/to/music/' . basename($_FILES['musica']['name']);
    move_uploaded_file($_FILES['musica']['tmp_name'], $musicPath);
    $songs[$foundIndex]['music'] = $musicPath;
}

// Verificar si se subió una nueva imagen
if (isset($_FILES['imatge']) && $_FILES['imatge']['error'] === UPLOAD_ERR_OK) {
    $imagePath = 'path/to/images/' . basename($_FILES['imatge']['name']);
    move_uploaded_file($_FILES['imatge']['tmp_name'], $imagePath);
    $songs[$foundIndex]['cover'] = $imagePath;
}

// Verificar si se subió un nuevo archivo de letra
if (isset($_FILES['lletra']) && $_FILES['lletra']['error'] === UPLOAD_ERR_OK) {
    $lyricsPath = 'path/to/lyrics/' . basename($_FILES['lletra']['name']);
    move_uploaded_file($_FILES['lletra']['tmp_name'], $lyricsPath);
    $songs[$foundIndex]['lyrics'] = $lyricsPath;
}

// Guardar los cambios en el archivo JSON
if (file_put_contents($jsonFile, json_encode($songs, JSON_PRETTY_PRINT)) === false) {
    die("Error al escribir en el archivo JSON.");
}

// Redirigir a la página de inicio después de guardar
header('Location: index.html');
exit;
