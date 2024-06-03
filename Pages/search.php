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

    <style>
        /* Stile für die Sortierfelder */
        body {

            background-color: #fffbeb;

        }

        h1 {
            text-align: center;
            color: #923f0e;
            font-family: "Goudy Stout";
            margin-TOP: 70px;
            margin-bottom: 100px;
        }
        h4 {
            color: #923f0e;
            margin-bottom: 30px;
            margin-Top: 30px;
        }
        h2 {
            color: #923f0e;
            margin-bottom: 30px;
        }

        /* Stile für den Container */
        .container {
            max-width: 90%;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Suchergebnisse</h1>
        <div class="row mt-4 cards">
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
                    echo "<h4>Keine Künstler gefunden.</h4>";
                }

                // Suche nach Kunstwerken
                $artworkResults = Artwork::searchArtworks($searchQuery);

                if (!empty($artworkResults)) {
                    echo "<h2>Kunstwerke</h2>";
                    foreach ($artworkResults as $artwork) {
                        $artwork->outputArtworks();
                    }
                } else {
                    echo "<h4>Keine Kunstwerke gefunden.</h4>";
                }
            }
            ?>
        </div>
    </div>
</body>

</html>

<?php
require_once("../Homepage/footer.php");
?>