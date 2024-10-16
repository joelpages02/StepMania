<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Comprova si el formulari ha estat enviat
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Defineix la ubicació del fitxer JSON amb les dades de les cançons
    $jsonFile = 'basecanc.json';

    // Llegeix el contingut del fitxer JSON
    $jsonData = file_get_contents($jsonFile);
    $songs = json_decode($jsonData, true);

    // Obté les dades del formulari
    $title = isset($_POST['titol']) ? $_POST['titol'] : '';
    $artist = isset($_POST['artista']) ? $_POST['artista'] : '';
    $description = isset($_POST['descripcio']) ? $_POST['descripcio'] : '';

    // Comprova si s'ha pujat un nou arxiu de música
    if (isset($_FILES['musica']) && $_FILES['musica']['error'] == 0) {
        $musicPath = 'uploads/' . basename($_FILES['musica']['name']);
        if (!move_uploaded_file($_FILES['musica']['tmp_name'], $musicPath)) {
            die('Error al guardar el archivo de música.');
        }
    } else {
        $musicPath = ''; // Si no se subió música, no asignar ruta
    }

    // Comprova si s'ha pujat una nova imatge
    if (isset($_FILES['imatge']) && $_FILES['imatge']['error'] == 0) {
        $imagePath = 'uploads/' . basename($_FILES['imatge']['name']);
        if (!move_uploaded_file($_FILES['imatge']['tmp_name'], $imagePath)) {
            die('Error al guardar la imagen.');
        }
    } else {
        $imagePath = ''; // Si no se subió imagen, no asignar ruta
    }

    // Comprova si s'ha pujat un nou arxiu de lletra
    if (isset($_FILES['lletra']) && $_FILES['lletra']['error'] == 0) {
        $lyricsPath = 'uploads/' . basename($_FILES['lletra']['name']);
        if (!move_uploaded_file($_FILES['lletra']['tmp_name'], $lyricsPath)) {
            die('Error al guardar la letra.');
        }
    } else {
        $lyricsPath = ''; // Si no se subió letra, no asignar ruta
    }

    // Actualitza les dades de la cançó
    $songs[$title] = [
        'artist' => $artist,
        'description' => $description,
        'musicPath' => $musicPath,
        'imagePath' => $imagePath,
        'gamePath' => $lyricsPath
    ];

    // Codifica l'array de cançons a JSON
    $jsonData = json_encode($songs, JSON_PRETTY_PRINT);

    // Guarda el JSON actualitzat al fitxer
    if (file_put_contents($jsonFile, $jsonData) === false) {
        die('Error al guardar el fitxer JSON.');
    }

    // Redirigeix a la pàgina de la llista de cançons
    header('Location: llistacanc.php');
    exit;
}

// Si el mètode de la petició no és POST, redirigeix a la pàgina d'edició
header('Location: editcan.php');
exit;
?>
