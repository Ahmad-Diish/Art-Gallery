<?php
ob_start();

require_once("../Datenbank/datenbank.php");
require_once("../User/login.php");
require_once("login.vali.php");

session_start();
error_log("Session started");

$datenbank = new datenbank();

// Verbindung zur Datenbank herstellen
try {
    $datenbank->connect();
    error_log("Datenbankverbindung erfolgreich");
} catch (Exception $e) {
    ob_end_clean();
    die("Verbindung zur Datenbank fehlgeschlagen: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST["identifier"]); // Akzeptiert sowohl E-Mail als auch Benutzername
    $password = $_POST["password"];
    error_log("Formulardaten empfangen: identifier=$identifier");

    // SQL-Abfrage vorbereiten und ausführen, um Daten aus beiden Tabellen zu holen
    $sql = "SELECT l.CustomerID, l.Pass, l.UserName, c.Email 
            FROM customerlogon l
            LEFT JOIN customers c ON l.CustomerID = c.CustomerID
            WHERE c.Email = :identifier OR l.UserName = :identifier";
    $stmt = $datenbank->prepareStatement($sql);
    $stmt->bindParam(":identifier", $identifier);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    error_log("SQL-Abfrage ausgeführt: result=" . json_encode($result));

    $errors = validateLogin($result, $password);

    if (!empty($errors)) {
        // Bei Fehlern zurück zur Login-Seite mit Fehlermeldungen
        $_SESSION['login_errors'] = $errors;
        header("Location: login.php");
        ob_end_flush();
        exit();
    } else {
        // Erfolgreiche Anmeldung
        $_SESSION["CustomerID"] = $result["CustomerID"];
        $_SESSION["username"] = $result["UserName"];
        error_log("Login erfolgreich für Benutzer-ID: " . $result["CustomerID"]);

        header("Location: ../Homepage/index.php?login=success");
        ob_end_flush();
        exit();
    }
} else {
    header("Location: login.php");
    ob_end_flush();
    exit();
}
?>
