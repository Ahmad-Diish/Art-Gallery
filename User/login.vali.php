<?php

require_once("../User/login.inc.php");
function validateLogin($result, $password)
{
    $errors = array(); // Array für Fehlermeldungen

    if ($result) {
        if (!password_verify($password, $result["Pass"])) {
            $errors[] = "Das eingegebene Passwort ist falsch.";
        }
    } else {
        $errors[] = "Kein Benutzer mit diesem Benutzernamen oder dieser E-Mail-Adresse gefunden.";
    }

    return $errors;
}
?>
