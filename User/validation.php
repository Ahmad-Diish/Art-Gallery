<?php

function validateFirstName($firstname) {

    $errors = array();

    if (preg_match('/[0-9]/', $firstname)) {
        $errors[] = "Der Vorname darf keine Zahlen enthalten.";
    }

    if (preg_match('/[!?@#$%^&*()\-_=+{};:,<.>]/', $firstname)) {
        $errors[] = "Der Vorname darf keine Sonderzeichen enthalten.";
    }

    if (mb_strlen($firstname) < 2) {
        $errors[] = "Der Vorname muss mindestens 2 Zeichen lang sein.";
    }

    return $errors;
}

function capitalizeFirstName($firstname) {
    if (!empty($firstname)) {
        $firstname = ucfirst(strtolower($firstname));
    }
    return $firstname;
}

function validateLastName($lastname) {

    $errors = array();

    if (preg_match('/[0-9]/', $lastname)) {
        $errors[] = "Der Nachname darf keine Zahlen enthalten.";
    }

    if (preg_match('/[!?@#$%^&*()\-_=+{};:,<.>]/', $lastname)) {
        $errors[] = "Der Nachname darf keine Sonderzeichen enthalten.";
    }

    if (mb_strlen($lastname) < 2) {
        $errors[] = "Der Nachname muss mindestens 2 Zeichen lang sein.";
    }

    return $errors;
}

function capitalizeLastName($lastname) {
    if (!empty($lastname)) {
        $lastname = ucfirst(strtolower($lastname));
    }
    return $lastname;
}

function validateAddress($address){
    $errors = array();

    if(preg_match('/[!?@#$%^&*()\_=+{};:,<>]/', $address))
    {
        $errors[] = "Die Addresse darf dieses Sonderzeichen nicht enthalten.";
    }

    if (!preg_match('/^[a-zA-ZäöüÄÖÜß\s\-.]+(\s\d+)?$/', $address)) {
        $errors[] = "Es dürfen keine Zahlen mitten im Straßennamen enthalten sein.";
    }

    return $errors;
}

function capitalizeAddress($address) {
    if (!empty($address)) {
        $address = ucfirst(strtolower($address));
    }
    return $address;
}

function expandAddressAbbreviations($address) {
    // Liste der Abkürzungen und deren vollständige Formen
    $abbreviations = [
        'Str.' => 'Straße',
        'Pl.' => 'Platz',
        'Str' => 'Straße',
        'Pl' => 'Platz',
        'Hbf' => 'Hauptbahnhof',
        'Bhf' => 'Bahnhof',
    ];

    // Ersetze Abkürzungen mit den vollständigen Formen
    foreach ($abbreviations as $abbr => $full) {
        $address = preg_replace('/\b' . preg_quote($abbr, '/') . '\b/i', $full, $address);
    }

    return $address;
}

function validatePostal($postal, $country) {
    $patterns = [
        'DE' => '/^\d{5}$/', // Deutschland
        'US' => '/^\d{5}(-\d{4})?$/', // USA
        'CA' => '/^[A-Za-z]\d[A-Za-z] \d[A-Za-z]\d$/', // Kanada
        'FR' => '/^\d{5}$/', // France
        'IT' => '/^\d{5}$/', // Italy
        'GB' => '/^(GIR 0AA|[A-Z]{1,2}\d[A-Z\d]? \d[A-Z]{2})$/i', // UK
        'AU' => '/^\d{4}$/', // Australien
        'JP' => '/^\d{3}-\d{4}$/', // Japan
        'NL' => '/^\d{4} ?[A-Z]{2}$/', // Niederlande
        'BR' => '/^\d{5}-\d{3}$/', // Brasilien
    ];

    // Standardmuster für Länder ohne spezifisches Muster (nur Ziffern und Buchstaben, 3-10 Zeichen)
    $defaultPattern = '/^[A-Za-z0-9]{3,10}$/';

    $countryCode = strtoupper($country);

    // Validierung basierend auf Landeskürzel
    if (isset($patterns[$countryCode])) {
        if (preg_match($patterns[$countryCode], $postal) === 1) {
            return null;
        } else {
            return "Die Postleitzahl ist für das Land ungültig.";
        }
    } else {
        // Fallback-Validierung mit dem Standardmuster
        if (preg_match($defaultPattern, $postal) === 1) {
            return null;
        } else {
            return "Die Postleitzahl ist für das Land ungültig.";
        }
    }
}

