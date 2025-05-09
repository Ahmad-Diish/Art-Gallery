<?php
ob_start();
require_once("../Homepage/header.php");
require_once("../Datenbank/artworkManager.php");
require_once("../Datenbank/artworkClass.php");
require_once("../Datenbank/reviewClass.php");
require_once("../Datenbank/reviewManager.php");
?>
<style>
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
</style>

<?php
// Create a database connection
$datenbank = new datenbank();
$reviewManager = new ReviewManager($datenbank);

$conn = new Datenbank();
$artworkManager = new ArtworkManager($conn);


$artworkID = $_GET['artworkID'] ?? null;



try {
    if (!is_numeric($artworkID) || $artworkID <= 0) {
        handleError();
        exit;
    }
    $artwork = $artworkManager->getArtwork($artworkID);
} catch (Exception $e) {
    error_log('Error fetching artwork data: ' . $e->getMessage());
    handleError();
    exit;
}

function handleError()
{
?>
    <div class="error-container">
        <h2>Fehler</h2>
        <p>Entschuldigung, es gab einen Fehler beim Abrufen der Künstlerinformationen. Bitte versuchen Sie es später erneut.</p>
        <button onclick="window.location.href='../Pages/artsworks.php'">Zurück zu den Künstler Galerie</button>
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

            $artwork->outputSingleArtwork();
            ?>
        </div>


        <!DOCTYPE html>
        <html lang="de">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Bewertungen und Kommentare</title>
            <link rel="stylesheet" href="styles.css"> 
        </head>

        <body>



            <?php if (isset($_SESSION['CustomerID'])) : ?>

                <div class="comments-section">
                    <h2>Kommentare</h2>

                    <div class="comment">
                    </div>

                    <form class="comment-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?artworkID=' . $artworkID; ?>">

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

                        <textarea class="comment-textarea" name="comment" placeholder="Fügen Sie Ihren Kommentar hier hinzu"><?php echo htmlspecialchars($_POST['comment'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $commentText = $_POST['comment'] ?? '';
                            $artworkID = $_POST['artworkID'] ?? null;
                            $customerID = $_POST['customerID'] ?? null;
                            $rating = $_POST['rating'] ?? 0;

                            $commentText = htmlspecialchars($commentText, ENT_QUOTES, 'UTF-8');

                            if ($commentText && $artworkID && $customerID && $rating) {
                                $referer = $_SERVER['HTTP_REFERER'];

                                ob_start(); 
                                $reviewManager->addComment($artworkID, $customerID, $commentText, $rating);
                                $output = ob_get_clean(); 

                                if (strpos($output, 'Fehler:') === false) {
                                    header("Location: $referer");
                                    exit;
                                } else {
                                    echo $output; 
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

                $reviewManager->displayReview($artworkID);
                ?>

            </div>
        </body>

        </html>

        <?php

        require_once("../Homepage/footer.php");
        ?>