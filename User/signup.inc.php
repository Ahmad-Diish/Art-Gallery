<?php
// signup.inc.php

require_once("../Datenbank/userManager.php");
require_once("../Datenbank/userClass.php");

session_start();

if (isset($_POST['submit'])) {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $address = trim($_POST['address']);
    $postal = trim($_POST['postal']);
    $city = trim($_POST['city']);
    $region = trim($_POST['region']);
    $country = trim($_POST['country']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $emailRepeat = trim($_POST['emailrepeat']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $passwordRepeat = trim($_POST['passwordrepeat']);

    if (empty($firstname) || empty($lastname) || empty($address) || empty($postal) || empty($city) || empty($region) || empty($country) || empty($phone) || empty($email) || empty($emailRepeat) || empty($username) || empty($password) || empty($passwordRepeat)) {
        header("Location: register.php?error=emptyfields");
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || $email !== $emailRepeat) {
        header("Location: register.php?error=emailcheck");
        exit();
    } elseif ($password !== $passwordRepeat) {
        header("Location: register.php?error=passwordcheck");
        exit();
    } else {
        try {
            $userManager = new UserManager();
            $user = new User($firstname, $lastname, $address, $postal, $city, $region, $country, $phone, $email, $username, $password);

            if ($userManager->addUser($user)) {
                header("Location: ../Homepage/index.php?signup=success");
                exit();
            } else {
                error_log("Fehler beim Hinzufügen des Benutzers in die Datenbank.");
                header("Location: register.php?error=sqlerror");
                exit();
            }
        } catch (Exception $e) {
            error_log("Fehler beim Hinzufügen des Benutzers: " . $e->getMessage());
            header("Location: register.php?error=exception");
            exit();
        }
    }
} else {
    header("Location: register.php");
    exit();
}
?>


