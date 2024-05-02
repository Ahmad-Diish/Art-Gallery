<?php
require_once ("../Homepage/header.php");
require_once ("../Datenbank/artworkRepository.php");


// Erstellen einer neuen Datenbankverbindung und einer ArtistRepository-Instanz.
$conn = new Datenbank();
$artworkRepository = new ArtworkRepository($conn);

// Verarbeitung des Formulars für die Sortierung.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Überprüfe, ob die Formulardaten für die Sortierreihenfolge gesendet wurden
    if (isset($_POST['sortierreihenfolge']) && (isset($_POST['sortierungsart']))) {
        $sortierreihenfolge = $_POST['sortierreihenfolge'];
        $sortierungsart = $_POST['sortierungsart'];
        $artworkRepository->sortiereArtworks($sortierungsart, $sortierreihenfolge);
    }
} else {
    // Default-Sortierreihenfolge
    $sortierreihenfolge = 'aufsteigend';

    // Default-Sortierungsart
    $sortierungsart = 'Title';
    $artworkRepository->sortiereArtworks($sortierungsart, $sortierreihenfolge);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kunstwerke Galerie</title>
    <style>
        /* Stile für die Sortierfelder */
        body {
            
            background-color: #fffbeb;
           
        }

        select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin-right: 10px;
            /* Platz zwischen den Feldern */
        }

        /* Stile für den Sortierbutton */
        button {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            background-color: #923f0e;
            color: white;
            font-size: 16px;
            cursor: pointer;
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
        <h2>Kunstwerke Galerie</h2>
        <!-- Sorting Options -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="Sortiere">Sortiere nach:</label>
            <select class="" aria-label="Default select example" name="sortierungsart">
                <option value="Title" <?php echo ($sortierungsart == 'Titel') ? 'selected' : ''; ?>>Titel</option>
                <option value="YearOfWork" <?php echo ($sortierungsart == 'YearOfWork') ? 'selected' : ''; ?>>Jahr
                </option>
                <option value="ArtistID" <?php echo ($sortierungsart == 'ArtistID') ? 'selected' : ''; ?>>Künstler
                </option>
            </select>
            <label for="Rangfolge">Rangfolge:</label>
            <select class="" aria-label="Default select example" name="sortierreihenfolge">
                <option value="aufsteigend" <?php echo ($sortierreihenfolge == 'aufsteigend') ? 'selected' : ''; ?>>
                    Aufsteigend</option>
                <option value="absteigend" <?php echo ($sortierreihenfolge == 'absteigend') ? 'selected' : ''; ?>>
                    Absteigend</option>
            </select>
            <button type="submit">Sortieren</button>
        </form>

        <div class="row mt-4 cards">
            <!-- Artwork Images and Links -->
            <?php
            // Ausgabe der sortierten Kunstwerke
            $artworkRepository->AllArtworks();
            ?>
        </div>
    </div>
</body>

</html>




<?php
require_once ("../Homepage/footer.php");
?>