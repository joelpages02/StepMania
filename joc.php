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
    $jsonFile = 'basecanc.json';
    $title = '';
    $music = '';
    $cover = '';
    $artist = '';
    $timingData = '';
    $score = 0; // Inicializa la puntuación

    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $songs = json_decode($jsonData, true);

        if ($songs !== null && !empty($songs)) {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            foreach ($songs as $song) {
                if ($song['id'] === $id) {
                    $title = htmlspecialchars($song['title']);
                    $music = htmlspecialchars($song['music']);
                    $cover = htmlspecialchars($song['cover']);
                    $artist = htmlspecialchars($song['artist']);
                    $timingData = htmlspecialchars($song['timingData']);
                    break;
                }
            }
        } else {
            $title = 'No hi ha música disponible.';
        }
    }

    // Comprobar si timingData no es vacío
    $timingResult = [];
    if (!empty($timingData) && file_exists($timingData)) {
        $lines = file_get_contents($timingData);
        $c = explode(PHP_EOL, $lines);
        foreach ($c as $value) {
            $timingResult[] = explode('#', $value);
        }
    } else {
        echo "<script>alert('El fitxer de temporització no existeix o la ruta és incorrecta: $timingData');</script>";
    }
    ?>

    <div class="jugar">
        <div class="sonant">
            <div class="csonant">
                <img class="imgsonant" src="<?= $cover ?>">
                <a><?= $title ?></a>
                <a>Artista: <?= $artist ?></a>
                <audio id="audio" controls autoplay>
                    <source src="uploads/music/<?= $music ?>" type="audio/mpeg">
                </audio>
            </div>
        </div>

        <div class="pjoc">
            <div class="barra-progreso">
                <progress id="file" max="100" value="0">0%</progress>
                <span id="progress-text">0%</span>
            </div>
            <div class="fletxes">
                <a><img src="img/fletxa_esquerra.png" class="imgfletxa"></a>
                <a><img src="img/fletxa_adalt.png" class="imgfletxa"></a>
                <a><img src="img/fletxa_baix.png" class="imgfletxa"></a>
                <a><img src="img/fletxa_dreta.png" class="imgfletxa"></a>
            </div>
        </div>

        <div class="punts">
            <a>Punts:</a>
            <input type="text" id="nameInput" placeholder="Introduce tu nombre aquí" required>
            <button id="submitName" style="display:none;">Enviar Nombre</button>
        </div>
    </div>

    <form id="nombreForm" action="guardar_nom.php" method="POST" style="display: none;">
        <input type="hidden" name="name" id="hiddenNameInput">
        <input type="hidden" name="score" id="scoreInput" value="<?= $score ?>">
    </form>

    <script src="joc_moviment.js"></script>
    <script>
        const audioElement = document.getElementById('audio');
        const submitButton = document.getElementById('submitName');
        const nameInput = document.getElementById('nameInput');
        const progressBar = document.getElementById('file');
        const progressText = document.getElementById('progress-text');
        const puntuacionInput = document.getElementById('scoreInput');
        let gameEnded = false; // Para controlar si el juego ha terminado
        let score = 0; // Inicializar la puntuación

        function updateProgress() {
            if (!gameEnded && audioElement.duration) {
                const currentTime = audioElement.currentTime;
                const duration = audioElement.duration;
                const percentage = (currentTime / duration) * 100;

                progressBar.value = percentage;
                progressText.textContent = Math.floor(percentage) + '%';

                if (percentage >= 100) {
                    gameEnded = true;
                    puntuacionInput.value = score; // Asigna la puntuación
                    submitButton.style.display = 'block'; // Mostrar el botón de guardar
                    audioElement.pause();
                    audioElement.currentTime = 0;
                    document.querySelectorAll('.imgfletxa').forEach(arrow => {
                        arrow.style.display = 'none'; // Oculta todas las flechas
                    });
                }
            }
            requestAnimationFrame(updateProgress); // Solicitar la siguiente actualización
        }

        audioElement.addEventListener('play', () => {
            updateProgress(); // Iniciar la actualización cuando el audio se reproduce
        });

        audioElement.addEventListener('ended', () => {
            gameEnded = true;
            puntuacionInput.value = score; // Asigna la puntuación
            submitButton.style.display = 'block'; // Mostrar el botón de guardar
            progressText.textContent = '100%';
            progressBar.value = 100;
            document.querySelectorAll('.imgfletxa').forEach(arrow => {
                arrow.style.display = 'none'; // Oculta todas las flechas
            });
        });

        submitButton.addEventListener('click', () => {
            const userName = nameInput.value.trim();
            if (userName) {
                document.getElementById('hiddenNameInput').value = userName;
                document.getElementById('nombreForm').submit();
            } else {
                alert('Por favor, introduce tu nombre.');
            }
        });
    </script>

</body>
</html>
