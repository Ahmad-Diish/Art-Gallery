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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <style>
        body {
            background-color: #fffbeb;
            font-family: Arial, sans-serif;
        }

        .comments-section {
            margin: 100px;
            border-top: 2px solid #fef3c7;
            border: 25px solid #fef3c7;
            border-radius: 30px;
            overflow: hidden;
            border-radius: 10px;
            background-color: #fef3c7;
            margin-top: 0px;
        }

        h2 {
            color: #923f0e;
            font-family: "Arial";
        }

        .comment {
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .comment-info {
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .comment-author {
            color: #923f0e;
            font-family: "Arial";
            font-weight: bold;
        }

        .comment-date {
            color: #666;
        }

        .comment-text {
            margin-bottom: 10px;
        }

        .comment-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .delete-comment-btn {
            background-color: #ff6666;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .comment-form {
            margin-top: 20px;
        }

        .comment-author-input,
        .comment-textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
        }

        .submit-comment-btn {
            background-color: #923f0e;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-bottom: 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .submit-comment-btn:hover {
            background-color: #923f0e;
        }

        .star {
            color: gray;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <body>
        <div class="container mt-4">
            <?php
            // hier wurde die Funktion outSingleArtwork von Klasse Artwork gerufen, um das Kunstwerk allein zu zeigen
            $artwork->outputSingleArtwork();
            ?>
        </div>

        <!-- HTML -->
        <div class="comments-section">
            <h2>Kommentare</h2>

            <!-- Kommentare -->
            <div class="comment">
                <div class="comment-info">
                    <span class="comment-author">Benutzername</span>
                    <span class="comment-date">Veröffentlichungsdatum</span>
                </div>
                <p class="comment-text">Kommentartext hier</p>
                <div class="rating">
                    <?php
                    // Anzahl der maximalen Sterne
                    $maxRating = 5;

                    // Schleife zum Erzeugen der Sterne
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= 5) {
                            echo "<span class='star filled'>&#9733;</span>"; // Gefüllter Stern
                        } else {
                            echo "<span class='star'>&#9733;</span>"; // Leerer Stern
                        }
                    }
                    ?>
                </div>
                <div class="comment-actions">
                    <button class="delete-comment-btn">Löschen</button>
                </div>
            </div>

            <!-- Kommentarformular -->
            <form class="comment-form">
                <input type="text" class="comment-author-input" placeholder="Name">
                <textarea class="comment-textarea" placeholder="Fügen Sie Ihren Kommentar hier hinzu"></textarea>
                <button type="submit" class="submit-comment-btn">Kommentar hinzufügen</button>
            </form>
        </div>

        <?php
        // Verbindung zur Datenbank herstellen
        // $db_connection = mysqli_connect("localhost", "Benutzername", "Passwort", "Datenbankname");

        // Überprüfen, ob das Formular gesendet wurde
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Daten aus dem Formular abrufen
            $name = $_POST['name'];
            $comment = $_POST['comment'];

            // SQL-Befehl, um Kommentar in die Datenbank einzufügen
            $sql = "INSERT INTO comments (name, comment, created_at) VALUES ('$name', '$comment', NOW())";

            // Kommentar in die Datenbank einfügen
            // mysqli_query($db_connection, $sql);

            // Nach dem Einfügen zurück zur Seite weiterleiten
            // header("Location: index.php");
            // exit();
        }
        ?>



    </body>
</body>

</html>

<?php
require_once("../Homepage/footer.php");
?>