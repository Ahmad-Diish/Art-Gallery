<?php
require_once("../Homepage/header.php");
require_once("../Datenbank/artistManager.php");
require_once("../Datenbank/artistClass.php");
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
    if (isset($_POST['submit-search'])) {
        $search = mysqli_real_escape_string($conn, $_POST['sarch']);

        // Bereite ein Statement zur Ausführung vor
        $sql = "SELECT * FROM artist WHERE FirstName LIKE '%$search%' OR LastName LIKE '%$search%'";
    
        $result = mysqli_query($conn, $sql);

        $queryResult = mysqli_num_rows($result);

        echo "Es wurden " . $queryResult . " Ergebnisse gefunden:";

        if ($queryResult > 0) {
            // Ergebnisse ausgeben
            while($row = mysqli_fetch_assoc($result)) {
                echo "<div class='artist-box'>
                    <p>".$row['FirstName']."</p>
                    <p>" .$row['LastName']."</p>
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






