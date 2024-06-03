<?php
// Inkludiere den UserManager, um auf die Überprüfungsfunktionen zuzugreifen
require_once("../Datenbank/userManager.php");

class AccountValidator {
    private $userManager;

    public function __construct() {
        $this->userManager = new UserManager();
    }

    public function validateUsername($username) {
        // Prüfe, ob der Benutzername bereits existiert und nicht zum aktuellen Benutzer gehört
        if ($this->userManager->usernameExists($username)) {
            return "Der Benutzername ist bereits vergeben.";
        }
        return "";
    }

    public function validateEmail($email) {
        // Prüfe, ob die E-Mail bereits verwendet wird und nicht zum aktuellen Benutzer gehört
        if ($this->userManager->emailExists($email)) {
            return "Die E-Mail-Adresse wird bereits verwendet.";
        }
        return "";
    }

    public function validatePhone($phone) {
        // Prüfe, ob die Telefonnummer bereits verwendet wird und nicht zum aktuellen Benutzer gehört
        if ($this->userManager->phoneExists($phone)) {
            return "Die Telefonnummer wird bereits verwendet.";
        }
        return "";
    }

    public function validatePassword($oldPassword, $newPassword, $userId) {
        $hashedPasswordFromDB = $this->userManager->getUserPasswordById($userId);
        $errors = [];

        error_log("oldPassword: $oldPassword hashedPasswordFromDB: " . $hashedPasswordFromDB);
    
        if (!password_verify($oldPassword, $hashedPasswordFromDB)) {
            array_push($errors, "Das alte Passwort ist nicht korrekt.");
        }
    
        if (password_verify($newPassword, $hashedPasswordFromDB)) {
            array_push($errors, "Das neue Passwort darf nicht mit dem aktuellen Passwort übereinstimmen.");
        }
    
        // Überprüfe Passwortanforderungen
        if (strlen($newPassword) < 12) {
            array_push($errors, "Das Passwort muss mindestens 12 Zeichen lang sein.");
        }
    
        if (!preg_match("/[0-9]/", $newPassword)) {
            array_push($errors, "Das Passwort muss mindestens eine Zahl enthalten.");
        }
    
        if (!preg_match("/[A-Z]/", $newPassword)) {
            array_push($errors, "Das Passwort muss mindestens einen Großbuchstaben enthalten.");
        }
    
        if (!preg_match("/[!?@#$%^&*()\-_=+{};:,<.>]/", $newPassword)) {
            array_push($errors, "Das Passwort muss mindestens ein Sonderzeichen enthalten.");
        }
    
        return $errors;
    }    
}
