<?php
require_once("../Homepage/header.php");
require_once("../Datenbank/artistClass.php");
require_once("../Datenbank/artistManager.php");
require_once("../Datenbank/artworkClass.php");
require_once("../Datenbank/artworkManager.php");
// Database connection
$datenbank = new Datenbank();
$datenbank->connect();
$PDO = $datenbank->getConnection();

// Set the database connection for the Artist class
Artist::setDatabase($PDO);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
</head>
<body>
    <h1>Suchergebnisse</h1>
    <form class="d-flex" method="POST" action="">
        <input class="form-control me-2" type="text" name="firstName" placeholder="Vorname" aria-label="Search">
        <input class="form-control me-2" type="text" name="lastName" placeholder="Nachname" aria-label="Search">
        <button class="btn search-btn" type="submit" name="submit-search"><i class="bi bi-search"></i></button>
    </form>

    <?php
    if (isset($_POST['submit-search'])) {
        $firstName = $_POST['firstName'] ?? '';
        $lastName = $_POST['lastName'] ?? '';

        $results = Artist::searchArtists($firstName, $lastName);

        if (!empty($results)) {
            foreach ($results as $artist) {
                $artist->outputArtist();
            }
        } else {
            echo "<p>Keine Künstler gefunden.</p>";
        }
    }
    

    if (isset($_POST['submit-search'])) {
        $firstName = $_POST['title'] ?? '';

        $results = Artwork::searchArtworks($title);

        if (!empty($results)) {
            foreach ($results as $artist) {
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






