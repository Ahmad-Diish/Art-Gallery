<?php
require_once("../Homepage/header.php");
require_once("../Datenbank/genreManager.php");
require_once("../Datenbank/artworkManager.php");

$conn = new Datenbank();
$genreManager = new GenreManager($conn);
$genre = Genre::getDefaultGenre();
$artworkManager = new ArtworkManager($conn);

// Check if the Genre ID is provided in the URL parameters
if (isset($_GET['genreID'])) {
$genreID = $_GET['genreID'];
} else {
// If Genre ID is not provided, handle the error (e.g., redirect to an error page)
echo "genre ID not provided.";
exit; // Exit the script to prevent further execution
}

if (!is_numeric($genreID) || $genreID <= 0) {
// Handle ungültige Genre-ID (z. B. Fehlermeldung anzeigen)
echo "Ungültige Genre-ID!";
exit;
}

$artworks = $genreManager->getArtworksByGenreId($genreID);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
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

// Display the Genre based on the provided Genre ID
$genreManager->displayGenreById($genreID);


?>


<?php
echo '<div class="row">';
if ($artworks) {
// Output the artwork details
foreach ($artworks as $artwork) {
// Add a debugging statement
//var_dump($artwork);

$artworkObject = Artwork::fromState($artwork); // Falls erforderlich, um ein Artwork-Objekt aus dem Array zu erstellen
$artworkObject->outputArtworks();
}
} else {
// Handle the case where no artwork is found for the Genre
echo "No artwork found for the Genre.";
}
echo '</div>'; // Close row
?>

</div>
</body>
</singleGenre>
<?php require("../Homepage/footer.php"); ?>