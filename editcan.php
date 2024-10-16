<?php
// Defineix la ubicació del fitxer JSON amb les dades de les cançons
$jsonFile = 'basecanc.json';

// Llegeix el contingut del fitxer JSON
$jsonData = file_get_contents($jsonFile);

// Decodifica el JSON a un array associatiu de PHP
$songs = json_decode($jsonData, true);

// Obté el títol i l'artista de la cançó des de la URL
$title = isset($_GET['title']) ? $_GET['title'] : ' ';
$artist = isset($_GET['artist']) ? $_GET['artist'] : ' ';

// Obté la cançó seleccionada segons el títol
$selectedSong = isset($songs[$title]) ? $songs[$title] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cançó</title>
    <link rel="stylesheet" href="/estils/style.css">
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

    <form action="guardarcambis.php" method="post" enctype="multipart/form-data">
    <div class="afegircan">
        <ul>
            <!-- Campo para el título de la canción -->
            <li><input type="text" name="titol" placeholder="Títol de la cançó" value="<?= htmlspecialchars($title) ?>"><br></li>
            <!-- Campo para el artista -->
            <li><input type="text" name="artista" placeholder="Artista" value="<?= htmlspecialchars($artist) ?>"><br></li>
            <!-- Campos para la subida de archivos -->
            <div class="file-upload">
                <label for="musica" class="custom-file-label">Select music</label>
                <input type="file" id="musica" name="musica" accept="audio/*" class="file-input">
            </div>
            <div class="file-upload">
                <label for="imatge" class="custom-file-label">Select Imatge</label>
                <input type="file" id="imatge" name="imatge" accept="image/*" class="file-input">
            </div>
            <div class="file-upload">
                <label for="lletra" class="custom-file-label">Select TXT</label>
                <input type="file" id="lletra" name="lletra" accept="text/plain" class="file-input">
            </div>
            <!-- Campo para la descripción -->
            <li><textarea name="descripcio" rows="4" cols="50" placeholder="Descripció..."><?= isset($selectedSong['description']) ? htmlspecialchars($selectedSong['description']) : ''; ?></textarea><br></li>
            <!-- Botón para guardar los cambios -->
            <li><input type="submit" class="enviar" value="Guardar Canvis"></li>
        </ul>
    </div>
</form>


    <script>
        // Script per validar el formulari abans de l'enviament
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');

            // Afegeix un escoltador d'esdeveniments al formulari per a l'acció d'enviament
            form.addEventListener('submit', function(event) {
                // Obté el valor del camp del títol de la cançó
                const titol = document.querySelector('input[name="title"]').value;
                // Obté el valor del camp de l'artista
                const artista = document.querySelector('input[name="artist"]').value;
                // Obté el primer arxiu seleccionat per al camp de música
                const fmusic = document.querySelector('input[name="fmusic"]').files[0];
                // Obté el primer arxiu seleccionat per al camp de la caràtula
                const fcarat = document.querySelector('input[name="fcarat"]').files[0];
                // Obté el valor del camp de la descripció
                const descripcio = document.querySelector('textarea[name="descripcio"]').value;


                // Validar camps
                if (!titol || !artista) {
                    alert('Tots els camps són obligatoris.');
                    event.preventDefault(); // Evitar l'enviament del formulari
                    return;
                }

                // Validar tipus d'arxiu de música
                const allowedMusicTypes = ['audio/mpeg', 'audio/wav'];
                if (!allowedMusicTypes.includes(fmusic.type)) {
                    alert('Només es permeten arxius de música en format MP3 o WAV.');
                    event.preventDefault(); // Evitar l'enviament del formulari
                    return;
                }
            });
        });
    </script>

</body>

</html>