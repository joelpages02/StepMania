<?php
// Mostrar errors de PHP per a depuració
ini_set('display_errors', 1); // Activa la visualització d'errors
ini_set('display_startup_errors', 1); // Mostra els errors que es produeixen durant l'inici del PHP
error_reporting(E_ALL); // Estableix que es mostrin tots els errors possibles

// Arxiu JSON on es guarden les dades
$jsonFile = 'basecanc.json'; // Defineix el nom de l'arxiu JSON

// Verifica si l'arxiu JSON existeix
if (!file_exists($jsonFile)) {
    die("L'arxiu JSON no existeix."); // Finalitza l'execució si l'arxiu JSON no es troba
}

// Llegeix el contingut de l'arxiu JSON
$jsonData = file_get_contents($jsonFile); // Carrega el contingut de l'arxiu JSON en una cadena

// Verifica si s'ha llegit correctament l'arxiu JSON
if ($jsonData === false) {
    die("No es pot llegir l'arxiu JSON."); // Finalitza l'execució si l'arxiu no es pot llegir
}

// Decodifica el JSON a un array associatiu
$songs = json_decode($jsonData, true); // Converteix el JSON en un array de PHP

// Verifica si la decodificació ha estat exitosa
if ($songs === null) {
    die("Error al decodificar l'arxiu JSON."); // Finalitza l'execució si hi ha un error en la decodificació del JSON
}

// Obté les dades del formulari
$originalTitle = isset($_POST['original_title']) ? trim($_POST['original_title']) : ''; // Obté el títol original de la cançó
$newTitle = isset($_POST['titol']) ? trim($_POST['titol']) : $originalTitle; // Obté el nou títol o manté el títol original si no es modifica
$newArtist = isset($_POST['artista']) ? trim($_POST['artista']) : ''; // Obté el nou artista
$newDescription = isset($_POST['descripcio']) ? trim($_POST['descripcio']) : ''; // Obté la nova descripció

// Busca la cançó original dins de l'array de cançons
$foundIndex = -1; // Inicialitza l'índex com a no trobat
foreach ($songs as $index => $song) {
    if ($song['title'] === $originalTitle) { // Compara el títol original amb el títol de cada cançó
        $foundIndex = $index; // Si troba la coincidència, guarda l'índex de la cançó
        break; // Atura la cerca
    }
}

// Verifica si s'ha trobat la cançó
if ($foundIndex === -1) {
    die("La cançó original no existeix."); // Si no es troba la cançó, finalitza l'execució amb un missatge d'error
}

// Actualitza els camps de la cançó
$songs[$foundIndex]['title'] = $newTitle; // Actualitza el títol de la cançó
$songs[$foundIndex]['artist'] = $newArtist; // Actualitza l'artista
$songs[$foundIndex]['description'] = $newDescription; // Actualitza la descripció

// Verifica si s'ha pujat una nova música
if (isset($_FILES['musica']) && $_FILES['musica']['error'] === UPLOAD_ERR_OK) {
    $musicPath = 'path/to/music/' . basename($_FILES['musica']['name']); // Defineix la ruta per al nou arxiu de música
    move_uploaded_file($_FILES['musica']['tmp_name'], $musicPath); // Mou l'arxiu de música pujat a la ruta definida
    $songs[$foundIndex]['music'] = $musicPath; // Actualitza la ruta de la música en el JSON
}

// Verifica si s'ha pujat una nova imatge
if (isset($_FILES['imatge']) && $_FILES['imatge']['error'] === UPLOAD_ERR_OK) {
    $imagePath = 'path/to/images/' . basename($_FILES['imatge']['name']); // Defineix la ruta per a la nova imatge
    move_uploaded_file($_FILES['imatge']['tmp_name'], $imagePath); // Mou l'arxiu de la imatge pujada a la ruta definida
    $songs[$foundIndex]['cover'] = $imagePath; // Actualitza la ruta de la imatge en el JSON
}

// Verifica si s'ha pujat un nou arxiu de lletra
if (isset($_FILES['lletra']) && $_FILES['lletra']['error'] === UPLOAD_ERR_OK) {
    $lyricsPath = 'path/to/lyrics/' . basename($_FILES['lletra']['name']); // Defineix la ruta per al nou arxiu de lletra
    move_uploaded_file($_FILES['lletra']['tmp_name'], $lyricsPath); // Mou l'arxiu de lletra pujat a la ruta definida
    $songs[$foundIndex]['lyrics'] = $lyricsPath; // Actualitza la ruta de la lletra en el JSON
}

// Guarda els canvis en l'arxiu JSON
if (file_put_contents($jsonFile, json_encode($songs, JSON_PRETTY_PRINT)) === false) {
    die("Error en escriure a l'arxiu JSON."); // Finalitza l'execució si hi ha un error en guardar el JSON
}

// Redirigeix a la pàgina d'inici després de guardar
header('Location: llistacanc.php'); // Redirigeix a la pàgina principal
exit; // Finalitza l'execució del script
?>
