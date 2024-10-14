<?php
// Mostra errors per depuració
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia la sessió
session_start();

// Arxiu JSON on s'emmagatzemen les cançons
$jsonFile = 'basecanc.json';
$songs = [];

// Carrega l'arxiu JSON si existeix
if (file_exists($jsonFile)) {
    $jsonContent = file_get_contents($jsonFile);
    $songs = json_decode($jsonContent, true);  // Decodifica com a array associatiu

    // Comprova si hi ha hagut errors en la decodificació del JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        $_SESSION['error'] = "Error en decodificar el JSON: " . json_last_error_msg();
        header('Location: llistacanc.php');
        exit;
    }
} else {
    $_SESSION['error'] = "L'arxiu JSON no existeix.";
    header('Location: llistacanc.php');
    exit;
}

// Verifica que $songs sigui un array abans de continuar
if (!is_array($songs)) {
    $songs = [];  // Si la decodificació ha fallat, assegura't que sigui un array buit
}

// Eliminar cançó del JSON i arxius del servidor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['song_index'])) {
    $songIndex = (int)$_POST['song_index'];  // Índex de la cançó a eliminar

    if (isset($songs[$songIndex])) {
        $song = $songs[$songIndex];

        // Construeix rutes completes per als arxius de la cançó i la caràtula
        $coverPath = __DIR__ . '/' . $song['cover'];
        $musicPath = __DIR__ . '/uploads/music/' . $song['music'];

        // Elimina l'arxiu de la caràtula si existeix
        if (file_exists($coverPath)) {
            unlink($coverPath);
        } else {
            $_SESSION['error'] = "No s'ha trobat l'arxiu de la caràtula: " . htmlspecialchars($song['cover']);
        }

        // Elimina l'arxiu de la música si existeix
        if (file_exists($musicPath)) {
            unlink($musicPath);
        } else {
            $_SESSION['error'] = "No s'ha trobat l'arxiu de la cançó: " . htmlspecialchars($song['music']);
        }

        // Elimina la cançó de l'array
        unset($songs[$songIndex]);

        // Reindexa l'array de cançons
        $songs = array_values($songs);

        // Guarda el nou contingut a l'arxiu JSON
        if (file_put_contents($jsonFile, json_encode($songs, JSON_PRETTY_PRINT)) === false) {
            $_SESSION['error'] = "No s'ha pogut guardar l'arxiu JSON actualitzat.";
        } else {
            $_SESSION['success'] = "La cançó s'ha eliminat correctament.";
        }
    } else {
        $_SESSION['error'] = "Índex de cançó no vàlid.";
    }

    // Redirigeix de nou a la pàgina de llistat de cançons
    header('Location: llistacanc.php');
    exit;
} else {
    $_SESSION['error'] = "Sol·licitud no vàlida.";
    header('Location: llistacanc.php');
    exit;
}
