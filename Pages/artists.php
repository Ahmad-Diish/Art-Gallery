<?php
require_once("../Homepage/header.php");
require_once("../Datenbank/artistManager.php");
require_once("../Datenbank/artistClass.php");

$conn = new Datenbank(); 
$artistManager = new ArtistManager($conn); 
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

        body {

            background-color: #fffbeb;

        }

      
        .container {
            max-width: 90%;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Künstler Galerie</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            </select>
            <label for="Rangfolge">Rangfolge:</label>
            <select class="" aria-label="Default select example" name="sortierreihenfolge">
                <option value="aufsteigend" <?php echo ($sortierreihenfolge == 'aufsteigend') ? 'selected' : ''; ?>> Aufsteigend</option>
                <option value="absteigend" <?php echo ($sortierreihenfolge == 'absteigend') ? 'selected' : ''; ?>>Absteigend</option>
            </select>
            <button name="sortieren" type="submit">Sortieren</button>
        </form>

        <div class="row mt-4 cards">
            <?php

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                if (isset($_POST['sortieren'])) {
                    $sortierreihenfolge = $_POST['sortierreihenfolge']; 
                    $artistManager->displayAllArtist($sortierreihenfolge); 
                }
            } else {
                $artistManager->displayAllArtist($sortierreihenfolge); 
            }
            ?>
        </div>
    </div>
</body>

<?php
require_once("../Homepage/footer.php");
?>