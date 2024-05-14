<?php
require_once("header.php");
require_once("../Datenbank/artworkManager.php");
require_once("../Datenbank/artistManager.php");
require_once("carousel.php");


// Erstellen einer neuen Datenbankverbindung und einer ArtistManager-Instanz.
$conn = new Datenbank();
$artworkManager = new ArtworkManager($conn);
$artistManager = new ArtistManager($conn);

?>


<!DOCTYPE html>
<html>
<head>
    <title>Art-Gallery</title>
    <link rel="stylesheet" href="../css/style.css">
    
</head>
<body>

<!-- Your page content here -->
       <div class="container mt-5">
            <h2>Top Kunstwerk</h2>
            <div class="row mt-5" style="justify-content: space-between;">
                <!-- Top Künstler -->
                <?php
                 
                 $artworkManager->TopArtwork();
                
                ?>
            </div>
            <h2>Häufigsten rezensierten Künstler</h2>
            <div class="row mt-5" style="justify-content: space-between;">
                <!-- Top Kunstwerk -->
                <?php
                    $artistManager->displayTopArtists();
                ?>
            </div>
            <h2>Aktuellsten Rezensionen</h2>
            <div class="row mt-5">
                <!-- neueste Feedback -->
                <?php
               
                ?>
            </div>
</div>
</body>
</html>

<?php
require_once("footer.php");
?>
