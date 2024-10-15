<?php
// Mostrar errors per a depuració
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$jsonFile = 'basecanc.json';
$songs = [];

// Carregar l'arxiu JSON si existeix
if (file_exists($jsonFile)) {
    $jsonContent = file_get_contents($jsonFile);
    $songs = json_decode($jsonContent, true);  // Decodificar com a array associatiu

    if (json_last_error() !== JSON_ERROR_NONE) {
        $_SESSION['error'] = "Error en decodificar el JSON: " . json_last_error_msg();
    }
} else {
    $_SESSION['error'] = "L'arxiu JSON no existeix.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llistat de Cançons</title>
    <link rel="stylesheet" href="/estils/style.css">
</head>

<body class="llista-cancions">


    <nav class="nav2">
        <a href="index.html">
            <span class="tabler--home-filled"></span>
        </a>

        <a href="formacanco.html">
            <span class="streamline--song-recommendation-solid"></span>
        </a>
    </nav>


    <?php
    // Carregar l'arxiu JSON
    $jsonFile = 'basecanc.json';

    if (file_exists($jsonFile)) {
        // Llegir el contingut de l'arxiu JSON
        $jsonData = file_get_contents($jsonFile);
        if ($jsonData === false) {
            // Mostrar un error si no es pot llegir l'arxiu
            echo "<li>Error: No es pot llegir l'arxiu JSON.</li>";
        } else {
            // Decodificar el JSON a un array associatiu
            $songs = json_decode($jsonData, true);
            if ($songs === null) {
                // Mostrar un error si el JSON està mal formatat
                echo "<li>Error: L'arxiu JSON està mal formatat.</li>";
            }
        }
    } else {
        // Mostrar un missatge si l'arxiu no existeix
        echo "<li>Error: L'arxiu JSON no existeix.</li>";
        $songs = [];
    }

    // Mostrar cançons des de l'arxiu JSON
    if (empty($songs)): ?>
        <li>No hi ha cançons disponibles.</li>
        <?php else:
        // Inicialitzar el comptador
        $count = 1;

        foreach ($songs as $index => $song):
            // Assegurar-se que cada camp està present
            $title = isset($song['title']) ? htmlspecialchars($song['title']) : 'Títol no disponible';
            $artist = isset($song['artist']) ? htmlspecialchars($song['artist']) : 'Artista no disponible';
            $cover = isset($song['cover']) ? htmlspecialchars($song['cover']) : '';
            $id = isset($song['id']) ? htmlspecialchars($song['id']) : '';
            $music = isset($song['music']) ? htmlspecialchars($song['music']) : '';
        ?>
            <div class="llistac">
                <div class="llcancons">
                    <?= $title ?><br>
                    Artista: <?= $artist ?>
                </div>

                <div class="llimatge">
                    <?php if ($cover): ?>
                        <img src="<?= $cover ?>" alt="Caràtula" class="img-llista">
                    <?php else: ?>
                        No disponible
                    <?php endif; ?>
                </div>


                <div class="botonsllista">

                    <!-- Formulari per jugar -->
                    <form action="joc.php?id=<?= urlencode($id) ?>" method="post">
                        <input type="hidden" name="music" value="<?= $music ?>">
                        <button class='bllista' type="submit">Jugar</button>
                    </form>

                    <!-- Formulari per editar la cançó -->
                    <form action="editcan.php?title=<?= urlencode($title) ?>&artist=<?= urlencode($artist) ?>" method="post">
                        <input type="hidden" name="song_index" value="<?= $index ?>">
                        <button class='bllista' type="submit">Editar</button>
                    </form>

                    <!-- Formulari per eliminar la cançó -->
                    <form action="eliminarcanco.php" method="post">
                        <input type="hidden" name="song_index" value="<?= $index ?>">
                        <button class='bllista' type="submit">Eliminar Cançó</button>
                    </form>
                </div>
            </div>
            </div>



            <?php $count++; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Mostrar missatges d'èxit -->
    <?php if (isset($_SESSION['success'])): ?>
        <?php if (is_array($_SESSION['success'])): ?>
            <?php foreach ($_SESSION['success'] as $message): ?>
                <li><?= $message ?></li>
            <?php endforeach; ?>
        <?php else: ?>
            <li><?= $_SESSION['success'] ?></li>
        <?php endif; ?>
        <?php unset($_SESSION['success']); // Netejar missatges després de mostrar-los 
        ?>
    <?php endif; ?>

    <!-- Mostrar missatges d'error -->
    <?php if (isset($_SESSION['error'])): ?>
        <li style="color:red;"><?= $_SESSION['error'] ?></li>
        <?php unset($_SESSION['error']); // Netejar missatge d'error després de mostrar-lo 
        ?>
    <?php endif; ?>
    </div>


</body>

</html>