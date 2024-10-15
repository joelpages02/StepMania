<?php
session_start(); // Iniciar sesión

// Nombre del archivo JSON donde se guardarán los datos
$nombreArchivo = 'noms.json';

// Comprobar si se recibe el nombre
if (isset($_POST['name'])) {
    $nombre = htmlspecialchars($_POST['name']);
    
    // Asegurarse de que score esté en la sesión
    $score = isset($_SESSION['score']) ? intval($_SESSION['score']) : 0;

    // Verificar que se esté recibiendo correctamente
    error_log("Nombre: $nombre, Score: $score"); // Para depurar en el log del servidor

    // Leer el archivo existente o crear uno nuevo
    if (file_exists($nombreArchivo)) {
        $nombres = json_decode(file_get_contents($nombreArchivo), true);
    } else {
        $nombres = [];
    }

    // Agregar el nuevo nombre y la puntuación
    $nombres[] = [
        'name' => $nombre,
        'score' => $score
    ];

    // Guardar el nuevo arreglo de nombres en el archivo JSON
    file_put_contents($nombreArchivo, json_encode($nombres));

    // Éxito al guardar
    header('Location: Classificacio.html');
    exit();
}
?>
