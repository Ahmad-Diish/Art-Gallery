<?php
require_once("../Homepage/header.php");
require_once("../Datenbank/datenbank.php");
require_once("../Datenbank/artistClass.php");
require_once("../Datenbank/");

// Verbindung zur Datenbank herstellen
$conn = mysqli_connect("localhost:4306", "root", "", "art");

if (!$conn) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}

// Überprüfen, ob das Formular abgesendet wurde
if (isset($_POST['submit-search'])) {
    // Benutzereingabe bereinigen
    $search = mysqli_real_escape_string($conn, $_POST['search']);

    // SQL-Abfrage erstellen
    $sql = "SELECT * FROM artist WHERE FirstName LIKE '%$search%'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Ergebnisse ausgeben
        while($row = mysqli_fetch_assoc($result)) {
            echo "Ergebnis: " . $row['FirstName'] . "<br>";
        }
    } else {
        echo "Keine Ergebnisse gefunden.";
    }
}

// Verbindung schließen
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
</head>
<body>
    <h1>Suchergebnisse</h1>
</body>
</html>

<?php
require_once("../Homepage/footer.php");
?>