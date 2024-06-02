<?php
require_once("../Homepage/header.php");
require_once("../Datenbank/artistClass.php");
require_once("../Datenbank/artistManager.php");
require_once("../Datenbank/artworkClass.php");
require_once("../Datenbank/artworkManager.php");
// Datenbankverbindung
$datenbank = new Datenbank();
$datenbank->connect();
$PDO = $datenbank->getConnection();

// Setzen der Datenbankverbindung für die Artist- und Artwork-Klassen
Artist::setDatabase($PDO);
Artwork::setDatabase($PDO);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suche</title>
</head>
<body>
    <h1>Suchergebnisse</h1>
    <form class="d-flex" method="POST" action="">
        <input class="form-control me-2" type="text" name="firstName" placeholder="Vorname" aria-label="Suche">
        <input class="form-control me-2" type="text" name="lastName" placeholder="Nachname" aria-label="Suche">
        <input class="form-control me-2" type="text" name="title" placeholder="Titel" aria-label="Suche">
        <button class="btn search-btn" type="submit" name="submit-search"><i class="bi bi-search"></i></button>
    </form>

    <?php
    if (isset($_POST['submit-search'])) {
        $firstName = $_POST['firstName'] ?? '';
        $lastName = $_POST['lastName'] ?? '';
        $title = $_POST['title'] ?? '';

        // Suche nach Künstlern
        $artistResults = Artist::searchArtists($firstName, $lastName);

        if (!empty($artistResults)) {
            echo "<h2>Künstler</h2>";
            foreach ($artistResults as $artist) {
                $artist->outputArtist();
            }
        } else {
            echo "<p>Keine Künstler gefunden.</p>";
        }

        // Suche nach Kunstwerken
        $artworkResults = Artwork::searchArtworks($title);

        if (!empty($artworkResults)) {
            echo "<h2>Kunstwerke</h2>";
            foreach ($artworkResults as $artwork) {
                $artwork->outputArtworks();
            }
        } else {
            echo "<p>Keine Kunstwerke gefunden.</p>";
        }
    }
    ?>
</body>
</html>

<?php
require_once("../Homepage/footer.php");
?>







