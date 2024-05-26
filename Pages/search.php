<?php
require_once("../Homepage/header.php");
require_once("../Datenbank/artistManager.php");
require_once("../Datenbank/artistClass.php");

// Funktion zum Durchführen der Suche
function performSearch($conn) {
    // Wenn das Formular abgesendet wurde
    if (isset($_POST['submit-search'])) {
        // Benutzereingabe bereinigen
        $search = mysqli_real_escape_string($conn, $_POST['search']);

        // SQL-Abfrage erstellen
        $sql = "SELECT * FROM artist WHERE FirstName LIKE '%$search%'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            // Ergebnisse ausgeben
            while($row = mysqli_fetch_assoc($result)) {
                echo "Ergebnis: " . htmlspecialchars($row['FirstName']) . "<br>"; // Vermeidung von XSS-Angriffen
            }
        } else {
            echo "Keine Ergebnisse gefunden.";
        }
    }
}

// Aufruf der Funktion mit der Verbindung als Parameter
performSearch($conn);
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
    <form class="d-flex" method="POST" action="../Pages/search.php">
        <input class="form-control me-2" type="text" name="search" placeholder="Suche" aria-label="Search">
        <button class="btn search-btn" type="submit" name="submit-search"><i class="bi bi-search"></i></button>
    </form>
</body>
</html>

<?php
require_once("../Homepage/footer.php");
?>



