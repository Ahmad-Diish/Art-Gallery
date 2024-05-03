<?php
require_once("../Homepage/header.php");
require_once("../Datenbank/artistRepository.php");
require_once("../Datenbank/artist.php");
$conn = new Datenbank();
$artistRepository = new ArtistRepository($conn);
$sortierreihenfolge = isset($_POST['sortierreihenfolge']) ? $_POST['sortierreihenfolge'] : 'aufsteigend';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Künstler</title>

    <style>
        .form-select {
            width: 200px;
            margin-right: 10px;
        }

        .button_style {
            background-color: #923f0e;
            color: #fff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
        }

        .button_style:hover {
            background-color: #1d2124;
        }

        body {

            background-color: #fffbeb;

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
        <h2>Künstler Galeria</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"><select class="form-select select_style" style="height: 50px" aria-label="Default select example" name="sortierreihenfolge">
                <option value="aufsteigend" <?php echo ($sortierreihenfolge == 'aufsteigend') ? 'selected' : ''; ?>>Aufsteigend</option>
                <option value="absteigend" <?php echo ($sortierreihenfolge == 'absteigend') ? 'selected' : ''; ?>>Absteigend</option>
            </select><button name="sortieren" type="submit" class="btn btn-dark button_style">Sortieren</button></form>
      
            <div class="row mt-4 cards">
        <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['sortieren'])) {
                $sortierreihenfolge = $_POST['sortierreihenfolge'];
                $artistRepository->displayAllArtist($sortierreihenfolge);
            }
        } else {
            $artistRepository->displayAllArtist($sortierreihenfolge);
        }
        ?>
    </div>
    </div>
</body>

<?php
require_once("../Homepage/footer.php");
?>


<!-- <div class="row mt-4" style="border: 1px solid #ccc; border-radius: 5px; padding: 15px; margin-bottom: 1px; background-color: #D1BB9E;">
    style="

    max-width: 1100px;
    margin: 0 auto;
    padding: 20px;
    background-color: #E5DDC5;
    border: 1px solid #ddd;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 40px;" -->