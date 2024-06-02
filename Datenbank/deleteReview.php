<?php

// Include necessary files
require_once("datenbank.php");
require_once("reviewManager.php");

// Create a database connection
$datenbank = new datenbank();
$reviewManager = new ReviewManager($datenbank);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ReviewId'])) {
    $reviewId = $_POST['ReviewId'];
    $reviewManager->deleteComment($reviewId);
    header("Location: ../Homepage/index.php");
    exit;
}
?>
