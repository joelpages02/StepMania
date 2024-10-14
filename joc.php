<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StepMania</title>
    <link rel="stylesheet" href="/StepMania/estils/style.css">
</head>

<body>

    <!-- Barra de navegació secundària -->
    <nav class="nav2">
        <!-- Enllaç a la pàgina d'inici -->
        <a href="index.html">
            <!-- Icona de la casa (home) -->
            <span class="tabler--home-filled"></span>
        </a>
    </nav>

    <?php
    // Defineix la ubicació del fitxer JSON amb les dades de les cançons
    $jsonFile = 'basecanc.json';
    $title = '';

    // Comprova si el fitxer JSON existeix
    if (file_exists($jsonFile)) {
        // Llegeix el contingut del fitxer JSON
        $jsonData = file_get_contents($jsonFile);
        // Decodifica el JSON a un array associatiu
        $songs = json_decode($jsonData, true);

        // Comprova si s'ha obtingut alguna dada i si no està buida
        if ($songs !== null && !empty($songs)) {
            // Comprova si hi ha un paràmetre 'id' a la URL
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Obté l'ID de la URL o utilitza 0 com a predeterminat

            // Comprova que l'ID estigui dins de l'interval de cançons
            foreach ($songs as $song) {
                if ($song['id'] === $id) {
                    $title = htmlspecialchars($song['title']); // Escull el títol segons l'ID seleccionat
                    $cover = htmlspecialchars($song['cover']);
                    $artist = htmlspecialchars($song['artist']);
                    break;
                }
            }
        } else {
            $title = 'No hi ha música disponible.';
        }
    }
    ?>

    <!-- Secció per jugar -->
    <div class="jugar">
        <!-- Contenidor de la cançó que sona -->
        <div class="sonant">
            <div class="csonant">
                <!-- Imatge de la coberta de la cançó -->
                <img class="imgsonant" src="<?= $cover ?>">
                <!-- Títol de la cançó -->
                <a><?= $title?></a>
                <!-- Artista de la cançó -->
                <a>Artista: <?= $artist?></a>
            </div>
        </div>

        <!-- Contenidor del joc -->
        <div class="pjoc">
            <!-- Contenidor de les fletxes -->
            <div class="fletxes">
                <!-- Imatges de les fletxes -->
                <a><img src="img/fletxa_esquerra.png" class="imgfletxa"></a>
                <a><img src="img/fletxa_adalt.png" class="imgfletxa"></a>
                <a><img src="img/fletxa_baix.png" class="imgfletxa"></a>
                <a><img src="img/fletxa_dreta.png" class="imgfletxa"></a>
            </div>
        </div>

        <!-- Contenidor dels punts -->
        <div class="punts">
            <!-- Text dels punts -->
            <a>Punts:</a>
        </div>
    </div>

</body>

</html>
