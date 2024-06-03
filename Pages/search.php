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

    <?php
    if (isset($_POST['submit-search'])) {
        $searchQuery = $_POST['searchQuery'] ?? '';

        // Suche nach Künstlern
        $artistResults = Artist::searchArtists($searchQuery);

        if (!empty($artistResults)) {
            echo "<h2>Künstler</h2>";
            foreach ($artistResults as $artist) {
                $artist->outputArtist();
            }
        } else {
            echo "<p>Keine Künstler gefunden.</p>";
        }

        // Suche nach Kunstwerken
        $artworkResults = Artwork::searchArtworks($searchQuery);

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










