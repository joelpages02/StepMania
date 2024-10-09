<?php
// Archivo JSON
$jsonFile = 'basecanc.json';

// Leer el contenido del archivo JSON
$jsonData = file_get_contents($jsonFile);

// Decodificar el JSON en un array asociativo de PHP
$songs = json_decode($jsonData, true);

// Obtener el ID de la canción desde la URL
$title = isset($_GET['title']) ? $_GET['title'] : ' ';
$artist = isset($_GET['artist']) ? $_GET['artist']: ' ';

// Obtener la canción seleccionada según el ID
$selectedSong = isset($songs[$title]) ? $songs[$title] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cançó</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <video autoplay muted loop class="bg-video">
        <source src="img/fondo.mp4" type="video/mp4">
    </video>

    <!--<header class="header2">
        <h1>Editar Cançó</h1>
    </header>!-->

    <nav class="nav2">
        <a href="index.html">
            <span class="tabler--home-filled"></span>
        </a>
        <a href="llistacanc.php">
            <span class="streamline--song-recommendation-solid"></span>
        </a>
    </nav>

    <!-- Formulario de edición -->
    <form action="guardarcambis.php" method="post" enctype="multipart/form-data">
        <div class="afegircan">
            <ul>
                <li><input type="text" name="titol" placeholder="Títol de la cançó" value="<?= $title ?>"><br></li>
                <li><input type="text" name="artista" placeholder="Artista" value="<?= $artist ?>"><br></li>
                <div class="file-upload">
                    <label for="musica" class="custom-file-label">Select music</label>
                    <input type="file" id="musica" name="musica" accept="audio/*" class="file-input" required>
                </div>
                <div class="file-upload">
                    <label for="musica" class="custom-file-label">Select Imatge</label>
                    <input type="file" id="musica" name="musica" accept="audio/*" class="file-input" required>
                </div>
                <div class="file-upload">
                    <label for="musica" class="custom-file-label">Select TXT</label>
                    <input type="file" id="musica" name="musica" accept="audio/*" class="file-input" required>
                </div>
                <li><textarea name="descripcio" rows="4" cols="50" placeholder="Descripció..." <?php echo isset($selectedSong['description']) ? htmlspecialchars($selectedSong['description']) : ''; ?>></textarea><br></li>
                <li><input type="submit" class="enviar" value="Guardar Canvis"></li>
            </ul>
        </div>
    </form>
    <!--<form action="guardar_cambis.php" method="post" enctype="multipart/form-data" class="afegircan">
        <div>
            <label for="title">Títol de la cançó:</label>
            <input type="text" id="title" name="title" class="input-field"<br>

            <label for="artist">Artista:</label>
            <input type="text" id="artist" name="artist" class="input-field"<br>

            <label for="fmusic">Subir nueva música:</label>
            <input type="file" id="fmusic" name="fmusic" class="input-field" accept="audio/*"><br>

            <label for="fcarat">Caràtula actual:</label><br>
            <input type="file" id="fcarat" name="fcarat" class="input-field" accept="image/*"><br>

            <label for="fjoc">Fitxer de joc nou:</label>
            <input type="file" id="fjoc" name="fjoc" class="input-field" accept="joc/*"><br>

            <label for="descripcio">Descripció:</label><br>
            <textarea id="descripcio" name="descripcio" class="input-field" rows="4" cols="50"></textarea><br>

            <input type="submit" class="enviar" value="Guardar Canvis">
        </div>
    </form>!-->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');

            form.addEventListener('submit', function (event) {
                const titol = document.querySelector('input[name="title"]').value;
                const artista = document.querySelector('input[name="artist"]').value;
                const fmusic = document.querySelector('input[name="fmusic"]').files[0];
                const fcarat = document.querySelector('input[name="fcarat"]').files[0];
                const descripcio = document.querySelector('textarea[name="descripcio"]').value;

                // Validar campos
                if (!titol || !artista || !fmusic || !fcarat) {
                    alert('Todos los campos son obligatorios.');
                    event.preventDefault();  // Evitar el envío del formulario
                    return;
                }

                // Validar tipo de archivo de música
                const allowedMusicTypes = ['audio/mpeg', 'audio/wav'];
                if (!allowedMusicTypes.includes(fmusic.type)) {
                    alert('Solo se permiten archivos de música en formato MP3 o WAV.');
                    event.preventDefault();  // Evitar el envío del formulario
                    return;
                }
            });
        });
    </script>

</body>

</html>
