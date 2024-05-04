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
} else {

    echo "<p>Fehler: Kunstwerk-ID nicht angegeben.</p>";
}

$artwork = $artworkRepository->getArtwork($artworkID);
?>



<?php
require_once("../Homepage/footer.php");
?>