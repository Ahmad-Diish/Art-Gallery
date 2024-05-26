<?php
require_once("../Homepage/header.php");
require_once("../Datenbank/datenbank.php");
require_once("../Datenbank/artistManager.php");
require_once("../Datenbank/artistClass.php");

// Verbindung zur Datenbank herstellen
$conn = new Datenbank();
$conn->connect(); // Stellt die Verbindung her

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

    <?php
    // Wenn das Formular abgesendet wurde
    if (isset($_POST['submit-search'])) {
        // Benutzereingabe bereinigen und vor XSS-Angriffen schützen
        $search = htmlspecialchars($_POST['search']);
        $search = "%$search%";

        // Bereite ein Statement zur Ausführung vor
        $stmt = $conn->prepareStatement("SELECT * FROM artist WHERE FirstName LIKE :search OR LastName LIKE :search");
        
        // Binde die Parameter
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $queryResult = count($result);

        echo "Es wurden " . $queryResult . " Ergebnisse gefunden:";

        if ($queryResult > 0) {
            // Ergebnisse ausgeben
            foreach ($result as $row) {
                echo "<div class='artist-box'>
                    <p>" . htmlspecialchars($row['FirstName']) . "</p>
                    <p>" . htmlspecialchars($row['LastName']) . "</p>
                </div>"; 
            }
        } else {
            echo "Keine Ergebnisse gefunden.";
        }
    }
    ?>
</body>
</html>

<?php
require_once("../Homepage/footer.php");
?>






