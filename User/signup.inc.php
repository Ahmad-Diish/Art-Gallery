<?php
require_once ("../Datenbank/userManager.php");
require_once ("../Datenbank/userClass.php");
require_once ("../User/validation.php");

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

    $errors = array(); // Array für Fehlermeldungen

    if (empty($firstname) || empty($lastname) || empty($address) || empty($postal) || empty($city) || empty($region) || empty($country) || empty($phone) || empty($email) || empty($emailRepeat) || empty($username) || empty($password) || empty($passwordRepeat)) {
        $errors[] = "Bitte füllen Sie alle Felder aus.";
    }

    $emailError = validateEmails($email, $emailRepeat);
    if ($emailError) {
        $errors[] = $emailError;
    }

    $usernameError = validateUsername($username);
    if ($usernameError) {
        $errors[] = $usernameError;
    }

    // Validiere die Passwörter
    $passwordErrors = validatePassword($password, $passwordRepeat);
    if (!empty($passwordErrors)) {
        $errors = array_merge($errors, $passwordErrors);
    }

    if (!empty($errors)) {
        // Wenn es Fehler gibt, leite zurück zur Registrierungsseite und zeige die Fehlermeldungen an
        $errorMessage = implode(" 
        ", $errors);
        header("Location: register.php?error=validation&message=" . urlencode($errorMessage));
        exit();
    }

    // Wenn keine Fehler vorhanden sind, fahren Sie mit der Benutzerregistrierung fort
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
} else {
    header("Location: register.php");
    exit();
}
?>