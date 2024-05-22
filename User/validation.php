<?php
function validateEmails($email, $emailRepeat)
{
    if ($email !== $emailRepeat) {
        return "Die E-Mail-Adressen stimmen nicht überein.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Die E-Mail-Adresse ist ungültig.";
    }

    $userManager = new UserManager();
    if ($userManager->emailExists($email)) {
        return "Die Email existiert bereits.";
    }
    return null;
}

function validateUsername($username)
{
    $userManager = new UserManager();
    if ($userManager->usernameExists($username)) {
        return "Der Username existiert bereits.";
    }
    return null;
}

function validatePassword($password, $passwordRepeat)
{
    $errors = array(); // Array für Fehlermeldungen

    if ($password !== $passwordRepeat) {
        $errors[] = "Die Passwörter stimmen nicht überein.";
    }

    if (strlen($password) < 12) {
        $errors[] = "Das Passwort muss mindestens 12 Zeichen lang sein.";
    }

    if (!preg_match("/[0-9]/", $password)) {
        $errors[] = "Das Passwort muss mindestens eine Zahl enthalten.";
    }

    if (!preg_match("/[A-Z]/", $password)) {
        $errors[] = "Das Passwort muss mindestens einen Großbuchstaben enthalten.";
    }

    if (!preg_match("/[!?@#$%^&*()\-_=+{};:,<.>]/", $password)) {
        $errors[] = "Das Passwort muss mindestens ein Sonderzeichen enthalten.";
    }

    return $errors;
}
?>