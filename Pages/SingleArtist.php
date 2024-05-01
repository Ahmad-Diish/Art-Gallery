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


//* wenn die Bewertung ausgeführt wird , wird die Webseite nochmal mit Parameter artistId geladen , um die Fehler zu vermeiden
$artistID = isset($_GET["parameter"]) ? $_GET["parameter"] : null;

//* wenn die das Kunstwerk in displayArtistSingle gelöscht  wird , wird die Webseite nochmal mit Parameter artistId geladen , um die Fehler zu vermeiden
//$artistID = isset($_GET["action"]) ? $_GET["artistId"] : null;


// Überprüfe, ob die artistID im URL-Parameter vorhanden ist
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    //Beim Reload wegen Hinzufügen/Entfernen in/von Favourite
    $artistID = isset($_GET["parameter"]) ? $_GET["parameter"] : null;

    // Überprüfe, ob die artworkID im URL-Parameter vorhanden ist
    if (isset($_GET['artistID'])) {

        $artistID = $_GET['artistID'];
    }
    // Verarbeitung der GET-Anfrage für das Hinzufügen/Entfernen von Favoriten.
    if (isset($_GET['action']) && ($_GET['action'] == 'addFavoriteArtist')) {

        $artistID = $_GET['artistId'];

        // Fügen das Artist zu den Favoriten hinzu, wenn es noch nicht vorhanden ist

        if (!in_array($artistID, $_SESSION['favorite_artists'])) {

            // hierfür Hinzufügen eines Elementes Ende der Liste
            $_SESSION['favorite_artists'][] = $artistID;

            exit();
        }
    }

    //Artwork Favourite Hinzufügen/Entfernen
    if (isset($_GET['artworkId'])) {

        $artworkId = $_GET['artworkId'];

        if (isset($_GET['action']) && ($_GET['action'] == 'addFavoriteArtwork')) {

            if (!in_array($artworkId, $_SESSION['favorite_artworks'])) {

                // hier für Hinzufügen eines Elementes Ende der Liste

                exit();
            }

            $artwork = $artworkRepository->getArtwork($artworkId);

            $artistID = $artwork->getArtistId();
        }
    } else {

        echo "<p>Fehler: Künstler-ID nicht angegeben.</p>";
    }
}

$artist = $artistRepository->getArtist($artistID);
$artworks = $artistRepository->getArtworks($artistID);


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
}



?>
<singleArtist>

    <body>
        <div class="container mt-4";>
            <?php
            $artist->outputSingleArtist();
            //hier wurden die Kunstwerke von jedem Künstler gezeigt    
            foreach ($artworks as $artwork) {
                $artwork->outputArtworks();
            }
            echo '</div>'; //Ende der Row für Bilder
            ?>

        </div>
    </body>
</singleArtist>

<?php
require("../Homepage/footer.php");
?>