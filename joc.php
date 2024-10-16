<?php

// Funció per desar les dades del jugador (nom i puntuació) en un fitxer JSON
function saveData($name, $score, $dataFile = 'noms.json') {
    // Carrega les dades existents del fitxer JSON
    $data = loadData($dataFile);
    // Afegix o actualitza la puntuació del jugador
    $data[$name] = $score;
    // Desa les dades actualitzades al fitxer JSON
    storeData($data, $dataFile);
    // Desa les cookies amb el nom i la puntuació, que expiren en 30 dies
    setcookie('name', $name, time() + (86400 * 30), "/"); // 86400 = 1 dia
    setcookie('score', $score, time() + (86400 * 30), "/");
}

// Funció per carregar les dades des d'un fitxer JSON
function loadData($dataFile) {
    // Comprova si el fitxer existeix
    if (!file_exists($dataFile)) {
        return []; // Retorna un array buit si el fitxer no existeix
    }
    // Llegeix el contingut del fitxer JSON
    $json = file_get_contents($dataFile);
    // Retorna les dades com un array associatiu
    return json_decode($json, true);
}

// Funció per emmagatzemar dades en un fitxer JSON
function storeData($data, $dataFile) {
    // Codifica les dades a format JSON
    $json = json_encode($data, JSON_PRETTY_PRINT);
    // Desa el JSON al fitxer especificat
    file_put_contents($dataFile, $json);
}

