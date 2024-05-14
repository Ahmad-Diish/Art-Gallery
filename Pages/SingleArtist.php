<?php
// Require necessary files
require_once("../Homepage/header.php");
require_once("../Datenbank/artistRepository.php");
require_once("../Datenbank/artworkRepository.php");


//*Dieser Anwendungsfall wird initiiert, wenn ein Benutzer einen einzelnen Künstler zur Anzeige auswählt (z. B. auf einen Link klickt, der den Benutzer zu dieser Seite führt). 2. Das System zeigt Informationen zu einem einzelnen Künstler an (angegeben über die Künstler-ID, die über einen Query-String-Parameter übergeben wird). Diese Seite sollte fehlende oder ungültige Abfragezeichenfolgen-ID-Parameter behandeln, indem sie auf eine Fehlerseite umleitet. Alle Informationen in der Künstlertabelle sollten angezeigt werden. Es sollte eine Möglichkeit geben, alle Kunstwerke des Künstlers anzuzeigen. Die Miniaturansicht, der Titel und die Schaltfläche „Ansicht“ des Kunstwerks müssen Links zu diesem Kunstwerk sein (gehen Sie also zum Anwendungsfall „Einzelnes Kunstwerk anzeigen“). Diese Seite muss es dem Benutzer ermöglichen, den Künstler zu einer sitzungsbasierten Favoritenliste hinzuzufügen (d. h. zum Anwendungsfall „Zu Favoriten hinzufügen“ wechseln). In diesem Fall wird es zur Liste der bevorzugten Künstler hinzugefügt.


// Erstellen einer neuen Datenbankverbindung und einer ArtistRepository-Instanz.
$conn = new Datenbank();
$artistRepository = new ArtistRepository($conn);
$artist = Artist::getDefaultArtist();
$artworkRepository = new ArtworkRepository($conn);

// Überprüfe, ob eine Künstler-ID im URL-Parameter vorhanden ist
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Prüfe, ob die Künstler-ID im URL-Parameter "parameter" oder "artistID" vorhanden ist
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
    // Handle ungültige Künstler-ID (z. B. Fehlermeldung anzeigen)
    echo "Ungültige Künstler-ID!";
    exit;
}

$artist = $artistRepository->getArtist($artistID);
$artworks = $artistRepository->getArtworks($artistID);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
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