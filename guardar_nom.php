<?php
// Ruta al fitxer JSON on es guarden les puntuacions
$jsonFile = 'noms.json';

// Verifica si el fitxer JSON existeix, si no, el crea
if (!file_exists($jsonFile)) {
    file_put_contents($jsonFile, '[]');
}

// Carregar el contingut actual del fitxer JSON
$jsonData = file_get_contents($jsonFile);
$scores = json_decode($jsonData, true); // Decodifica el JSON en un array associatiu

// Verificar si s'han rebut el nom i la puntuació
if (isset($_POST['name']) && isset($_POST['score'])) {
    $name = htmlspecialchars($_POST['name']); // Escapar el nom per seguretat
    $score = intval($_POST['score']); // Assegura't que la puntuació sigui un número enter

    // Afegir la nova puntuació a l'array
    $scores[] = [
        'name' => $name,
        'score' => $score
    ];

    // Ordenar l'array per 'score' en ordre descendent perquè el jugador amb més punts estigui primer
    usort($scores, function ($a, $b) {
        return $b['score'] - $a['score'];
    });

    // Limitar als 10 primers punts
    $scores = array_slice($scores, 0, 10);

    // Guardar l'array actualitzat de nou en el fitxer JSON
    file_put_contents($jsonFile, json_encode($scores, JSON_PRETTY_PRINT));
    
    // Redirigir a la pàgina de classificació o una altra pàgina
    header('Location: classificacio.php');
    exit();
} else {
    echo "No s'han rebut les dades correctament.";
}
?>
