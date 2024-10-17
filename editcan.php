<?php
// Defineix la ubicació del fitxer JSON amb les dades de les cançons
$jsonFile = 'basecanc.json';

// Llegeix el contingut del fitxer JSON
$jsonData = file_get_contents($jsonFile);

// Decodifica el JSON a un array associatiu de PHP
$songs = json_decode($jsonData, true);

// Obté el títol i l'artista de la cançó des de la URL
$title = isset($_GET['title']) ? $_GET['title'] : ' '; // Si el títol no està definit, assigna un valor buit
$artist = isset($_GET['artist']) ? $_GET['artist'] : ' '; // Si l'artista no està definit, assigna un valor buit

// Obté la cançó seleccionada segons el títol
$selectedSong = isset($songs[$title]) ? $songs[$title] : null; // Busca la cançó pel títol, si no existeix, retorna null
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cançó</title>
    <link rel="stylesheet" href="/estils/style.css"> <!-- Enllaç al fitxer CSS per a l'estil de la pàgina -->
</head>

<body>

    <!-- Navegació secundària -->
    <nav class="nav2">
        <!-- Enllaç a la pàgina d'inici -->
        <a href="index.html">
            <!-- Icona de la casa (home) -->
            <span class="tabler--home-filled"></span>
        </a>
        <!-- Enllaç a la llista de cançons -->
        <a href="llistacanc.php">
            <!-- Icona de recomanació de cançons -->
            <span class="streamline--song-recommendation-solid"></span>
        </a>
    </nav>

    <!-- Formulari per editar la cançó -->
    <form action="guardarcambis.php" method="post" enctype="multipart/form-data">
        <div class="afegircan">
            <ul>
                <!-- Camp ocult per emmagatzemar el títol original -->
                <input type="hidden" name="original_title" value="<?= htmlspecialchars($title) ?>">

                <!-- Camp per al títol de la cançó -->
                <li><input type="text" name="titol" placeholder="Títol de la cançó" value="<?= htmlspecialchars($title) ?>"><br></li>

                <!-- Camp per a l'artista -->
                <li><input type="text" name="artista" placeholder="Artista" value="<?= htmlspecialchars($artist) ?>"><br></li>

                <!-- Camps per pujar arxius -->
                <div class="file-upload">
                    <label for="musica" class="custom-file-label">Select music</label>
                    <input type="file" id="musica" name="musica" accept="audio/*" class="file-input"> <!-- Camp per pujar un arxiu de música -->
                </div>
                <div class="file-upload">
                    <label for="imatge" class="custom-file-label">Select Imatge</label>
                    <input type="file" id="imatge" name="imatge" accept="image/*" class="file-input"> <!-- Camp per pujar una imatge -->
                </div>
                <div class="file-upload">
                    <label for="lletra" class="custom-file-label">Select TXT</label>
                    <input type="file" id="lletra" name="lletra" accept="text/plain" class="file-input"> <!-- Camp per pujar un arxiu de text -->
                </div>

                <!-- Camp per a la descripció -->
                <li><textarea name="descripcio" rows="4" cols="50" placeholder="Descripció..."><?= isset($selectedSong['description']) ? htmlspecialchars($selectedSong['description']) : ''; ?></textarea><br></li>

                <!-- Botó per guardar els canvis -->
                <li><input type="submit" class="enviar" value="Guardar Canvis"></li>
            </ul>
        </div>
    </form>

    <!-- Script per validar el formulari abans de l'enviament -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');

            // Afegeix un escoltador d'esdeveniments al formulari per a l'acció d'enviament
            form.addEventListener('submit', function(event) {
                // Obté el valor dels camps del formulari
                const titol = document.querySelector('input[name="titol"]').value;
                const artista = document.querySelector('input[name="artista"]').value;
                const fmusic = document.querySelector('input[name="musica"]').files[0]; // Arxiu de música
                const fcarat = document.querySelector('input[name="imatge"]').files[0]; // Arxiu d'imatge

                // Obté el valor del camp de la descripció
                const descripcio = document.querySelector('textarea[name="descripcio"]').value;

                // Validar si el tipus d'arxiu de música és vàlid
                if (fmusic && !allowedMusicTypes.includes(fmusic.type)) {
                    alert('Només es permeten arxius de música en format MP3 o WAV.');
                    event.preventDefault(); // Atura l'enviament del formulari si l'arxiu no és vàlid
                    return;
                }

                // Validar camps obligatoris
                if (!titol || !artista) {
                    alert('Tots els camps són obligatoris.');
                    event.preventDefault(); // Atura l'enviament si els camps no estan omplerts
                    return;
                }

                // Validar tipus d'arxiu de música
                const allowedMusicTypes = ['audio/mpeg', 'audio/wav'];
                if (!allowedMusicTypes.includes(fmusic.type)) {
                    alert('Només es permeten arxius de música en format MP3 o WAV.');
                    event.preventDefault(); // Atura l'enviament si el tipus d'arxiu no és vàlid
                    return;
                }
            });
        });
    </script>

</body>

</html>
