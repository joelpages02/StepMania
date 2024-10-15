<?php
// Inicia la sessió
session_start();

// Defineix la ubicació del fitxer JSON amb les dades de les cançons
$jsonFile = 'basecanc.json';
$songs = [];

// Carrega el fitxer JSON si existeix
if (file_exists($jsonFile)) {
    $songs = json_decode(file_get_contents($jsonFile), true);
}

// Directoris per pujar arxius
$musicDir = 'uploads/music/';
$coversDir = 'uploads/covers/';
$gameDir = 'uploads/games/';

// Crea els directoris si no existeixen
if (!is_dir($musicDir)) {
    mkdir($musicDir, 0755, true);
}
if (!is_dir($coversDir)) {
    mkdir($coversDir, 0755, true);
}
if (!is_dir($gameDir)) {
    mkdir($gameDir, 0755, true);
}

// Verificar si se han enviado los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $title = $_POST['titol'];
    $artist = $_POST['artista'];
    $description = $_POST['descripcio'];
    $timingData = $_POST['timingData'];  // Contenido del archivo TXT
}

// Processa la pujada d'arxius de música
if (isset($_FILES['fmusic'])) {
    $fileMusic = $_FILES['fmusic'];
    $fileMusicName = $fileMusic['name'];
    $fileMusicTmpName = $fileMusic['tmp_name'];
    $fileMusicError = $fileMusic['error'];
    $fileMusicExt = strtolower(pathinfo($fileMusicName, PATHINFO_EXTENSION));
    $allowedMusic = ['mp3', 'wav'];

    // Obté el següent número de música
    if (!empty($songs)) {
        $lastSong = end($songs);
        $count = $lastSong['id'] + 1; // Incrementa l'ID basat en l'última cançó
    } else {
        $count = 1; // Si no hi ha cançons, comença des de 1
    }

    $newMusicName = "musica_$count.$fileMusicExt";  // Asegúrate de que el nombre de archivo sea correcto
    $musicDestination = $musicDir . $newMusicName;

    if (move_uploaded_file($fileMusicTmpName, $musicDestination)) {
        // S'ha pujat correctament
    } else {
        $_SESSION['error'] = "Error al moure l'arxiu de música.";
        header('Location: llistacanc.php');
        exit();
    }
}

// Processa la pujada de la caràtula
if (isset($_FILES['fcarat'])) {
    $fileCover = $_FILES['fcarat'];
    $fileCoverName = $fileCover['name'];
    $fileCoverTmpName = $fileCover['tmp_name'];
    $fileCoverError = $fileCover['error'];
    $fileCoverExt = strtolower(pathinfo($fileCoverName, PATHINFO_EXTENSION));
    $allowedCover = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($fileCoverExt, $allowedCover)) {
        $_SESSION['error'] = "Només es permeten arxius de caràtula en format JPG, JPEG, PNG o GIF.";
        header('Location: llistacanc.php');
        exit();
    }

    $newCoverName = "imatge_$count.$fileCoverExt";  // Asegúrate de que el nombre de archivo sea correcto
    $coverDestination = $coversDir . $newCoverName;

    if (!move_uploaded_file($fileCoverTmpName, $coverDestination)) {
        $_SESSION['error'] = "Error al moure l'arxiu de caràtula.";
        header('Location: llistacanc.php');
        exit();
    }
}

// Emmagatzema les dades de la cançó
$songs[] = [
    'id' => $count,
    'title' => $title,
    'artist' => $artist,
    'music' => $newMusicName,  // Almacena el nombre del archivo de música
    'cover' => $coverDestination ?? null,
    'description' => $description,
    'timingData' => $timingData
];

// Guarda les dades en el fitxer JSON
file_put_contents($jsonFile, json_encode($songs));

// Redirigeix a la llista
header('Location: llistacanc.php');
exit();
?>