function validateCity($city){
    
    $errors = array();

    if(preg_match('/[0-9]/', $city)){
        $errors[] = "Der Stadtname darf keine Zahlen enthalten.";
    }

    if(preg_match('/[!?@#$%^&*()\_=+{};:,<>]/', $city)){
        $errors[] = "Der Stadtname darf dieses nicht Sonderzeichen enthalten";
    }

    return $errors;
}

function capitalizeCity($city) {
    if (!empty($city)) {
        $city = ucfirst(strtolower($city));
    }
    return $city;
}

function validateRegion($region){

    $errors = array();

    if(preg_match('/[0-9]/', $region)){
        $errors[] = "Das Kürzel der Region darf keine Zahlen enthalten.";
    }

    if (preg_match('/[!?@#$%^&*()\_=+{};:,<.>]/', $region)) {
        $errors[] = "Das Kürzel der Region darf keine Sonderzeichen enthalten.";
    }

    if (mb_strlen($region) > 3) {
        $errors[] = "Das Kürzel der Region darf maximal 3 Zeichen lang sein.";
    }

    $region = strtoupper($region);

    return $errors;
}

function validateCountry($country) {
    $errors = array();

    if (preg_match('/[!?@#$%^&*()\-_=+{};:,<.>]/', $country)) {
        $errors[] = "Der Name des Landes darf keine Sonderzeichen enthalten.";
    }

    if(preg_match('/[0-9]/', $country)){
        $errors[] = "Der Name des Landes darf keine Zahlen enthalten.";
    }

    if (mb_strlen($country) < 4) {
        $errors[] = "Der Name des Landes muss mindestens 4 Zeichen lang sein.";
    }

    return $errors;
}

function capitalizeCountry($country) {
    if (!empty($country)) {
        $country = ucfirst(strtolower($country));
    }
    return $country;
}

function validateUsername($username)
{
    $userManager = new UserManager();
    if ($userManager->usernameExists($username)) {
        return "Der Username existiert bereits.";
    }
    return null;
}

function validateEmails($email, $emailRepeat)
{
    $errors = array();

    if ($email !== $emailRepeat) {
        $errors['emailrepeat'] = "Die E-Mail-Adressen stimmen nicht überein.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Die E-Mail-Adresse ist ungültig.";
    }

    $userManager = new UserManager();
    if ($userManager->emailExists($email)) {
        $errors['email'] = "Die Email existiert bereits.";
    }

    return $errors;
}

function validatePassword($password, $passwordRepeat)
{
    $errors = array();

    if ($password !== $passwordRepeat) {
        $errors['passwordrepeat'] = "Die Passwörter stimmen nicht überein.";
    }

    if (mb_strlen($password) < 12) {
        $errors['password'] = "Das Passwort muss mindestens 12 Zeichen lang sein.";
    }

    if (!preg_match("/[0-9]/", $password)) {
        $errors['password'] = "Das Passwort muss mindestens eine Zahl enthalten.";
    }

    if (!preg_match("/[A-Z]/", $password)) {
        $errors['password'] = "Das Passwort muss mindestens einen Großbuchstaben enthalten.";
    }

    if (!preg_match("/[!?@#$%^&*()\-_=+{};:,<.>]/", $password)) {
        $errors['password'] = "Das Passwort muss mindestens ein Sonderzeichen enthalten.";
    }

    return $errors;
}

?>