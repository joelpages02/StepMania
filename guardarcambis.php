<?php
// Comprova si el formulari ha estat enviat
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Defineix la ubicació del fitxer JSON amb les dades de les cançons
    $jsonFile = 'basecanc.json';

    // Llegeix el contingut del fitxer JSON
    $jsonData = file_get_contents($jsonFile);

    // Decodifica el JSON a un array associatiu de PHP
    $songs = json_decode($jsonData, true);

    // Obté les dades del formulari
    $title = isset($_POST['titol']) ? $_POST['titol'] : '';
    $artist = isset($_POST['artista']) ? $_POST['artista'] : '';
    $description = isset($_POST['descripcio']) ? $_POST['descripcio'] : '';

    // Comprova si s'ha pujat un nou arxiu de música
    if (isset($_FILES['musica']) && $_FILES['musica']['error'] == 0) {
        // Guarda l'arxiu de música en una ubicació temporal
        $musicPath = 'uploads/' . basename($_FILES['musica']['name']);
        move_uploaded_file($_FILES['musica']['tmp_name'], $musicPath);
    }

    // Comprova si s'ha pujat una nova imatge
    if (isset($_FILES['imatge']) && $_FILES['imatge']['error'] == 0) {
        // Guarda la imatge en una ubicació temporal
        $imagePath = 'uploads/' . basename($_FILES['imatge']['name']);
        move_uploaded_file($_FILES['imatge']['tmp_name'], $imagePath);
    }

    // Comprova si s'ha pujat un nou arxiu de lletra
    if (isset($_FILES['lletra']) && $_FILES['lletra']['error'] == 0) {
        // Guarda l'arxiu de lletra en una ubicació temporal
        $lyricsPath = 'uploads/' . basename($_FILES['lletra']['name']);
        move_uploaded_file($_FILES['lletra']['tmp_name'], $lyricsPath);
    }

    // Actualitza les dades de la cançó
    $songs[$title] = [
        'artist' => $artist,
        'description' => $description,
        'musicPath' => $musicPath ?? '', // Utilitza el nou camí si està disponible
        'imagePath' => $imagePath ?? '', // Utilitza el nou camí si està disponible
        'gamePath' => $lyricsPath ?? '' // Utilitza el nou camí si està disponible
    ];

    // Codifica l'array de cançons a JSON
    $jsonData = json_encode($songs, JSON_PRETTY_PRINT);

    // Guarda el JSON actualitzat al fitxer
    file_put_contents($jsonFile, $jsonData);

    // Redirigeix a la pàgina de la llista de cançons
    header('Location: llistacanc.php');
    exit;
}

// Si el mètode de la petició no és POST, redirigeix a la pàgina d'edició
header('Location: editcan.php');
exit;
?>
