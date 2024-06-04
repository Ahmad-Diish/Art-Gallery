<?php
ob_start();
require_once("../Homepage/header.php");
require_once("../Datenbank/artworkManager.php");
require_once("../Datenbank/artworkClass.php");
require_once("../Datenbank/reviewClass.php");
require_once("../Datenbank/reviewManager.php");

// Create a database connection
$datenbank = new datenbank();
$reviewManager = new ReviewManager($datenbank);

$conn = new Datenbank();
$artworkManager = new ArtworkManager($conn);


$artworkID = $_GET['artworkID'] ?? null;



// Stelle sicher, dass die Künstler-ID gültig ist, bevor du sie abrufst
try {
    if (!is_numeric($artworkID) || $artworkID <= 0) {
        handleError();
        exit;
    }
    $artwork = $artworkManager->getArtwork($artworkID);
} catch (Exception $e) {
    error_log('Error fetching artist data: ' . $e->getMessage());
    handleError();

    exit;
}

function handleError()
{
?>
    <div class="error-container">
        <h2>Fehler</h2>
        <p>Entschuldigung, es gab einen Fehler beim Abrufen der Künstlerinformationen. Bitte versuchen Sie es später erneut.</p>
        <button onclick="window.location.href='../Pages/artworks.php'">Zurück zu den Künstler Galerie</button>
    </div>
<?php
    require("../Homepage/footer.php");
}

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
            margin-top: 100px;
        }

        .reviews-section {
            margin: 100px;
            border-top: 2px solid #fef3c7;
            border: 25px solid #fef3c7;
            border-radius: 30px;
            overflow: hidden;
            border-radius: 10px;
            background-color: #fef3c7;
            margin-top: 100px;
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

        #rating-select {
            margin-bottom: 20px;
            color: #923f0e;
            border-radius: 5px;
        }


        .login-notice {
            background-color: #fef3c7;
            border: 2px solid #923f0e;
            padding: 18px;
            border-radius: 10px;
            text-align: center;
            font-family: 'Roboto', sans-serif;
            margin-bottom: 6rem;
            margin-right: 10rem;
            margin-left: 10rem;
            margin-top: 6rem;
        }

        .login-notice p {
            color: #923f0e;
            font-size: 18px;
            margin: 0;
        }

        .login-link {
            color: #923f0e;
            font-weight: bold;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;

        }

        h6 {
            color: red;

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
        <!DOCTYPE html>
        <html lang="de">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Bewertungen und Kommentare</title>
            <link rel="stylesheet" href="styles.css"> <!-- Stil-Datei einbinden -->
        </head>

        <body>

            <!-- Bewertungen -->

            <?php if (isset($_SESSION['CustomerID'])) : ?>
                <!-- Kommentare -->
                <div class="comments-section">
                    <h2>Kommentare</h2>

                    <!-- Kommentare anzeigen -->
                    <div class="comment">
                    </div>

                    <!-- Kommentarformular -->
                    <form class="comment-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?artworkID=' . $artworkID; ?>">

                        <!-- Sternebewertung -->
                        <label for="rating-select">Bewertung:</label>
                        <select name="rating" id="rating-select">
                            <option value="0">Keine Bewertung</option>
                            <option value="1">1 Stern</option>
                            <option value="2">2 Sterne</option>
                            <option value="3">3 Sterne</option>
                            <option value="4">4 Sterne</option>
                            <option value="5">5 Sterne</option>
                        </select>
                        <input type="hidden" name="artworkID" value="<?php echo $artworkID; ?>">
                        <input type="hidden" name="customerID" value="<?php echo $_SESSION['CustomerID']; ?>">
                        <textarea class="comment-textarea" name="comment" placeholder="Fügen Sie Ihren Kommentar hier hinzu"></textarea>

                        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $commentText = $_POST['comment'] ?? '';
                            $artworkID = $_POST['artworkID'] ?? null;
                            $customerID = $_POST['customerID'] ?? null;
                            $rating = $_POST['rating'] ?? 0;

                            if ($commentText && $artworkID && $customerID && $rating) {
                                $referer = $_SERVER['HTTP_REFERER'];

                                ob_start(); // Output buffering start
                                $reviewManager->addComment($artworkID, $customerID, $commentText, $rating);
                                $output = ob_get_clean(); // Get the buffered output

                                if (strpos($output, 'Fehler:') === false) {
                                    header("Location: $referer");
                                    exit;
                                } else {
                                    echo $output; // Display the error message
                                }
                            } else {
                                echo "<h6> Alle Felder müssen ausgefüllt werden.</h6>";
                            }
                        }
                        ?>

                        <button type="submit" class="submit-comment-btn">Kommentar hinzufügen</button>
                    </form>


                </div>

            <?php else : ?>
                <div class="login-notice">
                    <p>Sie müssen <a href="../user/login.php" class="login-link">eingeloggt</a> sein, um Kommentare hinzuzufügen</p>
                </div>
            <?php endif; ?>
            <div class="reviews-section">
                <h2>Bewertungen</h2>

                <?php
                // Zeige die Top-Bewertungen an
                $reviewManager->displayReview($artworkID);
                ?>

            </div>
        </body>

        </html>

        <?php

        require_once("../Homepage/footer.php");
        ?>