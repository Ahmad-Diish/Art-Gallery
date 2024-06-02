<?php


require_once("datenbank.php");
require_once("reviewManager.php");

$datenbank = new datenbank();
$reviewManager = new ReviewManager($datenbank);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ReviewId'])) {
    $reviewId = $_POST['ReviewId'];

    $referer = $_SERVER['HTTP_REFERER'];

    $reviewManager->deleteComment($reviewId);

    header("Location: $referer");
    exit;
}
?>