// Comprova si s'ha enviat el formulari i desa les dades
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['score'])) {
    saveData($_POST['name'], $_POST['score']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StepMania</title>
    <link rel="stylesheet" href="/estils/style.css">
</head>

<body>

    <nav class="nav2">
        <a href="index.html">
            <span class="tabler--home-filled"></span>
        </a>
    </nav>

    <?php
    $jsonFile = 'basecanc.json'; // Fitxer JSON que conté les dades de les cançons
    $title = ''; // Inicialitza la variable del títol
    $music = ''; // Inicialitza la variable de la música
    $cover = ''; // Inicialitza la variable de la portada
    $artist = ''; // Inicialitza la variable de l'artista
    $timingData = ''; // Inicialitza la variable de les dades de temporització
    $score = 0; // Inicialitza la puntuació

    // Comprova si el fitxer JSON existeix
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile); // Llegeix el contingut del fitxer JSON
        $songs = json_decode($jsonData, true); // Decodifica el JSON a un array associatiu

        // Comprova que les cançons no siguin nuls o buides
        if ($songs !== null && !empty($songs)) {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Obté l'ID de la cançó de la URL

            // Cerca la cançó amb l'ID especificat
            foreach ($songs as $song) {
                if ($song['id'] === $id) {
                    $title = htmlspecialchars($song['title']); // Desa el títol de la cançó
                    $music = htmlspecialchars($song['music']); // Desa el nom de la música
                    $cover = htmlspecialchars($song['cover']); // Desa la imatge de la portada
                    $artist = htmlspecialchars($song['artist']); // Desa el nom de l'artista
                    $timingData = htmlspecialchars($song['timingData']); // Desa les dades de temporització
                    break; // Atura el bucle si la cançó es troba
                }
            }
        } else {
            $title = 'No hi ha música disponible.'; // Missatge si no hi ha cançons
        }
    }

    // Comprova si timingData no és buit
    $timingResult = []; // Inicialitza l'array per les dades de temporització
    if (!empty($timingData) && file_exists($timingData)) {
        $lines = file_get_contents($timingData); // Llegeix el fitxer de temporització
        $c = explode(PHP_EOL, $lines); // Divideix les línies del fitxer
        // Afegeix les dades de temporització a l'array timingResult
        foreach ($c as $value) {
            $timingResult[] = explode('#', $value); // Divideix cada línia per '#'
        }
    } else {
        // Alerta si el fitxer de temporització no existeix o la ruta és incorrecta
        echo "<script>alert('El fitxer de temporització no existeix o la ruta és incorrecta: $timingData');</script>";
    }
    ?>

    <div class="jugar">
        <div class="sonant">
            <div class="csonant">
                <img class="imgsonant" src="<?= $cover ?>"> <!-- Mostra la portada de la cançó -->
                <a><?= $title ?></a> <!-- Mostra el títol de la cançó -->
                <a>Artista: <?= $artist ?></a> <!-- Mostra l'artista de la cançó -->
                <audio id="audio" controls autoplay>
                    <source src="uploads/music/<?= $music ?>" type="audio/mpeg"> <!-- Reproductor d'àudio -->
                </audio>
            </div>
        </div>

        <div class="pjoc">
            <div class="barra-progreso">
                <progress id="file" max="100" value="0">0%</progress> <!-- Barra de progrés -->
                <span id="progress-text">0%</span> <!-- Text per mostrar el progrés -->
            </div>
            <div class="fletxes">
                <!-- Imatges de les fletxes -->
                <a><img src="img/fletxa_esquerra.png" class="imgfletxa"></a>
                <a><img src="img/fletxa_adalt.png" class="imgfletxa"></a>
                <a><img src="img/fletxa_baix.png" class="imgfletxa"></a>
                <a><img src="img/fletxa_dreta.png" class="imgfletxa"></a>
            </div>
        </div>

        <div class="punts">
            <a>Punts:</a> <!-- Etiqueta per la puntuació -->
            <input type="text" id="nameInput" placeholder="Introduce tu nombre aquí" required> <!-- Camp per introduir el nom -->
            <button id="submitName" style="display:none;">Enviar Nombre</button> <!-- Botó per enviar el nom -->
        </div>
    </div>

    <!-- Formulari per desar el nom i la puntuació -->
    <form id="nombreForm" action="guardar_nom.php" method="POST" style="display: none;">
        <input type="hidden" name="name" id="hiddenNameInput"> <!-- Input ocult per desar el nom -->
        <input type="hidden" name="score" id="scoreInput" value="<?= $score ?>"> <!-- Input ocult per desar la puntuació -->
    </form>

    <script src="joc_moviment.js"></script>
    <script>
        const audioElement = document.getElementById('audio'); // Element d'àudio
        const submitButton = document.getElementById('submitName'); // Botó per enviar el nom
        const nameInput = document.getElementById('nameInput'); // Input per nom
        const progressBar = document.getElementById('file'); // Barra de progrés
        const progressText = document.getElementById('progress-text'); // Text per mostrar el progrés
        const puntuacionInput = document.getElementById('scoreInput'); // Input per la puntuació
        let gameEnded = false; // Variable per controlar si el joc ha acabat
        let score = 0; // Inicialitza la puntuació

        // Funció per actualitzar la barra de progrés
        function updateProgress() {
            if (!gameEnded && audioElement.duration) {
                const currentTime = audioElement.currentTime; // Temps actual de l'àudio
                const duration = audioElement.duration; // Durada total de l'àudio
                const percentage = (currentTime / duration) * 100; // Percentatge de progrés

                progressBar.value = percentage; // Actualitza el valor de la barra de progrés
                progressText.textContent = Math.floor(percentage) + '%'; // Actualitza el text de progrés

                // Comprova si l'àudio ha acabat
                if (percentage >= 100) {
                    gameEnded = true; // Marca el joc com a acabat
                    puntuacionInput.value = score; // Assigna la puntuació
                    submitButton.style.display = 'block'; // Mostra el botó de guardar
                    audioElement.pause(); // Pausa l'àudio
                    audioElement.currentTime = 0; // Reinicia el temps d'àudio
                    document.querySelectorAll('.imgfletxa').forEach(arrow => {
                        arrow.style.display = 'none'; // Amaga totes les fletxes
                    });
                }
            }
            requestAnimationFrame(updateProgress); // Sol·licita la següent actualització
        }

        // Inicia l'actualització de la barra de progrés quan l'àudio comença a reproduir-se
        audioElement.addEventListener('play', () => {
            updateProgress(); 
        });

        // Gestiona quan l'àudio acaba
        audioElement.addEventListener('ended', () => {
            gameEnded = true; // Marca el joc com a acabat
            puntuacionInput.value = score; // Assigna la puntuació
            submitButton.style.display = 'block'; // Mostra el botó de guardar
            progressText.textContent = '100%'; // Actualitza el text de progrés a 100%
            progressBar.value = 100; // Actualitza la barra de progrés a 100%
            document.querySelectorAll('.imgfletxa').forEach(arrow => {
                arrow.style.display = 'none'; // Amaga totes les fletxes
            });
        });

        // Gestiona l'esdeveniment de clic al botó d'enviament
        submitButton.addEventListener('click', () => {
            const userName = nameInput.value.trim(); // Obté el nom introduït
            if (userName) {
                document.getElementById('hiddenNameInput').value = userName; // Desa el nom a l'input ocult
                document.getElementById('nombreForm').submit(); // Envia el formulari
            } else {
                alert('Per favor, introdueix el teu nom.'); // Alerta si el nom està buit
            }
        });

    </script>

</body>

</html>
