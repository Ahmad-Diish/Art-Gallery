<?php
require_once("../Homepage/header.php");
require_once("../Datenbank/genreManager.php");
require_once("../Datenbank/artworkManager.php");

$conn = new Datenbank();
$genreManager = new GenreManager($conn);
$artworkManager = new ArtworkManager($conn);

function displayError($message)
{
    echo "<div class='error-container'>";
    echo "<h2>Fehler</h2>";
    echo "<p>" . htmlspecialchars($message) . "</p>";
    echo '<button onclick="window.location.href=\'../Pages/genres.php\'">Zurück zu den Genre Galerie</button>';
    echo "</div>";
}

echo "<style>
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
</style>";

if (isset($_GET['genreID'])) {
    $genreID = $_GET['genreID'];

    if (!is_numeric($genreID) || $genreID <= 0) {
        displayError("Entschuldigung, es gab einen Fehler beim Abrufen der Genreinformationen. Bitte versuchen Sie es später erneut.");
        require("../Homepage/footer.php");
        exit;
    }

    $artworks = $genreManager->getArtworksByGenreId($genreID);

    if (empty($artworks)) {
        displayError("Es wurden keine Kunstwerke für das angegebene Genre gefunden.");
        require("../Homepage/footer.php");
        exit;
    }
} else {
    displayError("Genre-ID wurde nicht angegeben. Bitte überprüfen Sie die URL und versuchen Sie es erneut.");
    require("../Homepage/footer.php");
    exit;
}
?>

<singleGenre>

<head>
    <style>
        body {
            background-color: #fffbeb;
        }
        .title {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            overflow: hidden;
            color: #923f0e;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <?php
        // Genre anhand der ID anzeigen
        $genreManager->displayGenreById($genreID);
        ?>

        <div class="row">
            <?php
            if ($artworks) {
                foreach ($artworks as $artwork) {
                    $artworkObject = Artwork::fromState($artwork); // Falls erforderlich, um ein Artwork-Objekt aus dem Array zu erstellen
                    $artworkObject->outputArtworks();
                }
            } else {
                echo "No artwork found for the genre.";
            }
            ?>
        </div>
    </div>
</body>
</singleGenre>
<?php require("../Homepage/footer.php"); ?>
