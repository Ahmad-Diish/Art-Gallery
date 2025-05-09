<?php
require_once("../Homepage/header.php");
require_once("../Datenbank/artworkManager.php");



$conn = new Datenbank();
$artworkManager = new ArtworkManager($conn);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['sortierreihenfolge']) && (isset($_POST['sortierungsart']))) {
        $sortierreihenfolge = $_POST['sortierreihenfolge'];
        $sortierungsart = $_POST['sortierungsart'];
        $artworkManager->sortiereArtworks($sortierungsart, $sortierreihenfolge);
    }
} else {

    $sortierreihenfolge = 'aufsteigend';


    $sortierungsart = 'Title';
    $artworkManager->sortiereArtworks($sortierungsart, $sortierreihenfolge);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kunstwerke Galerie</title>
    <style>

        body {

            background-color: #fffbeb;

        }

        h2 {
            text-align: center;
            color: #923f0e;
            font-family: "Goudy Stout";
            margin-TOP: 70px;
            margin-bottom: 100px;
        }

        label {
            color: #923f0e;
            margin-bottom: 50px;
        }

        select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin-right: 10px;
            color: #923f0e;
        }


        button {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            background-color: #923f0e;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .container {
            max-width: 90%;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Kunstwerke Galerie</h2>

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

            <?php

            $artworkManager->AllArtworks();
            ?>
        </div>
    </div>
</body>

</html>




<?php
require_once("../Homepage/footer.php");
?>