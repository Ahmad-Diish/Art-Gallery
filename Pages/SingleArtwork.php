<?php
require_once("../Homepage/header.php");
require_once("../Datenbank/artworkRepository.php");
require_once("../Datenbank/artwork.php");
// Erstellen einer neuen Datenbankverbindung und einer ArtistRepository-Instanz.

$conn = new Datenbank();
$artworkRepository = new ArtworkRepository($conn);

$isLoggedIn = false;
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {

    $isLoggedIn = true;
}

$modalOpen = isset($_GET['modal']) && $_GET['modal'] == 'ja';

//* wenn die Bewertung ausgeführt wird , wird die Webseite nochmal mit Parameter artworkId geladen , um die Fehler zu vermeiden
//$artworkID = isset($_GET["parameter"]) ? $_GET["parameter"] : null;

//* wenn der Benutzer auf dem Kunstwerk anklickt , wird der Kunstwerk allein gezeigt . 
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    //Beim Reload wegen Hinzufügen/Entfernen in/von Favourite
    $artworkID = isset($_GET["parameter"]) ? $_GET["parameter"] : null;

    // Überprüfe, ob die artworkID im URL-Parameter vorhanden ist
    if (isset($_GET['artworkID'])) {

        $artworkID = $_GET['artworkID'];
    }
    if (isset($_GET['action']) && $_GET["action"] == "addFavoriteArtwork") {
       
        $artworkID = $_GET['ArtworkId'];
        
            // das Kunstwerk zu den Favoriten hinzu, wenn es noch nicht vorhanden ist
            if (!in_array($artworkID, $_SESSION['favorite_artworks'])) {

                // hier für Hinzufügen eines Elementes Ende der Liste

                $_SESSION['favorite_artworks'][] = $artworkID;

                umleitenMitParameter($_SERVER["PHP_SELF"],$artworkID);
            }
    }
    if (isset($_GET['action']) && ($_GET['action'] == 'removeFavoriteArtwork')) {

        $artworkID = $_GET['ArtworkId'];

        removeFavoriteArtwork($artworkID);

        umleitenMitParameter($_SERVER["PHP_SELF"],$artworkID);

        exit();
    }

} else {

    echo "<p>Fehler: Kunstwerk-ID nicht angegeben.</p>";
}

$artwork = $artworkRepository->getArtwork($artworkID);
?>

<singleArtwork>
    <body>
        <div class="container mt-4">
            <?php
            //* hier wurde die Funktion outSingleArtwork von Klasse Artwork gerufen , um das Kunstwerk allein zu zeigen
            $artwork->outputSingleArtwork();
            ?>
        </div>
        <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="errorModalLabel">Fehler</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo 'Sie sind noch nicht angemeldet, bitte melden Sie sich  an' ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary button_style" data-bs-dismiss="modal">schließen</button>

                    </div>
                </div>
            </div>
        </div>

    </body>
</singleArtwork>

<?php
require_once("../Homepage/footer.php");
?>