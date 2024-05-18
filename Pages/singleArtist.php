<?php
// Require necessary files
require_once("../Homepage/header.php");
require_once("../Datenbank/artistManager.php");
require_once("../Datenbank/artworkManager.php");


// Erstellen einer neuen Datenbankverbindung und Instanzen der Manager
$conn = new Datenbank();
$artistManager = new ArtistManager($conn);
$artist = Artist::getDefaultArtist();
$artworkManager = new ArtworkManager($conn);

// Überprüfe, ob eine Künstler-ID im URL-Parameter vorhanden ist
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Überprüfen, ob die Künstler-ID im URL-Parameter "parameter" oder "artistID" vorhanden ist
    if (isset($_GET["parameter"])) {
        $artistID = $_GET["parameter"];
    } elseif (isset($_GET["artistID"])) {
        $artistID = $_GET["artistID"];
    } else {
        $artistID = null;
    }

    // Überprüfe, ob der Parameter "action" in der URL vorhanden ist
    if (isset($_GET['action'])) {
        // Verarbeite die GET-Anfrage für das Hinzufügen von Favoriten
        if ($_GET['action'] === 'addFavoriteArtist' && $artistID) {
            // Füge den Künstler zu den Favoriten hinzu, wenn er noch nicht vorhanden ist
            if (!in_array($artistID, $_SESSION['favorite_artists'] ?? [])) {
                $_SESSION['favorite_artists'][] = $artistID;
                exit();
            }
        }
    }
}

// Stelle sicher, dass die Künstler-ID gültig ist, bevor du sie abrufst
if (!is_numeric($artistID) || $artistID <= 0) {
    echo "Ungültige Künstler-ID!";
    exit;
}

$artist = $artistManager->getArtist($artistID); // Künstlerinformationen abrufen
$artworks = $artistManager->getArtworks($artistID); // Kunstwerke des Künstlers abrufen


?>
<singleArtist>
    <style>
        /* CSS-Stile für die Seite */
    </style>

    <body>
        <div class="container mt-4">
            <?php

            // Prüfe, ob das Objekt nicht null ist, bevor du die Methode aufrufst
            if ($artist !== null) {
                $artist->outputSingleArtist(); // Gib die Informationen des Künstlers aus
            } else {
                echo "Fehler: Künstlerobjekt ist null."; // Zeige eine Fehlermeldung
            }
            ?>

            <?php foreach ($artworks as $artwork) {
              $artwork->outputArtworks(); // Gib die Informationen des Kunstwerks aus
            } ?>
        </div>
        </div>
    </body>
</singleArtist>
<?php
require("../Homepage/footer.php");
?>
