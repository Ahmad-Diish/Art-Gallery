<?php
require_once("../Homepage/header.php");
require_once("../Datenbank/artistManager.php");
require_once("../Datenbank/artworkManager.php");

?>
<style>
    .error-container {
        border: 2px solid lightcoral;
        padding: 20px;
        margin: 50px auto;
        background-color: #f9f0e1;
        text-align: center;
        max-width: 1000px;
        font-family: 'Arial', sans-serif;
        margin-bottom: 300px;
        margin-top: 250px;
    }

    .error-container h2 {
        color: #a75b25;
        font-size: 24px;
        margin-bottom: 10px;
    }

    .error-container p {
        color: #4d4d4d;
        font-size: 18px;
        margin-bottom: 20px;
    }

    .error-container button {
        padding: 10px 20px;
        color: #fff;
        background-color: #a75b25;
        border: none;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
    }

    .error-container button:hover {
        background-color: #8c451a;
    }
</style>
<?php

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
try {
    if (!is_numeric($artistID) || $artistID <= 0) {
        handleError();
        exit;
    }
    $artist = $artistManager->getArtist($artistID);
    $artworks = $artistManager->getArtworks($artistID);
} catch (Exception $e) {
    error_log('Error fetching artist data: ' . $e->getMessage());
    handleError();

    exit;
}

function handleError()
{
?>
    <div class="error-container">
        <h2>Fehler</h2>
        <p>Entschuldigung, es gab einen Fehler beim Abrufen der Künstlerinformationen. Bitte versuchen Sie es später erneut.</p>
        <button onclick="window.location.href='../Pages/artists.php'">Zurück zu den Künstler Galerie</button>
    </div>
<?php
    require("../Homepage/footer.php");
}
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