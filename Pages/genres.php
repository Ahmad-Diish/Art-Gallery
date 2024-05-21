<?php

// Einbindung der Header-Datei
require_once("../Homepage/header.php");
// Einbindung der Datei, die die Klasse GenreManager enthält
require_once("../Datenbank/genreManager.php");

// Erstellen einer neuen Datenbankverbindung
$conn = new datenbank();
// Initialisieren des GenreManagers mit der Datenbankverbindung
$genreManager = new GenreManager($conn);

?>
<!-- HTML-Bereich für Genres -->

<head>
<style>
/* CSS-Stile für den Body-Hintergrund und die Titel */
body {
    background-color: #fffbeb;
}

.title {
    text-align: center;
    color: #923f0e;
    font-family: "Goudy Stout";
    margin-TOP: 70px;
    margin-bottom: 100px;
}
</style>
</head>

<body>
<div class="container mt-5">
    <!-- Titel der Genres-Galerie -->
    <h2 class="title">Genres Galerie</h2>
    <div class="row mt-4">
    <?php
    // Aufrufen der Methode zur Anzeige aller Genres
    $genreManager->displayAllGenres();
    ?>
    </div>
</div>
</body>
</genres>

<?php
// Einbindung der Footer-Datei
require_once("../Homepage/footer.php");
?>
